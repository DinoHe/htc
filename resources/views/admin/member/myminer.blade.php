@extends('layout.admin-master')
@section('tittle')会员矿机列表 @endsection

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
                <form action="{{url('admin/memberMiner')}}" method="post">
                    @csrf
                <div class="text-c">
                    <span class="select-box inline">
                        <select name="minerType" class="select">
                            <option value="-1">所有类型</option>
                            @foreach($miners as $miner)
                            <option value="{{$miner->id}}" {{old('minerType')==$miner->id?'selected':''}}>{{$miner->tittle}}</option>
                            @endforeach
                        </select>
                    </span>
                    <input type="text" name="account" value="{{old('account')}}" placeholder="会员账号" style="width:200px" class="input-text">
                    <button class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> 查找</button>
                </div>
                </form>
                <div class="cl pd-5 bg-1 bk-gray mt-20">
                    @if(session('permission') == 0 || in_array("admin/memberMinerDel",session('permission')))
                        <a href="javascript:;" onclick="dataDel('{{url("admin/memberMinerDel")}}')" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a>
                    @endif
                    @if(session('permission') == 0 || in_array("admin/memberMinerAdd",session('permission')))
                        <a href="javascript:;" onclick="add('赠送矿机','{{url("admin/memberMinerAdd")}}','800','300')" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i>赠送矿机</a>
                    @endif
                    <span class="r">共有数据：<strong>{{count($myminers)}}</strong> 条</span>
                </div>
                <table class="table table-border table-bordered table-bg table-sort">
                    <thead>
                        <tr>
                            <th scope="col" colspan="12">实名认证列表</th>
                        </tr>
                        <tr class="text-c">
                            <th width="25"><input type="checkbox"></th>
                            <th width="80">账号</th>
                            <th>矿机类型</th>
                            <th>算力</th>
                            <th>总产量</th>
                            <th>运行周期</th>
                            <th>每小时产量</th>
                            <th>已挖</th>
                            <th>待收取</th>
                            <th>日期</th>
                            <th width="50">状态</th>
                            <th width="80">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(!$myminers->isEmpty())
                    @foreach($myminers as $mm)
                        <tr class="text-c">
                            <td><input type="checkbox" value="{{$mm->id}}" class="checkBox"></td>
                            <td class="table_content">{{$mm->member->phone}}</td>
                            <td class="table_content">{{$mm->miner_tittle}}</td>
                            <td class="table_content">{{$mm->hashrate}}</td>
                            <td class="table_content">{{$mm->total_dig}}</td>
                            <td class="table_content">{{$mm->runtime}}</td>
                            <td class="table_content">{{$mm->nph}}</td>
                            <td class="table_content">{{$mm->dug}}</td>
                            <td>{{$mm->no_collect}}</td>
                            <td>{{$mm->created_at}}</td>
                            <td class="td-status">
                                <span class="label radius {{$mm->run_status==\App\Http\Models\MyMiners::RUNNING?'label-success':''}}">{{$mm->getMinerStatus($mm->run_status)}}</span>
                            </td>
                            <td class="f-14 td-manage">
                                @if(session('permission') == 0 || in_array("admin/memberMinerEdit",session('permission')))
                                    @if($mm->run_status == \App\Http\Models\MyMiners::RUNNING)
                                    <a style="text-decoration:none" onClick="miner_stop(this,'{{$mm->id}}')" href="javascript:;" title="结束"><i class="Hui-iconfont">&#xe631;</i></a>
                                    @endif
                                    <a title="编辑" href="javascript:;" onclick="edit('编辑','{{url("admin/memberMinerEdit")}}','{{$mm->id}}','800','600')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
                                @endif
                                @if(session('permission') == 0 || in_array("admin/memberMinerDel",session('permission')))
                                    <a title="删除" href="javascript:;" onclick="onesDel(this,'{{url("admin/memberMinerDel")}}','{{$mm->id}}')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
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
<script type="text/javascript" src="{{asset('static/admin/lib/dataTables/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{asset('static/admin/lib/layerPage/laypage.js')}}"></script>
<script type="text/javascript">

    $('.table-sort').dataTable({
        "aaSorting": [[ 1, "asc" ]],//默认第几个排序
        "bStateSave": true,//状态保存
        "aoColumnDefs": [
            //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
            {"orderable":false,"aTargets":[0,11]}// 制定列不参与排序
        ]
    });

    function miner_stop(obj,id){
        layer.confirm('确认要结束吗？',function(index){
            //此处请求后台程序，下方是成功后的前台处理……
            $.get('{{url("admin/memberMinerStop")}}/'+id,function () {
                $(obj).parents("tr").find(".td-status").html('<span class="label radius">已结束</span>');
                $(obj).remove();
                layer.msg('已结束运行!',{icon: 1,time:1000});
            });
        });
    }

</script>
@endsection
