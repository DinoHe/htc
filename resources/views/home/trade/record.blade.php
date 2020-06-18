@extends('home.trade.trade')
@section('trade-tittle')交易中心 @endsection
@section('trade-container')

    <div class="app-cells">
        <div class="weui-panel weui-panel_access">
            <div class="weui-cells color-white">
                @if(count($orders) > 0)
                    @foreach($orders as $order)
                    <div class="weui-cell border-radius bg-order app-fs-13">
                        <div class="weui-cell__bd">
                            <h2>{{$order->buy_member_id==\Illuminate\Support\Facades\Auth::id()?'买入':'卖出'}}</h2>
                            <p>数量：{{$order->trade_number}}</p>
                            <p>单价：${{$order->trade_price}}</p>
                            <p>日期：{{$order->created_at}}</p>
                        </div>
                        <div class="weui-cell__ft color-danger">{{$order->getTradeStatus($order->trade_status)}}</div>
                    </div>
                    @endforeach
                @endif
            </div>

        </div>
    </div>
@endsection

@section('trade-js')
    <script>
        $(function () {
            $('.trade-tittle_record').addClass('trade-select').siblings().removeClass('trade-select');
        })
    </script>
@endsection
