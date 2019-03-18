<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>发放优惠券</title>
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
            /*margin-top: 60px;*/
        }

    </style>
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    <form class="layui-form " action="">

        <div class="login-title"><h2>发放优惠券</h2></div>

        <div class="layui-form-item">
            <label class="layui-form-label">手机号：</label>
            <div class="layui-input-block">
                <input type="text" name="phone" lay-verify="required" placeholder="请输入手机号" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">选择优惠券</label>
            <div class="layui-input-block">
                <input type="radio" name="coupon" value="1" title="优惠券5元" lay-filter="checked" checked>
                <input type="radio" name="coupon" value="2" title="优惠券8元" lay-filter="checked">
            </div>
        </div>
        <div class="layui-form-item">
            <button class="layui-btn layui-btn-fluid layui-bg-red submit" type="button">发放</button>
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
            let phone = $("input[name='phone']").val();

            if (! phone) {
                return layer.msg('请输入手机号码');
            }

            if (! /1[3-9]\d{9}/.test(phone)) {
                return layer.msg('请输入正确的手机号码');
            }

            if (! coupontId) {
                return layer.msg('请选择优惠券');
            }

            $.ajax({
                type: 'post',
                url: '/html/submit',
                data: {phone: phone, couponId: couponId},
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