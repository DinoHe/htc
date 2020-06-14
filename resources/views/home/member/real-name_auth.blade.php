@extends('layout.master')
@section('tittle')实名认证 @endsection
@section('header')@component('layout.header')@endcomponent @endsection
@section('container')

    <div class="app-cells">
        <form action="{{url('home/realNameAuth')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell color-warning" style="text-align: center">
                    <div class="weui-cell__bd">{{$auths->getAuthStatusDesc($auths->auth_status)}}</div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <div class="weui-label"><i class="color-warning">*</i>真实姓名</div>
                    </div>
                    <div class="weui-cell__bd">
                        <input type="text" class="weui-input" name="name" value="{{old('name')?:$auths->name}}" placeholder="必填" required>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <div class="weui-label"><i class="color-warning">*</i>身份证号</div>
                    </div>
                    <div class="weui-cell__bd">
                        <input type="number" class="weui-input" name="idcard" id="idcard" value="{{old('idcard')?:$auths->idcard}}" placeholder="必填" required>
                        <span class="color-error app-fs-13" style="position: absolute;left: 120px;bottom: 0">
                            @if($errors->has('idcard'))
                                {{$errors->first('idcard')}}
                            @endif
                        </span>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <div class="weui-label">支付宝</div>
                    </div>
                    <div class="weui-cell__bd">
                        <input type="text" class="weui-input" name="alipay" value="{{\Illuminate\Support\Facades\Auth::user()->phone}}" readonly>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <div class="weui-label"><i class="color-warning">*</i>微信</div>
                    </div>
                    <div class="weui-cell__bd">
                        <input type="text" class="weui-input" name="weixin" value="{{old('weixin')?:$auths->weixin}}" placeholder="必填" required>
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <div class="weui-label">开户银行</div>
                    </div>
                    <div class="weui-cell__bd">
                        <input type="text" class="weui-input" name="bank_name" value="{{old('bank_name')?:$auths->bank_name}}" placeholder="可选">
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <div class="weui-label">银行卡号</div>
                    </div>
                    <div class="weui-cell__bd">
                        <input type="number" class="weui-input" name="bank_card" value="{{old('bank_card')?:$auths->bank_card}}" placeholder="可选">
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <div class="weui-label" style="width: 100%"><i class="color-warning">*</i>上传身份证正面</div>
                    </div>
                    <span class="color-error app-fs-13" style="position: absolute;left: 120px;bottom: 0">
                        @if($errors->has('front'))
                            {{$errors->first('front')}}
                        @endif
                    </span>
                </div>
                <div class="weui-cell" style="flex-direction: column">
                    <p class="app-fs-10 color-main">
                        <span>需要贴上HTC认证专用纸条,否则不予通过;</span>
                        <span>上传的图片大小不能超过1M</span>
                    </p>
                    <div class="weui-uploader">
                        <div class="weui-uploader__bd">
                            <ul class="weui-uploader__files app-id_file" id="uploaderFiles1">
                                @if(isset($auths->idcard_front_img))
                                    <li style="background-image: url({{asset('storage').$auths->idcard_front_img}})"></li>
                                @endif
                            </ul>
                            @if(!isset($auths->idcard_front_img))
                            <div class="weui-uploader__input-box app-id_input">
                                <input id="uploaderInput1" class="weui-uploader__input" name="id_front" accept="image/*" type="file" required>
                            </div>
                            @endif
                        </div>
                    </div>
                    <p>示例：</p>
                    <img src="{{asset('static/home/img/示例1.gif')}}" class="shili">
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__hd">
                        <div class="weui-label" style="width: 100%"><i class="color-warning">*</i>上传身份证背面</div>
                    </div>
                    <span class="color-error app-fs-13" style="position: absolute;left: 120px;bottom: 0">
                        @if($errors->has('back'))
                            {{$errors->first('back')}}
                        @endif
                    </span>
                </div>
                <div class="weui-cell" style="flex-direction: column">
                    <p class="app-fs-10 color-main">
                        <span>需要贴上HTC认证专用纸条,否则不予通过;</span>
                        <span>上传的图片大小不能超过1M</span>
                    </p>
                    <div class="weui-uploader">
                        <div class="weui-uploader__bd">
                            <ul class="weui-uploader__files app-id_file" id="uploaderFiles2">
                                @if(isset($auths->idcard_back_img))
                                    <li style="background-image: url({{asset('storage').$auths->idcard_back_img}})"></li>
                                @endif
                            </ul>
                            @if(!isset($auths->idcard_back_img))
                            <div class="weui-uploader__input-box app-id_input">
                                <input id="uploaderInput2" class="weui-uploader__input" name="id_back" accept="image/*" type="file" required>
                            </div>
                            @endif
                        </div>
                    </div>
                    <p>示例：</p>
                    <img src="{{asset('static/home/img/示例2.gif')}}" class="shili">
                </div>
            </div>
            @if($auths->auth_status == \App\Http\Models\RealNameAuths::AUTH_FAIL || $auths->auth_status == \App\Http\Models\RealNameAuths::AUTH_CHECK_FAIL)
            <div class="weui-cell">
                <input type="submit" class="weui-btn app-submit" value="提交">
            </div>
            @endif
        </form>
    </div>
