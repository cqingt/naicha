@extends('clerk.layouts.app')
@section('title','订单列表')

@section('body')
    <blockquote class="layui-elem-quote layui-text">
        订单列表
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
                <label class="layui-form-label">订单状态</label>
                <div class="layui-input-inline">
                    <select name="status" lay-search="" id="status">
                        <option value="">所有</option>
                        <option value="0">待支付</option>
                        <option value="1" @if($from == 'tips') selected @endif>已支付</option>
                        <option value="3">已完成</option>
                    </select>
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">支付方式</label>
                <div class="layui-input-inline">
                    <select name="pay_type" lay-search="" id="pay_type">
                        <option value="">所有</option>
                        <option value="0">店员下单</option>
                        <option value="1">微信支付</option>
                    </select>
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">开始时间</label>
                <div class="layui-input-inline">
                    <input type="text" name="start_time" id="start_time" autocomplete="off" class="layui-input" value="{{$startTime}}">
                </div>
            </div>

            <div class="layui-inline">
                <label class="layui-form-label">结束时间</label>
                <div class="layui-input-inline">
                    <input type="text" name="stop_time" id="stop_time" autocomplete="off" class="layui-input" value="{{$stopTime}}">
                </div>
            </div>
            <div class="layui-inline" style="margin-left: 5%;">
                <button class="layui-btn" data-type="reload">搜索</button>
                <button class="layui-btn layui-btn-primary" data-type="reset">清空</button>
            </div>
        </div>
    </div>

    <div style="overflow-x: scroll">
        <table id="orders_table" lay-filter="orders_table" style="overflow-x: scroll"></table>
    </div>

    <script type="text/html" id="bartools">
        <button class="layui-btn layui-btn-xs" lay-event="show"><i class="layui-icon">&#xe63c;</i>  详情</button>
        @{{#  if(d.status_type == 1){ }}
        <button class="layui-btn layui-btn-primary layui-btn-xs" lay-event="edit"><i class="layui-icon">&#xe642;</i>修改</button>
        <button class="layui-btn layui-btn-normal layui-btn-xs" lay-event="success"><i class="layui-icon">&#xe605;</i>完成</button>
        <button class="layui-btn layui-btn-danger layui-btn-xs" lay-event="cancel"><i class="layui-icon">&#xe642;</i>取消</button>
        @{{# } }}
    </script>

@endsection
@section('scripts')
    <script src="{{ asset('assets/clerk/js/modules/orders.js') }}"></script>
@endsection