@extends('admin.layouts.app')
@section('title','订单列表')

@section('body')
    <blockquote class="layui-elem-quote layui-text">
        订单列表
        {{--<a href="{{ url('shops/create') }}"class="layui-btn" style="margin-left: 30px;">添加</a>--}}
    </blockquote>

    <div class="layui-form layui-card-header layuiadmin-card-header-auto" style="height: auto;">
        <div class="layui-form-item demoTable">
            <div class="layui-inline">
                <label class="layui-form-label">订单号</label>
                <div class="layui-input-inline">
                    <input class="layui-input" name="order_sn" id="order_sn" autocomplete="off" value="">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">店铺</label>
                <div class="layui-input-inline">
                    <select name="shop_id" lay-search="" id="shop_id">
                        <option value="">直接选择或搜索选择</option>
                        @foreach($shops as $shop)
                            <option value="{{$shop->id}}">{{$shop->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">订单状态</label>
                <div class="layui-input-inline">
                    <select name="status" lay-search="" id="status">
                        <option value="">所有</option>
                        <option value="0">待支付</option>
                        <option value="1">已支付</option>
                        <option value="3">已完成</option>
                    </select>
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">支付方式</label>
                <div class="layui-input-inline">
                    <select name="pay_type" lay-search="" id="pay_type">
                        <option value="">所有</option>
                        <option value="0">线下支付</option>
                        <option value="1">微信支付</option>
                    </select>
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">开始时间</label>
                <div class="layui-input-inline">
                    <input type="text" name="start_time" id="start_time" autocomplete="off" class="layui-input">
                </div>
            </div>

            <div class="layui-inline">
                <label class="layui-form-label">结束时间</label>
                <div class="layui-input-inline">
                    <input type="text" name="stop_time" id="stop_time" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-inline">
                <button class="layui-btn" data-type="reload">搜索</button>
                <button class="layui-btn layui-btn-primary" data-type="reset">清空</button>
            </div>
        </div>
    </div>

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