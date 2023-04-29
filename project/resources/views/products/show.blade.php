@extends('adminlte::page')

@section('title', 'Product Detail')

@section('content_header')
    <h1>Product Detail</h1>
@stop

@section('content')
    <div><img src="{{ $product->image_url }}" alt="{{ $product->name }}" width="200"></div>
    <div>Name: {{ $product->name }}</div>
    <div>Description: {{ $product->description }}</div>
    <div>Type: {{ $product->type }}</div>
    <div>Stock: {{ $product->stock }}</div>
    <div>Buying Price: {{ $product->buying_price }}</div>
    <div>Selling Price: {{ $product->selling_price }}</div>
    <div>Created At: {{ $product->created_at }}</div>
    <div>Updated At: {{ $product->updated_at }}</div>
    <div>
        <a href="{{route('products.edit', ['product' => $product->id])}}">Edit</a>
        <form action="{{route('products.destroy', ['product' => $product->id])}}" method="POST">
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
