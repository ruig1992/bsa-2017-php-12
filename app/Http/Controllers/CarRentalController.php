<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\{StoreCarRental, StoreCarReturn};
use App\Managers\Contracts\{CarManager, LocationManager};
use App\Services\Rental\Contracts\{RentalService, ReturnService};

/**
 * Class CarRentalController
 * @package App\Http\Controllers
 */
class CarRentalController extends Controller
{
    /**
     * @var \App\Managers\Contracts\CarManager
     */
    protected $cars;
    /**
     * @var \App\Managers\Contracts\LocationManager
     */
    protected $locations;
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
     * @param \App\Managers\Contracts\LocationManager $locations
     * @param \App\Services\Rental\Contracts\RentalService $rentalService
     * @param \App\Services\Rental\Contracts\ReturnService $returnService
     */
    public function __construct(
        CarManager $cars,
        LocationManager $locations,
        RentalService $rentalService,
        ReturnService $returnService
    ) {
        $this->cars = $cars;
        $this->locations = $locations;
        $this->rentalService = $rentalService;
        $this->returnService = $returnService;

        $this->middleware('auth');
    }

    /**
     * Shows the form for rent the car by its id.
     *
     * @param int $carId
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create(int $carId)
    {
        $this->authorize('cars.rent.store', $carId);

        $car = $this->cars->find($carId);
        $locations = $this->locations->findAll();

        return view('cars.rental.rent', [
            'car' => $car->toArray(),
            'locations' => $locations->toArray(),
        ]);
    }

    /**
     * Rents the car by its id.
     *
     * @param \App\Http\Requests\StoreCarRental $request
     *    Contains the rules for validating the car rental data from form request
     * @param int $carId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rentCar(StoreCarRental $request, int $carId): RedirectResponse
    {
        $this->authorize('cars.rent.store', $carId);

        $this->rentalService->rent(Auth::user()->id, $carId, $request->only([
            'rented_from',
        ]));

        return redirect()->route('cars.show', ['id' => $carId]);
    }

    /**
     * Shows the form for the car return from rent by car id.
     *
     * @param int $carId
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(int $carId)
    {
        $this->authorize('cars.rent.return', $carId);

        $car = $this->cars->find($carId);
        $locations = $this->locations->findAll();

        return view('cars.rental.return', [
            'car' => $car->toArray(),
            'locations' => $locations->toArray(),
        ]);
    }

    /**
     * Returns the car from a rent.
     *
     * @param \App\Http\Requests\StoreCarReturn $request
     *    Contains the rules for validating the car return from rent data from form request
     * @param int $carId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function returnCar(StoreCarReturn $request, int $carId): RedirectResponse
    {
        $this->authorize('cars.rent.return', $carId);

        $this->returnService->returnFromRent(Auth::user()->id, $carId, $request->only([
            'returned_to',
        ]));

        return redirect()->route('cars.index');
    }
}
