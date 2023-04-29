@extends('adminlte::page')

@section('title', 'Edit Product')

@section('content_header')
    <h1>Edit Product</h1>
@stop

@section('content')
    {!! Form::model($product, [
        'route' => ['products.update', $product->id],
        'enctype' => 'multipart/form-data',
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
            {!! Form::label('description', 'Description', []) !!}
        </div>
        <div>
            {!! Form::textarea('description') !!}
            @error('description')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div>
            {!! Form::label('type', 'Type', []) !!}
        </div>
        <div>
            {!! Form::text('type') !!}
            @error('type')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div>
            {!! Form::label('stock', 'Stock', []) !!}
        </div>
        <div>
            {!! Form::number('stock') !!}
            @error('stock')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div>
            {!! Form::label('buying_price', 'Buying Price', []) !!}
        </div>
        <div>
            {!! Form::number('buying_price') !!}
            @error('buying_price')
                <div>{{ $message }}</div>
            @enderror
        </div>
        <div>
            {!! Form::label('selling_price', 'Selling Price', []) !!}
        </div>
        <div>
            {!! Form::number('selling_price') !!}
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
    {!! Form::close() !!}
@stop

@section('css')
@stop

@section('js')
@stop
