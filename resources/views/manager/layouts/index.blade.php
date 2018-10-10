@extends('manager.layouts.app')
@section('title','PinYer-Admin')
@section('styles')
    <style>
        .layui-tab-title li:first-child > i {
            display: none;
        }
    </style>
@endsection
@section('body')
    <div class="layui-header">
        <div class="layui-logo">Manager后台管理</div>
        <!-- 头部区域（可配合layui已有的水平导航） -->
        @include('manager.layouts._header')
    </div>

    <div class="layui-side layui-bg-black">
        <div class="layui-side-scroll">
            <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
            <ul class="layui-nav layui-nav-tree"  lay-filter="left-menu">
                @include('manager.layouts._sidebar')
            </ul>
        </div>
    </div>

    <div class="layui-body">
        <div class="layui-tab" lay-allowClose="true" lay-filter="tab-switch">
            <ul class="layui-tab-title">
                <li class="layui-this" >后台首页</li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                    {{--<div>暂时没有内容</div>--}}
                </div>
            </div>
        </div>
    </div>

    <div class="layui-footer">
        <!-- 底部固定区域 -->
        @include('manager.layouts._footer')
    </div>
    @endsection

@section('scripts')
    <script src="{{ asset('assets/manager/js/modules/index.js') }}"></script>
@endsection