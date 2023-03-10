<div class="main">
    {!! Theme::widget('breadcrumb')->render() !!}
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">

            <div class="tabel-message">
                <div class="layui-inline tabel-btn">
                    <button class="layui-btn layui-btn-warm "><a href="{{guard_url('page/feature/create')}}">{{ trans('app.add') }}</a></button>
                    <button class="layui-btn layui-btn-primary " data-type="del" data-events="del">{{ trans('app.delete') }}</button>
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="title" id="demoReload" placeholder="{{ trans('app.search') }}{{ trans('page.label.title') }}" autocomplete="off">
                </div>
                <div class="layui-inline">
                    <button class="layui-btn" data-type="reload">{{ trans('app.search') }}</button>
                </div>
            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">

            </table>
        </div>
    </div>
</div>

<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.edit') }}</a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
</script>
<script type="text/html" id="imageTEM">
    <a href="@{{d.image}}" target="_blank"><img src="@{{d.sm_image}}" alt="" height="28"></a>
</script>

<script>
    var main_url = "{{guard_url('page/feature')}}";
    var delete_all_url = "{{guard_url('page/feature/destroyAll')}}";
    layui.use(['jquery','element','table'], function(){
        var table = layui.table;
        var form = layui.form;
        var $ = layui.$;
        table.render({
            elem: '#fb-table'
            ,url: main_url
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'id',title:'ID', width:80, sort: true}
                ,{field:'title',title:'{{ trans('page.label.title') }}',edit:'text' }
                ,{field:'link',title:'{{ trans('project.name') }}{{ trans('app.link') }}',width:150}
                ,{field:'description',title:'{{ trans('app.description') }}',edit:'text'}
                ,{field:'image',title:'{{ trans('page.label.image') }}', toolbar:'#imageTEM',width:100}
                ,{field:'order',title:'{{ trans('app.order') }}', width:80, sort: true,edit:'text'}
                ,{field:'updated_at',title:'{{ trans('app.updated_at') }}', width:200}
                ,{field:'score',title:'{{ trans('app.actions') }}', width:200, align: 'right',fixed: 'right',toolbar:'#barDemo'}
            ]]
            ,id: 'fb-table'
            ,page: true
            ,limit: '{{ config('app.limit') }}'
            ,height: 'full-200'
            ,cellMinWidth:200
            ,error: function(e,t){
                console.log(e);
                $.ajax_table_error(e);
            }
        });

    });
</script>
{!! Theme::partial('common_handle_js') !!}