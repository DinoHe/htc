@extends('layout.master')
@section('tittle')我的团队@endsection
@section('header')@component('layout.header')@endcomponent @endsection

@section('container')
<div class="app-cells">
    <h4 class="weui-cells__title">我的直推人数：{{count($subordinates)}}
        <p class="color-success">已认证：{{$realNameAuthedNumber}}</p>
        <p class="color-success">团队总算力：{{$teamHashrates}}</p>
    </h4>
    <div class="subordinate_tittle">我的下级</div>
    <table style="text-align: center;width: 100%" cellspacing="0">
        <thead>
        <tr>
            <th>账号</th>
            <th>认证状态</th>
            <th>等级</th>
            <th>直推人数</th>
        </tr>
        </thead>
        <tbody>
        @if(count($subordinates) > 0)
            @foreach($subordinates as $s)
            <tr class="table-body">
                <td class="weui-cell__bd">{{$s->phone}}</td>
                <td class="weui-cell__bd">{{$s->realNameStatus}}</td>
                <td class="weui-cell__bd">{{$s->memberLevel}}</td>
                <td class="weui-cell__bd">{{$s->subordinatesCount}}</td>
            </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4" align="left" class="color-warning">还没有下级会员</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
@endsection

@section('js')
<script>
    $(function () {
        showHeaderBack();
    });
</script>
@endsection
