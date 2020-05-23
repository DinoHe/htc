@extends('layout.admin-master')
@section('tittle')等级列表 @endsection

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
                        @if(session('permission') == 0 || in_array("admin/memberLevelAdd",session('permission')))
                        <a href="javascript:;" onclick="add('添加等级','{{url("admin/memberLevelAdd")}}','800','300')" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i>添加等级</a>
                        @endif
                    </span>
                    <span class="r">共有数据：<strong>{{count($levels)}}</strong> 条</span>
                </div>
                <table class="table table-border table-bordered table-bg">
                    <thead>
                        <tr>
                            <th scope="col" colspan="9">等级列表</th>
                        </tr>
                        <tr class="text-c">
                            <th width="25"></th>
                            <th>等级名称</th>
                            <th>每天卖币次数</th>
                            <th width="100">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($levels as $l)
                        <tr class="text-c">
                            <td><input type="checkbox"></td>
                            <td>{{$l->level_name}}</td>
                            <td>{{$l->sales_times}}</td>
                            <td class="td-manage">
                                @if(session('permission') == 0 || in_array("admin/memberLevelEdit",session('permission')))
                                    <a title="编辑" href="javascript:;" onclick="edit('等级编辑','{{url("admin/memberLevelEdit")}}','{{$l->id}}','800','300')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
                                @endif
                                @if(session('permission') == 0 || in_array("admin/memberLevelDel",session('permission')))
                                <a title="删除" href="javascript:;" onclick="onesDel(this,'{{url("admin/memberLevelDel")}}','{{$l->id}}')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
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
<script type="text/javascript" src="{{asset('static/admin/lib/dataTables/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{asset('static/admin/lib/layerPage/laypage.js')}}"></script>

@endsection
