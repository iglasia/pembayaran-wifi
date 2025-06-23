<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientBillingController;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            Route::middleware('client')
                ->prefix('client')
                ->name('client.')
                ->namespace($this->namespace)
                ->group(base_path('routes/client.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    public function invoice($id)
    {
        $bill = Transaction::with('client.internet_package')->findOrFail($id);
        $client = auth()->guard('client')->user();
        if (!$client) {
            abort(403, 'Anda harus login sebagai klien untuk mengakses halaman ini.');
        }
        // tampilkan view invoice
        return view('client.invoice', compact('bill'));
    }

    public function bayar($id)
    {
        $bill = Transaction::with('client.internet_package')->findOrFail($id);
        $client = auth()->guard('client')->user();
        if (!$client) {
            abort(403, 'Anda harus login sebagai klien untuk mengakses halaman ini.');
        }
        // tampilkan form pembayaran
        return view('client.bayar', compact('bill'));
    }

    public function index()
    {
        $client = auth()->guard('client')->user();
        if (!$client) {
            abort(403, 'Anda harus login sebagai klien untuk mengakses halaman ini.');
        }
        // ... lanjutkan query tagihan ...
    }
}
