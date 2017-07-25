<?php
namespace App\Services\Rental\Exceptions\User;

use App\Services\Rental\Exceptions\RentalException;

/**
 * Class UserNotFound
 * @package App\Services\Rental\Exceptions\User
 */
class UserNotFound extends RentalException
{
    /**
     * @var int
     */
    protected $code = 404;

    /**
     * @param int $userId
     */
    public function __construct(int $userId)
    {
        $this->message = "The user #{$userId} not found";
    }
}
