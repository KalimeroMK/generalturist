<?php

    namespace Modules\Hotel\Admin;

    use Illuminate\Support\Facades\Auth;

    class AvailabilityController extends \Modules\Hotel\Controllers\AvailabilityController
    {
        protected $indexView = 'Hotel::admin.room.availability';

        public function __construct()
        {
            parent::__construct();
            $this->setActiveMenu('admin/module/hotel');
            $this->middleware('dashboard');
        }

        protected function hasHotelPermission($hotel_id = false)
        {
            if (empty($hotel_id)) {
                return false;
            }

            $hotel = $this->hotelClass::find($hotel_id);
            if (empty($hotel)) {
                return false;
            }

            if (!$this->hasPermission('hotel_manage_others') and $hotel->create_user != Auth::id()) {
                return false;
            }

            $this->currentHotel = $hotel;
            return true;
        }
    }