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
            @foreach($images as $i)
                <div class="item">
                    <img src="{{asset("storage/homeImg/".$i->src)}}">
                </div>
            @endforeach
        </div>
    </div>

    <div class="index-header">
        <a href="javascript:qiandao();" class="index-header_list">
            <i class="iconfont icon-qiandao1"></i>
            <p class="app-fs-16">签到</p>
        </a>
        <a href="{{url('home/link')}}" class="index-header_list">
            <i class="iconfont icon-ico app-fs-27"></i>
            <p class="app-fs-16">分享</p>
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
                        <img class="weui-media-box__thumb" src="{{asset('storage/homeImg/'.$miner->tittle.'.jpg')}}">
                    </div>
                    <div class="weui-media-box__bd">
                        <input type="hidden" name="id" value="{{$miner->id}}">
                        <h4 class="weui-media-box__title">{{$miner->tittle}}</h4>
                        <p class="index-miner_desc">算力：<span>{{$miner->hashrate}}</span> G</p>
                        <p class="index-miner_desc">价格：<span>{{$miner->coin_number}}</span> HTC</p>
                        <p class="index-miner_desc">挖币总量：<span>{{$miner->total_dig}}</span> HTC</p>
                        <p class="index-miner_desc">每小时挖币量：<span>{{$miner->nph}}</span> HTC/小时</p>
                        <p class="index-miner_desc">运行周期：<span>{{$miner->runtime}}</span> 小时</p>
                        <button type="button" class="index-miner_buy">租用</button>
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
            $.loading('正在排队签到');
            $.ajax({
                method: 'get',
                url: '{{url("home/qiandao")}}',
                dataType: 'json',
                success: function (data) {
                    // console.log(data);
                    $.hideLoading();
                    if (data.status == 0){
                        $.toast('签到成功');
                    }else{
                        $.alert(data.message);
                    }
                },
                error: function (error) {
                    // console.log(error);
                    $.hideLoading();
                    $.topTip('系统错误');
                }
            });
        }

        // 租用矿机
        $('.index-miner_buy').on('click',function () {
            var id = $(this).siblings('input').val(),
                tittle = $(this).siblings('h4').text(),
                $data = $(this).siblings('p').children(),
                hashrate = $data.eq(0).text(),
                coin_number = $data.eq(1).text(),
                total_dig = $data.eq(2).text(),
                nph = $data.eq(3).text(),
                runtime = $data.eq(4).text();
            $.confirm('租用矿机','确定租用矿机吗？',function () {
                $.loading('正在排队租用');
                $.ajax({
                   method: 'post',
                   url: '{{url("home/rent")}}',
                   data: {'id':id,'hashrate':hashrate,'coin_number':coin_number,'total_dig':total_dig,'nph':nph,
                       'runtime':runtime,'miner_tittle':tittle},
                   dataType: 'json',
                   success: function (data) {
                       // console.log(data);
                       $.hideLoading();
                        if (data.status == 0){
                            $.toast('租用矿机成功');
                        }else{
                            $.alert(data.message);
                        }
                   },
                    error: function (error) {
                        // console.log(error);
                        $.hideLoading();
                        $.topTip('系统错误');
                    }
                });
            });
        });
    </script>
@endsection

