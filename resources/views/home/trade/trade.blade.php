@extends('layout.master')
@section('tittle')
    @yield('trade-tittle')
@endsection
@section('css')@yield('trade-css') @endsection

@section('header')
    @component('layout.header')@endcomponent
@endsection

@section('container')
    <div class="trade-tittle">
        <a href="{{url('home/buy')}}" class="trade-tittle_buy trade-select"><i class="iconfont icon-goumai"></i> 购买</a>
        <a href="{{url('home/tradeCenter')}}" class="trade-tittle_center"><i class="iconfont icon-hangqing1"></i> 交易行情</a>
    </div>
    @yield('trade-container')
@endsection

@section('footer')
    @component('layout.footer')@endcomponent
@endsection

@section('js')
    <script>
        showTabbarBgColor('#trade');

        //交易时间段限制
        var auth = '{{isset($realNameAuth)?$realNameAuth:""}}',e = '{{isset($trade)?$trade:""}}',
            c = '{{session('safeP')}}';
        if (auth != ''){
            $.alert(auth,'{{url('home/member')}}');
        }else if (e != ''){
            $.alert(e,'{{url('home/index')}}');
        }else if (c == '') {
            safeCheck();
        }

        //验证交易密码
        function safeCheck() {
            var content = '<p><input type="password" placeholder="请输入安全密码" name="safePassword"></p>' +
                '<span class="color-error app-fs-13" style="position: absolute;left: 73px"></span>'
            $.confirm('安全验证',content,function () {
                var $password = $('input[name="safePassword"]'),
                    $error = $password.parent('p').siblings('span');
                if ($password.val() == ''){
                    $error.text('请输入安全密码');
                    return false;
                }
                $.ajax({
                    method: 'post',
                    url: '{{url("home/tradeCheck")}}',
                    data: {'password':$password.val()},
                    dataType: 'json',
                    async: false,
                    success: function (data) {
                        if (data.status != 0){
                            $error.text(data.message);
                        }else {
                            location.reload();
                        }
                    },
                    error: function (error) {
                        // console.log(error);
                    }
                });
                return false;
            },document.referrer);
        }
    </script>
    @yield('trade-js')
@endsection
