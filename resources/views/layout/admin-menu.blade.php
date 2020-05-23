<aside class="Hui-aside">
    <div class="menu_dropdown bk_2">
    <dl id="menu-mainPage">
        <a href="{{url('admin/index')}}"><dt><i class="Hui-iconfont"></i> 首页</dt></a>
    </dl>
    <dl id="menu-article">
        <dt><i class="Hui-iconfont">&#xe687;</i> 交易管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
        <dd>
            <ul>
                <li><a href="article-list.html" title="资讯管理">委托买入单</a></li>
                <li><a href="article-list.html" title="资讯管理">委托卖出单</a></li>
                <li><a href="article-list.html" title="资讯管理">交易订单</a></li>
            </ul>
        </dd>
    </dl>
    <dl id="menu-picture">
        <dt><i class="Hui-iconfont">&#xe613;</i> 图片管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
        <dd>
            <ul>
                <li><a href="picture-list.html" title="图片管理">图片管理</a></li>
            </ul>
        </dd>
    </dl>
    <dl id="menu-product">
        <dt><i class="Hui-iconfont">&#xe620;</i> 矿机商城<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
        <dd>
            <ul>
                <li><a href="product-brand.html" title="品牌管理">矿机列表</a></li>
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
                @if(session('permission') == 0 || in_array("admin/memberList",session('permission')))
                <li><a href="{{url('admin/memberMiners')}}" title="矿机管理">矿机管理</a></li>
                @endif
                @if(session('permission') == 0 || in_array("admin/memberTeams",session('permission')))
                <li><a href="{{url('admin/memberTeams')}}" title="团队">团队</a></li>
                @endif
                @if(session('permission') == 0 || in_array("admin/memberActivities",session('permission')))
                <li><a href="{{url('admin/memberActivities')}}" title="活动">活动</a></li>
                @endif
                @if(session('permission') == 0 || in_array("admin/memberIdeals",session('permission')))
                <li><a href="{{url('admin/memberIdeals')}}" title="建议">建议</a></li>
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
