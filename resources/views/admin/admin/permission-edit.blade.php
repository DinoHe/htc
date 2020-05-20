@extends('layout.admin-master')
@section('tittle')权限编辑 @endsection

@section('container')
    <article class="cl pd-20">
        <form action="{{url('admin/adminPermissionEdit')}}" method="post" class="form form-horizontal" id="form-admin-permission-edit">
            <div class="row cl">
                <input type="hidden" name="id" value="{{$permission->id}}">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>权限名称：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="{{$permission->tittle}}" name="tittle" required>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>url：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="{{$permission->url}}" name="url" required>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">上级权限：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <span class="select-box">
                        <select class="select" name="pid" size="1" required>
                            <option value="0">顶级权限</option>
                            @foreach($permissionChildNodes as $p)
                            <option value="{{$p->id}}" {{$p->id==$permission->pid?'selected':''}}>
                                @if($p->level != 0)|
                                @for($i=0;$i<$p->level;$i++)
                                    --
                                @endfor
                                @endif
                                {{$p->tittle}}</option>
                            @endforeach
                        </select>
				    </span>
                </div>
            </div>
            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
                    <button type="submit" class="btn btn-success radius" id="admin-role-save" name="admin-role-save"><i class="icon-ok"></i> 确定</button>
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

	$("#form-admin-permission-edit").validate({
		rules:{
			tittle:{
				required:true,
			},
            url:{
                required:true,
            },
			permission:{
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
