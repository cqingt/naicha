@extends('manager.layouts.app')
@section('title','商品列表')
@section('styles')
    <style>
        .layui-table-cell{
            height: auto!important;
            white-space: normal;
        }
        .layui-table img {
            max-width: 40px;
            min-width: 40px;
        }
    </style>
@endsection

@section('body')
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
        <a href="@{{ d.image }}"><img src="@{{ d.image }}"></a>
    </script>
@endsection
@section('scripts')
    <script src="{{ asset('assets/manager/js/modules/goods.js') }}"></script>
@endsection