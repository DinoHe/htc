@extends('layout.master')
@section('tittle')账单明细@endsection
@section('header')@component('layout.header')@endcomponent @endsection

@section('container')
<div class="app-cells bg-gray">
    <div class="weui-cells">
        @if(!is_null($bills))
            @foreach($bills as $bill)
            <div class="weui-cell">
                <div class="weui-cell__bd">
                    <p>{{$bill->tittle}}</p>
                    <span class="color-primary app-fs-13">{{$bill->created_at}}</span>
                </div>
                <div class="weui-cell__ft">{{$bill->operation}}</div>
            </div>
            @endforeach
        @endif
    </div>
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
