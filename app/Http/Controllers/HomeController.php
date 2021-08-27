<?php
namespace App\Http\Controllers;

use App\User;
use Modules\Hotel\Models\Hotel;
use Modules\Location\Models\LocationCategory;
use Modules\Page\Models\Page;
use Modules\News\Models\NewsCategory;
use Modules\News\Models\Tag;
use Modules\News\Models\News;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $home_page_id = setting_item('home_page_id');
        $home_hotel_id = setting_item('home_hotel_id');
        if($home_hotel_id && $row = Hotel::where("id",$home_hotel_id)->where("status","publish")->first())
        {
            $translation = $row->translateOrOrigin(app()->getLocale());
            $hotel_related = [];
            $location_id = $row->location_id;
            if (!empty($location_id)) {
                $hotel_related = Hotel::where('location_id', $location_id)->where("status", "publish")->take(4)->whereNotIn('id', [$row->id])->with(['location','translations','hasWishList'])->get();
            }
            $review_list = $row->getReviewList();
            $data = [
                'row'          => $row,
                'translation'       => $translation,
                'hotel_related' => $hotel_related,
                'location_category'=>LocationCategory::where("status", "publish")->with('location_category_translations')->get(),
                'booking_data' => $row->getBookingData(),
                'review_list'  => $review_list,
                'seo_meta'  => $row->getSeoMetaWithTranslation(app()->getLocale(),$translation),
                'body_class'=>'is_single'
            ];
            $this->setActiveMenu($row);
            return view('Hotel::frontend.detail',$data);
        }
        if($home_page_id && $page = Page::where("id",$home_page_id)->where("status","publish")->first())
        {
            $this->setActiveMenu($page);
            $translation = $page->translateOrOrigin(app()->getLocale());
            $seo_meta = $page->getSeoMetaWithTranslation(app()->getLocale(), $translation);
            $seo_meta['full_url'] = url("/");
            $seo_meta['is_homepage'] = true;
            $data = [
                'row'=>$page,
                "seo_meta"=> $seo_meta,
                'translation'=>$translation
            ];
            return view('Page::frontend.detail',$data);
        }
        $model_News = News::where("status", "publish");
        $data = [
            'rows'=>$model_News->paginate(5),
            'model_category'    => NewsCategory::where("status", "publish"),
            'model_tag'         => Tag::query(),
            'model_news'        => News::where("status", "publish"),
            'breadcrumbs' => [
                ['name' => __('News'), 'url' => url("/news") ,'class' => 'active'],
            ],
            "seo_meta" => News::getSeoMetaForPageList()
        ];
        return view('News::frontend.index',$data);
    }

    public function checkConnectDatabase(Request $request){
        $connection = $request->input('database_connection');
        config([
            'database' => [
                'default' => $connection."_check",
                'connections' => [
                    $connection."_check" => [
                        'driver' => $connection,
                        'host' => $request->input('database_hostname'),
                        'port' => $request->input('database_port'),
                        'database' => $request->input('database_name'),
                        'username' => $request->input('database_username'),
                        'password' => $request->input('database_password'),
                    ],
                ],
            ],
        ]);
        try {
            DB::connection()->getPdo();
            $check = DB::table('information_schema.tables')->where("table_schema","performance_schema")->get();
            if(empty($check) and $check->count() == 0){
                return $this->sendSuccess(false , __("Access denied for user!. Please check your configuration."));
            }
            if(DB::connection()->getDatabaseName()){
                return $this->sendSuccess(false , __("Yes! Successfully connected to the DB: ".DB::connection()->getDatabaseName()));
            }else{
                return $this->sendSuccess(false , __("Could not find the database. Please check your configuration."));
            }
        } catch (\Exception $e) {
            return $this->sendError( $e->getMessage() );
        }
    }
}
