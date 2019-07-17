@extends(request()->get('layout'))

@section('content')
    <!-- Content Header (Page header) -->
    {{--@include('admin.partials.breadcrumb')--}}

    <!-- Main content -->
    <section class="content">
        <p>{{ $user->id . '-' . $user->name }}</p>
    </section>
@stop
