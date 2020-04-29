@extends('layout.master')
@section('tittle')
    首页
@endsection
@section('css')
    <link rel="stylesheet" href="{{asset('static/home/css/slider.css')}}">
@endsection

@section('header')
    @component('layout.header')@endcomponent
@endsection

@section('container')
    <!-- {{--轮播图--}} -->
    <div class="slider" id="slider">
        <div class="slider-inner">
            <div class="item">
                <img src="{{asset("static/home/img/main-slider-1.jpg")}}">
            </div>
            <div class="item">
                <img src="{{asset("static/home/img/main-slider-2.jpg")}}">
            </div>
        </div>
    </div>

    <div class="index-header">
        <a href="#" class="index-header_list">
            <i class="iconfont icon-qiandao1"></i>
            <p class="app-fs-16">签到</p>
        </a>
        <a href="#" class="index-header_list">
            <i class="iconfont icon-ico app-fs-27"></i>
            <p class="app-fs-16">邀请</p>
        </a>
        <a href="#" class="index-header_list">
            <i class="iconfont icon-gonggao app-fs-27"></i>
            <p class="app-fs-16">公告</p>
        </a>
        <a href="#" class="index-header_list">
            <i class="iconfont icon-kefu app-fs-27"></i>
            <p class="app-fs-16">联系客服</p>
        </a>
    </div>

    <div class="app-cells">
        <div class="weui-panel weui-panel_access">
            <div class="index-miner_tittle">——矿机列表——</div>
            <div class="weui-panel__bd">
                <div class="weui-media-box weui-media-box_appmsg index-miner_list">
                    <div class="weui-media-box__hd index-miner_img">
                        <img class="weui-media-box__thumb" src="{{asset('static/home/img/微型.gif')}}">
                    </div>
                    <div class="weui-media-box__bd">
                        <h4 class="weui-media-box__title">微型矿机</h4>
                        <p class="index-miner_desc">价格：5 HTC</p>
                        <p class="index-miner_desc">总产量：7 HTC</p>
                        <p class="index-miner_desc">每小时产量：0.0001 HTC/小时</p>
                        <p class="index-miner_desc">运行周期：30 天</p>
                        <button class="index-miner_buy">购买</button>
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
                        <button class="index-miner_buy">购买</button>
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
                        <button class="index-miner_buy">购买</button>
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
                        <button class="index-miner_buy">购买</button>
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
                        <button class="index-miner_buy">购买</button>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    @component('layout.footer')@endcomponent
@endsection

@section('js')
    <script src="{{asset('static/home/js/slider.js')}}"></script>

@endsection

