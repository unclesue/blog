@extends(request()->get('layout'))

@section('content')
    <!-- Content Header (Page header) -->
    {{--@include('admin.partials.breadcrumb')--}}

    <!-- Main content -->
    <section class="content">
        <form method="POST" action="{{ route('users.index') }}" class="form-horizontal" accept-charset="UTF-8" pjax-container="1">

        </form>
    </section>
@stop
