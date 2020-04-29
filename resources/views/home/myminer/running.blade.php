@extends('home.myminer.myminer')

@section('myminer-container')
    <div class="myminer-tittle">
        <a class="myminer-running myminer-select">运行中的矿机</a>
        <a href="{{url('home/finished')}}" class="myminer-finished">已结束的矿机</a>
    </div>

    <div class="app-cells">
        <div class="weui-panel weui-panel_access">
            <div class="weui-panel__hd app-fs-13">
                <p>矿机数量：<span class="color-success">5</span></p>
                <p class="color-warning">* 每天只能收取一次,超过15天未收取自动结束！</p>
            </div>
            <div class="weui-panel__hd">
                <a href="javacript:void(0);" class="weui-btn app-submit">一键收取</a>
            </div>
            <div class="weui-panel__bd">
                <div class="weui-media-box weui-media-box_appmsg index-miner_list">
                    <div class="weui-media-box__hd index-miner_img">
                        <img class="weui-media-box__thumb" src="{{asset('static/home/img/微型.gif')}}">
                    </div>
                    <div class="weui-media-box__bd">
                        <h4 class="weui-media-box__title">微型矿机 <i class="iconfont icon-dian1 color-success app-fs-10"> 运行中...</i></h4>
                        <p class="index-miner_desc">价格：5 HTC</p>
                        <p class="index-miner_desc">总产量：7 HTC</p>
                        <p class="index-miner_desc">每小时产量：0.0001 HTC/小时</p>
                        <p class="index-miner_desc">运行周期：30 天</p>
                    </div>
                </div>
                <a href="javascript:void(0);" class="weui-media-box weui-media-box_appmsg index-miner_list">
                    <div class="weui-media-box__hd index-miner_img">
                        <img class="weui-media-box__thumb" src="{{asset('static/home/img/小型.gif')}}">
                    </div>
                    <div class="weui-media-box__bd">
                        <h4 class="weui-media-box__title">小型矿机</h4>
                        <p class="index-miner_desc">价格：5 HTC</p>
                        <p class="index-miner_desc">总产量：7 HTC</p>
                        <p class="index-miner_desc">每小时产量：0.0001 HTC/小时</p>
                        <p class="index-miner_desc">运行周期：30 天</p>
                    </div>
                </a>
                <a href="javascript:void(0);" class="weui-media-box weui-media-box_appmsg index-miner_list">
                    <div class="weui-media-box__hd index-miner_img">
                        <img class="weui-media-box__thumb" src="{{asset('static/home/img/中型.gif')}}">
                    </div>
                    <div class="weui-media-box__bd">
                        <h4 class="weui-media-box__title">中型矿机</h4>
                        <p class="index-miner_desc">价格：5 HTC</p>
                        <p class="index-miner_desc">总产量：7 HTC</p>
                        <p class="index-miner_desc">每小时产量：0.0001 HTC/小时</p>
                        <p class="index-miner_desc">运行周期：30 天</p>
                    </div>
                </a>
                <a href="javascript:void(0);" class="weui-media-box weui-media-box_appmsg index-miner_list">
                    <div class="weui-media-box__hd index-miner_img">
                        <img class="weui-media-box__thumb" src="{{asset('static/home/img/大型.gif')}}">
                    </div>
                    <div class="weui-media-box__bd">
                        <h4 class="weui-media-box__title">大型矿机</h4>
                        <p class="index-miner_desc">价格：5 HTC</p>
                        <p class="index-miner_desc">总产量：7 HTC</p>
                        <p class="index-miner_desc">每小时产量：0.0001 HTC/小时</p>
                        <p class="index-miner_desc">运行周期：30 天</p>
                    </div>
                </a>
                <a href="javascript:void(0);" class="weui-media-box weui-media-box_appmsg index-miner_list">
                    <div class="weui-media-box__hd index-miner_img">
                        <img class="weui-media-box__thumb" src="{{asset('static/home/img/超级.gif')}}">
                    </div>
                    <div class="weui-media-box__bd">
                        <h4 class="weui-media-box__title">超级矿机</h4>
                        <p class="index-miner_desc">价格：5 HTC</p>
                        <p class="index-miner_desc">总产量：7 HTC</p>
                        <p class="index-miner_desc">每小时产量：0.0001 HTC/小时</p>
                        <p class="index-miner_desc">运行周期：30 天</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection

@section('myminer-js')
    <script>
        $(function(){
        });
    </script>
@endsection
