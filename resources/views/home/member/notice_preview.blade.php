@extends('layout.master')
@section('tittle')公告详情@endsection
@section('header')@component('layout.header')@endcomponent @endsection

@section('container')
    <article class="weui-article">
        <h1 style="text-align: center">{{$notice->tittle}}</h1>
        <section>
            <section>
                <p>
                    {!! $notice->content !!}
                </p>
                <p style="text-align: right">
                    <label>HTC官方发布</label><br>
                    <label>{{$notice->updated_at}}</label>
                </p>
            </section>
        </section>
    </article>
@endsection

@section('js')
<script>
    $(function () {
        showHeaderBack();
    });
</script>
@endsection
