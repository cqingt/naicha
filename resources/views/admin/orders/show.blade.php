@extends('admin.layouts.app')
@section('title','订单详情')

@section('body')
    <blockquote class="layui-elem-quote layui-text">
        订单详情
        <a href="{{ url('admin/orders') }}"class="layui-btn  layui-btn-sm" style="margin-left: 30px;">返回列表</a>
    </blockquote>

    <div class="layui-container">
        <h2>订单基础信息</h2>
        <hr class="layui-bg-black">

        <table class="layui-table" lay-even="" lay-skin="nob">
            <colgroup>
                <col width="150">
                <col width="150">
                <col width="150">
                <col width="150">
                <col width="200">
                <col>
            </colgroup>
            <thead>
            <tr>
                <th>订单号</th>
                <th>会员姓名</th>
                <th>订单状态</th>
                <th>订单价格</th>
                <th>支付方式</th>
                <th>下单时间</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{$order->order_sn}}</td>
                <td>{{$order->member->username or '--'}}</td>
                <td>{{$order->status}}</td>
                <td>{{$order->price}}</td>
                <td>{{$order->pay_type}}</td>
                <td>{{$order->created_at}}</td>
            </tr>
            </tbody>
        </table>


        <h2>订单商品信息</h2>
        <hr class="layui-bg-black">

        @foreach($packages as $key => $package)

        <table class="layui-table" lay-even="" lay-skin="nob">
            <colgroup>
                <col width="150">
                <col width="150">
                <col width="150">
                <col width="150">
                <col>
            </colgroup>
            @if($key === 1)
            <thead>
            <tr>
                <th>商品名称</th>
                <th>图片</th>
                <th>购买数量</th>
                <th>商品单价</th>
                <th>附加属性</th>
            </tr>
            </thead>
            @endif
            <tbody>
            <tr style="background: #393D49;color: #fff;"><td colspan="5">第{{$key}}杯 @if($temps && isset($temps[$key])) (温度： {{$temps[$key]}}) @endif</td></tr>
            @foreach($package as $goods)
            <tr>
                <td>{{$goods->goods_name}}</td>
                <td><div><img src="{{$goods->goods_image}}" style="height: 50px;"></div></td>
                <td>x{{$goods->goods_num}}</td>
                <td>{{$goods->goods_price}}</td>
                <td>{{$goods->deploy}}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
        @endforeach


        {{--<div class="layui-input-block">
            <a href="{{ url('admin/orders') }}"class="layui-btn" style="margin-left: 30px;">返回</a>
        </div>--}}
    </div>




@endsection

@section('scripts')
    <script src="{{ asset('assets/admin/js/modules/shops.js') }}"></script>
@endsection


