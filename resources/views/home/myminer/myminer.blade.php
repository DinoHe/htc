@extends('layout.master')
@section('tittle')
    我的矿机
@endsection
@section('css')@yield('css') @endsection

@section('header')
    @component('layout.header')@endcomponent
@endsection

@section('container')
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
