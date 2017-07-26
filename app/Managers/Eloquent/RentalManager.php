<?php
namespace App\Managers\Eloquent;

use App\Entity\Rental;
use Illuminate\Support\Collection;
use App\Managers\AbstractEntityManager;
use App\Managers\Eloquent\Criteria\WhereIsOrNotNull;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Managers\Contracts\RentalManager as RentalManagerContract;

/**
 * Class RentalManager
 * @package App\Managers\Eloquent
 */
class RentalManager extends AbstractEntityManager implements RentalManagerContract
{
    /**
     * @inheritdoc
     */
    public function entity(): string
    {
        return Rental::class;
    }

    /**
     * @inheritdoc
     */
    public function getActiveRentalByCar(int $carId): ?Rental
    {
        try {
            return $this->withCriteria(new WhereIsOrNotNull('returned_at'))
                ->findWhereFirst('car_id', $carId);

        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    /**
     * @inheritdoc
     */
    public function getActiveRentalByUser(int $userId): ?Rental
    {
        try {
            return $this->withCriteria(new WhereIsOrNotNull('returned_at'))
                ->findWhereFirst('user_id', $userId);

        } catch (ModelNotFoundException $e) {
            return null;
        }
    }
}
