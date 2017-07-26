<?php
namespace App\Services\Rental\Traits;

use App\Managers\Contracts\{
    UserManager,
    CarManager,
    RentalManager
};
use App\Services\Rental\Exceptions\{
    Car\CarNotFound,
    Car\CarNotRented,
    User\UserNotFound,
    Car\CarAlreadyRented
};
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Trait RentalBase
 * @package App\Services\Rental\Traits
 */
trait RentalBase
{
    /**
     * @var \App\Managers\Contracts\UserManager
     */
    private $users;
    /**
     * @var \App\Managers\Contracts\CarManager
     */
    private $cars;
    /**
     * @var \App\Managers\Contracts\RentalManager
     */
    private $rentals;
    /**
     * @var int
     */
    private $userId;
    /**
     * @var int
     */
    private $carId;

    /**
     * @param \App\Managers\Contracts\UserManager $users
     * @param \App\Managers\Contracts\CarManager $cars
     * @param \App\Managers\Contracts\RentalManager $rentals
     */
    public function __construct(
        UserManager $users,
        CarManager $cars,
        RentalManager $rentals
    ) {
        $this->users = $users;
        $this->cars = $cars;
        $this->rentals = $rentals;
    }

    /**
     * Sets the current user and the car for rental actions.
     *
     * @param int $userId
     * @param int $carId
     *
     * @return $this
     */
    private function setUserAndCar(int $userId, int $carId): self
    {
        $this->userId = $userId;
        $this->carId = $carId;

        return $this;
    }

    /**
     * Checks, is the current user exists.
     *
     * @param  int $id
     *
     * @return $this
     * @throws \App\Services\Rental\Exceptions\User\UserNotFound
     */
    private function isUserExists(int $id): self
    {
        if (!$this->users->isExists($id)) {
            throw new UserNotFound($id);
        }
        $this->userId = $id;

        return $this;
    }

    /**
     * Checks, is the car exists.
     *
     * @param  int $id
     *
     * @return $this
     * @throws \App\Services\Rental\Exceptions\Car\CarNotFound
     */
    private function isCarExists(int $id): self
    {
        if (!$this->cars->isExists($id)) {
            throw new CarNotFound($id);
        }
        $this->carId = $id;

        return $this;
    }

    /**
     * Checks, is the car has already rented.
     *
     * @param  bool $rentedFalse
     *
     * @return $this
     * @throws \App\Services\Rental\Exceptions\Car\CarAlreadyRented|
     *         \App\Services\Rental\Exceptions\Car\CarNotRented
     */
    private function isCarRented(bool $rentedFalse = false): self
    {
        $rental = $this->rentals->getActiveRentalByCar($this->carId);

        if ($rental === null) {
            if ($rentedFalse === false) {
                throw new CarNotRented($this->carId);
            }
            return $this;
        }

        if ($rentedFalse === true) {
            throw new CarAlreadyRented($this->carId);
        }
        if ($rental->user_id !== $this->userId) {
            throw new CarNotRented($this->carId, $this->userId);
        }

        return $this;
    }
}
