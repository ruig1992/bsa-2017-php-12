<?php
namespace App\Services\Rental\Contracts;

use App\Entity\User;

/**
 * Interface RentalService
 * @package App\Services\Rental\Contracts
 */
interface RentalService
{
    /**
     * Car rent for current user.
     *
     * @param User $user
     * @param array $properties
     *
     * @return void
     */
    public function rent(User $user, array $properties): void;
}
