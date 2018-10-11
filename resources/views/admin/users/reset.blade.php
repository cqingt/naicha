@extends('admin.layouts.app')
@section('title', '重置密码')

@section('body')
    <blockquote class="layui-elem-quote layui-text">
        重置密码
    </blockquote>

    <form class="layui-form" action="{{ url('users/reset') }}">
        {{ csrf_field() }}

        <div class="layui-form-item">
            <label class="layui-form-label">原始密码</label>
            <div class="layui-input-inline">
                <input type="password" name="old_password"  lay-verify="required" placeholder="请输入用户原密码" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">新密码</label>
            <div class="layui-input-inline">
                <input type="password" name="password"  lay-verify="required" placeholder="请输入用户新密码" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">确认密码</label>
            <div class="layui-input-inline">
                <input type="password" name="password_confirmation" lay-verify="required|confirmpwd" placeholder="请确认新密码" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="resets">重置密码</button>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script src="{{ asset('assets/admin/js/modules/users.js') }}"></script>
@endsection
