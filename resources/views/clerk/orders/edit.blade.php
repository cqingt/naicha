@extends('clerk.layouts.app')
@section('title','修改订单')
@section('styles')
    <style>
        .layui-layout-body {
            overflow-y: scroll;
        }

        .header-child {
            margin: 20px 0;
        }

        .layui-row .layui-col-sm2 {
            width: 20% !important;
            text-align: center;
        }

        .goods layui-circle {
            text-align: center;
            width: 100px;
            margin: 5px 0;
            display: block;
        }

        .cmdlist-container span {
            display: block;
            text-align: center;
        }

        .cmdlist-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .header-four .cmdlist-container, .header-choose-one .cmdlist-container, .header-two .cmdlist-container{
            border: 1px solid #fff;
            padding: 5px;
        }

        .cmdlist-container.active {
            /*border: 1px solid #a2a0a0;*/
            background: #e1e1e9;
            border-radius: 10px;
            /*padding: 5px;*/
        }

        h3.category {
            text-align: center;
            margin: 18px;
        }

        .header-three-item .layui-form-label {
            width: 120px;

        }

        .header-three-item {
            clear: both;
            height: 50px;
            line-height: 50px;
            vertical-align: middle;
        }

        .layui-circle {
            height: 80px;
            width: 80px !important;
            margin-bottom: 5px;
        }
        .count, .count-item {
            display: inline-block;
        }
        .count-item {
            margin-right: 24px;
            font-size: 16px;
        }
        .count-item .num{
            font-size: 18px;
            font-weight:bolder;
        }
        .submit-bar{
/*            margin-top:50px;
            margin-bottom: 50px;*/

            position: fixed;
            bottom: 0;
            background: #fff;
            height: 46px;
            vertical-align: bottom;
            width: 90%;
            line-height: 46px;
            border-top: 1px solid #999;
            padding: 5px;
        }
        .submit-bar .submit{
            margin-top: 8px;
            /*float: right;*/
        }
        .header-three {
            margin-bottom:120px;
        }
        .header-three {
            background: #393D49;
            color: #fff;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            padding:10px;
            /*margin: 20px 0;*/
        }
        .header-three-item.disabled {
            color: #999;
        }
        .header-three .disabled button {
            background: #999;
        }
        .header-three .layui-input-block {
            margin-left: 145px;
        }
        .site-title{
            margin:12px 0;
        }
        .site-title fieldset {
            border: none;
            padding: 0;
            text-align: center;
            border-top: 1px solid #eee;
        }
        .site-title fieldset legend {
            padding: 0 10px;
            font-size: 16px;
            font-weight: 300;
            /*width: 100%;*/
        }
        .site-title fieldset a {
             color: #333;
             text-decoration: none;
         }
        button.layui-btn.layui-btn-primary.shop-cart {
            position: relative;
            margin-right: 5px;
        }
        .shop-cart span.cups {
            position: absolute;
            top: -10px;
            right: -10px;
            color: #009688;
            border: 1px solid #009688;
            display: block;
            width: 20px;
            font-weight: bolder;
            height: 20px;
            text-align: center;
            line-height: 20px;
            border-radius: 20px;
        }
        span.goods.deleted {
            height: 80px;
            display: block;
            text-align: center;
            line-height: 80px;
            border: 1px solid #ccc;
        }
        .submit-bar .carts {
            position: absolute;
            bottom: 56px;
            left: 0px;
            background: #fff;
            width: 98%;
            height: 60px;
            padding-left: 20px;
            line-height: 60px;
            border: 1px solid #ddd;
        }
        .submit-bar .cups{
            display: inline-block;
        }
        .header-end{
            margin-bottom: 170px;
        }
        .goods-item{
            margin-bottom: 30px;
        }
        .header-end{
            text-align: center;
        }
        .header-end .layui-row{
            margin-top: 10px;
        }
        .header-end .layui-input-block{
            margin-left:0
        }
        .no-image{
            width: 80px;
            line-height: 80px;
            text-align: center;
            height: 80px;
            border: 1px solid #333;
            border-radius: 80px;
        }
    </style>
@endsection

