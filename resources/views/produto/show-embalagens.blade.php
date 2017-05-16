<ul class='list-group text-right' id="div-embalagens">
    <li class="list-group-item">
        <small class="pull-left text-muted">
            R$
        </small>
        <b style="font-size: xx-large">
        {{ formataNumero($model->preco) }}
        </b>
        <span class="text-muted">
            {{ $model->UnidadeMedida->unidademedida }}
            <a href="<?php echo url("produto-embalagem/create?codproduto={$model->codproduto}");?>">
                <i class="fa fa-plus"></i>
            </a>
        </span>
    </li>

    @foreach($model->ProdutoEmbalagemS()->orderBy('quantidade')->get() as $pe)
        <li class="list-group-item">
            <small class="pull-left text-muted">
                R$
            </small>
            <b style="font-size: large">
                @if (empty($pe->preco))
                    <i class="text-muted">
                        <small>
                        ({{ formataNumero($model->preco * $pe->quantidade) }})
                        </small>
                    </i>
                @else
                    {{ formataNumero($pe->preco) }}                            
                @endif
            </b>
            <span class="text-muted">
                <small>
                    {{ $pe->UnidadeMedida->unidademedida }} com
                    {{ formataNumero($pe->quantidade, 0) }}
                </small>

                <a href="{{ url("produto-embalagem/$pe->codprodutoembalagem/edit") }}"><i class="fa fa-pencil"></i></a>
                <a href="{{ url("produto-embalagem/$pe->codprodutoembalagem") }}" data-delete data-question="Tem certeza que deseja excluir a Embalagem '{{ $pe->UnidadeMedida->unidademedida }} com {{ formataNumero($pe->quantidade, 0) }}'?" data-after="recarregaDiv('div-embalagens')"><i class="fa fa-trash"></i></a>
            </span>
        </li>
    @endforeach
</ul>
