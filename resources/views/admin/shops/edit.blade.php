@extends('admin.layouts.app')
@section('title','编辑店铺')

@section('body')
    <blockquote class="layui-elem-quote layui-text">
        编辑店铺
        <a href="{{ url('admin/shops') }}"class="layui-btn  layui-btn-sm" style="margin-left: 30px;">返回列表</a>
    </blockquote>

    <form class="layui-form" action="{{ url('admin/shops', $shop->id) }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PUT">
        <div class="layui-form-item">
            <label class="layui-form-label">店铺名称</label>
            <div class="layui-input-block">
                <input type="text" name="name" readonly autocomplete="off" class="layui-input" value="{{$shop->name}}">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">店铺标识</label>
            <div class="layui-input-block">
                <input type="text" name="flag" readonly autocomplete="off" class="layui-input" value="{{$shop->flag}}">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">店铺地址</label>
            <div class="layui-input-block">
                <input type="text" name="address" lay-verify="required"  placeholder="请输入店铺地址" autocomplete="off" class="layui-input" value="{{$shop->address}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">联系方式</label>
            <div class="layui-input-block">
                <input type="text" name="contact" lay-verify="required" placeholder="请输入联系方式" autocomplete="off" class="layui-input" value="{{$shop->contact}}">
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


