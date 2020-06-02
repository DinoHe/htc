@extends('layout.admin-master')
@section('tittle')订单列表 @endsection
@section('css')
    <style>
        .preview{position: relative}
        .preview div{width: 300px;height:330px;position: absolute;left: -305px;top: -45px;z-index: 9}
        .preview div img{width: 100%;height: 100%}
    </style>
@endsection

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
                <form action="{{url('admin/tradeOrder')}}" method="post">
                    @csrf
                <div class="text-c">
                    <span class="select-box inline">
                        <select name="tradeStatus" class="select">
                            <option value="-1">所有状态</option>
                            <option value="0" {{old('tradeStatus')=='0'?'selected':''}}>待支付</option>
                            <option value="1" {{old('tradeStatus')=='1'?'selected':''}}>待确认</option>
                            <option value="2" {{old('tradeStatus')=='2'?'selected':''}}>交易完成</option>
                        </select>
                    </span>
                    创建日期：
                    <input type="text" name="date_start" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}',
                    dateFmt:'yyyy-MM-dd HH:mm:ss'})" id="logmin" value="{{old('date_start')?:date('Y-m-d 00:00:00')}}" class="input-text Wdate" style="width:170px;">
                    -
                    <input type="text" name="date_end" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'logmin\')||\'%y-%M-%d\'}',maxDate:'%y-%M-%d',
                    dateFmt:'yyyy-MM-dd HH:mm:ss'})" id="logmax" value="{{old('date_end')?:date('Y-m-d H:i:s')}}" class="input-text Wdate" style="width:170px;">
                    <input type="text" name="account" value="{{old('account')}}" placeholder="会员账号" style="width:200px" class="input-text">
                    <button class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> 查找</button>
                </div>
                </form>
                <div class="cl pd-5 bg-1 bk-gray mt-20">
                    <span class="l">
                        @if(session('permission') == 0 || in_array("admin/tradeOrderDel",session('permission')))
                        <a href="javascript:;" onclick="dataDel('{{url("admin/tradeOrderDel")}}')" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a>
                        @endif
                    </span>
                    <span class="r">共有数据：<strong>{{count($orders)}}</strong> 条</span>
                </div>
                <table class="table table-border table-bordered table-bg table-sort">
                    <thead>
                        <tr class="text-c">
                            <th width="25"><input type="checkbox"></th>
                            <th>订单号</th>
                            <th>买家</th>
                            <th>卖家</th>
                            <th>数量(HTC)</th>
                            <th>价格($)</th>
                            <th>总额($)</th>
                            <th width="130">截图</th>
                            <th width="130">日期</th>
                            <th>剩余时间</th>
                            <th width="80">状态</th>
                            <th width="80">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(!$orders->isEmpty())
                    @foreach($orders as $o)
                        <tr class="text-c">
                            <td><input type="checkbox" value="{{$o->id}}" class="checkBox"></td>
                            <td class="table_content">{{$o->order_id}}</td>
                            <td class="table_content">{{$o->buy_member_phone}}</td>
                            <td class="table_content">{{$o->sales_member_phone}}</td>
                            <td class="table_content">{{$o->trade_number}}</td>
                            <td class="table_content">{{$o->trade_price}}</td>
                            <td class="table_content">{{$o->trade_total_money}}</td>
                            <td>
                                <a href="javascript:;" class="preview" data-src="{{asset('storage').$o->payment_img}}">
                                <img src="{{asset('storage').$o->payment_img}}" width="100"></a>
                            </td>
                            <td class="table_content">{{$o->created_at}}</td>
                            <td class="table_content">
                                {{$o->trade_status == \App\Http\Models\Orders::TRADE_NO_PAY || $o->trade_status == \App\Http\Models\Orders::TRADE_NO_CONFIRM?implode(':',$o->remainingTime($o->updated_at)):'0:0:0'}}</td>
                            <td class="table_content"><div class="label radius {{$o->trade_status==2?'label-success':'label-danger'}}">{{$o->getTradeStatus($o->trade_status)}}</div></td>
                            <td class="td-manage">
                                @if((session('permission') == 0 || in_array("admin/tradeOrderCancelEdit",session('permission'))) && $o->trade_status < 2)
                                    <a href="javascript:;" onclick="cancelOrder('{{url("admin/tradeOrderCancelEdit")}}','{{$o->id}}','{{$o->buy_member_id}}')" class="pd-5 radius btn-warning" style="text-decoration:none">取消交易</a>
                                @endif
                                @if(session('permission') == 0 || in_array("admin/tradeOrderDel",session('permission')))
                                <a title="删除" href="javascript:;" onclick="onesDel(this,'{{url("admin/tradeOrderDel")}}','{{$o->id}}')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
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
            {"orderable":false,"aTargets":[0,10]}// 制定列不参与排序
        ]
    });

    function cancelOrder(url,orderId,memberId) {
        layer.confirm('取消订单并冻结买家账号，是否操作？',function () {
            $.post(url,{'orderId':orderId,'memberId':memberId});
            layer.msg('操作成功',{icon:1,time:1000});
            refresh();
        });
    }

    var preview = $('.preview');
    preview.on('mouseover',function () {
        $(this).append('<div><img src="'+$(this).attr("data-src")+'"></div>')
    });
    preview.on('mouseout',function () {
        $(this).find('div').remove();
    });

</script>
@endsection
