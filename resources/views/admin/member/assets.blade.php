@extends('layout.admin-master')
@section('tittle')资产列表 @endsection

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
                <form action="{{url('admin/memberAssets')}}" method="post">
                    @csrf
                <div class="text-c">
                    <input type="text" name="account" value="{{old('account')}}" placeholder="会员账号" style="width:200px" class="input-text">
                    <input type="number" name="balanceMin" value="{{old('balanceMin')}}" placeholder="余额最小值" style="width:130px" class="input-text">
                    <input type="number" name="buyMin" value="{{old('buyMin')}}" placeholder="购币最小值" style="width:130px" class="input-text">
                    <button class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> 查找</button>
                </div>
                </form>
                <div class="cl pd-5 bg-1 bk-gray mt-20">
                    <span class="l">
                        <a href="javascript:;" onclick="assets_sum(this)" class="btn btn-danger radius">统计</a>
                        <span class="ml-10 c-orange"></span>
                    </span>
                    <span class="r">共有数据：<strong>{{count($assets)}}</strong> 条</span>
                </div>
                <table class="table table-border table-bordered table-bg table-sort">
                    <thead>
                        <tr>
                            <th scope="col" colspan="12">实名认证列表</th>
                        </tr>
                        <tr class="text-c">
                            <th width="25"><input type="checkbox"></th>
                            <th width="150">账号</th>
                            <th>余额</th>
                            <th>冻结资产</th>
                            <th>累积奖励</th>
                            <th>累积购买（HTC）</th>
                            <th width="150">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(!$assets->isEmpty())
                    @foreach($assets as $a)
                        <tr class="text-c">
                            <td><input type="checkbox" value="{{$a->id}}" class="checkBox"></td>
                            <td>{{$a->member->phone}}</td>
                            <td class="balance">{{$a->balance}}</td>
                            <td>{{$a->blocked_assets}}</td>
                            <td>{{$a->rewards}}</td>
                            <td>{{$a->buys}}</td>
                            <td class="td-manage">
                                @if(session('permission') == 0 || in_array("admin/memberAssetsRechargeEdit",session('permission')))
                                    <a href="javascript:;" onclick="edit('充值','{{url("admin/memberAssetsRechargeEdit")}}','{{$a->id}}','800','400')" class="ml-5 btn-success pd-5 radius" style="text-decoration:none">充值</a>
                                @endif
                                @if(session('permission') == 0 || in_array("admin/memberAssetsBlockedEdit",session('permission')))
                                    <a href="javascript:;" onclick="assets_block(this,'{{url("admin/memberAssetsBlockEdit")}}','{{$a->id}}')" class="ml-5 btn-danger pd-5 radius" style="text-decoration:none">冻结</a>
                                @endif
                                @if(session('permission') == 0 || in_array("admin/memberAssetsDel",session('permission')))
                                    <a title="删除" href="javascript:;" onclick="onesDel(this,'{{url("admin/memberAssetsDel")}}','{{$a->id}}')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
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
            {"orderable":false,"aTargets":[0,6]}// 制定列不参与排序
        ]
    });

    function assets_sum(obj) {
        $.ajax({
            method:'get',
            url:'{{url("admin/memberAssetsSum")}}',
            data:{'account':$('input[name="account"]').val(),'balanceMin':$('input[name="balanceMin"]').val(),
                'buyMin':$('input[name="buyMin"]').val()},
            dataType:'json',
            success:function (data) {
                if (data.status == 0){
                    $(obj).siblings().empty();
                    $(obj).siblings().text('余额总计：'+data.balanceSum+'， 冻结总计：'+data.blockedSum+'， 奖励总计：'+data.rewardSum+'， 购买总计：'+data.buySum);
                }
            }
        });
    }

    function assets_block(obj,url,id) {
        layer.prompt({title:'冻结HTC'},function (n) {
            if ($(obj).parent().siblings('.balance').text() < n){
                layer.msg('冻结数量超过了用户余额',{icon:2,time:1000});
                layer_close();
                return false;
            }
            $.post(url,{'id':id,'blockNumber':n});
            layer.msg('操作成功',{icon:1,time:1000});
            refresh();
        });
    }

</script>
@endsection
