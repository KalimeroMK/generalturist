<?php

    namespace Modules\Core\Admin;

    use Modules\AdminController;

    class ToolsController extends AdminController
    {
        public function index()
        {
            $this->setActiveMenu('admin/module/core/tools');
            return view('Core::admin.tools.index');
        }
    }