<?php
namespace App\Services\Rental;

use Carbon\Carbon;
use App\Entity\{Car, User};
use App\Managers\Contracts\{
    RentalManager,
    LocationManager
};
use App\Managers\Eloquent\Criteria\{
    EagerLoad,
    WhereIsOrNotNull
};
use App\Services\Rental\Exceptions\{
    UserNotReturnedCar,
    CarHasAlreadyRented
};
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Rental\Contracts\RentalService as RentalServiceContract;

/**
 * Class RentalService
 * @package App\Services\Rental
 */
class RentalService implements RentalServiceContract
{
    /**
     * @var \App\Managers\Contracts\RentalManager
     */
    protected $rentals;
    /**
     * @var \App\Managers\Contracts\LocationManager
     */
    protected $locations;
    /**
     * @var float
     */
    protected $price;

    /**
     * @param \App\Managers\Contracts\RentalManager $rentals
     * @param \App\Managers\Contracts\LocationManager $locations
     */
    public function __construct(RentalManager $rentals, LocationManager $locations)
    {
        $this->rentals = $rentals;
        $this->locations = $locations;
        $this->price = env('RENTAL_PRICE');
    }

    /**
     * Checks if the current user can rent the car.
     *
     * @param \App\Entity\User $user
     *
     * @return $this
     * @throws UserNotReturnedCar exception
     */
    public function checkUser(User $user): self
    {
        try {
            $rental = $this->rentals
                ->withCriteria(new WhereIsOrNotNull('returned_at'))
                ->findWhereFirst('user_id', $user->id);

            throw new UserNotReturnedCar($rental->toArray());

        } catch (ModelNotFoundException $e) {
            return $this;
        }
    }

    /**
     * Checks whether the car is rented or not.
     *
     * @param int $carId
     *
     * @return $this
     * @throws CarHasAlreadyRented exception
     */
    public function checkCar(int $carId): self
    {
        try {
            $rental = $this->rentals
                ->withCriteria([new WhereIsOrNotNull('returned_at')])
                ->findWhereFirst('car_id', '=', $carId);

            throw new CarHasAlreadyRented($rental->toArray());

        } catch (ModelNotFoundException $e) {
            return $this;
        }
    }

    /**
     * @inheritdoc
     */
    public function rent(User $user, array $properties): void
    {
        if (empty($properties['returned_to'])) {
            $properties['returned_to'] = $properties['rented_from'];
        }
        $properties['user_id'] = $user->id;
        $properties['rented_at'] = Carbon::now()->toDateTimeString();
        $properties['price'] = $this->price;

        $this->rentals->create($properties);
    }
}
