<?php

namespace Modules\Boat\Blocks;

use Modules\Boat\Models\Boat;
use Modules\Template\Blocks\BaseBlock;

class ListBoat extends BaseBlock
{
    protected $boatClass;

    public function __construct(Boat $boatClass)
    {
        $this->boatClass = $boatClass;
    }

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
                    'id' => 'number',
                    'type' => 'input',
                    'inputType' => 'number',
                    'label' => __('Number Item')
                ],
                [
                    'id' => 'style',
                    'type' => 'radios',
                    'label' => __('Style'),
                    'values' => [
                        [
                            'value' => 'normal',
                            'name' => __("Normal")
                        ],
                        [
                            'value' => 'carousel',
                            'name' => __("Slider Carousel")
                        ]
                    ]
                ],
                [
                    'id' => 'location_id',
                    'type' => 'select2',
                    'label' => __('Filter by Location'),
                    'select2' => [
                        'ajax' => [
                            'url' => route('location.admin.getForSelect2'),
                            'dataType' => 'json'
                        ],
                        'width' => '100%',
                        'allowClear' => 'true',
                        'placeholder' => __('-- Select --')
                    ],
                    'pre_selected' => route('location.admin.getForSelect2', ['pre_selected' => 1])
                ],
                [
                    'id' => 'order',
                    'type' => 'radios',
                    'label' => __('Order'),
                    'values' => [
                        [
                            'value' => 'id',
                            'name' => __("Date Create")
                        ],
                        [
                            'value' => 'title',
                            'name' => __("Title")
                        ],
                    ]
                ],
                [
                    'id' => 'order_by',
                    'type' => 'radios',
                    'label' => __('Order By'),
                    'values' => [
                        [
                            'value' => 'asc',
                            'name' => __("ASC")
                        ],
                        [
                            'value' => 'desc',
                            'name' => __("DESC")
                        ],
                    ]
                ],
                [
                    'type' => "checkbox",
                    'label' => __("Only featured items?"),
                    'id' => "is_featured",
                    'default' => true
                ],
                [
                    'id' => 'custom_ids',
                    'type' => 'select2',
                    'label' => __('List by IDs'),
                    'select2' => [
                        'ajax' => [
                            'url' => route('boat.admin.getForSelect2'),
                            'dataType' => 'json'
                        ],
                        'width' => '100%',
                        'multiple' => "true",
                        'placeholder' => __('-- Select --')
                    ],
                    'pre_selected' => route('boat.admin.getForSelect2', [
                        'pre_selected' => 1
                    ])
                ],
            ],
            'category' => __("Service Boat")
        ];
    }

    public function getName()
    {
        return __('Boat: List Items');
    }

    public function content($model = [])
    {
        $list = $this->query($model);
        $data = [
            'rows' => $list,
            'style_list' => $model['style'],
            'title' => $model['title'],
            'desc' => $model['desc'],
        ];
        return $this->view('Boat::frontend.blocks.list-boat.index', $data);
    }

    public function query($model)
    {
        $listCar = $this->boatClass->search($model);
        $limit = $model['number'] ?? 5;
        return $listCar->paginate($limit);
    }

    public function contentAPI($model = [])
    {
        $rows = $this->query($model);
        $model['data'] = $rows->map(function ($row) {
            return $row->dataForApi();
        });
        return $model;
    }
}
