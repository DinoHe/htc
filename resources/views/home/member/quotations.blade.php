@extends('layout.master')
@section('tittle')市场行情@endsection
@section('header')@component('layout.header')@endcomponent @endsection

@section('container')
<div class="app-cells">
    <table style="text-align: center;width: 100%;" cellspacing="0" >
        <thead>
        <tr>
            <th>名称</th>
            <th>价格</th>
            <th>成交量</th>
            <th>涨幅</th>
        </tr>
        </thead>
        <tbody>
        @if(!empty($quotations))
            @foreach($quotations as $q)
                @if($q->currency == 'USD')
                <tr class="table-body">
                    <td style="color: #00a2ca">{{$q->base}}</td>
                    <td style="color:#ea1bbe;font-size: 13px">${{$q->close}} <p>≈￥{{$q->close * 7}}</p></td>
                    <td class="app-fs-13">{{$q->vol}}</td>
                    <td><span>{{$q->degree}}</span>%</td>
                </tr>
                @endif
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
