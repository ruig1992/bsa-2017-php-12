<?php
namespace App\Services\Rental\Exceptions;

/**
 * Class RentalException
 * @package App\Services\Rental\Exceptions
 */
class RentalException extends \Exception
{
    /**
     * @var int
     */
    protected $code = 403;
}
