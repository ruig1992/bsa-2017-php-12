<?php
namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Location
 * @package App\Entity
 */
class Location extends Model
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
        'name',
    ];

    /**
     * Get rentals which have the location as "rented_from".
     */
    public function rentedFrom()
    {
        return $this->hasMany(Rental::class, 'rented_from');
    }

    /**
     * Get rentals which have the location as "returned_to".
     */
    public function returnedTo()
    {
        return $this->hasMany(Rental::class, 'returned_to');
    }
}
