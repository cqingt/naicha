@extends('admin.layouts.app')
@section('title','订单列表')

@section('body')
    <blockquote class="layui-elem-quote layui-text">
        订单列表
        {{--<a href="{{ url('admin/shops/create') }}"class="layui-btn" style="margin-left: 30px;">添加</a>--}}
    </blockquote>

    <table id="orders_table" lay-filter="orders_table"></table>

    <script type="text/html" id="bartools">
        <button class="layui-btn layui-btn-xs" lay-event="show"><i class="layui-icon">&#xe63c;</i>  详情</button>
        {{--<button class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><i class="layui-icon">&#xe640;</i>删除</button>--}}

        {{--<a class="layui-btn layui-btn-xs" lay-event="lock">锁定</a>--}}

    </script>
@endsection
@section('scripts')
    <script src="{{ asset('assets/admin/js/modules/orders.js') }}"></script>
@endsection