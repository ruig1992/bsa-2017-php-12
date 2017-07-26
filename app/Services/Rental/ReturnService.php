<?php
namespace App\Services\Rental;

use Carbon\Carbon;
use App\Entity\Rental;
use App\Services\Rental\{
    Traits\RentalBase,
    Contracts\ReturnService as ReturnServiceContract
};

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
    public function returnFromRent(int $userId, int $carId, array $properties): Rental
    {
        return $this->validate($userId, $carId)
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
     * @return \App\Entity\Rental
     */
    private function returnCar(array $properties): Rental
    {
        $properties['returned_at'] = Carbon::now()->toDateTimeString();

        $rental = $this->rentals
            ->getActiveRentalByCar($this->carId)
            ->fill($properties);

        $rental->save();

        return $rental;
    }
}
