@extends('layout.admin-master')
@section('tittle')团队列表 @endsection

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
                <form action="{{url('admin/memberTeam')}}" method="post">
                    @csrf
                <div class="text-c">
                    <input type="text" name="account" value="{{old('account')}}" placeholder="会员账号" style="width:200px" class="input-text">
                    <button class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> 查找</button>
                </div>
                </form>
                <div class="cl pd-5 bg-1 bk-gray mt-20">
                    <span class="l">直推人数：<strong>{{count($teams)}}</strong> </span>
                </div>
                <table class="table table-border table-bordered table-bg table-sort">
                    <thead>
                        <tr>
                            <th scope="col" colspan="12">直推团队列表</th>
                        </tr>
                        <tr class="text-c">
                            <th width="25"><input type="checkbox"></th>
                            <th>账号</th>
                            <th>等级</th>
                            <th>团队人数</th>
                            <th>注册时间</th>
                            <th>状态</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(!empty($teams))
                    @foreach($teams as $t)
                        <tr class="text-c">
                            <td><input type="checkbox" value="{{$t->id}}"></td>
                            <td>{{$t->phone}}</td>
                            <td>{{$t->level->level_name}}</td>
                            <td>{{$t->team_total}}</td>
                            <td>{{$t->created_at}}</td>
                            <td class="td-status">
                                <span class="label radius {{$t->activated==\App\Http\Models\Members::ACTIVATED?'label-success':''}}">{{$t->getAccountStatus($t->activated)}}</span>
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
            {"orderable":false,"aTargets":[0]}// 制定列不参与排序
        ]
    });

</script>
@endsection
