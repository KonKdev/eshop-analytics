@extends('layouts.app')

@section('content')
    <h1>Orders</h1>
    <ul>
        @foreach($orders as $order)
            <li>
                Order #{{ $order->id }} - {{ $order->total }}€
            </li>
        @endforeach
    </ul>
@endsection
