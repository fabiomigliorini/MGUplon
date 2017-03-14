<div class='row'>
  <div class="card">
    <h4 class="card-header">Saldos</h4>
    <div class="card-block">
        <div class="card-text">
          <table class="table table-bordered table-striped table-hover table-sm">
            <thead>
              <tr>
                <th>
                  Variações
                </th>
                @foreach ($pivot['locais'] as $codestoquelocal => $local)
                  <th style='text-align: center'>
                    {{ $local['estoquelocal'] }}
                  </th>
               @endforeach          
               <th style="text-align: center">
                  Total
                </th>
              </tr>
            </thead>
            <tbody>
            @foreach ($pivot['variacoes'] as $codprodutovariacao => $variacao)
              <tr>
                <th>
                  {{ $variacao['variacao'] or '{ Sem Variação }' }}
                </th>
                @foreach ($pivot['locais'] as $codestoquelocal => $local)
                  @if (isset($pivot['data'][$codestoquelocal][$codprodutovariacao]))
                    <td style='text-align: right' class='{{ $pivot['data'][$codestoquelocal][$codprodutovariacao]->codestoquesaldo == $saldo->codestoquesaldo?'table-info':'' }}'>
                      <a href="{{ url('estoque-saldo', $pivot['data'][$codestoquelocal][$codprodutovariacao]->codestoquesaldo) }}">
                        {{ formataNumero($pivot['data'][$codestoquelocal][$codprodutovariacao]->saldoquantidade, 3) }}
                      </a>
                    </td>
                  @else
                    <td>&nbsp;</td>
                  @endif
                @endforeach
                <th style='text-align: right'>
                  {{ formataNumero($variacao['saldoquantidade'], 3) }}
                </th>
              </tr>
            @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th>
                  Total
                </th>
                @foreach ($pivot['locais'] as $codestoquelocal => $local)
                  <th style='text-align: right'>
                    {{ formataNumero($local['saldoquantidade'], 3) }}
                  </th>
               @endforeach          
               <th style="text-align: right">
                 {{ formataNumero($pivot['saldoquantidade'], 3) }}
               </th>
              </tr>
            </tfoot>
          </table>
        </div>
    </div>
  </div>
</div>
  