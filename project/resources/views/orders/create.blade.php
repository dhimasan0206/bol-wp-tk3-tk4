@extends('adminlte::page')

@section('title', 'Cart Items')

@section('content_header')
    <h1>Cart Items</h1>
@stop

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- Minimal example / fill data using the component slot --}}
    <x-adminlte-datatable id="table1" :heads="$heads">
        @foreach($config['data'] as $row)
            <tr>
                @foreach($row as $cell)
                    <td>{!! $cell !!}</td>
                @endforeach
            </tr>
        @endforeach
    </x-adminlte-datatable>
    <div>Total Items: {{ $totalQuantity }}</div>
    <div>Total Price: {{ $totalPrice }} </div>
    <div>
        <form action="{{route('orders.store')}}" method="post">
            @csrf
            <button type="submit" @if ($totalQuantity == 0 || count($unavailableItems) > 0)
                disabled
            @endif>Checkout</button>
        </form>
    </div>
    <div>
        <a href="{{route('products.index')}}">Add items to cart</a>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
