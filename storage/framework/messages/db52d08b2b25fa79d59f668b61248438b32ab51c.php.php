<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Акции
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <h1>Редактирование акции</h1>

    <?php if(session('message-error')): ?>
        <div class="alert alert-danger">
            <?php echo e(session('message-error')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="form">
        <form method="post">
            <?php echo csrf_field(); ?>

            <input type="hidden" name="prev" value="<?php echo e($prev); ?>">
            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Общая информация</h4>
                    </div>
                    <div class="panel-body">
                        <?php echo $__env->make('admin.layouts.form.string', [
                         'label' => 'Название',
                         'key' => 'name',
                         'item' => $sale,
                         'required' => true
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        
                         
                         
                         
                        
                        <?php echo $__env->make('admin.layouts.form.editor', [
	                     'label' => 'Описание',
                         'key' => 'body',
                         'item' => $sale
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Превью</label>
                                <div class="form-element col-sm-2">
                                    <?php echo $__env->make('admin.layouts.form.image', [
                                     'key' => 'preview_id',
                                     'image' => $sale->preview
                                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                                
                                
                                        
                                         
                                         
                                        
                                
                                
                                
                                    
                                     
                                     
                                    
                                
                            </div>
                        </div>
                        
                         
                         
                         
                         
                        
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Настройки</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Время начала</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <input type="text" class="form-control from" name="show_from" value="<?php echo e(old('show_from') ? old('show_from') : date('Y/m/d H:i', strtotime($sale->show_from))); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Время окончания</label>
                                <div class="form-element col-sm-10">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <input type="text" class="form-control to" name="show_to" value="<?php echo e(old('show_to') ? old('show_to') : date('Y/m/d H:i', strtotime($sale->show_to))); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php echo $__env->make('admin.layouts.form.select', [
                         'label' => 'Статус',
                         'key' => 'status',
                         'options' => [(object)['id' => 0, 'name' => 'Отключено'], (object)['id' => 1, 'name' => 'Включено']],
                         'selected' => [old('status') ? old('status') : $sale->status]
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
                <?php if(!$sale->beauty): ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Товары участвующие в акции</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group" style="position: relative">
                            <?php if($user->hasAccess(['sales.update'])): ?>
                            <input type="text" name="search" class="form-control" id="live_search" value="" placeholder="Поиск">
                            <div id="live_search_results"></div>
                            <?php endif; ?>
                            <div id="in_action">
                                <?php echo $__env->make('admin.sales.products', ['products' => $products], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php echo $__env->make('admin.layouts.seo', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php if($user->hasAccess(['sales.update'])): ?>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <link rel="stylesheet" href="/css/larchik/jquery.datetimepicker.min.css" />
    <script src="/js/larchik/jquery.datetimepicker.full.min.js"></script>
    <script>
        jQuery(document).ready(function($){
            jQuery.datetimepicker.setLocale('ru');
            $('.from, .to').datetimepicker({
                datepicker:true,
                step:30
            });

            var live_search_output = $('#live_search_results');
            $('#live_search').keyup(function(){
                var search = $(this).val();
                var target = $(this).attr('data-target');
                live_search_output.html('').hide();

                if (search.length > 1) {
                    var data = {
                        search: search,
                        sale_id: <?php echo e($sale->id); ?>

                    };
                    $.ajax({
                        url: '/admin/products/livesearch',
                        data: data,
                        method: 'POST',
                        dataType: 'JSON',
                        success: function(resp) {
                            var html = '<ul>';
                            $.each(resp, function(i, value){
                                if (value.empty) {
                                    html += '<li>';
                                    html += value.empty;
                                    html += '</li>';
                                } else {
                                    html += '<li class="selectable" data-name="' + value.name + '" data-id="' + value.product_id + '">';
                                    html += '<img src="'+value.image+'">';
                                    html += '<div>';
                                    html += '<b>'+value.name+'</b>';
                                    html += ' ('+value.price+'грн)';
                                    html += '</div>';
                                    html += '<button type="button" class="btn btn-primary add-to-action" data-id="' + value.product_id + '">Добавить в акцию</button>';
                                    html += '</li>';
                                }
                            });
                            html += '</ul>';

                            $.each(live_search_output, function(i, value){
                                if($(value).attr('data-target') == target){
                                    $(value).html(html).show();
                                }
                            });
                        }
                    });
                } else {
                    live_search_output.hide();
                }
            });

            live_search_output.on('click', '.add-to-action', function(){
                var $this = $(this);
                var data = {
                    'sale_id': <?php echo e($sale->id); ?>,
                    'product_id': $this.data('id')
                };

                $.post('/admin/sales/add_product', data, function(response){
                    if(response.result == 'success'){
                        $('#in_action').html(response.html);
                        $this.parents('li').remove();
                    }
                });
            });

            $(document).on('click', '.remove-from-action', function(){
                var $this = $(this);
                var data = {
                    'sale_id': <?php echo e($sale->id); ?>,
                    'product_id': $this.data('id')
                };

                $.post('/admin/sales/remove_product', data, function(response){
                    if(response.result == 'success'){
                        $this.parents('tr').remove();
                    }
                });
            });
        });
    </script>

    <style>
        #live_search_results{
            position: absolute;
            background-color: #fff;
            max-height: 80vh;
            width: 100%;
            z-index: 2;
        }
        #live_search_results ul{
            list-style: none;
            padding: 0;
            border: 1px solid #ccc;
            margin: 0;
        }
        #live_search_results ul li{
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-right: 10px;
        }
        #live_search_results ul li img{
            height: 50px;
            width: 50px;
            object-fit: cover;
            margin-right: 15px;
        }
        #live_search_results ul li div{
            flex-grow: 1;
            margin-right: 15px;
        }
    </style>

    <?php echo $__env->make('admin.layouts.mce', ['editors' => $editors], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('before_footer'); ?>
    <?php echo $__env->make('admin.media.assets', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>