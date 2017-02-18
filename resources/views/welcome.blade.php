@extends('layouts.default')
@section('content')
<?php
use MGLara\Library\Breadcrumb\Breadcrumb;
$bc = new Breadcrumb('Dashboard');
$bc->addItem('Dashboard', url(''));
?>
<div class="row">
    <div class="col-xs-6">
    	<input  name="" type="hidden" value="931808" id="produto"> 
		{!! Form::select2ProdutoVariacao('id', null, ['class' => 'form-control', 'id' => 'id', 'codproduto'=>'produto']) !!}        
    </div>
</div>
@section('inscript')
<script type="text/javascript"></script>
@endsection
@stop