<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>手机验证</title>
    <link rel="stylesheet" href="{{ asset('assets/admin/layui/css/layui.css') }}">
    <style>
        body{
            background: #f2f2f2;
        }
        form{
            margin: 50px 10px;
        }
        .layui-btn-fluid{
            width: 100%;
        }
        .send-sms{
            display: inline-block;
            width: 47%;
            float: right;
            font-size: 12px;
            padding:0;
        }
        .layui-btn+.layui-btn{
            margin-left: 0;

        }
        .phone-code{
            width: 52%;
            display: inline-block;
        }

        .login-title{
            margin:30px auto;
            text-align: center;
        }
        .send{
            display: block;
        }
        .repeat{
            display: none;
        }
    </style>
</head>
<body class="layui-layout-body">
    <div class="layui-layout layui-layout-admin">
        <form class="layui-form layui-form-pane" action="">

            <div class="login-title"><h2>登录验证</h2></div>

            <div class="layui-form-item">
                <label class="layui-form-label">手机号：</label>
                <div class="layui-input-block">
                    <input type="text" name="phone" lay-verify="required" placeholder="请输入手机号" autocomplete="off" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">验证码</label>
                <div class="layui-input-inline">
                    <input type="text" name="code" lay-verify="required" placeholder="请输入" autocomplete="off" class="layui-input phone-code" maxlength="6">

                    <button type="button" class="layui-btn send-sms layui-bg-red send">获取验证码</button>
                    <button type="button" class="layui-btn send-sms layui-bg-red repeat"><span class="time">59</span>s后重新获取</button>
                </div>

            </div>
            <div class="layui-form-item">
                <button class="layui-btn layui-btn-fluid layui-bg-red submit" type="button">登录</button>
            </div>
        </form>
    </div>
<script src="{{ asset('assets/admin/layui/layui.js') }}"></script>

<script>
    //一般直接写在一个js文件中
    layui.use(['layer', 'form', 'jquery'], function(){
        var layer = layui.layer
            ,$ = layui.jquery
            ,form = layui.form;

        var time = 59;

        $('.send').on('click', function () {
            let phone = $("input[name='phone']").val();

            if (! phone.length) {
                return layer.msg('手机号不能为空');
            }

            if (! /1[356789]\d{9}/.test(phone)) {
                return layer.msg('请输入正确的手机号');
            }

            $('.send').hide();
            $('.repeat').show();

            $.getJSON('/html/send', {phone: phone}, function (result) {
                if (result.code === 200) {

                    layer.msg('验证码已发送');

                    var interval = setInterval(function () {
                        if (time > 1) {
                            time--;
                            $('.time').text(time);
                        } else {
                            time = 59;
                            $('.time').text(59);
                            $('.repeat').hide();
                            $('.send').show();
                            clearInterval(interval);
                        }
                    }, 1000);

                } else {
                    layer.msg(result.msg);
                }
            })

        });

        // login
        $('.submit').on('click', function () {
            let phone = $("input[name='phone']").val();
            let code = $("input[name='code']").val();

            if (! phone || ! code) {
                return layer.msg('手机号或验证码不能为空');
            }

            $.ajax({
                type: 'post',
                url: '/html/submit',
                data: {phone: phone, code: code},
                dataType: 'json',
                success: function (result) {
                    if (result.code === 200) {
                        window.location.href = '/html/index';
                    } else {
                        layer.msg(result.msg);
                    }
                }
            })

        })

        //layer.msg('Hello World');
    });
</script>
</body>
</html>