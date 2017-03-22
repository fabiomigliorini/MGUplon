@extends('layouts.default')
@section('content')
<?php
    
    use Carbon\Carbon;
    Carbon::setLocale('pt_BR');
    $str_fiscal = ($fiscal)?'fiscal':'fisico';
    $saldodias = 9999999999999;
    if (!empty($elpv->vendadiaquantidadeprevisao)) {
        $saldodias = $es->saldoquantidade / $elpv->vendadiaquantidadeprevisao;
    }
    
?>
<div class='row'>
    <div class='col-md-2'>
        <div class='card'>
          <div class='card-block'>
            <ul class="nav nav-pills">
            @foreach (['fisico' => 'Fisico', 'fiscal' => 'Fiscal'] as $fis => $label)
              <div class='row'>
                <li class="nav-item col-md-12">
                  <a class="nav-link {{ ($str_fiscal == $fis)?'active':'' }}" href='{{ url("kardex/{$el->codestoquelocal}/{$pv->codprodutovariacao}/$fis/$ano/$mes") }}'>
                    {{ $label }}
                  </a>
                </li>            
              </div>
            @endforeach
            </ul>
          </div>
        </div>
      
        <div class='card'>
          <div class='card-block'>
            <ul class="nav nav-pills">
            @foreach ($els as $loc)
              <div class='row'>
                <li class="nav-item col-md-12">
                  <a class="nav-link {{ ($loc->codestoquelocal == $el->codestoquelocal)?'active':'' }}" href='{{ url("kardex/{$loc->codestoquelocal}/{$pv->codprodutovariacao}/$str_fiscal/$ano/$mes") }}'>
                    {{ $loc->estoquelocal }}
                  </a>
                </li>            
              </div>
            @endforeach
            </ul>
          </div>
        </div>
        <div class='card'>
          <div class='card-block'>
            <ul class="nav nav-pills">
            @foreach ($pvs as $var)
              <div class='row'>
                <li class="nav-item col-md-12">
                  <a class="nav-link {{ ($var->codprodutovariacao == $pv->codprodutovariacao)?'active':'' }}" href='{{ url("kardex/{$el->codestoquelocal}/{$var->codprodutovariacao}/$str_fiscal/$ano/$mes") }}'>
                    {{ $var->variacao or '{Sem Variação}' }}
                  </a>
                </li>            
              </div>
            @endforeach
            </ul>
          </div>
        </div>
    </div>
    
    <div class='col-md-10'>
        <div class='card'>
          <div class='card-block'>
            @if (!empty($elpv))
              @if (!empty($elpv->corredor))
                <p>
                Armazenado no corredor <b>{{ formataLocalEstoque($elpv->corredor, $elpv->prateleira, $elpv->coluna, $elpv->bloco) }}</b><br>
                </p>
              @endif
              @if (!empty($elpv->vendaanoquantidade))
                <p>
                  Vendeu <b>{{ formataNumero($elpv->vendadiaquantidadeprevisao, 8) }} </b>  {{ $pv->Produto->UnidadeMedida->sigla }}  por dia, 
                  totalizando <b>{{ formataNumero($elpv->vendabimestrequantidade, 0) }}</b> (R$ {{ formataNumero($elpv->vendabimestrevalor, 2) }})  no último bimestre, 
                  <b>{{ formataNumero($elpv->vendasemestrequantidade, 0) }}</b> (R$ {{ formataNumero($elpv->vendasemestrevalor, 2) }}) no semestre e 
                  <b>{{ formataNumero($elpv->vendaanoquantidade, 0) }}</b> (R$ {{ formataNumero($elpv->vendaanovalor, 2) }}) no ano, 
                  pelos cálculos feitos em {{ formataData($elpv->vendaultimocalculo, 'C') }}.
                </p>
              @endif
            
              @if (!empty($elpv->vencimento))
                
                
                @if ($elpv->vencimento->isPast())
                  <p class='text-danger'>Validade vencida <b>{{ $elpv->vencimento->diffForHumans()}}</b>!</p>
                @else
                  @if ($elpv->vencimento->diffInDays() > $saldodias)
                    <p class='text-success'>
                  @elseif ($elpv->vencimento->diffInDays() < 30)
                    <p class='text-danger'>
                  @else
                    <p class='text-warning'>
                  @endif
                  Validade vencerá <b>{{ $elpv->vencimento->diffForHumans()}}</b>!
                  </p>
                @endif
              @endif
              
            @endif
            @if (!empty($es))
              <p>
                Saldo Atual de <b>{{ formataNumero($es->saldoquantidade, 3) }}</b> {{ $pv->Produto->UnidadeMedida->sigla }} 
                custando R$ <b>{{ formataNumero($es->customedio, 6) }}</b> cada
                @if (!empty($es->saldovalor))
                  , totalizando R$ <b>{{ formataNumero($es->saldovalor, 2) }} </b>
                @endif
                @if (!empty($elpv->vendadiaquantidadeprevisao))
                  , suficiente para <b>{{ formataNumero($saldodias, 1) }} </b> dias
                @endif
                .
              </p>
              <p>
                @if (!empty($es->dataentrada))
                  Última entrada registrada em <b>{{ formataData($es->dataentrada, 'E') }}</b>
                @else
                  Não existe registro de entrada
                @endif
                ,
                @if (!empty($es->ultimaconferencia))
                  a última conferência foi realizada em <b>{{ formataData($es->ultimaconferencia, 'E') }}</b>
                @else
                  o saldo <b>nunca</b> foi <b>conferido</b>.
                @endif
              </p>
            @endif
            @if (!empty($elpv->estoqueminimo) && !empty($elpv->estoquemaximo))
              <p>
              Estoque
              @if (!empty($elpv->estoqueminimo))
                mínimo de <b>{{ formataNumero($elpv->estoqueminimo, 0) }}</b> {{ $pv->Produto->UnidadeMedida->sigla }}
                @if (!empty($elpv->estoqueminimo))
                 e
                @endif
              @endif
              @if (!empty($elpv->estoqueminimo))
                máximo de <b>{{ formataNumero($elpv->estoquemaximo, 0) }}</b> {{ $pv->Produto->UnidadeMedida->sigla }}
              @endif
              .
              </p>
            @endif
            
          </div>
        </div>
        <div class='card'>
          <div class='card-block'>
            <ul class="nav nav-pills">
              @forelse ($ems as $eml)
                <li class="nav-item">
                  <a class="nav-link {{ (!empty($em) && ($eml->codestoquemes == $em->codestoquemes))?'active':'' }}" href='{{ url("kardex/{$el->codestoquelocal}/{$pv->codprodutovariacao}/$str_fiscal/{$eml->mes->year}/{$eml->mes->month}") }}'>
                    {{ $eml->mes->format('M/Y') }}
                  </a>
                </li>
              @empty
                <li class="nav-item">
                  Não há movimentação em mês algum!
                </li>
              @endforelse
            </ul>
          </div>
        </div>
      
        <div class='card'>
          <div class='card-block'>
            @include('estoque-mes.kardex', $movs)
          </div>
        </div>
    </div>
</div>




@section('buttons')

    
@endsection
@section('inactive')

    @include('layouts.includes.inactive', [$em])
    
@endsection
@section('creation')

    @include('layouts.includes.creation', [$em])
    
@endsection
@section('inscript')

@include('layouts.includes.datatable.assets')

<?php 
/*
@include('layouts.includes.datatable.js', ['id' => 'datatable', 'url' => url('usuario/datatable'), 'order' => $filtro['order'], 'filtros' => ['codusuario', 'usuario', 'codfilial', 'codpessoa', 'inativo'] ])
*/
?>

<script type="text/javascript">
    $(document).ready(function () {
        
    });

</script>
@endsection
@stop