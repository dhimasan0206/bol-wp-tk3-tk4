@extends('adminlte::page')

@section('title', 'Customer Detail')

@section('content_header')
    <h1>Customer Detail</h1>
@stop

@section('content')
    <div>Name: {{ $user->name }}</div>
    <div>Email: {{ $user->email }}</div>
    <div>
        <a href="{{route('customers.edit', ['customer' => $user->id])}}">Edit</a>
        <form action="{{route('customers.destroy', ['customer' => $user->id])}}" method="POST">
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
