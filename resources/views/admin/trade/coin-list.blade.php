@extends('layout.admin-master')
@section('tittle')币价列表 @endsection

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
                        @if(session('permission') == 0 || in_array("admin/coinDestroy",session('permission')))
                        <a href="javascript:;" onclick="dataDel('{{url("admin/coinDestroy")}}')" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a>
                        @endif
                        @if(session('permission') == 0 || in_array("admin/coinAdd",session('permission')))
                            <a href="javascript:;" onclick="add('添加币价','{{url("admin/coinAdd")}}','800','300')" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i>添加币价</a>
                        @endif
                    </span>
                    <span class="r">共有数据：<strong>{{count($coins)}}</strong> 条</span>
                </div>
                <table class="table table-border table-bordered table-bg table-sort">
                    <thead>
                        <tr class="text-c">
                            <th width="25"><input type="checkbox"></th>
                            <th>ID</th>
                            <th>币价</th>
                            <th width="150">日期</th>
                            <th width="100">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(!empty($coins))
                    @foreach($coins as $c)
                        <tr class="text-c">
                            <td><input type="checkbox" value="{{$c->id}}" class="checkBox"></td>
                            <td>{{$c->id}}</td>
                            <td>{{$c->price}}</td>
                            <td>{{$c->created_at}}</td>
                            <td class="td-manage">
                                @if(session('permission') == 0 || in_array("admin/coinEdit",session('permission')))
                                    <a title="编辑" href="javascript:;" onclick="edit('修改币价','{{url("admin/coinEdit")}}','{{$c->id}}','800','300')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
                                @endif
                                @if(session('permission') == 0 || in_array("admin/coinDestroy",session('permission')))
                                <a title="删除" href="javascript:;" onclick="onesDel(this,'{{url("admin/coinDestroy")}}','{{$c->id}}')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @endif
                    </tbody>
                </table>
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
        "aaSorting": [[ 1, "desc" ]],//默认第几个排序
        "bStateSave": true,//状态保存
        "aoColumnDefs": [
            //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
            {"orderable":false,"aTargets":[0,4]}// 制定列不参与排序
        ]
    });

</script>
@endsection
