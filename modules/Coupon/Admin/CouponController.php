<?php

namespace Modules\Coupon\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\AdminController;
use Modules\Booking\Models\Service;
use Modules\Coupon\Models\Coupon;
use Modules\Coupon\Models\CouponServices;

class CouponController extends AdminController
{
    public function __construct()
    {
        $this->setActiveMenu(route('coupon.admin.index'));
    }

    public function callAction($method, $parameters)
    {
        return parent::callAction($method, $parameters); // TODO: Change the autogenerated stub
    }

    public function index(Request $request)
    {
        $this->checkPermission('coupon_view');

        $query = Coupon::query();

        $query->orderBy('id', 'desc');
        if (!empty($coupon_name = $request->input('s'))) {
            $query->where('name', 'LIKE', '%'.$coupon_name.'%');
            $query->orWhere('code', $coupon_name);
        }

        $data = [
            'rows' => $query->with(['author'])->paginate(20),
            'breadcrumbs' => [
                [
                    'name' => __('Coupon Management'),
                    'class' => 'active'
                ],
            ],
            'page_title' => __("Coupon Management"),
        ];
        return view('Coupon::admin.index', $data);
    }

    public function edit(Request $request, $id)
    {
        $this->checkPermission('coupon_update');

        $row = Coupon::find($id);
        if (empty($row)) {
            return redirect(route('coupon.admin.index'));
        }

        $data = [
            'row' => $row,
            'breadcrumbs' => [
                [
                    'name' => __('All Coupons'),
                    'url' => route('coupon.admin.index')
                ],
                [
                    'name' => __('Edit Coupon: :name', ['name' => $row->code]),
                ],
            ],
            'page_title' => __("Edit: :name", ['name' => $row->code]),
        ];
        return view('Coupon::admin.detail', $data);
    }

    public function create(Request $request)
    {
        $this->checkPermission('coupon_create');

        $row = new Coupon();
        $data = [
            'row' => $row,
            'breadcrumbs' => [
                [
                    'name' => __('All Coupons'),
                    'url' => route('coupon.admin.index')
                ],
                [
                    'name' => __('Create Coupon'),
                ],
            ],
            'page_title' => __('Create Coupon'),
        ];
        return view('Coupon::admin.detail', $data);
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'code' => [
                'required',
                'max:50',
                'string',
                'alpha_dash',
                Rule::unique('bravo_coupons')->ignore($id > 0 ? $id : false)
            ],
            'amount' => ['required'],
        ]);

        if ($id > 0) {
            $this->checkPermission('coupon_update');
            $row = Coupon::find($id);
            if (empty($row)) {
                return redirect(route('coupon.admin.index'));
            }
        } else {
            $this->checkPermission('coupon_create');
            $row = new Coupon();
            $row->status = "publish";
        }

        $dataKeys = [
            'name',
            'code',
            'status',
            'amount',
            'discount_type',
            'end_date',
            'min_total',
            'max_total',
            'services',
            'only_for_user',
            'quantity_limit',
            'limit_per_user',
            'image_id'
        ];

        $row->fillByAttr($dataKeys, $request->input());

        //Save Coupon Product
        $services = $request->input('services');
        $coupon_product = new CouponServices();
        $coupon_product->clean($row->id);
        if (!empty($services) and is_array($services)) {
            $services = Service::selectRaw('id,object_id,object_model')->whereIn('id', $services)->get();
            foreach ($services as $service) {
                $coupon_product = new CouponServices();
                $coupon_product->fill([
                    'coupon_id' => $row->id,
                    'object_id' => $service->object_id,
                    'object_model' => $service->object_model,
                    'service_id' => $service->id,
                ]);
                $coupon_product->save();
            }
        }
        $res = $row->save();
        if ($res) {
            if ($id > 0) {
                return redirect()->back()->with('success', __('Coupon updated'));
            } else {
                return redirect()->to(route('coupon.admin.index'))->with('success', __('Coupon created'));
            }
        }
    }

    public function bulkEdit(Request $request)
    {
        $ids = $request->input('ids');
        $action = $request->input('action');
        if (empty($ids) or !is_array($ids)) {
            return redirect()->back()->with('error', __('No items selected!'));
        }
        if (empty($action)) {
            return redirect()->back()->with('error', __('Please select an action!'));
        }
        switch ($action) {
            case "delete":
                foreach ($ids as $id) {
                    $query = Coupon::query()->where("id", $id);
                    $this->checkPermission('coupon_delete');
                    $query->first();
                    if (!empty($query)) {
                        $query->delete();
                    }
                }
                return redirect()->back()->with('success', __('Deleted success!'));
                break;
            case "clone":
                $this->checkPermission('coupon_create');
                foreach ($ids as $id) {
                    (new Coupon())->saveCloneByID($id);
                }
                return redirect()->back()->with('success', __('Clone success!'));
                break;
            default:
                // Change status
                foreach ($ids as $id) {
                    $query = Coupon::query()->where("id", $id);
                    $this->checkPermission('coupon_update');
                    $query->update(['status' => $action]);
                }
                return redirect()->back()->with('success', __('Update success!'));
                break;
        }
    }

    function getServiceForSelect2(Request $request)
    {
        $q = $request->query('q');
        $query = Service::select('*');
        if ($q) {
            $query->where(function ($query) use ($q) {
                $query->where('title', 'like', '%'.$q.'%')
                    ->orWhere('id', $q);
            });
        }
        $res = $query->orderBy('id', 'desc')->orderBy('title', 'asc')->limit(20)->get();
        $data = [];
        if (!empty($res)) {
            foreach ($res as $item) {
                $data[] = [
                    'id' => $item->id,
                    'text' => strtoupper($item->object_model)." (#{$item->object_id}): {$item->title}",
                ];
            }
        }
        return response()->json([
            'results' => $data
        ]);
    }
}
