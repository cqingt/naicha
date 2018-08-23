@extends('admin.layouts.app')
@section('title','商品列表')

@section('body')
    <blockquote class="layui-elem-quote layui-text">
        商品列表
        {{--<a href="{{ url('admin/shops/create') }}"class="layui-btn" style="margin-left: 30px;">添加</a>--}}
    </blockquote>

    <table id="goods_table" lay-filter="goods_table"></table>
    <script type="text/html" id="bartools">
        @{{#  if(d.deleted_at){ }}
        <button class="layui-btn layui-btn-xs" lay-event="online"><i class="layui-icon">&#xe642;</i>  上架 </button>
        @{{# } else { }}
        <button class="layui-btn layui-btn-danger layui-btn-xs" lay-event="offline"><i class="layui-icon">&#xe642;</i>  下架 </button>
        @{{#  } }}

        <button class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><i class="layui-icon">&#xe640;</i>删除</button>

        {{--<a class="layui-btn layui-btn-xs" lay-event="lock">锁定</a>--}}

    </script>
@endsection
@section('scripts')
    <script src="{{ asset('assets/admin/js/modules/goods.js') }}"></script>
@endsection