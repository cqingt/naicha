layui.use(['table','form','jquery','laydate'], function(){
    var table = layui.table
        ,form = layui.form
        ,$ = layui.jquery
        ,laydate = layui.laydate;

    var _mod = 'orders';

    table.render({
        elem: '#orders_table'
        ,url: '/admin/' + _mod + '/list' //数据接口
        ,limit: 10
        ,page: true //开启分页
        ,cols: [[ //表头
            // {fixed: 'left',checkbox : true}
            {field: 'id', title: 'ID', width:50, align:'center'}
            ,{field: 'order_sn', title: '订单号', width:180, align:'center'}
            ,{field: 'member_name', title: '会员姓名', align:'center',width:150}
            ,{field: 'shop_name', title: '归属店铺', align:'center',width:150}
            ,{field: 'status', title: '订单状态', align:'center',width:120}
            ,{field: 'price', title: '支付价格', align:'center',width:100}
            ,{field: 'pay_type', title: '支付方式', align:'center',width:150}
            ,{field: 'payed_at', title: '支付时间', align:'center',width:190}
            ,{field: 'created_at', title: '创建时间',align:'center', width: 165}
            ,{title: '操作', width:120, align:'center', toolbar: '#bartools'} //这里的toolbar值是模板元素的选择器
        ]]
        ,id: 'testReload'
    });

    table.on('tool(orders_table)', function (obj) { //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
        var data = obj.data; //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr; //获得当前行 tr 的DOM对象

        if (layEvent === 'del') { //删除
            console.log('detail');
            console.log(obj);
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
            location.href= '/admin/' + _mod + '/'+obj.data.id;
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
});