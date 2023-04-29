@extends('adminlte::page')

@section('title', 'User Detail')

@section('content_header')
    <h1>User Detail</h1>
@stop

@section('content')
    <div>Name: {{ $user->name }}</div>
    <div>Email: {{ $user->email }}</div>
    <div>
        <a href="{{route('users.edit', ['user' => $user->id])}}">Edit</a>
        <form action="{{route('users.destroy', ['user' => $user->id])}}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit">Delete</button>
        </form>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
