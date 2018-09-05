layui.use(['table','form','jquery','laydate', 'element'], function(){
    var form = layui.form
        ,$ = layui.jquery;

    // 保存整体html
    let container = '';
    window.onload = function() {
        container = $('#container').html();

        let firstly = getPackageData(1);
        for (var i in firstly) {
            if (firstly[i].deploy) {
                // 设置糖类
                var sugar = $('.header-three-item[data-pk="'+ firstly[i]['goods_id'] +'"]');
                sugar.removeClass('disabled');

                $('input[value="' + firstly[i].deploy + '"]', sugar).attr('checked',true);
                form.render();

            } else {
                $('div[data-pk="'+ firstly[i]['goods_id'] +'"]').addClass('active');
            }
        }

        let orderInfo = getPackageData();
        let temperature;

        if (orderInfo.temperature) {
            temperature = orderInfo.temperature[1]; console.log(temperature);
            if (temperature !== 'hot') {
                $('.header-end .temp').eq(0).addClass('layui-btn-primary');
                $('.header-end .temp').eq(1).removeClass('layui-btn-primary');
                $('.temperature').show();

                $('input[value="'+temperature+'"]', $('.temperature')).attr('checked',true);
                form.render();
            }
        }

        init();
        calculate();
    };

    // 初始化选项
    function init() {
        // 分类选项
        let ind = $('.header-child').find('.active').parents('.layui-row').index();
        if (ind) {
            $('.header').find('button').addClass('layui-btn-primary');
            $('.header').find('button').eq(ind - 1).removeClass('layui-btn-primary');
            $('.header-child .layui-row').css('display', 'none');
            $('.header-child .header-child-' + (ind - 1)).css('display', 'block');
        }
    }

    // 获取每杯信息, 默认获取订单信息
    function getPackageData(packageNum = 0) {
        let storageCart = window.sessionStorage.getItem('edit_carts');
        let cartArr = JSON.parse(storageCart);
        if (cartArr) {
            let orderDetail = cartArr[0]['details'];

            return packageNum ? orderDetail[packageNum] : cartArr[0];
        }
    }

    // 购物车
    let cup = 'CUP-1';
    let carts = {};
    // 单个购物车
    let cart = {
        'tab' : 0,
        'volume': 0,
        'price': '0.00',
        'temperature': '热饮', // 温度选择
        'sugar' : '', // 糖类选择
        'weight': '',  // 糖分分量
        'double' : '', // 一级品类双倍 的id
        'list': [], // 选中
    };

    var _tab = 0;

    // 选项
    $('#container').on('click', '.header button', function () {
        var _index = $(this).index();

        _tab = _index;

        $('.header button').addClass('layui-btn-primary');
        $(this).removeClass('layui-btn-primary');

        // 一级品类
        $('.header-child .layui-row').css('display', 'none');
        $('.header-child .header-child-' + _index).css('display', 'block');
    });

    // 一级选中
    $('#container').on('click', '.header-child .cmdlist-container', function () {

        // 取消选中
        if ( $(this).hasClass("active") ) {
            $(this).removeClass('active');

            // 柑橘类不选中
            $('.header-two-item').eq(1).find('.cmdlist-container').removeClass('active');
            $('.header-two-item').eq(1).show();
        } else {

            // 选中
            $('.header-child .cmdlist-container').removeClass('active');

            // 下架不选
            if ($(this).find('img').length) {
                $(this).addClass('active');
            } else {
                return;
            }

            // 设置一级品类双倍参数
            $('.double').data('price', $(this).data('price'));
            $('.double').data('pk', $(this).data('pk'));

            // 奶类排斥
            if ($(this).data('milk')) {
                $('.header-two-item').eq(1).hide();
                $('.header-two-item').eq(1).find('.cmdlist-container').removeClass('active');
            } else {
                $('.header-two-item').eq(1).show();
            }

            // 获取tab值
            $('.header').find('button').each(function (i, item) {
                if (! $(item).hasClass('layui-btn-primary')) {
                    cart.tab = i;
                }
            });
        }

        calculate();
    });

    // 一级可选
    $('#container').on('click', '.header-choose-one .cmdlist-container', function () {
        if ( $(this).hasClass("active") ) {
            $(this).removeClass('active');

            // 柑橘类不选中
            if ($(this).data('milk')) {
                $('.header-two-item').eq(1).find('.cmdlist-container').removeClass('active');
            }
            $('.header-two-item').eq(1).show();

        } else {

            $('.header-choose-one .cmdlist-container').removeClass('active');
            if ($(this).find('img').length) {
                $(this).addClass('active');
            } else {
                return;
            }

            // 奶类排斥
            if ($(this).data('milk')) {
                $('.header-two-item').eq(1).hide();
                $('.header-two-item').eq(1).find('.cmdlist-container').removeClass('active');
            } else {
                $('.header-two-item').eq(1).show();
            }
        }

        calculate();
    });

    // 二级
    $('#container').on('click', '.header-two .cmdlist-container', function () {
        if ( $(this).hasClass("active") ) {
            $(this).removeClass('active');
           
        } else {
            if ($(this).hasClass('double')) {
                if (! $('.header-child').find('.active').length) {
                    layer.msg('请先选择一级品类');
                    return;
                } else {
                    $(this).find('input').val($('.header-child').find('.active input').val()); // 设置商品ID
                }
            }

            $('.header-two .cmdlist-container').removeClass('active');

            if ($(this).find('img').length) {
                $(this).addClass('active');
            } else {
                return;
            }
        }

        calculate();
    });

    // 二级可选
    $('#container').on('click', '.header-two-choose .cmdlist-container', function () {
        if ( $(this).hasClass("active") ) {
            $(this).removeClass('active');
           
        } else {
            $('.header-two-choose .cmdlist-container').removeClass('active');

            if ($(this).find('img').length) {
                $(this).addClass('active');
            } else {
                return;
            }
        }

        calculate();
    });

    // 四级
    $('#container').on('click', '.header-four .cmdlist-container', function () {
        if ( $(this).hasClass("active") ) {
            $(this).removeClass('active');
           
        } else {
            if ($(this).find('img').length) {
                $(this).addClass('active');
            } else {
                return;
            }
        }

        calculate();
    });

    // 五级品类
    $('#container').on('click', '.header-five .cmdlist-container', function () {
        if ( $(this).hasClass("active") ) {
            $(this).removeClass('active');
           
        } else {
            $('.header-five .cmdlist-container').removeClass('active');

            if ($(this).find('img').length) {
                $(this).addClass('active');
            } else {
                return;
            }
        }

        calculate();
    });

    // 奶盖撒料
    $('#container').on('click', '.header-last .cmdlist-container', function () {
        if ( $(this).hasClass("active") ) {
            $(this).removeClass('active');
           
        } else {
            $('.header-last .cmdlist-container').removeClass('active');

            if ($(this).find('img').length) {
                $(this).addClass('active');
            } else {
                return;
            }
        }

        calculate();
    });

    // 糖类按钮
    $('#container').on('click', 'button.change-item', function () {
        var parent = $(this).parents('.header-three-item');
        $('.header-three-item').addClass('disabled');

        $(parent).removeClass('disabled');

        $('input[name="weight"]').each(function (i, item) {
            item.checked = false;
        });
        form.render('radio');
    });

    // 单选按钮
    form.on('radio(filter)',  function(data){
        let parent = $(this).parents('.header-three-item');
        $('.header-three-item').addClass('disabled');

        $(parent).removeClass('disabled');

        // 在删除一杯后，切换杯时 input 选中问题
        $('input[name="weight"]').attr('check', 0);
        $(this).attr('check', 1);

        cart.sugar = $(parent).data('pk'); // 选中ID
        cart.weight = data.value; // 加入购物车
        cart.price = FloatAdd(cart.price, $(parent).data('price'));
        setCups(cup, cart);

        calculate();
    });

    // 提交订单
    form.on('submit(confirm)', function (data) {
        $('button.submit').attr('disabled', 'disabled');

        let storageCart = window.sessionStorage.getItem('carts');
        let cartArr = JSON.parse(storageCart);
        if (cartArr) {
            for(var i in cartArr[0]) {
                if (cartArr[0][i].volume > 500) {
                    layer.msg(i + '超出了500ml, 无法下单');
                    return
                }
            }
        }

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: data.form.action,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {'data': cartArr ? cartArr[0] : ''},
            success: function(data) {
                if(data.code==1){
                    layer.msg('创建订单成功', {icon: 1}, function () {
                        //location.href = '/clerk/orders';
                        location.reload();
                    });

                }else{
                    layer.alert(data.msg,{icon: 2});
                }
            },
            error : function (msg) {
                var json=JSON.parse(msg.responseText);
                $.each(json.errors,function(index,error){
                    $.each(error,function(key,value){
                        layer.alert(value,{icon: 2});
                    });
                });
            },
            complete: function () {
                //$('button.submit').removeAttr('disabled');
            }
        });
        return false;
    });

    // 计算卡路里，价格，订单数
    function calculate() {
        var total = Number(0),   // 总价
            price = Number(0),   // 价格
            volume =  Number(0); // 容量

        let current = getCup(cup);
        if (current) {
            cart = current;
        }

        // 初始化
        cart.list = [];
        cart.volume = 0;
        cart.double = 0;

        $('div.active').each(function (i, item) {
            volume = parseInt(volume) + parseInt($(item).data('volume'));
            price = FloatAdd(price, $(item).data('price'));

            let pk = $(item).data('pk');

            // 一级品类双倍
            if ($(this).hasClass('double')) {
                cart.double = pk;
            } else {
                cart.list.push(pk);
            }
            cart.volume = volume;
        });

        // 糖类单选
        $('input[name="weight"]').each(function (i, item) {
            if ($(item).attr('check') == 1) {
                let parent = $(item).parents('.header-three-item');
                cart.sugar = parent.data('pk');
                price = FloatAdd(price, parent.data('price'));
            }
        });

        cart.price = price; // 计算价格
        setCups(cup, cart);

        // 计算总价
        let storageCart = window.sessionStorage.getItem('carts');
        let cartArr = JSON.parse(storageCart);

        if (cartArr) {
            for(var i in cartArr[0]) {
                //if (cartArr[0][i])
                total = total + cartArr[0][i].price;
            }
        } else {
            total = price;
        }

        console.log(cart);

        $('.order-volume .num').text(volume);
        $('.order-price>.num').text(price.toFixed(2));
        $('.order-total>.num').text(total.toFixed(2));
    }

    // 获取单杯信息
    function getCup(cup) {
        let storageCart = window.sessionStorage.getItem('carts');
        let cartArr = JSON.parse(storageCart);
        if (cartArr) {
            for(var i in cartArr[0]) {
                if (i === cup) {
                    return cartArr[0][i];
                }
            }
        }
    }

    // 存储购物车
    function setCups(cupIndex, cartInfo) {
        if (cartInfo == null) {
            delete carts[cupIndex];
        } else {
            carts[cupIndex] = cartInfo;
        }

        let array = [];
        array.push(carts);
        window.sessionStorage.setItem('carts', JSON.stringify(array));
    }

    //浮点数加法运算
    function FloatAdd(arg1,arg2) {
        var r1, r2, m;
        try {
            r1 = arg1.toString().split(".")[1].length
        } catch (e) {
            r1 = 0
        }
        try {
            r2 = arg2.toString().split(".")[1].length
        } catch (e) {
            r2 = 0
        }
        m = Math.pow(10, Math.max(r1, r2));
        return (arg1 * m + arg2 * m) / m;
    }

    // 添加多杯
    $('.add-cup').on('click', function () {
        if (! $('#container').find('.active').length) {
            layer.msg(cup + '未选择任何商品');
            return;
        }

        if (! $('.header-child').find('.active').length) {
            layer.msg('一级品类必选');
            return;
        }

        if (! $('.header-two').find('.active').length) {
            layer.msg('二级品类必选');
            return;
        }

        // 是否已满500ml
        if (cart.volume > 500) {
             layer.msg('当前杯容量大于500ml，请重新下单');
             return;
        }

        // 存储当前杯到购物车
        setCups(cup, cart);

        $('.carts>.cups').find('button').addClass('layui-btn-primary');
        let len = $('.carts>.cups').find('button').length + 1;
        let html = '<button class="layui-btn layui-btn-sm " type="button">CUP-' +  len  + '</button>';

        $('.carts > .cups').append(html);

        // 存储杯 html 选择
        window.sessionStorage.setItem(cup, $('#container').html());

        cup = 'CUP-' + len;

        // 购物车初始化
        cart = {
            'tab' : 0,
            'volume': 0,
            'price': '0.00',
            'temperature': '热饮', // 温度选择
            'sugar' : '', // 糖类选择
            'weight': '',  // 糖分分量
            'double' : '', // 一级品类双倍 的id
            'list': [], // 选中
        };

        $('#container').html(container);

        window.scrollTo(0, 0); // 滚动到顶部

        $('.cups-num > .num').text($('.carts > .cups').find('button').length); // 统计杯数
        $('.current-cup > .num').text(cup);    // 当前杯
        $('.order-volume .num').text(0);
        $('.order-price>.num').text(0.00);

        form.render('radio');
    });

    // 展示杯数
    $('.shop-cart').on('click', function () {
        if ($('.carts').is(':visible')) {
            $('.carts').hide();
            $(this).addClass('layui-btn-primary');
        } else {
            $('.carts').show();
            $(this).removeClass('layui-btn-primary');
        }
    });

    // 删除杯
    $('.carts .delete-cup').on('click', function () {
        if ($('.carts > .cups>button').length === 1) {
            layer.msg('只有一杯，不能删除');
            return
        }

        $('.carts > .cups>button').each(function (i, item) {
            if (! $(item).hasClass('layui-btn-primary')) {
                window.sessionStorage.removeItem($(item).text()); // 删除html
                setCups($(item).text(), null);  // 删除对应的数据

                $(item).remove();

                let obj = $('.carts > .cups>button');
                // 删除第一个，设置第二个，直接退出，否则会多次删除
                if (i === 0) {
                    obj.eq(0).removeClass('layui-btn-primary');
                    let html = window.sessionStorage.getItem(obj.eq(0).text());
                    $('#container').html(html);

                    cup = obj.eq(0).text(); // 当前杯

                    return;
                }

                obj.eq(i-1).removeClass('layui-btn-primary');
                let html = window.sessionStorage.getItem(obj.eq(i-1).text());
                $('#container').html(html);

                cup = obj.eq(i - 1).text(); // 当前杯
            }
        });

        window.scrollTo(0, 0); // 滚动到顶部

        $('.cups-num > .num').text($('.carts > .cups').find('button').length); // 统计杯数
        $('.current-cup > .num').text(cup);    // 当前杯

        calculate(); //重新计算
    });

    // 购物车选择
    $('.cups').on('click', 'button', function () {
        // 切换时，保存原页面
        window.sessionStorage.setItem(cup, $('#container').html());

        let _index = $(this).index();
        $('.cups').find('button').addClass('layui-btn-primary');
        $('.cups').find('button').eq(_index).removeClass('layui-btn-primary');

        // 刷新页面到新的购物车
        $('#container').html(window.sessionStorage.getItem($(this).text()));

        window.scrollTo(0, 0); // 滚动到顶部

        cup = $(this).text(); // 当前杯
        $('.current-cup > .num').text(cup);    // 当前杯

        // 设置杯 对应数据
        let cupInfo = getCup(cup);

        $('.order-volume .num').text(cupInfo ? cupInfo.volume : 0);
        $('.order-price>.num').text(cupInfo ? cupInfo.price : 0.00 );
    });

    // 温度选择
    $('#container').on('click', '.header-end .temp', function () {
        $('button.temp').addClass('layui-btn-primary');
        $(this).removeClass('layui-btn-primary');
        let _index = $(this).index();

        if (_index) {
            $('.temperature').show();
            cart.temperature = $('.temperature').find('input').eq(0).val(); // 温度选择
        } else {
            $('.temperature').hide();
            cart.temperature = $(this).data('value'); // 温度选择
        }
        setCups(cup, cart);
    });

    // 温度选择
    form.on('radio(temperature)',  function(data){
        cart.temperature = data.value; // 温度选择
        setCups(cup, cart);
    });
});