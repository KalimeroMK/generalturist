<?php


namespace Modules\Booking\Gateways;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;
use Modules\Booking\Gateways\BaseGateway;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\Payment;

class StripeCheckoutGateway extends BaseGateway
{
    protected $id = 'stripe_checkout';

    public $name = 'Stripe Checkout V2';

    protected $gateway;

    public function getOptionsConfigs()
    {
        return [
            [
                'type'  => 'checkbox',
                'id'    => 'enable',
                'label' => __('Enable Stripe Checkout V2?')
            ],
            [
                'type'  => 'input',
                'id'    => 'name',
                'label' => __('Custom Name'),
                'std'   => __("Stripe"),
                'multi_lang' => "1"
            ],
            [
                'type'  => 'upload',
                'id'    => 'logo_id',
                'label' => __('Custom Logo'),
            ],
            [
                'type'  => 'editor',
                'id'    => 'html',
                'label' => __('Custom HTML Description'),
                'multi_lang' => "1"
            ],
            [
                'type'       => 'input',
                'id'        => 'stripe_secret_key',
                'label'     => __('Secret Key'),
            ],
            [
                'type'       => 'input',
                'id'        => 'stripe_publishable_key',
                'label'     => __('Publishable Key'),
            ],
            [
                'type'       => 'checkbox',
                'id'        => 'stripe_enable_sandbox',
                'label'     => __('Enable Sandbox Mode'),
            ],
            [
                'type'       => 'input',
                'id'        => 'stripe_test_secret_key',
                'label'     => __('Test Secret Key'),
            ],
            [
                'type'       => 'input',
                'id'        => 'stripe_test_publishable_key',
                'label'     => __('Test Publishable Key'),
            ],
            [
                'type'       => 'input',
                'id'        => 'endpoint_secret',
                'label'     => __('Endpoint Secret for Webhooks'),
            ]
        ];
    }

    public function getGatewayMethods(){
        return [
            'alipay'=>[
                'label'=>__('Pay with Alipay'),
                'logo'=>asset('images/stripe/alipay.png')
            ]
        ];
    }

    public function process(Request $request, $booking, $service)
    {
        $this->setupStripe();

        if (in_array($booking->status, [
            $booking::PAID,
            $booking::COMPLETED,
            $booking::CANCELLED
        ])) {

            throw new Exception(__("Booking status does need to be paid"));
        }
        if (!$booking->pay_now) {
            throw new Exception(__("Booking total is zero. Can not process payment gateway!"));
        }
        $payment = new Payment();
        $payment->booking_id = $booking->id;
        $payment->payment_gateway = $this->id;
        $payment->status = 'draft';
        $payment->amount = (float) $booking->pay_now;

        if($stripe_customer_id = auth()->user()->stripe_customer_id){
            $stripe_customer_id = $this->tryCreateUser($booking);
        }

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => [$this->current_gateway_method],
            //'mode' => 'payment',
            'customer' => $stripe_customer_id,
            'success_url' => $this->getReturnUrl() . '?c=' . $booking->code.'&session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $this->getCancelUrl() . '?c=' . $booking->code,
            'line_items'=>[
                [
                    'price_data'=>[
                        'currency'=>setting_item('currency_main'),
                        'product_data'=>[
                            'name'=>$booking->service->title ?? '',
                            'images'=>[get_file_url($booking->service->image_id ?? '')]
                        ],
                        'unit_amount'=>(float) $booking->pay_now * 100
                    ],
                    'quantity'=>1
                ]
            ]
        ]);
        $payment->stripe_session_id = $session->id;
        $payment->save();

        $booking->status = $booking::UNPAID;
        $booking->payment_id = $payment->id;
        $booking->stripe_session_id = $session->id;
        $booking->save();

        return response()->json([
            'url' => route('stripe.go',['code'=>$session->id])
        ])->send();
    }

    public function tryCreateUser(Booking $booking){

        $customer = \Stripe\Customer::create([
            'address'=>$booking->address,
            'email'=>$booking->email,
            'phone'=>$booking->phone,
            'name'=>$booking->first_name.' '.$booking->last_name,
        ]);

        $user = auth()->user();
        $user->stripe_customer_id = $customer->id;
        $user->save();

        return $customer->id;

    }

    public function cancelPayment(Request $request)
    {
        $c = $request->query('c');
        $booking = Booking::where('code', $c)->first();
        if (!empty($booking) and in_array($booking->status, [$booking::UNPAID])) {
            $payment = $booking->payment;
            if ($payment) {
                $payment->status = 'cancel';
                $payment->logs = \GuzzleHttp\json_encode([
                    'customer_cancel' => 1
                ]);
                $payment->save();
            }

            // Refund without check status
            $booking->tryRefundToWallet(false);

            return redirect($booking->getDetailUrl())->with("error", __("You cancelled the payment"));
        }
        if (!empty($booking)) {
            return redirect($booking->getDetailUrl());
        } else {
            return redirect(url('/'));
        }
    }

    public function setupStripe(){
        \Stripe\Stripe::setApiKey($this->getSecretKey());
    }

    public function getPublicKey(){
        if($this->getOption('stripe_enable_sandbox'))
        {
            return $this->getOption('stripe_test_publishable_key');
        }
        return $this->getOption('stripe_public_key');
    }

    public function getSecretKey(){
        if($this->getOption('stripe_enable_sandbox'))
        {
            return $this->getOption('stripe_test_secret_key');
        }
        return $this->getOption('stripe_secret_key');
    }

    public function confirmPayment(Request $request)
    {
        $c = $request->query('c');
        $booking = Booking::where('code', $c)->first();
        $this->setupStripe();

        if (!empty($booking) and in_array($booking->status, [$booking::UNPAID])) {

            $session_id = $request->query('session_id');
            if(empty($session_id)){
                return redirect($booking->getDetailUrl(false));
            }

            $session = \Stripe\Checkout\Session::retrieve($session_id);
            if(empty($session)){
                return redirect($booking->getDetailUrl(false));
            }

            if($session->payment_status == 'paid'){
                $booking->pay_now = 0;
                $booking->stripe_setup_intent = $session->setup_intent;
                $booking->stripe_cs_complete = 1;
                $booking->markAsPaid();
                $booking->addMeta('session_data',$session);
            }
            if($session->payment_status == 'no_payment_required'){
                $booking->pay_now = 0;
                $booking->stripe_setup_intent = $session->setup_intent;
                $booking->stripe_cs_complete = 1;
                $booking->save();
                $booking->addMeta('session_data',$session);
            }

            return redirect($booking->getDetailUrl(false));

        }
        if (!empty($booking)) {
            return redirect($booking->getDetailUrl(false));
        } else {
            return redirect(url('/'));
        }
    }
}
