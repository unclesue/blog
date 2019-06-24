<li class="dd-item" data-id="{{ $node['id'] }}">
    <div class="dd-handle">
        <i class="{{ $node['icon'] }}"></i>&nbsp;<strong>{{ $node['title'] }}</strong>&nbsp;&nbsp;&nbsp;<a href="{{ $node['uri'] }}" class="dd-nodrag">{{ $node['uri'] }}</a>
        <span class="pull-right dd-nodrag">
            <a href="admin/menu/edit"><i class="far fa-edit"></i></a>
            <a href="javascript:void(0);" data-id="{{ $node['id'] }}" class="tree_branch_delete"><i class="far fa-trash-alt"></i></a>
        </span>
    </div>
    @if(isset($node['children']))
        <ol class="dd-list">
            @foreach($node['children'] as $node)
                @include('admin.tree', $node)
            @endforeach
        </ol>
    @endif
</li>
