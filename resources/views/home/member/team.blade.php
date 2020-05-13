@extends('layout.master')
@section('tittle')我的团队@endsection
@section('header')@component('layout.header')@endcomponent @endsection

@section('container')
<div class="app-cells">
    <h4 class="weui-cells__title">我的团队总人数：100 <p class="color-success">已认证：50</p></h4>
    <div class="subordinate_tittle">我的下级</div>
    <table style="text-align: center;width: 100%">
        <thead >
        <tr style="">
            <th>账号</th>
            <th>认证状态</th>
            <th>等级</th>
            <th>团队人数</th>
        </tr>
        </thead>
        <tbody>
        <tr class="table-body">
            <td class="weui-cell__bd">13048814716</td>
            <td class="weui-cell__bd">已认证</td>
            <td class="weui-cell__bd">三级会员</td>
            <td class="weui-cell__bd">100</td>
        </tr>
        <tr class="table-body">
            <td class="weui-cell__bd">13048814716</td>
            <td class="weui-cell__bd">已认证</td>
            <td class="weui-cell__bd">三级会员</td>
            <td class="weui-cell__bd">100</td>
        </tr>
        </tbody>
    </table>
</div>
@endsection

@section('js')
<script>
    $(function () {
        showHeaderBack();
    });
    $('.weui-cell__ft').each(function (k,v) {
        if ($(v).text().indexOf('+') >= 0){
            $(v).addClass('color-error');
        }
    });
</script>
@endsection
