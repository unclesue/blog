@section('header')
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/square/blue.css') }}">
@stop
@include('admin.header')

<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="/admin"><b>Admin</b>LTE</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf

            <div class="form-group has-feedback @error('username') has-error @enderror">
                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" autocomplete="username" autofocus>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

                @error('username')
                <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group has-feedback @error('password') has-error @enderror">
                <input id="password" type="password" class="form-control" name="password" autocomplete="current-password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                @error('password')
                <span class="help-block">{{ $message }}</span>
                @enderror
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

@section('footer')
    <script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' /* optional */
            });
        });
    </script>
@stop
@include('admin.footer')
