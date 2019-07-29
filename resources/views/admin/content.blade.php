<div id="app">
    <h1>
        {{ $header ?: trans('admin.title') }}
        <small>{{ $description ?: trans('admin.description') }}</small>
    </h1>
    {!! $content !!}
</div>
@yield('header')
@yield('footer')
