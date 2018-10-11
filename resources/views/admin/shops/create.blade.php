@extends('admin.layouts.app')
@section('title','新增店铺')

@section('body')
    <blockquote class="layui-elem-quote layui-text">
        新增店铺
        <a href="{{ url('shops') }}"class="layui-btn  layui-btn-sm" style="margin-left: 30px;">返回列表</a>
    </blockquote>

    <form class="layui-form" action="{{ route('shops.store') }}" method="post">
        {{ csrf_field() }}
        <div class="layui-form-item">
            <label class="layui-form-label">店铺名称</label>
            <div class="layui-input-block">
                <input type="text" name="name" lay-verify="required" autocomplete="off" placeholder="请输入店铺名称" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">店铺地址</label>
            <div class="layui-input-block">
                <input type="text" name="address" lay-verify="required"  placeholder="请输入店铺地址" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">联系方式</label>
            <div class="layui-input-block">
                <input type="text" name="contact" lay-verify="required" placeholder="请输入联系方式" autocomplete="off" class="layui-input" >
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="shop">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>

@endsection

@section('scripts')
    <script src="{{ asset('assets/admin/js/modules/shops.js') }}"></script>
@endsection


