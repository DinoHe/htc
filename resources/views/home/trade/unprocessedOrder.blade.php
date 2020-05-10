@extends('home.trade.trade')
@section('trade-tittle')交易中心 @endsection
@section('trade-container')

    <div class="app-cells">
        <div class="weui-panel weui-panel_access">
            <div class="weui-cells color-white">
                @if(!is_null($unOrders))
                    @foreach($unOrders as $uo)
                    <a href="{{url('home/orderPreview').'/'.$uo->id}}" class="weui-cell border-radius bg-order app-fs-13">
                        <div class="weui-cell__bd">
                            <h2>{{$uo->buy_member_id == \Illuminate\Support\Facades\Auth::id()?'买入':'卖出'}}</h2>
                            <p>数量：{{$uo->trade_number}}</p>
                            <p>单价：${{$uo->trade_price}}</p>
                            <p>日期：{{$uo->updated_at}}</p>
                        </div>
                        <div class="weui-cell__ft color-danger">
                            {{$uo->trade_status == \App\Http\Models\Orders::TRADE_NO_PAY?'待支付':'待确认'}}
                        </div>
                    </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection

@section('trade-js')
    <script>
        $(function () {
            $('.trade-tittle_un').addClass('trade-select').siblings().removeClass('trade-select');
        });
    </script>
@endsection
