<?php

    namespace Modules;

    use App\Http\Controllers\Controller;
    use Illuminate\Support\Facades\Auth;

    class AdminController extends Controller
    {
        public function __construct()
        {
            $this->middleware('auth');
        }

        public function checkPermission($permission = false)
        {
            if ($permission) {
                if (!Auth::id() or !Auth::user()->hasPermissionTo($permission)) {
                    abort(403);
                }
            }
        }

        public function hasPermission($permission): bool
        {
            return Auth::user()->hasPermissionTo($permission);
        }
    }