@extends('layout.master')
@section('tittle')订单详情 @endsection
@section('header')@component('layout.header')@endcomponent @endsection
@section('container')

    <div class="app-cells">
        <div class="weui-panel weui-panel_access">
            <div class="weui-cells color-white app-fs-16">
                <form action="{{url('home/uploadPayImg')}}" method="post" enctype="multipart/form-data">
                    @csrf
                <div class="weui-cell border-radius bg-order">
                    <div class="weui-cell__bd">
                        <h3 class="padding10-b">订单号：htc123456678</h3>
                        <p>数量：10</p>
                        <p>单价：$5</p>
                        <p>日期：2020-04-27</p>
                        <p class="padding10-t">总计：$50 <span class="app-fs-19 color-error">≈￥350</span></p>
                    </div>
                </div>
                <div class="weui-cell border-radius bg-order">
                    <div class="weui-cell__bd">
                        <h3>买家</h3>
                        <p>信用度：100</p>
                        <p>联系电话：123456789</p>
                        <p>微信：123456789</p>
                    </div>
                </div>
                <div class="weui-cell border-radius bg-order">
                    <div class="weui-cell__bd">
                        <h3>卖家</h3>
                        <p>姓名：xxxx</p>
                        <p>信用度：100</p>
                        <p>开户行：中国银行</p>
                        <p>银行卡号：1234567890123456</p>
                        <p>微信：12345678</p>
                        <p>支付宝：123456789</p>
                    </div>
                </div>
                <div class="weui-cell border-radius bg-order">
                    <div class="weui-cell__bd">
                        <h3>付款截图</h3>
                        <div class="weui-uploader">
                            <div class="weui-uploader__bd">
                                <ul class="weui-uploader__files" id="uploaderFiles">
                                </ul>
                                <div class="weui-uploader__input-box">
                                    <input id="uploaderInput" class="weui-uploader__input" name="pay_img" accept="image/*" type="file">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="submit" class="weui-btn app-submit" value="上传截图">
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(function () {
            showHeaderBack();
        });
        $('form').submit(function () {
            $.loading('上传中');
        });
        $(function(){

            var tmpl = '<li class="weui-uploader__file" style="background-image:url(#url#)"></li>';
            var      $uploaderInput = $("#uploaderInput"); //上传按钮+
            var       $uploaderFiles = $("#uploaderFiles");    //图片列表
            $uploaderInput.on("change", function(e){
                var src, url = window.URL || window.webkitURL || window.mozURL, files = e.target.files;
                for (var i = 0, len = files.length; i < len; ++i) {
                    var file = files[i];
                    if (url) {
                        src = url.createObjectURL(file);
                    } else {
                        src = e.target.result;
                    }
                    $uploaderFiles.empty();
                    $uploaderFiles.append($(tmpl.replace('#url#', src)));
                }

                if ($('.weui-uploader__file')){
                    $(this).parent().css('opacity',0);
                }
            });

        });
    </script>
@endsection
