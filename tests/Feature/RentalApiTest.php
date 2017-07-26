<?php
namespace Tests\Feature;

use Tests\TestCase;
use Tests\Traits\RentalFaker;
use App\Services\Rental\RentalService;

class RentalApiTest extends TestCase
{
    use RentalFaker;

    public function setUp()
    {
        parent::setUp();
        $this->migrateRefresh()->setLocations();
    }

    public function testUserUnAuthenticatedRentCar()
    {
        $user = $this->createUser();
        $car = $this->createCar();
        $rentedFrom = $this->getLocation();

        $this->apiCarsRent($car->id, $rentedFrom)
            ->assertStatus(401);
    }

    public function testRentCar()
    {
        // Test success the car rent

        $user = $this->createUser();
        $car = $this->createCar();
        $rentedFrom = $this->getLocation();

        $this->actingAs($user)
            ->apiCarsRent($car->id, $rentedFrom)
            ->assertStatus(200)
            ->assertJson([
                'user_id' => $user->id,
                'car_id' => $car->id,
                'price' => RentalService::RENTAL_PRICE,
                'rented_from' => $rentedFrom,
            ]);

        // Test \App\Services\Rental\Exceptions\Car\CarAlreadyRented

        $user2 = $this->createUser();

        $this->actingAs($user2)
            ->apiCarsRent($car->id, $rentedFrom)
            ->assertStatus(403)
            ->json([
                'error' => "The car #{$car->id} is already rented"
            ]);

        // Test \App\Services\Rental\Exceptions\User\UserHasRentedCar

        $car2 = $this->createCar();

        $this->actingAs($user)
            ->apiCarsRent($car2->id, $rentedFrom)
            ->assertStatus(403)
            ->json([
                'error' => "The user has already rented the car #{$car->id}"
            ]);

        // Test \App\Services\Rental\Exceptions\Car\CarNotFound

        $car2->delete();

        $this->actingAs($user2)
            ->apiCarsRent($car2->id, $rentedFrom)
            ->assertStatus(404)
            ->json([
                'error' => "The car #{$car2->id} not found"
            ]);

        // Test \App\Services\Rental\Exceptions\User\UserrNotFound

        $user2->delete();

        $this->actingAs($user2)
            ->apiCarsRent($car->id, $rentedFrom)
            ->assertStatus(404)
            ->json([
                'error' => "The user #{$user2->id} not found"
            ]);
    }

    protected function apiCarsRent(int $carId, int $rentedFrom)
    {
        return $this->json('POST', "/api/cars/{$carId}/rent/", [
            'rented_from' => $rentedFrom
        ]);
    }
}
