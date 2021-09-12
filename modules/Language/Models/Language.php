<?php

    namespace Modules\Language\Models;

    use App;
    use App\BaseModel;
    use Illuminate\Database\Eloquent\SoftDeletes;
    use Illuminate\Support\Facades\Cache;

    class Language extends BaseModel
    {
        use SoftDeletes;

        protected $table = 'core_languages';
        protected $fillable = [
            'locale',
            'name',
            'active',
            'flag',
            'status',
        ];

        public static function getActive($withCurrent = true)
        {
            $value = Cache::rememberForever('locale_active_'.((int)$withCurrent), function () use ($withCurrent) {
                $q = parent::query();
                if (!$withCurrent) {
                    $q->where('locale', '!=', App::getLocale());
                }
                return $q->where('status',
                    'publish')->orderByRaw('CASE WHEN (locale = \''.e(setting_item('site_locale')).'\') THEN 0 ELSE 1 END')->get();
            });
            return $value;
        }

        public static function findByLocale($locale)
        {
            $value = Cache::rememberForever('locale_'.$locale, function () use ($locale) {
                return Language::where('locale', $locale)->first();
            });
            return $value;
        }

        public function getTranslatedNumberAttribute()
        {
            $count = Translation::where('locale', $this->locale)->whereRaw(" IFNULL(string,'') != '' ")->count();
            return $count;
        }

        public function save(array $options = [])
        {
            $res = parent::save($options); // TODO: Change the autogenerated stub

            if ($res) {
                Cache::forget('locale_'.$this->locale);
                Cache::forget('locale_active_0');
                Cache::forget('locale_active_1');
            }

            return $res;
        }

        public function delete()
        {
            $locale = $this->locale;

            $res = parent::delete(); // TODO: Change the autogenerated stub

            if ($res) {
                Cache::forget('locale_'.$locale);
                Cache::forget('locale_active_0');
                Cache::forget('locale_active_1');
            }

            return $res;
        }

        public function update(array $attributes = [], array $options = [])
        {
            $old = $this->locale;

            $res = parent::update($attributes, $options); // TODO: Change the autogenerated stub

            if ($res) {
                Cache::forget('locale_'.$old);
                if ($old != $this->locale) {
                    Cache::forget('locale_'.$this->locale);
                }
                Cache::forget('locale_active_0');
                Cache::forget('locale_active_1');
            }

            return $res;
        }
    }