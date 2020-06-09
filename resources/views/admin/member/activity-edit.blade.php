@extends('layout.admin-master')
@section('tittle')编辑购买活动 @endsection
@section('css')
    <style>
        li{margin: 0 2px}
        .data-item{padding: 2px;color: white;width: 103px;background-color: #00a2d4;
            float: left;border-radius: 3px}
        .data-item label{overflow: hidden;}
        .data-item a{display: inline;color: red;text-decoration: none;font-weight: bold;
            float: right;padding: 0 2px}
    </style>
@endsection

@section('container')
<article class="cl pd-20">
	<form action="{{url('admin/memberActivityEdit')}}" method="post" class="form form-horizontal">
        <input type="hidden" name="id" value="{{$activity->id}}">
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>直推人数：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="number" class="input-text" placeholder="数量" value="{{$activity->subordinate}}" name="subordinate" min="0" style="width: 200px" required>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>要求算力（G）：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="number" class="input-text" placeholder="算力G" value="{{$activity->hashrate}}" name="hashrate" min="0" step="0.1" style="width: 200px" required>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>赠送矿机类型：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <span class="select-box inline">
                    <select name="minerType" class="select">
                        @foreach($miners as $miner)
                            <option value="{{$miner->id}}" {{$activity->reward_miner_type==$miner->id?'selected':''}}>{{$miner->tittle}}</option>
                        @endforeach
                    </select>
                </span>
            </div>
        </div>
		<div class="row cl">
			<label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>赠送矿机数量：</label>
			<div class="formControls col-xs-8 col-sm-9">
				<input type="number" class="input-text" name="number" value="{{$activity->reward_miner_number}}" min="0" style="width: 200px" required>
			</div>
		</div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3">添加已获得赠送的会员：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" style="width: 200px" onchange="this.value.length==11?1:this.value=''">
                <a href="#" onclick="addMember(this)" class="btn btn-success">添加</a>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>已获得赠送的会员：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="hidden" name="rewardMembers" value="{{$activity->rewardMemberStr}}">
                <ul class="input-text">
                    @if(!is_null($activity->rewardMembers))
                    @foreach($activity->rewardMembers as $rm)
                    <li class="data-item">
                        <label>{{$rm}}</label>
                        <a href="#" onclick="removeElement(this)">X</a>
                    </li>
                    @endforeach
                    @endif
                </ul>
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
			subordinate:{
				required:true,
			},
            hashrate:{
                required:true,
            },
            minerType:{
                required:true,
            },
			number:{
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

var $rm = $('input[name="rewardMembers"]');
function removeElement(obj) {
    var account = $(obj).siblings('label').text();
    var c = $rm.val().replace(account,'');
    $rm.val(c);
    $(obj).parent().remove();
}
function addMember(obj){
    var $inpt = $(obj).siblings(),
        li = '<li class="data-item"><label>'+$inpt.val()+'</label><a href="#" onclick="removeElement(this)">X</a></li>';
    if ($inpt.val() == ''){
        return false;
    }
    $.loading();
    $.ajax({
        method:'post',
        url:'{{url("admin/memberActivityAccountCheck")}}',
        data:{'account':$inpt.val()},
        dataType: 'json',
        success: function (data) {
            $.hideLoading();
            if (data.status != 0){
                layer.msg('账号不存在',{icon:2,time:1000});
            }else{
                $('ul').append(li);
                var v = $rm.val();
                if (v != ''){
                    v += ',' + $inpt.val();
                }else{
                    v = $inpt.val();
                }
                $rm.val(v);
                $inpt.val('');
            }
        }
    });
}
</script>
@endsection
