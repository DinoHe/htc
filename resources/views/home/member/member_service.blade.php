@extends('layout.master')
@section('tittle')联系客服@endsection
@section('header')@component('layout.header')@endcomponent @endsection

@section('container')
<div class="app-cells bg-gray">
    <div class="weui-cells bg-gray">
        <div class="weui-cells__title">
            <p>微信：</p>
        </div>
        <div class="weui-cell">
            <p>客服1：kevin123</p>
        </div>
        <div class="weui-cell">
            <p>客服2：kevin123</p>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(function () {
        showHeaderBack();
    });
</script>
@endsection
