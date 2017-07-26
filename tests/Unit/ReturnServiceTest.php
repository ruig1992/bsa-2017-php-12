<?php
namespace Tests\Unit;

use Tests\TestCase;
use App\Entity\Rental;
use Tests\CreatesApplication;
use Tests\Traits\RentalFaker;
use App\Services\Rental\RentalService;
use App\Services\Rental\Exceptions\{
    Car\CarNotFound,
    Car\CarNotRented,
    User\UserNotFound
};

class ReturnServiceTest extends TestCase
{
    use CreatesApplication, RentalFaker;

    /**
     * @var \App\Entity\Rental
     */
    protected $rental;

    public function setUp()
    {
        parent::setUp();
        $this->migrateRefresh()->setLocations();

        $user = $this->createUser();
        $car = $this->createCar();
        $properties = ['rented_from' => $this->getLocation()];

        $this->rental = $this->rentCar($user, $car, $properties);
    }

    /**
     * Tests the car return
     */
    public function testReturnCar()
    {
        $user = $this->rental->user;
        $car = $this->rental->car;
        $properties = ['returned_to' => $this->getLocation(1)];

        $rental = $this->returnCar($user, $car, $properties);

        $this->assertInstanceOf(Rental::class, $rental);
        $this->assertTrue($rental->exists);
        $this->assertArraySubset([
            'id' => $this->rental->id,
            'car_id' => $car->id,
            'user_id' => $user->id,
            'price' => RentalService::RENTAL_PRICE,
            'rented_from' => $this->rental->rented_from,
            'returned_to' => $properties['returned_to'],
        ], $rental->toArray());
    }

    /**
     * Tests when the user not found
     */
    public function testReturnWhenUserNotFound()
    {
        $this->expectException(UserNotFound::class);

        $user = $this->rental->user;
        $car = $this->rental->car;
        $properties = ['returned_to' => $this->getLocation(1)];

        $user->delete();

        $rental = $this->returnCar($user, $car, $properties);
    }

    /**
     * Tests when the car not found
     */
    public function testReturnWhenCarNotFound()
    {
        $this->expectException(CarNotFound::class);

        $user = $this->rental->user;
        $car = $this->rental->car;
        $properties = ['returned_to' => $this->getLocation(1)];

        $car->delete();

        $rental = $this->returnCar($user, $car, $properties);
    }

    /**
     * Tests when the car is not rented by any user
     */
    public function testReturnWhenCarNotRentedByAnyUser()
    {
        $car2 = $this->createCar();
        $properties = ['returned_to' => $this->getLocation(1)];

        $this->expectException(CarNotRented::class);
        $this->expectExceptionMessage("The car #{$car2->id} is not rented by any user");

        $this->returnCar($this->rental->user, $car2, $properties);
    }

    /**
     * Tests when the car is not rented by the current user
     */
    public function testReturnWhenCarNotRentedByThisUser()
    {
        $user = $this->createUser();
        $car2 = $this->createCar();
        $properties = ['rented_from' => $this->getLocation(1)];

        $this->expectException(CarNotRented::class);
        $this->expectExceptionMessage("The car #{$car2->id} is not rented by this user");

        $this->rentCar($user, $car2, [
            'rented_from' => $this->getLocation(1)
        ]);

        $this->returnCar($this->rental->user, $car2, [
            'returned_to' => $this->rental->returned_to
        ]);
    }
}
