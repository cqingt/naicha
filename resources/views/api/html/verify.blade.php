<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>核销优惠券</title>
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

        .login-title{
            margin:50px auto;
            text-align: center;
        }

        .submit{
            margin-top: 20px;
        }

    </style>
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    <form class="layui-form " action="">

        <div class="login-title"><h2>核销优惠券</h2></div>

        <div class="layui-form-item">
            <label class="layui-form-label">优惠券码：</label>
            <div class="layui-input-block">
                <input type="text" name="code" lay-verify="required" placeholder="请输入6位优惠券码" autocomplete="off" class="layui-input" maxlength="6">
            </div>
        </div>


        <div class="layui-form-item">
            <button class="layui-btn layui-btn-fluid layui-bg-red submit" type="button">核销</button>
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

        var couponId = 1; // 默认选中

        form.on('radio(checked)', function(data){
            couponId = data.value; //被点击的radio的value值
        });

        $('.submit').on('click', function () {
            let code = $("input[name='code']").val();

            if (! code) {
                return layer.msg('请输入优惠券码');
            }

            if (! /\d{6}/.test(code)) {
                return layer.msg('请输入6位优惠券码');
            }

            $.ajax({
                type: 'post',
                url: '/html/submit',
                data: {code: code},
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

    });
</script>
</body>
</html>