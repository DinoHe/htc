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
            <a class="btn btn-success radius l" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a> </nav>
        <div class="Hui-article">
            <article class="cl pd-20">
                <form action="{{url('admin/memberList')}}" method="post">
                    @csrf
                <div class="text-c">
                    <span class="select-box inline">
                        <select name="activated" class="select">
                            <option value="-1">所有状态</option>
                            <option value="0" {{old('activated')=='0'?'selected':''}}>已激活</option>
                            <option value="1" {{old('activated')=='1'?'selected':''}}>未激活</option>
                            <option value="2" {{old('activated')=='2'?'selected':''}}>临时冻结</option>
                            <option value="3" {{old('activated')=='3'?'selected':''}}>永久冻结</option>
                        </select>
                    </span>
                    <span class="select-box inline">
                        <select name="level" class="select">
                            <option value="0">所有等级</option>
                            <option value="1" {{old('level')=='1'?'selected':''}}>一级会员</option>
                            <option value="2" {{old('level')=='2'?'selected':''}}>二级会员</option>
                            <option value="3" {{old('level')=='3'?'selected':''}}>三级会员</option>
                            <option value="4" {{old('level')=='4'?'selected':''}}>四级会员</option>
                        </select>
                    </span>
                    注册时间：
                    <input type="text" name="date_start" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}',
                    dateFmt:'yyyy-MM-dd HH:mm:ss'})" id="logmin" value="{{old('date_start')}}" class="input-text Wdate" style="width:170px;">
                    -
                    <input type="text" name="date_end" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'logmin\')||\'%y-%M-%d\'}',maxDate:'%y-%M-%d',
                    dateFmt:'yyyy-MM-dd HH:mm:ss'})" id="logmax" value="{{old('date_end')}}" class="input-text Wdate" style="width:170px;">
                    <input type="text" name="account" value="{{old('account')}}" placeholder="会员账号" style="width:200px" class="input-text">
                    <input type="number" name="credit" value="{{old('credit')}}" placeholder="会员信用" style="width:100px" min="0" class="input-text">
                    <button class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> 查找</button>
                </div>
                </form>
                <div class="cl pd-5 bg-1 bk-gray mt-20">
                    <span class="l">
                        @if(session('permission') == 0)
                        <a href="javascript:;" onclick="dataDel('{{url("admin/memberDel")}}')" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a>
                        @endif
                    </span>
                    <span class="r">共有数据：<strong>{{count($members)}}</strong> 条</span>
                </div>
                <table class="table table-border table-bordered table-bg table-sort">
                    <thead>
                        <tr>
                            <th scope="col" colspan="9">会员列表</th>
                        </tr>
                        <tr class="text-c">
                            <th width="25"><input type="checkbox"></th>
                            <th width="150">账号</th>
                            <th>等级</th>
                            <th>信用</th>
                            <th width="150">注册时间</th>
                            <th width="100">状态</th>
                            <th width="100">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(!$members->isEmpty())
                    @foreach($members as $m)
                        <tr class="text-c">
                            <td><input type="checkbox" value="{{$m->id}}" class="checkBox"></td>
                            <td>{{$m->phone}}</td>
                            <td>{{$m->levelName}}</td>
                            <td>{{$m->credit}}</td>
                            <td>{{$m->created_at}}</td>
                            <td><div class="label radius {{$m->status=='已激活'?'label-success':''}}">{{$m->status}}</div></td>
                            <td class="td-manage">
                                @if(session('permission') == 0 || in_array("admin/memberEdit",session('permission')))
                                    <a title="编辑" href="javascript:;" onclick="edit('编辑','{{url("admin/memberEdit")}}','{{$m->id}}','800','500')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
                                @endif
                                @if(session('permission') == 0)
                                <a title="删除" href="javascript:;" onclick="onesDel(this,'{{url("admin/memberDel")}}','{{$m->id}}')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
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
            {"orderable":false,"aTargets":[0,6]}// 制定列不参与排序
        ]
    });


</script>
@endsection
