<h1>Unidade de Medida | Nova</h1>
<p>
    <a href="{{ url("unidade-medida") }}">Listagem</a>
</p>

{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-unidade-medida', 'route' => 'unidade-medida.store']) !!}
    @include('unidade-medida.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
