@extends('layouts.default')
@section('content')

<div class="collapse" id="collapsePesquisa">
  <div class="card">
    <h3 class="card-header">Pesquisa</h3>
    <div class="card-block">
        <div class="card-text">
            @yield ('filter')
        </div>
    </div>
  </div>
</div>

<div class="card-box table-responsive">
    @yield('datatable')
</div>

@section('inscript-layout')
@include('layouts.includes.datatable.assets')
@endsection
@stop
