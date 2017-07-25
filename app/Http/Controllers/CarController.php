<?php
namespace App\Http\Controllers;

use App\Managers\Contracts\{
    CarManager,
    UserManager,
    LocationManager
};
use App\Managers\Eloquent\Criteria\{
    Latest,
    EagerLoad
};
use App\Http\Requests\{
    StoreCar,
    StoreCarRental
};
use App\Jobs\SendNotificationEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Services\Rental\Contracts\RentalService;
use App\Traits\Jobs\DispatchCarStoredNotification;

/**
 * Class CarController
 * @package App\Http\Controllers
 */
class CarController extends Controller
{
    use DispatchCarStoredNotification;

    /**
     * @var \App\Managers\Contracts\CarManager
     */
    protected $cars;
    /**
     * @var \App\Managers\Contracts\UserManager
     */
    protected $users;
    /**
     * @var \App\Managers\Contracts\LocationManager
     */
    protected $locations;
    /**
     * @var \App\Services\Rental\Contracts\RentalService
     */
    protected $rentalService;

    /**
     * @param \App\Managers\Contracts\CarManager $cars
     * @param \App\Managers\Contracts\UserManager $users
     * @param \App\Managers\Contracts\LocationManager $locations
     * @param \App\Services\Rental\Contracts\RentalService $rentalService
     */
    public function __construct(
        CarManager $cars,
        UserManager $users,
        LocationManager $locations,
        RentalService $rentalService
    ) {
        $this->cars = $cars;
        $this->users = $users;
        $this->locations = $locations;
        $this->rentalService = $rentalService;

        $this->middleware('auth');
        $this->middleware('can:cars.create')->only(['create', 'store']);
    }

    /**
     * Gets and displays the list of all cars.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $cars = $this->cars
            ->withCriteria(new Latest('id'))
            ->paginate(6, ['id', 'model', 'color', 'price']);

        return view('cars.index', ['cars' => $cars]);
    }

    /**
     * Gets and displays the full information about the car by its id.
     *
     * @param  int $id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show(int $id)
    {
        $car = $this->cars->find($id);

        return view('cars.show', ['car' => $car->toArray()]);
    }

    /**
     * Shows the form for creating a new car.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        $users = $this->users->findAllForForm();

        return view('cars.create', ['users' => $users]);
    }

    /**
     * Stores a newly created car.
     *
     * @param \App\Http\Requests\StoreCar $request
     *    Contains the rules for validating the car data from form request
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function store(StoreCar $request)
    {
        $car = $this->cars->create($request->only([
            'model',
            'registration_number',
            'year',
            'color',
            'mileage',
            'price',
            'user_id',
        ]));

        $this->carStoredNotification($car);

        return redirect()->route('cars.index');
    }

    /**
     * Shows the form for editing the specified car by its id.
     *
     * @param  int $id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(int $id)
    {
        $car = $this->cars->find($id);

        $this->authorize('cars.update', $car);

        $users = $this->users->findAllForForm();

        return view('cars.edit', [
            'car' => $car->toArray(),
            'users' => $users,
        ]);
    }

    /**
     * Updates the specified car by its id.
     *
     * @param  \App\Http\Requests\StoreCar $request
     *    Contains the rules for validating the car data from form request
     * @param  int $id
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function update(StoreCar $request, int $id)
    {
        $this->authorize('cars.update');

        $this->cars->update($id, $request->only([
            'model',
            'registration_number',
            'year',
            'color',
            'mileage',
            'price',
            'user_id',
        ]));

        return redirect()->route('cars.show', ['id' => $id]);
    }

    /**
     * Deletes the specified car by its id.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function destroy(int $id)
    {
        $this->authorize('cars.delete');

        $this->cars->delete($id);

        return redirect()->route('cars.index');
    }

    /**
     * Shows the form for rent the car by its id.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function rent(int $id)
    {
        $car = $this->cars->find($id);
        $this->authorize('cars.rent.store', $car->id);

        $locations = $this->locations->findAll();

        return view('cars.rent', [
            'car' => $car->toArray(),
            'locations' => $locations->toArray(),
        ]);
    }

    /**
     * Store the car rental data.
     *
     * @param \App\Http\Requests\StoreCarRental $request
     *    Contains the rules for validating the car rental data from form request
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function storeRent(StoreCarRental $request)
    {
        $this->authorize('cars.rent.store', $request->car_id);

        $this->rentalService->rent(
            Auth::user(),
            $request->only([
                'car_id',
                'rented_from',
                'returned_to',
            ])
        );

        return redirect()->route('cars.show', ['id' => $request->car_id]);
    }

    public function returnFromRent()
    {
        $this->rentalService->closeRent(Auth::user());

        return redirect()->route('cars.index');
    }
}
