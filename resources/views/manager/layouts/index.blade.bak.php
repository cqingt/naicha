@extends('manager.layouts.app')
@section('title','PinYer-Admin')
@section('styles')
    <style>
        .layui-tab-title li:first-child > i {
            display: none;
        }
        .layui-table-cell{
            height: auto!important;
            white-space: normal;
        }
        .layui-table img {
            max-width: 80px;
            min-width: 60px;
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
                <li class="layui-this" >商品列表</li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">

                    <blockquote class="layui-elem-quote layui-text">
                        商品列表
                    </blockquote>

                    <div class="layui-form layui-card-header layuiadmin-card-header-auto">
                        <div class="layui-form-item demoTable">

                            <div class="layui-inline">
                                <label class="layui-form-label">商品分类</label>
                                <div class="layui-input-inline">
                                    <select name="category_id" lay-search="" id="category_id">
                                        <option value="">直接选择或搜索选择</option>
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="layui-inline">
                                <label class="layui-form-label">商品名称</label>
                                <div class="layui-input-inline">
                                    <input class="layui-input" name="name" id="goods_name" autocomplete="off" value="">
                                </div>
                            </div>

                            <div class="layui-inline">
                                <button class="layui-btn" data-type="reload">搜索</button>
                                <button class="layui-btn layui-btn-primary" data-type="reset">清空</button>
                            </div>
                        </div>
                    </div>

                    <table id="goods_table" lay-filter="goods_table"></table>
                    <script type="text/html" id="bartools">
                        @{{#  if(d.deleted_at){ }}
                        <button class="layui-btn layui-btn-xs" lay-event="online"><i class="layui-icon">&#xe642;</i>  上架 </button>
                        @{{# } else { }}
                        <button class="layui-btn layui-btn-danger layui-btn-xs" lay-event="offline"><i class="layui-icon">&#xe642;</i>  下架 </button>
                        @{{#  } }}

                        {{--<button class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><i class="layui-icon">&#xe640;</i>删除</button>--}}
                    </script>

                    <script type="text/html" id="imgTpl">
                        <img src="@{{ d.image }}">
                    </script>

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
    <script src="{{ asset('assets/manager/js/modules/goods.js') }}"></script>
@endsection