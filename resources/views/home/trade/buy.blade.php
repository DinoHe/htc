@extends('home.trade.trade')
@section('trade-tittle')交易中心 @endsection
@section('trade-container')
    <div class="app-cells">
        <div class="weui-cells">
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">今日单价</label></div>
                <div class="weui-cell__bd app-fs-19 color-main bold">$<span id="price">{{isset($coinPrice)?$coinPrice:0}}</span>
                    <span class="app-fs-13 color-primary">≈￥{{isset($coinPrice)?$coinPrice*7:0}}</span></div>
            </div>
            <div class="weui-cell weui-cell_active weui-cell_access weui-cell_select weui-cell_select-after">
                <div class="weui-cell__hd"><label class="weui-label">购买数量</label></div>
                <div class="weui-cell__bd" id="buy-number">5</div>
            </div>
            <div class="weui-cell trade-bs">
                <div><a href="javascript:tradeBuy();" class="weui-btn trade-buy">买入</a></div>
                <div><a href="javascript:tradeSales();" class="weui-btn trade-sales">卖出</a></div>
            </div>
        </div>

        <div class="weui-cells__title color-main">委托买入单</div>
        <div class="weui-cells color-white">
            @if(!empty($buyOrders))
                @foreach($buyOrders as $b)
                <div class="weui-cell border-radius bg-order app-fs-13">
                    <div class="weui-cell__bd">
                        <h2>买入 <a href="javascript:cancelOrder('{{$b['order_id']}}');" class="app-btn_cancel">取消</a></h2>
                        <p>数量：{{$b['trade_number']}}</p>
                        <p>单价：${{$b['trade_price']}}</p>
                        <p>日期：{{$b['created_at']}}</p>
                    </div>
                    <div class="weui-cell__ft color-success">排队中</div>
                </div>
                @endforeach
            @else
                <div class="weui-cell border-radius bg-order app-fs-13">无买单</div>
            @endif
        </div>

        <div class="weui-cells__title color-main">委托卖出单</div>
        <div class="weui-cells color-white">
            @if(!empty($salesOrders))
                @foreach($salesOrders as $s)
                <div class="weui-cell border-radius bg-order app-fs-13">
                    <div class="weui-cell__bd">
                        <h2>卖出 <a href="javascript:cancelOrder('{{$s['order_id']}}');" class="app-btn_cancel">取消</a></h2>
                        <p>数量：{{$s['trade_number']}}</p>
                        <p>单价：${{$s['trade_price']}}</p>
                        <p>日期：{{$s['created_at']}}</p>
                    </div>
                    <div class="weui-cell__ft color-success">排队中</div>
                </div>
                @endforeach
            @else
                <div class="weui-cell border-radius bg-order app-fs-13">无卖单</div>
            @endif
        </div>
    </div>
@endsection

@section('trade-js')
    <script type="text/javascript">
        // 买入
        function tradeBuy() {
            $.confirm('买入提示','确定买入吗？',function () {
                $.loading();
                $.ajax({
                    method: 'post',
                    url: '{{url("home/tradeBuy")}}',
                    data: {'buyNumber':$('#buy-number').text(),'price':$('#price').text()},
                    success: function (data) {
                        $.hideLoading();
                        if (data.status == 0){
                            $.toast('已委托买入');
                            setTimeout(function () {
                                location.reload();
                            },2000);
                        }else {
                            $.alert(data.message);
                        }
                    },
                    error: function (error) {
                        $.hideLoading();
                        // console.log(error);
                        $.topTip('系统错误');
                    },
                    dataType: 'json'
                });
            });
        }

        // 卖出
        function tradeSales() {
            $.confirm('卖出提示','确定卖出吗？',function () {
                $.loading();
                $.ajax({
                    method: 'post',
                    url: '{{url("home/tradeSales")}}',
                    data: {'salesNumber':$('#buy-number').text(),'price':$('#price').text()},
                    success: function (data) {
                        $.hideLoading();
                        if (data.status == 0){
                            $.toast('已委托卖出');
                            setTimeout(function () {
                                location.reload();
                            },2000);
                        }else {
                            $.alert(data.message);
                        }
                    },
                    error: function (error) {
                        $.hideLoading();
                        // console.log(error);
                        $.topTip('系统错误');
                    },
                    dataType: 'json'
                });
            });
        }

        //取消单
        function cancelOrder(orderId) {
            $.confirm('取消提示','确定要取消吗？',function () {
                $.loading('正在取消');
                $.ajax({
                    method: 'get',
                    url: '{{url("home/cancelOrder")}}/'+orderId,
                    dataType: 'json',
                    success: function (data) {
                        $.hideLoading();
                        if (data.status == 0){
                            $.toast('取消成功');
                            setTimeout(function () {
                                location.reload();
                            },2000);
                        }else {
                            $.alert(data.message);
                        }
                    },
                    error: function (error) {
                        $.hideLoading();
                        $.topTip('系统错误');
                    }
                });
            });
        }

        //选择器
        var labelArry=[],
            numbers = JSON.parse('{{json_encode($tradeNumbers)}}');
        numbers.forEach(function (v,k) {
            var label = {
                    label:v,
                    value:k,
                    default:true
                }
            labelArry.push(label);
        });
        $('#buy-number').on('click', function () {
            weui.picker(labelArry,
                {
                onChange: function (result) {
                    // $('#buy-number').text(result[0].label);
                },
                onConfirm: function (result) {
                    $('#buy-number').text(result[0].label);
                }
            });
        });

    </script>
@endsection
