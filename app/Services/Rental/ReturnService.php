<?php
namespace App\Services\Rental;

use Carbon\Carbon;
use App\Services\Rental\Traits\RentalBase;
use App\Services\Rental\Contracts\ReturnService as ReturnServiceContract;

/**
 * Class ReturnService
 * @package App\Services\Rental
 */
class ReturnService implements ReturnServiceContract
{
    use RentalBase;

    /**
     * @inheritdoc
     */
    public function returnFromRent(int $userId, int $carId, array $properties): bool
    {
        return $this->setUserAndCar($userId, $carId)
            ->returnCar($properties);
    }

    /**
     * @inheritdoc
     */
    public function validate(int $userId, int $carId): ReturnServiceContract
    {
        return $this->isUserExists($userId)
            ->isCarExists($carId)
            ->isCarRented();
    }

    /**
     * Return the car from rent.
     *
     * @param array $properties
     * @return bool
     */
    private function returnCar(array $properties): bool
    {
        $properties['returned_at'] = Carbon::now()->toDateTimeString();

        return $this->rentals
            ->getActiveRentalByCar($this->carId)
            ->update($properties);
    }
}
