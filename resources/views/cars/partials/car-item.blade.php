<div class="card car-card">
  <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">

    <div>
      <h2 class="card-title h4 mb-0">
        @if ($vMode === 'index')
          <a href="{{ route('cars.show', ['id' => $car['id']]) }}">
            {{ $car['model'] }}
          </a>
        @else
          {{ $car['model'] }}
        @endif
      </h2>
    </div>


    @if ($vMode === 'show')
    <div class="d-flex flex-wrap justify-content-end mt-3 mt-sm-0">
      {{-- @can('cars.update') --}}
      <div class="p-1">
        <a href="{{ route('cars.edit', ['id' => $car['id']]) }}"
          class="btn btn-success edit-button">
            <i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i>
            <span class="sr-only ml-1">Edit</span>
        </a>
      </div>
      {{-- @endcan --}}

      {{-- @can('cars.delete') --}}
      <div class="p-1">
        <button type="submit" class="btn btn-danger delete-button" form="delete-form">
          <i class="fa fa-trash-o fa-lg" aria-hidden="true"></i>
          <span class="sr-only ml-1">Delete</span>
        </button>

        <form id="delete-form" action="{{ route('cars.destroy', ['id' => $car['id']]) }}"
          method="POST" style="display:none">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}</form>
      </div>
      {{-- @endcan --}}
    </div>

    @endif
  </div>

  <div class="card-block">
    <dl class="row mb-0">

    @if ($vMode === 'show')
      <dt class="col-sm-5 col-md-3 car-field">Registration number</dt>
      <dd class="col-sm-7 col-md-9">{{ $car['registration_number'] }}</dd>

      <dt class="col-sm-5 col-md-3 car-field">Year</dt>
      <dd class="col-sm-7 col-md-9">{{ $car['year'] }}</dd>

      <dt class="col-sm-5 col-md-3 car-field">Mileage</dt>
      <dd class="col-sm-7 col-md-9">{{ $car['mileage'] }}</dd>
    @endif

      <dt class="col-sm-5 col-md-3 car-field">Color</dt>
      <dd class="col-sm-7 col-md-9">{{ $car['color'] }}</dd>

      <dt class="col-sm-5 col-md-3 car-field">Price</dt>
      <dd class="col-sm-7 col-md-9">{{ $car['price'] }}</dd>
    </dl>
  </div>

  @if ($vMode === 'show')
    <div class="cart-footer">
      <a href="{{ route('cars.rent', ['id' => $car['id']]) }}"
        class="btn btn-primary rent-button">
          <i class="fa fa-arrow-right mr-1" aria-hidden="true"></i> Rent</a>

      <button type="submit" class="btn btn-warning rent-return-button" form="rent-return-form">
      <i class="fa fa-arrow-left mr-1" aria-hidden="true"></i> Return from Rent</button>

      <form id="rent-return-form" action="{{ route('cars.rent.return') }}"
        method="POST" style="display:none">
          {{ csrf_field() }}</form>
      </div>
    </div>
  @endif
</div>
