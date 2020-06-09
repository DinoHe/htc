@extends('layout.master')
@section('tittle')登录 @endsection
@section('css')
    <link rel="stylesheet" href="{{asset('static/home/css/login.css')}}">
@endsection

@section('container')
<article class="htmleaf-container">
    <div class="panel-lite">
        <div class="thumbur"></div>
        <div class="tittle">火特币</div>
        <form action="{{url('home/login')}}" method="post">
            @csrf
            <div class="login-group">
                <strong class="app-font_mini color-error" style="position: absolute;left: 0;top: -35px">{{ session('error') }}</strong>

                <input type="text" required="required" class="login-control" name="phone" value="{{old('phone')}}"/>
                <label class="login-label">用户名</label>
            </div>
            <div class="login-group">
                <input type="password" required="required" class="login-control" name="password" value="{{old('password')}}"/>
                <label class="login-label">密　码</label>
            </div>
            <div class="login-group">
                <input type="text" required="required" class="login-control" name="captcha" pattern="[a-zA-Z0-9]*"/>
                <label class="login-label">验证码</label>
                <img src="{{captcha_src()}}" style="position:absolute;top:-2px;right:0;cursor:pointer;z-index:9999"
                     onclick="this.src='{{captcha_src()}}'+Math.random()">
            </div>
            <button type="submit" class="floating-btn">登录</button>
        </form>
        <div class="login-tip">
            <div class="login-tip_reg login-tip_group">
                <span style="color: #a3a3a3">没有账号？</span>
                <a href="{{url('home/register')}}">注册</a>
            </div>
            <div class="login-tip_pwd login-tip_group">
                <a href="{{url('home/forget')}}">忘记密码？</a>
            </div>
        </div>
    </div>
</article>
@endsection

@section('js')
    <script>
        $(function () {
            $('form').submit(function () {
                $.loading('登录中');
            });
        });
    </script>
@endsection
