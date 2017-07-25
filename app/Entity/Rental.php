<?php
namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Rental
 * @package App\Entity
 */
class Rental extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'car_id',
        'rented_from',
        'returned_to',
        'rented_at',
        'returned_at',
        'price',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'float',
    ];

    /**
     * Get the car that's rented.
     */
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    /**
     * Get the user who makes the rental.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the location where the car is taken in rent.
     */
    public function rentedFrom()
    {
        return $this->belongsTo(Location::class, 'rented_from');
    }

    /**
     * Get the location where the car returns from the rent.
     */
    public function returnedTo()
    {
        return $this->belongsTo(Location::class, 'returned_to');
    }
}
