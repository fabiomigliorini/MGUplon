@extends('layouts.default')
@section('content')

<div class='row'>
    <div class='col-md-12'>
        <div class='card'>
          <h4 class="card-header">
            <a href="{{ url('produto', $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->codproduto) }}">
              {{ $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->produto }}
              
            </a>
          </h4>
          <div class='card-block'>
            <div class="row">
              <div class="col-md-1">
                <ul class="nav nav-pills flex-column">
                  <li class="nav-item">
                    <a class="nav-link active" href="#">Active</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link disabled" href="#">Disabled</a>
                  </li>
                </ul>                
                <ul class="nav nav-pills flex-column">
                  <li class="nav-item">
                    <a class="nav-link active" href="#">Físico</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">Fiscal</a>
                  </li>
                </ul>                
              </div>
              <div class="col-md-1">
                <ul class="nav nav-pills flex-column">
                  <li class="nav-item">
                    <a class="nav-link active" href="#">Active</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link disabled" href="#">Disabled</a>
                  </li>
                </ul>                
              </div>
              <div class="col-md-10">
                <ul class="nav nav-pills">
                  @foreach ($anteriores as $ant)
                    <li class="nav-item">
                      <a class="nav-link" href="{{ url('estoque-mes', $ant->codestoquemes) }}">{{ $ant->mes->format('M/Y') }}</a>
                    </li>
                  @endforeach
                  <li class="nav-item">
                      <a class="nav-link active" href="{{ url('estoque-mes', $model->codestoquemes) }}">{{ $model->mes->format('M/Y') }}</a>
                  </li>
                  @foreach ($proximos as $prox)
                    <li class="nav-item">
                      <a class="nav-link" href="{{ url('estoque-mes', $prox->codestoquemes) }}">{{ $prox->mes->format('M/Y') }}</a>
                    </li>
                  @endforeach
                </ul>   
                <br>
                <div class='col-md-6'>
                  <table class='table table-sm'>
                    <thead>
                    <tr>
                      <th>
                        &nbsp;
                      </th>
                      <th class='text-right'>
                        {{ $model->EstoqueSaldo->EstoqueLocalProdutoVariacao->ProdutoVariacao->Produto->UnidadeMedida->sigla }}
                      </th>
                      <th class='text-right '>
                        R$
                      </th>
                    </tr>
                    </thead>
                    <tr>
                      <td>
                        Saldo Inicial
                      </td>
                      <td class='text-right {{ ($model->inicialquantidade>=0)?'text-info':'text-danger' }}'>
                        {{ formataNumero($model->inicialquantidade, 3) }}
                      </td>
                      <td class='text-right {{ ($model->inicialvalor>=0)?'text-info':'text-danger' }}'>
                        {{ formataNumero($model->inicialvalor, 2) }}
                      </td>
                    </tr>
                    <tr>
                      <td>
                        Entradas
                      </td>
                      <td class='text-right text-info'>
                        {{ formataNumero($model->entradaquantidade, 3) }} 
                      </td>
                      <td class='text-right text-info'>
                        {{ formataNumero($model->entradavalor, 2) }}
                      </td>
                    </tr>
                    <tr>
                      <td>
                        Saídas
                      </td>
                      <td class='text-right text-danger'>
                        {{ formataNumero($model->saidaquantidade * -1, 3) }}
                      </td>
                      <td class='text-right text-danger'>
                        {{ formataNumero($model->inicialvalor * -1, 2) }}
                      </td>
                    </tr>
                    <tfoot>
                    <tr>
                      <th>
                        Saldo Final
                      </th>
                      <th class='text-right {{ ($model->saldoquantidade>=0)?'text-info':'text-danger' }}'>
                        {{ formataNumero($model->saldoquantidade, 3) }}
                      </th>
                      <th class='text-right {{ ($model->saldovalor>=0)?'text-info':'text-danger' }}'>
                        {{ formataNumero($model->saldovalor, 3) }}
                      </th>
                    </tr>
                    <tr>
                      <th>
                        Custo Médio
                      </th>
                      <th class='text-right text-info' colspan="2">
                        {{ formataNumero($model->customedio, 6) }}
                      </th>
                    </tr>
                    </tfoot>
                  </table>
                </div>
                  <?php /*
                  @include('layouts.includes.datatable.html', ['id' => 'datatable', 'colunas' => ['URL', 'Inativo Desde', '#', 'Usuario', 'Pessoa', 'Filial']])
                  */
                  ?>
              </div>
            </div>
          
            
                <table class="table table-bordered table-striped table-hover table-sm col-md-6">
                  <tbody>  
                    <tr> 
                      <th>#</th> 
                      <td>{{ $model->codestoquemes }}</td> 
                    </tr>
                    <tr> 
                      <th>Estoque Mes</th> 
                      <td>{{ $model->mes }}</td> 
                    </tr>
                    <tr> 
                      <th>Codestoquesaldo</th> 
                      <td>{{ $model->codestoquesaldo }}</td> 
                    </tr>
                    <tr> 
                      <th>Inicialquantidade</th> 
                      <td>{{ $model->inicialquantidade }}</td> 
                    </tr>
                    <tr> 
                      <th>Inicialvalor</th> 
                      <td>{{ $model->inicialvalor }}</td> 
                    </tr>
                    <tr> 
                      <th>Entradaquantidade</th> 
                      <td>{{ $model->entradaquantidade }}</td> 
                    </tr>
                    <tr> 
                      <th>Entradavalor</th> 
                      <td>{{ $model->entradavalor }}</td> 
                    </tr>
                    <tr> 
                      <th>Saidaquantidade</th> 
                      <td>{{ $model->saidaquantidade }}</td> 
                    </tr>
                    <tr> 
                      <th>Saidavalor</th> 
                      <td>{{ $model->saidavalor }}</td> 
                    </tr>
                    <tr> 
                      <th>Saldoquantidade</th> 
                      <td>{{ $model->saldoquantidade }}</td> 
                    </tr>
                    <tr> 
                      <th>Saldovalor</th> 
                      <td>{{ $model->saldovalor }}</td> 
                    </tr>
                    <tr> 
                      <th>Customedio</th> 
                      <td>{{ $model->customedio }}</td> 
                    </tr>
                  </tbody> 
                </table>
                <div class='clearfix'></div>
            </div>
        </div>
    </div>
