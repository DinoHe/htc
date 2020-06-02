@extends('layout.master')
@section('tittle')注册 @endsection
@section('css')
    <link rel="stylesheet" href="{{asset('static/home/css/login.css')}}">
@endsection

@section('container')
<article class="htmleaf-container">
    <div class="panel-lite">
        <div class="tittle">注册</div>
        <form action="{{url('home/register')}}" method="post">
            @csrf
            @if($errors->has('register'))
                <div class="login-group color-error">{{$errors->first('register')}}</div>
            @endif
            <div class="login-group">
                <input type="text" required="required" class="login-control" name="phone" value="{{old('phone')}}"/>
                <label class="login-label">手机号</label>
                @if($errors->has('phone'))
                    <span class="app-fs-10 color-error">{{$errors->first('phone')}}</span>
                @endif
            </div>
            <div class="login-group">
                <input type="text" required="required" class="login-control" name="sms_verify" value="{{old('sms_verify')}}"/>
                <label class="login-label">验证码</label>
                <button type="button" class="btn-verify" id="verify" onclick="smsVerify('{{url("home/registerVerify")}}');">获取验证码</button>
                @if($errors->has('sms_verify'))
                    <span class="app-fs-10 color-error">{{$errors->first('sms_verify')}}</span>
                @endif
            </div>
            <div class="login-group">
                <input type="password" required="required" class="login-control" name="password" value="{{old('password')}}"/>
                <label class="login-label">登录密码(不少于6位)</label>
                @if($errors->has('password'))
                    <span class="app-fs-10 color-error">{{$errors->first('password')}}</span>
                @endif
            </div>
            <div class="login-group">
                <input type="password" required="required" class="login-control" name="password_confirmation" value="{{old('password_confirmation')}}"/>
                <label class="login-label">确认登录密码</label>
                @if($errors->has('password_confirmation'))
                    <span class="app-fs-10 color-error">{{$errors->first('password_confirmation')}}</span>
                @endif
            </div>
            <div class="login-group">
                <input type="password" required="required" class="login-control" name="safe_password" value="{{old('safe_password')}}"/>
                <label class="login-label">安全密码(6位)</label>
                @if($errors->has('safe_password'))
                    <span class="app-fs-10 color-error">{{$errors->first('safe_password')}}</span>
                @endif
            </div>
            <div class="login-group">
                <input type="password" required="required" class="login-control" name="safe_password_confirmation" value="{{old('safe_password_confirmation')}}"/>
                <label class="login-label">确认安全密码</label>
                @if($errors->has('safe_password_confirmation'))
                    <span class="app-fs-10 color-error">{{$errors->first('safe_password_confirmation')}}</span>
                @endif
            </div>
            <div class="login-group">
                <input type="text" required="required" class="login-control" value="{{!empty($invite)?$invite:old('invite')}}" name="invite"/>
                <label class="login-label">邀请码</label>
                @if($errors->has('invite'))
                    <span class="app-fs-10 color-error">{{$errors->first('invite')}}</span>
                @endif
            </div>
            <button type="submit" class="floating-btn">注册</button>
        </form>

        <div class="login-tip">
            <div class="login-tip_reg login-tip_group">
                <span style="color: #a3a3a3">已有账号？</span>
                <a href="{{url('home/login')}}">去登录</a>
            </div>
        </div>
    </div>
</article>
@endsection

@section('js')
    <script>
        $(function () {
            $('form').submit(function () {
                $.loading('注册中');
            });
        });
    </script>
@endsection
