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
        <a href="javascript:qiandao();" class="index-header_list">
            <i class="iconfont icon-qiandao1"></i>
            <p class="app-fs-16">签到</p>
        </a>
        <a href="{{url('home/link')}}" class="index-header_list">
            <i class="iconfont icon-ico app-fs-27"></i>
            <p class="app-fs-16">邀请</p>
        </a>
        <a href="{{url('home/notice')}}" class="index-header_list">
            <i class="iconfont icon-gonggao app-fs-27"></i>
            <p class="app-fs-16">公告</p>
        </a>
        <a href="{{url('home/memberService')}}" class="index-header_list">
            <i class="iconfont icon-kefu app-fs-27"></i>
            <p class="app-fs-16">联系客服</p>
        </a>
    </div>

    <div class="app-cells">
        <div class="weui-panel weui-panel_access">
            <div class="index-miner_tittle">——矿机列表——</div>
            <div class="weui-panel__bd">
                @foreach($miners as $miner)
                <div class="weui-media-box weui-media-box_appmsg index-miner_list">
                    <div class="weui-media-box__hd index-miner_img">
                        <img class="weui-media-box__thumb" src="{{asset('static/home/'.$miner->miner_img)}}">
                    </div>
                    <div class="weui-media-box__bd">
                        <h4 class="weui-media-box__title">{{$miner->tittle}}</h4>
                        <p class="index-miner_desc">算力：{{$miner->hashrate}} G</p>
                        <p class="index-miner_desc">价格：{{$miner->coin_number}} HTC</p>
                        <p class="index-miner_desc">挖币总量：{{$miner->total_dig}} HTC</p>
                        <p class="index-miner_desc">每小时挖币量：{{$miner->nph}} HTC/小时</p>
                        <p class="index-miner_desc">运行周期：{{$miner->runtime}} 小时</p>
                        <button class="index-miner_buy">租用</button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('footer')
    @component('layout.footer')@endcomponent
@endsection

@section('js')
    <script src="{{asset('static/home/js/slider.js')}}"></script>
    <script>
        function qiandao() {
            $.ajax({
                method: 'get',
                url: '{{url("home/qiandao")}}',
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    if (data.status == 0){
                        $.alert(data.message);
                    }else{
                        $.alert('签到失败，请稍后再试');
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    </script>
@endsection

