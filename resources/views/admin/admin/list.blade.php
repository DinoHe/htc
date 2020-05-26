@extends('layout.admin-master')
@section('tittle')管理员列表 @endsection

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
                        @if(session('permission') == 0 || in_array("admin/adminDel",session('permission')))
                        <a href="javascript:;" onclick="dataDel('{{url("admin/adminDel")}}')" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a>
                        @endif
                        @if(session('permission') == 0 || in_array("admin/adminAdd",session('permission')))
                        <a href="javascript:;" onclick="add('添加管理员','{{url("admin/adminAdd")}}','800','500')" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i>添加管理员</a>
                        @endif
                    </span>
                    <span class="r">共有数据：<strong>{{count($admins)}}</strong> 条</span>
                </div>
                <table class="table table-border table-bordered table-bg table-sort">
                    <thead>
                        <tr>
                            <th scope="col" colspan="9">管理员列表</th>
                        </tr>
                        <tr class="text-c">
                            <th width="25"></th>
                            <th width="120">账号</th>
                            <th>姓名</th>
                            <th>手机</th>
                            <th>微信</th>
                            <th>角色</th>
                            <th>加入时间</th>
                            <th width="50">状态</th>
                            <th width="100">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($admins as $admin)
                        <tr class="text-c">
                            <td><input type="checkbox" value="{{$admin->id}}" class="checkBox"></td>
                            <td class="table_content">{{$admin->account}}</td>
                            <td class="table_content">{{$admin->name}}</td>
                            <td class="table_content">{{$admin->phone}}</td>
                            <td class="table_content">{{$admin->weixin}}</td>
                            <td class="table_content">{{$admin->roles->name}}</td>
                            <td class="table_content">{{$admin->created_at}}</td>
                            <td class="td-status">
                                @if($admin->blocked == \App\Http\Models\Admins::ACCOUNT_ON)
                                    <span class="label label-success radius">已启用</span>
                                @else
                                    <span class="label radius">已停用</span>
                                @endif
                            </td>
                            <td class="td-manage">
                                @if(session('permission') == 0 || in_array("admin/adminEdit",session('permission')))
                                    @if($admin->blocked == \App\Http\Models\Admins::ACCOUNT_ON)
                                    <a style="text-decoration:none" onClick="admin_stop(this,'{{$admin->id}}')" href="javascript:;" title="停用"><i class="Hui-iconfont">&#xe631;</i></a>
                                    @else
                                    <a style="text-decoration:none" onClick="admin_start(this,'{{$admin->id}}')" href="javascript:;" title="启用"><i class="Hui-iconfont">&#xe615;</i></a>
                                    @endif
                                    <a title="编辑" href="javascript:;" onclick="edit('管理员编辑','{{url("admin/adminEdit")}}','{{$admin->id}}','800','500')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
                                @endif
                                @if(session('permission') == 0 || in_array("admin/adminDel",session('permission')))
                                <a title="删除" href="javascript:;" onclick="onesDel(this,'{{url("admin/adminDel")}}','{{$admin->id}}')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </article>
        </div>
    </section>
@endsection

@section('js')
<script type="text/javascript" src="{{asset('static/admin/lib/dataTables/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{asset('static/admin/lib/layerPage/laypage.js')}}"></script>
<script type="text/javascript">

    $('.table-sort').dataTable({
        "aaSorting": [[ 5, "desc" ]],//默认第几个排序
        "bStateSave": true,//状态保存
        "aoColumnDefs": [
            //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
            {"orderable":false,"aTargets":[0,7,8]}// 制定列不参与排序
        ]
    });

/*管理员-停用*/
function admin_stop(obj,id){
	layer.confirm('确认要停用吗？',function(index){
		//此处请求后台程序，下方是成功后的前台处理……
        $.get('{{url("admin/adminAccountStop")}}/'+id,function () {
            $(obj).parents("tr").find(".td-manage").prepend('<a onClick="admin_start(this,'+id+')" href="javascript:;" title="启用" style="text-decoration:none"><i class="Hui-iconfont">&#xe615;</i></a>');
            $(obj).parents("tr").find(".td-status").html('<span class="label radius">已停用</span>');
            $(obj).remove();
            layer.msg('已停用!',{icon: 5,time:1000});
        });
	});
}

/*管理员-启用*/
function admin_start(obj,id){
	layer.confirm('确认要启用吗？',function(index){
		//此处请求后台程序，下方是成功后的前台处理……
        $.get('{{url("admin/adminAccountOpen")}}/'+id,function () {
            $(obj).parents("tr").find(".td-manage").prepend('<a onClick="admin_stop(this,'+id+')" href="javascript:;" title="停用" style="text-decoration:none"><i class="Hui-iconfont">&#xe631;</i></a>');
            $(obj).parents("tr").find(".td-status").html('<span class="label label-success radius">已启用</span>');
            $(obj).remove();
            layer.msg('已启用!', {icon: 6,time:1000});
        });
	});
}
</script>
@endsection
