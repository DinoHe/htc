@extends('layout.master')
@section('tittle')交易行情 @endsection
@section('header')
    @component('layout.header')@endcomponent
@endsection
@section('container')

    <div class="app-cells color-white">
        <div class="weui-panel weui-panel_access">
            <div class="padding15"><div id="container" style="height: 300px;width: 100%"></div></div>
            <div class="weui-cells">
                <div class="weui-cells__title">排单</div>
                <div class="weui-cell">
                    <div class="weui-cell__bd paidan">
                        @foreach($tradeNumber as $n)
                        <a href="javascript:paidan({{$n->number}});">{{$n->number}}</a>
                        @endforeach
                    </div>
                </div>
                <div id="buy_content">
                @if(!empty($buyOrders))
                    @foreach($buyOrders as $o)
                    <div class="weui-cell border-radius bg-order app-fs-13">
                        <div class="weui-cell__bd">
                            <h2>买入</h2>
                            <p>数量：{{$o['trade_number']}}</p>
                            <p>单价：${{$o['trade_price']}}</p>
                            <p>日期：{{$o['created_at']}}</p>
                        </div>
                        <div class="weui-cell__ft color-success">待匹配</div>
                    </div>
                    @endforeach
                @else
                    <div class="weui-cell border-radius bg-order app-fs-13">
                        <div class="weui-cell__bd">无买单</div>
                    </div>
                @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{asset('ext/echarts/echarts-line.min.js')}}"></script>
    <script>
        $(function () {
            showHeaderBack();
        });

        var $p = $('.paidan a');
        $p.eq(0).addClass('paidan-bg');
        $p.click(function () {
            $(this).addClass('paidan-bg').siblings().removeClass('paidan-bg');
        });

        var $buyContent = $('#buy_content');
        function paidan(n) {
            $.loading();
            $.ajax({
                method: 'get',
                url: '{{url("home/paidan")}}/'+n,
                dataType: 'json',
                success: function (data) {
                    $.hideLoading();
                    $buyContent.empty();
                    if (data.status == 0){
                        $buyContent.append('<div class="weui-cell border-radius bg-order app-fs-13">\n' +
                            '                        <div class="weui-cell__bd">无买单</div>\n' +
                            '                    </div>');
                    }else {
                        data.orders.forEach(function (v,k) {
                        var content = '<div class="weui-cell border-radius bg-order app-fs-13">\n' +
                            '                        <div class="weui-cell__bd">\n' +
                            '                            <h2>买入</h2>\n' +
                            '                            <p>数量：' + v['trade_number'] + '</p>\n' +
                            '                            <p>单价：' + v['trade_price'] + '</p>\n' +
                            '                            <p>日期：' + v['created_at'] + '</p>\n' +
                            '                        </div>\n' +
                            '                        <div class="weui-cell__ft color-success">待匹配</div>\n' +
                            '                    </div>';
                            $buyContent.append(content);
                        });
                    }
                },
                error: function (error) {
                    $.hideLoading();
                    $.topTip('系统错误');
                }
            })
        }

        //价格走势图
        var dom = document.getElementById("container");
        var myChart = echarts.init(dom);
        var x_data = getDay7(),
            y_data = '{{$coinPrice}}'.split(',');

        var option = {
            title: {
                text: '实时价格：'+y_data[y_data.length-1],
                left: '30%'
            },
            tooltip: {
                trigger: 'axis'
            },
            grid: {
                left: '10%',
                right: '5%',
                // bottom: '3%',
                // containLabel: true
            },
            toolbox: {
                feature: {
                    saveAsImage: {}
                }
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: x_data
            },
            yAxis: {
                type: 'value'
            },
            series: [

                {
                    name:'',
                    type:'line',
                    stack: '总量',
                    data: y_data
                }
            ]
        };

        if (option && typeof option === "object") {
            myChart.setOption(option, true);
        }

        function getDay7() {
            var day = 0,
                month = 0,
                dateArry = [];
            for (var i=0;i<7;i++){
                var date = new Date();
                date.setDate(date.getDate() - (6 - i));
                day = date.getDate();
                month = date.getMonth()+1;
                dateArry[i] = month+'-'+day;
            }
            return dateArry;
        }
    </script>
@endsection
