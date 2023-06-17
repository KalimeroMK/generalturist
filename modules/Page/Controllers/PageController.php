<?php

namespace Modules\Page\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Modules\Page\Models\Page;
use Modules\Page\Models\PageTranslation;

class PageController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $data = [
            'rows' => Page::paginate(20)
        ];
        return view('Page::frontend.index', $data);
    }

    /**
     * @return Factory|View
     */
    public function detail()
    {
        /**
         * @var Page $page
         * @var PageTranslation $translation
         */
        $slug = request()->route('slug');

        $page = Page::where('slug', $slug)->first();

        if (empty($page) || !$page->is_published) {
            abort(404);
        }
        $translation = $page->translate();
        $data = [
            'row' => $page,
            'translation' => $translation,
            'seo_meta' => $page->getSeoMetaWithTranslation(app()->getLocale(), $translation),
            'body_class' => "page",
        ];
        if (!empty($page->header_style) and $page->header_style == "transparent") {
            $data['header_transparent'] = true;
        }
        return view('Page::frontend.detail', $data);
    }
}
