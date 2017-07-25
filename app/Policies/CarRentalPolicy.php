<?php
namespace App\Policies;

use App\Entity\User;
use App\Services\Rental\{
    Contracts\RentalService,
    Contracts\ReturnService,
    Exceptions\RentalException
};

/**
 * Class CarRentalPolicy
 * @package App\Policies
 */
class CarRentalPolicy
{
    /**
     * @var \App\Services\Rental\Contracts\RentalService
     */
    private $rentalService;
    /**
     * @var \App\Services\Rental\Contracts\ReturnService
     */
    private $returnService;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct(
        RentalService $rentalService,
        ReturnService $returnService
    ) {
        $this->rentalService = $rentalService;
        $this->returnService = $returnService;
    }

    /**
     * Determine whether the user can rent the car.
     *
     * @param User $user
     * @param int $carId
     * @param bool $notThrow  Not throw RentalException if true
     *
     * @return bool
     * @throws \App\Services\Rental\Exceptions\RentalException
     */
    public function rentCar(User $user, int $carId, bool $notThrow = false): bool
    {
        try {
            $this->rentalService->validate($user->id, $carId);
        } catch (RentalException $e) {
            if ($notThrow) {
                return false;
            }
            throw $e;
        }
        return true;
    }

    /**
     * Determine whether the user can return the car from the rent.
     *
     * @param User $user
     * @param int $carId
     * @param bool $notThrow  Not throw RentalException if true
     *
     * @return bool
     * @throws \App\Services\Rental\Exceptions\RentalException
     */
    public function returnCar(User $user, int $carId, bool $notThrow = false): bool
    {
        try {
            $this->returnService->validate($user->id, $carId);
        } catch (RentalException $e) {
            if ($notThrow) {
                return false;
            }
            throw $e;
        }
        return true;
    }
}
