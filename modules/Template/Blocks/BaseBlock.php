<?php
namespace Modules\Template\Blocks;
use Illuminate\Support\Facades\View;

class BaseBlock
{
    public $options = [];

    public function getName()
    {
        return '';
    }

    public function setOptions($options)
    {
        $this->options = $options;
    }

    public function getOption($k, $default = false)
    {
        if(empty($this->options)){
            $this->options = $this->getOptions();
        }
        return $this->options[$k] ?? $default;
    }

    public function getOptions(){
        return [];
    }

    public function content($model = [])
    {

    }
    public function view($view,$data = null){
        if(View::exists($view)){
            return view($view,$data);
        }
    }
}
