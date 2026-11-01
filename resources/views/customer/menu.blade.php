@extends('layouts.customer')

@section('title', 'Meja ' . $table->number . ' - Ayam Yogya')

@section('content')
    <div id="customer-app">
        <customer-order
            :table="{{ json_encode(['number' => $table->number]) }}"
            :products="{{ $products }}"
        ></customer-order>
    </div>
@endsection