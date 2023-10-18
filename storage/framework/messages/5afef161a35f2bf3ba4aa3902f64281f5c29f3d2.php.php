<?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('title'); ?>
    Запись блога
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <h1>Редактирование статьи <?php echo e($article->name); ?></h1>

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

    <div class="form">
        <form method="post">
            <?php echo csrf_field(); ?>

            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Общая информация</h4>
                    </div>
                    <div class="panel-body">
                        <?php echo $__env->make('admin.layouts.form.string', [
                         'label' => 'Название',
                         'key' => 'name',
                         'locale' => 'ru',
                         'required' => true,
                         'item' => $article,
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Изображение</label>
                                <div class="form-element col-sm-3">
                                    <?php echo $__env->make('admin.layouts.form.image', [
                                     'key' => 'file_id',
                                     'image' => $article->image
                                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                            </div>
                        </div>
                        <?php echo $__env->make('admin.layouts.form.editor', [
                         'label' => 'Текст статьи',
                         'key' => 'body',
                         'locale' => 'ru',
                         'item' => $article,
                         'languages' => $languages
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Настройки</h4>
                    </div>
                    <div class="panel-body">
                        <?php echo $__env->make('admin.layouts.form.select', [
                         'label' => 'Статус',
                         'key' => 'published',
                         'options' => [(object)['id' => 0, 'name' => 'Отключено'], (object)['id' => 1, 'name' => 'Включено']],
                         'selected' => [old('published') ? old('published') : $article->published]
                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Связанные товары</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group" style="position: relative">
                            <input type="text" name="search" class="form-control" id="live_search" value="" placeholder="Поиск">
                            <div id="live_search_results"></div>
                            <div id="in_action">
                                <?php echo $__env->make('admin.modules.products', ['products' => $products], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo $__env->make('admin.layouts.seo', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php if($user->hasAccess(['news.update'])): ?>
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

    <script>
        jQuery(document).ready(function($){
            var live_search_output = $('#live_search_results');
            $('#live_search').keyup(function(){
                var search = $(this).val();
                var target = $(this).attr('data-target');
                live_search_output.html('').hide();

                if (search.length > 1) {
                    var data = {
                        search: search,
                        news_id: <?php echo e($article->id); ?>

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
                                    html += '<button type="button" class="btn btn-primary add-to-action" data-id="' + value.product_id + '">Прикрепить к статье</button>';
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
                    'news_id': <?php echo e($article->id); ?>,
                    'product_id': $this.data('id')
                };

                $.post('/admin/news/add_product', data, function(response){
                    if(response.result == 'success'){
                        $('#in_action').html(response.html);
                        $this.parents('li').remove();
                    }
                });
            });

            $(document).on('click', '.remove-from-action', function(){
                var $this = $(this);
                var data = {
                    'news_id': <?php echo e($article->id); ?>,
                    'product_id': $this.data('id')
                };

                $.post('/admin/news/remove_product', data, function(response){
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