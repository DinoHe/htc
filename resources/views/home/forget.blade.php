@extends('layout.master')
@section('tittle')忘记密码 @endsection
@section('css')
    <link rel="stylesheet" href="{{asset('static/home/css/login.css')}}">
@endsection

@section('container')
<article class="htmleaf-container">
    <div class="panel-lite">
        <div class="tittle">找回密码</div>
        <form action="{{url('home/forget')}}" method="post">
            @csrf
            <div class="login-group">
                <input type="text" required="required" class="login-control" name="phone" value="{{session('data')['phone']}}"/>
                <label class="login-label">手机号</label>
                @if($errors->has('phone'))
                    <i class="app-font_mini">{{$errors->first('phone')}}</i>
                @endif
            </div>
            <div class="login-group">
                <input type="text" required="required" class="login-control" name="captcha"/>
                <label class="login-label">验证码</label>
                <button class="btn-verify" id="verify" onclick="smsVerify('{{url("home/forgetVerify")}}');return false;">获取验证码</button>
                @if($errors->has('captcha'))
                    <i class="app-font_mini">{{$errors->first('captcha')}}</i>
                @endif
            </div>
            <div class="login-group">
                <input type="password" required="required" class="login-control" name="password" value="{{session('data')['password']}}"/>
                <label class="login-label">登录密码(不少于6位)</label>
                @if($errors->has('password'))
                    <i class="app-font_mini">{{$errors->first('password')}}</i>
                @endif
            </div>
            <div class="login-group">
                <input type="password" required="required" class="login-control" name="password_confirmation" value="{{session('data')['password_confirmation']}}"/>
                <label class="login-label">确认登录密码</label>
                @if($errors->has('password_confirmation'))
                    <i class="app-font_mini">{{$errors->first('password_confirmation')}}</i>
                @endif
            </div>
            <div class="login-group">
                <input type="password" required="required" class="login-control" name="safe_password" value="{{session('data')['safe_password']}}"/>
                <label class="login-label">安全密码(6位数字)</label>
                @if($errors->has('safe_password'))
                    <i class="app-font_mini">{{$errors->first('safe_password')}}</i>
                @endif
            </div>
            <div class="login-group">
                <input type="password" required="required" class="login-control" name="safe_password_confirmation" value="{{session('data')['safe_password_confirmation']}}"/>
                <label class="login-label">确认安全密码</label>
            </div>
            <button type="submit" class="floating-btn">提交</button>
        </form>
        <div class="login-tip">
            <div class="login-tip_pwd login-tip_group" style="text-align: left">
                <a href="{{url('home/login')}}"><-- 返回登录</a>
            </div>
        </div>
    </div>
</article>
@endsection

@section('js')
<script>
    $(function () {
        $('form').submit(function () {
            $.showLoading('正在提交');
        });
    })
</script>
@endsection
