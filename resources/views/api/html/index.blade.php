<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>首页</title>
    <link rel="stylesheet" href="{{ asset('assets/admin/layui/css/layui.css') }}">
    <style>
        body{
            background: #f2f2f2;
            height:100%;
            width:100%;
        }
        button{
            display: block;
            width: 100%;
            margin-bottom: 30px;
        }
        .button-container{
            width: 90%;
            margin:0 20px;
            position: absolute;
            top: 40%;
            left: 0;
        }

        .layui-tab-title li{
            width: 42%;
        }
        .layui-tab-item{
            padding-top:30px;
        }
        .success{
            display: none;
            color: #0c9076;
        }
        .success-amount{
            margin-left: 12px;
        }
        .error{
            display: none;
            color: #FF5722;
        }
        .layui-form-radio span {
            font-size: 0.8rem;
        }
    </style>
</head>
<body class="layui-layout-body">

<div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
    <ul class="layui-tab-title">
        <li class="layui-this">发放</li>
        <li>核销</li>
    </ul>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            <form class="layui-form " action="">

                <div class="layui-form-item">
                    <label class="layui-form-label">手机号：</label>
                    <div class="layui-input-block">
                        <input type="text" name="phone" lay-verify="required" placeholder="请输入手机号" autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">选择优惠券</label>
                    <div class="layui-input-block">
                        <?php foreach ($coupons as $key => $coupon) { ?>
                            <input type="radio" name="coupon" value="<?php echo $coupon->id; ?>" title="<?php echo $coupon->title; ?>" lay-filter="checked" >
                        <?php } ?>

                            <input type="radio" name="coupon" value="1" title="<?php echo $couponGroup; ?>"  lay-filter="checked" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <button class="layui-btn layui-btn-fluid layui-bg-red provide" type="button">发放</button>
                </div>
            </form>

        </div>

        <div class="layui-tab-item">
            <form class="layui-form " action="">
                <div class="layui-form-item">
                    <label class="layui-form-label">优惠券码：</label>
                    <div class="layui-input-block">
                        <input type="text" name="code" lay-verify="required" placeholder="请输入6位优惠券码" autocomplete="off" class="layui-input" maxlength="6">
                    </div>
                </div>

                <div class="layui-form-item" style="text-align: center;font-size:24px;">
                    <div class="success"><i class="layui-icon layui-icon-ok" style="font-size:24px;">&#xe605;</i> 核销成功</div>
                    <div class="error"><i class="layui-icon layui-icon-close"  style="font-size:24px;">&#x1006;</i> <span class="error-msg">优惠券已使用</span></div>
                    <div class="success success-amount">金额：<span class="amount">0.00</span>元</div>
                </div>

                <div class="layui-form-item">
                    <button class="layui-btn layui-btn-fluid layui-bg-red spend" type="button">核销</button>
                </div>
            </form>
        </div>
    </div>
</div>




<div class="button-container" style="display: none">
        <a href="/html/sendCoupon"><button class="layui-btn layui-btn-fluid layui-btn-lg layui-bg-red layui-btn-primary layui-btn-radius">发放优惠券</button></a>

        <a href="/html/verifyCoupon"><button class="layui-btn layui-btn-fluid layui-btn-lg layui-bg-red layui-btn-primary layui-btn-radius">核销优惠券</button></a>
    </div>

<script src="{{ asset('assets/admin/layui/layui.js') }}"></script>

<script>
    //一般直接写在一个js文件中
    layui.use(['layer', 'form', 'jquery', 'element'], function(){
        var layer = layui.layer
            ,$ = layui.jquery
            ,form = layui.form;

        var couponId = "<?php echo $couponId; ?>"; // 默认选中

        var submit = false;

        // 清空优惠券码
        $("input[name='code']").on('keyup', function () {
            let code = $(this).val();

            if (! /\d{6}/.test(code)) {
                $('.success, .error').hide();
            }
        });

        //被点击的radio的value值
        form.on('radio(checked)', function(data){
            couponId = data.value;
        });

        // 发放
        $('.provide').on('click', function () {
            let phone = $("input[name='phone']").val();

            if (! phone) {
                return layer.msg('请输入手机号码');
            }

            if (! /1[3-9]\d{9}/.test(phone)) {
                return layer.msg('请输入正确的手机号码');
            }

            if (! couponId) {
                return layer.msg('请选择优惠券');
            }

            if (submit) {
                return;
            } else {
                submit = true;
            }

            $.ajax({
                type: 'post',
                url: '/html/sendCoupon',
                data: {phone: phone, couponId: couponId},
                dataType: 'json',
                success: function (result) {
                    submit = false;
                    if (result.code === 200) {
                        layer.msg('优惠券已发放');
                        $("input[name='phone']").val('');
                    } else {
                        layer.msg(result.msg);
                    }
                },

            })

        });

        // 核销
        $('.spend').on('click', function () {
            $('.success, .error').hide();
            let code = $("input[name='code']").val();

            if (! code) {
                return layer.msg('请输入优惠券码');
            }

            if (! /\d{6}/.test(code)) {
                return layer.msg('请输入6位优惠券码');
            }

            if (submit) {
                return;
            } else {
                submit = true;
            }

            $.ajax({
                type: 'post',
                url: '/html/verifyCoupon',
                data: {code: code},
                dataType: 'json',
                success: function (result) {
                    submit = false;
                    if (result.code === 200) {
                        $('.success').show();
                        if (result.data.price > 0 ) {
                            $('.success-amount').text('金额：' + result.data.price + '元');
                            //$('.amount').text(result.data.price);
                        } else {
                            $('.success-amount').text(result.data.title);
                        }

                    } else {
                        $('.error-msg').text(result.msg);
                        $('.error').show();
                        //layer.msg(result.msg);
                    }
                }
            })

        })

    });
</script>
</body>
</html>