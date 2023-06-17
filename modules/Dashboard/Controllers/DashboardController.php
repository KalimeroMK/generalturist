<?php

namespace Modules\Dashboard\Controllers;

use Modules\AdminController;

class DashboardController extends AdminController
{
    public function __construct()
    {
    }

    public function index()
    {
        return View('Dashboard::index');
    }
}
