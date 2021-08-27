<?php
namespace Modules\Tour\Blocks;

use Modules\Template\Blocks\BaseBlock;

use Modules\Media\Helpers\FileHelper;

use Modules\Tour\Models\TourCategory;

class BoxCategoryTour extends BaseBlock
{
    function __construct()
    {
        $this->setOptions([
            'settings' => [
                [
                    'id'        => 'title',
                    'type'      => 'input',
                    'inputType' => 'text',
                    'label'     => __('Title')
                ],
                [
                    'id'        => 'desc',
                    'type'      => 'input',
                    'inputType' => 'text',
                    'label'     => __('Desc')
                ],
                [
                    'id'          => 'list_item',
                    'type'        => 'listItem',
                    'label'       => __('List Item(s)'),
                    'title_field' => 'title',
                    'settings'    => [
                        [
                            'id'      => 'category_id',
                            'type'    => 'select2',
                            'label'   => __('Select Category'),
                            'select2' => [
                                'ajax'  => [
                                    'url'      => url('/admin/module/tour/category/getForSelect2'),
                                    'dataType' => 'json'
                                ],
                                'width' => '100%',
                                'allowClear' => 'true',
                                'placeholder' => __('-- Select --')
                            ],
                            'pre_selected'=>url('/admin/module/tour/category/getForSelect2?pre_selected=1')
                        ],
                        [
                            'id'    => 'image_id',
                            'type'  => 'uploader',
                            'label' => __('Image Background')
                        ],
                    ]
                ],
            ],
            'category'=>__("Service Tour")
        ]);
    }

    public function getName()
    {
        return __('Tour: Box Category');
    }

    public function content($model = [])
    {
        if(!empty($model['list_item'])){
            $ids = collect($model['list_item'])->pluck('category_id');
            $categories = TourCategory::query()->whereIn("id",$ids)->get();
            $model['categories'] = $categories;
        }
        return view('Tour::frontend.blocks.box-category-tour.index', $model);
    }

    public function contentAPI($model = []){
        if(!empty($model['list_item'])){
            foreach ( $model['list_item'] as &$item ){
                $item['image_id_url'] = FileHelper::url($item['image_id'], 'full');
            }
        }
        return $model;
    }
}