@extends('home.myminer.myminer')

@section('myminer-container')

    <div class="app-cells">
        <div class="weui-panel weui-panel_access">
            <div class="weui-panel__hd app-fs-13">
                <p>矿机数量：<span class="color-success">{{count($myMiners)}}</span></p>
                <p>当前算力：<span class="color-success">{{$hashrates}}</span></p>
                <p class="color-warning">* 每天只能收取一次,超过15天未收取自动结束！</p>
            </div>
            @if(count($myMiners) > 0)
            <div class="weui-panel__hd">
                <a href="javascript:collect();" class="weui-btn app-submit">一键收取</a>
            </div>
            <div class="weui-panel__bd">
                @foreach($myMiners as $miner)
                    @if($miner->run_status == \App\Http\Models\MyMiners::RUNNING)
                    <div class="weui-media-box weui-media-box_appmsg index-miner_list">
                        <div class="weui-media-box__hd index-miner_img">
                            <img class="weui-media-box__thumb" src="{{asset('storage/homeImg/'.$miner->miner_tittle.'.jpg')}}">
                        </div>
                        <div class="weui-media-box__bd">
                            <h4 class="weui-media-box__title">{{$miner->miner_tittle}}
                                <i class="iconfont icon-dian1 color-success app-fs-10"> 运行中...</i>
                            </h4>
                            <input type="hidden" name="id" value="{{$miner->id}}">
                            <p class="index-miner_desc">算力：{{$miner->hashrate}} G</p>
                            <p class="index-miner_desc">总产量：<span class="total_dig">{{$miner->total_dig}}</span> HTC</p>
                            <p class="index-miner_desc">每小时产量：{{$miner->nph}} HTC/小时</p>
                            <p class="index-miner_desc">运行周期：{{$miner->runtime}} 小时</p>
                            <p class="index-miner_desc">已挖：<span class="dug">{{$miner->dug}}</span> HTC</p>
                            <p class="index-miner_desc color-main">待收取：<span class="no_collect">{{$miner->no_collect}}</span> HTC</p>
                            <p class="index-miner_desc">租用日期：{{$miner->created_at}}</p>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
            @endif
        </div>
    </div>
@endsection

@section('myminer-js')
    <script>
        function collect() {
            if ($('.index-miner_list').eq(0).find('.no_collect').text() == 0){
                $.alert('正在拼命挖...');
                return;
            }
            $.loading('正在收取');
            var dataArry = [];
            $('.index-miner_list').each(function (k,v) {
                var listArry = {
                    'id': $(this).find('input[name="id"]').val(),
                    'total_dig':$(this).find('.total_dig').text(),
                    'dug': $(this).find('.dug').text(),
                    'no_collect': $(this).find('.no_collect').text()
                };
                dataArry.push(listArry);
            });
            $.ajax({
                method: 'post',
                url: '{{url("home/collect")}}',
                data: {'info':JSON.stringify(dataArry)},
                dataType: 'json',
                success: function (data) {
                    // console.log(data);
                    $.hideLoading();
                    if (data.status == 0){
                        $.toast('收取成功');
                        setTimeout(function () {
                            location.reload();
                        },2000);
                    }else{
                        $.alert('今天已收取，请明天再来');
                    }
                },
                error: function (error) {
                    // console.log(error);
                    $.hideLoading();
                    $.topTip('系统错误');
                }
            });
        }
    </script>
@endsection
