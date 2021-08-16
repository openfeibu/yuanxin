<div class="main">
    {!! Theme::widget('breadcrumb')->render() !!}
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('page/feature/'.$page->id)}}" method="post" lay-filter="fb-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label">* {{ trans('page.label.title') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="title" lay-verify="required" autocomplete="off" placeholder="请输入{{ trans('page.label.title') }}" class="layui-input" value="{{$page->title}}">
                        </div>
                    </div>
                    <div class="layui-form-item fb-form-item">
                        <label class="layui-form-label">关联项目 </label>
                        <div class="layui-input-block">
                            @inject('projectRepository','App\Repositories\Eloquent\ProjectRepository')
                            <select name="link" id="link" lay-filter="" lay-search>
                                <option value="">请选择项目</option>
                                @foreach($projectRepository->getProjects() as $key => $project)
                                    <option value="{{ config('common.project_weapp_link') }}{{ $project->id }}" @if(config('common.project_weapp_link').$project->id == $page->link) selected @endif>{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('app.description') }}</label>
                        <div class="layui-input-inline">
                            <textarea name="description" class="layui-textarea">{{ $page->description }}</textarea>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('page.label.image') }}</label>
                        {!! $page->files('image')
                        ->url($page->getUploadUrl('image'))
                        ->uploader()!!}
                    </div>

                    <div class="layui-form-item button-group"><div class="layui-input-block"><button class="layui-btn layui-btn-normal layui-btn-lg" lay-submit="" lay-filter="demo1">{{ trans('app.submit_now') }}</button></div></div>
                    {!!Form::token()!!}
                    <input type="hidden" name="_method" value="PUT">
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