@extends('layout.admin-master')
@section('tittle')添加买单 @endsection

@section('container')
<article class="cl pd-20">
	<form action="{{url('admin/tradeBuyAdd')}}" method="post" class="form form-horizontal" id="form-admin-add">
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>账号：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" placeholder="请输入账号" name="account" required>
			</div>
		</div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>买入数量：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <span class="select-box inline">
                    <select name="number" class="select">
                        @foreach($numbers as $n)
                            <option value="{{$n->number}}">{{$n->number}}</option>
                        @endforeach
                    </select>
                </span>
            </div>
        </div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>价格($)：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" placeholder="价格" name="price" value="{{$price}}" required>
			</div>
		</div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>订单数量：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="number" class="input-text" name="orderNumber" value="1" min="1" required>
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

	$("#form-admin-add").validate({
		rules:{
			account:{
				required:true,
			},
            number:{
                required:true,
            },
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
                        layer.msg('添加成功',{icon:6,time:1000});
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
