@extends('layout.admin-master')
@section('tittle')修改币价 @endsection

@section('container')
<article class="cl pd-20">
	<form action="{{url('admin/tradeCoinEdit')}}" method="post" class="form form-horizontal">
        <input type="hidden" value="{{$coin->id}}" name="id">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>价格($)：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="number" class="input-text" step="0.01" name="price" value="{{$coin->price}}" required>
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

	$("form").validate({
		rules:{
			price:{
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
