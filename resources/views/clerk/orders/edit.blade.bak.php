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
            border: 1px solid #a2a0a0;
            background: #e1e1e9;
            border-radius: 10px;
            padding: 5px;
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
            float: right;
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
            margin-bottom: 120px;
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
    </style>
@endsection

@section('body')
    <blockquote class="layui-elem-quote layui-text">
        修改订单
        <a href="{{ url('orders') }}"class="layui-btn  layui-btn-sm" style="margin-left: 30px;">返回列表</a>
    </blockquote>

    <div class="layui-container">
        <form class="layui-form" action="{{ url('orders') }}" method="post">
        {{ csrf_field() }}
            <div id="container">
            <!-- 品类选择 -->
            <div class="header" style="text-align: center">
                <button class="layui-btn layui-btn-radius" type="button">茶底</button>
                <button class="layui-btn layui-btn-primary layui-btn-radius" type="button">牛奶</button>
                <button class="layui-btn layui-btn-primary layui-btn-radius" type="button">其他</button>
            </div>

            <!-- 一级品类 -->
            <div class="goods-item header-child">
                <div class="site-title">
                    <fieldset><legend><a name="fieldset">一级品类(250ml)</a></legend></fieldset>
                </div>
                <div class="header-child-0 layui-row layui-col-space30">
                    @foreach($data[1]['items'] as $key => $item)
                    <div class="layui-col-md2 layui-col-sm2">
                        <div class="cmdlist-container @if (array_key_exists($item['id'], $cupOne)) active @endif"
                             data-pk="{{$item['id']}}" data-price="{{$item['price']}}" data-volume="{{$data[1]['volume']}}">
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

                <div style="display: none;" class="header-child-1 layui-row layui-col-space30">
                    @foreach($data[2]['items'] as $key => $item)
                        <div class="layui-col-md2 layui-col-sm2">
                            <div class="cmdlist-container @if (array_key_exists($item['id'], $cupOne)) active  @endif"
                                 data-milk="1" data-pk="{{$item['id']}}" data-price="{{$item['price']}}" data-volume="{{$data[2]['volume']}}">
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

                <div style="display: none;" class="header-child-2 layui-row layui-col-space30">
                    @foreach($data[3]['items'] as $key => $item)
                        <div class="layui-col-md2 layui-col-sm2">
                            <div class="cmdlist-container @if (array_key_exists($item['id'], $cupOne)) active  @endif"
                                 data-pk="{{$item['id']}}" data-price="{{$item['price']}}" data-volume="{{$data[3]['volume']}}">
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

            <!-- 一级可选 -->
            <div class="goods-item header-choose-one">
                <div class="site-title">
                    <fieldset><legend><a name="fieldset">可选配料(50ml)</a></legend></fieldset>
                </div>
                <h3 class="category"></h3>
                <div class="header-choose-item header-choose-0">
                    <div class="layui-row layui-col-space30">
                        @foreach($data[4]['items'] as $key => $item)
                            <div class="layui-col-md2 layui-col-sm2">
                                <div class="cmdlist-container @if (array_key_exists($item['id'], $cupOne)) active  @endif"
                                     @if(in_array($item['id'], $categoryMilk)) data-milk="1" @endif
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
            </div>

            <!-- 二级品类 -->
            <div class="goods-item header-two">
                <div class="header-two-item">
                    <div class="site-title">
                        <fieldset><legend><a name="fieldset">二级品类 - 果瓜类(150ml)</a></legend></fieldset>
                    </div>
                    {{--<h3 class="category">果瓜类</h3>--}}
                    <div class="header-choose-1">
                        <div class="layui-row layui-col-space30">
                            @foreach($data[5]['items'] as $key => $item)
                                @if(in_array($item['id'], $categoryOne))
                                <div class="layui-col-md2 layui-col-sm2">
                                    <div class="cmdlist-container @if (array_key_exists($item['id'], $cupOne)) active  @endif"
                                         data-pk="{{$item['id']}}" data-price="{{$item['price']}}" data-volume="{{$data[5]['volume']}}">
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
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="header-two-item">
                    <div class="site-title">
                        <fieldset><legend><a name="fieldset">二级品类 - 柑橘类(150ml)</a></legend></fieldset>
                    </div>
                    {{--<h3 class="category">柑橘类</h3>--}}
                    <div class="header-choose-2">
                        <div class="layui-row layui-col-space30">
                            @foreach($data[5]['items'] as $key => $item)
                                @if(in_array($item['id'], $categoryTwo))
                                    <div class="layui-col-md2 layui-col-sm2">
                                        <div class="cmdlist-container @if (array_key_exists($item['id'], $cupOne)) active  @endif"
                                             data-pk="{{$item['id']}}" data-price="{{$item['price']}}" data-volume="{{$data[5]['volume']}}">
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
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="header-two-item">
                    <div class="site-title">
                        <fieldset><legend><a name="fieldset">二级品类 - 谷物类(150ml)</a></legend></fieldset>
                    </div>
                    {{--<h3 class="category">谷物类</h3>--}}
                    <div class="header-choose-2">
                        <div class="layui-row layui-col-space30">
                            @foreach($data[5]['items'] as $key => $item)
                                @if(in_array($item['id'], $categoryThree))
                                    <div class="layui-col-md2 layui-col-sm2">
                                        <div class="cmdlist-container @if (array_key_exists($item['id'], $cupOne)) active  @endif"
                                             data-pk="{{$item['id']}}" data-price="{{$item['price']}}" data-volume="{{$data[5]['volume']}}">
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
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="header-two-item">
                    <div class="site-title">
                        <fieldset><legend><a name="fieldset">二级品类 - 其他(250ml)</a></legend></fieldset>
                    </div>
                    {{--<h3 class="category">其他</h3>--}}
                    <div class="header-choose-2">
                        <div class="layui-row layui-col-space30">
                            <div class="layui-col-md2 layui-col-sm2">
                                <div class="cmdlist-container double" data-pk="-1" data-price="" data-calorie="" data-volume="250">
                                    <a href="javascript:;">
                                        <img class="goods layui-circle"
                                             src="https://www.layui.com/admin/std/dist/layuiadmin/style/res/template/portrait.png">
                                        <span>一级品类双倍</span>
                                        <input type="hidden" name="double" value="">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 二级可选 -->
            <div class="goods-item header-two-choose">
                <div class="site-title">
                    <fieldset><legend><a name="fieldset">二级可选</a></legend></fieldset>
                </div>
                {{--<h3 class="category">二级可选</h3>--}}
                <div class="layui-row layui-col-space30">
                    @foreach($data[6]['items'] as $key => $item)
                        <div class="layui-col-md2 layui-col-sm2">
                            <div class="cmdlist-container @if (array_key_exists($item['id'], $cupOne)) active  @endif"
                                 data-pk="{{$item['id']}}" data-price="{{$item['price']}}" data-volume="{{$data[6]['volume']}}">
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

            <!-- 四级品类 -->
            <div class="goods-item header-four">
                <div class="site-title">
                    <fieldset style="width:100%;"><legend><a name="fieldset">可选配料(25ml)</a></legend></fieldset>
                </div>
                {{--<h3 class="category">配料类</h3>--}}
                <div class="layui-row layui-col-space30">
                    @foreach($data[8]['items'] as $key => $item)
                        <div class="layui-col-md2 layui-col-sm2">
                            <div class="cmdlist-container @if (array_key_exists($item['id'], $cupOne)) active  @endif"
                                 data-pk="{{$item['id']}}" data-price="{{$item['price']}}" data-volume="{{$data[8]['volume']}}">
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
                    @foreach($data[9]['items'] as $key => $item)
                        <div class="layui-col-md2 layui-col-sm2">
                            <div class="cmdlist-container @if (array_key_exists($item['id'], $cupOne)) active  @endif"
                                 data-pk="{{$item['id']}}" data-price="{{$item['price']}}" data-volume="{{$data[9]['volume']}}">
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

            <!-- 撒料 -->
            <div class="goods-item header-last">
                <div class="site-title">
                    <fieldset style="width:100%;"><legend><a name="fieldset">奶盖撒料</a></legend></fieldset>
                </div>
                {{--<h3 class="category">奶盖撒料</h3>--}}
                <div class="layui-row layui-col-space30">
                    @foreach($data[10]['items'] as $key => $item)
                        <div class="layui-col-md2 layui-col-sm2">
                            <div class="cmdlist-container @if (array_key_exists($item['id'], $cupOne)) active  @endif"
                                 data-pk="{{$item['id']}}" data-price="{{$item['price']}}" data-volume="{{$data[10]['volume']}}">
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

                {{--<hr>--}}
                <div class="site-title">
                    <fieldset style="width:100%;"><legend><a name="fieldset">糖类可选</a></legend></fieldset>
                </div>
                <!-- 三级品类 -->
                <div class="goods-item header-three">

                    @foreach($data[7]['items'] as $key => $item)
                        @if (! $item['deleted_at'])
                            <div class="header-three-item @if (! array_key_exists($item['id'], $cupOne)) disabled  @endif " data-pk="{{$item['id']}}" data-price="{{$item['price']}}">
                                <label class="layui-form-label">
                                    <button class="layui-btn layui-btn-primary layui-btn-radius change-item" type="button">{{$item['name']}}</button>
                                    <input type="hidden" name="goods_id[]">
                                </label>

                                <div class="layui-input-block select-item">
                                    <input type="radio" name="weight" value="多糖" title="多糖" lay-filter="filter" @if(isset($cupOne[$item['id']]) && $cupOne[$item['id']]['deploy'] == '多糖') checked @endif>
                                    <input type="radio" name="weight" value="正常" title="正常" lay-filter="filter" @if(isset($cupOne[$item['id']]) && $cupOne[$item['id']]['deploy'] == '正常') checked @endif>
                                    <input type="radio" name="weight" value="少糖" title="少糖" lay-filter="filter" @if(isset($cupOne[$item['id']]) && $cupOne[$item['id']]['deploy'] == '少糖') checked @endif>
                                    <input type="radio" name="weight" value="半糖" title="半糖" lay-filter="filter" @if(isset($cupOne[$item['id']]) && $cupOne[$item['id']]['deploy'] == '半糖') checked @endif>
                                    <input type="radio" name="weight" value="无糖" title="无糖" lay-filter="filter" @if(isset($cupOne[$item['id']]) && $cupOne[$item['id']]['deploy'] == '无糖') checked @endif>
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
                        <button class="layui-btn layui-btn-radius temp @if($temperature != '热饮') layui-btn-primary @endif" type="button">热饮</button>
                        <button class="layui-btn layui-btn-radius temp @if($temperature == '热饮') layui-btn-primary @endif" type="button">冷饮</button>
                        <input type="hidden" name="temperature" value="热饮">
                        <div class="temperature" style="display: @if($temperature == '热饮') none @endif">
                            <div class="layui-input-block select-item">
                                <input type="radio" name="temperature" value="正常冰" title="正常冰" lay-filter="temperature" @if($temperature == '正常冰') checked @endif>
                                <input type="radio" name="temperature" value="少冰" title="少冰" lay-filter="temperature" @if($temperature == '少冰') checked @endif>
                                <input type="radio" name="temperature" value="去冰" title="去冰" lay-filter="temperature" @if($temperature == '去冰') checked @endif>
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
                    {{--<div class="count-item goods-cal">卡路里: <span class="num"> 0</span>cal</div>--}}
                    {{--<div class="count-item goods-volume">容量: <span class="num"> 0</span>ml</div>--}}
                    <div class="count-item current-cup">当前杯: <span class="num"> CUP-1</span></div>
                    <div class="count-item order-volume">容量: <span class="num"> 0</span>ml</div>
                    <div class="count-item order-price">金额: <span class="num"> 0.00</span></div>
                    <div class="count-item order-total">订单总金额: <span class="num"> 0.00</span></div>
                </div>
                <button class="layui-btn layui-btn-sm submit" lay-submit="" lay-filter="confirm">确认下单</button>
            </div>
        </form>

    </div>

@endsection

@section('scripts')
    <script src="{{ asset('assets/clerk/js/modules/orders_edit.js') }}"></script>
    <script>
        layui.use(['jquery'],function() {
            let $ = layui.jquery;
            console.log('debug');

            // 编辑订单时
            let ind = $('.header-child').find('.active').parents('.layui-row').index();
            console.log(ind);
            console.log(ind - 1);
            if (ind) {
                $('.header').find('button').addClass('layui-btn-primary');
                $('.header').find('button').eq(ind - 1).removeClass('layui-btn-primary');
                console.log(ind);
                console.log(ind - 1);
                $('.header-child .layui-row').css('display', 'none');
                $('.header-child .header-child-' + (ind - 1)).css('display', 'block');
            }
        });

    </script>
@endsection


