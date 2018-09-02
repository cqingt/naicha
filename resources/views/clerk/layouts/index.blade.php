@extends('clerk.layouts.app')
@section('title','PinYer-Admin')
@section('styles')
    <style>
        .layui-tab-title li:first-child > i {
            display: none;
        }
        body .demo-class .layui-layer-title{background:#393D49; color:#fff; border: none;}
        body .demo-class .layui-layer-btn{border-top:1px solid #E9E7E7}
        body .demo-class .layui-layer-btn a{background:#333;}
        body .demo-class .layui-layer-btn .layui-layer-btn1{background:#999;}
    </style>
@endsection
@section('body')
    <div class="layui-header">
        <div class="layui-logo">店员管理平台</div>
        <!-- 头部区域（可配合layui已有的水平导航） -->
        @include('clerk.layouts._header')
    </div>

    <div class="layui-side layui-bg-black">
        <div class="layui-side-scroll">
            <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
            <ul class="layui-nav layui-nav-tree"  lay-filter="left-menu">
                @include('clerk.layouts._sidebar')
            </ul>
        </div>
    </div>

    <div class="layui-body">
        <iframe src="{{url('clerk/index/data')}}" frameborder="0" id="iframe" width="100%" height="100%"></iframe>
    </div>

{{--    <div class="layui-footer">
        <!-- 底部固定区域 -->
        @include('clerk.layouts._footer')
    </div>--}}
    @endsection

@section('scripts')
    <script src="{{ asset('assets/clerk/js/modules/mobile.js') }}"></script>
    <script>
        layui.use('layer', function() { //独立版的layer无需执行这一句

            var layer = layui.layer; //独立版的layer无需执行这一句
            var type = 'rb';

            layer.open({
                type: 1
                ,title: '订单消息'
                ,closeBtn: 1
                ,area: ['300px', '150px']
                ,offset: type //具体配置参考：http://www.layui.com/doc/modules/layer.html#offset
                ,id: 'layerDemo'+type //防止重复弹出
                ,content: '<div style="padding: 20px 100px;">消息内容</div>'
                ,btn: false
                ,skin: 'demo-class'
                ,shade: 0 //不显示遮罩
                ,yes: function(){
                    layer.closeAll();
                }
            });

        });
    </script>
@endsection