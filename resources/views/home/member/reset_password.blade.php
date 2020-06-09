@extends('layout.master')
@section('tittle')修改密码@endsection

@section('header')
    @component('layout.header')@endcomponent
@endsection
@section('container')
    <div class="weui-form__control-area">
        <form action="{{url('home/reset')}}" method="post">
            @csrf
        <div class="weui-cells__group weui-cells__group_form">
            <div class="weui-cells__title">修改登录密码</div>
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">原密码</label></div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" placeholder="请输入旧密码" name="old_password" required>
                    </div>
                    <i class="app-fs-13 color-error">{{$errors->first('passwordError')}}</i>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd"><label class="weui-label">新密码</label></div>
                    <div class="weui-cell__bd">
                        <input type="text" class="weui-input" name="new_password" placeholder="请输入新密码"
                               pattern="[a-zA-Z0-9]{6,}" title="请输入不少于6位密码" required>
                    </div>
                </div>
            </div>
            <div class="weui-cells__title">修改安全密码</div>
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__hd">原安全密码</div>
                    <div class="weui-cell__bd">
                        <input class="weui-input" placeholder="请输入旧密码" name="old_safe_password" required>
                    </div>
                    <i class="app-fs-13 color-error">{{$errors->first('safePasswordError')}}</i>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd">新安全密码</div>
                    <div class="weui-cell__bd">
                        <input type="text" class="weui-input" name="new_safe_password" placeholder="请输入新密码"
                              pattern="[a-zA-Z0-9]{6}" title="请输入6位密码" required>
                    </div>
                </div>
            </div>
            <div class="weui-cells weui-cells_form">
                <input type="submit" class="weui-btn app-submit" value="提交">
            </div>
        </div>
        </form>
    </div>
@endsection

@section('js')
<script>
    showHeaderBack();
</script>
@endsection
