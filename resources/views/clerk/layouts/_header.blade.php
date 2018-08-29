<ul class="layui-nav layui-layout-right">
    <li class="layui-nav-item">
        <a href="javascript:;">
            <img src="http://t.cn/RCzsdCq" class="layui-nav-img">
            {{ Auth::user()->name }}
        </a>
    </li>
    <li class="layui-nav-item">
        <a href="{{ url('auth/logout') }}"
           onclick="event.preventDefault();  document.getElementById('logout-form').submit();">
            退出
        </a>

        <form id="logout-form" action="{{ url('auth/logout') }}" method="GET" style="display: none;">
            {{ csrf_field() }}
        </form>
    </li>
</ul>