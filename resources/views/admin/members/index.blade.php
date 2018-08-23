@extends('admin.layouts.app')
@section('title','会员列表')

@section('body')
    <blockquote class="layui-elem-quote layui-text">
        会员列表
        {{--<a href="{{ url('admin/shops/create') }}"class="layui-btn" style="margin-left: 30px;">添加</a>--}}
    </blockquote>

    <table id="members_table" lay-filter="members_table"></table>
    <script type="text/html" id="bartools">
        <button class="layui-btn layui-btn-xs" lay-event="edit"><i class="layui-icon">&#xe642;</i>  编辑</button>
        <button class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><i class="layui-icon">&#xe640;</i>删除</button>

        {{--<a class="layui-btn layui-btn-xs" lay-event="lock">锁定</a>--}}

    </script>
@endsection
@section('scripts')
    <script src="{{ asset('assets/admin/js/modules/members.js') }}"></script>
@endsection