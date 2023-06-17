<?php
namespace Modules\Template\Blocks;

use Modules\Template\Blocks\BaseBlock;

class FaqList extends BaseBlock
{

    public function getName()
    {
        return __('FAQ List');
    }

    public function getOptions()
    {
        return [
            'settings' => [
                [
                    'id'        => 'title',
                    'type'      => 'input',
                    'inputType' => 'text',
                    'label'     => __('Title')
                ],
                [
                    'id'          => 'list_item',
                    'type'        => 'listItem',
                    'label'       => __('List Item(s)'),
                    'title_field' => 'title',
                    'settings'    => [
                        [
                            'id'        => 'title',
                            'type'      => 'input',
                            'inputType' => 'text',
                            'label'     => __('Question')
                        ],
                        [
                            'id'        => 'sub_title',
                            'type'      => 'editor',
                            'inputType' => 'textArea',
                            'label'     => __('Answer')
                        ],
                    ]
                ],
            ],
            'category'=>__("Other Block")
        ];
    }

    public function content($model = [])
    {
        return $this->view('Template::frontend.blocks.faq-list', $model);
    }
}
