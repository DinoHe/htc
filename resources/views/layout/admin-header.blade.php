<header class="navbar-wrapper">
    <div class="navbar navbar-fixed-top">
        <div class="container-fluid cl">
            <a class="logo navbar-logo f-l mr-10 hidden-xs" href="{{url('admin/index')}}">HTC后台管理</a>
            <nav id="Hui-userbar" class="nav navbar-nav navbar-userbar hidden-xs">
                <ul class="cl">
                    <li>{{session('admin')['role']}}</li>
                    <li class="dropDown dropDown_hover"> <a href="#" class="dropDown_A">{{session('admin')['account']}} <i class="Hui-iconfont">&#xe6d5;</i></a>
                        <ul class="dropDown-menu menu radius box-shadow">
                            <li><a href="javascript:;" onClick="myselfinfo('{{session('admin')['lastIp']}}','{{session('admin')['lastTime']}}')">个人信息</a></li>
                            <li><a href="{{url('admin/logout')}}">退出</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>
