layui.use(['table','form','jquery', 'laydate'], function(){
    var table = layui.table
        ,form = layui.form
        ,$ = layui.jquery
        ,laydate = layui.laydate
        ,upload = layui.upload;

    var _mod = 'coupons';
    var prefix = '/';

    table.render({
        elem: '#coupons_table'
        ,url:  prefix + _mod + '/records' //数据接口
        ,limit: 10
        ,page: true //开启分页
        ,cols: [[ //表头
            {field: 'id', title: 'ID', width:'5%', align:'center'}
            ,{field: 'shop_name', title: '所属店铺', align:'center',width:'10%'}
            ,{field: 'title', title: '优惠券标题', align:'center',width:'20%'}
            ,{field: 'amount', title: '发放数量', align:'center',width:'8%'}
            ,{field: 'start_time', title: '优惠券开始时间', align:'center',width:'12%'}
            ,{field: 'stop_time', title: '优惠券结束时间', align:'center',width:'12%'}
            ,{field: 'send_status', title: '发放状态', align:'center',width:'8%'}
            ,{field: 'created_at', title: '创建时间',align:'center', width: '12%'}
            ,{title: '操作', width:'13%', align:'center', toolbar: '#bartools'} //这里的toolbar值是模板元素的选择器
        ]]
    });
    //监听工具条
    table.on('tool(coupons_table)', function (obj) { //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
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
                    url: prefix + _mod + '/'+obj.data.id,
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
        } else if (layEvent === 'edit') { //编辑
            location.href= prefix + _mod + '/'+obj.data.id+'/edit';

        } else if (layEvent === 'grant') { // 发放优惠券
            layer.confirm('确定发放该优惠券吗', function (index) {
                layer.close(index);
                //向服务端发送删除指令
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: prefix + _mod + '/'+obj.data.id + '/grant',
                    success: function(data) {
                        if(data.code==1){
                            layer.alert(data.msg,{icon: 1});
                            window.location.reload();
                        }else{
                            layer.alert(data.msg,{icon: 2});
                        }
                    },
                    error : function (msg) {
                        console.log(msg);
                        layer.alert(data.msg,{icon: 2});
                    }
                });
            });
        }
    });

    //自定义验证规则
    form.verify({

    });

    // 结束时间
    laydate.render({
        elem: '#stop_time'
        ,type: 'datetime'
    });

    // 开始时间
    laydate.render({
        elem: '#start_time'
        ,type: 'datetime'
    });

    //监听提交
    form.on('submit(coupons)', function(data){
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: data.form.action,
            data: data.field,
            success: function(data) {
                if(data.code==1){
                    layer.msg(data.msg,{icon: 1}, function () {
                        location.href = prefix + _mod;
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
            }
        });
        return false;
    });
});