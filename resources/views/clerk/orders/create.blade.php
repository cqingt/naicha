@extends('clerk.layouts.app')
@section('title','创建订单')
@section('styles')
    <style>
        .layui-layout-body {
            overflow-y: scroll;
        }

        .header-child {
            margin: 20px;
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
            height: 100px;
            width: 100px !important;
            margin-bottom: 5px;
        }
        .count, .count-item {
            display: inline-block;
        }
        .count-item {
            margin-right: 30px;
            font-size: 20px;
        }
        .count-item .num{
            font-weight:bolder;
        }
        .submit-bar{
            margin-top:50px;
            margin-bottom: 50px;
        }
        .submit-bar .submit{
            float: right;
        }
        .header-three {
            background: #040404;
            color: #fff;
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
    </style>
@endsection

@section('body')
    <blockquote class="layui-elem-quote layui-text">
        创建订单
    </blockquote>

    <div class="layui-container">
        <form class="layui-form" action="">

            <!-- 品类选择 -->
            <div class="header" style="text-align: center">
                <button class="layui-btn layui-btn-radius">茶底</button>
                <button class="layui-btn layui-btn-primary layui-btn-radius">牛奶</button>
                <button class="layui-btn layui-btn-primary layui-btn-radius">其他</button>
            </div>

            <!-- 一级品类 -->
            <div class="header-child">
                <div class="header-child-0 layui-row layui-col-space30">
                    @foreach($data[1]['items'] as $key => $item)
                    <div class="layui-col-md2 layui-col-sm2">
                        <div class="cmdlist-container" data-price="{{$item['price']}}" data-calorie="{{$item['calorie']}}" data-volume="{{$data[1]['volume']}}">
                            <a href="javascript:;" class="layui-inline">
                                <img class="goods layui-circle" src="{{$item['image']}}">
                                <span>{{$item['name']}}</span>
                                <input type="hidden" name="goods_id[]" value="{{$item['id']}}">
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div style="display: none;" class="header-child-1 layui-row layui-col-space30">
                    @foreach($data[2]['items'] as $key => $item)
                        <div class="layui-col-md2 layui-col-sm2">
                            <div class="cmdlist-container" data-price="{{$item['price']}}" data-calorie="{{$item['calorie']}}" data-volume="{{$data[2]['volume']}}">
                                <a href="javascript:;" class="layui-inline">
                                    <img class="goods layui-circle" src="{{$item['image']}}">
                                    <span>{{$item['name']}}</span>
                                    <input type="hidden" name="goods_id[]" value="{{$item['id']}}">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="display: none;" class="header-child-2 layui-row layui-col-space30">
                    @foreach($data[3]['items'] as $key => $item)
                        <div class="layui-col-md2 layui-col-sm2">
                            <div class="cmdlist-container" data-price="{{$item['price']}}" data-calorie="{{$item['calorie']}}" data-volume="{{$data[3]['volume']}}">
                                <a href="javascript:;" class="layui-inline">
                                    <img class="goods layui-circle" src="{{$item['image']}}">
                                    <span>{{$item['name']}}</span>
                                    <input type="hidden" name="goods_id[]" value="{{$item['id']}}">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <hr>

            <!-- 一级可选 -->
            <div class="header-choose-one">
                <h3 class="category">可选</h3>
                <div class="header-choose-item header-choose-0">
                    <div class="header-child-1 layui-row layui-col-space30">
                        @foreach($data[4]['items'] as $key => $item)
                            <div class="layui-col-md2 layui-col-sm2">
                                <div class="cmdlist-container" data-price="{{$item['price']}}" data-calorie="{{$item['calorie']}}" data-volume="{{$data[4]['volume']}}">
                                    <a href="javascript:;" class="layui-inline">
                                        <img class="goods layui-circle" src="{{$item['image']}}">
                                        <span>{{$item['name']}}</span>
                                        <input type="hidden" name="goods_id[]" value="{{$item['id']}}">
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="header-choose-item header-choose-1" style="display: none;">
                    <div class="header-child-1 layui-row layui-col-space30">
                        <div class="layui-col-md2 layui-col-sm2">
                            <div class="cmdlist-container">
                                <a href="javascript:;">
                                    <img class="goods layui-circle"
                                         src="https://www.layui.com/admin/std/dist/layuiadmin/style/res/template/portrait.png">
                                    <span>本草纲目</span>
                                </a>
                            </div>
                        </div>
                        <div class="layui-col-md2 layui-col-sm2">
                            <div class="cmdlist-container">
                                <a href="javascript:;">
                                    <img class="goods layui-circle"
                                         src="https://www.layui.com/admin/std/dist/layuiadmin/style/res/template/portrait.png">
                                    <span>本草纲目</span>
                                </a>
                            </div>
                        </div>
                        <div class="layui-col-md2 layui-col-sm2">
                            <div class="cmdlist-container">
                                <a href="javascript:;">
                                    <img class="goods layui-circle"
                                         src="https://www.layui.com/admin/std/dist/layuiadmin/style/res/template/portrait.png">
                                    <span>本草纲目</span>
                                </a>
                            </div>
                        </div>
                        <div class="layui-col-md2 layui-col-sm2">
                            <div class="cmdlist-container">
                                <a href="javascript:;">
                                    <img class="goods layui-circle"
                                         src="https://www.layui.com/admin/std/dist/layuiadmin/style/res/template/portrait.png">
                                    <span>本草纲目</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="header-choose-item header-choose-2" style="display: none;">
                    <div class="header-child-1 layui-row layui-col-space30">
                        <div class="layui-col-md2 layui-col-sm2">
                            <div class="cmdlist-container">
                                <a href="javascript:;">
                                    <img class="goods layui-circle"
                                         src="https://www.layui.com/admin/std/dist/layuiadmin/style/res/template/portrait.png">
                                    <span>本草纲目</span>
                                </a>
                            </div>
                        </div>
                        <div class="layui-col-md2 layui-col-sm2">
                            <div class="cmdlist-container">
                                <a href="javascript:;">
                                    <img class="goods layui-circle"
                                         src="https://www.layui.com/admin/std/dist/layuiadmin/style/res/template/portrait.png">
                                    <span>本草纲目</span>
                                </a>
                            </div>
                        </div>
                        <div class="layui-col-md2 layui-col-sm2">
                            <div class="cmdlist-container">
                                <a href="javascript:;">
                                    <img class="goods layui-circle"
                                         src="https://www.layui.com/admin/std/dist/layuiadmin/style/res/template/portrait.png">
                                    <span>本草纲目</span>
                                </a>
                            </div>
                        </div>
                        <div class="layui-col-md2 layui-col-sm2">
                            <div class="cmdlist-container">
                                <a href="javascript:;">
                                    <img class="goods layui-circle"
                                         src="https://www.layui.com/admin/std/dist/layuiadmin/style/res/template/portrait.png">
                                    <span>本草纲目</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- 二级品类 -->
            <div class="header-two">
                <div class="header-two-item">
                    <h3 class="category">果瓜类</h3>
                    <div class="header-choose-1">
                        <div class="header-child-1 layui-row layui-col-space30">
                            @foreach($data[5]['items'] as $key => $item)
                                @if(in_array($item['id'], $categoryOne))
                                <div class="layui-col-md2 layui-col-sm2">
                                    <div class="cmdlist-container" data-price="{{$item['price']}}" data-calorie="{{$item['calorie']}}" data-volume="{{$data[5]['volume']}}">
                                        <a href="javascript:;" class="layui-inline">
                                            <img class="goods layui-circle" src="{{$item['image']}}">
                                            <span>{{$item['name']}}</span>
                                            <input type="hidden" name="goods_id[]" value="{{$item['id']}}">
                                        </a>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="header-two-item">
                    <h3 class="category">柑橘类</h3>
                    <div class="header-choose-2">
                        <div class="layui-row layui-col-space30">
                            @foreach($data[5]['items'] as $key => $item)
                                @if(in_array($item['id'], $categoryTwo))
                                    <div class="layui-col-md2 layui-col-sm2">
                                        <div class="cmdlist-container" data-price="{{$item['price']}}" data-calorie="{{$item['calorie']}}" data-volume="{{$data[5]['volume']}}">
                                            <a href="javascript:;" class="layui-inline">
                                                <img class="goods layui-circle" src="{{$item['image']}}">
                                                <span>{{$item['name']}}</span>
                                                <input type="hidden" name="goods_id[]" value="{{$item['id']}}">
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="header-two-item">
                    <h3 class="category">谷物类</h3>
                    <div class="header-choose-2">
                        <div class="layui-row layui-col-space30">
                            @foreach($data[5]['items'] as $key => $item)
                                @if(in_array($item['id'], $categoryThree))
                                    <div class="layui-col-md2 layui-col-sm2">
                                        <div class="cmdlist-container" data-price="{{$item['price']}}" data-calorie="{{$item['calorie']}}" data-volume="{{$data[5]['volume']}}">
                                            <a href="javascript:;" class="layui-inline">
                                                <img class="goods layui-circle" src="{{$item['image']}}">
                                                <span>{{$item['name']}}</span>
                                                <input type="hidden" name="goods_id[]" value="{{$item['id']}}">
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="header-two-item">
                    <h3 class="category">其他</h3>
                    <div class="header-choose-2">
                        <div class="layui-row layui-col-space30">
                            <div class="layui-col-md2 layui-col-sm2">
                                <div class="cmdlist-container double" data-price="" data-calorie="" data-volume="250">
                                    <a href="javascript:;">
                                        <img class="goods layui-circle"
                                             src="https://www.layui.com/admin/std/dist/layuiadmin/style/res/template/portrait.png">
                                        <span>一级品类双倍</span>
                                        <input type="hidden" name="goods_id[]" value="">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <!-- 二级可选 -->
            <div class="header-two-choose">
                <h3 class="category">二级可选</h3>
                <div class="header-child-1 layui-row layui-col-space30">
                    @foreach($data[6]['items'] as $key => $item)
                        <div class="layui-col-md2 layui-col-sm2">
                            <div class="cmdlist-container" data-price="{{$item['price']}}" data-calorie="{{$item['calorie']}}" data-volume="{{$data[6]['volume']}}">
                                <a href="javascript:;" class="layui-inline">
                                    <img class="goods layui-circle" src="{{$item['image']}}">
                                    <span>{{$item['name']}}</span>
                                    <input type="hidden" name="goods_id[]" value="{{$item['id']}}">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <hr>

            <!-- 三级品类 -->
            <div class="header-three">
                @foreach($data[7]['items'] as $key => $item)
                <div class="header-three-item @if($key) disabled @endif">
                    <label class="layui-form-label">
                        <button class="layui-btn layui-btn-primary layui-btn-radius change-item">{{$item['name']}}</button>
                        <input type="hidden" name="goods_id[]" value="{{$item['id']}}">
                    </label>

                    <div class="layui-input-block select-item">
                        <input type="radio" name="weight" value="多糖" title="多糖" lay-filter="filter">
                        <input type="radio" name="weight" value="正常" title="正常" lay-filter="filter">
                        <input type="radio" name="weight" value="少糖" title="少糖" lay-filter="filter">
                        <input type="radio" name="weight" value="半糖" title="半糖" lay-filter="filter">
                        <input type="radio" name="weight" value="无糖" title="无糖" lay-filter="filter">
                    </div>
                </div>
                @endforeach
            </div>

            <hr>

            <!-- 四级品类 -->
            <div class="header-four">
                <h3 class="category">配料类</h3>
                <div class="header-child-1 layui-row layui-col-space30">
                    @foreach($data[8]['items'] as $key => $item)
                        <div class="layui-col-md2 layui-col-sm2">
                            <div class="cmdlist-container" data-price="{{$item['price']}}" data-calorie="{{$item['calorie']}}" data-volume="{{$data[8]['volume']}}">
                                <a href="javascript:;" class="layui-inline">
                                    <img class="goods layui-circle" src="{{$item['image']}}">
                                    <span>{{$item['name']}}</span>
                                    <input type="hidden" name="goods_id[]" value="{{$item['id']}}">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <hr>

            <!-- 五级品类 -->
            <div class="header-five">
                <h3 class="category">奶盖</h3>
                <div class="header-child-1 layui-row layui-col-space30">
                    @foreach($data[9]['items'] as $key => $item)
                        <div class="layui-col-md2 layui-col-sm2">
                            <div class="cmdlist-container" data-price="{{$item['price']}}" data-calorie="{{$item['calorie']}}" data-volume="{{$data[9]['volume']}}">
                                <a href="javascript:;" class="layui-inline">
                                    <img class="goods layui-circle" src="{{$item['image']}}">
                                    <span>{{$item['name']}}</span>
                                    <input type="hidden" name="goods_id[]" value="{{$item['id']}}">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <hr>

            <!-- 撒料 -->
            <div class="header-last">
                <h3 class="category">奶盖撒料</h3>
                <div class="header-child-1 layui-row layui-col-space30">
                    @foreach($data[10]['items'] as $key => $item)
                        <div class="layui-col-md2 layui-col-sm2">
                            <div class="cmdlist-container" data-price="{{$item['price']}}" data-calorie="{{$item['calorie']}}" data-volume="{{$data[10]['volume']}}">
                                <a href="javascript:;" class="layui-inline">
                                    <img class="goods layui-circle" src="{{$item['image']}}">
                                    <span>{{$item['name']}}</span>
                                    <input type="hidden" name="goods_id[]" value="{{$item['id']}}">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <hr>

            <div class="layui-form-item submit-bar">
                <div class="count">
                    <div class="count-item goods-num">商品总数: <span class="num"> 3</span></div>
                    <div class="count-item goods-cal">卡路里: <span class="num"> 300</span>cal</div>
                    <div class="count-item goods-price">订单金额: <span class="num"> 300.00</span></div>
                </div>
                <button class="layui-btn submit" lay-submit="" lay-filter="confirm">确认下单</button>
            </div>
        </form>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('assets/clerk/js/modules/orders.js') }}"></script>
@endsection


