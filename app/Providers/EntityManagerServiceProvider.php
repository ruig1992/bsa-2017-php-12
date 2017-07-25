<?php
namespace App\Providers;

use App\Managers\Contracts\{
    CarManager as CarManagerContract,
    UserManager as UserManagerContract,
    RentalManager as RentalManagerContract,
    LocationManager as LocationManagerContract
};
use App\Managers\Eloquent\{
    CarManager,
    UserManager,
    RentalManager,
    LocationManager
};
use Illuminate\Support\ServiceProvider;

/**
 * Class EntityManagerServiceProvider
 * @package App\Providers
 */
class EntityManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // method body
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(CarManagerContract::class, CarManager::class);
        $this->app->bind(UserManagerContract::class, UserManager::class);
        $this->app->bind(RentalManagerContract::class, RentalManager::class);
        $this->app->bind(LocationManagerContract::class, LocationManager::class);
    }
}
