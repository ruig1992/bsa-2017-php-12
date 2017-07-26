<?php
namespace Tests\Traits;

use App\Entity\{Car, User, Location};
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\Collection;
use App\Services\Rental\Contracts\{RentalService, ReturnService};

trait RentalFaker
{
    protected $locations;

    protected function migrateRefresh(): self
    {
        Artisan::call("migrate:refresh");
        return $this;
    }

    protected function createUser(): User
    {
        return factory(User::class)->create();
    }

    protected function createCar(): Car
    {
        return factory(Car::class)->create();
    }

    protected function setLocations(): self
    {
        $this->locations = factory(Location::class, 2)->create();
        return $this;
    }

    protected function getLocation(int $number = 0): int
    {
        return $this->locations[$number]->id;
    }

    protected function rentCar(User $user, Car $car, array $properties)
    {
        return app(RentalService::class)->rent($user->id, $car->id, array_only(
            $properties,
            ['rented_from']
        ));
    }

    protected function returnCar(User $user, Car $car, array $properties)
    {
        return app(ReturnService::class)->returnFromRent($user->id, $car->id, array_only(
            $properties,
            ['returned_to']
        ));
    }
}
