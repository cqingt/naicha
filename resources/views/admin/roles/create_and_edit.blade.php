
@extends('admin.layouts.app')
@section('title',isset($roles->id)? '编辑角色':'添加角色')

@section('body')
    <blockquote class="layui-elem-quote layui-text">
        {{isset($roles->id)? '编辑角色':'添加角色'}}
        <a href="{{ url('roles') }}"class="layui-btn  layui-btn-sm" style="margin-left: 30px;">返回列表</a>
    </blockquote>
    @if(isset($roles->id))
        <form class="layui-form" action="{{ url('roles', $roles->id) }}" method="POST">
            <input type="hidden" name="_method" value="PUT">
            @else
                <form class="layui-form" action="{{ url('roles') }}" method="POST">
                    @endif
                    {{ csrf_field() }}
                    <div class="layui-form-item">
                        <label class="layui-form-label">角色名称</label>
                        <div class="layui-input-inline">
                            <input type="text" name="name" lay-verify="required|name" autocomplete="off" placeholder="请输入用户名称" class="layui-input" value="{{ old('name', $roles->name ) }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">角色说明</label>
                        <div class="layui-input-block">
                            <textarea placeholder="请输入角色说明" class="layui-textarea" name="description">{{ old('description', $roles->description ) }}</textarea>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="role">立即提交</button>
                            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                        </div>
                    </div>
    </form>

@endsection

@section('scripts')
    <script src="{{ asset('assets/admin/js/modules/roles.js') }}"></script>
@endsection


