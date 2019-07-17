@extends(request()->get('layout'))

@section('content')
    <!-- Content Header (Page header) -->
    {{--@include('admin.partials.breadcrumb')--}}

    <!-- Main content -->
    <section class="content">
        <form action="{{ route('users.create') }}" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" pjax-container>
            @csrf

            <button type="submit" class="btn btn-primary">提交</button>
        </form>
    </section>
@stop
