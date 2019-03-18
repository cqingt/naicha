layui.use(['table','form','jquery','laydate', 'element'], function(){
    var table = layui.table
        ,form = layui.form
        ,$ = layui.jquery
        ,laydate = layui.laydate
        ,element = layui.element;

    var _mod = 'orders';

    table.render({
        elem: '#orders_table'
        ,url: '/' + _mod + '/records' //数据接口
        ,limit: 10
        ,page: true //开启分页
        ,cols: [[ //表头
            // {fixed: 'left',checkbox : true}
            {field: 'id', title: 'ID', align:'center', width:'100'}
            ,{field: 'order_sn', title: '订单号',  align:'center', width:'200'}
            ,{field: 'member_name', title: '会员姓名', align:'center', width:'150'}
            ,{field: 'status', title: '状态', align:'center', width:'90'}
            ,{field: 'price', title: '价格', align:'center', width:'90'}
            ,{field: 'difference', title: '修改差价', align:'center', width:'120'}
            ,{field: 'pay_type', title: '支付方式', align:'center', width:'100'}
            ,{field: 'payed_at', title: '支付时间', align:'center', width:'180'}
            ,{title: '操作',align:'center', toolbar: '#bartools', width: '200', fixed: 'right'} //这里的toolbar值是模板元素的选择器
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
            location.href= '/' + _mod + '/'+obj.data.id;

        }  else if (layEvent === 'edit') { // 编辑
            location.href= '/' + _mod + '/'+obj.data.id+'/edit';

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
                    url: '/' + _mod + '/' + obj.data.id,
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
        } else if (layEvent === 'cancel') { // 取消订单
            layer.confirm('确定要取消订单吗', function (index) {
                layer.close(index);
                //向服务端发送删除指令
                $.ajax({
                    type: 'PUT',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/' + _mod + '/cancel/' + obj.data.id,
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
        } else if (layEvent === 'action') { // 弹窗
            var pk = data.id;
            var buttons;

            if ( data.status_type == 1) {
                buttons = '<button class="layui-btn actions" data-event="show" data-pk="'+pk+'"><i class="layui-icon">&#xe63c;</i>  详情</button>' +
                    '<button class="layui-btn layui-btn-primary actions" data-event="edit" data-pk="'+pk+'"><i class="layui-icon">&#xe642;</i>修改</button>' +
                    '<button class="layui-btn layui-btn-normal actions" data-event="success" data-pk="'+pk+'"><i class="layui-icon">&#xe605;</i>完成</button>' +
                    '<button class="layui-btn layui-btn-danger actions" data-event="cancel" data-pk="'+pk+'"><i class="layui-icon">&#xe642;</i>取消</button>';
            } else {
                buttons = '<button class="layui-btn layui-btn" lay-event="show" data-pk="'+pk+'"><i class="layui-icon">&#xe63c;</i>  详情</button>';
            }

            //页面层
            layer.open({
                title: '操作',
                type: 1,
                skin: 'layui-layer-rim', //加上边框
                area: ['400px', '200px'], //宽高
                content:  buttons
            })
        }

        window.sessionStorage.setItem('form-html', $('.demoTable').html());
    });

    $('body').on('click', 'button.actions', function (obj) {
        var layEvent = $(this).data('event');
        var pk = $(this).data('pk');

        if (layEvent === 'show') { // 展示

            //do something
            location.href= '/' + _mod + '/' + pk;

        }  else if (layEvent === 'edit') { // 编辑
            location.href= '/' + _mod + '/'+pk+'/edit';

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
                    url: '/' + _mod + '/' + pk,
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
        } else if (layEvent === 'cancel') { // 取消订单
            layer.confirm('确定要取消订单吗', function (index) {
                layer.close(index);
                //向服务端发送删除指令
                $.ajax({
                    type: 'PUT',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/' + _mod + '/cancel/' + pk,
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
    var container = ''; // 保存整体html

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
    container = $('#container').html();
    window.sessionStorage.clear();

    // window.onload = function()
    // {
    //     console.log(container);
    // };

    // 从提醒中跳转
    $(function () {
        var html = window.sessionStorage.getItem('form-html');

        if (html) {
            $('.demoTable').html(html);
            window.sessionStorage.setItem('form-html', '');
        }

        if ($('#status').val() > 0) {
            active['reload'].call(this);
        }
    });

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

    // 是否允许选中
    function getAllow(obj) {
        let current = getCup(cup);
        let reject = obj.data('reject');
        var allow = true;

        if (reject.length) {
            var rejectId = reject.split(',');

            if (current != undefined) {
                for (let i = 0, l = current.list.length; i < l; i++) {
                    let id = current.list[i];
                    if (rejectId.indexOf(id.toString()) != -1) {
                        allow = false;
                    }
                }
            }
        }

        return allow;
    }

    // 基底
    $('#container').on('click', '.header-child .cmdlist-container', function () {

        // 取消选中
        if ( $(this).hasClass("active") ) {
            $(this).removeClass('active');
        } else {
            if (! getAllow($(this))) {
                return layer.msg('奶制品与柑橘类或果醋冲突');
            }

            // 选中
            $('.header-child .cmdlist-container').removeClass('active');

            // 下架不选
            if ($(this).find('img').length) {
                $(this).addClass('active');
            } else {
                return;
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

    // 口感
    $('#container').on('click', '.header-choose-one .cmdlist-container', function () {
        if ( $(this).hasClass("active") ) {
            $(this).removeClass('active');

            // 柑橘类不选中
            if ($(this).data('milk')) {
                $('.header-two-item').eq(1).find('.cmdlist-container').removeClass('active');
            }
            $('.header-two-item').eq(1).show();

        } else {
            if (! getAllow($(this))) {
                return layer.msg('奶制品与柑橘类或果醋冲突');
            }

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

    // 风味
    $('#container').on('click', '.header-two .cmdlist-container', function () {
        if ( $(this).hasClass("active") ) {
            $(this).removeClass('active');
        } else {
            if (! getAllow($(this))) {
                return layer.msg('奶制品与柑橘类或果醋冲突');
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

    // 配料
    $('#container').on('click', '.header-two-choose .cmdlist-container', function () {
        if ( $(this).hasClass("active") ) {
            $(this).removeClass('active');
           
        } else {
            //$('.header-two-choose .cmdlist-container').removeClass('active');

            if ($(this).find('img').length) {
                $(this).addClass('active');
            } else {
                return;
            }
        }

        calculate();
    });

    // 奶盖
    $('#container').on('click', '.header-five .cmdlist-container', function () {
        if ( $(this).hasClass("active") ) {
            $(this).removeClass('active');
           
        } else {
            $('.header-five .cmdlist-container').removeClass('active');

            $(this).addClass('active');
        }

        calculate();
    });

    // 奶盖撒料
    $('#container').on('click', '.header-last .cmdlist-container', function () {
        if ( $(this).hasClass("active") ) {
            $(this).removeClass('active');
        } else {
            $('.header-last .cmdlist-container').removeClass('active');

            $(this).addClass('active');
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
        if (! $('.header-child').find('.active').length) {
            layer.msg('基底必选');
            return;
        }

        $('button.submit').attr('disabled', 'disabled');

        let storageCart = window.sessionStorage.getItem('carts');
        let cartArr = JSON.parse(storageCart);


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
                        //location.href = '/orders';
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

        // 温度单选
        $('input[name="temperature"]').each(function (i, item) {
            if ($(item).is(':checked')) {
                //let parent = $(item).parents('.header-three-item');
                cart.temperature = $(item).val();
                //price = FloatAdd(price, parent.data('price'));
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
            layer.msg('基底必选');
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
        console.log(container);
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
            layer.msg('最后一杯，不能删除');
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
    $('#container').on('click', '.temperature input', function () {
        // $('button.temp').addClass('layui-btn-primary');
        // $(this).removeClass('layui-btn-primary');
        // let _index = $(this).index();
        //
        // if (_index) {
        //     $('.temperature').show();
        //     cart.temperature = $('.temperature').find('input').eq(0).val(); // 温度选择
        // } else {
        //     $('.temperature').hide();
        cart.temperature = $(this).val(''); // 温度选择
        //}
        setCups(cup, cart);
    });

    // 温度选择
    form.on('radio(temperature)',  function(data){
        console.log(data);
        cart.temperature = data.value; // 温度选择
        setCups(cup, cart);
    });
});