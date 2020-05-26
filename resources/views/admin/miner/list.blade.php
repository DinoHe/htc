@extends('layout.admin-master')
@section('tittle')矿机商城列表 @endsection

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
                        @if(session('permission') == 0 || in_array("admin/minerAdd",session('permission')))
                            <a href="javascript:;" onclick="add('添加矿机','{{url("admin/minerAdd")}}','800','500')" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i>添加矿机</a>
                        @endif
                    </span>
                    <span class="r">共有数据：<strong>{{count($miners)}}</strong> 条</span>
                </div>
                <table class="table table-border table-bordered table-bg">
                    <thead>
                        <tr class="text-c">
                            <th width="25"></th>
                            <th>矿机类型</th>
                            <th>价格(HTC)</th>
                            <th>算力(G)</th>
                            <th>总产量(HTC)</th>
                            <th>运行周期(H)</th>
                            <th>每小时产量(HTC/H)</th>
                            <th width="80">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(!empty($miners))
                    @foreach($miners as $m)
                        <tr class="text-c">
                            <td><input type="checkbox"></td>
                            <td>{{$m->tittle}}</td>
                            <td>{{$m->coin_number}}</td>
                            <td>{{$m->hashrate}}</td>
                            <td>{{$m->total_dig}}</td>
                            <td>{{$m->runtime}}</td>
                            <td>{{$m->nph}}</td>
                            <td class="td-manage">
                                @if(session('permission') == 0 || in_array("admin/minerEdit",session('permission')))
                                    <a title="编辑" href="javascript:;" onclick="edit('矿机编辑','{{url("admin/minerEdit")}}','{{$m->id}}','800','500')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
                                @endif
                                @if(session('permission') == 0 || in_array("admin/minerDel",session('permission')))
                                    <a title="删除" href="javascript:;" onclick="onesDel(this,'{{url("admin/minerDel")}}','{{$m->id}}')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
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

</script>
@endsection
