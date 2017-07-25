<?php
namespace App\Services\Rental\Exceptions\User;

use App\Services\Rental\Exceptions\RentalException;

/**
 * Class UserHasRentedCar
 * @package App\Services\Rental\Exceptions\User
 */
class UserHasRentedCar extends RentalException
{
    /**
     * @param int $carId
     */
    public function __construct(int $carId)
    {
        $this->message = "The user has already rented the car #{$carId}";
    }
}
