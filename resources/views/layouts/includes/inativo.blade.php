@if (!empty($model->inativo))
    <span class="label label-danger" role="alert">
      Inativo desde {{ formataData($model->inativo, 'L') }}
    </span>
@endif
