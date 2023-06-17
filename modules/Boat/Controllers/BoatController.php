<?php

namespace Modules\Boat\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Modules\Boat\Models\Boat;
use Modules\Core\Models\Attributes;
use Modules\Location\Models\Location;

class BoatController extends Controller
{
    protected $boatClass;
    protected $locationClass;

    public function __construct(Boat $boatClass, Location $locationClass)
    {
        $this->boatClass = $boatClass;
        $this->locationClass = $locationClass;
    }

    public function callAction($method, $parameters)
    {
        if (!$this->boatClass::isEnable()) {
            return redirect('/');
        }
        return parent::callAction($method, $parameters); // TODO: Change the autogenerated stub
    }

    public function index(Request $request)
    {
        $is_ajax = $request->query('_ajax');

        if (!empty($request->query('limit'))) {
            $limit = $request->query('limit');
        } else {
            $limit = !empty(setting_item("boat_page_limit_item")) ? setting_item("boat_page_limit_item") : 9;
        }

        $query = $this->boatClass->search($request->input());
        $list = $query->paginate($limit);

        $markers = [];
        if (!empty($list)) {
            foreach ($list as $row) {
                $markers[] = [
                    "id" => $row->id,
                    "title" => $row->title,
                    "lat" => (float) $row->map_lat,
                    "lng" => (float) $row->map_lng,
                    "gallery" => $row->getGallery(true),
                    "infobox" => view('Boat::frontend.layouts.search.loop-grid',
                        ['row' => $row, 'disable_lazyload' => 1, 'wrap_class' => 'infobox-item'])->render(),
                    'marker' => get_file_url(setting_item("boat_icon_marker_map"),
                            'full') ?? url('images/icons/png/pin.png'),
                ];
            }
        }
        $limit_location = 15;
        if (empty(setting_item("boat_location_search_style")) or setting_item("boat_location_search_style") == "normal") {
            $limit_location = 1000;
        }
        $data = [
            'rows' => $list,
            'list_location' => $this->locationClass::where('status',
                'publish')->limit($limit_location)->with(['translation'])->get()->toTree(),
            'boat_min_max_price' => $this->boatClass::getMinMaxPrice(),
            'markers' => $markers,
            "blank" => setting_item('search_open_tab') == "current_tab" ? 0 : 1,
            "seo_meta" => $this->boatClass::getSeoMetaForPageList()
        ];
        $layout = setting_item("boat_layout_search", 'normal');
        if ($request->query('_layout')) {
            $layout = $request->query('_layout');
        }
        if ($is_ajax) {
            return $this->sendSuccess([
                'html' => view('Boat::frontend.layouts.search-map.list-item', $data)->render(),
                "markers" => $data['markers']
            ]);
        }
        $data['attributes'] = Attributes::where('service', 'boat')->orderBy("position", "desc")->with([
            'terms', 'translation'
        ])->get();

        if ($layout == "map") {
            $data['body_class'] = 'has-search-map';
            $data['html_class'] = 'full-page';
            return view('Boat::frontend.search-map', $data);
        }
        $data['layout'] = $layout;
        return view('Boat::frontend.search', $data);
    }

    public function detail(Request $request, $slug)
    {
        $row = $this->boatClass::where('slug', $slug)->with(['location', 'translation', 'hasWishList'])->first();
        if (empty($row) or !$row->hasPermissionDetailView()) {
            return redirect('/');
        }
        $translation = $row->translate();
        $boat_related = [];
        $location_id = $row->location_id;
        if (!empty($location_id)) {
            $boat_related = $this->boatClass::where('location_id', $location_id)->where("status",
                "publish")->take(4)->whereNotIn('id', [$row->id])->with([
                'location', 'translation', 'hasWishList'
            ])->get();
        }
        $review_list = $row->getReviewList();
        $data = [
            'row' => $row,
            'translation' => $translation,
            'boat_related' => $boat_related,
            'booking_data' => $row->getBookingData(),
            'review_list' => $review_list,
            'seo_meta' => $row->getSeoMetaWithTranslation(app()->getLocale(), $translation),
            'body_class' => 'is_single',
            'breadcrumbs' => [
                [
                    'name' => __('Boat'),
                    'url' => route('boat.search'),
                ],
            ],
        ];
        $data['breadcrumbs'] = array_merge($data['breadcrumbs'], $row->locationBreadcrumbs());
        $data['breadcrumbs'][] = [
            'name' => $translation->title,
            'class' => 'active'
        ];
        $this->setActiveMenu($row);
        return view('Boat::frontend.detail', $data);
    }
}
