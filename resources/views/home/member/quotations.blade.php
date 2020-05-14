@extends('layout.master')
@section('tittle')市场行情@endsection
@section('header')@component('layout.header')@endcomponent @endsection

@section('container')
<div class="app-cells">

    <table style="text-align: left;width: 100%" cellspacing="0" >
        <thead>
        <tr>
            <th>名称</th>
            <th>价格</th>
            <th>成交额(24h)</th>
            <th>涨幅(24h)</th>
        </tr>
        </thead>
        <tbody>
        @if(!empty($quotations))
            @foreach($quotations as $q)
            <tr class="table-body">
                <td style="color: #00a2ca">{{$q->coinshortcode.'-'.$q->coinname}}</td>
                <td style="color:#ea1bbe;font-size: 13px">${{$q->price_usd}} <p>≈{{$q->price_cny_text}}</p></td>
                <td class="app-fs-13">{{$q->trademoney24h_cny_text}}</td>
                <td><span>{{$q->percent_24h}}</span>%</td>
            </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
@endsection

@section('js')
<script>
    $(function () {
        showHeaderBack();

        $('.table-body span').each(function () {
            var percent = $(this).text();
            if (percent > 0){
                $(this).text('+'+percent);
                $(this).parent().addClass('color-green');
            }else{
                $(this).parent().addClass('color-error');
            }
        });
    });

</script>
@endsection
