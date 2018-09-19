layui.use(['table','form','jquery'], function(){
    var table = layui.table
        ,form = layui.form
        ,$ = layui.jquery;

    var _mod = 'shops';

    table.render({
        elem: '#shops_table'
        ,url: '/admin/' + _mod + '/records' //数据接口
        ,limit: 10
        ,cellMinWidth: 80
        ,page: true //开启分页
        ,cols: [[ //表头
            // {fixed: 'left',checkbox : true}
            {field: 'id', title: 'ID', width:'5%', align:'center'}
            ,{field: 'name', title: '店铺名称', align:'center', width:'15%'}
            ,{field: 'flag', title: '店铺标识', align:'center', width:'10%'}
            ,{field: 'address', title: '地址', align:'center', width:'20%'}
            ,{field: 'contact', title: '联系方式', align:'center', width:'10%'}
            ,{field: 'created_at', title: '创建时间',align:'center', width:'12%'}
            ,{field: 'updated_at', title: '更新时间',align:'center', width:'12%'}
            ,{field: 'operator', title: '操作', align:'center', toolbar: '#bartools', width:'16%'} //这里的toolbar值是模板元素的选择器
        ]]
    });

    //监听工具条
    table.on('tool(shops_table)', function (obj) { //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
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
        } else if (layEvent === 'edit') { //编辑
            //do something
            location.href= '/admin/' + _mod + '/'+obj.data.id+'/edit';

            // //同步更新缓存对应的值
            // obj.update({
            //     username: '123'
            //     , title: 'xxx'
            // });
        }
    });

    //自定义验证规则
    form.verify({

    });

    //监听提交
    form.on('submit(shop)', function(data){
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: data.form.action,
            data: data.field,
            success: function(data) {
                if(data.code==1){
                    layer.msg(data.msg,{icon: 1}, function () {
                        location.href = '/admin/' + _mod;
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