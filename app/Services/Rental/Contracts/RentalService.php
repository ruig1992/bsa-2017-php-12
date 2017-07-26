<?php
namespace App\Services\Rental\Contracts;

use App\Entity\Rental;

/**
 * Interface RentalService
 * @package App\Services\Rental\Contracts
 */
interface RentalService
{
    /**
     * Car rent for current user.
     *
     * @param int $userId
     * @param int $carId
     * @param array $properties
     *
     * @return \App\Entity\Rental
     */
    public function rent(int $userId, int $carId, array $properties): Rental;

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
