@extends('home.myminer.myminer')

@section('myminer-container')

    <div class="app-cells">
        <div class="weui-panel weui-panel_access">
            <div class="weui-panel__bd">
                @if(count($myMiners) > 0)
                    @foreach($myMiners as $miner)
                    <div class="weui-media-box weui-media-box_appmsg index-miner_list">
                        <div class="weui-media-box__hd index-miner_img">
                            <img class="weui-media-box__thumb" src="{{asset('storage/homeImg/'.$miner->miner_tittle.'.jpg')}}">
                        </div>
                        <div class="weui-media-box__bd">
                            <h4 class="weui-media-box__title">{{$miner->miner_tittle}} <i class="iconfont icon-dian1 color-primary app-fs-10"> {{$miner->getMinerStatus($miner->run_status)}}</i></h4>
                            <p class="index-miner_desc">算力：{{$miner->hashrate}} G</p>
                            <p class="index-miner_desc">总产量：{{$miner->total_dig}} HTC</p>
                            <p class="index-miner_desc">每小时产量：{{$miner->nph}} HTC/小时</p>
                            <p class="index-miner_desc">运行周期：{{$miner->runtime}} 小时</p>
                            <p class="index-miner_desc">已挖：{{$miner->dug}} HTC</p>
                            <p class="index-miner_desc">剩余时间：{{$miner->remaining_time}} HTC</p>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
            @if(count($myMiners) >= 20)
            <a href="javascript:void(0);" class="weui-cell weui-cell_access weui-cell_link">
                <div class="weui-cell__bd" id="getmore">查看更多<i id="loading" class="weui-loading app-dp_no"></i></div>
            </a>
            @endif
        </div>
    </div>
@endsection

@section('myminer-js')
    <script>
        $(function(){
            $('.myminer-finished').addClass('myminer-select').siblings().removeClass('myminer-select');

            var $more = $('#getmore');
            $more.on('click',function () {
                $('#loading').removeClass('app-dp_no');
                $.ajax({
                    method: 'get',
                    url: '{{url("home/getMoreMinerFinished")}}/'+$('.index-miner_list').length,
                    dataType: 'json',
                    success: function (data) {
                        if (data.status != 0){
                            $more.empty();
                            $more.text('没有更多数据');
                            return false;
                        }
                        $.each(data.miners,function (k,v) {
                            var content = '<div class="weui-media-box weui-media-box_appmsg index-miner_list">\n' +
                                '                        <div class="weui-media-box__hd index-miner_img">\n' +
                                '                            <images class="weui-media-box__thumb" src="{{asset('static/home/images')}}'+v.miner_tittle+'.jpg">\n' +
                                '                        </div>\n' +
                                '                        <div class="weui-media-box__bd">\n' +
                                '                            <h4 class="weui-media-box__title">'+v.miner_tittle+' <i class="iconfont icon-dian1 color-primary app-fs-10"> 已结束</i></h4>\n' +
                                '                            <p class="index-miner_desc">算力：'+v.hashrate+' G</p>\n' +
                                '                            <p class="index-miner_desc">总产量：'+v.total_dig+' HTC</p>\n' +
                                '                            <p class="index-miner_desc">每小时产量：'+v.nph+' HTC/小时</p>\n' +
                                '                            <p class="index-miner_desc">运行周期：'+v.runtime+' 小时</p>\n' +
                                '                            <p class="index-miner_desc">已挖：'+v.dug+' HTC</p>\n' +
                                '                            <p class="index-miner_desc">剩余时间：'+v.remaining_time+' HTC</p>\n' +
                                '                        </div>\n' +
                                '                    </div>';
                            $('.weui-panel__bd').append(content);
                        });
                        $('#loading').addClass('app-dp_no');
                    },
                    error: function (error) {
                        $.topTip('系统错误');
                    }
                });
            });
        });
    </script>
@endsection
