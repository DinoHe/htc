@extends('layout.admin-master')
@section('tittle')活动列表 @endsection

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
                    @if(session('permission') == 0 || in_array("admin/memberActivityAdd",session('permission')))
                        <a href="javascript:;" onclick="add('添加活动','{{url("admin/memberActivityAdd")}}','800','300')" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i>添加活动</a>
                    @endif
                    <span class="r">共有数据：<strong>{{count($activities)}}</strong> 条</span>
                </div>
                <table class="table table-border table-bordered table-bg table-sort">
                    <thead>
                        <tr>
                            <th scope="col" colspan="12">活动列表</th>
                        </tr>
                        <tr class="text-c">
                            <th width="25"></th>
                            <th>购买数量</th>
                            <th>奖励上级矿机类型</th>
                            <th>奖励上级矿机数量</th>
                            <th>已获得奖励的会员</th>
                            <th width="80">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(!$activities->isEmpty())
                    @foreach($activities as $a)
                        <tr class="text-c">
                            <td><input type="checkbox" value="{{$a->id}}"></td>
                            <td>{{$a->buy_number}}</td>
                            <td>{{$a->miner->tittle}}</td>
                            <td>{{$a->reward_leader_miner_number}}</td>
                            <td>{{$a->rewardMembers}}</td>
                            <td class="td-manage">
                                @if(session('permission') == 0 || in_array("admin/memberActivityEdit",session('permission')))
                                    <a title="编辑" href="javascript:;" onclick="edit('活动编辑','{{url("admin/memberActivityEdit")}}','{{$a->id}}','800','500')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
                                @endif
                                @if(session('permission') == 0 || in_array("admin/memberActivityDel",session('permission')))
                                    <a title="删除" href="javascript:;" onclick="onesDel(this,'{{url("admin/memberActivityDel")}}','{{$a->id}}')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
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
            {"orderable":false,"aTargets":[0,5]}// 制定列不参与排序
        ]
    });

</script>
@endsection
