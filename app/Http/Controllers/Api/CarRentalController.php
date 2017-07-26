<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Managers\Contracts\CarManager;
use App\Http\Requests\{StoreCarRental, StoreCarReturn};
use App\Services\Rental\Contracts\{RentalService, ReturnService};

/**
 * Class CarRentalController
 * @package App\Http\Controllers\Api
 */
class CarRentalController extends Controller
{
    /**
     * @var \App\Managers\Contracts\CarManager
     */
    protected $cars;
    /**
     * @var \App\Services\Rental\Contracts\RentalService
     */
    protected $rentalService;
    /**
     * @var \App\Services\Rental\Contracts\ReturnService
     */
    protected $returnService;

    /**
     * @param \App\Managers\Contracts\CarManager $cars
     * @param \App\Services\Rental\Contracts\RentalService $rentalService
     * @param \App\Services\Rental\Contracts\ReturnService $returnService
     */
    public function __construct(
        CarManager $cars,
        RentalService $rentalService,
        ReturnService $returnService
    ) {
        $this->cars = $cars;
        $this->rentalService = $rentalService;
        $this->returnService = $returnService;
    }

    /**
     * Rents the car by its id.
     *
     * @param \App\Http\Requests\StoreCarRental $request
     *    Contains the rules for validating the car rental data from form request
     * @param int $carId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentCar(StoreCarRental $request, int $carId): JsonResponse
    {
        $rental = $this->rentalService->rent(
            Auth::user()->id,
            $carId,
            $request->only(['rented_from',])
        );

        return response()->json($rental);
    }

    /**
     * Returns the car from a rent.
     *
     * @param \App\Http\Requests\StoreCarReturn $request
     *    Contains the rules for validating the car return from rent data from form request
     * @param int $carId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function returnCar(StoreCarReturn $request, int $carId): JsonResponse
    {
        $rental = $this->returnService->returnFromRent(
            Auth::user()->id,
            $carId,
            $request->only(['returned_to',])
        );

        return response()->json($rental);
     }
}
