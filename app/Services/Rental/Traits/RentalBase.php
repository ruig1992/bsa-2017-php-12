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
use App\Managers\Eloquent\Criteria\WhereIsOrNotNull;
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
     * @var float
     */
    private $price;
    /**
     * @var int
     */
    private $userId;
    /**
     * @var int
     */
    private $carId;
    /**
     * @var \App\Entity\Rental
     */
    private $rental;

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

        $this->price = env('RENTAL_PRICE');
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
     * @param  bool $rentedFalse  If true, throws exception
     *
     * @return $this
     * @throws \App\Services\Rental\Exceptions\Car\CarAlreadyRented|
     *         \App\Services\Rental\Exceptions\Car\CarNotRented
     */
    private function isCarRented(bool $rentedFalse = false): self
    {
        try {
            $this->rental = $this->rentals
                ->withCriteria([new WhereIsOrNotNull('returned_at')])
                ->findWhereFirst([
                    ['car_id', $this->carId],
                ]);

            if ($rentedFalse === true) {
                throw new CarAlreadyRented($this->carId);
            }
            if ($this->rental->user_id !== $this->userId) {
                throw new CarNotRented($this->carId, $this->userId);
            }

        } catch (ModelNotFoundException $e) {
            if ($rentedFalse === false) {
                throw new CarNotRented($this->carId);
            }
        }

        return $this;
    }
}
