@extends('layout.admin-master')
@section('tittle')会员列表 @endsection

@section('header')
    @component('layout.admin-header')@endcomponent
@endsection

@section('aside')
    @component('layout.admin-menu')@endcomponent
@endsection

@section('container')
    <section class="Hui-article-box">
        <nav class="breadcrumb">
            <a class="btn btn-success radius l" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a>
        </nav>
        <div class="Hui-article">
            <article class="cl pd-20">
                <div class="cl pd-5 bg-1 bk-gray mt-20">
                    <span class="l">
                        @if(session('permission') == 0 || in_array("admin/imageDel",session('permission')))
                        <a href="javascript:;" onclick="dataDel('{{url("admin/imageDel")}}')" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a>
                        @endif
                        @if(session('permission') == 0 || in_array("admin/imageAdd",session('permission')))
                        <a class="btn btn-primary radius" onclick="add('添加图片','{{url("admin/imageAdd")}}')" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i> 添加图片</a>
                        @endif
                    </span>
                    <span class="r">共有数据：<strong>{{count($images)}}</strong> 条</span>
                </div>
                <div class="mt-20">
                    <table class="table table-border table-bordered table-bg table-hover table-sort">
                        <thead>
                        <tr class="text-c">
                            <th width="40"><input type="checkbox"></th>
                            <th>分类</th>
                            <th>src</th>
                            <th width="150">更新时间</th>
                            <th width="100">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!$images->isEmpty())
                            @foreach($images as $i)
                            <tr class="text-c">
                                <td><input type="checkbox" class="checkBox"></td>
                                <td>{{$i->type}}</td>
                                <td>{{$i->src}}</td>
                                <td>{{$i->updated_at}}</td>
                                <td class="td-manage">
                                    @if(session('permission') == 0 || in_array("admin/imageEdit",session('permission')))
                                    <a style="text-decoration:none" class="ml-5" onClick="edit('图库编辑','{{url("admin/imageEdit")}}','{{$i->id}}')" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a>
                                    @endif
                                    @if(session('permission') == 0 || in_array("admin/imageDel",session('permission')))
                                    <a style="text-decoration:none" class="ml-5" onClick="onesDel(this,'{{url("admin/imageDel")}}','{{$i->id}}')" href="javascript:;" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        @endif
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

    $('.table-sort').dataTable({
        "aaSorting": [[ 1, "asc" ]],//默认第几个排序
        "bStateSave": true,//状态保存
        "aoColumnDefs": [
            //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
            {"orderable":false,"aTargets":[0,4]}// 制定列不参与排序
        ]
    });


</script>
@endsection
