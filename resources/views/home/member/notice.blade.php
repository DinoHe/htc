@extends('layout.master')
@section('tittle')系统公告@endsection
@section('header')@component('layout.header')@endcomponent @endsection

@section('container')
<div class="app-cells bg-gray">
    <div class="weui-cells">
        @if(count($notices) > 0)
            @foreach($notices as $notice)
            <a href="{{url('home/noticePreview/'.$notice->id)}}" class="weui-cell weui-cell_access">
                <div class="weui-cell__bd">{{$notice->tittle}}</div>
                <div class="weui-cell__ft">{{$notice->updated_at}}</div>
            </a>
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
</script>
@endsection
