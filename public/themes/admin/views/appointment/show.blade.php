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

                    {!!Form::token()!!}
                    <input type="hidden" name="_method" value="PUT">
                </form>
            </div>

        </div>
    </div>
</div>

{!! Theme::asset()->container('ueditor')->scripts() !!}
<script>

</script>