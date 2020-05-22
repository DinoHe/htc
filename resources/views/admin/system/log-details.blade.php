@extends('layout.admin-master')
@section('tittle')详情 @endsection

@section('container')
<article class="cl pd-20 form form-horizontal">
    <div class="row cl">
        <label class="form-label col-xs-4 col-sm-3">类型：</label>
        <div class="formControls col-xs-8 col-sm-9">
            <input type="text" class="input-text" value="{{$log->event}}" disabled>
        </div>
    </div>
    <div class="row cl">
        <label class="form-label col-xs-4 col-sm-3">操作用户：</label>
        <div class="formControls col-xs-8 col-sm-9">
            <input type="text" class="input-text" value="{{$log->account}}" disabled>
        </div>
    </div>
    <div class="row cl">
        <label class="form-label col-xs-4 col-sm-3">ip：</label>
        <div class="formControls col-xs-8 col-sm-9">
            <input type="text" class="input-text" value="{{$log->ip}}" disabled>
        </div>
    </div>
    <div class="row cl">
        <label class="form-label col-xs-4 col-sm-3">时间：</label>
        <div class="formControls col-xs-8 col-sm-9">
            <input type="text" class="input-text" value="{{$log->created_at}}" disabled>
        </div>
    </div>
    <div class="row cl">
        <label class="form-label col-xs-4 col-sm-3">内容：</label>
        <div class="formControls col-xs-8 col-sm-9">
            <div class="input-text" style="height: auto;background-color: #EBEBE4">{{$log->content}}</div>
        </div>
    </div>
</article>
@endsection

