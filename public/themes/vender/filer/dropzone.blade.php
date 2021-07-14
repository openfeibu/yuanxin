<input type="hidden" name="{!!$field!!}" id="path_{!!$field!!}" value="@if($files){{$files['path']}}@endif"/>
<div class="layui-input-block">
    <div class="layui-upload-drag" id="uploadImage_{!!$field!!}">

            <i class="layui-icon layui-icon-upload-drag"></i>
            <p>点击上传，或将文件拖拽到此处</p>

            @if($files)
            <hr/>
            <div class="">

                <img id="image_{!!$field!!}" src="{!!url("/image/original".$files['path'])!!}" style="max-width: 196px">
            </div>
            @else
            <hr/>
            <div class="layui-hide">
                <img id="image_{!!$field!!}" src="" style="max-width: 196px">
            </div>
            @endif

    </div>
</div>
<script>
    layui.use(['jquery','element','form','table','upload'], function(){
        var $ = layui.$;
        var form = layui.form;
        var upload = layui.upload;
        upload.render({
            elem: '#uploadImage_{!!$field!!}'
            ,accept:'images'
            ,url: '{!! $url !!}'
            ,data: {
                '_token':$('meta[name="csrf-token"]').attr('content')
            }
            ,before: function(obj){ //obj参数包含的信息，跟 choose回调完全一致，可参见上文。
                layer.load(); //上传loading
                obj.preview(function(index, file, result){
                    $('#image_{!!$field!!}').show().attr('src', result); //图片链接（base64）
                });
            }
            ,done: function(res, index, upload){
                console.log(res)
                $('.layui-upload-view').removeClass('layui-hide');
                $("#path_{!!$field!!}").val(res.data.path);
                layer.closeAll('loading'); //关闭loading
                layer.msg(res.message);

            }
            ,error: function(index, upload){
                layer.closeAll('loading'); //关闭loading
            }
        });
    });
</script>