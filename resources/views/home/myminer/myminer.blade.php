@extends('layout.master')
@section('tittle')
    我的矿机
@endsection
@section('css')@yield('css') @endsection

@section('header')
    @component('layout.header')@endcomponent
@endsection

@section('container')
    <div class="myminer-tittle">
        <a href="{{url('home/running')}}" class="myminer-running myminer-select">运行中的矿机</a>
        <a href="{{url('home/finished')}}" class="myminer-finished">已结束的矿机</a>
    </div>
    @yield('myminer-container')
@endsection

@section('footer')
    @component('layout.footer')@endcomponent
@endsection

@section('js')
    <script>
        showTabbarBgColor('#myminer');
    </script>
    @yield('myminer-js')
@endsection
