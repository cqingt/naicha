@extends('admin.layouts.app')
@section('title','数据统计')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/admin.css') }}">
@endsection

@section('body')
    <div class="layui-fluid">
        {{--<div class="layui-card-header layuiadmin-card-header-auto">--}}
            <form class="layui-form" action="{{ url('admin/data/index') }}" method="GET" style="margin: 20px 0 10px; float: right;">
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
                <div class="layui-form-item" style="display: inline-block">
                <div class="layui-input-inline" style="margin-bottom: 4px;margin-left:20px; ">
                    <button class="layui-btn layui-btn-sm" id="searchBtn">搜 索</button>
                    {{--<button type="reset" id="reset" class="layui-btn layui-btn-primary layui-btn-sm">清空</button>--}}
                </div>
                </div>
            </form>
        {{--</div>--}}
        <hr class="layui-bg-black">
        <div class="layui-row layui-col-space15" style="margin-top:20px;">
            <div class="layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        新增订单
                        <span class="layui-badge layui-bg-green  layuiadmin-badge">{{$showTxt}}</span>
                    </div>

                    <div class="layui-card-body layuiadmin-card-list">
                        <p class="layuiadmin-big-font">{{$order}}</p>
                        <p>
                            总计订单数
                            <span class="layuiadmin-span-color">{{$orders}}单</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        营业额<span class="layui-badge layui-bg-green  layuiadmin-badge">{{$showTxt}}</span>
                    </div>

                    <div class="layui-card-body layuiadmin-card-list">
                        <p class="layuiadmin-big-font">{{$amount or '0.00'}}</p>
                        <p>总营业额
                            <span class="layuiadmin-span-color">{{$amounts or '0.00'}}元 </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">新增会员<span
                                class="layui-badge layui-bg-green  layuiadmin-badge">{{$showTxt}}</span>
                    </div>

                    <div class="layui-card-body layuiadmin-card-list">
                        <p class="layuiadmin-big-font">{{$member}}</p>
                        <p>总计会员<span class="layuiadmin-span-color">{{$members}}个 </span></p>
                    </div>
                </div>
            </div>

            <div class="layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        新增店铺
                        <span class="layui-badge layui-bg-green  layuiadmin-badge">{{$showTxt}}</span>
                    </div>

                    <div class="layui-card-body layuiadmin-card-list">
                        <p class="layuiadmin-big-font">{{$shop}}</p>
                        <p>
                            总计店铺数
                            <span class="layuiadmin-span-color">{{$shops}}家 </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="echats" style="margin-top:50px ; ">
            <div class="layui-form-item" style="float: right;margin-right: 50px;display: none">
                <label class="layui-form-label">日期选择</label>
                <div class="layui-input-inline">
                    <input type="text" name="query_time" id="query_time" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div id="main" style="max-width: 1200px;min-width: 800px;height:400px;margin:0px 50px;"></div>
        </div>

    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/admin/js/modules/data.js') }}"></script>
    <script src="{{ asset('assets/admin/js/echarts.common.min.js') }}"></script>

    <script>
        var myChart = echarts.init(document.getElementById('main'));

        // 指定图表的配置项和数据
        var option = {
            title: {
                text: '各时段订单数据统计',
                left:'center',
                // textAlign: 'center',
            },
            tooltip: {
                trigger: 'axis'
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: {
                name: '时间',
                data: [{!! $timeString !!}]
            },
            yAxis: {
                name: '订单量',
                type: 'value'
            },
            series: [
                {
                    name: '订单量',
                    type: 'line',
                    data: [{!! $orderString !!}],
                    smooth: true
                }
            ]
        };

        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    </script>
@endsection