<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<!--[if lt IE 9]>
<script type="text/javascript" src="{{asset('static/admin/lib/html5.js')}}"></script>
<script type="text/javascript" src="{{asset('static/admin/lib/respond.min.js')}}"></script>
<![endif]-->
<link href="{{asset('static/admin/css/htc.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('static/admin/css/login.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('static/admin/lib/iconfont/1.0.8/iconfont.css')}}" rel="stylesheet" type="text/css" />

<title>后台登录</title>
</head>
<body>
<input type="hidden" id="TenantId" name="TenantId" value="" />
<div class="header"><h2 class="header-content">HTC 后台管理</h2></div>
<div class="loginWraper">
	<div id="loginform" class="loginBox">
		<form class="form form-horizontal" action="{{url('admin/login')}}" method="post">
            @csrf
            <i style="position: absolute;left: 28%;top: 28px;color: red">{{$errors->first('loginError')}}</i>
			<div class="row cl">
				<label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60d;</i></label>
				<div class="formControls col-xs-8">
					<input name="account" type="text" placeholder="账户" class="input-text size-L">
				</div>
			</div>
			<div class="row cl">
				<label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60e;</i></label>
				<div class="formControls col-xs-8">
					<input name="password" type="password" placeholder="密码" class="input-text size-L">
				</div>
			</div>
			<div class="row cl">
				<div class="formControls col-xs-8 col-xs-offset-3">
					<input class="input-text size-L" type="text" name="captcha" placeholder="验证码:" style="width:150px;">
                    <img src="{{captcha_src()}}" onclick="this.src='{{captcha_src()}}'+Math.random()">
				</div>
			</div>
			<div class="row cl">
				<div class="formControls col-xs-8 col-xs-offset-3">
					<input type="submit" class="btn btn-success radius size-L" style="width: 100%" value="登 录">
				</div>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript" src="{{asset('js/jquery-3.1.1.min.js')}}"></script>
<script type="text/javascript" src="{{asset('static/admin/js/htc.js')}}"></script>
<script>
    $('form').submit(function () {
        var $s = $('input:submit');
        $s.css('opacity',0.7);
        $('input').attr('readonly','readonly');
        $s.attr('disabled','disabled');
        $s.val('登 录 中 ...');
    });

</script>
</body>
</html>
