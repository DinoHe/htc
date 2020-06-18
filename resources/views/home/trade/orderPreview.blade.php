@extends('layout.master')
@section('tittle')订单详情 @endsection
@section('header')@component('layout.header')@endcomponent @endsection
@section('container')

    <div class="app-cells">
        <div class="weui-panel weui-panel_access">
            <div class="weui-cells color-white app-fs-16">
                <form action="{{url('home/finishPay')}}" method="post" enctype="multipart/form-data">
                    @csrf
                <input type="hidden" name="id" value="{{$previews->id}}">
                <div class="weui-cell border-radius bg-order">
                    <div class="weui-cell__bd">
                        <h3 class="padding10-b">订单号：{{$previews->order_id}}</h3>
                        <p>数量：{{$previews->trade_number}}</p>
                        <p>单价：${{$previews->trade_price}}</p>
                        <p>日期：{{$previews->created_at}}</p>
                        <p class="padding10-t">总计：${{$previews->trade_total_money}}
                            <span class="app-fs-19 color-error">≈￥{{$previews->trade_total_money * 7}}</span></p>
                        <p class="color-warning" id="chaoshi">超时剩余时间：{{$previews->remaining['h']}}小时{{$previews->remaining['i']}}分{{$previews->remaining['s']}}秒</p>
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
                                @if(!isset($previews->payment_img) && $previews->sales_member_id!=\Illuminate\Support\Facades\Auth::id())
                                <div class="weui-uploader__input-box">
                                    <input id="uploaderInput" class="weui-uploader__input" name="pay_img" accept="image/*" type="file">
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @if($previews->buy_member_id == \Illuminate\Support\Facades\Auth::id())
                    @if($previews->trade_status==\App\Http\Models\Orders::TRADE_NO_PAY)
                    <input type="submit" class="weui-btn app-submit" id="finish_pay" value="已付款">
                    @endif
                @else
                    <input type="submit" class="weui-btn app-submit" id="finish_pay_confirm" value="确认已收款">
                    <input type="button" class="weui-btn app-submit bg-error" onclick="complaint('{{$previews->id}}')" value="投诉少付钱或传假图">
                @endif
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

            var $form = $('form');
            $('#finish_pay').on('click',function () {
                $.confirm('付款提示','确认已付款吗？',function () {
                    $.loading();
                    $form.submit();
                });
                return false;
            });
            $('#finish_pay_confirm').on('click',function () {
                $.confirm('收款提示','确认收到付款吗？',function () {
                    $.loading('正在处理');
                    $form.attr('action',"{{url('home/finishPayConfirm')}}");
                    $form.submit();
                });
                return false;
            })
        });
        //错误提示
        var error = '{{$errors->first('tradeError')}}';
        if (error != ''){
            $.alert(error);
        }

        //超时倒计时
        var $c = $('#chaoshi'),h = '{{$previews->remaining['h']}}',i = '{{$previews->remaining['i']}}',s = '{{$previews->remaining['s']}}';
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
            // console.log(e);
            $.topTip('复制失败');
        });

        function complaint(orderId){
            $.confirm('信息','确认投诉？',function () {
                $.get('{{url("home/tradeComplaint")}}/'+orderId);
                $.alert('投诉成功');
            });
        }

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
