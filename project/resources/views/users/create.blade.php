@extends('adminlte::page')

@section('title', 'Add New User')

@section('content_header')
    <h1>Add New User</h1>
@stop

@section('content')
    <form action="{{route('users.store')}}" method="POST">
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
            {!! Form::label('roles', 'Roles', []) !!}
        </div>
        <div>
            @foreach ($roles as $role)
                {!! Form::checkbox('roles[]', $role->name, false, ['id' => 'role__'.$role->name]) !!} {!! Form::label('role__'.$role->name, $role->name, []) !!}
            @endforeach
            @error('roles')
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
