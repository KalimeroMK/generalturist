<?php

namespace Modules\Car\Blocks;

use Modules\Core\Models\Terms;
use Modules\Template\Blocks\BaseBlock;

class CarTermFeaturedBox extends BaseBlock
{
    public function getOptions()
    {
        return [
            'settings' => [
                [
                    'id' => 'title',
                    'type' => 'input',
                    'inputType' => 'text',
                    'label' => __('Title')
                ],
                [
                    'id' => 'desc',
                    'type' => 'input',
                    'inputType' => 'text',
                    'label' => __('Desc')
                ],
                [
                    'id' => 'term_car',
                    'type' => 'select2',
                    'label' => __('Select term car'),
                    'select2' => [
                        'ajax' => [
                            'url' => route('car.admin.attribute.term.getForSelect2', ['type' => 'car']),
                            'dataType' => 'json'
                        ],
                        'width' => '100%',
                        'multiple' => "true",
                    ],
                    'pre_selected' => route('car.admin.attribute.term.getForSelect2', [
                        'type' => 'car',
                        'pre_selected' => 1
                    ])
                ],
            ],
            'category' => __("Service Car")
        ];
    }

    public function getName()
    {
        return __('Car: Term Featured Box');
    }

    public function content($model = [])
    {
        if (empty($term_car = $model['term_car'])) {
            return "";
        }
        $list_term = Terms::whereIn('id', $term_car)->with('translation')->get();
        $model['list_term'] = $list_term;
        return view('Car::frontend.blocks.term-featured-box.index', $model);
    }

    public function contentAPI($model = [])
    {
        $model['list_term'] = null;
        if (!empty($term_car = $model['term_car'])) {
            $list_term = Terms::whereIn('id', $term_car)->get();
            if (!empty($list_term)) {
                foreach ($list_term as $item) {
                    $model['list_term'][] = [
                        "id" => $item->id,
                        "attr_id" => $item->attr_id,
                        "name" => $item->name,
                        "image_id" => $item->image_id,
                        "image_url" => get_file_url($item->image_id, "full"),
                        "icon" => $item->icon,
                    ];
                }
            }
        }
        return $model;
    }
}
