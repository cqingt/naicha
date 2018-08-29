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
};