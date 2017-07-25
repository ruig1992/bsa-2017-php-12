<?php
namespace App\Managers\Eloquent;

use App\Entity\Rental;
use Illuminate\Support\Collection;
use App\Managers\AbstractEntityManager;
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
}
