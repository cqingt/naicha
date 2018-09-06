@extends('admin.layouts.app')
@section('title','优惠券列表')

@section('body')
    <blockquote class="layui-elem-quote layui-text">
        优惠券列表
        <a href="{{ url('admin/coupons/create') }}"class="layui-btn layui-btn-sm" style="margin-left: 30px;">添加</a>
    </blockquote>

    <table id="coupons_table" lay-filter="coupons_table"></table>
    <script type="text/html" id="bartools">
        @{{#  if(d.is_send == 0){ }}
        <button class="layui-btn layui-btn-xs" lay-event="edit"><i class="layui-icon">&#xe642;</i>  编辑</button>
        <button class="layui-btn layui-btn-xs" lay-event="grant"><i class="layui-icon">&#xe674;</i>  发放</button>
        @{{# } }}
        <button class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><i class="layui-icon">&#xe640;</i>删除</button>

    </script>
@endsection
@section('scripts')
    <script src="{{ asset('assets/admin/js/modules/coupons.js') }}"></script>
@endsection