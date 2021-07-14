<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="<?php echo e(route('home')); ?>">主页</a><span lay-separator="">/</span>
            <a><cite>添加<?php echo e(trans('permission.name')); ?></cite></a>
        </div>
    </div>
    <div class="main_full">
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="<?php echo e(guard_url('permission/'.$permission->id)); ?>" method="post" lay-filter="fb-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo e(trans('permission.label.name')); ?></label>
                        <div class="layui-input-inline">
                            <input type="text" name="name" lay-verify="name" autocomplete="off" placeholder="请输入<?php echo e(trans('permission.label.name')); ?>" class="layui-input" value="<?php echo e($permission->name); ?>">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo e(trans('permission.label.slug')); ?></label>
                        <div class="layui-input-inline">
                            <input type="text" name="slug" lay-verify="slug" autocomplete="off" placeholder="请输入<?php echo e(trans('permission.label.slug')); ?>" class="layui-input" value="<?php echo e($permission->slug); ?>">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo e(trans('permission.label.icon')); ?></label>
                        <div class="layui-input-inline">
                            <input type="text" name="icon" lay-verify="icon" autocomplete="off" placeholder="请输入<?php echo e(trans('permission.label.icon')); ?>" class="layui-input" value="<?php echo e($permission->icon); ?>">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo e(trans('permission.label.order')); ?></label>
                        <div class="layui-input-inline">
                            <input type="text" name="order" lay-verify="order" autocomplete="off" placeholder="请输入<?php echo e(trans('permission.label.order')); ?>" class="layui-input" value="<?php echo e($permission->order); ?>">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">是否菜单</label>
                        <div class="layui-input-block">
                            <input type="checkbox" name="is_menu_switch" lay-skin="switch" lay-filter="is_menu_switch" lay-text="菜单|否" <?php if($permission->is_menu): ?> checked <?php endif; ?>>
                            <input type="hidden" name="is_menu" id="is_menu" value="<?php echo e($permission->is_menu); ?>">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">所属权限组</label>
                        <div class="layui-input-block">
                            <select name="parent_id">
                                <option value="0">顶级权限</option>
                                <?php $__currentLoopData = $father; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $father): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($father->id); ?>" <?php if($permission->parent_id == $father->id): ?> selected <?php endif; ?>><?php echo e($father->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
                        </div>
                    </div>
                    <?php echo Form::token(); ?>

                    <input type="hidden" name="_method" value="PUT">
                </form>
            </div>

        </div>
    </div>
</div>
<script>
    layui.use(['form','jquery'], function(){
        var form = layui.form;
        var $ = layui.$;
        form.on('switch(is_menu_switch)', function(data){
            if(data.elem.checked == true)
            {
                $("#is_menu").val('1');
            }else{
                $("#is_menu").val('0');
            }
            form.render();
        });
    });
</script>