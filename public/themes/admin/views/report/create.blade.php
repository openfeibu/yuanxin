<div class="main">
    {!! Theme::widget('breadcrumb')->render() !!}
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('report')}}" method="POST" lay-filter="fb-form">
                    <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('report.label.status') }}</label>
                        <div class="layui-input-block">
                            <select name="status" lay-filter="checkBox" lay-verify="required">
                                @foreach(config('model.report.report.status') as $key => $status)
                                    <option value="{{ $status }}" >{{ trans('report.status.'.$status) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="demo1">下一步</button>
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
