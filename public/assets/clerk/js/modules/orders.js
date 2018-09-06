layui.use(['table','form','jquery','laydate', 'element'], function(){
    var table = layui.table
        ,form = layui.form
        ,$ = layui.jquery
        ,laydate = layui.laydate
        ,element = layui.element;

    var _mod = 'orders';

    table.render({
        elem: '#orders_table'
        ,url: '/clerk/' + _mod + '/list' //数据接口
        ,limit: 10
        ,page: true //开启分页
        ,cols: [[ //表头
            // {fixed: 'left',checkbox : true}
            {field: 'id', title: 'ID', align:'center', width:'50'}
            ,{field: 'order_sn', title: '订单号',  align:'center', width:'200'}
            ,{field: 'member_name', title: '会员姓名', align:'center', width:'150'}
            ,{field: 'status', title: '状态', align:'center', width:'90'}
            ,{field: 'price', title: '价格', align:'center', width:'90'}
            ,{field: 'difference', title: '修改差价', align:'center', width:'120'}
            ,{field: 'pay_type', title: '支付方式', align:'center', width:'100'}
            ,{field: 'payed_at', title: '支付时间', align:'center', width:'160'}
            ,{title: '操作',align:'center', toolbar: '#bartools', width: '220', fixed: 'right'} //这里的toolbar值是模板元素的选择器
        ]]
        ,id: 'testReload'
    });

    table.on('tool(orders_table)', function (obj) { //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
        var data = obj.data; //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr; //获得当前行 tr 的DOM对象

        if (layEvent === 'del') { //删除
            layer.confirm('确定删除行吗', function (index) {
                layer.close(index);
                //向服务端发送删除指令
                $.ajax({
                    type: 'DELETE',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/admin/' + _mod + '/'+obj.data.id,
                    success: function(data) {
                        if(data.code==1){
                            layer.alert(data.msg,{icon: 1});
                            obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                        }else{
                            layer.alert(data.msg,{icon: 2});
                        }
                    },
                    error : function (msg) {
                        console.log('error');
                        layer.alert(data.msg,{icon: 2});
                    }
                });
            });
        } else if (layEvent === 'show') { // 展示

            //do something
            location.href= '/clerk/' + _mod + '/'+obj.data.id;

        }  else if (layEvent === 'edit') { // 编辑
            location.href= '/clerk/' + _mod + '/'+obj.data.id+'/edit';

        } else if (layEvent === 'success') { // 完成订单

            layer.confirm('订单确定完成吗', function (index) {
                layer.close(index);
                //向服务端发送删除指令
                $.ajax({
                    type: 'PUT',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/clerk/' + _mod + '/' + obj.data.id,
                    success: function(data) {
                        if(data.code==1){
                            layer.alert(data.msg,{icon: 1}, function () {
                                location.reload();
                            });
                        }else{
                            layer.alert(data.msg,{icon: 2});
                        }
                    },
                    error : function (msg) {
                        console.log('error');
                        layer.alert(data.msg,{icon: 2});
                    }
                });
            });
        }
    });

    var $ = layui.$, active = {
        reload: function(){
            //执行重载
            table.reload('testReload', {
                page: {
                    curr: 1 //重新从第 1 页开始
                }
                ,where: {
                    order_sn: $('#order_sn').val(),
                    shop_id: $('#shop_id').val(),
                    status:  $('#status').val(),
                    start_time: $('#start_time').val(),
                    stop_time: $('#stop_time').val(),
                    pay_type: $('#pay_type').val()
                }
            });
        },
        reset: function () {
            $('#order_sn, #shop_id,#status,#start_time,#stop_time').val('');
            layui.form.render('select');
            //执行重载
            table.reload('testReload', {
                page: {
                    curr: 1 //重新从第 1 页开始
                }
                ,where: {
                    order_sn: '',
                    shop_id: '',
                    status: '',
                    start_time: '',
                    stop_time: '',
                    pay_type: ''
                }
            });
        }
    };

    // 购物车
    let cup = 'CUP-1';  // 当前杯
    let carts = {};     // 购物车
    let _tab = 0;       // 茶底button
    let container = ''; // 保存整体html

    // 单个购物车
    let cart = {
        'tab' : 0,
        'volume': 0,
        'price': '0.00',
        'temperature': 'hot', // 温度选择
        'sugar' : '', // 糖类选择
        'weight': '',  // 糖分分量
        'double' : '', // 一级品类双倍 的id
        'list': [], // 选中
    };

    window.onload = function()
    {
        container = $('#container').html();
        window.sessionStorage.clear();
    };

    $('.demoTable .layui-btn').on('click', function(){
        var type = $(this).data('type');
        active[type] ? active[type].call(this) : '';
    });

    laydate.render({
        elem: '#start_time'
        , type: 'datetime'
    });

    laydate.render({
        elem: '#stop_time'
        , type: 'datetime'
    });

    // 选项
    $('#container').on('click', '.header button', function () {
        var _index = $(this).index();

        _tab = _index;

        $('.header button').addClass('layui-btn-primary');
        $(this).removeClass('layui-btn-primary');

        // 一级品类
        $('.header-child .layui-row').css('display', 'none');
        $('.header-child .header-child-' + _index).css('display', 'block');

        // // 一级可选
        // $('.header-choose-one .header-choose-item').css('display', 'none');
        // $('.header-choose-one .header-choose-'+_tab).css('display', 'block');
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

    // 四级 多选
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
        calculate();
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
                    $('button.submit').removeAttr("disabled");
                    //$('button.submit').attr('disabled', '');
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
                $('button.submit').removeAttr('disabled');
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
            'temperature': 'hot', // 温度选择
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