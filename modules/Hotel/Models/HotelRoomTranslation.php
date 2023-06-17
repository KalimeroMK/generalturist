<?php

namespace Modules\Hotel\Models;

use App\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Booking\Models\Booking;
use Modules\Review\Models\Review;
use Modules\User\Models\UserWishList;

class HotelRoomTranslation extends BaseModel
{
    use SoftDeletes;

    public $type = 'hotel_room_translation';
    protected $table = 'bravo_hotel_room_translations';
    protected $fillable = [
        'title',
        'content',
        'status',
    ];

    protected $seo_type = 'hotel_room_translation';


    protected $bookingClass;
    protected $reviewClass;
    protected $hotelDateClass;
    protected $hotelRoomTermClass;
    protected $hotelTranslationClass;
    protected $userWishListClass;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->bookingClass = Booking::class;
        $this->reviewClass = Review::class;
        $this->hotelRoomTermClass = HotelRoomTerm::class;
        $this->hotelTranslationClass = HotelTranslation::class;
        $this->userWishListClass = UserWishList::class;
    }

    public static function getModelName()
    {
        return __("Hotel Room");
    }

    public static function getTableName()
    {
        return with(new static)->table;
    }


    public function terms()
    {
        return $this->hasMany($this->hotelRoomTermClass, "target_id");
    }
}
