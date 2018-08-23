<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>PinYer后台管理系统</title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=no">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <!-- load css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/css/bootstrap.min.css?v=v3.3.7')}}" media="all">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/css/font/iconfont.css?v=1.0.0')}}" media="all">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/css/layui.css?v=1.0.9')}}" media="all">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/css/jqadmin.css?v=1.3.4')}}" media="all">
</head>

<body>
<ul class='right-click-menu'>
    <li><a href='javascript:;' data-event='fresh'>刷新</a></li>
    <li><a href='javascript:;' data-event='close'>关闭</a></li>
    <li><a href='javascript:;' data-event='other'>关闭其它</a></li>
    <li><a href='javascript:;' data-event='all'>全部关闭</a></li>
</ul>
<div class="layui-layout layui-layout-admin">
    <div class="layui-header">
        <!-- logo区域 -->
        <div class="jqadmin-logo-box">
            <a class="logo" href="#" title="jQAdmin" style="color: #fff;margin:20px 30px;font-size:20px;">后台管理</a>
            <div class="menu-type" data-type="2"><i class="iconfont">&#xe61a;</i></div>
        </div>

        <!-- 主菜单区域 -->
        <div class="jqadmin-main-menu">
            <ul class="layui-nav clearfix" id="menu" lay-filter="main-menu">
                <li class="layui-nav-item layui-this" style="width: auto; line-height: 60px;"><a href="javascript:;" data-title="首页"><i class="iconfont" style="font-size: 14px;"></i><span style="display: inline;">首页</span></a> </li>
                <li class="layui-nav-item " style="width: auto; line-height: 60px;"> <a href="javascript:;" data-title="内容"><i class="iconfont" style="font-size: 14px;"></i><span style="display: inline;">内容</span></a> </li>
                <li class="layui-nav-item" style="width: auto; line-height: 60px;"> <a href="javascript:;" data-title="表单"><i class="iconfont" style="font-size: 14px;"></i><span style="display: inline;">表单</span></a> </li>
                <li class="layui-nav-item " style="width: auto; line-height: 60px;"> <a href="javascript:;" data-title="功能"><i class="iconfont" style="font-size: 14px;"></i><span style="display: inline;">功能</span></a> </li>
                <li class="layui-nav-item " style="width: auto; line-height: 60px;"> <a href="javascript:;" data-title="系统"><i class="iconfont" style="font-size: 14px;"></i><span style="display: inline;">系统</span></a> </li>
                <span class="layui-nav-bar" style="left: 41px; top: 55px; width: 0px; opacity: 0; display: block;"></span>
            </ul>

        </div>
        <!-- 头部右侧导航 -->
        <div class="header-right">
            <ul class="layui-nav right-menu">
                <li class="layui-nav-item first">
                    <a href="javascript:;">
                        <cite> {{Auth::user()->name}}</cite>
                        <span class="layui-nav-more"></span>
                    </a>
                    <dl class="layui-nav-child">
                        <dd>
                            <a class="ajax" style="cursor: pointer" data-params='{"url": "{:url("admin/Login/logout")}","confirm":"false","data":"","complete":"logout"}'>
                                <i class="iconfont ">&#xe64b; </i>退出
                            </a>
                        </dd>
                    </dl>
                </li>
            </ul>
            <button title="刷新" class="layui-btn layui-btn-normal fresh-btn"><i class="iconfont">&#xe62e; </i> </button>
        </div>
    </div>

    <!-- 左侧导航-->
    <div class="layui-side layui-bg-black jqamdin-left-bar">
        <div class="layui-side-scroll">
            <!--子菜单项-->
            <p class="jqadmin-home tab-menu">
                <a href="javascript:;" data-url="{{url('index/home')}}" data-title="控制台">
                    <i class="iconfont" data-icon='&#xe600;'>&#xe600;</i>
                    <span>控制台</span>
                </a>
            </p>
            <div id="submenu">
                <div class="sub-menu" style="display: block;">
                    <ul class="layui-nav layui-nav-tree">
                        <li class="layui-nav-item layui-this" style="width: 200px;">
                            <a href="javascript:;" data-url="/admin/index/home.html" data-title="管理首页" style="padding-left: 30px;">
                                <i class="iconfont" data-icon="" style="font-size: 14px;"></i>
                                <span style="display: inline;">管理首页</span>
                            </a>
                        </li>
                        <li class="layui-nav-item" style="width: 200px;">
                            <a href="javascript:;" data-url="/admin/admin_menu/index.html" data-title="后台菜单" style="padding-left: 30px;">
                                <i class="iconfont" data-icon="" style="font-size: 14px;"></i>
                                <span style="display: inline;">后台菜单</span>
                            </a>
                        </li>
                        <li class="layui-nav-item" style="width: 200px;">
                            <a href="javascript:;" data-url="/admin/nav/index" data-title="导航管理" style="padding-left: 30px;">
                                <i class="iconfont" data-icon="" style="font-size: 14px;"></i>
                                <span style="display: inline;">导航管理</span>
                            </a>
                        </li>
                        <span class="layui-nav-bar" style="top: 22.5px; height: 0px; opacity: 0; display: block;">

                        </span>
                    </ul>
                </div>
                <div class="sub-menu" style="">
                    <ul class="layui-nav layui-nav-tree">
                        <li class="layui-nav-item" style="width: 200px;">
                            <a href="javascript:;" data-url="/kbcms/admin_category/index.html" data-title="栏目管理" style="padding-left: 30px;">
                                <i class="iconfont" data-icon="" style="font-size: 14px;"></i>
                                <span style="display: inline;">栏目管理</span>
                            </a>
                        </li>
                        <li class="layui-nav-item" style="width: 200px;">
                            <a href="javascript:;" data-url="/article/admin_content/index.html" data-title="文章管理" style="padding-left: 30px;"> <i class="iconfont" data-icon="" style="font-size: 14px;"></i> <span style="display: inline;">文章管理</span> </a> </li>
                        <span class="layui-nav-bar" style="display: block;"></span>
                    </ul>
                </div>
                <div class="sub-menu" style="display: none;">
                    <ul class="layui-nav layui-nav-tree">
                        <li class="layui-nav-item" style="width: 200px;"> <a href="javascript:;" data-url="/tplayui/public/kbcms/admin_form_data/index/fieldset_id/1.html" data-title="留言板" style="padding-left: 30px;"> <i class="iconfont" data-icon="" style="font-size: 14px;"></i> <span style="display: inline;">留言板</span> </a> </li>
                        <li class="layui-nav-item layui-this" style="width: 200px;"> <a href="javascript:;" data-url="/tplayui/public/kbcms/admin_form_data/index/fieldset_id/2.html" data-title="友情链接" style="padding-left: 30px;"> <i class="iconfont" data-icon="" style="font-size: 14px;"></i> <span style="display: inline;">友情链接</span> </a> </li>
                        <span class="layui-nav-bar" style="display: block; top: 22.5px; height: 0px; opacity: 0;"></span>
                    </ul>
                </div>
                <div class="sub-menu" style="">
                    <ul class="layui-nav layui-nav-tree">
                        <li class="layui-nav-item" style="width: 200px;"> <a href="javascript:;" data-url="/kbcms/admin_fragment/index.html" data-title="碎片管理" style="padding-left: 30px;"> <i class="iconfont" data-icon="" style="font-size: 14px;"></i> <span style="display: inline;">碎片管理</span> </a> </li>    <li class="layui-nav-item" style="width: 200px;"> <a href="javascript:;" data-url="/kbcms/admin_form/index.html" data-title="表单管理" style="padding-left: 30px;"> <i class="iconfont" data-icon="" style="font-size: 14px;"></i> <span style="display: inline;">表单管理</span> </a> </li>
                        <li class="layui-nav-item" style="width: 200px;"> <a href="javascript:;" data-url="/kbcms/admin_position/index.html" data-title="推荐位管理" style="padding-left: 30px;"> <i class="iconfont" data-icon="" style="font-size: 14px;"></i> <span style="display: inline;">推荐位管理</span> </a> </li>    <li class="layui-nav-item" style="width: 200px;"> <a href="javascript:;" data-url="/kbcms/admin_expand/index.html" data-title="扩展模型管理" style="padding-left: 30px;"> <i class="iconfont" data-icon="" style="font-size: 14px;"></i> <span style="display: inline;">扩展模型管理</span> </a> </li>
                        <span class="layui-nav-bar" style="display: block;"></span>
                    </ul>
                </div>
                <div class="sub-menu" style=""> <ul class="layui-nav layui-nav-tree">
                        <li class="layui-nav-item" style="width: 200px;"> <a href="javascript:;" data-url="/admin/setting/site.html" data-title="系统设置" style="padding-left: 30px;"> <i class="iconfont" data-icon="" style="font-size: 14px;"></i> <span style="display: inline;">系统设置</span> </a> </li>
                        <li class="layui-nav-item" style="width: 200px;"> <a href="javascript:;" data-url="/admin/admin_user/index.html" data-title="后台用户" style="padding-left: 30px;"> <i class="iconfont" data-icon="" style="font-size: 14px;"></i> <span style="display: inline;">后台用户</span> </a> </li>
                        <li class="layui-nav-item" style="width: 200px;"> <a href="javascript:;" data-url="/admin/admin_user_group/index.html" data-title="后台用户组" style="padding-left: 30px;"> <i class="iconfont" data-icon="" style="font-size: 14px;"></i> <span style="display: inline;">后台用户组</span> </a> </li>
                        <li class="layui-nav-item" style="width: 200px;"> <a href="javascript:;" data-url="/admin/user/index.html" data-title="会员管理" style="padding-left: 30px;"> <i class="iconfont" data-icon="" style="font-size: 14px;"></i> <span style="display: inline;">会员管理</span> </a> </li>
                        <li class="layui-nav-item" style="width: 200px;"> <a href="javascript:;" data-url="/admin/user_type/index.html" data-title="会员类型" style="padding-left: 30px;"> <i class="iconfont" data-icon="" style="font-size: 14px;"></i> <span style="display: inline;">会员类型</span> </a> </li>
                        <li class="layui-nav-item" style="width: 200px;"> <a href="javascript:;" data-url="/admin/lang/index.html" data-title="语言管理" style="padding-left: 30px;"> <i class="iconfont" data-icon="" style="font-size: 14px;"></i> <span style="display: inline;">语言管理</span> </a> </li>
                        <span class="layui-nav-bar" style="display: block;"></span>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- 左侧侧边导航结束 -->
    <!-- 右侧主体内容 -->
    <div class="layui-body jqadmin-body">

        <div class="layui-tab layui-tab-card jqadmin-tab-box" id="jqadmin-tab" lay-filter="tabmenu" lay-notAuto="true">
            <ul class="layui-tab-title">
                <li class="layui-this" id="admin-home" lay-id="0" fresh=1><i class="iconfont">&#xe600;</i><em>后台首页</em></li>

            </ul>
            <div class="menu-btn" title="显示左则菜单">
                <i class="iconfont">&#xe616;</i>
            </div>
            <div class="tab-move-btn">
                <span>更多<i class="iconfont">&#xe604;</i></span>

                <!--<span class="move-left-btn"><i class="iconfont">&#xe616;</i></span>
                <span class="move-right-btn"><i class="iconfont ">&#xe618;</i></span>-->
            </div>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                    <iframe class="jqadmin-iframe" name="yk_iframe" id="yk_iframe" data-id='0' src="{{url('admin/home')}}"></iframe>
                </div>
            </div>
        </div>
    </div>
    <!-- 底部区域 -->
    <div class="layui-footer jqadmin-foot">
        <div class="layui-mian">
            <p class="jqadmin-copyright">
                <span class="layui">2018 &copy;</span> Write by PinYer,<a href="#">品悦</a>. 版权所有 <span class="layui">依赖前端框架layui</span>
            </p>
        </div>
    </div>
