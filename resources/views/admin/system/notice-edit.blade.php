@extends('layout.admin-master')
@section('tittle')公告编辑 @endsection

@section('container')
<article class="cl pd-20">
	<form action="{{url('admin/systemNoticeEdit')}}" method="post" class="form form-horizontal">
		@csrf
        <input type="hidden" name="id" value="{{$notice->id}}">
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>标题：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="{{$notice->tittle}}" name="tittle" required>
			</div>
		</div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>内容：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <script id="editor" name="content" type="text/plain" style="width:100%;height:400px;">{!! $notice->content !!}</script>
            </div>
        </div>
		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
				<input class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
			</div>
		</div>
	</form>
</article>
@endsection

@section('js')
<script type="text/javascript" src="{{asset('static/admin/lib/ueditor/ueditor.config.js')}}"></script>
<script type="text/javascript" src="{{asset('static/admin/lib/ueditor/ueditor.all.min.js')}}"></script>
<script type="text/javascript" src="{{asset('static/admin/lib/ueditor/lang/zh-cn/zh-cn.js')}}"></script>
<script type="text/javascript">
$(function(){

    $('form').submit(function () {
        layer.msg('提交成功',{icon:6,time:1000});
        closeLayer();
    });

    var ue = UE.getEditor('editor');
});
</script>
@endsection
