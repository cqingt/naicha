@extends('clerk.layouts.app')
@section('title','数据统计')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/admin.css') }}">
@endsection

@section('body')
    <div class="layui-fluid">
        {{--<div class="layui-card-header layuiadmin-card-header-auto">--}}
            <form class="layui-form" action="{{ url('clerk/index/data') }}" method="GET" style="margin: 20px 0 10px; ">
                <div class="layui-form-item" style="display: inline-block;">
                    <label class="layui-form-label">快捷操作</label>
                    <div class="layui-input-inline">
                        <select name="date_type">
                            <option value="">快捷操作</option>
                            @foreach($dates as $flag => $date)
                                <option value="{{$flag}}" @if($flag == $currentDate) selected="selected" @endif>{{$date}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="layui-form-item" style="display: inline-block;">
                    <label class="layui-form-label">开始时间</label>
                    <div class="layui-input-inline">
                        <input type="text" name="start_time" id="start_time" placeholder="yyyy-MM-dd HH:mm:ss" autocomplete="off" class="layui-input" value="{{$start}}">
                    </div>
                </div>

                <div class="layui-form-item" style="display: inline-block;">
                    <label class="layui-form-label">结束时间</label>
                    <div class="layui-input-inline">
                        <input type="text" name="stop_time" id="stop_time" placeholder="yyyy-MM-dd HH:mm:ss" autocomplete="off" class="layui-input"  value="{{$stop}}">
                    </div>
                </div>
                <div class="layui-form-item" style="display: inline-block;margin-left: 5%;">

                    <div class="layui-input-inline" style="margin-bottom: 4px;">
                        <button class="layui-btn layui-btn-sm" id="searchBtn">搜 索</button>
                    </div>
                </div>
            </form>
        {{--</div>--}}
        <hr class="layui-bg-black">
        <div class="layui-row layui-col-space15" style="margin-top:20px;">
            <div class="layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        待处理订单
                        <span class="layui-badge layui-bg-green  layuiadmin-badge">{{$showTxt}}</span>
                    </div>

                    <div class="layui-card-body layuiadmin-card-list">
                        <p class="layuiadmin-big-font">{{$todo}}</p>
                        <p>
                            已处理订单数
                            <span class="layuiadmin-span-color">{{$done}}单</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        我的下单数<span class="layui-badge layui-bg-green  layuiadmin-badge">{{$showTxt}}</span>
                    </div>

                    <div class="layui-card-body layuiadmin-card-list">
                        <p class="layuiadmin-big-font">{{$order}}</p>
                        <p>总下单数<span class="layuiadmin-span-color">{{$orders}}单 </span></p>
                    </div>
                </div>
            </div>

            <div class="layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">我的下单金额<span class="layui-badge layui-bg-green  layuiadmin-badge">{{$showTxt}}</span>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                        <p class="layuiadmin-big-font">{{$amount or '0.00'}}</p>
                        <p>总下单金额
                            <span class="layuiadmin-span-color">{{$amounts or '0.00'}}元 </span>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/clerk/js/modules/data.js') }}"></script>
@endsection