<?php

namespace Modules\Vendor\Models;

use App\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorPlan extends BaseModel
{
    use SoftDeletes;

    protected $table = 'core_vendor_plans';
    protected $fillable = [
        'name',
        'base_commission',
        'status',
    ];

    public static function getModelName()
    {
        return __("Vendor Plans");
    }

    public static function getAsMenuItem($id)
    {
        return parent::select('id', 'name')->find($id);
    }

    public static function searchForMenu($q = false)
    {
        $query = static::select('id', 'name');
        if (strlen($q)) {
            $query->where('name', 'like', "%".$q."%");
        }
        $a = $query->orderBy('id', 'desc')->limit(10)->get();
        return $a;
    }

    public function meta()
    {
        return $this->hasMany(VendorPlanMeta::class);
    }

    public function getEditUrlAttribute()
    {
        return null;
    }
}
