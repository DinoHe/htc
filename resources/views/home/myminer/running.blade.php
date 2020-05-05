@extends('home.myminer.myminer')

@section('myminer-container')

    <div class="app-cells">
        <div class="weui-panel weui-panel_access">
            <div class="weui-panel__hd app-fs-13">
                <p>矿机数量：<span class="color-success">{{count($myMiners)}}</span></p>
                <p class="color-warning">* 每天只能收取一次,超过15天未收取自动结束！</p>
            </div>
            @if(count($myMiners) > 0)
            <div class="weui-panel__hd">
                <a href="javacript:void(0);" class="weui-btn app-submit">一键收取</a>
            </div>
            <div class="weui-panel__bd">
                @foreach($myMiners as $miner)
                <div class="weui-media-box weui-media-box_appmsg index-miner_list">
                    <div class="weui-media-box__hd index-miner_img">
                        <img class="weui-media-box__thumb" src="{{asset('static/home/img/'.$miner->miner_tittle.'.jpg')}}">
                    </div>
                    <div class="weui-media-box__bd">
                        <h4 class="weui-media-box__title">{{$miner->miner_tittle}}
                            <i class="iconfont icon-dian1 color-success app-fs-10"> 运行中...</i>
                        </h4>
                        <p class="index-miner_desc">算力：{{$miner->hashrate}} G</p>
                        <p class="index-miner_desc">总产量：{{$miner->total_dig}} HTC</p>
                        <p class="index-miner_desc">每小时产量：{{$miner->nph}} HTC/小时</p>
                        <p class="index-miner_desc">运行周期：{{$miner->runtime}} 小时</p>
                        <p class="index-miner_desc">已挖：{{$miner->dug}} HTC</p>
                        <p class="index-miner_desc">剩余时间：{{$miner->remaining_time}} HTC</p>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
@endsection

@section('myminer-js')
    <script>
        $(function(){
        });
    </script>
@endsection
