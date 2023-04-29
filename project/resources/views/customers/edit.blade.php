@extends('adminlte::page')

@section('title', 'Edit Customer')

@section('content_header')
    <h1>Edit Customer</h1>
@stop

@section('content')
    {!! Form::model($customer, [
        'route' => ['customers.update', $customer->id],
    ]) !!}
        @csrf
        @method('PUT')
        <div>
            {!! Form::label('name', 'Name', []) !!}
        </div>
        <div>
            {!! Form::text('name') !!}
            @error('name')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div>
            {!! Form::label('email', 'Email', []) !!}
        </div>
        <div>
            {{ $customer->email }}
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
    {!! Form::close() !!}
@stop

@section('css')
@stop

@section('js')
@stop
