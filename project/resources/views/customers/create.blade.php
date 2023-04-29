@extends('adminlte::page')

@section('title', 'Add New Customer')

@section('content_header')
    <h1>Add New Customer</h1>
@stop

@section('content')
    <form action="{{route('customers.store')}}" method="POST">
        @csrf
        <div>
            {!! Form::label('name', 'Name', []) !!}
        </div>
        <div>
            {!! Form::text('name', null, []) !!}
            @error('name')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div>
            {!! Form::label('email', 'Email', []) !!}
        </div>
        <div>
            {!! Form::email('email', null, []) !!}
            @error('email')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div>
            {!! Form::label('password', 'Password', []) !!}
        </div>
        <div>
            {!! Form::password('password', null, []) !!}
            @error('password')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div>
            <button type="submit">Save</button>
        </div>
    </form>
@stop

@section('css')
@stop

@section('js')
@stop
