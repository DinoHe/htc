@extends('layout.admin-master')
@section('tittle')意见反馈 @endsection

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
                <form action="{{url('admin/memberIdeal')}}" method="post">
                    @csrf
                <div class="text-c">
                    <input type="text" name="account" placeholder="会员账号" style="width:200px" class="input-text">
                    <button class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> 查找</button>
                </div>
                </form>
                <div class="cl pd-5 bg-1 bk-gray mt-20">
                    <span class="l">
                        @if(session('permission') == 0 || in_array("admin/memberIdealDel",session('permission')))
                        <a href="javascript:;" onclick="dataDel('{{url("admin/memberIdealDel")}}')" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a>
                        @endif
                    </span>
                    <span class="r">共有数据：<strong>{{count($ideals)}}</strong> 条</span>
                </div>
                <table class="table table-border table-bordered table-bg table-sort">
                    <thead>
                        <tr class="text-c">
                            <th width="25"><input type="checkbox"></th>
                            <th width="120">账号</th>
                            <th>内容</th>
                            <th width="120">日期</th>
                            <th width="100">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(!$ideals->isEmpty())
                    @foreach($ideals as $i)
                        <tr class="text-c">
                            <td><input type="checkbox" value="{{$i->id}}" class="checkBox"></td>
                            <td class="table_content">{{$i->account}}</td>
                            <td class="table_content">{{$i->content}}</td>
                            <td class="table_content">{{$i->created_at}}</td>
                            <td class="td-manage">
                                @if(session('permission') == 0 || in_array("admin/memberIdealDel",session('permission')))
                                <a title="删除" href="javascript:;" onclick="onesDel(this,'{{url("admin/memberIdealDel")}}','{{$i->id}}')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
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
        "aaSorting": [[ 1, "asc" ]],//默认第几个排序
        "bStateSave": true,//状态保存
        "aoColumnDefs": [
            //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
            {"orderable":false,"aTargets":[0,4]}// 制定列不参与排序
        ]
    });


</script>
@endsection
