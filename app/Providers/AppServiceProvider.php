<?php
namespace App\Providers;

use App\Services\Rental\{
    RentalService,
    Contracts\RentalService as RentalServiceContract
};
use Laravel\Dusk\DuskServiceProvider;
use Illuminate\Support\ServiceProvider;

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

        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }
    }
}
