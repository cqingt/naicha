var element;
var $;
layui.use(['element','jquery','form', 'layedit'],function(){
    element = layui.element;
    $ = layui.jquery;
    var form = layui.form
        ,layer = layui.layer
        ,layedit = layui.layedit;

    //左侧导航根据一级菜单关闭其它一级菜单
    $('ul.layui-nav li>button').click(function() {

        $('ul.layui-nav li>button').addClass('layui-btn-primary');
        $(this).removeClass('layui-btn-primary');
    });

    //监听左侧菜单点击
    element.on('nav(left-menu)', function(elem){
        var _href = $(this).find('button').attr('name');
        var _id = $(this).find('button').attr('id');
        $(this).removeClass('layui-this');

        addTab(elem[0].innerText, _href, _id);
    });

    $('body').on('click', '.layui-layer-tips', function () {
        $('#iframe').attr('src', "/orders?from=tips");
    });

    let first = true;
    // 消息提醒
    setInterval(listenOrder, 4000);

    function listenOrder() {
        $.ajax({
            type: "GET",
            url: "/index/listen",
            timeout: 60000,
            async: true,
            success: function(result) {
                if (result.code === 1) {
                    let total = result.data.total;
                    if (first) {
                        openTips(total);
                        first = false;
                    }
                    $('.order_num').text(total);
                } else {
                    first = true;
                    layer.closeAll();
                }
            }
        });
    }
    listenOrder(true);

    // 右下角弹窗
    function openWindow(num) {
        let type = 'rb';
        layer.open({
            type: 1
            ,title: '新订单提醒'
            ,closeBtn: 1
            ,area: ['300px', '150px']
            ,offset: type //具体配置参考：http://www.layui.com/doc/modules/layer.html#offset
            ,id: 'layerDemo'+type //防止重复弹出
            ,content: '<div style="text-align: center;padding: 40px 0;">有 <span class="order_num" style="font-size:18px;font-weight: bolder"> ' + num + ' </span>个新订单等待处理</div>'
            ,btn: false
            ,skin: 'demo-class'
            ,shade: 0 //不显示遮罩
            ,yes: function(){
                layer.closeAll();
            }
        });
    }

    function openTips(num) {
        layer.tips('有 <span class="order_num" style="font-size:16px;font-weight: bolder"> ' + num + ' </span>个新订单等待处理', '#avatar',  {
            tips: 1,time:0
        });
    }
});

/**
 * 新增tab选项卡，如果已经存在则打开已经存在的，不存在则新增
 * @param tabTitle 选项卡标题名称
 * @param tabUrl 选项卡链接的页面URL
 * @param tabId 选项卡id
 */
function addTab(tabTitle, tabUrl,tabId){
    $('#iframe').attr('src', tabUrl);
}

/**
 * ifrme自适应页面高度，需要设定min-height
 * @param iframe
 */
function setIframeHeight(iframe) {
    if (iframe) {
        var iframeWin = iframe.contentWindow || iframe.contentDocument.parentWindow;
        if (iframeWin.document.body) {
            var height = iframeWin.document.documentElement.scrollHeight || iframeWin.document.body.scrollHeight;
            iframe.height = parseInt(height) + 100;
        }
    }
}

