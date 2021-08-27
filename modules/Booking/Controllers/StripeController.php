<?php


namespace Custom\Booking\Controllers;


use Custom\Booking\Gateways\StripeCheckoutGateway;
use Mockery\Exception;
use Modules\Booking\Models\Booking;
use Modules\FrontendController;

class StripeController extends FrontendController
{

    public function go($code = ''){

        $gw = new StripeCheckoutGateway();

        $public_key = $gw->getPublicKey();

        return view('Booking::frontend.stripe.go',['code'=>$code,'public_key'=>$public_key]);
    }

    public function webhooks(){
        $gw = new StripeCheckoutGateway();
        $gw->setupStripe();

        $payload = @file_get_contents('php://input');
        $event = null;
        $endpoint_secret = $gw->getOption('endpoint_secret','');

        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            echo 'Webhook error while parsing basic request.';
            http_response_code(400);
            exit();
        }
        if ($endpoint_secret) {
            // Only verify the event if there is an endpoint secret defined
            // Otherwise use the basic decoded event
            $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
            try {
                $event = \Stripe\Webhook::constructEvent(
                    $payload, $sig_header, $endpoint_secret
                );
            } catch(\Stripe\Exception\SignatureVerificationException $e) {
                // Invalid signature
                echo '⚠️  Webhook error while validating signature.';
                http_response_code(400);
                exit();
            }
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
                // Then define and call a method to handle the successful payment intent.
                //$this->handlePaymentSuccess($paymentIntent);
                break;
            case 'checkout.session.completed':
            case "checkout.session.async_payment_succeeded":
                $session = $event->data->object;
                $this->handleCompletedCheckoutSession($session);
                break;
            case "checkout.session.async_payment_failed":
                $session = $event->data->object;
                $this->handleFailCheckoutSession($session);
                break;
            case "account.updated":
                $account = $event->data->object;
                //$this->handleAccountUpdated($account);
                break;
            default:
                // Unexpected event type
                echo 'Received unknown event type';
        }
        http_response_code(200);
    }

    public function handleCompletedCheckoutSession($session){
        $booking = \Custom\Booking\Models\Booking::query()->where('stripe_session_id',$session->id)->first();
        if(!$booking or in_array($booking->status,[
                $booking::PAID,
                $booking::COMPLETED,
                $booking::CANCELLED
            ])){
            return;
        };
        if($session->payment_status == 'paid') {
            $booking->pay_now = 0;
            $booking->stripe_setup_intent = $session->setup_intent ?? '';
            $booking->markAsPaid();
            $booking->stripe_cs_complete = 1;
            $booking->addMeta('session_data', $session);
        }

    }
    public function handleFailCheckoutSession($session){
        $booking = \Custom\Booking\Models\Booking::query()->where('stripe_session_id',$session->id)->first();
        if(!$booking or in_array($booking->status,[
                $booking::PAID,
                $booking::COMPLETED,
                $booking::CANCELLED
            ])){
            return;
        };
        $booking->markAsPaymentFailed();

    }
}
