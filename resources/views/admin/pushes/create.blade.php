@extends('admin.layouts.app')
@section('title','新增推送')

@section('body')
    <blockquote class="layui-elem-quote layui-text">
        新增推送
        <a href="{{ url('pushes') }}"class="layui-btn  layui-btn-sm" style="margin-left: 30px;">返回列表</a>
    </blockquote>

    <form class="layui-form" action="{{ route('pushes.store') }}" method="post">
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
            <label class="layui-form-label">推送标题</label>
            <div class="layui-input-block">
                <input type="text" name="title" lay-verify="required"  placeholder="请输入推送标题" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序</label>
            <div class="layui-input-inline">
                <input type="number" name="position" lay-verify="required" value="1"  class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">展示图片</label>
            <div class="layui-input-block">
                <button type="button" class="layui-btn layui-btn-danger" id="image"><i class="layui-icon"></i>上传图片</button>
                <input type="hidden" name="image" lay-verify="required" id="image_hidden" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <img src="" alt="" id="show_image" style="max-height: 300px;">
            </div>
        </div>


        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="pushes">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>

@endsection

@section('scripts')
    <script src="{{ asset('assets/admin/js/modules/pushes.js') }}"></script>
@endsection


