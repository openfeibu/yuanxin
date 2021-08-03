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
                            <input class="layui-input search_key" name="name" placeholder="{{ trans('user.label.name') }}" autocomplete="off">
                        </div>
                        <div class="layui-inline">
                            <input class="layui-input search_key" name="phone" placeholder="{{ trans('user.label.phone') }}" autocomplete="off">
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
    <a class="layui-btn layui-btn-normal layui-btn-sm" lay-event="edit">{{ trans('app.details') }} / {{ trans('report_file.name') }}</a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
</script>
<script type="text/html" id="imageTEM">
    <img src="@{{d.image}}" alt="" height="28">
</script>
<script>
    var main_url = "{{guard_url('report')}}";
    var delete_all_url = "{{guard_url('report/destroyAll')}}";
    layui.use(['jquery','element','table'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        table.render({
            elem: '#fb-table'
            ,url: '{{guard_url('report')}}'
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'id',title:'ID', width:80, sort: true}
                ,{field:'project_name',title:'{{ trans('project.label.name') }}',templet:'<div>@{{ d.project.name }}</div>'}
                ,{field:'name',title:'{{ trans('user.label.name') }}', width:100}
                ,{field:'phone',title:'{{ trans('user.label.phone') }}', width:120}
                ,{field:'idcard',title:'{{ trans('user.label.idcard') }}', width:200}
                ,{field:'status_desc',title:'{{ trans('report.label.status') }}', width:200,templet:"<div> @{{# if(d.status =='good'){ }} <span class='layui-green'> @{{#}else{  }} <span class='layui-red'> @{{# }  }} @{{ d.status_desc }}</span></div>"}
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