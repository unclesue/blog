<table class="table table-hover" id="{{ $grid->tableID }}">
    <thead>
        <tr>
            @foreach($grid->visibleColumns() as $column)
                <th class="column-{!! $column->getName() !!}">{{$column->getLabel()}}{!! $column->sorter() !!}</th>
            @endforeach
        </tr>
    </thead>

    <tbody>
        @foreach($grid->rows() as $row)
            <tr>
                @foreach($grid->visibleColumnNames() as $name)
                    <td class="column-{!! $name !!}">
                        {!! $row->column($name) !!}
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>

</table>
