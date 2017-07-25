<?php
namespace App\Services\Rental\Exceptions;

use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * Class UserNotReturnedCar
 */
class UserNotReturnedCar extends AuthorizationException
{
    /**
     * @var string
     */
    protected $message = 'You have not returned the car #{car_id} that you rented on {rented_at}';

    /**
     * @param array $rental
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(array $rental, int $code = 0, \Throwable $previous = null)
    {
        $rental['rented_at'] = (new Carbon($rental['rented_at']))
            ->format('d.m.Y, H:i');

        $this->message = str_replace([
            '{car_id}', '{rented_at}',
        ], [
            $rental['car_id'], $rental['rented_at']
        ], $this->message);
    }
}
