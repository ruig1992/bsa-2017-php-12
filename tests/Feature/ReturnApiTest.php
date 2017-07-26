<?php
namespace Tests\Feature;

use Tests\TestCase;
use Tests\Traits\RentalFaker;
use App\Services\Rental\RentalService;

class ReturnApiTest extends TestCase
{
    use RentalFaker;

    /**
     * @var \App\Entity\Rental
     */
    protected $rental;

    public function setUp()
    {
        parent::setUp();
        $this->migrateRefresh()->setLocations();

        $this->rental = $this->rentCar(
            $this->createUser(),
            $this->createCar(),
            ['rented_from' => $this->getLocation()]
        );
    }

    public function testUserUnAuthenticatedReturnCar()
    {
        $car = $this->rental->car;
        $returnedTo = $this->getLocation(1);

        $this->apiCarsReturn($car->id, $returnedTo)
            ->assertStatus(401);
    }

    public function testReturnCar()
    {
        // Test success the car return

        $user = $this->rental->user;
        $car = $this->rental->car;
        $returnedTo = $this->getLocation(1);

        $this->actingAs($user)
            ->apiCarsReturn($car->id, $returnedTo)
            ->assertStatus(200)
            ->assertJson([
                'id' => $this->rental->id,
                'car_id' => $car->id,
                'user_id' => $user->id,
                'price' => RentalService::RENTAL_PRICE,
                'rented_from' => $this->rental->rented_from,
                'returned_to' => $returnedTo,
            ]);

        // Test \App\Services\Rental\Exceptions\Car\CarNotRented for the current user

        $this->actingAs($user)
            ->apiCarsReturn($car->id, $returnedTo)
            ->assertStatus(403)
            ->json([
                'error' => "The car #{$car->id} is not rented by this user"
            ]);

        // Test \App\Services\Rental\Exceptions\Car\CarNotRented for the another user

        $user2 = $this->createUser();

        $this->actingAs($user2)
            ->apiCarsReturn($car->id, $returnedTo)
            ->assertStatus(403)
            ->json([
                'error' => "The car #{$car->id} is not rented by any user"
            ]);

        // Test \App\Services\Rental\Exceptions\Car\CarNotFound

        $car->delete();

        $this->actingAs($user)
            ->apiCarsReturn($car->id, $returnedTo)
            ->assertStatus(404)
            ->json([
                'error' => "The car #{$car->id} not found"
            ]);

        // Test \App\Services\Rental\Exceptions\User\UserNotFound

        $user->delete();

        $this->actingAs($user)
            ->apiCarsReturn($car->id, $returnedTo)
            ->assertStatus(404)
            ->json([
                'error' => "The user #{$user->id} not found"
            ]);
    }

    protected function apiCarsReturn(int $carId, int $returnedTo)
    {
        return $this->json('POST', "/api/cars/{$carId}/return/", [
            'returned_to' => $returnedTo
        ]);
    }
}
