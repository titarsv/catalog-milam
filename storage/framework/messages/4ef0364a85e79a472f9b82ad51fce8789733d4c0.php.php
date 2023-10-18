<?php $file_name = $file_info[$key]['name'];?>
<?php if($type == 'Images'): ?>
<?php $thumb_src = $thumb_url . $file_name;?>
<?php endif; ?>

<div class="col-sm-4 col-md-3 col-lg-2 img-row">

  <div class="thumbnail thumbnail-img text-center" data-id="<?php echo e($file_name); ?>" id="img_thumbnail_<?php echo e($key); ?>">
    <?php if($type == 'Images'): ?>
    <img id="<?php echo e($file_name); ?>" src="<?php echo e(asset($thumb_src)); ?>" alt="" class="pointer" onclick="useFile('<?php echo e($file_name); ?>')">
    <?php else: ?>
    <i class="fa <?php echo e($file['icon']); ?> fa-5x" style="height:200px;cursor:pointer;padding-top:60px;" onclick="useFile('<?php echo e($file_name); ?>')"></i>
    <?php endif; ?>
  </div>

  <div class="caption text-center">
    <div class="btn-group">
      <button type="button" onclick="useFile('<?php echo e($file_name); ?>')" class="btn btn-default btn-xs">
        <?php echo e(str_limit($file_name, $limit = 10, $end = '...')); ?>

      </button>
      <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
      </button>
      <ul class="dropdown-menu" role="menu">
        <li><a href="javascript:rename('<?php echo e($file_name); ?>')"><i class="fa fa-edit fa-fw"></i> <?php echo e(Lang::get('laravel-filemanager::lfm.menu-rename')); ?></a></li>
        <li><a href="javascript:download('<?php echo e($file_name); ?>')"><i class="fa fa-download fa-fw"></i> <?php echo e(Lang::get('laravel-filemanager::lfm.menu-download')); ?></a></li>
        <li class="divider"></li>
        <?php if($type == 'Images'): ?>
        <li><a href="javascript:fileView('<?php echo e($file_name); ?>')"><i class="fa fa-image fa-fw"></i> <?php echo e(Lang::get('laravel-filemanager::lfm.menu-view')); ?></a></li>
        
        <li><a href="javascript:resizeImage('<?php echo e($file_name); ?>')"><i class="fa fa-arrows fa-fw"></i> <?php echo e(Lang::get('laravel-filemanager::lfm.menu-resize')); ?></a></li>
        <li><a href="javascript:cropImage('<?php echo e($file_name); ?>')"><i class="fa fa-crop fa-fw"></i> <?php echo e(Lang::get('laravel-filemanager::lfm.menu-crop')); ?></a></li>
        <li class="divider"></li>
        <?php endif; ?>
        <li><a href="javascript:trash('<?php echo e($file_name); ?>')"><i class="fa fa-trash fa-fw"></i> <?php echo e(Lang::get('laravel-filemanager::lfm.menu-delete')); ?></a></li>
      </ul>
    </div>
  </div>
</div>
