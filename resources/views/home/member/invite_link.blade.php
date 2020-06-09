@extends('layout.master')
@section('tittle')分享好友@endsection
@section('header')@component('layout.header')@endcomponent @endsection

@section('container')
<div class="link">
    <div class="readonly"></div>
    <div class="link-header">
        <div class="link-header_logo"></div>
        <h1 class="link-header_tittle">火 特 币</h1>
    </div>
    <div class="link-container">
        <label>区块链 + 新应用</label>
        <p class="dream">梦想起航 共创辉煌</p>
        <p class="target">先定个小目标，挖出100万</p>
    </div>
    <div class="qrcode">
        <button class="copy" data-clipboard-text="{{$link}}">复制链接</button><br>
        <img src="{{url('home/qrcode/'.encrypt($link))}}">
        <div>扫码注册，开启财富</div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('ext/clipboard/clipboard.min.js')}}"></script>
<script>
    $(function () {
        showHeaderBack();
    });

    var clipboard = new ClipboardJS('.copy');
    clipboard.on('success', function(e) {
        $.toast('已复制');
    });
    clipboard.on('error', function(e) {
        // console.log(e);
        $.topTip('复制失败');
    });
</script>
@endsection
