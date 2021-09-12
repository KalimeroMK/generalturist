<?php

    namespace Modules\Contact\Blocks;

    use Modules\Template\Blocks\BaseBlock;

    class Contact extends BaseBlock
    {
        function __construct()
        {
            $this->setOptions([
                'settings' => [
                    [
                        'id'        => 'class',
                        'type'      => 'input',
                        'inputType' => 'text',
                        'label'     => __('Class Block'),
                    ],
                ],
                'category' => __("Other Block"),
            ]);
        }

        public function getName()
        {
            return __('Contact Block');
        }

        public function content($model = [])
        {
            return view('Contact::frontend.blocks.contact.index', $model);
        }
    }