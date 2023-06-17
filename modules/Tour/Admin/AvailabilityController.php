<?php

namespace Modules\Tour\Admin;

use Modules\Space\Models\SpaceDate;

class AvailabilityController extends \Modules\Tour\Controllers\AvailabilityController
{
    protected $spaceClass;
    /**
     * @var SpaceDate
     */
    protected $spaceDateClass;
    protected $indexView = 'Tour::admin.availability';

    public function __construct()
    {
        parent::__construct();
        $this->setActiveMenu(route('tour.admin.index'));
        $this->middleware('dashboard');
    }

}
