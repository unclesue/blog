<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">{{ $form->title() }}</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    {!! $form->open(['class' => "form-horizontal"]) !!}

    <div class="box-body">

        <div class="fields-group">

            @if($form->hasRows())
                @foreach($form->getRows() as $row)
                    {!! $row->render() !!}
                @endforeach
            @else
                @foreach($form->fields() as $field)
                    {!! $field->render() !!}
                @endforeach
            @endif


        </div>

    </div>
    <!-- /.box-body -->

<!-- /.box-footer -->
    {!! $form->close() !!}
</div>

