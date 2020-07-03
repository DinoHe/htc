@extends('layout.admin-master')
@section('tittle')系统设置 @endsection

@section('header')
    @component('layout.admin-header')@endcomponent
@endsection

@section('aside')
    @component('layout.admin-menu')@endcomponent
@endsection

@section('container')
    <section class="Hui-article-box">
        <nav class="breadcrumb">
            <a class="btn btn-success radius l" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a>
        </nav>
        <div class="Hui-article">
            <article class="cl pd-20">
                <div id="tab-system" class="HuiTab">
                    <div class="tabBar cl">
                        <span>基本设置</span>
                        @if(session('permission') == 0)
                        <span>高级设置</span>
                        @endif
                    </div>
                    <div class="tabCon">
                        <form action="{{url('admin/systemSetting')}}" method="post" class="form form-horizontal">
                        @if(count($settings) > 0)
                            @foreach($settings as $s)
                                @if($s->input_type == 'text')
                                <div class="row cl">
                                    <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>{{$s->describes}}：</label>
                                    <div class="formControls col-xs-8 col-sm-9">
                                        <input type="text" value="{{$s->value}}" name="{{$s->tittle}}" class="input-text" required>
                                    </div>
                                </div>
                                @elseif($s->input_type == 'switch')
                                <div class="row cl">
                                    <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>{{$s->describes}}：</label>
                                    <div class="formControls col-xs-8 col-sm-9">
                                        <div class="switch">
                                            <input type="hidden" name="{{$s->tittle}}" value="{{$s->value}}">
                                            <input type="checkbox" {{$s->value=='on'?'checked':''}}>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        @endif
                            <div class="row cl">
                                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                                    <button class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 保存</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if(session('permission') == 0)
                    <div class="tabCon">
                        <form action="{{url('admin/systemAdvancedSetting')}}" method="post" class="form form-horizontal">
                            @csrf
                            <div class="row cl">
                                <label class="form-label col-xs-4 col-sm-2">当前币价($)：</label>
                                <div class="formControls col-xs-8 col-sm-9">
                                    <input type="hidden" value="{{$coin->id}}" name="id">
                                    <input type="text" value="{{$coin->price}}" name="price" class="input-text" required>
                                </div>
                            </div>
                            <div class="row cl">
                                <label class="form-label col-xs-4 col-sm-2">手机号码：</label>
                                <div class="formControls col-xs-8 col-sm-9">
                                    <input type="number" class="input-text" style="width: 200px">
                                    <a onclick="sendTest(this)" class="btn btn-success">发送验证码</a>
                                </div>
                            </div>
                            <div class="row cl">
                                <label class="form-label col-xs-4 col-sm-2">返回内容：</label>
                                <div class="formControls col-xs-8 col-sm-9">
                                    <div id="testContent"></div>
                                </div>
                            </div>
                            <div class="row cl">
                                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                                    <button class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 保存</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
            </article>
        </div>
    </section>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('static/admin/lib/jqueryValidation/messages_zh.js')}}"></script>
<script type="text/javascript">
    $('button:submit').click(function () {
        var form = $(this).parents('form');
        layer.confirm('确认保存吗？',function () {
            form.ajaxSubmit({
                dataType:'json',
                success: function (data) {
                    if (data.status == 0){
                        layer.close('hide');
                        layer.msg('保存成功',{icon:6,time:1000});
                        refresh();
                    }else {
                        layer.msg('保存失败',{icon:5,time:1000});
                    }
                }
            });
        });
        return false;
    });

    $('#tab-system').Huitab("#tab-system .tabBar span","#tab-system .tabCon","current","click","0");
    $('.switch').on('switch-change',function (e,data) {
        if (data.value){
            $(this).find('input:hidden').val('on');
        }else{
            $(this).find('input:hidden').val('off');
        }
    });

    function sendTest(obj) {
        $.ajax({
            method:'post',
            url:'{{url("admin/sendTest")}}',
            data:{'phone':$(obj).siblings().val()},
            dataType: 'json',
            success:function (data) {
                $('#testContent').text(data.message);
            },
            error:function (error) {
                console.log(error);
            }
        })
    }
</script>
@endsection
