<?php

namespace Modules\Boat\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Boat\Models\Boat;
use Modules\Boat\Models\BoatTerm;
use Modules\Boat\Models\BoatTranslation;
use Modules\Booking\Events\BookingUpdatedEvent;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\Enquiry;
use Modules\Core\Events\CreatedServicesEvent;
use Modules\Core\Events\UpdatedServiceEvent;
use Modules\Core\Models\Attributes;
use Modules\FrontendController;
use Modules\Location\Models\Location;

class ManageBoatController extends FrontendController
{
    protected $boatClass;
    protected $boatTranslationClass;
    protected $boatTermClass;
    protected $attributesClass;
    protected $locationClass;
    protected $bookingClass;
    protected $enquiryClass;

    public function __construct(
        Boat $boatClass,
        BoatTranslation $boatTranslationClass,
        BoatTerm $boatTermClass,
        Attributes $attributesClass,
        Location $locationClass,
        Booking $bookingClass,
        Enquiry $enquiryClass
    ) {
        parent::__construct();
        $this->boatClass = $boatClass;
        $this->boatTranslationClass = $boatTranslationClass;
        $this->attributesClass = $attributesClass;
        $this->boatTermClass = $boatTermClass;
        $this->locationClass = $locationClass;
        $this->bookingClass = $bookingClass;
        $this->enquiryClass = $enquiryClass;
    }

    public function callAction($method, $parameters)
    {
        if (!$this->boatClass::isEnable()) {
            return redirect('/');
        }
        return parent::callAction($method, $parameters); // TODO: Change the autogenerated stub
    }

    public function manageBoat(Request $request)
    {
        $this->checkPermission('boat_view');
        $user_id = Auth::id();
        $list_tour = $this->boatClass::where("author_id", $user_id)->orderBy('id', 'desc');
        $data = [
            'rows' => $list_tour->paginate(5),
            'breadcrumbs' => [
                [
                    'name' => __('Manage Boats'),
                    'url' => route('boat.vendor.index')
                ],
                [
                    'name' => __('All'),
                    'class' => 'active'
                ],
            ],
            'page_title' => __("Manage Boats"),
        ];
        return view('Boat::frontend.manageBoat.index', $data);
    }

    public function recovery(Request $request)
    {
        $this->checkPermission('boat_view');
        $user_id = Auth::id();
        $list_tour = $this->boatClass::onlyTrashed()->where("author_id", $user_id)->orderBy('id', 'desc');
        $data = [
            'rows' => $list_tour->paginate(5),
            'recovery' => 1,
            'breadcrumbs' => [
                [
                    'name' => __('Manage Boats'),
                    'url' => route('boat.vendor.index')
                ],
                [
                    'name' => __('Recovery'),
                    'class' => 'active'
                ],
            ],
            'page_title' => __("Recovery Boats"),
        ];
        return view('Boat::frontend.manageBoat.index', $data);
    }

    public function restore($id)
    {
        $this->checkPermission('boat_delete');
        $user_id = Auth::id();
        $query = $this->boatClass::onlyTrashed()->where("author_id", $user_id)->where("id", $id)->first();
        if (!empty($query)) {
            $query->restore();
            event(new UpdatedServiceEvent($query));
        }
        return redirect(route('boat.vendor.recovery'))->with('success', __('Restore boat success!'));
    }

    public function createBoat(Request $request)
    {
        $this->checkPermission('boat_create');


        $row = new $this->boatClass();
        $data = [
            'row' => $row,
            'translation' => new $this->boatTranslationClass(),
            'boat_location' => $this->locationClass::where("status", "publish")->get()->toTree(),
            'attributes' => $this->attributesClass::where('service', 'boat')->get(),
            'breadcrumbs' => [
                [
                    'name' => __('Manage Boats'),
                    'url' => route('boat.vendor.index')
                ],
                [
                    'name' => __('Create'),
                    'class' => 'active'
                ],
            ],
            'page_title' => __("Create Boats"),
        ];
        return view('Boat::frontend.manageBoat.detail', $data);
    }


