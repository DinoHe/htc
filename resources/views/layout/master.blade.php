<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('tittle')</title>
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('static/home/css/weui.min.css')}}">
    <link rel="stylesheet" href="{{asset('static/home/css/weuix.css')}}">
    <link rel="stylesheet" href="{{asset('ext/font/iconfont.css')}}">
    <link rel="stylesheet" href="{{asset('static/home/css/app.css')}}">
    @yield('css')

</head>
<body ontouchstart="">
    <div class="page">
        <div class="page__bd" style="height: 100%">
            <div class="weui-tab">
{{--                header--}}
                @yield('header')

                <div class="weui-tab__panel" style="height: 100%;">
                    @section('container')
                    @show
                </div>

                <!-- {{--  footer--}} -->
                @yield('footer')
            </div>
        </div>
    </div>

    <script src="{{asset('js/jquery-3.1.1.min.js')}}"></script>
    <script src="https://res.wx.qq.com/open/libs/weuijs/1.0.0/weui.min.js"></script>
    <script src="{{asset('static/home/js/zepto.min.js')}}"></script>
    <script src="{{asset('static/home/js/zepto.weui.js')}}"></script>
    <script>

        function showHeaderBack() {
            $('.weui-header-left').removeClass('app-dp_no');
        }

        function showTabbarBgColor(obj) {
            $(obj).addClass('weui-bar__item_on').siblings().removeClass('weui-bar__item_on');
        }

        // 限制发送短信验证码
        var time = 60; //短信发送间隔
        var index = time,verify = $('#verify');
        function smsVerify(url){
            var phone = $('input[name="phone"]').val();
            if(11 != phone.length){
                $.toptip('手机号错误');
                return false;
            }
            $.showLoading('正在发送');
            $.ajax({
                url: url+'/'+phone,
                method: 'get',
                dataType: 'json',
                success: function(data){
                    // console.log(data);
                    $.hideLoading();
                    if(data.status == 0){
                        $.toast('发送成功');
                        sendDelay();
                    }else{
                        $.toptip(data.message);
                    }
                },
                error: function(error){
                    console.log(error);
                    $.hideLoading();
                    $.toptip('发送失败，系统错误');
                }
            });
        }

        function sendDelay(){
            verify.removeClass('btn_verify').addClass('delay');
            verify.attr('disabled','disabled');
            setTime();
        }
        function setTime(){
            verify.text(index + 's重新获取');
            index--;
            if(index < 0){
                verify.text('获取验证码');
                verify.removeClass('delay').addClass('btn_verify');
                verify.removeAttr('disabled');
                index = time;
                return false;
            }
            setTimeout('sendDelay()', 1000);
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    @yield('js')
</body>
</html>
