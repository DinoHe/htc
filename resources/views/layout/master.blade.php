<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('tittle')</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,viewport-fit=cover">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('static/home/css/weui.min.css')}}">
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
{{--alert--}}
            <div id="alert" style="display: none;">
                <div class="weui-mask"></div>
                <div class="weui-dialog">
                    <div class="weui-dialog__bd" id="alert_content">内容</div>
                    <div class="weui-dialog__ft">
                        <a href="javascript:" class="weui-dialog__btn weui-dialog__btn_primary" id="alert_c">知道了</a>
                    </div>
                </div>
            </div>
{{--confirm--}}
            <div id="confirm" style="display: none;">
                <div class="weui-mask"></div>
                <div class="weui-dialog">
                    <div class="weui-dialog__hd"><strong class="weui-dialog__title" id="confirm_tittle">提示</strong></div>
                    <div class="weui-dialog__bd" id="confirm_content">内容</div>
                    <div class="weui-dialog__ft">
                        <a href="javascript:" class="weui-dialog__btn weui-dialog__btn_default" id="confirm_c">取消</a>
                        <a href="javascript:" class="weui-dialog__btn weui-dialog__btn_primary" id="confirm_y">确认</a>
                    </div>
                </div>
            </div>
{{--toast提示--}}
            <div id="toast" style="display: none;">
                <div class="weui-mask_transparent"></div>
                <div class="weui-toast">
                    <i class="weui-icon-success-no-circle weui-icon_toast"></i>
                    <p class="weui-toast__content" id="toast_content">已完成</p>
                </div>
            </div>
{{--loading--}}
            <div id="loading" style="display: none;">
                <div class="weui-mask_transparent"></div>
                <div class="weui-toast">
                    <i class="weui-loading weui-icon_toast"></i>
                    <p class="weui-toast__content" id="loading_content">数据加载中</p>
                </div>
            </div>

            <div class="weui-toptips weui-toptips_warn" id="topTips" style="display: none;">错误提示</div>

        </div>
    </div>

    <script src="{{asset('js/jquery-3.1.1.min.js')}}"></script>
    <script src="https://res.wx.qq.com/open/libs/weuijs/1.2.1/weui.min.js"></script>
    <script>

        function showHeaderBack() {
            $('.weui-header-left').removeClass('app-dp_no');
        }

        function showTabbarBgColor(obj) {
            $(obj).addClass('weui-bar__item_on').siblings().removeClass('weui-bar__item_on');
        }

        var $alert = $('#alert'),
            $alert_content = $('#alert_content'),
            $alert_c = $('#alert_c'),
            $confirm = $('#confirm'),
            $confirm_tittle = $('#confirm_tittle'),
            $confirm_content = $('#confirm_content'),
            $confirm_c = $('#confirm_c'),
            $confirm_y = $('#confirm_y'),
            $toast = $('#toast'),
            $toast_content = $('#toast_content'),
            $loading = $('#loading'),
            $loading_content = $('#loading_content'),
            $topTips = $('#topTips');

        var url = '';
        $.alert = function (content='',redirectTo='') {
            $alert_content.content(content);
            $alert.fadeIn(100);
            url = redirectTo;
        }
        $alert_c.on('click',function () {
            if (url != ''){
                location.href = url;
            }else{
                $alert.fadeOut(100);
            }
        });
        var c_url = '';
        $.confirm = function (tittle='提示',content='',callback,redirectTo='') {
            $confirm_tittle.text(tittle);
            $confirm_content.html(content);
            c_url = redirectTo;
            $confirm.fadeIn(100);
            $confirm_y.on('click',function () {
                $confirm.fadeOut(100);
                if (typeof callback == 'function'){
                    callback();
                    $confirm_y.off();
                }
            });
        }
        $confirm_c.on('click',function () {
            if (c_url != ''){
                location.href = c_url;
            }else{
                $confirm.fadeOut(100);
                $confirm_y.off();
            }
        });

        $.toast = function (content='已完成') {
            $toast_content.html(content);
            $toast.fadeIn(100);
            setTimeout(function () {
                $toast.fadeOut(100);
            },2000);
        }
        $.loading = function (content='加载中') {
            $loading_content.html(content);
            $loading.fadeIn(100);
        }
        $.hideLoading = function () {
            $loading.fadeOut(100);
        }
        $.topTip = function (content='错误') {
            $topTips.html(content);
            $topTips.fadeIn(100);
            setTimeout(function () {
                $topTips.fadeOut(100);
            },2000);
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
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            }
        });
    </script>
    @yield('js')
</body>
</html>
