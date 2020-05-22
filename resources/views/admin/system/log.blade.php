@extends('layout.admin-master')
@section('tittle')系统日志 @endsection

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
                <form action="{{url('admin/systemLog')}}" method="post">
                    @csrf
                <div class="text-c">
                    <span class="select-box inline">
                        <select name="event" class="select">
                            <option value="0">所有类型</option>
                            <option value="登录" {{old('event')=='登录'?'selected':''}}>登录</option>
                            <option value="更新" {{old('event')=='更新'?'selected':''}}>更新</option>
                            <option value="新增" {{old('event')=='新增'?'selected':''}}>新增</option>
                            <option value="删除" {{old('event')=='删除'?'selected':''}}>删除</option>
                        </select>
                    </span>
                    日期范围：
                    <input type="text" name="date_start" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}',
                    dateFmt:'yyyy-MM-dd HH:mm:ss',isShowClear:false})" id="logmin" value="{{old('date_start')?:date('Y-m-d 00:00:00')}}" class="input-text Wdate" style="width:170px;">
                    -
                    <input type="text" name="date_end" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'logmin\')||\'%y-%M-%d\'}',maxDate:'%y-%M-%d',
                    dateFmt:'yyyy-MM-dd HH:mm:ss',isShowClear:false})" id="logmax" value="{{old('date_end')?:date('Y-m-d H:i:s')}}" class="input-text Wdate" style="width:170px;">
                    <input type="text" name="account" value="{{old('account')}}" placeholder="用户名" style="width:200px" class="input-text">
                    <input type="text" name="ip" value="{{old('ip')}}" placeholder="ip" style="width:200px" class="input-text">
                    <button class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> 搜日志</button>
                </div>
                </form>
                <div class="cl pd-5 bg-1 bk-gray mt-20">
                    <span class="l">
                        @if(session('permission') == 0 || in_array("admin/systemLogDestroy",session('permission')))
                        <a href="javascript:;" onclick="dataDel('{{url("admin/systemLogDestroy")}}')" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a>
                        @endif
                    </span>
                    <span class="r">共有数据：<strong>{{count($logs)}}</strong> 条</span>
                </div>
                <table class="table table-border table-bordered table-bg table-sort">
                    <thead>
                        <tr>
                            <th scope="col" colspan="9">系统公告列表</th>
                        </tr>
                        <tr class="text-c">
                            <th width="25"><input type="checkbox"></th>
                            <th width="150">类型</th>
                            <th>内容</th>
                            <th width="150">操作用户</th>
                            <th width="150">客户端IP</th>
                            <th width="150">时间</th>
                            <th width="100">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($logs as $l)
                        <tr class="text-c">
                            <td><input type="checkbox" value="{{$l->id}}" class="checkBox"></td>
                            <td>{{$l->event}}</td>
                            <td><div style="overflow:hidden;height: 60px">{{$l->content}}</div></td>
                            <td>{{$l->account}}</td>
                            <td>{{$l->ip}}</td>
                            <td>{{$l->created_at}}</td>
                            <td class="td-manage">
                                @if(session('permission') == 0 || in_array("admin/systemLogDetails",session('permission')))
                                    <a title="详情" href="javascript:;" onclick="edit('详情','{{url("admin/systemLogDetails")}}','{{$l->id}}','800','500')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe665;</i></a>
                                @endif
                                @if(session('permission') == 0 || in_array("admin/systemLogDestroy",session('permission')))
                                <a title="删除" href="javascript:;" onclick="onesDel(this,'{{url("admin/systemLogDestroy")}}','{{$l->id}}')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
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
<script type="text/javascript" src="{{asset('static/admin/lib/datePicker/WdatePicker.js')}}"></script>
<script type="text/javascript" src="{{asset('static/admin/lib/dataTables/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{asset('static/admin/lib/layerPage/laypage.js')}}"></script>
<script type="text/javascript">

    $('.table-sort').dataTable({
        "aaSorting": [[ 5, "desc" ]],//默认第几个排序
        "bStateSave": true,//状态保存
        "aoColumnDefs": [
            //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
            {"orderable":false,"aTargets":[0,2,4]}// 制定列不参与排序
        ]
    });


</script>
@endsection
