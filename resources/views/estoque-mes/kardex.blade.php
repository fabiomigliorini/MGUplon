<table class="table table-striped table-sm table-hover">
    <thead>
        <tr>
            <th rowspan="2" colspan='3'>Movimento</th>
            <th colspan="2" class="text-center">Entrada</th>
            <th colspan="2" class="text-center">Saída</th>
            <th colspan="2" class="text-center">Saldo</th>
            <th rowspan="2" class="text-right">Custo</th>
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
            <td class="text-right text-muted small">{{ formataNumero($movs['inicial']['entradavalor'], 2) }}</td>
            <td class="text-right ">{{ formataNumero($movs['inicial']['saidaquantidade'], 3) }}</td>
            <td class="text-right text-muted small">{{ formataNumero($movs['inicial']['saidavalor'], 2) }}</td>
            <td class="text-right {{ ($movs['inicial']['saldoquantidade']<0)?'text-danger':'text-primary' }} ">{{ formataNumero($movs['inicial']['saldoquantidade'], 3) }}</td>
            <td class="text-right text-muted small">{{ formataNumero($movs['inicial']['saldovalor'], 2) }}</td>
            <td class="text-right ">{{ formataNumero($movs['inicial']['customedio'], 6) }}</td>
            <td></td>
        </tr>
    </thead>
    <tbody>
      @foreach ($movs['movimento'] as $mov)
        <tr>
            <td>{{ $mov['data']->format('d/M') }}</td>
            <td class="text-muted small">{{ $mov['data']->format('H:i') }}</td>
            <td>
              {{ $mov['descricao'] }}
              @if (!empty($mov['urlestoquemesrelacionado']))
                <a class='pull-right' href='{{ $mov['urlestoquemesrelacionado'] }}'>
                  <i class='fa fa-external-link'></i>
                </a>
              @endif
            </td>
            <td class="text-right ">{{ formataNumero($mov['entradaquantidade'], 3) }}</td>
            <td class="text-right text-muted small">{{ formataNumero($mov['entradavalor'], 2) }}</td>
            <td class="text-right ">{{ formataNumero($mov['saidaquantidade'], 3) }}</td>
            <td class="text-right text-muted small">{{ formataNumero($mov['saidavalor'], 2) }}</td>
            <td class="text-right {{ ($mov['saldoquantidade']<0)?'text-danger':'text-primary' }}">{{ formataNumero($mov['saldoquantidade'], 3) }}</td>
            <td class="text-right text-muted small">{{ formataNumero($mov['saldovalor'], 2) }}</td>
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
    </tbody>
    <tfoot>
        <tr>
            <th colspan='3'>Totais</th>
            <th class="text-right">{{ formataNumero($movs['total']['entradaquantidade'], 3) }}</th>
            <th class="text-right text-muted small">{{ formataNumero($movs['total']['entradavalor'], 2) }}</th>
            <th class="text-right">{{ formataNumero($movs['total']['saidaquantidade'], 3) }}</th>
            <th class="text-right text-muted small">{{ formataNumero($movs['total']['saidavalor'], 2) }}</th>
            <th class="text-right {{ ($movs['total']['saldoquantidade']<0)?'text-danger':'text-primary' }}">{{ formataNumero($movs['total']['saldoquantidade'], 3) }}</th>
            <th class="text-right text-muted small">{{ formataNumero($movs['total']['saldovalor'], 2) }}</th>
            <th class="text-right">{{ formataNumero($movs['total']['customedio'], 6) }}</th>
            <th></th>
        </tr>
    </tfoot>
    
</table>