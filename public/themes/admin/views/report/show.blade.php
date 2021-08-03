<div class="main">
    {!! Theme::widget('breadcrumb')->render() !!}
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('report/'.$report->id)}}" method="post" lay-filter="fb-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('project.label.name') }}</label>
                        <div class="layui-input-inline">
                           <p class="input-p">{{ $report->project->name }}</p>
                        </div>
                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('report.label.status') }}</label>
                        <div class="layui-input-block">
                            <select name="status" lay-filter="checkBox" lay-verify="required">
                                @foreach(config('model.report.report.status') as $key => $status)
                                    <option value="{{ $status }}" @if($report->status == $status)  selected @endif>{{ trans('report.status.'.$status) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="demo1">{{ trans('app.submit_now') }}</button>
                        </div>
                    </div>
                    {!!Form::token()!!}
                    <input type="hidden" name="_method" value="PUT">
                </form>
            </div>
        </div>
        <div class="layui-col-md12">
            <div class="tabel-message">
                <form class="layui-form" action="" lay-filter="fb-form">
                    <div class="layui-block mb10">
                        <div class="layui-inline tabel-btn">
                            <button class="layui-btn layui-btn-warm "><a href="{{guard_url('report_file/create?report_id='.$report->id)}}">{{ trans('app.add') }} {{ trans('report_file.name') }}</a></button>
                            <button class="layui-btn layui-btn-primary " data-type="del" data-events="del">{{ trans('app.delete') }}</button>
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
    <a class="layui-btn layui-btn-normal layui-btn-sm" href="@{{ d.original_url }}" target="_blank">查看文件</a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
</script>
<script type="text/html" id="imageTEM">
    <img src="@{{d.image}}" alt="" height="28">
</script>
<script>
    var main_url = "{{guard_url('report_file')}}";
    var delete_all_url = "{{guard_url('report_file/destroyAll')}}";
    layui.use(['jquery','element','table'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        table.render({
            elem: '#fb-table'
            ,url: '{{guard_url('report_file')}}?report_id={{ $report->id }}'
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'id',title:'ID', width:80, sort: true}
                ,{field:'name',title:'{{ trans('report_file.label.name') }}',edit:'text'}
                ,{field:'url',title:'{{ trans('report_file.label.url') }}', width:200,templet:'<div><a href="@{{ d.original_url }}" target="_blank">@{{ d.url }}</div>'}
                ,{field:'suffix',title:'{{ trans('report_file.label.suffix') }}', width:120}
                ,{field:'file_type_desc',title:'{{ trans('report_file.label.file_type') }}', width:200}
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