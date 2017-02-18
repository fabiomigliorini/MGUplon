@extends('layouts.default')
@section('content')
<?php
use MGLara\Library\Breadcrumb\Breadcrumb;
$bc = new Breadcrumb('Dashboard');
$bc->addItem('Dashboard', url(''));
?>
<div class="row">
    <div class="col-xs-6">
        <!-- {!! Form::select2Marca('codmarca', 1, ['class' => 'form-control', 'id'=>'codmarca', 'placeholder' => 'Pessoa', 'ativo' => 9]) !!} -->
    </div>
</div>
@section('inscript')
<script type="text/javascript"></script>
@endsection
@stop