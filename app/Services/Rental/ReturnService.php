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
    public function returnFromRent(int $userId, int $carId): bool
    {
        return $this->validate($userId, $carId)
            ->returnCar();
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
     * @return bool
     */
    private function returnCar(): bool
    {
        return $this->rental->update([
            'returned_at' => Carbon::now()->toDateTimeString(),
        ]);
    }
}
