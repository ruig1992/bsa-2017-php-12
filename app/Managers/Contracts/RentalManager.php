<?php
namespace App\Managers\Contracts;

use App\Entity\Rental;

/**
 * Interface RentalManager
 * @package App\Repositories\Contracts
 */
interface RentalManager
{
    /**
     * Get the active rental by the car.
     *
     * @param int $carId
     *
     * @return \App\Entity\Rental|null
     */
    public function getActiveRentalByCar(int $carId): ?Rental;

    /**
     * Get the active rental by the user.
     *
     * @param int $userId
     *
     * @return \App\Entity\Rental|null
     */
    public function getActiveRentalByUser(int $userId): ?Rental;
}
