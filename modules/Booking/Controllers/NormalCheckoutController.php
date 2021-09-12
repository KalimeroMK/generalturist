<?php


    namespace Modules\Booking\Controllers;


    use Illuminate\Contracts\Foundation\Application;
    use Illuminate\Contracts\View\Factory;
    use Illuminate\Contracts\View\View;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\Request;

    class NormalCheckoutController extends BookingController
    {
        /**
         * @return Application|Factory|View
         */
        public function showInfo()
        {
            return view("Booking::frontend.normal-checkout.info");
        }

        /**
         * @param  Request  $request
         * @param $gateway
         * @return RedirectResponse
         */
        public function confirmPayment(Request $request, $gateway): RedirectResponse
        {
            $gateways = get_payment_gateways();
            if (empty($gateways[$gateway]) or !class_exists($gateways[$gateway])) {
                return $this->sendError(__("Payment gateway not found"));
            }
            $gatewayObj = new $gateways[$gateway]($gateway);
            if (!$gatewayObj->isAvailable()) {
                return $this->sendError(__("Payment gateway is not available"));
            }
            $res = $gatewayObj->confirmNormalPayment($request);
            $status = $res[0] ?? null;
            $message = $res[1] ?? null;
            $redirect_url = $res[2] ?? null;

            if (empty($redirect_url)) {
                $redirect_url = route('gateway.info');
            }

            return redirect()->to($redirect_url)->with($status ? "success" : "error", $message);
        }

        /**
         * @param $message
         * @param  array  $data
         * @return RedirectResponse
         */
        public function sendError($message, $data = []): RedirectResponse
        {
            return redirect()->to(route('gateway.info'))->with('error', $message);
        }

        /**
         * @param  Request  $request
         * @param $gateway
         * @return RedirectResponse
         */
        public function cancelPayment(Request $request, $gateway): RedirectResponse
        {
            $gateways = get_payment_gateways();
            if (empty($gateways[$gateway]) or !class_exists($gateways[$gateway])) {
                return $this->sendError(__("Payment gateway not found"));
            }
            $gatewayObj = new $gateways[$gateway]($gateway);
            if (!$gatewayObj->isAvailable()) {
                return $this->sendError(__("Payment gateway is not available"));
            }
            $res = $gatewayObj->cancelNormalPayment($request);
            $status = $res[0] ?? null;
            $message = $res[1] ?? null;
            $redirect_url = $res[2] ?? null;

            if (empty($redirect_url)) {
                $redirect_url = route('gateway.info');
            }

            return redirect()->to($redirect_url)->with($status ? "success" : "error", $message);
        }
    }
