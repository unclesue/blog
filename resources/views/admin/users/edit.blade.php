@extends(request()->get('layout'))

@section('content')
    <!-- Content Header (Page header) -->
    {{--@include('admin.partials.breadcrumb')--}}

    <!-- Main content -->
    <section class="content">
        <form action="{{ route('users.update', $user->id) }}" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" pjax-container>
            @csrf

            <input name="name" type="text" value="{{ $user->name }}">
            <input name="test" type="text" value="{{ $user->profile->avatar }}">
            <input type="file" class="website_logo" name="profile[avatar]" />
            {{ method_field('PUT') }}

            <button type="submit" class="btn btn-primary">提交</button>
        </form>
    </section>
@stop
