@extends('home.trade.trade')

@section('trade-container')

    <div class="app-cells">
        <div class="weui-panel weui-panel_access">
            <div class="weui-cells color-white">
                <a href="{{url('home/orderPreview').'/1'}}" class="weui-cell border-radius bg-order app-fs-13">
                    <div class="weui-cell__bd">
                        <h2>买入</h2>
                        <p>数量：10</p>
                        <p>单价：$5</p>
                        <p>日期：2020-04-27</p>
                    </div>
                    <div class="weui-cell__ft color-danger">待支付</div>
                </a>
                <a href="javascript:;" class="weui-cell border-radius bg-order app-fs-13">
                    <div class="weui-cell__bd">
                        <h2>买入</h2>
                        <p>数量：10</p>
                        <p>单价：$5</p>
                        <p>日期：2020-04-27</p>
                    </div>
                    <div class="weui-cell__ft color-danger">待确认</div>
                </a>
                <a href="javascript:;" class="weui-cell border-radius bg-order app-fs-13">
                    <div class="weui-cell__bd">
                        <h2>卖出</h2>
                        <p>数量：10</p>
                        <p>单价：$5</p>
                        <p>日期：2020-04-27</p>
                    </div>
                    <div class="weui-cell__ft color-danger">排队中</div>
                </a>
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
