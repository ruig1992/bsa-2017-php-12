<?php
namespace App\Services\Rental\Contracts;

/**
 * Interface ReturnService
 * @package App\Services\Rental\Contracts
 */
interface ReturnService
{
    /**
     * Returns the car from the user rent and close the rental.
     *
     * @param int $userId
     * @param int $carId
     * @param array $properties
     *
     * @return bool
     */
    public function returnFromRent(int $userId, int $carId, array $properties): bool;

    /**
     * Validates the input parameters.
     *
     * @param int $userId
     * @param int $carId
     *
     * @return $this
     */
    public function validate(int $userId, int $carId): self;
}
