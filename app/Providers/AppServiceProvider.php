<?php
namespace App\Providers;

use Laravel\Dusk\DuskServiceProvider;
use Illuminate\Support\ServiceProvider;
use App\Services\Rental\{
    RentalService,
    ReturnService,
    Contracts\RentalService as RentalServiceContract,
    Contracts\ReturnService as ReturnServiceContract
};

/**
 * Class AppServiceProvider
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(RentalServiceContract::class, RentalService::class);
        $this->app->bind(ReturnServiceContract::class, ReturnService::class);

        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }
    }
}
