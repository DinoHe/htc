@extends('layout.admin-master')
@section('tittle')角色编辑 @endsection

@section('container')
    <article class="cl pd-20">
        <form action="{{url('admin/adminRoleEdit')}}" method="post" class="form form-horizontal" id="form-admin-role-edit">
            <div class="row cl">
                <input type="hidden" name="id" value="{{$role->id}}">
                <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>角色名称：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" name="roleName" value="{{$role->name}}" required>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-3">权限：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <dl class="permission-list">
                        <dd>
                            <dl class="cl permission-list2">
                                <dt>
                                    <label>
                                        <input type="checkbox" value="0" name="permission">
                                        所有权限</label>
                                </dt>
                                <dd></dd>
                            </dl>
                            @foreach($permissions as $p)
                            <dl class="cl permission-list2">
                                <dt style="width: 150px">
                                    <label>
                                        <input type="checkbox"
                                            {{in_array($p->id,explode(',',$role->permission))?'checked':''}}>
                                        {{$p->tittle}}</label>
                                </dt>
                                <dd>
                                    <label class="findbox">
                                        <input type="checkbox" value="{{$p->id}}" name="permission[]"
                                            {{in_array($p->id,explode(',',$role->permission))?'checked':''}}>
                                        查看</label>
                                    @foreach($p->childNodes as $node)
                                    <label>
                                        <input type="checkbox" value="{{$node->id}}" name="permission[]"
                                            {{in_array($node->id,explode(',',$role->permission))?'checked':''}}>
                                        {{$node->tittle}}</label>
                                    @endforeach
                                </dd>
                            </dl>
                            @endforeach
                        </dd>
                    </dl>

                </div>
            </div>
            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
                    <button type="submit" class="btn btn-success radius"><i class="icon-ok"></i> 确定</button>
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

    $(".permission-list dt input:checkbox").click(function(){
        $(this).closest("dl").find("dd input:checkbox").prop("checked",$(this).prop("checked"));
    });
    $(".permission-list2 dd input:checkbox").click(function(){
        var l =$(this).parent().parent().find("input:checked").length;
        var l2=$(this).parents(".permission-list").find(".permission-list2 dd").find("input:checked").length;
        if($(this).prop("checked")){
            $(this).parent().siblings('.findbox').find('input:checkbox').prop('checked',true);
            $(this).closest("dl").find("dt input:checkbox").prop("checked",true);
        }
        else{
            if(l==0){
                $(this).closest("dl").find("dt input:checkbox").prop("checked",false);
            }
            if(l2==0){
                $(this).parents(".permission-list").find("dt").first().find("input:checkbox").prop("checked",false);
            }
        }
    });

	$("#form-admin-role-edit").validate({
		rules:{
            roleName:{
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
