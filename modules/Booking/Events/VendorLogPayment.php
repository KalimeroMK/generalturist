<?php

    namespace Modules\Booking\Events;

    use Illuminate\Queue\SerializesModels;
    use Modules\Booking\Models\Booking;

    class VendorLogPayment
    {
        use SerializesModels;

        public Booking $booking;

        public function __construct(Booking $booking)
        {
            $this->booking = $booking;
        }
    }
