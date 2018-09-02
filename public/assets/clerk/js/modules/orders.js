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
            ,{field: 'pay_type', title: '支付方式', align:'center', width:'100'}
            ,{field: 'payed_at', title: '支付时间', align:'center', width:'160'}
            ,{title: '操作',align:'center', toolbar: '#bartools', width: '250'} //这里的toolbar值是模板元素的选择器
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

    var _tab = 0;

    // 选项
    $('.header button').on('click', function (event) {
        event.preventDefault();
        var _index = $(this).index();

        _tab = _index;

        $('.header button').addClass('layui-btn-primary');
        $(this).removeClass('layui-btn-primary');

        // 一级品类
        $('.header-child .layui-row').css('display', 'none');
        $('.header-child .header-child-' + _index).css('display', 'block');

        // 一级可选
        $('.header-choose-one .header-choose-item').css('display', 'none');
        $('.header-choose-one .header-choose-'+_tab).css('display', 'block');
    });
/*
    $('.cmdlist-container').on('click', function () {
        // if ( $(this).hasClass("active") ) {
        //     console.log($(this));
        //     $(this).attr('class', 'cmdlist-container');
        //     //$(this).parent().find('.cmdlist-container').removeClass("active");
        // }
    });*/

    // 选中
    $('.header-child .cmdlist-container').on('click', function () {
        if ( $(this).hasClass("active") ) {
            $(this).removeClass('active');
           
        } else {
            $('.header-child .cmdlist-container').removeClass('active');
            $(this).addClass('active');
            
        }
        caculate();
    });

    $('.header-choose-one .cmdlist-container').on('click', function () {
        if ( $(this).hasClass("active") ) {
            $(this).removeClass('active');
           
        } else {
            $('.header-choose-one .cmdlist-container').removeClass('active');
            $(this).addClass('active');
            
        }

        caculate();
    });

    // 一级可选
    $('.header-two .cmdlist-container').on('click', function () {
        if ( $(this).hasClass("active") ) {
            $(this).removeClass('active');
           
        } else {
            $('.header-two .cmdlist-container').removeClass('active');
            $(this).addClass('active');
            
        }

        caculate();
    });

    // 二级可选
    $('.header-two-choose .cmdlist-container').on('click', function () {
        if ( $(this).hasClass("active") ) {
            $(this).removeClass('active');
           
        } else {
            $('.header-two-choose .cmdlist-container').removeClass('active');
            $(this).addClass('active');
            
        }

        caculate();
    });

    $('.header-four .cmdlist-container').on('click', function () {
        if ( $(this).hasClass("active") ) {
            $(this).removeClass('active');
           
        } else {
            $('.header-four .cmdlist-container').removeClass('active');
            $(this).addClass('active');
            
        }

        caculate();
    });

    $('.header-five .cmdlist-container').on('click', function () {
        if ( $(this).hasClass("active") ) {
            $(this).removeClass('active');
           
        } else {
            $('.header-five .cmdlist-container').removeClass('active');
            $(this).addClass('active');
            
        }

        caculate();
    });

    $('.header-last .cmdlist-container').on('click', function () {
        if ( $(this).hasClass("active") ) {
            $(this).removeClass('active');
           
        } else {
            $('.header-last .cmdlist-container').removeClass('active');
            $(this).addClass('active');
            
        }

        caculate();
    });

    $('button.change-item').on('click', function (event) {
        event.preventDefault();
        /*var parent = $(this).parents('.header-three-item');
        $('.header-three-item').addClass('disabled');

        $(parent).removeClass('disabled')*/
    });

    // 单选按钮
    form.on('radio(filter)', function(data){
        console.log(data);
        var parent = $(this).parents('.header-three-item');
        $('.header-three-item').addClass('disabled');

        $(parent).removeClass('disabled')
    });

    var volume = 0;
    form.on('submit(confirm)', function (data) {
        if (volume > 500) {
            layer.msg('容量超出了500ml，请重新选择');
            return false;
        }
    });

    // 计算卡路里，价格，订单数
    function caculate() {
        var calorie = 0, // 卡路里
            amount = 0,   // 数量
            price = 0;   // 价格

        volume =0;   // 毫升

        $('div.active').each(function (i, item) {
            calorie = parseInt(calorie) + parseInt($(item).data('calorie'));
            volume = parseInt(volume) + parseInt($(item).data('volume'));
            //price = parseFloat(price).toFixed(2) + parseFloat($(item).data('price')).toFixed(2);
            price = FloatAdd(price, $(item).data('price'));
            amount++;
        });

        console.log(volume);

        $('.goods-num .num').text(amount);
        $('.goods-cal .num').text(calorie);
        $('.goods-price>.num').text(price);
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



    });