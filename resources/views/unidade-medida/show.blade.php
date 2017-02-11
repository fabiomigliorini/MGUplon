<h1>Unidade de Medida | {{ $model->unidademedida }}</h1>
<p>
    <a href="{{ url("unidade-medida") }}">Listagem</a> | 
    <a href="{{ url("unidade-medida/create") }}">Nova</a> | 
    <a href="{{ url("unidade-medida/$model->codunidademedida/edit") }}">Editar</a>
</p>
<div class="row">
  <div class="col-lg-12">
      <table class="detail-view table table-striped table-condensed" border="1"> 
        <tbody>  
          <tr> 
            <th class="col-md-2">#</th> 
            <td class="col-md-10">{{ $model->codunidademedida }}</td> 
          </tr>
          <tr> 
            <th>Descrição</th> 
            <td>{{ $model->unidademedida }}</td> 
          </tr>
          <tr> 
            <th>Sigla</th> 
            <td>{{ $model->sigla }}</td> 
          </tr>
        </tbody> 
      </table>
  </div>    
</div>