</div>
<div class="my-tip">
    {{--<img src="{{asset('assets/admin/images/my-tip.jpg'}}" alt="" />--}}
</div>
<ul class="menu-list" id="menu-list"></ul>

</body>
<script id="menu-tpl" type="text/html" data-params='{"url":"{{url("admin/AdminApi/index")}}","listid":"menu","icon":"true"}'>
    @{{# layui.each(d.list, function(index, item){ }}
    <li class="layui-nav-item @{{# if(index==0){ }}layui-this@{{# } }}">
        <a href="javascript:;" data-title="@{{item.name}}"><i class="iconfont">@{{item.iconfont}}</i><span>@{{item.name}}</span></a>
    </li>
    @{{# }); }}

    console.log('debug');
</script>

<script id="submenu-tpl" type="text/html">
    @{{# layui.each(d.list, function(index, menu){ }}
    <div class="sub-menu">
        <ul class="layui-nav layui-nav-tree">
            @{{# layui.each(menu.sub, function(index, submenu){ }} @{{# if(submenu.sub && submenu.sub.length>0){ }}
            <li class="layui-nav-item" data-bind="0">
                <a href="javascript:;" data-title="@{{submenu.name}}">
                    <i class="iconfont">@{{submenu.iconfont}}</i>
                    <span>@{{submenu.name}}</span>
                    <em class="layui-nav-more"></em>
                </a>
                <dl class="layui-nav-child">
                    @{{# layui.each(submenu.sub, function(index, secMenu){ }}
                    <dd>
                        <a href="javascript:;" data-url="@{{secMenu.url}}" data-title="@{{secMenu.name}}">
                            <i class="iconfont " data-icon='@{{secMenu.iconfont}}'>@{{secMenu.iconfont}}</i>
                            <span>@{{secMenu.name}}</span>
                        </a>
                    </dd>
                    @{{# }); }}
                </dl>
            </li>
            @{{# }else{ }}
            <li class="layui-nav-item">
                <a href="javascript:;" data-url="@{{submenu.url}}" data-title="@{{submenu.name}}">
                    <i class="iconfont" data-icon='@{{submenu.iconfont}}'>@{{submenu.iconfont}}</i>
                    <span>@{{submenu.name}}</span>
                </a>
            </li>
            @{{# } }} @{{# }); }}
        </ul>
    </div>
    @{{# }); }}
</script>


<script id="menu-list-tpl" type="text/html">

    @{{# layui.each(d.list, function(index, item){ }}
    <li>
        <a href="javascript:;" data-url="@{{item.href}}" data-title="@{{item.title}}">
            <i class="iconfont " data-icon='@{{item.icon}}'>@{{item.icon}}</i>
            <span>@{{item.title}}</span>
        </a>
    </li>
    @{{# }); }}

</script>
<script type="text/javascript" src="{{asset('assets/admin/js/layui/layui.js')}}"></script>
<script>
    layui.config({
        base: "{{asset('assets/admin/js/')}}/",
    }).extend({
        elem: 'jqmodules/elem',
        tabmenu: 'jqmodules/tabmenu',
        jqmenu: 'jqmodules/jqmenu',
        ajax: 'jqmodules/ajax',
        dtable: 'jqmodules/dtable',
        jqdate: 'jqmodules/jqdate',
        modal: 'jqmodules/modal',
        tags: 'jqmodules/tags',
        jqform: 'jqmodules/jqform',
        echarts: 'lib/echarts',
        webuploader: 'lib/webuploader'
    })
</script>


</html>