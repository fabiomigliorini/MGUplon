<div id="div-variacoes">
    <?php
    $pvs = $model->ProdutoVariacaoS()->orderBy(DB::raw("coalesce(variacao, '')"), 'ASC')->get();
    ?>
    <ul class="list-group list-group-striped list-group-hover">
        @foreach ($pvs as $pv)
            <li class="list-group-item">
                <div>
                    <strong>
                        @if (!empty($pv->variacao))
                            {{ $pv->variacao }}
                        @else
                            <i class='text-muted'>{ Sem Variação }</i>
                        @endif
                        @if (!empty($pv->codmarca))
                            <a href="{{ url("marca/$pv->codmarca") }}">
                                {{ $pv->Marca->marca }}
                            </a>
                        @endif
                    </strong>
                    <div class="pull-right">
                        {{ $pv->referencia }}
                        &nbsp;
                        <a href="{{ url("produto-variacao/$pv->codprodutovariacao/edit") }}"><i class="fa fa-pencil"></i></a>
                        <a href="{{ url("produto-variacao/$pv->codprodutovariacao") }}" data-delete data-question="Tem certeza que deseja excluir a variação '{{ $pv->variacao }}'?" data-after="recarregaDiv('div-variacoes');"><i class="fa fa-trash"></i></a>
                    </div>
                </div>
                    
                <?php
                $pbs = $pv->ProdutoBarraS()->leftJoin('tblprodutoembalagem as pe', 'pe.codprodutoembalagem', '=', 'tblprodutobarra.codprodutoembalagem')
                   ->orderBy(DB::raw('coalesce(pe.quantidade, 0)'), 'ASC')
                   ->select([
                       'tblprodutobarra.codproduto',
                       'tblprodutobarra.codprodutobarra',
                       'tblprodutobarra.barras',
                       'tblprodutobarra.referencia',
                       'tblprodutobarra.codmarca',
                       'tblprodutobarra.codprodutoembalagem',
                       'tblprodutobarra.variacao',
                       'pe.quantidade',
                       'pe.codunidademedida',
                   ])
                   ->with('ProdutoEmbalagem')->get();
                //dd($pbs);
                ?>
                <div class="row">
                    @foreach ($pbs as $pb)
                    <div class="col-md-6 small">
                        {{ $pb->barras }}
                        <span class='text-muted'>
                            {{ $pb->referencia }}
                            {{ $pb->variacao }}
                        </span>
                        <span class="text-muted pull-right">
                            @if (!empty($pb->codprodutoembalagem))
                                {{ $pb->ProdutoEmbalagem->descricao }}
                            @else
                                {{ $model->UnidadeMedida->sigla }}
                            @endif
                            &nbsp;
                            <a href="{{ url("produto-barra/{$pb->codprodutobarra}/edit") }}"><i class="fa fa-pencil"></i></a>
                            <a href="{{ url("produto-barra/{$pb->codprodutobarra}") }}" data-delete data-question="Tem certeza que deseja excluir o Código de Barras '{{ $pb->barras }}'?" data-after="recarregaDiv('div-variacoes');"><i class="fa fa-trash"></i></a>
                        </span>
                    </div>
                    @endforeach
                </div>
                <div class="clearfix"></div>
            </li>
        @endforeach
    </ul>
</div>
