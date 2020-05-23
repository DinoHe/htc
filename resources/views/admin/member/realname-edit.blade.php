@extends('layout.admin-master')
@section('tittle')实名认证审核 @endsection

@section('container')
<article class="cl pd-20">
	<form action="{{url('admin/memberLevelEdit')}}" method="post" class="form form-horizontal">
        @csrf
		<div class="row cl">
            <input type="hidden" name="id" value="{{$realName->id}}">
			<label class="form-label col-xs-4 col-sm-3">账号：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="{{$realName->member->phone}}" disabled>
			</div>
		</div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>姓名：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" name="name" value="{{$realName->name}}" required>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>身份证：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" name="idcard" value="{{$realName->idcard}}" required>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>微信：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" name="weixin" value="{{$realName->weixin}}" required>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>支付宝：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" name="alipay" value="{{$realName->alipay}}" required>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>银行名称：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" name="bank_name" value="{{$realName->bank_name}}" required>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>银行卡号：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" name="bank_card" value="{{$realName->bank_card}}" required>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3">身份证正面：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="file" class="input-text" name="idcard_front_img" value="{{$realName->idcard}}">
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
            levelName:{
				required:true,
			},
            salesTimes:{
                required:true,
                min:0,
            },
		},
		onkeyup:false,
		focusCleanup:true,
		success:"valid",
		submitHandler:function(form){
			$(form).ajaxSubmit({
                success: function (data) {
                    if (data.status == 0){
                        layer.msg('修改成功',{icon:1,time:1000});
                        closeLayer();
                    }else {
                        layer.msg(data.message,{icon:2,time:1000});
                    }
                },
                dataType: 'json'
            });
		}
	});
});
</script>
@endsection
