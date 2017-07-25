<?php
namespace App\Services\Rental;

use Carbon\Carbon;
use App\Services\Rental\{
    Traits\RentalBase,
    Contracts\RentalService as RentalServiceContract
};
use App\Managers\Eloquent\Criteria\WhereIsOrNotNull;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Rental\Exceptions\User\UserHasRentedCar;

/**
 * Class RentalService
 * @package App\Services\Rental
 */
class RentalService implements RentalServiceContract
{
    use RentalBase;

    /**
     * @inheritdoc
     */
    public function rent(int $userId, int $carId, array $properties): bool
    {
        return $this->validate($userId, $carId)
            ->rentCar($properties);
    }

    /**
     * @inheritdoc
     */
    public function validate(int $userId, int $carId): RentalServiceContract
    {
        return $this->isUserExists($userId)
            ->isCarExists($carId)
            ->isCarRented(true)
            ->isUserHasRentedCar();
    }

    /**
     * Checks, if the current user has adlready rented the car.
     *
     * @return $this
     * @throws \App\Services\Rental\Exceptions\User\UserHasRentedCar
     */
    private function isUserHasRentedCar(): self
    {
        try {
            $rental = $this->rentals
                ->withCriteria(new WhereIsOrNotNull('returned_at'))
                ->findWhereFirst('user_id', $this->userId);

            throw new UserHasRentedCar($rental->car_id);

        } catch (ModelNotFoundException $e) {
            return $this;
        }
    }

    /**
     * Creates the car rental for the current user.
     *
     * @param array $properties
     * @return bool
     */
    private function rentCar(array $properties): bool
    {
        if (empty($properties['returned_to'])) {
            $properties['returned_to'] = $properties['rented_from'];
        }
        $properties['user_id'] = $this->userId;
        $properties['car_id'] = $this->carId;
        $properties['price'] = $this->price;
        $properties['rented_at'] = Carbon::now()->toDateTimeString();

        return $this->rentals->create($properties) !== null;
    }
}
