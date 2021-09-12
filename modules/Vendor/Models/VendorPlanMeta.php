<?php

    namespace Modules\Vendor\Models;

    use App\BaseModel;
    use Illuminate\Database\Eloquent\SoftDeletes;

    class VendorPlanMeta extends BaseModel
    {
        use SoftDeletes;

        protected $table = 'core_vendor_plan_meta';
        protected $fillable = [
            'post_type',
            'enable',
            'maximum_create',
            'auto_publish',
            'commission',
            'vendor_plan_id',
        ];

        public static function getModelName()
        {
            return __("Vendor Plan Meta");
        }

        public function plan()
        {
            return $this->belongsTo(VendorPlan::class, 'vendor_plan_id');
        }
    }
