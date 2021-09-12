<?php

    namespace Modules\User\Controllers;

    use Illuminate\Support\Facades\Auth;
    use Modules\Booking\Models\Booking;
    use Modules\FrontendController;
    use Modules\User\Models\Newsletter;

    class BookingController extends FrontendController
    {

        public function __construct()
        {
            parent::__construct();
        }


        public function bookingInvoice($code)
        {
            $booking = Booking::where('code', $code)->first();
            $user_id = Auth::id();
            if (empty($booking)) {
                return redirect('user/booking-history');
            }
            if ($booking->customer_id != $user_id and $booking->vendor_id != $user_id) {
                return redirect('user/booking-history');
            }
            $data = [
                'booking'    => $booking,
                'service'    => $booking->service,
                'page_title' => __("Invoice"),
            ];
            return view('User::frontend.bookingInvoice', $data);
        }

        public function ticket($code = '')
        {
            $booking = Booking::where('code', $code)->first();
            $user_id = Auth::id();
            if (empty($booking)) {
                return redirect('user/booking-history');
            }
            if ($booking->customer_id != $user_id and $booking->vendor_id != $user_id) {
                return redirect('user/booking-history');
            }
            $data = [
                'booking'    => $booking,
                'service'    => $booking->service,
                'page_title' => __("Ticket"),
            ];
            return view('User::frontend.booking.ticket', $data);
        }

    }
