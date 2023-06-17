<?php
namespace Modules\Core\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Modules\Core\Models\Settings;

class ToolsController extends Controller
{
    public function clearCache()
    {
        Artisan::call('cache:clear');
        return redirect()->route('core.admin.tool.index')->with('success', __('Clear cache success!') );
    }
}
