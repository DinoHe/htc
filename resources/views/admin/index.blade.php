@extends('layout.admin-master')
@section('tittle')首页 @endsection
@section('css')
    <style>
        .index{padding: 20px;text-align: center;}
        .index-content{width: 130px;border: 1px solid #0cadee;display: inline-block;margin: 0 30px;
            color: white}
        .index-content_tittle{background-color: #0cadee;padding: 5px}
        .index-content_ft{color: #08d45a;font-size: 18px}
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
        <nav class="breadcrumb"><i class="Hui-iconfont"></i> <a href="{{url('admin/index')}}" class="maincolor">首页</a>
            <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
        <div class="Hui-article">
            <article class="cl pd-20">
                <div class="index">
                    <div class="index-content">
                        <p class="index-content_tittle">今日价格</p>
                        <p class="index-content_ft">${{$price}}<span class="f-12 c-primary"> ≈￥{{$price*7}}</span></p>
                    </div>
                    <div class="index-content">
                        <p class="index-content_tittle">当前在线人数</p>
                        <p class="index-content_ft">{{$online}}</p>
                    </div>
                    <div class="index-content">
                        <p class="index-content_tittle">运行中的矿机</p>
                        <p class="index-content_ft">{{$countMiner}}</p>
                    </div>
                    <div class="index-content">
                        <p class="index-content_tittle">今日注册的会员</p>
                        <p class="index-content_ft">{{$countRegister}}</p>
                    </div>
                </div>
                <div id="echarts-bar" style="width: 800px;height: 500px"></div>
            </article>
        </div>
    </section>
@endsection

@section('js')
    <script src="{{asset('ext/echarts/echarts-bar.min.js')}}"></script>
    <script>
        var dom = document.getElementById("echarts-bar");
        var myChart = echarts.init(dom);
        var x_data = getDay7(),
            y_data = '{{$countTradeMoney}}'.split(',');

        var option = {
            title: {
                text: '今日成交额：$'+y_data[6],
                left: '10%'
            },
            tooltip: {
                trigger: 'axis',
                axisPointer: {            // 坐标轴指示器，坐标轴触发有效
                    type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                },

            },
            grid: {
                left: '10%',
                right: '5%',
                // bottom: '3%',
                // containLabel: true
            },
            xAxis: {
                type: 'category',
                data: x_data
            },
            yAxis: {
                type: 'value'
            },
            series: [
                {
                    name:'成交额($)',
                    type:'bar',
                    stack: 'one',
                    label: {
                        show: true,
                        position: 'top'
                    },
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
