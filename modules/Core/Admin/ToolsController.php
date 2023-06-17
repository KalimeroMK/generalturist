<?php

namespace Modules\Core\Admin;

use Modules\AdminController;

class ToolsController extends AdminController
{
    public function index()
    {
        $this->setActiveMenu(route('core.admin.tool.index'));
        return view('Core::admin.tools.index');
    }
}
