<div class="main">
    {!! Theme::widget('breadcrumb')->render() !!}
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('report_file')}}" method="POST" lay-filter="fb-form">
                    <input type="hidden" name="report_id" value="{{ $report->id }}">
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('report_file.label.name') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="name" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" id="file_name" value="{{ $report->project->name }}报告单">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('app.file') }}</label>
                        {!! $report_file->files('url')
                        ->url($report_file->getReportFileURL('url'))
                        ->exts('jpg|jpeg|png|pdf|mp4|avi')
                        ->uploaderReportFile()!!}
                    </div>

                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="demo1">{{ trans('app.submit_now') }}</button>
                        </div>
                    </div>
                    {!!Form::token()!!}
                </form>
            </div>

        </div>
    </div>
</div>
{!! Theme::asset()->container('ueditor')->scripts() !!}
<script>
    var ue = getUe();

    layui.use(['form','jquery'], function(){
        var form = layui.form;
        var $ = layui.$;

    });

</script>
