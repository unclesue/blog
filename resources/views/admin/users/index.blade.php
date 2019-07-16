{{--@extends(request()->get('layout'))--}}
@foreach ($users as $user)
    <p>{{ $user->name }}</p>
@endforeach
