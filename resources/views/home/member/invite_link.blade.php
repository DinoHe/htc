@extends('layout.master')
@section('tittle')邀请好友@endsection

@section('container')
<div style="margin: 10px 16px;">
    <div class="weui-form__control-area">
        <div class="weui-cells__group weui-cells__group_form">
            <div class="weui-cells__title">邀请链接：</div>
            <div class="weui-cells weui-cells_form">
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        {{$link}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>

</script>
@endsection
