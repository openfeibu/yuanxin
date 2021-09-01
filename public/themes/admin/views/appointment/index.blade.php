<div class="main">
    {!! Theme::widget('breadcrumb')->render() !!}
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">

        </div>
        <div class="layui-col-md12">
            
            <div class="tabel-message">
			 <form class="layui-form appointment_code_form" action="" method="post" lay-filter="appointment_code_form">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                           
                            <div class="layui-input-inline">
                                <input placeholder="请输入验证码" type="text" name="code" autocomplete="off" class="layui-input" id="code">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <button class="layui-btn layui-btn-normal" lay-submit lay-filter="search_code">验证</button>
                        </div>
                    </div>
                </form>
                <form class="layui-form" action="" lay-filter="fb-form">
                    
                    <div class="layui-block table-search mb10">
					 <div class="layui-inline tabel-btn">
                            <button class="layui-btn layui-btn-primary " type="button" data-type="del" data-events="del">{{ trans('app.delete') }}</button>
                        </div>
                        <div class="layui-inline">
                            <select name="project_id" class="search_key layui-select">
                                @inject('projectRepository','App\Repositories\Eloquent\ProjectRepository')
                                <option value="">{{ trans('project.name') }}</option>
                                @foreach($projectRepository->orderBy('order','asc')->orderBy('id','asc')->get() as $key => $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="layui-inline">
                            <select name="status" class="search_key layui-select">
                                <option value="">{{ trans('appointment.label.status') }}</option>
                                @foreach(config('model.appointment.appointment.status') as $key => $status)
                                    <option value="{{ $status }}">{{ trans('appointment.status.'.$status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="layui-inline">
                            <input class="layui-input search_key" name="number" placeholder="{{ trans('appointment.label.number') }}" autocomplete="off">
                        </div>
                        <div class="layui-inline">
                            <input class="layui-input search_key" name="name" placeholder="{{ trans('user.label.name') }}" autocomplete="off">
                        </div>

                        <div class="layui-inline">
                            <button class="layui-btn" type="button" data-type="reload">{{ trans('app.search') }}</button>
                        </div>
                    </div>
                </form>
            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">

            </table>
        </div>
    </div>
</div>
<script type="text/html" id="barDemo">
    @{{# if(d.status == 'unchecked'){ }}
        <a class="layui-btn layui-btn-normal layui-btn-sm" lay-event="check">核销</a>
    @{{# }else{  }}
        @{{# if(d.report_id){ }}
        <a class="layui-btn layui-btn-warm layui-btn-sm" href="{{ guard_url('report') }}/@{{ d.report_id }}">查看报告单</a>
        @{{# }else{  }}
        <a class="layui-btn layui-btn-normal layui-btn-sm" href="{{ guard_url('report/create') }}?appointment_id=@{{ d.id }}">出报告单</a>
        @{{# }  }}
    @{{# }  }}
    <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.details') }}</a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
</script>
<script type="text/html" id="imageTEM">
    <img src="@{{d.image}}" alt="" height="28">
</script>
<script>
    var main_url = "{{guard_url('appointment')}}";
    var delete_all_url = "{{guard_url('appointment/destroyAll')}}";
    layui.use(['jquery','element','table'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        table.render({
            elem: '#fb-table'
            ,url: '{{guard_url('appointment')}}'
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'id',title:'ID', width:80, sort: true,fixed: 'left'}
                ,{field:'number',title:'{{ trans('appointment.label.number') }}', width:100,fixed: 'left'}
                ,{field:'project_name',title:'{{ trans('project.label.name') }}',templet:'<div>@{{ d.project.name }}</div>', width:200}
                ,{field:'name',title:'{{ trans('user.label.name') }}', width:100}
                ,{field:'phone',title:'{{ trans('user.label.phone') }}', width:120}
                ,{field:'idcard',title:'{{ trans('user.label.idcard') }}', width:200}
                ,{field:'date',title:'{{ trans('appointment.label.date') }}', width:150}
                ,{field:'start_time',title:'{{ trans('appointment.label.start_time') }}', width:100}
                ,{field:'end_time',title:'{{ trans('appointment.label.end_time') }}', width:100}
                ,{field:'status',title:'{{ trans('appointment.label.status') }}', width:100,templet:"<div> @{{# if(d.status =='check'){ }} <span class='layui-green'> @{{#}else{  }} <span class='layui-red'> @{{# }  }} @{{ d.status_desc }}</span></div>"}
                ,{field:'note',title:'{{ trans('appointment.label.note') }}', width:200}
                ,{field:'score',title:'{{ trans('app.actions') }}', width:230, align: 'right',fixed: 'right',toolbar:'#barDemo',}
            ]]
            ,id: 'fb-table'
            ,page: true
            ,limit: '{{ config('app.limit') }}'
            ,height: 'full-200'
            ,error: function(e,t){
                console.log(e);
                $.ajax_table_error(e);
            }
        });
        $.extend_tool = function (obj) {
            var data = obj.data;
            data['_token'] = "{!! csrf_token() !!}";
            data['nPage'] = $(".layui-laypage-curr em").eq(1).text();

            appointment_handle[obj.event] ? appointment_handle[obj.event].call(this,data) : '';
        }
        appointment_handle = {
            check: function (obj) {
                layer.prompt({title: '请输入验证码，该操作无法撤回，确定体检人已到场吗？', formType: 0}, function(text, index){
                    var load = layer.load();
                    $.ajax({
                        url : "{{ guard_url('appointment/check') }}",
                        data :  {'id':obj['id'],'code':text,'_token' : "{!! csrf_token() !!}"},
                        type : 'POST',
                        success : function (data) {
                            layer.close(load);
                            if(data.code == 0)
                            {
                                var nPage = $(".layui-laypage-curr em").eq(1).text();
                                //执行重载
                                table.reload('fb-table', {
                                    page: {
                                        curr: nPage //重新从第 1 页开始
                                    }
                                });
                                layer.close(index);
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

            },
        }
        form.on('submit(search_code)', function(data){
            var load = layer.load();
            var code = $('.appointment_code_form').find('#code').val();
            $.ajax({
                url : "{{ guard_url('appointment/search_code') }}",
                data :  {'code':code,'_token' : "{!! csrf_token() !!}"},
                type : 'POST',
                success : function (data) {
                    layer.close(load);
                    if(data.code == 0)
                    {
                        layer.msg(data.message);
                        layer.load();
                        window.location.href=data.url;
                    }else{
                        layer.msg(data.message);
                    }
                },
                error : function (jqXHR, textStatus, errorThrown) {
                    layer.close(load);
                    $.ajax_error(jqXHR, textStatus, errorThrown);
                }
            });
            return false;
        });
    });
</script>
{!! Theme::partial('common_handle_js') !!}