@extends('admin.layouts.app')
@section('title','会员列表')
@section('styles')
    <style>
        body{overflow-y: scroll;}
        .layui-body{overflow-y: scroll;}
        .layui-table-cell{
            height: auto!important;
            white-space: normal;
        }
    </style>
@endsection
@section('body')
    <blockquote class="layui-elem-quote layui-text">
        会员列表
        {{--<a href="{{ url('admin/shops/create') }}"class="layui-btn" style="margin-left: 30px;">添加</a>--}}
    </blockquote>
    <div class="layui-form layui-card-header layuiadmin-card-header-auto">
        <div class="layui-form-item demoTable">
            <div class="layui-inline">
                <label class="layui-form-label">会员姓名</label>
                <div class="layui-input-inline">
                    <input class="layui-input" name="username" id="username" autocomplete="off" value="">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">手机号</label>
                <div class="layui-input-inline">
                    <input class="layui-input" name="telephone" id="telephone" autocomplete="off" value="">
                </div>
            </div>
            <div class="layui-inline">
                <button class="layui-btn" data-type="reload">搜索</button>
                <button class="layui-btn layui-btn-primary" data-type="reset">清空</button>
            </div>
        </div>
    </div>

    <table id="members_table" lay-filter="members_table"></table>
    <script type="text/html" id="bartools">
        <button class="layui-btn layui-btn-xs" lay-event="edit"><i class="layui-icon">&#xe642;</i>  编辑</button>
        <button class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><i class="layui-icon">&#xe640;</i>删除</button>
    </script>
    <script type="text/html" id="imgTpl">
        <img src="@{{ d.avatar }}">
    </script>
@endsection
@section('scripts')
    <script src="{{ asset('assets/admin/js/modules/members.js') }}"></script>
@endsection