<div class="main">
    {!! Theme::widget('breadcrumb')->render() !!}
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('appointment/'.$appointment->id)}}" method="post" lay-filter="fb-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('appointment.label.number') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p">{{ $appointment->number }}</p>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('project.label.name') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p">{{ $appointment->project->name }}</p>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('user.label.phone') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p">{{ $appointment->phone }}</p>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('user.label.name') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p">{{ $appointment->name }}</p>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('user.label.idcard') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p">{{ $appointment->idcard }}</p>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('appointment.label.date') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p">{{ $appointment->date }} {{ $appointment->start_time }} ~ {{ $appointment->end_time }}</p>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('appointment.label.status') }}</label>
                        <div class="layui-input-inline">
                            <p class="input-p">
                                <span  @if($appointment->status == 'check') class='layui-green' @else   class='layui-red' @endif>{{ trans('appointment.status.'.$appointment->status) }}</span>
                            </p>
                        </div>
                    </div>


                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            @if($appointment->status == 'unchecked')
                                <button class="layui-btn layui-btn-normal" lay-submit lay-filter="check">核销</button>
                            @else
                                @if($appointment->report_id)
                                    <button class="layui-btn layui-btn-warm" lay-filter="check"><a href="{{ guard_url('report') }}/{{ $appointment->report_id }}">查看报告单</a></button>

                                @else
                                    <button class="layui-btn layui-btn-normal" lay-filter="check"><a href="{{ guard_url('report/create?appointment_id='.$appointment->id) }}">出报告单</a></button>
                                @endif
                            @endif

                        </div>
                    </div>
                    {!!Form::token()!!}
                    <input type="hidden" name="_method" value="PUT">
                </form>
            </div>

        </div>
    </div>
</div>

{!! Theme::asset()->container('ueditor')->scripts() !!}
<script>

    layui.use(['jquery','element','table'], function() {
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        form.on('submit(check)', function(data){
            var code = "{!! Request::get('code') ?? '' !!}";
            var index = layer.prompt({title: '请输入验证码，该操作无法撤回，确定体检人已到场吗？', formType: 0,value:code}, function(code, index){
                $('#layui-layer'+index + " .layui-layer-input").val("{!! Request::get('code') ?? '' !!}")
                var load = layer.load();
                $.ajax({
                    url : "{{ guard_url('appointment/check') }}",
                    data :  {'id':"{{ $appointment->id }}",'code':code,'_token' : "{!! csrf_token() !!}"},
                    type : 'POST',
                    success : function (data) {
                        layer.close(load);
                        if(data.code == 0)
                        {
                            layer.msg(data.message);
                            window.location.reload();
                        }else{
                            layer.msg(data.message);
                        }
                    },
                    error : function (jqXHR, textStatus, errorThrown) {
                        layer.close(load);
                        $.ajax_error(jqXHR, textStatus, errorThrown);
                    }
                });
            });

            return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
        });
    });
</script>