<?php

namespace Modules\Space\Admin;

use Modules\Space\Models\SpaceDate;

class AvailabilityController extends \Modules\Space\Controllers\AvailabilityController
{
    protected $spaceClass;
    /**
     * @var SpaceDate
     */
    protected $spaceDateClass;
    protected $indexView = 'Space::admin.availability';

    public function __construct()
    {
        parent::__construct();
        $this->setActiveMenu(route('space.admin.index'));
        $this->middleware('dashboard');
    }

}
