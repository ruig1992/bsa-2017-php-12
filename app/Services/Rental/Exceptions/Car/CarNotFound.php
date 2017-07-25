<?php
namespace App\Services\Rental\Exceptions\Car;

use App\Services\Rental\Exceptions\RentalException;

/**
 * Class CarNotFound
 * @package App\Services\Rental\Exceptions\Car
 */
class CarNotFound extends RentalException
{
    /**
     * @var int
     */
    protected $code = 404;

    /**
     * @param int $carId
     */
    public function __construct(int $carId)
    {
        $this->message = "The car #{$carId} not found";
    }
}
