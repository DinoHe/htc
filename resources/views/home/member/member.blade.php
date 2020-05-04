@extends('layout.master')
@section('tittle')会员中心@endsection

@section('header')
    @component('layout.header')@endcomponent
@endsection

@section('container')
    <div class="member-header">
        <div class="weui-cell weui-cell_access weui-cell_example">
            <div class="weui-cell__hd"><img src="{{asset('static/home/img/logo.jpg')}}" style="width:110px;margin-left:10px;display:block;border-radius: 50%"></div>
            <div class="weui-cell__bd weui-cell_primary">
                <p class="app-fs-19">{{Auth::user()['phone']}}</p>
                <p class="app-fs-10">认证状态：已认证</p>
                <p class="app-fs-10">会员等级：一级会员</p>
                <p class="app-fs-10">信用分：100</p>
                <p class="app-fs-10">团队人数：0</p>
                <p class="app-fs-10">我的矿机：50</p>
            </div>
            <span><a href="{{ url('/home/logout') }}" class="member-header_logout">退出登录</a></span>
        </div>
    </div>

    <div class="member-container color-white">
        <div class="weui-grids app-grids app-fs-16 member-container_assets">
            <div class="weui-grid">
                <span>100</span>
                <p class="app-fs-13">余额</p>
            </div>
            <div class="weui-grid">
                <span>0</span>
                <p class="app-fs-13">冻结</p>
            </div>
            <div class="weui-grid">
                <span>0</span>
                <p class="app-fs-13">累积购买</p>
            </div>
        </div>
        <div class="weui-grids app-grids app-fs-16 member-container_list">
            <a href="{{url('home/identityAuth')}}" class="weui-grid">
                <i class="iconfont icon-shimingrenzheng"></i>
                <p class="">实名认证</p>
            </a>
            <a href="#" class="weui-grid">
                <i class="iconfont icon-xingzhuangjiehe"></i>
                <p class="">账单</p>
            </a>
            <a href="#" class="weui-grid">
                <i class="iconfont icon-daichulidingdan"></i>
                <p class="">待处理订单</p>
            </a>
            <a href="#" class="weui-grid">
                <i class="iconfont icon-icon"></i>
                <p class="">交易记录</p>
            </a>
            <a href="#" class="weui-grid">
                <i class="iconfont icon-tuandui"></i>
                <p class="">我的团队</p>
            </a>
            <a href="{{url('home/link')}}" class="weui-grid">
                <i class="iconfont icon-fenxianglianjie"></i>
                <p class="">推广链接</p>
            </a>
            <a href="{{url('home/notice')}}" class="weui-grid">
                <i class="iconfont icon-gonggao"></i>
                <p class="">系统公告</p>
            </a>
            <a href="#" class="weui-grid">
                <i class="iconfont icon-duihuan-1"></i>
                <p class="">兑换管理</p>
            </a>
            <a href="#" class="weui-grid">
                <i class="iconfont icon-hangqing1"></i>
                <p class="">行情</p>
            </a>
            <a href="#" class="weui-grid">
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
            <a href="#" class="weui-grid">
                <i class="iconfont icon-jianyi"></i>
                <p class="">建议</p>
            </a>
            <a href="{{url('home/memberService')}}" class="weui-grid">
                <i class="iconfont icon-kefu"></i>
                <p class="">客服</p>
            </a>
            <a href="/home/reset" class="weui-grid">
                <i class="iconfont icon-rizhichakan"></i>
                <p class="">修改密码</p>
            </a>
            </div>
    </div>
@endsection

@section('footer')
    @component('layout.footer')@endcomponent
@endsection

@section('js')
    <script>
        showTabbarBgColor('#member');
    </script>
@endsection

