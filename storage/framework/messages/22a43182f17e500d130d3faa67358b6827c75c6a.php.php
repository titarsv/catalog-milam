<div class="panel-group">
    <div class="panel panel-default">
        <table class="table table-hover table-condensed">
            <thead>
                <tr class="success">
                    <td align="center" style="min-width: 100px">Фото</td>
                    <td>Артикул</td>
                    <td>Цена</td>
                    <td style="max-width: 220px;">Название</td>
                    <td align="center" class="hidden-xs">Категория</td>
                    <td align="center">Наличие</td>
                    <td align="center">Действия</td>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr id="product-<?php echo e($product->id); ?>">
                        <td align="center">
                            <?php if(!empty($product->image)): ?>
                            <img src="<?php echo e($product->image->url([100, 100])); ?>"
                                 alt="<?php echo e($product->image->title); ?>"
                                 class="img-thumbnail">
                            <?php else: ?>
                                <img src="/uploads/no_image.jpg"
                                     alt="no_image"
                                     class="img-thumbnail">
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($product->sku); ?></td>
                        <td><?php echo e($product->original_price); ?></td>
                        <td style="max-width: 220px;white-space: normal;"><?php echo e($product->name); ?></td>
                        <td align="center" class="hidden-xs product-categories">
                            <?php $__currentLoopData = $product->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="product-category category-<?php echo e($category->id); ?>"><?php echo e($category->name); ?></span><br>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                        <td class="status" align="center">
                            <span class="<?php echo $product->stock ? 'on' : 'off'; ?>" data-id="<?php echo e($product->id); ?>" style="cursor: pointer;">
                                <span class="runner"></span>
                            </span>
                        </td>
                        <td class="actions" align="center">
                            <a class="btn btn-primary" href="/admin/products/edit/<?php echo e($product->id); ?>" target="_blank">
                                <i class="glyphicon glyphicon-edit"></i>
                            </a>
                            <button type="button" class="btn btn-danger remove-from-action" data-id="<?php echo e($product->id); ?>">
                                <i class="glyphicon glyphicon-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" align="center">Нет добавленных товаров!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php if($products->count()): ?>
        <div class="panel-footer text-right">
            <?php echo e($products->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>