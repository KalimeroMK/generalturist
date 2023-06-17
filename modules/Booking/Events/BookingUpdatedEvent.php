<?php

namespace Modules\Booking\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Booking\Models\Booking;

class BookingUpdatedEvent
{
    use SerializesModels;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }
}