    public function store(Request $request, $id)
    {
        if ($id > 0) {
            $this->checkPermission('boat_update');
            $row = $this->boatClass::find($id);
            if (empty($row)) {
                return redirect(route('boat.vendor.index'));
            }

            if ($row->author_id != Auth::id() and !$this->hasPermission('boat_manage_others')) {
                return redirect(route('boat.vendor.index'));
            }
        } else {
            $this->checkPermission('boat_create');
            $row = new $this->boatClass();
            $row->status = "publish";
            if (setting_item("boat_vendor_create_service_must_approved_by_admin", 0)) {
                $row->status = "pending";
            }
            $row->author_id = Auth::id();
        }
        $dataKeys = [
            'title',
            'content',
            'video',
            'faqs',
            'image_id',
            'banner_image_id',
            'gallery',
            'location_id',
            'address',
            'map_lat',
            'map_lng',
            'map_zoom',
            'price_per_hour',
            'price_per_day',
            'max_guest',
            'cabin',
            'length',
            'speed',
            'specs',
            'cancel_policy',
            'terms_information',
            'enable_extra_price',
            'extra_price',
            'is_featured',
            'default_state',
            'enable_service_fee',
            'service_fee',
            'min_day_before_booking',
            'include',
            'exclude',
            'start_time_booking',
            'end_time_booking',
        ];
        $row->fillByAttr($dataKeys, $request->input());
        if (!auth()->user()->checkUserPlan() and $row->status == "publish") {
            return redirect(route('user.plan'));
        }
        $row->min_price = $row->price_per_day < $row->price_per_hour ? $row->price_per_day : $row->price_per_hour;
        $row->number = 1;
        $res = $row->saveOriginOrTranslation($request->input('lang'), true);
        if ($res) {
            if (!$request->input('lang') or is_default_lang($request->input('lang'))) {
                $this->saveTerms($row, $request);
            }

            if ($id > 0) {
                event(new UpdatedServiceEvent($row));
                return back()->with('success', __('Boat updated'));
            } else {
                event(new CreatedServicesEvent($row));
                return redirect(route('boat.vendor.edit', ['id' => $row->id]))->with('success', __('Boat created'));
            }
        }
    }

    public function saveTerms($row, $request)
    {
        if (empty($request->input('terms'))) {
            $this->boatTermClass::where('target_id', $row->id)->delete();
        } else {
            $term_ids = $request->input('terms');
            foreach ($term_ids as $term_id) {
                $this->boatTermClass::firstOrCreate([
                    'term_id' => $term_id,
                    'target_id' => $row->id
                ]);
            }
            $this->boatTermClass::where('target_id', $row->id)->whereNotIn('term_id', $term_ids)->delete();
        }
    }

    public function editBoat(Request $request, $id)
    {
        $this->checkPermission('boat_update');
        $user_id = Auth::id();
        $row = $this->boatClass::where("author_id", $user_id);
        $row = $row->find($id);
        if (empty($row)) {
            return redirect(route('boat.vendor.index'))->with('warning', __('Boat not found!'));
        }
        $translation = $row->translate($request->query('lang'));
        $data = [
            'translation' => $translation,
            'row' => $row,
            'boat_location' => $this->locationClass::where("status", "publish")->get()->toTree(),
            'attributes' => $this->attributesClass::where('service', 'boat')->get(),
            "selected_terms" => $row->terms->pluck('term_id'),
            'breadcrumbs' => [
                [
                    'name' => __('Manage Boats'),
                    'url' => route('boat.vendor.index')
                ],
                [
                    'name' => __('Edit'),
                    'class' => 'active'
                ],
            ],
            'page_title' => __("Edit Boats"),
        ];
        return view('Boat::frontend.manageBoat.detail', $data);
    }

    public function deleteBoat($id)
    {
        $this->checkPermission('boat_delete');
        $user_id = Auth::id();
        if (\request()->query('permanently_delete')) {
            $query = $this->boatClass::where("author_id", $user_id)->where("id", $id)->withTrashed()->first();
            if (!empty($query)) {
                $query->forceDelete();
            }
        } else {
            $query = $this->boatClass::where("author_id", $user_id)->where("id", $id)->first();
            if (!empty($query)) {
                $query->delete();
                event(new UpdatedServiceEvent($query));
            }
        }
        return redirect(route('boat.vendor.index'))->with('success', __('Delete boat success!'));
    }

    public function bulkEditBoat($id, Request $request)
    {
        $this->checkPermission('boat_update');
        $action = $request->input('action');
        $user_id = Auth::id();
        $query = $this->boatClass::where("author_id", $user_id)->where("id", $id)->first();
        if (empty($id)) {
            return redirect()->back()->with('error', __('No item!'));
        }
        if (empty($action)) {
            return redirect()->back()->with('error', __('Please select an action!'));
        }
        if (empty($query)) {
            return redirect()->back()->with('error', __('Not Found'));
        }
        switch ($action) {
            case "make-hide":
                $query->status = "draft";
                break;
            case "make-publish":
                if (!auth()->user()->checkUserPlan()) {
                    return redirect(route('user.plan'));
                }
                $query->status = "publish";
                break;
        }
        $query->save();
        event(new UpdatedServiceEvent($query));

        return redirect()->back()->with('success', __('Update success!'));
    }

    public function bookingReportBulkEdit($booking_id, Request $request)
    {
        $status = $request->input('status');
        if (!empty(setting_item("boat_allow_vendor_can_change_their_booking_status")) and !empty($status) and !empty($booking_id)) {
            $query = $this->bookingClass::where("id", $booking_id);
            $query->where("vendor_id", Auth::id());
            $item = $query->first();
            if (!empty($item)) {
                $item->status = $status;
                $item->save();

                if ($status == $this->bookingClass::CANCELLED) {
                    $item->tryRefundToWallet();
                }
                event(new BookingUpdatedEvent($item));
                return redirect()->back()->with('success', __('Update success'));
            }
            return redirect()->back()->with('error', __('Booking not found!'));
        }
        return redirect()->back()->with('error', __('Update fail!'));
    }
}
