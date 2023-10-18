<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Redis
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <h1>Redis</h1>

    <?php if(session('message-success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('message-success')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php elseif(session('message-error')): ?>
        <div class="alert alert-danger">
            <?php echo e(session('message-error')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="panel-group">
        <div class="panel panel-default">
            
                
            
            <div class="panel-heading text-right">
                <span class="btn btn-primary" id="start_sync">Обновить кеш</span>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-2 text-right">Индекс товаров</label>
                        <div class="form-element col-sm-10">
                            <div id="products_progress" class="progress progress-striped active">
                                <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0">
                                    <span class="text">0% Complete</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-2 text-right">Индекс категорий</label>
                        <div class="form-element col-sm-10">
                            <div id="categories_progress" class="progress progress-striped active">
                                <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0">
                                    <span class="text">0% Complete</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-2 text-right">Индекс атрибутов</label>
                        <div class="form-element col-sm-10">
                            <div id="attributes_progress" class="progress progress-striped active">
                                <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0">
                                    <span class="text">0% Complete</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-2 text-right">Индекс акций</label>
                        <div class="form-element col-sm-10">
                            <div id="sales_progress" class="progress progress-striped active">
                                <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0">
                                    <span class="text">0% Complete</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <label class="col-sm-2 text-right">Поисковый индекс</label>
                        <div class="form-element col-sm-10">
                            <div id="search_progress" class="progress progress-striped active">
                                <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0">
                                    <span class="text">0% Complete</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            var products_pages = <?php echo e($products_pages); ?>;
            var categories_pages = <?php echo e($categories_pages); ?>;
            var attributes_pages = <?php echo e($attributes_pages); ?>;
            var sales_pages = <?php echo e($sales_pages); ?>;
            var search_pages = <?php echo e($products_pages); ?>;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function syncProducts(page){
                $.post('/admin/products/redis/progress', {action: 'products', page: page}, function(response){
                    updateProgress('products', Math.floor(page/products_pages * 100));
                    setTimeout(function(){
                        if(page < products_pages){
                            syncProducts(page + 1);
                        }else{
                            syncCategories(1);
                        }
                    }, 1000);
                });
            }

            function syncCategories(page){
                $.post('/admin/products/redis/progress', {action: 'categories', page: page}, function(response){
                    updateProgress('categories', Math.floor(page/categories_pages * 100));
                    setTimeout(function(){
                        if(page < categories_pages){
                            syncCategories(page + 1);
                        }else{
                            window.syncAttributes(1);
                        }
                    }, 1000);
                });
            }

            window.syncAttributes = function(page){
                $.post('/admin/products/redis/progress', {action: 'attributes', page: page}, function(response){
                    updateProgress('attributes', Math.floor(page/attributes_pages * 100));
                    setTimeout(function(){
                        if(page < attributes_pages){
                            window.syncAttributes(page + 1);
                        }else{
                            syncSales(1);
                        }
                    }, 1000);
                });
            };

            function syncSales(page){
                $.post('/admin/products/redis/progress', {action: 'sales', page: page}, function(response){
                    updateProgress('sales', Math.floor(page/sales_pages * 100));
                    setTimeout(function(){
                        if(page < sales_pages){
                            syncSales(page + 1);
                        }else{
                            syncSearch(1);
                        }
                    }, 1000);
                });
            }

            function syncSearch(page){
                $.post('/admin/products/redis/progress', {action: 'search', page: page}, function(response){
                    updateProgress('search', Math.floor(page/search_pages * 100));
                    setTimeout(function(){
                        if(page < search_pages){
                            syncSearch(page + 1);
                        }
                    }, 1000);
                });
            }

            function updateProgress(key, percent){
                $('#'+key+'_progress .progress-bar').css('width', percent+'%');
                $('#'+key+'_progress .text').text(percent+'% Complete');
            }

            $('#start_sync').click(function(){
                syncProducts(1);
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\milam.lh\resources\views/admin/products/redis/index.blade.php ENDPATH**/ ?>