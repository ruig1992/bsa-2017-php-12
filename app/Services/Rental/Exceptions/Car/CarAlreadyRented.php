<?php
namespace App\Services\Rental\Exceptions\Car;

use App\Services\Rental\Exceptions\RentalException;

/**
 * Class CarAlreadyRented
 * @package App\Services\Rental\Exceptions\Car
 */
class CarAlreadyRented extends RentalException
{
    /**
     * @param int $carId
     */
    public function __construct(int $carId)
    {
        $this->message = "The car #{$carId} is already rented";
    }
}
