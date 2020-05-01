@extends('layout.master')
@section('tittle')交易行情 @endsection
@section('header')
    @component('layout.header')@endcomponent
@endsection
@section('container')

    <div class="app-cells">
        <div class="weui-panel weui-panel_access">
            <div class="padding15"><div id="container" style="height: 300px;width: 100%"></div></div>
            <div class="weui-cells">
                <div class="weui-cells__title">排单</div>
                <div class="weui-cell">
                    <div class="weui-cell__bd paidan">
                        <a href="javascript:;" class="paidan-bg">5</a>
                        <a href="javascript:;">20</a>
                        <a href="javascript:;">50</a>
                        <a href="javascript:;">100</a>
                        <a href="javascript:;">500</a>
                    </div>
                </div>
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

@section('js')
    <script src="{{asset('ext/echarts/echarts-line.min.js')}}"></script>
    <script>
        $(function () {
            showHeaderBack();
        });

        $('.paidan a').click(function () {
            $(this).addClass('paidan-bg').siblings().removeClass('paidan-bg');
        });

        var dom = document.getElementById("container");
        var myChart = echarts.init(dom);
        var x_data = ['10-18','10-19','10-20','10-21','10-22','10-23','10-24'],
            y_data = [0.1, 0.11, 0.12, 0.13, 0.14, 0.15, 0.16];

        var option = {
            title: {
                text: '实时价格：$0.99',
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
    </script>
@endsection
