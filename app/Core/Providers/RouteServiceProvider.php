<?php

namespace LarAPI\Core\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'LarAPI';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        Route::get('/', function () {
            return response()->json([
                'application' => config('app.name'),
                'environment' => config('app.env'),
                'status'      => 200
            ]);
        })->name('login');

        $this->mapV1Routes();
    }

    /**
     * Define the API v1 routes for the application.
     *
     * @return void
     */
    protected function mapV1Routes()
    {
        $modules = config('modules');

        foreach ($modules as $module) {
            Route::prefix('v1')
                ->middleware('api')
                ->namespace("LarAPI\\{$module}\\Controllers")
                ->group(base_path("app/{$module}/Routing/v1.php"));
        }
    }
}
