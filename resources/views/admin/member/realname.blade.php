@extends('layout.admin-master')
@section('tittle')实名认证列表 @endsection
@section('css')
    <style>
        .preview{position: relative}
        .preview div{width: 300px;height:330px;position: absolute;left: -305px;top: -45px;z-index: 9}
        .preview div img{width: 100%;height: 100%}
    </style>
@endsection

@section('header')
    @component('layout.admin-header')@endcomponent
@endsection

@section('aside')
    @component('layout.admin-menu')@endcomponent
@endsection

@section('container')
    <section class="Hui-article-box">
        <nav class="breadcrumb">
            <a class="btn btn-success radius l" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a> </nav>
        <div class="Hui-article">
            <article class="cl pd-20">
                <form action="{{url('admin/memberRealName')}}" method="post">
                    @csrf
                <div class="text-c">
                    <span class="select-box inline">
                        <select name="auth_status" class="select">
                            <option value="-1">所有状态</option>
                            <option value="0" {{old('auth_status')=='0'?'selected':''}}>未认证</option>
                            <option value="1" {{old('auth_status')=='1'?'selected':''}}>已认证</option>
                            <option value="2" {{old('auth_status')=='2'?'selected':''}}>审核未通过</option>
                            <option value="3" {{old('auth_status')=='3'?'selected':''}}>审核中</option>
                        </select>
                    </span>
                    <input type="text" name="account" value="{{old('account')}}" placeholder="会员账号" style="width:200px" class="input-text">
                    <input type="text" name="alipay" value="{{old('alipay')}}" placeholder="会员支付宝" style="width:200px" class="input-text">
                    <input type="text" name="weixin" value="{{old('credit')}}" placeholder="会员微信" style="width:200px" class="input-text">
                    <button class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> 查找</button>
                </div>
                </form>
                <div class="cl pd-5 bg-1 bk-gray mt-20">
                    <span class="l">共有数据：<strong>{{count($realnames)}}</strong> 条</span>
                </div>
                <table class="table table-border table-bordered table-bg table-sort">
                    <thead>
                        <tr>
                            <th scope="col" colspan="12">实名认证列表</th>
                        </tr>
                        <tr class="text-c">
                            <th width="25"><input type="checkbox"></th>
                            <th width="80">账号</th>
                            <th>姓名</th>
                            <th>身份证</th>
                            <th>微信</th>
                            <th>支付宝</th>
                            <th>银行名称</th>
                            <th>银行卡号</th>
                            <th>身份证正面</th>
                            <th>身份证背面</th>
                            <th width="50">状态</th>
                            <th width="80">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(!$realnames->isEmpty())
                    @foreach($realnames as $r)
                        <tr class="text-c">
                            <td><input type="checkbox" value="{{$r->id}}" class="checkBox"></td>
                            <td>{{$r->member->phone}}</td>
                            <td>{{$r->name}}</td>
                            <td>{{$r->idcard}}</td>
                            <td>{{$r->weixin}}</td>
                            <td>{{$r->alipay}}</td>
                            <td>{{$r->bank_name}}</td>
                            <td>{{$r->bank_card}}</td>
                            <td><a href="javascript:;" class="preview" data-src="{{asset('storage').$r->idcard_front_img}}">
                                    <img src="{{asset('storage').$r->idcard_front_img}}" width="100"></a>
                            </td>
                            <td><a href="javascript:;" class="preview" data-src="{{asset('storage').$r->idcard_back_img}}">
                                    <img src="{{asset('storage').$r->idcard_back_img}}" width="100"></a></td>
                            <td class="td-status">
                                <span class="label radius {{$r->auth_status==\App\Http\Models\RealNameAuths::AUTH_SUCCESS?'label-success':'label-danger'}}">{{$r->getAuthStatusDesc($r->auth_status)}}</span>
                            </td>
                            <td class="f-14 td-manage">
                                @if(session('permission') == 0 || in_array("admin/memberRealNameCheckEdit",session('permission')))
                                    <a title="审核" href="javascript:;" onclick="realName_check(this,'{{$r->id}}')" class="ml-5" style="text-decoration:none">审核</a>
                                @endif
                                @if(session('permission') == 0 || in_array("admin/memberRealNameEdit",session('permission')))
                                    <a title="编辑" href="javascript:;" onclick="edit('编辑','{{url("admin/memberRealNameEdit")}}','{{$r->id}}','800','600')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
                                @endif
                                @if(session('permission') == 0 || in_array("admin/memberRealNameDel",session('permission')))
                                    <a title="删除" href="javascript:;" onclick="onesDel(this,'{{url("admin/memberRealNameDel")}}','{{$r->id}}')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @endif
                    </tbody>
                </table>
            </article>
        </div>
    </section>
@endsection

@section('js')
<script type="text/javascript" src="{{asset('static/admin/lib/dataTables/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{asset('static/admin/lib/layerPage/laypage.js')}}"></script>
<script type="text/javascript">

    $('.table-sort').dataTable({
        "aaSorting": [[ 1, "asc" ]],//默认第几个排序
        "bStateSave": true,//状态保存
        "aoColumnDefs": [
            //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
            {"orderable":false,"aTargets":[0,8,9,11]}// 制定列不参与排序
        ]
    });

    function realName_check(obj,id) {
        layer.confirm('审核实名认证？', {
                btn: ['通过','不通过','取消'],
                shade: false,
                closeBtn: 0
            },
            function(){
                $.post('{{url("admin/memberRealNameCheckEdit")}}',{'id':id,'auth_status':1});
                $(obj).parents("tr").find(".td-manage").prepend('<a class="c-primary" onClick="realName_check(this,id)" href="javascript:;" title="审核">审核</a>');
                $(obj).parents("tr").find(".td-status").html('<span class="label label-success radius">已认证</span>');
                $(obj).remove();
                layer.msg('已认证', {icon:6,time:1000});
            },
            function(){
                $.post('{{url("admin/memberRealNameCheckEdit")}}',{'id':id,'auth_status':0});
                $(obj).parents("tr").find(".td-manage").prepend('<a class="c-primary" onClick="realName_check(this,id)" href="javascript:;" title="审核">审核</a>');
                $(obj).parents("tr").find(".td-status").html('<span class="label label-danger radius">未通过</span>');
                $(obj).remove();
                layer.msg('未通过', {icon:5,time:1000});
            });
    }

    var preview = $('.preview');
    preview.on('mouseover',function () {
        $(this).append('<div><img src="'+$(this).attr("data-src")+'"></div>')
    });
    preview.on('mouseout',function () {
        $(this).find('div').remove();
    });
</script>
@endsection
