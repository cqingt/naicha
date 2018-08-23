<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PinYer - 后台登录</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <!-- load css -->
    <link href="{{asset('assets/admin/login/css/layui.css')}}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/login/css/login.css')}}" media="all">

    <style>
        .btn{
            width: 100%;
            height: 42px;
            border: none;
            font-size: 16px;
            background: #FF5722;
            color: #f8f8f8;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="layui-canvs"></div>
<form id="doLogin" name="doLogin" method="POST" action="/auth/login">
    {!! csrf_field() !!}
    <div class="layui-layout layui-layout-login">
        <h1>
            <strong>PinYer 后台管理系统</strong>
            <!--<em>为你所爱，是你想要</em>-->
        </h1>
        @if (count($errors) > 0)
            <div style="color: #FF5722">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="layui-user-icon larry-login">
            <input type="text" placeholder="账号" id="username" name="name" class="login_txtbx" lay-verify="name" value="{{old('name')}}"/>
        </div>
        <div class="layui-pwd-icon larry-login">
            <input type="password" placeholder="密码" class="login_txtbx" id="password" name="password" lay-verify="password"/>
        </div>

        <div class="layui-submit larry-login">
            <input type="submit" class="btn" value="立即登录" lay-filter="formDemo"/>
            <!--<button type="submit" class="btn btn-primary btn-block">登　录</button>-->
        </div>
        <div class="layui-login-text">

            <p>PinYer后台管理系统<a href="#" title=""> welcome you</a></p>
        </div>
    </div>
</form>
<script src="{{asset('assets/admin/login/js/layui.all.js')}}"></script>
<script src="{{asset('assets/admin/login/js/login.js')}}"></script>
<script src="{{asset('assets/admin/login/js/jquery.form.js')}}"></script>

{{--<script src="{{asset('assets/admin/login/js/jparticle.jquery.js')}}"></script>--}}

<script type="text/javascript">
    layui.use('form', function() {
        var form = layui.form;

        form.on('submit(formDemo)', function(data){
            form.verify({
                username: function (value, item) { //value：表单的值、item：表单的DOM对象
                    if (value.length === 0) {
                        return '账号不能为空';
                    }
                }
                , password: function (value, item) { //value：表单的值、item：表单的DOM对象
                    if (value.length === 0) {
                        return '密码不能为空';
                    }
                }
            });
        });
    });
</script>

</body>
</html>