@endsection

@section('js')
    <script>
        $(function () {
            showHeaderBack();

            if ($('input:submit').length == 0){
                $('input').attr('disabled','disabled');
            }
        });
        var $idCard = $('#idcard');
        $('form').submit(function () {
            var error = $idCard.siblings().text();
            if (error != ''){
                $.alert(error);
                return false;
            }
            $.loading('正在提交');
        });

        $idCard.blur(function () {
            var idCard = $(this).val(),
                er = $(this).siblings();
            er.empty();
            if (idCard.length != 18){
                er.text('身份证号码不合法');
                return false;
            }
            $.ajax({
                method: 'get',
                url: '{{url("home/idCardCheck")}}/'+idCard,
                dataType: 'json',
                success: function (data) {
                    if (data == 0){
                        er.text('身份证号码不合法');
                    }
                },
                error: function (error) {
                    // console.log(error);
                }
            });
        });

        $(function(){
            var tmpl1 = '<li class="weui-uploader__file1" style="background-image:url(#url1#)"></li>',
                tmpl2 = '<li class="weui-uploader__file2" style="background-image:url(#url2#)"></li>';
            var $uploaderInput1 = $("#uploaderInput1"), //上传按钮+
                $uploaderFiles1 = $("#uploaderFiles1"),
                $uploaderInput2 = $("#uploaderInput2"), //上传按钮+
                $uploaderFiles2 = $("#uploaderFiles2");    //图片列表
            $uploaderInput1.on("change", function(e){
                var src, url = window.URL || window.webkitURL || window.mozURL, files = e.target.files;
                for (var i = 0, len = files.length; i < len; ++i) {
                    var file = files[i];
                    if (url) {
                        src = url.createObjectURL(file);
                    } else {
                        src = e.target.result;
                    }
                    $uploaderFiles1.empty();
                    $uploaderFiles1.append($(tmpl1.replace('#url1#', src)));
                }

                if ($('.weui-uploader__file')){
                    $(this).parent().css('opacity',0);
                }
            });
            $uploaderInput2.on("change", function(e){
                var src, url = window.URL || window.webkitURL || window.mozURL, files = e.target.files;
                for (var i = 0, len = files.length; i < len; ++i) {
                    var file = files[i];
                    if (url) {
                        src = url.createObjectURL(file);
                    } else {
                        src = e.target.result;
                    }
                    $uploaderFiles2.empty();
                    $uploaderFiles2.append($(tmpl2.replace('#url2#', src)));
                }

                if ($('.weui-uploader__file')){
                    $(this).parent().css('opacity',0);
                }
            });

        });
    </script>
@endsection
