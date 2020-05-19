@extends('layout.admin-master')
@section('tittle')角色列表 @endsection

@section('header')
    @component('layout.admin-header')@endcomponent
@endsection

@section('aside')
    @component('layout.admin-menu')@endcomponent
@endsection

@section('container')
    <section class="Hui-article-box">
        <nav class="breadcrumb">
            <a class="btn btn-success radius l" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a> </nav>
        <div class="Hui-article">
            <article class="cl pd-20">
                <div class="cl pd-5 bg-1 bk-gray mt-20">
                    <span class="l">
                        <a href="javascript:;" onclick="dataDel('{{url("admin/adminRoleDel")}}')" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a>
                        <a href="javascript:;" onclick="admin_add('添加角色','{{url("admin/adminRoleAdd")}}','800','500')" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i>添加角色</a>
                    </span>
                    <span class="r">共有数据：<strong>{{count($roles)}}</strong> 条</span>
                </div>
                <div class="mt-10">
                <table class="table table-border table-bordered table-bg">
                    <thead>
                        <tr>
                            <th scope="col" colspan="9">角色列表</th>
                        </tr>
                        <tr class="text-c">
                            <th width="25"></th>
                            <th width="150">角色名称</th>
                            <th width="300">所属用户列表</th>
                            <th>权限描述</th>
                            <th width="100">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($roles as $role)
                        <tr class="text-c">
                            <td><input type="checkbox" value="{{$role->id}}" class="checkBox"></td>
                            <td class="table_content">{{$role->name}}</td>
                            <td class="table_content"><a href="#">{{$role->user}}</a></td>
                            <td class="table_content">{{$role->permission}}</td>
                            <td class="td-manage">
                                <a title="编辑" href="javascript:;" onclick="admin_edit('管理员编辑','{{url("admin/adminEdit")}}','{{$role->id}}','800','500')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
                                <a title="删除" href="javascript:;" onclick="admin_del(this,'{{$role->id}}')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                </div>
            </article>
        </div>
    </section>
@endsection

@section('js')
<script type="text/javascript" src="{{asset('static/admin/lib/datePicker/WdatePicker.js')}}"></script>
<script type="text/javascript" src="{{asset('static/admin/lib/dataTables/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{asset('static/admin/lib/layerPage/laypage.js')}}"></script>
<script type="text/javascript">

/*角色-增加*/
function admin_add(title,url,w,h){
	layer_show(title,url,w,h);
}
/*角色-删除*/
function admin_del(obj,id){
	layer.confirm('确认要删除吗？',function(index){
		//此处请求后台程序，下方是成功后的前台处理……
        var content = id;
        $(obj).parent().siblings('.table_content').each(function () {
            content += ',' + $(this).text();
        });
        content = JSON.stringify(content);
        $.post('{{url("admin/adminDel")}}',{'id':id,'content':content});
		$(obj).parents("tr").remove();
		layer.msg('已删除!',{icon:1,time:1000});
	});
}
/*角色-编辑*/
function admin_edit(title,url,id,w,h){
    var u = url + '?id=' + id;
	layer_show(title,u,w,h);
}

</script>
@endsection
