@extends('adminlte::page')

@section('title', 'Edit User')

@section('content_header')
    <h1>Edit User</h1>
@stop

@section('content')
    {!! Form::model($user, [
        'route' => ['users.update', $user->id],
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
            {!! Form::email('email') !!}
            @error('email')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div>
            {!! Form::label('password', 'Password', []) !!}
        </div>
        <div>
            {!! Form::password('password') !!}
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
