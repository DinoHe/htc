<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="Bookmark" href="{{asset('favicon.ico')}}" >
    <link rel="Shortcut Icon" href="{{asset('favicon.ico')}}" />
    <!--[if lt IE 9]>
    <script type="text/javascript" src="{{asset('static/admin/lib/html5.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/admin/lib/respond.min.js')}}"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="{{asset('static/admin/css/htc.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('static/admin/css/htc.admin.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('static/admin/lib/iconfont/1.0.8/iconfont.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('static/admin/skin/default/skin.css')}}" id="skin" />
    @yield('css')
</head>
<body>
@component('layout.admin-header')@endcomponent

@component('layout.admin-menu')@endcomponent

{{--container--}}
@yield('container')

<script type="text/javascript" src="{{asset('js/jquery-3.1.1.min.js')}}"></script>
<script type="text/javascript" src="{{asset('static/admin/lib/layer/2.4/layer.js')}}"></script>
<script type="text/javascript" src="{{asset('static/admin/js/htc.js')}}"></script>
<script type="text/javascript" src="{{asset('static/admin/js/htc.admin.page.js')}}"></script>
@yield('js')
</body>
</html>
