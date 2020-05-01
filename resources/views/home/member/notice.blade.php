@extends('layout.master')
@section('tittle')邀请好友@endsection
@section('header')@component('layout.header')@endcomponent @endsection

@section('container')
<div class="app-cells bg-gray">
    <div class="weui-cells bg-gray">
        <a href="javascript:;" class="weui-cell weui-cell_access">
            <div class="weui-cell__bd">上线公告</div>
            <div class="weui-cell__ft">{{date('Y-m-d')}}</div>
        </a>
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
