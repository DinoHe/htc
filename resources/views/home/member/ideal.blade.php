@extends('layout.master')
@section('tittle')建议@endsection

@section('header')
    @component('layout.header')@endcomponent
@endsection
@section('container')
    <div class="app-cells">
        <div class="weui-form__text-area">
            <div class="weui-form__desc app-fs-13 color-warning">如果建议被采纳，根据建议的实用性，最高奖励大型云矿机一台</div>
        </div>
        <div class="weui-form__control-area">
            <div class="weui-cells__group weui-cells__group_form">
                <div class="weui-cells__title">建议描述</div>
                <div class="weui-cells weui-cells_form">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <textarea class="weui-textarea" placeholder="请描述你的建议" rows="10" maxlength="200"></textarea>
                            <div class="weui-textarea-counter"><span>0</span>/200</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="weui-form__opr-area">
            <a class="weui-btn app-submit" href="javascript:ideal();" id="showTooltips">提交</a>
        </div>
    </div>
@endsection

@section('js')
<script>
    showHeaderBack();

    var $counter = $('.weui-textarea-counter span');
    $('.weui-textarea').on('keyup',function () {
        var areaLen = $(this).val().length;
        $counter.empty();
        $counter.text(areaLen);
    });

    function ideal() {
        var content = $('.weui-textarea').val();
        if (content.length > 200){
            $.alert('请输入不超过200个字符');
            return false;
        }
        $.loading('正在提交');
        $.ajax({
            method: 'post',
            url: '{{url("home/ideal")}}',
            data: {'content':content},
            dataType: 'json',
            success: function (data) {
                $.hideLoading();
                if (data.status == 0){
                    $.toast('提交成功');
                    setTimeout(function () {
                        location.reload();
                    },2000);
                }
            },
            error: function (error) {
                $.hideLoading();
                $.topTip('提交失败');
            }
        });
    }
</script>
@endsection
