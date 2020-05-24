@extends('layout.admin-master')
@section('tittle')充值 @endsection

@section('container')
<article class="cl pd-20">
	<form action="{{url('admin/memberAssetsRechargeEdit')}}" method="post" class="form form-horizontal">
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"></label>
            <div class="formControls col-xs-8 col-sm-9 c-danger f-12">
                注：- 代表扣除！( 扣除10个，输入 -10 )
            </div>
        </div>
		<div class="row cl">
            <input type="hidden" name="id" value="{{$assets->id}}">
			<label class="form-label col-xs-4 col-sm-3">账号：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="text" class="input-text" value="{{$assets->member->phone}}" disabled>
			</div>
		</div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3">余额：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="number" class="input-text" name="balance" step="0.01" style="width: 200px"> HTC
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3">购币总数：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="number" class="input-text" name="buyTotal" step="0.01" style="width: 200px"> HTC
            </div>
        </div>
		<div class="row cl">
			<div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
				<input class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;充值&nbsp;&nbsp;">
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

	$("form").submit(function () {
        $(this).ajaxSubmit({
            success: function (data) {
                if (data.status == 0){
                    layer.msg('充值成功',{icon:1,time:1000});
                    closeLayer();
                }else {
                    layer.msg(data.message,{icon:2,time:1000});
                }
            },
            dataType: 'json'
        });
        return false;
    });
});
</script>
@endsection
