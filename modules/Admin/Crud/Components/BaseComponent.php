<?php


    namespace Modules\Admin\Crud\Components;


    use Exception;
    use Modules\Admin\Crud;

    class BaseComponent
    {
        protected array $allData = [];
        protected string $name = 'div';

        protected $curdModule;

        /**
         * @param $data
         */
        public function setData($data)
        {
            $this->allData = $data;
        }

        /**
         * @param $data
         */
        public function setCurdModule($data)
        {
            $this->curdModule = $data;
        }

        /**
         * @param $name
         */
        public function setName($name)
        {
            $this->name = $name;
        }

        /**
         * @throws Exception
         */
        public function render()
        {
            $class = '';
            if ($className = $this->data('class')) {
                $class = "class='".e($className)."'";
            }
            printf("<%s %s %s>", e($this->name), $class, $this->data("attr"));

            $children = $this->dataArray('children');

            if ($text = $this->data("text")) {
                echo $text;
            }

            if (!empty($children)) {
                Crud::layout($this->curdModule, $children);
            }

            printf("</%s>", e($this->name));
        }

        public function data($key, $default = '')
        {
            return $this->allData[$key] ?? $default;
        }

        public function dataArray($key, $default = [])
        {
            $res = $this->data($key, $default);
            if (!is_array($res)) {
                return $default;
            }
            return $res;
        }
    }
