layui.use(['table','form','jquery', 'laydate'], function() {
    var table = layui.table
        , form = layui.form
        , $ = layui.jquery
        , laydate = layui.laydate
        , upload = layui.upload;

    // 结束时间
    laydate.render({
        elem: '#stop_time'
        , type: 'datetime'
    });

    // 开始时间
    laydate.render({
        elem: '#start_time'
        , type: 'datetime'
    });
});