<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title','Laravel-admin')</title>
    <link rel="stylesheet" href="{{ asset('assets/admin/layui/css/layui.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/clerk/css/clerk.css') }}">
    @yield('styles')
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    <!-- 内容主体区域 -->
    @yield('body')

</div>
<script src="{{ asset('assets/admin/layui/layui.js') }}"></script>

@yield('scripts')
</body>
</html>
