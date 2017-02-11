@extends('layouts.default')
@section('content')

<p>
    <a href="{{ url("unidade-medida/create") }}">Nova</a>
</p>
<ul>
    @foreach($model as $row)
    <li>
        <a href="{{ url("unidade-medida/$row->codunidademedida") }}">
            {{ formataCodigo($row->codunidademedida) }} | {{ $row->unidademedida }} | {{ $row->sigla }}
        </a>
        <hr>
    </li>
    @endforeach
</ul>
<?php echo $model->appends(Request::session()->get('unidade-medida.index'))->render();?>

@section('inscript')
<script type="text/javascript"></script>
@endsection
@stop