@extends('layouts.app')

@section('title', $car['model'])
@section('meta-description', 'Full information about the car - ' . $car['model'])

@section('content')
  <article>
    @component('components.page-header')
      @slot('header') Car Info @endslot
      @slot('icon') fa-car @endslot
    @endcomponent

    @if (session('error'))
      @component('components.alert')
        @slot('errorCode') 403 @endslot
        {{ session('error') }}
      @endcomponent
    @endif

    @include('cars.partials.car-item', ['vMode' => 'show'])
  </article>

@endsection
