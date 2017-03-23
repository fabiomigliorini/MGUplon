<table class="table table-striped table-condensed table-sm table-hover">
    <thead>
        <tr>
            <th rowspan="2" colspan='3'>Movimento</th>
            <th colspan="2" class="text-center">Entrada</th>
            <th colspan="2" class="text-center">Saída</th>
            <th colspan="2" class="text-center">Saldo</th>
            <th rowspan="2" class="text-right">Custo Médio</th>
            <th rowspan="2" class="">Documento</th>
        </tr>
        <tr>
            <th class="text-right">Quantidade</th>
            <th class="text-right">Valor</th>
            <th class="text-right">Quantidade</th>
            <th class="text-right">Valor</th>
            <th class="text-right">Quantidade</th>
            <th class="text-right">Valor</th>            
        </tr>
        <tr>
            <th colspan='3'>Saldo Inicial</th>
            <td class="text-right">{{ formataNumero($movs['inicial']['entradaquantidade'], 3) }}</td>
            <td class="text-right ">{{ formataNumero($movs['inicial']['entradavalor'], 2) }}</td>
            <td class="text-right ">{{ formataNumero($movs['inicial']['saidaquantidade'], 3) }}</td>
            <td class="text-right ">{{ formataNumero($movs['inicial']['saidavalor'], 2) }}</td>
            <td class="text-right ">{{ formataNumero($movs['inicial']['saldoquantidade'], 3) }}</td>
            <td class="text-right ">{{ formataNumero($movs['inicial']['saldovalor'], 3) }}</td>
            <td class="text-right ">{{ formataNumero($movs['inicial']['customedio'], 6) }}</td>
            <td></td>
        </tr>
    </thead>
    <tbody>
      @foreach ($movs['movimento'] as $mov)
        <tr>
            <td>{{ $mov['data']->format('d/M') }}</td>
            <td>{{ $mov['data']->format('H:i') }}</td>
            <td>
              {{ $mov['descricao'] }}
              @if (!empty($mov['urlestoquemesrelacionado']))
              <a class='pull-right' href='{{ $mov['urlestoquemesrelacionado'] }}'>
                <i class='fa fa-external-link'></i>
              </a>
              @endif
            </td>
            <td class="text-right ">{{ formataNumero($mov['entradaquantidade'], 3) }}</td>
            <td class="text-right ">{{ formataNumero($mov['entradavalor'], 2) }}</td>
            <td class="text-right ">{{ formataNumero($mov['saidaquantidade'], 3) }}</td>
            <td class="text-right ">{{ formataNumero($mov['saidavalor'], 2) }}</td>
            <td class="text-right ">{{ formataNumero($mov['saldoquantidade'], 3) }}</td>
            <td class="text-right ">{{ formataNumero($mov['saldovalor'], 3) }}</td>
            <td class="text-right ">{{ formataNumero($mov['customedio'], 6) }}</td>
            <td>
              <a href='{{ $mov['urldocumento'] }}'>
                {{ $mov['documento'] }}
              </a>
              <a href='{{ $mov['urlpessoa'] }}'>
                {{ $mov['pessoa'] }}
              </a>
              @if (!empty($mov['observacoes']))
                <span>
                  {!! nl2br(e($mov['observacoes'])) !!}
                </span>
              @endif
            </td>
        </tr>
      @endforeach
      <!--
                <tr>
                        <td>
                <span class="pull-right">
                    13/03/17 11:06
                </span>
                Transferencia Saida
                
                                    P/
                    <a href="http://192.168.1.205/MGLara/estoque-mes/456541">
                        Botanico
                    </a>
                            </td>
            <td class="text-right"></td>
            <td class="text-right"></td>
            <td class="text-right">144,000</td>
            <td class="text-right">399,13</td>
            <td class="text-right ">72,000</td>
            <td class="text-right " "="">199,57</td>
            <td class="text-right">2,771736</td>
            <td>
                                
                                    <a href="http://192.168.1.205/MGLara/negocio/688491">#00688491</a>
                    -
                    <a href="http://192.168.1.205/MGLara/pessoa/3508">MG Papelaria Botanico</a>
                                
                                
                
            </td>
        </tr>
                <tr>
                        <td>
                <span class="pull-right">
                    15/03/17 17:10
                </span>
                Transferencia Saida
                
                                    P/
                    <a href="http://192.168.1.205/MGLara/estoque-mes/468700">
                        Imperial
                    </a>
                            </td>
            <td class="text-right"></td>
            <td class="text-right"></td>
            <td class="text-right">24,000</td>
            <td class="text-right">66,52</td>
            <td class="text-right ">48,000</td>
            <td class="text-right " "="">133,05</td>
            <td class="text-right">2,771667</td>
            <td>
                                
                                    <a href="http://192.168.1.205/MGLara/negocio/692215">#00692215</a>
                    -
                    <a href="http://192.168.1.205/MGLara/pessoa/3555">MG Papelaria Imperial</a>
                                
                                
                
            </td>
        </tr>
      -->
    </tbody>
    <tfoot>
        <tr>
            <th colspan='3'>Totais</th>
            <td class="text-right ">{{ formataNumero($movs['total']['entradaquantidade'], 3) }}</td>
            <td class="text-right ">{{ formataNumero($movs['total']['entradavalor'], 2) }}</td>
            <td class="text-right ">{{ formataNumero($movs['total']['saidaquantidade'], 3) }}</td>
            <td class="text-right ">{{ formataNumero($movs['total']['saidavalor'], 2) }}</td>
            <td class="text-right ">{{ formataNumero($movs['total']['saldoquantidade'], 3) }}</td>
            <td class="text-right ">{{ formataNumero($movs['total']['saldovalor'], 3) }}</td>
            <td class="text-right ">{{ formataNumero($movs['total']['customedio'], 6) }}</td>
            <td></td>
        </tr>
    </tfoot>
    
</table>