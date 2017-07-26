@extends('layouts.app')

@section('title', "Return the car \"{$car['model']}\"")
@section('meta-description', "Return from rent the car - {$car['model']}")

@section('content')
  <section>
    @component('components.page-header')
      @slot('header') Return from rent the car "{{ $car['model'] }}" @endslot
      @slot('icon') fa-arrow-left @endslot
    @endcomponent

    <div class="container">
      <form method="POST" action="{{ route('cars.rent.return', ['id' => $car['id']]) }}">
        {{ csrf_field() }}

        <div class="form-group row{{ $errors->has('returned_to') ? ' has-danger' : '' }}">
          <label for="returned_to" class="form-control-label col-md-3 col-form-label">
            Returned To</label>

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
            <i class="fa fa-floppy-o fa-lg mr-1" aria-hidden="true"></i> Return</button>
        </div>
      </form>

    </div>
  </section>
@endsection
