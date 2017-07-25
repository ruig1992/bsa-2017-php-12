<?php
namespace App\Policies;

use Exception;
use App\Entity\Car;
use App\Entity\User;
use App\Services\Rental\Contracts\RentalService;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class CarRentalPolicy
 * @package App\Policies
 */
class CarRentalPolicy
{
    use HandlesAuthorization;

    /**
     * @var \App\Services\Rental\Contracts\RentalService
     */
    private $rentalService;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct(RentalService $rentalService)
    {
        $this->rentalService = $rentalService;
    }

    public function rentCar(User $user, int $carId): bool
    {
        $this->rentalService->checkUser($user)
            ->checkCar($carId);

        return true;
    }

    public function rentReturn()
    {
        //
    }
}
