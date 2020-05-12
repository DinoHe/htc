@extends('home.trade.trade')
@section('trade-tittle')交易中心 @endsection
@section('trade-container')

    <div class="app-cells">
        <div class="weui-cells">
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">今日价格</label></div>
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

        <div class="weui-cell color-white">
            <a href="{{url('home/tradeCenter')}}" class="weui-btn app-submit">交易行情 <i class="iconfont icon-hangqing1"></i></a>
        </div>

        <div class="weui-cells__title color-main">委托买入单</div>
        <div class="weui-cells color-white">
            @if(!empty($buyOrders))
                @foreach($buyOrders as $b)
                <div class="weui-cell border-radius bg-order app-fs-13">
                    <div class="weui-cell__bd">
                        <h2>买入</h2>
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
                        <h2>卖出</h2>
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
        //交易时间段限制
        var auth = '{{isset($realNameAuth)?$realNameAuth:""}}',e = '{{$trade}}',c = '{{session('safeP')}}';
        if (auth != ''){
            $.alert(auth,'{{url('home/member')}}');
        }else if (e != 'on'){
            $.alert(e,'{{url('home/index')}}');
        }else if (c == '') {
            safeCheck();
        }

        //验证交易密码
        function safeCheck() {
            var content = '<p><input type="password" placeholder="请输入安全密码" name="safePassword"></p>' +
                '<i class="color-error app-fs-13" style="position: absolute;left: 80px"></i>'
            $.confirm('安全验证',content,function () {
                var $password = $('input[name="safePassword"]'),flag = true;
                $.ajax({
                    method: 'post',
                    url: '{{url("home/tradeCheck")}}',
                    data: {'password':$password.val()},
                    dataType: 'json',
                    async: false,
                    success: function (data) {
                        if (data.status != 0){
                            $password.parent('p').siblings('i').text(data.message);
                            flag = false;
                        }
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
                return flag;
            },document.referrer);
        }

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
                            $.toast('买入成功');
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
                            $.toast('卖出成功');
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
