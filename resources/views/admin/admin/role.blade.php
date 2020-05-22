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
                        @if(session('permission') == 0 || in_array("admin/adminRoleDel",session('permission')))
                        <a href="javascript:;" onclick="dataDel('{{url("admin/adminRoleDel")}}')" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a>
                        @endif
                        @if(session('permission') == 0 || in_array("admin/adminRoleAdd",session('permission')))
                        <a href="javascript:;" onclick="add('添加角色','{{url("admin/adminRoleAdd")}}','800','500')" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i>添加角色</a>
                        @endif
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
                            <td class="table_content"><a href="javascript:void(0);">{{$role->user}}</a></td>
                            <td class="table_content">{{$role->permission}}</td>
                            <td class="td-manage">
                                @if(session('permission') == 0 || in_array("admin/adminRoleEdit",session('permission')))
                                <a title="编辑" href="javascript:;" onclick="edit('管理员编辑','{{url("admin/adminRoleEdit")}}','{{$role->id}}','800','500')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
                                @endif
                                @if(session('permission') == 0 || in_array("admin/adminRoleDel",session('permission')))
                                <a title="删除" href="javascript:;" onclick="onesDel(this,'{{url("admin/adminRoleDel")}}','{{$role->id}}')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
                                @endif
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

@endsection
