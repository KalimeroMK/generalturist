<?php

namespace Modules\Core\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class ToolsController extends Controller
{
    public function clearCache()
    {
        Artisan::call('cache:clear');
        return redirect()->route('core.admin.tool.index')->with('success', __('Clear cache success!'));
    }
}
