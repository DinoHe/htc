<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="csrf_token" content="{{ csrf_token() }}">
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
    <title>@yield('tittle')</title>
    @yield('css')
</head>
<body>

@yield('header')

@yield('aside')

{{--container--}}

@yield('container')

    <div id="loading" style="display:none;">
        <div style="width: 100%;height: 100%;background-color: rgba(230,231,234,0.38);z-index: 9999;position:absolute;"></div>
        <div class="loading pos-a" style="z-index: 9999;text-align: center;width: 100%;height: 80%"></div>
    </div>


<script type="text/javascript" src="{{asset('js/jquery-3.1.1.min.js')}}"></script>
<script type="text/javascript" src="{{asset('static/admin/lib/layer/2.4/layer.js')}}"></script>
<script type="text/javascript" src="{{asset('static/admin/js/htc.js')}}"></script>
<script type="text/javascript" src="{{asset('static/admin/js/htc.admin.page.js')}}"></script>
<script>
    var href = location.href;
    $('li a').each(function () {
        if ($(this).attr('href') == href){
            $(this).parent().addClass('current');
            $(this).parents('dd').css('display','block');
            $(this).parents('dd').siblings().addClass('selected');
        }
    });

    $.loading = function (){
        $('#loading').fadeIn(100);
    }
    $.hideLoading = function (){
        $('#loading').fadeOut(100);
    }

    $('.menu_dropdown a').on('click',function () {
        $.loading();
    });

    //批量删除
    function dataDel(url) {
        layer.confirm('确认删除吗？',function () {
            var ids = '',contents = [],content = '';
            $('.checkBox').each(function () {
                if ($(this).prop('checked')){
                    $(this).parent().siblings('.table_content').each(function () {
                        if (content != ''){
                            content += ',' + $(this).text();
                        }else {
                            content += $(this).text();
                        }
                    });
                    contents.push(content);
                    if (ids == ''){
                        ids = $(this).val();
                    }else{
                        ids += ',' + $(this).val();
                    }
                }
            });
            contents = JSON.stringify(contents);
            $.ajax({
                url: url,
                method: 'post',
                data: {'id':ids,'content':contents},
                success: function (data) {
                    layer.msg('删除成功',{icon:1,time:1000});
                    closeLayer();
                }
            });
        });
    }

    function closeLayer(){
        var index = parent.layer.getFrameIndex(window.name);
        setTimeout(function () {
            parent.location.reload();
            parent.layer.close(index);
        },1000);
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        }
    });
</script>
@yield('js')
</body>
</html>
