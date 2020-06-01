<aside class="Hui-aside">
    <div class="menu_dropdown bk_2">
    <dl id="menu-mainPage">
        <ul>
            <li><a href="{{url('admin/index')}}"><i class="Hui-iconfont"></i> 首页</a></li>
        </ul>
    </dl>
    <dl id="menu-article">
        <dt><i class="Hui-iconfont">&#xe687;</i> 交易管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
        <dd>
            <ul>
                @if(session('permission') == 0 || in_array("admin/tradeBuyList",session('permission')))
                <li><a href="{{url('admin/tradeBuyList')}}" title="委托买入单">委托买入单</a></li>
                @endif
                @if(session('permission') == 0 || in_array("admin/tradeSalesList",session('permission')))
                <li><a href="{{url('admin/tradeSalesList')}}" title="委托卖出单">委托卖出单</a></li>
                @endif
                @if(session('permission') == 0 || in_array("admin/tradeOrder",session('permission')))
                <li><a href="{{url('admin/tradeOrder')}}" title="交易订单">交易订单</a></li>
                @endif
            </ul>
        </dd>
    </dl>
    <dl id="menu-picture">
        <dt><i class="Hui-iconfont">&#xe613;</i> 图片管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
        <dd>
            <ul>
                @if(session('permission') == 0 || in_array("admin/imageList",session('permission')))
                <li><a href="{{url('admin/imageList')}}" title="图片管理">图片管理</a></li>
                @endif
            </ul>
        </dd>
    </dl>
    <dl id="menu-product">
        <dt><i class="Hui-iconfont">&#xe620;</i> 矿机商城<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
        <dd>
            <ul>
                @if(session('permission') == 0 || in_array("admin/minerList",session('permission')))
                <li><a href="{{url('admin/minerList')}}" title="矿机列表">矿机列表</a></li>
                @endif
            </ul>
        </dd>
    </dl>
    <dl id="menu-member">
        <dt><i class="Hui-iconfont">&#xe60d;</i> 会员管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
        <dd>
            <ul>
                @if(session('permission') == 0 || in_array("admin/memberList",session('permission')))
                <li><a href="{{url('admin/memberList')}}" title="会员列表">会员列表</a></li>
                @endif
                @if(session('permission') == 0 || in_array("admin/memberLevel",session('permission')))
                <li><a href="{{url('admin/memberLevel')}}" title="等级管理">等级管理</a></li>
                @endif
                @if(session('permission') == 0 || in_array("admin/memberRealName",session('permission')))
                <li><a href="{{url('admin/memberRealName')}}" title="实名认证">实名认证</a></li>
                @endif
                @if(session('permission') == 0 || in_array("admin/memberAssets",session('permission')))
                <li><a href="{{url('admin/memberAssets')}}" title="资产管理">资产管理</a></li>
                @endif
                @if(session('permission') == 0 || in_array("admin/memberMiner",session('permission')))
                <li><a href="{{url('admin/memberMiner')}}" title="矿机管理">矿机管理</a></li>
                @endif
                @if(session('permission') == 0 || in_array("admin/memberBill",session('permission')))
                    <li><a href="{{url('admin/memberBill')}}" title="账单">账单</a></li>
                @endif
                @if(session('permission') == 0 || in_array("admin/memberTeam",session('permission')))
                <li><a href="{{url('admin/memberTeam')}}" title="团队">团队</a></li>
                @endif
                @if(session('permission') == 0 || in_array("admin/memberActivity",session('permission')))
                <li><a href="{{url('admin/memberActivity')}}" title="活动">活动</a></li>
                @endif
                @if(session('permission') == 0 || in_array("admin/memberIdeal",session('permission')))
                <li><a href="{{url('admin/memberIdeal')}}" title="建议反馈">建议反馈</a></li>
                @endif
            </ul>
        </dd>
    </dl>
    <dl id="menu-admin">
        <dt><i class="Hui-iconfont">&#xe62d;</i> 管理员管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
        <dd>
            <ul>
                @if(session('permission') == 0 || in_array("admin/adminList",session('permission')))
                <li><a href="{{url('admin/adminList')}}" title="管理员列表">管理员列表</a></li>
                @endif
                @if(session('permission') == 0 || in_array("admin/adminRole",session('permission')))
                <li><a href="{{url('admin/adminRole')}}" title="角色管理">角色管理</a></li>
                @endif
                @if(session('permission') == 0 || in_array("admin/adminPermission",session('permission')))
                <li><a href="{{url('admin/adminPermission')}}" title="权限管理">权限管理</a></li>
                @endif
            </ul>
        </dd>
    </dl>
    <dl id="menu-system">
        <dt><i class="Hui-iconfont">&#xe62e;</i> 系统管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
        <dd>
            <ul>
                @if(session('permission') == 0 || in_array("admin/systemSetting",session('permission')))
                <li><a href="{{url('admin/systemSetting')}}" title="系统设置">系统设置</a></li>
                @endif
                @if(session('permission') == 0 || in_array("admin/systemNotice",session('permission')))
                <li><a href="{{url('admin/systemNotice')}}" title="栏目管理">系统公告</a></li>
                @endif
                @if(session('permission') == 0 || in_array("admin/systemLog",session('permission')))
                <li><a href="{{url('admin/systemLog')}}" title="系统日志">系统日志</a></li>
                @endif
            </ul>
        </dd>
    </dl>
</div>
</aside>
<div class="dislpayArrow hidden-xs"><a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a></div>
