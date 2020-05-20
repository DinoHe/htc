@extends('layout.admin-master')
@section('tittle')权限列表 @endsection

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
                        <a href="javascript:;" onclick="dataDel('{{url("admin/adminPermissionDel")}}')" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a>
                        <a href="javascript:;" onclick="admin_add('添加权限','{{url("admin/adminPermissionAdd")}}','800','500')" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i>添加权限</a>
                    </span>
                    <span class="r">共有数据：<strong>{{count($permissions)}}</strong> 条</span>
                </div>
                <div class="mt-10">
                <table class="table table-border table-bordered table-bg">
                    <thead>
                        <tr>
                            <th scope="col" colspan="9">权限列表</th>
                        </tr>
                        <tr class="text-c">
                            <th width="25"></th>
                            <th>权限名称</th>
                            <th>url</th>
                            <th width="100">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($permissions as $p)
                        <tr class="text-c">
                            <td><input type="checkbox" value="{{$p->id}}" class="checkBox"></td>
                            <td class="table_content" style="text-align: left">
                                @if($p->level != 0)|
                                    @for($i=0;$i<$p->level;$i++)
                                        --
                                    @endfor
                                @endif
                                {{$p->tittle}}</td>
                            <td class="table_content">{{$p->url}}</td>
                            <td class="td-manage">
                                <a title="编辑" href="javascript:;" onclick="admin_edit('权限编辑','{{url("admin/adminPermissionEdit")}}','{{$p->id}}','800','500')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
                                <a title="删除" href="javascript:;" onclick="admin_del(this,'{{$p->id}}')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
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

/*权限-增加*/
function admin_add(title,url,w,h){
	layer_show(title,url,w,h);
}
/*权限-删除*/
function admin_del(obj,id){
	layer.confirm('确认要删除吗？',function(index){
		//此处请求后台程序，下方是成功后的前台处理……
        var content = id;
        $(obj).parent().siblings('.table_content').each(function () {
            content += ',' + $(this).text();
        });
        content = JSON.stringify(content);
        $.post('{{url("admin/adminPermissionDel")}}',{'id':id,'content':content});
		$(obj).parents("tr").remove();
		layer.msg('已删除!',{icon:1,time:1000});
	});
}
/*权限-编辑*/
function admin_edit(title,url,id,w,h){
    var u = url + '?id=' + id;
	layer_show(title,u,w,h);
}

</script>
@endsection
