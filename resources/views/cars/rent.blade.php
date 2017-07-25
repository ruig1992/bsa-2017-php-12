@extends('layouts.app')

@section('title', "Rent the car \"{$car['model']}\"")
@section('meta-description', "Rent the car - {$car['model']}")

@section('content')
  <section>
    @component('components.page-header')
      @slot('header') Rent the car "{{ $car['model'] }}" @endslot
      @slot('icon') fa-arrow-right @endslot
    @endcomponent

    <div class="container">
      <form method="POST" action="{{ route('cars.rent.store', ['id' => $car['id']]) }}">
        {{ csrf_field() }}

        <div class="form-group row{{ $errors->has('rented_from') ? ' has-danger' : '' }}">
          <label for="rented_from" class="form-control-label col-md-3 col-form-label">
            Rented From</label>

          <div class="col col-lg-8">
            <select id="rented_from" class="form-control{{ $errors->has('rented_from') ?
              ' form-control-danger' : '' }}" name="rented_from" autofocus>

                <option>__ select the location FROM __</option>

              @foreach ($locations as $location)
                <option value="{{ $location['id'] }}"
                  @if (old('rented_from') == $location['id']) selected @endif>
                  {{ $location['name'] }}
                </option>
              @endforeach
            </select>

            @if ($errors->has('rented_from'))
              <div class="form-control-feedback">{{ $errors->first('rented_from') }}</div>
            @endif
          </div>
        </div>

        <div class="form-group row{{ $errors->has('returned_to') ? ' has-danger' : '' }}">
          <label for="returned_to" class="form-control-label col-md-3 col-form-label">
            Returned To <i class="font-weight-normal">(optional)</i></label>

          <div class="col col-lg-8">
            <select id="returned_to" class="form-control{{ $errors->has('returned_to') ?
              ' form-control-danger' : '' }}" name="returned_to">

              <option disabled selected>__ select the location TO __</option>

              @foreach ($locations as $location)
                <option value="{{ $location['id'] }}"
                  @if (old('returned_to') == $location['id']) selected @endif>
                  {{ $location['name'] }}
                </option>
              @endforeach
            </select>

            @if ($errors->has('returned_to'))
              <div class="form-control-feedback">{{ $errors->first('returned_to') }}</div>
            @endif
          </div>
        </div>

        <div class="form-group mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="fa fa-floppy-o fa-lg mr-1" aria-hidden="true"></i> Rent</button>
        </div>
      </form>

    </div>
  </section>
@endsection
