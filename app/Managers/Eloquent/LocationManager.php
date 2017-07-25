<?php
namespace App\Managers\Eloquent;

use App\Entity\Location;
use Illuminate\Support\Collection;
use App\Managers\AbstractEntityManager;
use App\Managers\Contracts\LocationManager as LocationManagerContract;

/**
 * Class LocationManager
 * @package App\Managers\Eloquent
 */
class LocationManager extends AbstractEntityManager implements LocationManagerContract
{
    /**
     * @inheritdoc
     */
    public function entity(): string
    {
        return Location::class;
    }
}
