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
    </script>
    @yield('trade-js')
@endsection