</div>

@section('buttons')

    <a class="btn btn-secondary btn-sm" href="{{ url("estoque-mes/$model->codestoquemes/edit") }}"><i class="fa fa-pencil"></i></a>
    @if(empty($model->inativo))
        <a class="btn btn-secondary btn-sm" href="{{ url("estoque-mes/$model->codestoquemes/inactivate") }}" data-activate data-question="Tem certeza que deseja inativar '{{ $model->mes }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-ban"></i></a>
    @else
        <a class="btn btn-secondary btn-sm" href="{{ url("estoque-mes/$model->codestoquemes/activate") }}" data-activate data-question="Tem certeza que deseja ativar '{{ $model->mes }}'?" data-after="recarregaDiv('content-page')"><i class="fa fa-circle-o"></i></a>
    @endif
    <a class="btn btn-secondary btn-sm" href="{{ url("estoque-mes/$model->codestoquemes") }}" data-delete data-question="Tem certeza que deseja excluir '{{ $model->mes }}'?" data-after="location.replace('{{ url('estoque-mes') }}');"><i class="fa fa-trash"></i></a>                
    
@endsection
@section('inactive')

    @include('layouts.includes.inactive', [$model])
    
@endsection
@section('creation')

    @include('layouts.includes.creation', [$model])
    
@endsection
@section('inscript')

@include('layouts.includes.datatable.assets')

@include('layouts.includes.datatable.js', ['id' => 'datatable', 'url' => url('usuario/datatable'), 'order' => $filtro['order'], 'filtros' => ['codusuario', 'usuario', 'codfilial', 'codpessoa', 'inativo'] ])

<script type="text/javascript">
    $(document).ready(function () {
        
    });

</script>
@endsection
@stop