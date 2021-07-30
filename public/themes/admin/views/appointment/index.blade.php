<div class="main">
    {!! Theme::widget('breadcrumb')->render() !!}
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="tabel-message">
                <form class="layui-form" action="" lay-filter="fb-form">
                    <div class="layui-block mb10">
                        <div class="layui-inline tabel-btn">
                            <button class="layui-btn layui-btn-primary " data-type="del" data-events="del">{{ trans('app.delete') }}</button>
                        </div>
                    </div>
                    <div class="layui-block table-search mb10">
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
    @{{# if(d.report_id){ }}
    <a class="layui-btn layui-btn-warm layui-btn-sm" >查看报告单</a>
    @{{# }else{  }}
    <a class="layui-btn layui-btn-normal layui-btn-sm" >出报告单</a>
    @{{# }  }}
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
                ,{field:'id',title:'ID', width:80, sort: true}
                ,{field:'number',title:'{{ trans('appointment.label.number') }}', width:100}
                ,{field:'project_name',title:'{{ trans('project.label.name') }}',templet:'<div>@{{ d.project.name }}</div>', width:200}
                ,{field:'name',title:'{{ trans('user.label.name') }}', width:100}
                ,{field:'phone',title:'{{ trans('user.label.phone') }}', width:120}
                ,{field:'idcard',title:'{{ trans('user.label.idcard') }}', width:200}
                ,{field:'date',title:'{{ trans('appointment.label.date') }}', width:150}
                ,{field:'start_time',title:'{{ trans('appointment.label.start_time') }}', width:100}
                ,{field:'end_time',title:'{{ trans('appointment.label.end_time') }}', width:100}
                ,{field:'note',title:'{{ trans('appointment.label.note') }}', width:200}
                ,{field:'score',title:'{{ trans('app.actions') }}', width:200, align: 'right',fixed: 'right',toolbar:'#barDemo',}
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
    });
</script>
{!! Theme::partial('common_handle_js') !!}