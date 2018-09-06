
@extends('admin.layouts.app')
@section('title',$user->id? '编辑用户':'添加用户')

@section('body')
    <blockquote class="layui-elem-quote layui-text">
        {{$user->id? '编辑用户':'添加用户'}}
        <a href="{{ url('admin/users/index') }}"class="layui-btn  layui-btn-sm" style="margin-left: 30px;">返回列表</a>
    </blockquote>
    @if($user->id)
        <form class="layui-form" action="{{  url('admin/users/update', $user->id) }}" method="POST">
            <input type="hidden" name="_method" value="PUT">
            @else
                <form class="layui-form" action="{{  url('admin/users/store') }}" method="POST">
                    @endif
        {{ csrf_field() }}
        <div class="layui-form-item">
            <label class="layui-form-label">用户名称</label>
            <div class="layui-input-inline">
                <input type="text" name="name" lay-verify="required|name" autocomplete="off" placeholder="请输入用户名称" class="layui-input" value="{{ old('name', $user->name ) }}">
            </div>
        </div>
            <div class="layui-form-item">
                <label class="layui-form-label">真实姓名</label>
                <div class="layui-input-inline">
                    <input type="text" name="real_name" lay-verify="required" autocomplete="off" placeholder="请输入真实姓名" class="layui-input" value="{{ old('real_name', $user->real_name ) }}">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">手机号</label>
                <div class="layui-input-inline">
                    <input type="text" name="telephone" lay-verify="required" autocomplete="off" placeholder="请输入手机号" class="layui-input" value="{{ old('telephone', $user->telephone ) }}">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">所属店铺</label>
                <div class="layui-input-inline">
                    <select name="shop_id" lay-search="">
                        <option value="">直接选择或搜索选择</option>
                        @foreach($shops as $shop)
                            <option value="{{$shop->id}}"  @if($shop->id == $user->shop_id) selected="selected" @endif  >{{$shop->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        <div class="layui-form-item">
            <label class="layui-form-label">用户邮箱</label>
            <div class="layui-input-inline">
                <input type="text" name="email" lay-verify="" placeholder="请输入用户邮箱" autocomplete="off" class="layui-input" value="{{ old('email', $user->email ) }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">用户密码</label>
            <div class="layui-input-inline">
                <input type="password" name="password"  lay-verify="required" placeholder="请输入用户密码" autocomplete="off" class="layui-input" value="{{ old('password',$user->password ) }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">确认密码</label>
            <div class="layui-input-inline">
                <input type="password" name="password_confirmation" lay-verify="required|confirmpwd" placeholder="请确认密码" autocomplete="off" class="layui-input" value="{{ old('password_confirmation',$user->password) }}">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">选择角色</label>
            <div class="layui-input-inline">
                <select name="role_id" lay-verify="required" lay-search="">
                    <option value="">直接选择或搜索选择</option>
                    @foreach($roles as $item)
                    <option value="{{ $item->id }}" {{ $user->role_id == $item->id ? 'selected': '' }}>{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="user">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>

@endsection

@section('scripts')
    <script src="{{ asset('assets/admin/js/modules/users.js') }}"></script>
@endsection


