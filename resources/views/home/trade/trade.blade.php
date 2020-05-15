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
        <a href="{{url('home/unprocessedOrder')}}" class="trade-tittle_un"><i class="iconfont icon-daichulidingdan"></i> 待处理订单</a>
        <a href="{{url('home/record')}}" class="trade-tittle_record"><i class="iconfont icon-icon"></i> 交易记录</a>
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
                '<i class="color-error app-fs-13" style="position: absolute;left: 80px"></i>'
            $.confirm('安全验证',content,function () {
                var $password = $('input[name="safePassword"]'),flag = true;
                $.ajax({
                    method: 'post',
                    url: '{{url("home/tradeCheck")}}',
                    data: {'password':$password.val()},
                    dataType: 'json',
                    async: false,
                    success: function (data) {
                        if (data.status != 0){
                            $password.parent('p').siblings('i').text(data.message);
                            flag = false;
                        }
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
                return flag;
            },document.referrer);
        }
    </script>
    @yield('trade-js')
@endsection
