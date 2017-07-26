<?php
namespace App\Services\Rental;

use Carbon\Carbon;
use App\Entity\Rental;
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

    const RENTAL_PRICE = 120.50;

    /**
     * @inheritdoc
     */
    public function rent(int $userId, int $carId, array $properties): Rental
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
     * @return \App\Entity\Rental
     */
    private function rentCar(array $properties): Rental
    {
        $properties['user_id'] = $this->userId;
        $properties['car_id'] = $this->carId;
        $properties['price'] = self::RENTAL_PRICE;
        $properties['rented_at'] = Carbon::now()->toDateTimeString();

        return $this->rentals->create($properties);
    }
}
