@extends('home.trade.trade')

@section('trade-container')

    <div class="app-cells">
        <div class="weui-panel weui-panel_access">
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">购买价格</label></div>
                    <div class="weui-cell__bd app-fs-19 color-main bold">$5 <span class="app-fs-13 color-primary">≈￥35</span></div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label for="buy-number" class="weui-label">购买数量</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" id="buy-number" type="text" value="5" readonly="" data-values="001">
                    </div>
                </div>
                <div class="weui-cell trade-bs">
                    <div><a href="javascript:void(0);" class="weui-btn trade-buy">买入</a></div>
                    <div><a href="javascript:void(0);" class="weui-btn trade-sales">卖出</a></div>
                </div>
            </div>

            <div class="weui-cell color-white">
                <a href="{{url('home/tradeCenter')}}" class="weui-btn app-submit">交易行情 <i class="iconfont icon-hangqing1"></i></a>
            </div>

            <div class="weui-cells__title color-main">委托买入单</div>
            <div class="weui-cells color-white">
                <div class="weui-cell border-radius bg-order app-fs-13">
                    <div class="weui-cell__bd">
                        <h2>买入</h2>
                        <p>数量：10</p>
                        <p>单价：$5</p>
                        <p>日期：2020-04-27</p>
                    </div>
                    <div class="weui-cell__ft color-success">排队中</div>
                </div>
                <div class="weui-cell border-radius bg-order app-fs-13">
                    <div class="weui-cell__bd">
                        <h2>买入</h2>
                        <p>数量：10</p>
                        <p>单价：$5</p>
                        <p>日期：2020-04-27</p>
                    </div>
                    <div class="weui-cell__ft color-success">排队中</div>
                </div>
            </div>

            <div class="weui-cells__title color-main">委托卖出单</div>
            <div class="weui-cells color-white">
                <div class="weui-cell border-radius bg-order app-fs-13">
                    <div class="weui-cell__bd">
                        <h2>卖出</h2>
                        <p>数量：10</p>
                        <p>单价：$5</p>
                        <p>日期：2020-04-27</p>
                    </div>
                    <div class="weui-cell__ft color-success">排队中</div>
                </div>
                <div class="weui-cell border-radius bg-order app-fs-13">
                    <div class="weui-cell__bd">
                        <h2>卖出</h2>
                        <p>数量：10</p>
                        <p>单价：$5</p>
                        <p>日期：2020-04-27</p>
                    </div>
                    <div class="weui-cell__ft color-success">排队中</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('trade-js')
    <script>

        $("#buy-number").select({
            title: "选择购买数量",
            items: [
                {
                    title: "5",
                    value: "001",
                },
                {
                    title: "10",
                    value: "002",
                },
                {
                    title: "20",
                    value: "003",
                },
                {
                    title: "50",
                    value: "004",
                }

            ]
        });


    </script>
@endsection
