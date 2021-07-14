<div class="layui-card fb-minNav">
    <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
        <a href="<?php echo e(route('home')); ?>">主页</a><span lay-separator="">/</span>
        <?php $__currentLoopData = $breadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $breadcrumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($key+1 == $count): ?>
                <a><cite><?php echo e($breadcrumb->name); ?></cite></a>
            <?php else: ?>
                <a <?php if($breadcrumb->slug && Route::has($breadcrumb->slug)): ?>href="<?php echo e(route($breadcrumb->slug)); ?>"<?php endif; ?>><cite><?php echo e($breadcrumb->name); ?></cite></a><span lay-separator="">/</span>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>