@extends('layout.master')
@section('tittle')会员中心@endsection

@section('header')
    @component('layout.header')@endcomponent
@endsection

@section('container')
    <div class="member-header">
        <div class="weui-cell">
            <div class="weui-cell__hd"><img src="{{asset('static/home/img/logo.jpg')}}" style="width:110px;margin-left:10px;display:block;border-radius: 50%"></div>
            <div class="weui-cell__bd weui-cell_primary">
                <p class="app-fs-24">{{$member->phone}}</p>
                <p class="app-fs-13">认证状态：{{$member->authStatus}}</p>
                <p class="app-fs-13">会员等级：{{$member->level}}</p>
                <p class="app-fs-13">团队人数：{{$member->teamsNumber}}</p>
                <p class="app-fs-13">我的矿机：{{$member->minerNumber}}</p>
                <p class="app-fs-13">信用：{{$member->credit}}</p>
            </div>
        </div>
    </div>

    <div class="member-container color-white">
        <div class="app-fs-16 member-container_assets">
            <div class="member-container_assets_show">
                <span>{{$assets->balance}}</span>
                <p class="app-fs-13">余额</p>
            </div>
            <div class="member-container_assets_show">
                <span>{{$assets->blocked_assets}}</span>
                <p class="app-fs-13">冻结</p>
            </div>
            <div class="member-container_assets_show">
                <span>{{$assets->buy_total}}</span>
                <p class="app-fs-13">累积购买</p>
            </div>
        </div>
        <div class="weui-grids app-grids app-fs-16 member-container_list">
            <a href="{{url('home/realNameAuth')}}" class="weui-grid">
                <i class="iconfont icon-shimingrenzheng"></i>
                <p class="">实名认证</p>
            </a>
            <a href="{{url('home/bill')}}" class="weui-grid">
                <i class="iconfont icon-xingzhuangjiehe"></i>
                <p class="">账单</p>
            </a>
            <a href="{{url('home/team')}}" class="weui-grid">
                <i class="iconfont icon-tuandui"></i>
                <p class="">我的团队</p>
            </a>
            <a href="{{url('home/link')}}" class="weui-grid">
                <i class="iconfont icon-fenxianglianjie"></i>
                <p class="">分享</p>
            </a>
            <a href="{{url('home/notice')}}" class="weui-grid">
                <i class="iconfont icon-gonggao"></i>
                <p class="">系统公告</p>
            </a>
            <a href="#" class="weui-grid">
                <i class="iconfont icon-duihuan-1"></i>
                <p class="">兑换管理</p>
            </a>
            <a href="{{url('home/quotations')}}" class="weui-grid">
                <i class="iconfont icon-hangqing1"></i>
                <p class="">行情</p>
            </a>
            <a href="{{url('home/running')}}" class="weui-grid">
                <i class="iconfont icon-kuangji"></i>
                <p class="">我的矿机</p>
            </a>
            <a href="#" class="weui-grid">
                <i class="iconfont icon-shangcheng"></i>
                <p class="">商城</p>
            </a>
            <a href="#" class="weui-grid">
                <i class="iconfont icon-huafeichongzhi"></i>
                <p class="">话费充值</p>
            </a>
            <a href="#" class="weui-grid">
                <i class="iconfont icon-youqiachongzhi"></i>
                <p class="">油卡充值</p>
            </a>
            <a href="{{url('home/ideal')}}" class="weui-grid">
                <i class="iconfont icon-jianyi"></i>
                <p class="">建议反馈</p>
            </a>
            <a href="{{url('home/memberService')}}" class="weui-grid">
                <i class="iconfont icon-kefu"></i>
                <p class="">客服</p>
            </a>
            <a href="{{url('home/reset')}}" class="weui-grid">
                <i class="iconfont icon-rizhichakan"></i>
                <p class="">修改密码</p>
            </a>
            <a href="{{url('home/logout')}}" class="weui-grid">
                <i class="iconfont icon-tuichufffpx"></i>
                <p class="">退出登录</p>
            </a>
        </div>
    </div>
@endsection

@section('footer')
    @component('layout.footer')@endcomponent
@endsection

@section('js')
    <script>
        $(function () {
            showTabbarBgColor('#member');
            $('.member-container_list a').on('click',function () {
                if ($(this).attr('href') == '#'){
                    $.alert('暂未开放');
                }
            });
        });
    </script>
@endsection

