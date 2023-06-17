<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectToInstaller
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array<int, string>
     */
    protected array $except = [
        '_debugbar/*'
    ];
    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  Closure  $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!str_contains($request->path(), 'install') && !file_exists(storage_path('installed')) && !$this->inExceptArray($request)) {
            return redirect('/install');
        }

        if (str_contains($request->path(), 'install') && !file_exists(base_path('.env'))) {
            copy(base_path('.env.example'), base_path('.env'));
        }

        return $next($request);
    }

    /**
     * Determine if the request URI should be accessible in maintenance mode.
     *
     * @param  Request  $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        $except = $this->except;

        foreach ($except as $path) {
            $path = trim($path, '/');

            if ($request->fullUrlIs($path) || $request->is($path)) {
                return true;
            }
        }

        return false;
    }
}
