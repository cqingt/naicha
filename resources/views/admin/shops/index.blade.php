@extends('admin.layouts.app')
@section('title','店铺列表')

@section('body')
    <blockquote class="layui-elem-quote layui-text">
        店铺列表
        <a href="{{ url('admin/shops/create') }}"class="layui-btn layui-btn-sm" style="margin-left: 30px;">添加</a>
    </blockquote>

    <table id="shops_table" lay-filter="shops_table"></table>
    <script type="text/html" id="bartools">
        <button class="layui-btn layui-btn-xs" lay-event="edit"><i class="layui-icon">&#xe642;</i>  编辑</button>
        <button class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><i class="layui-icon">&#xe640;</i>删除</button>
    </script>
@endsection
@section('scripts')
    <script src="{{ asset('assets/admin/js/modules/shops.js') }}"></script>
@endsection