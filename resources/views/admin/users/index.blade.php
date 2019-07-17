@extends(request()->get('layout'))

@section('content')
    <!-- Content Header (Page header) -->
    {{--@include('admin.partials.breadcrumb')--}}

    <!-- Main content -->
    <section class="content">
        @foreach ($users as $user)
            <p>
                {{ $user->id . '-' . $user->name }}<a href=""></a>
                <a href="{{ route('users.show', $user->id) }}" class="grid-row-view"><i class="fa fa-eye"></i></a>
                <a href="{{ route('users.edit', $user->id) }}" class="grid-row-edit"><i class="fa fa-edit"></i></a>
                <a href="javascript:void(0);" data-id="131" class="grid-row-delete"><i class="fa fa-trash"></i></a>
            </p>
        @endforeach
    </section>
@stop
