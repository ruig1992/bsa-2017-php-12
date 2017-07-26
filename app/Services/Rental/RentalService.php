<?php
namespace App\Services\Rental;

use Carbon\Carbon;
use App\Services\Rental\{
    Traits\RentalBase,
    Contracts\RentalService as RentalServiceContract
};
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
        return $this->setUserAndCar($userId, $carId)
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
        $rental = $this->rentals->getActiveRentalByUser($this->userId);

        if ($rental !== null) {
            throw new UserHasRentedCar($rental->car_id);
        }
        return $this;
    }

    /**
     * Creates the car rental for the current user.
     *
     * @param array $properties
     * @return bool
     */
    private function rentCar(array $properties): bool
    {
        $properties['user_id'] = $this->userId;
        $properties['car_id'] = $this->carId;
        $properties['price'] = $this->price;
        $properties['rented_at'] = Carbon::now()->toDateTimeString();

        return $this->rentals->create($properties) !== null;
    }
}
