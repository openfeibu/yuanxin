<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">主页</a><span lay-separator="">/</span>
            <a><cite>{{ trans("user.name") }}管理</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="tabel-message">
                <form class="layui-form" action="" lay-filter="fb-form">
                    <div class="layui-block mb10 table-search">
                        <div class="layui-inline">
                            <input class="layui-input search_key" name="nickname" id="demoReload" placeholder="{{ trans('user.label.nickname') }}" autocomplete="off">
                        </div>
                        <div class="layui-inline">
                            <input class="layui-input search_key" name="phone" id="demoReload" placeholder="{{ trans('user.label.phone') }}" autocomplete="off">
                        </div>

                        <div class="layui-inline">
                            <button class="layui-btn" data-type="reload" type="button">{{ trans('app.search') }}</button>

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
    <a class="layui-btn layui-btn-sm" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">删除</a>
</script>
<script type="text/html" id="avatar_url_tem">
    <img src="@{{d.avatar_url}}" alt="" height="28">
</script>


<script>
    var main_url = "{{guard_url('user')}}";
    var delete_all_url = "{{guard_url('user/destroyAll')}}";
    layui.use(['jquery','element','table'], function(){
        var table = layui.table;
        var form = layui.form;
        var $ = layui.$;
        table.render({
            elem: '#fb-table'
            ,url: '{{guard_url('user')}}'
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'id',title:'ID', width:80, sort: true}
                ,{field:'nickname',title:'{!! trans('user.label.nickname')!!}'}
                ,{field:'avatar_url',title:'{!! trans('user.label.avatar_url')!!}',toolbar:'#avatar_url_tem'}
                ,{field:'phone',title:'{!! trans('user.label.phone')!!}'}
                ,{field:'created_at',title:'{{ trans('user.label.created_at') }}'}
            ]]
            ,id: 'fb-table'
            ,page: true
            ,limit: 10
            ,height: 'full-200'
        });


    });
</script>
{!! Theme::partial('common_handle_js') !!}
