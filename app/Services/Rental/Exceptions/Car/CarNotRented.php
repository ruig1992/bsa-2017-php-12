<?php
namespace App\Services\Rental\Exceptions\Car;

use App\Services\Rental\Exceptions\RentalException;

/**
 * Class CarNotRented
 * @package App\Services\Rental\Exceptions\Car
 */
class CarNotRented extends RentalException
{
    /**
     * @param int $carId
     * @param int|null $userId
     */
    public function __construct(int $carId, int $userId = null)
    {
        $type = $userId === null ? 'any' : 'this';
        $this->message = "The car #{$carId} is not rented by {$type} user";
    }
}
