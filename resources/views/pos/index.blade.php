@extends('layouts.app')

@section('title', 'Kasir - Transaksi Baru')

@section('content')
    <!-- Panggil Komponen Vue, dan oper data products ke dalamnya -->
    <pos-component :products="{{ $products }}"></pos-component>
@endsection