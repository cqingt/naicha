@extends('admin.layouts.app')
@section('title','新增优惠券')

@section('body')
    <blockquote class="layui-elem-quote layui-text">
        新增优惠券
        <a href="{{ url('admin/coupons') }}"class="layui-btn layui-btn-sm" style="margin-left: 30px;">返回列表</a>
    </blockquote>

    <form class="layui-form" action="{{ route('admin.coupons.store') }}" method="post">
        {{ csrf_field() }}
        <div class="layui-form-item">
            <label class="layui-form-label">选择店铺</label>
            <div class="layui-input-inline">
                <select name="shop_id" lay-search="">
                    <option value="">直接选择或搜索选择</option>
                    @foreach($shops as $shop)
                    <option value="{{$shop->id}}">{{$shop->name}}</option>
                    @endforeach
                </select>
                (不选，对所有店铺有效)
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">优惠券名称</label>
            <div class="layui-input-block">
                <input type="text" name="title" lay-verify="required" autocomplete="off" placeholder="请输入优惠券名称" class="layui-input">
            </div>
        </div>

                <div class="layui-form-item">
            <label class="layui-form-label">优惠券条件</label>
            <div class="layui-input-block">
                <span>满</span> <input type="text" name="match_price" autocomplete="off" value="0" class="layui-input" style="display: inline-block !important;width: 80px;">
                <span>元，减</span> <input type="text" name="reduced_price" lay-verify="required" autocomplete="off" class="layui-input" style="display: inline-block !important;width: 80px;">
                <span>元</span>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">发放数量</label>
            <div class="layui-input-inline">
                <input type="number" name="amount" value="0" lay-verify="required" autocomplete="off" class="layui-input">
                (0表示不限制)
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">开始时间</label>
            <div class="layui-input-inline">
                <input type="text" name="start_time" lay-verify="required" id="start_time" placeholder="yyyy-MM-dd HH:mm:ss" autocomplete="off" class="layui-input" >
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">结束时间</label>
            <div class="layui-input-inline">
                <input type="text" name="stop_time" lay-verify="required" id="stop_time" placeholder="yyyy-MM-dd HH:mm:ss" autocomplete="off" class="layui-input" >
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="coupons">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>

@endsection

@section('scripts')
    <script src="{{ asset('assets/admin/js/modules/coupons.js') }}"></script>
@endsection


