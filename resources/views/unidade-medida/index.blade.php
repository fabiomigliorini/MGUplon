<h1>Unidades de Medida</h1>
<p>
    <a href="{{ url("unidade-medida/create") }}">Nova</a>
</p>
<ul>
    @foreach($model as $row)
    <li>
        <a href="{{ url("unidade-medida/$row->codunidademedida") }}">
            {{ $row->codunidademedida }} | {{ $row->unidademedida }} | {{ $row->sigla }}
        </a>
        <hr>
    </li>
    @endforeach
</ul>
<?php echo $model->appends(Request::session()->get('unidade-medida.index'))->render();?>