<?php
namespace Tests\Unit;

use Tests\TestCase;
use App\Entity\Rental;
use Tests\CreatesApplication;
use Tests\Traits\RentalFaker;
use App\Services\Rental\RentalService;
use App\Services\Rental\Exceptions\{
    Car\CarNotFound,
    User\UserNotFound,
    Car\CarAlreadyRented,
    User\UserHasRentedCar
};

class RentalServiceTest extends TestCase
{
    use CreatesApplication, RentalFaker;

    public function setUp()
    {
        parent::setUp();
        $this->migrateRefresh()->setLocations();
    }

    /**
     * Tests the car renting
     */
    public function testRentCar()
    {
        $user = $this->createUser();
        $car = $this->createCar();
        $properties = ['rented_from' => $this->getLocation()];

        $rental = $this->rentCar($user, $car, $properties);

        $this->assertTrue($rental->exists);
        $this->assertInstanceOf(Rental::class, $rental);

        $this->assertArraySubset([
            'user_id' => $user->id,
            'car_id' => $car->id,
            'price' => RentalService::RENTAL_PRICE,
            'rented_from' => $properties['rented_from'],
        ], $rental->toArray());

        $this->assertTrue($rental->returned_to === null);
        $this->assertTrue($rental->returned_at === null);
    }

    /**
     * Tests when the user not found
     */
    public function testRentWhenUserNotFound()
    {
        $this->expectException(UserNotFound::class);

        $user = $this->createUser();
        $car = $this->createCar();
        $properties = ['rented_from' => $this->getLocation()];

        $user->delete();

        $this->rentCar($user, $car, $properties);
    }

    /**
     * Tests when the car not found
     */
    public function testRentWhenCarNotFound()
    {
        $this->expectException(CarNotFound::class);

        $user = $this->createUser();
        $car = $this->createCar();
        $properties = ['rented_from' => $this->getLocation()];

        $car->delete();

        $this->rentCar($user, $car, $properties);
    }

    /**
     * Tests when the current user has already rented a car
     */
    public function testRentWhenUserHasRentedCar()
    {
        $this->expectException(UserHasRentedCar::class);

        $user = $this->createUser();
        $car1 = $this->createCar();
        $car2 = $this->createCar();
        $properties = ['rented_from' => $this->getLocation()];

        $this->rentCar($user, $car1, $properties);
        $this->rentCar($user, $car2, $properties);
    }

    /**
     * Tests when the car is already rented
     */
    public function testRentWhenCarAlreadyRented()
    {
        $this->expectException(CarAlreadyRented::class);

        $user1 = $this->createUser();
        $user2 = $this->createUser();
        $car = $this->createCar();
        $properties = ['rented_from' => $this->getLocation()];

        $this->rentCar($user1, $car, $properties);
        $this->rentCar($user2, $car, $properties);
    }
}
