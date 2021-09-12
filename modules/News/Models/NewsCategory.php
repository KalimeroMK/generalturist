<?php

    namespace Modules\News\Models;

    use App\BaseModel;
    use Illuminate\Database\Eloquent\SoftDeletes;
    use Kalnoy\Nestedset\NodeTrait;

    class NewsCategory extends BaseModel
    {
        use SoftDeletes;
        use NodeTrait;

        protected $table = 'core_news_category';
        protected $fillable = [
            'name',
            'content',
            'status',
            'parent_id',
        ];
        protected $slugField = 'slug';
        protected $slugFromField = 'name';
        protected $seo_type = 'news_category';

        public static function getModelName()
        {
            return __("News Category");
        }

        public static function searchForMenu($q = false)
        {
            $query = static::select('id', 'name');
            if (strlen($q)) {
                $query->where('title', 'name', "%".$q."%");
            }
            $a = $query->limit(10)->get();
            return $a;
        }

        public function filterbyCat($id)
        {
            $posts = News::where('news_id', $this->id)->get();
            return $posts;
        }

        public function dataForApi()
        {
            $translation = $this->translateOrOrigin(app()->getLocale());
            return [
                'name' => $translation->name,
                'id'   => $this->id,
                'url'  => $this->getDetailUrl(),
            ];
        }

        public function getDetailUrl($locale = false)
        {
            return route('news.category.index', ['slug' => $this->slug]);
        }

    }