@section('body')
    <blockquote class="layui-elem-quote layui-text">
        修改订单
        <a href="{{ url('orders') }}"class="layui-btn  layui-btn-sm" style="margin-left: 30px;">返回列表</a>
    </blockquote>

    <div class="layui-container">
        <form class="layui-form" action="{{ url('orders/compile') }}" method="post">
        {{ csrf_field() }}
            <div id="container">
            <!-- 品类选择 -->
            {{--<div class="header" style="text-align: center">--}}
                {{--<button class="layui-btn layui-btn-radius" type="button">茶底</button>--}}
                {{--<button class="layui-btn layui-btn-primary layui-btn-radius" type="button">牛奶</button>--}}
                {{--<button class="layui-btn layui-btn-primary layui-btn-radius" type="button">其他</button>--}}
            {{--</div>--}}

            <!-- 一级品类 -->
            <div class="goods-item header-child">
                <div class="site-title">
                    <fieldset><legend><a name="fieldset">基底</a></legend></fieldset>
                </div>
                <div class="header-child-0 layui-row layui-col-space30">
                    @foreach($data[1]['items'] as $key => $item)
                    <div class="layui-col-md2 layui-col-sm2">
                        <div class="cmdlist-container"
                             data-pk="{{$item['id']}}" data-price="{{$item['price']}}" data-volume="{{$data[1]['volume']}}" data-reject="{{$item['reject_id']}}">
                            <a href="javascript:;" class="layui-inline">

                                @if($item['deleted_at'])
                                    <span class="goods deleted layui-circle">下架</span>
                                @else
                                    <img class="goods layui-circle" src="{{$item['image']}}">
                                @endif

                                <span>{{$item['name']}}</span>
                                <input type="hidden" name="goods_id[]">
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- 口感 -->
            <div class="goods-item header-choose-one">
                <div class="site-title">
                    <fieldset><legend><a name="fieldset">口感</a></legend></fieldset>
                </div>
                <h3 class="category"></h3>
                <div class="header-choose-item header-choose-0">
                    <div class="layui-row layui-col-space30">
                        @foreach($data[2]['items'] as $key => $item)
                            <div class="layui-col-md2 layui-col-sm2">
                                <div class="cmdlist-container"
                                data-pk="{{$item['id']}}" data-price="{{$item['price']}}" data-volume="{{$data[2]['volume']}}" data-reject="{{$item['reject_id']}}">
                                    <a href="javascript:;" class="layui-inline">
                                        @if($item['deleted_at'])
                                            <span class="goods deleted layui-circle">下架</span>
                                        @else
                                            <img class="goods layui-circle" src="{{$item['image']}}">
                                        @endif
                                        <span>{{$item['name']}}</span>
                                        <input type="hidden" name="goods_id[]">
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- 风味 -->
            <div class="goods-item header-two">
                <div class="header-two-item">
                    <div class="site-title">
                        <fieldset><legend><a name="fieldset">风味</a></legend></fieldset>
                    </div>
                    {{--<h3 class="category">果瓜类</h3>--}}
                    <div class="header-choose-1">
                        <div class="layui-row layui-col-space30">
                            @foreach($data[3]['items'] as $key => $item)
                                <div class="layui-col-md2 layui-col-sm2">
                                    <div class="cmdlist-container "
                                         data-pk="{{$item['id']}}" data-price="{{$item['price']}}" data-volume="{{$data[3]['volume']}}" data-reject="{{$item['reject_id']}}">
                                        <a href="javascript:;" class="layui-inline">
                                            @if($item['deleted_at'])
                                                <span class="goods deleted layui-circle">下架</span>
                                            @else
                                                <img class="goods layui-circle" src="{{$item['image']}}">
                                            @endif
                                            <span>{{$item['name']}}</span>
                                            <input type="hidden" name="goods_id[]">
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- 配料 -->
            <div class="goods-item header-two-choose">
                <div class="site-title">
                    <fieldset><legend><a name="fieldset">配料</a></legend></fieldset>
                </div>
                <div class="layui-row layui-col-space30">
                    @foreach($data[4]['items'] as $key => $item)
                        <div class="layui-col-md2 layui-col-sm2">
                            <div class="cmdlist-container "
                                 data-pk="{{$item['id']}}" data-price="{{$item['price']}}" data-volume="{{$data[4]['volume']}}">
                                <a href="javascript:;" class="layui-inline">
                                    @if($item['deleted_at'])
                                        <span class="goods deleted layui-circle">下架</span>
                                    @else
                                        <img class="goods layui-circle" src="{{$item['image']}}">
                                    @endif
                                    <span>{{$item['name']}}</span>
                                    <input type="hidden" name="goods_id[]">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 五级品类 -->
            <div class="goods-item header-five">
                <div class="site-title">
                    <fieldset style="width:100%;"><legend><a name="fieldset">奶盖(50ml)</a></legend></fieldset>
                </div>
                {{--<h3 class="category">奶盖</h3>--}}
                <div class="layui-row layui-col-space30">
                    @foreach($data[5]['items'] as $key => $item)
                        <div class="layui-col-md2 layui-col-sm2">
                            <div class="cmdlist-container "
                                 data-pk="{{$item['id']}}" data-price="{{$item['price']}}" data-volume="{{$data[5]['volume']}}">
                                <a href="javascript:;" class="layui-inline">
                                    @if($item['deleted_at'])
                                        <span class="goods deleted layui-circle">下架</span>
                                    @elseif($item['image'])
                                        <img class="goods layui-circle" src="{{$item['image']}}">
                                    @else
                                        <div class="no-image">
                                            <span>{{$item['name']}}</span>
                                        </div>
                                    @endif
                                    <span>{{$item['name']}}</span>
                                    <input type="hidden" name="goods_id[]">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 撒料 -->
            <div class="goods-item header-last">
                <div class="site-title">
                    <fieldset style="width:100%;"><legend><a name="fieldset">奶盖小料</a></legend></fieldset>
                </div>
                {{--<h3 class="category">奶盖撒料</h3>--}}
                <div class="layui-row layui-col-space30">
                    @foreach($data[6]['items'] as $key => $item)
                        <div class="layui-col-md2 layui-col-sm2">
                            <div class="cmdlist-container "
                                 data-pk="{{$item['id']}}" data-price="{{$item['price']}}" data-volume="{{$data[6]['volume']}}">
                                <a href="javascript:;" class="layui-inline">
                                    @if($item['deleted_at'])
                                        <span class="goods deleted layui-circle">下架</span>
                                    @elseif($item['image'])
                                        <img class="goods layui-circle" src="{{$item['image']}}">
                                    @else
                                        <div class="no-image">
                                            <span>{{$item['name']}}</span>
                                        </div>
                                    @endif
                                    <span>{{$item['name']}}</span>
                                    <input type="hidden" name="goods_id[]">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

                {{--<hr>--}}
                <div class="site-title">
                    <fieldset style="width:100%;"><legend><a name="fieldset">糖类可选</a></legend></fieldset>
                </div>
                <!-- 三级品类 -->
                <div class="goods-item header-three">

                    @foreach($data[7]['items'] as $key => $item)
                        @if (! $item['deleted_at'])
                            <div class="header-three-item disabled" data-pk="{{$item['id']}}" data-price="{{$item['price']}}" data-volume="0">
                                <label class="layui-form-label">
                                    <button class="layui-btn layui-btn-primary layui-btn-radius change-item" type="button">{{$item['name']}}</button>
                                    <input type="hidden" name="goods_id[]">
                                </label>

                                <div class="layui-input-block select-item">
                                    <input type="radio" name="weight" value="正常糖" title="正常糖" lay-filter="filter">
                                    <input type="radio" name="weight" value="7分糖" title="7分糖" lay-filter="filter">
                                    <input type="radio" name="weight" value="5分糖" title="5分糖" lay-filter="filter">
                                    <input type="radio" name="weight" value="无糖" title="无糖" lay-filter="filter">
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="header-end">
                    <div class="site-title">
                        <fieldset style="width:100%;"><legend><a name="fieldset">温度选择</a></legend></fieldset>
                    </div>
                    {{--<h3 class="category">奶盖撒料</h3>--}}
                    <div class="layui-row layui-col-space30">
                        {{--<button class="layui-btn layui-btn-radius temp" type="button" data-value="hot">热饮</button>--}}
                        {{--<button class="layui-btn layui-btn-radius temp layui-btn-primary" type="button">冷饮</button>--}}
                        {{--<input type="hidden" name="temperature" value="热饮">--}}
                        <div class="temperature">
                            <div class="layui-input-block select-item">
                                @foreach($data[8]['items'] as $key => $item)
                                    <input type="radio" name="temperature" value="{{$item['id']}}" title="{{$item['name']}}" lay-filter="temperature" >
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="submit-bar">
                <div class="carts">
                    {{--<button class="layui-btn layui-btn-sm add-cup" type="button">
                        <i class="layui-icon">&#xe654;</i>
                    </button>--}}
                    <div class="cups">
                        <button class="layui-btn layui-btn-sm" type="button">CUP-1</button>
                        @for($i = 2; $i <= $cups; $i++)
                            <button class="layui-btn layui-btn-primary layui-btn-sm" type="button">CUP-{{$i}}</button>
                        @endfor
                    </div>

                    {{--<button class="layui-btn layui-btn-sm layui-btn-primary delete-cup" type="button">
                        <i class="layui-icon">&#xe640;</i>
                    </button>--}}
                </div>
                <div class="count">
                    <button class="layui-btn layui-btn-primary shop-cart" type="button">
                        <i class="layui-icon">&#xe657;</i>
                        <span class="cups" style="display: none;">3</span>
                    </button>

                    <div class="count-item cups-num">杯数: <span class="num"> 1</span></div>
                    <div class="count-item current-cup">当前杯: <span class="num"> CUP-1</span></div>
                    <div class="count-item order-volume">容量: <span class="num"> 0</span>ml</div>
                    <div class="count-item order-price">金额: <span class="num"> 0.00</span></div>
                    <div class="count-item order-total">订单总金额: <span class="num"> 0.00</span></div>
                </div>
                <button class="layui-btn layui-btn-sm submit" lay-submit="" lay-filter="confirm">确认修改</button>
            </div>
        </form>

    </div>

@endsection

@section('scripts')
    <script src="{{ asset('assets/clerk/js/modules/orders_edit.js') }}"></script>
    <script>
        layui.use(['jquery'],function() {
            window.sessionStorage.removeItem('edit_carts');
            let edit_carts = {!! $orderInfo !!};
            let array = [];
            array.push(edit_carts);
            window.sessionStorage.setItem('edit_carts', JSON.stringify(array));
        });

    </script>
@endsection


