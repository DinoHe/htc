@extends('layout.admin-master')
@section('tittle')会员信息修改 @endsection

@section('container')
<article class="cl pd-20">
	<form action="{{url('admin/memberEdit')}}" method="post" class="form form-horizontal">
        @csrf
		<input type="hidden" name="id" value="{{$member->id}}">
        <div class="row cl">
			<label class="form-label col-xs-4 col-sm-3">会员账号：</label>
			<div class="formControls col-xs-8 col-sm-9">
                <div>{{$member->phone}}</div>
			</div>
		</div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>信用：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="number" class="input-text" value="{{$member->credit}}" name="credit" max="100" min="0" required>
            </div>
        </div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3">登录密码：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" autocomplete="off" placeholder="修改登录密码" name="password">
			</div>
		</div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3">安全密码：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" autocomplete="off" placeholder="修改安全密码" name="safe_password">
            </div>
        </div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>状态：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<span class="select-box inline">
                    <select name="activated" class="select">
                        <option value="0" {{$member->activated=='0'?'selected':''}}>已激活</option>
                        <option value="1" {{$member->activated=='1'?'selected':''}}>未激活</option>
                        <option value="2" {{$member->activated=='2'?'selected':''}}>临时冻结</option>
                        <option value="3" {{$member->activated=='3'?'selected':''}}>永久冻结</option>
                    </select>
                </span>
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

	$("form").validate({
		rules:{
            credit:{
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
