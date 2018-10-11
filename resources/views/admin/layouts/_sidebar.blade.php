<li class="layui-nav-item layui-nav-itemed">
    <a class="" href="javascript:;"><i class="layui-icon" style="margin-right: 5px">&#xe620;</i>系统管理</a>
    <dl class="layui-nav-child">
        {{--<dd id="S001" name="{{ url('config/create') }}" class="layui-this"><a href="javascript:;">网站配置</a></dd>--}}
        <dd id="S0001" name="{{ url('data/index') }}"><a href="javascript:;">数据统计</a></dd>
    </dl>
</li>

<li class="layui-nav-item">
    <a href="javascript:;"><i class="layui-icon" style="margin-right: 5px">&#xe68e;</i>店铺管理</a>
    <dl class="layui-nav-child">
        <dd id="S012" name="{{ url('shops') }}"><a href="javascript:;">店铺列表</a></dd>
    </dl>
</li>

<li class="layui-nav-item">
    <a href="javascript:;"><i class="layui-icon" style="margin-right: 5px">&#xe647;</i>商品管理</a>
    <dl class="layui-nav-child">
        <dd id="S013" name="{{ url('goods') }}"><a href="javascript:;">商品列表</a></dd>
    </dl>
</li>

<li class="layui-nav-item">
    <a href="javascript:;"><i class="layui-icon" style="margin-right: 5px">&#xe63c;</i>订单管理</a>
    <dl class="layui-nav-child">
        <dd id="S022" name="{{ url('orders') }}"><a href="javascript:;">订单列表</a></dd>
    </dl>
</li>

<li class="layui-nav-item">
    <a href="javascript:;"><i class="layui-icon" style="margin-right: 5px">&#xe770;</i>会员管理</a>
    <dl class="layui-nav-child">
        <dd id="S032" name="{{ url('members') }}"><a href="javascript:;">会员列表</a></dd>
    </dl>
</li>

<li class="layui-nav-item">
    <a href="javascript:;"><i class="layui-icon" style="margin-right: 5px">&#xe60a;</i>内容管理</a>
    <dl class="layui-nav-child">
        <dd id="S042" name="{{ url('pushes') }}"><a href="javascript:;">推送列表</a></dd>
    </dl>
</li>

<li class="layui-nav-item">
    <a href="javascript:;"><i class="layui-icon" style="margin-right: 5px">&#xe62a;</i>营销管理</a>
    <dl class="layui-nav-child">
        <dd id="S052" name="{{ url('coupons') }}"><a href="javascript:;">优惠券列表</a></dd>
    </dl>
</li>

<li class="layui-nav-item">
    <a href="javascript:;"><i class="layui-icon" style="margin-right: 5px">&#xe612;</i>员工管理</a>
    <dl class="layui-nav-child">
        <dd id="S001" name="{{ url('users/resetPwd') }}"><a href="javascript:;">修改密码</a></dd>
        <dd id="S002" name="{{ url('users/index') }}"><a href="javascript:;">员工列表</a></dd>
        <dd id="S003" name="{{ url('roles/index') }}"><a href="javascript:;">角色</a></dd>
        {{--<dd id="S004" name="{{ url('permissions/index') }}"><a href="javascript:;">权限</a></dd>--}}
    </dl>
</li>
