@extends('layouts.admin')
@section('title') Inicio @endsection
@section('content')
@include('partial_msg')
<div style="width:100%; display:flex; justify-content:center; align-items:center; flex-wrap:wrap;">

    <div class="center">
        <p style="font-size:25px; color:#fff;">Panel de administración</p>
        <div class="menu" style="margin-top:50px;">
            <li class="item" id="home">
                <a href="{{route('home')}}" class="btn" style="font-size:18px!important;"><i class="fas fa-home"></i>
                    Home</a>
            </li>
            <li class="item" id="users">
                <a href="#users" class="btn" style="font-size:18px!important;"><i class="fas fa-users"></i>
                    Usuarios</a>
                <div class="smenu">
                    <a style="color:#fff;" href="">Ver</a>
                </div>
            </li>
            <li class="item" id="categories">
                <a href="#categories" class="btn" style="font-size:18px!important;"><i class="fas fa-sort"></i>
                    Categorías</a>
                <div class="smenu">
                    <a style="color:#fff;" href="#" data-toggle="modal" data-target="#create-category">Crear</a>
                    <a style="color:#fff;" href="{{route('admin.category.get')}}">Ver</a>
                </div>
            </li>

            <li class="item" id="sub_categories">
                <a href="#sub_categories" class="btn" style="font-size:18px!important;"><i class="fas fa-sort-amount-down-alt"></i>
                    Sub-Categorías</a>
                <div class="smenu">
                    <a style="color:#fff;" href="#" data-toggle="modal" data-target="#create-subcategory">Crear</a>
                    <a style="color:#fff;" href="{{route('admin.subcategory.get')}}">Ver</a>
                </div>
            </li>

            <li class="item" id="products">
                <a href="#products" class="btn" style="font-size:18px!important;"><i class="fas fa-store"></i>
                    Productos</a>
                <div class="smenu">
                    <a style="color:#fff;" href="#" data-toggle="modal" data-target="#create-product">Crear</a>
                    <a style="color:#fff;" href="{{route('admin.product.get')}}">Ver</a>
                </div>
            </li>

            <li class="item" id="discounts">
                <a href="#discounts" class="btn" style="font-size:18px!important;"><i class="fa-solid fa-percent"></i>
                    Descuentos</a>
                <div class="smenu">
                    <a style="color:#fff;" href="#" data-toggle="modal" data-target="#create-discount">Crear</a>
                    <a style="color:#fff;" href="{{route('admin.discount.get')}}">Ver</a>
                </div>
            </li>

            <li class="item" id="orders">
                <a href="#orders" class="btn" style="font-size:18px!important;"><i class="fa-solid fa-truck-fast"></i>
                    Pedidos</a>
                <div class="smenu">
                    <a style="color:#fff;" href="{{route('admin.order.get')}}">Ver</a>
                </div>
            </li>
        </div>
    </div>
</div>

@include('admin.categories.create')
@include('admin.subcategories.create')
@include('admin.products.create')
@include('admin.discounts.create')
@endsection