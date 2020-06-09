@extends('layout.admin-master')
@section('tittle')图片编辑 @endsection

@section('container')
<article class="cl pd-20">
	<form action="{{url('admin/imageEdit')}}" method="post" class="form form-horizontal" enctype="multipart/form-data">
		<div class="row cl">
            <input type="hidden" name="id" value="{{$image->id}}">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>分类：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="{{$image->type}}" name="type" required>
			</div>
		</div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3">图片：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="file" name="img" accept="image/*">
                <div style="width: 300px;height: 240px">
                    <img src="{{asset('storage/homeImg/'.$image->src)}}" width="100%" height="100%">
                </div>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3">图片名称：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="{{$image->type}}" name="imgTittle">
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
<script type="text/javascript" src="{{asset('static/admin/lib/jqueryValidation/jquery.validate.js')}}"></script>
<script type="text/javascript" src="{{asset('static/admin/lib/jqueryValidation/validate-methods.js')}}"></script>
<script type="text/javascript" src="{{asset('static/admin/lib/jqueryValidation/messages_zh.js')}}"></script>
<script type="text/javascript">
$(function(){
	$('.skin-minimal input').iCheck({
		checkboxClass: 'icheckbox-blue',
		radioClass: 'iradio-blue',
		increaseArea: '20%'
	});

    $('input:file').on('change',function (e) {
        var src,url = window.URL,files = e.target.files;
        src = url.createObjectURL(files[0]);
        $(this).siblings().children().attr('src',src);
    });

	$("form").validate({
		rules:{
			type:{
				required:true,
			},
		},
		onkeyup:false,
		focusCleanup:true,
		success:"valid",
		submitHandler:function(form){
			$(form).ajaxSubmit({
                success: function (data) {
                    if (data.status == 0){
                        layer.msg('修改成功',{icon:6,time:1000});
                        closeLayer();
                    }else {
                        layer.msg(data.message,{icon:5,time:1000});
                    }
                },
                dataType: 'json'
            });
		}
	});
});
</script>
@endsection
