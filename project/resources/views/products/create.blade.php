@extends('adminlte::page')

@section('title', 'Add New Product')

@section('content_header')
    <h1>Add New Product</h1>
@stop

@section('content')
    <form action="{{route('products.store')}}" method="POST" enctype="multipart/form-data">
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
            {!! Form::label('description', 'Description', []) !!}
        </div>
        <div>
            {!! Form::textarea('description', null, []) !!}
            @error('description')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div>
            {!! Form::label('type', 'Type', []) !!}
        </div>
        <div>
            {!! Form::text('type', null, []) !!}
            @error('type')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div>
            {!! Form::label('stock', 'Stock', []) !!}
        </div>
        <div>
            {!! Form::number('stock', null, []) !!}
            @error('stock')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div>
            {!! Form::label('buying_price', 'Buying Price', []) !!}
        </div>
        <div>
            {!! Form::number('buying_price', null, []) !!}
            @error('buying_price')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div>
            {!! Form::label('selling_price', 'Selling Price', []) !!}
        </div>
        <div>
            {!! Form::number('selling_price', null, []) !!}
            @error('selling_price')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div>
            {!! Form::label('image', 'Image', []) !!}
        </div>
        <div>
            {!! Form::file('image', ['accept' => 'image/*']) !!}
            @error('image')
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
