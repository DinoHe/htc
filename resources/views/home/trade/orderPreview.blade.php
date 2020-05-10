@extends('layout.master')
@section('tittle')订单详情 @endsection
@section('header')@component('layout.header')@endcomponent @endsection
@section('container')

    <div class="app-cells">
        <div class="weui-panel weui-panel_access">
            <div class="weui-cells color-white app-fs-16">
                <form action="{{url('home/uploadPayImg')}}" method="post" enctype="multipart/form-data">
                    @csrf
                <input type="hidden" name="id" value="{{$previews->id}}">
                <div class="weui-cell border-radius bg-order">
                    <div class="weui-cell__bd">
                        <h3 class="padding10-b">订单号：{{$previews->order_id}}</h3>
                        <p>数量：{{$previews->trade_number}}</p>
                        <p>单价：${{$previews->trade_price}}</p>
                        <p>日期：{{$previews->created_at}}</p>
                        <p class="padding10-t">总计：${{$previews->trade_price * $previews->trade_number}}
                            <span class="app-fs-19 color-error">≈￥{{$previews->trade_price * $previews->trade_number * 7}}</span></p>
                        <p class="color-warning" id="chaoshi">超时剩余时间：{{$previews->h}}小时{{$previews->i}}分{{$previews->s}}秒</p>
                    </div>
                </div>
                <div class="weui-cell border-radius bg-order">
                    <div class="weui-cell__bd">
                        <h3 class="color-success">买家</h3>
                        <p>信用度：{{$previews->buyMemberCredit}}</p>
                        <p>联系电话：{{$previews->buyMemberPhone}}</p>
                        <p>微信：{{$previews->buyMemberW}}</p>
                    </div>
                </div>
                <div class="weui-cell border-radius bg-order">
                    <div class="weui-cell__bd">
                        <h3 CLASS="color-success">卖家</h3>
                        <p>姓名：{{$previews->salesMemberName}}</p>
                        <p>信用度：{{$previews->salesMemberCredit}}</p>
                        <p>开户行：{{$previews->salesMemberBankName}}</p>
                        <p>银行卡号：{{$previews->salesMemberBankCard}}</p>
                        <p>微信：{{$previews->salesMemberW}}</p>
                        <p>支付宝：<span id="alipay_copy">{{$previews->salesMemberAlipay}}</span>
                            <button type="button" class="copy" data-clipboard-target="#alipay_copy">复制</button></p>
                    </div>
                </div>
                <div class="weui-cell border-radius bg-order">
                    <div class="weui-cell__bd">
                        <h3>付款截图</h3>
                        <div class="weui-uploader">
                            <div class="weui-uploader__bd">
                                <ul class="weui-uploader__files" id="uploaderFiles">
                                    <li class="weui-uploader__file" style="background-image: url({{asset('storage').$previews->payment_img}})"></li>
                                </ul>
                                @if(!isset($previews->payment_img))
                                <div class="weui-uploader__input-box">
                                    <input id="uploaderInput" class="weui-uploader__input" name="pay_img" accept="image/*" type="file">
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <input type="submit" class="weui-btn app-submit"
                       value="{{$previews->trade_status==\App\Http\Models\Orders::TRADE_NO_PAY?'已付款':'确认收款'}}">
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{asset('ext/clipboard/clipboard.min.js')}}"></script>
    <script>
        $(function () {
            showHeaderBack();
        });

        var error = '{{$errors->first('uploadError')}}';
        if (error != ''){
            $.alert(error);
        }

        //超时倒计时
        var $c = $('#chaoshi'),h = '{{$previews->h}}',i = '{{$previews->i}}',s = '{{$previews->s}}';
        var stop = setInterval(function () {
            if (s != 0) {
                s--;
            }else if (i != 0) {
                s = 59;
                i--;
            }else if (h != 0){
                i = 59;
                h--;
            }else{
                clearInterval(stop);
            }
            $c.empty();
            $c.text('超时剩余时间：'+h+'小时'+i+'分'+s+'秒');
        },1000);

        var clipboard = new ClipboardJS('.copy');
        clipboard.on('success', function(e) {
            $.toast('已复制');
        });
        clipboard.on('error', function(e) {
            console.log(e);
        });

        $('form').submit(function () {
            $.loading();
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
