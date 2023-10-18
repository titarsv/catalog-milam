<ul class="nav nav-pills nav-stacked">
    <li role="presentation">
        <a href="/admin"><i class="fa fa-tachometer" aria-hidden="true"></i>Главная</a>
    </li>
    <?php if($user->hasAnyAccess(['products.list', 'categories.list', 'attributes.list', 'sales.list', 'import.list', 'export.list'])): ?>
    <li role="presentation">
        <p data-toggle="collapse" onclick="location='/admin/products'" data-target="#products-collapse" class="nav-collapse"><i class="fa fa-shopping-bag" aria-hidden="true"></i>Товары</p>
        <ul id="products-collapse" class="collapse nav nav-pills nav-stacked nav-collapse">
            <?php if($user->hasAccess(['products.list'])): ?>
            <li><a href="/admin/products"><i class="fa fa-circle-thin" aria-hidden="true"></i>Каталог товаров</a></li>
            <?php endif; ?>
            <?php if($user->hasAccess(['categories.list'])): ?>
            <li><a href="/admin/categories"><i class="fa fa-circle-thin" aria-hidden="true"></i>Категории</a></li>
            <?php endif; ?>
            <?php if($user->hasAccess(['attributes.list'])): ?>
            <li><a href="/admin/attributes"><i class="fa fa-circle-thin" aria-hidden="true"></i>Атрибуты товаров</a></li>
            <?php endif; ?>
            <?php if($user->hasAccess(['import.list'])): ?>
            <li><a href="/admin/products/import"><i class="fa fa-circle-thin" aria-hidden="true"></i>Импорт товаров</a></li>
            <?php endif; ?>
            <?php if($user->hasAccess(['export.list'])): ?>
            <li><a href="/admin/products/export"><i class="fa fa-circle-thin" aria-hidden="true"></i>Экспорт товаров</a></li>
            <?php endif; ?>
        </ul>
    </li>
    <?php endif; ?>
    <?php if($user->hasAnyAccess(['reviews.list', 'shopreviews.list'])): ?>
        <li role="presentation">
            <p data-toggle="collapse" data-target="#reviews-collapse" class="nav-collapse">
                <i class="fa fa-comments-o" aria-hidden="true"></i>Отзывы
                <?php if(!empty($new_reviews + $new_shop_reviews)): ?>
                    <span class="badge" style="background-color: #f00"><?php echo e($new_reviews + $new_shop_reviews); ?></span>
                <?php endif; ?>
            </p>
            <ul id="reviews-collapse" class="collapse nav nav-pills nav-stacked nav-collapse">
                <?php if($user->hasAccess(['reviews.list'])): ?>
                    <li>
                        <a href="/admin/reviews"><i class="fa fa-circle-thin" aria-hidden="true"></i>О товарах
                            <?php if(!empty($new_reviews)): ?>
                                <span class="badge"><?php echo $new_reviews; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if($user->hasAccess(['shopreviews.list'])): ?>
                    <li>
                        <a href="/admin/shopreviews"><i class="fa fa-circle-thin" aria-hidden="true"></i>О сайте
                            <?php if(!empty($new_shop_reviews)): ?>
                                <span class="badge"><?php echo e($new_shop_reviews); ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </li>
    <?php endif; ?>
    <?php if($user->hasAccess(['settings.view'])): ?>
        <li role="presentation">
            <p data-toggle="collapse" data-target="#settings-collapse" class="nav-collapse"><i class="fa fa-wrench" aria-hidden="true"></i>Настройки</p>
            <ul id="settings-collapse" class="collapse nav nav-pills nav-stacked nav-collapse">
                <li><a href="/admin/settings"><i class="fa fa-circle-thin" aria-hidden="true"></i>Общие настройки</a></li>
                <li><a href="/admin/cacheflush"><i class="fa fa-circle-thin" aria-hidden="true"></i>Очистить кэш</a></li>
                <?php if(env('REDIS_CACHE')): ?>
                    <li><a href="/admin/products/redis"><i class="fa fa-circle-thin" aria-hidden="true"></i>Redis</a></li>
                <?php endif; ?>
            </ul>
        </li>
    <?php endif; ?>
    <?php if($user->hasAccess(['pages.list'])): ?>
        <li role="presentation">
            <p data-toggle="collapse" data-target="#pages-collapse" class="nav-collapse"><i class="fa fa-desktop" aria-hidden="true"></i>Страницы</p>
            <ul id="pages-collapse" class="collapse nav nav-pills nav-stacked nav-collapse">
                <li><a href="/admin/pages"><i class="fa fa-circle-thin" aria-hidden="true"></i>Страницы</a></li>
                <li><a href="/admin/pages/templates"><i class="fa fa-circle-thin" aria-hidden="true"></i>Шаблоны</a></li>
            </ul>
        </li>
    <?php endif; ?>
    <?php if($user->hasAccess(['blog.list'])): ?>
        <li role="presentation">
            <a href="/admin/blog"><i class="fa fa-newspaper-o" aria-hidden="true"></i>Статьи</a>
        </li>
    <?php endif; ?>
    <?php if($user->hasAnyAccess(['seo.settings', 'seo.list', 'redirects.list'])): ?>
        <li role="presentation">
            <p data-toggle="collapse" data-target="#seo-collapse" class="nav-collapse"><i class="fa fa-line-chart" aria-hidden="true"></i>Продвижение</p>
            <ul id="seo-collapse" class="collapse nav nav-pills nav-stacked nav-collapse">
                <?php if($user->hasAccess(['seo.settings'])): ?>
                    <li><a href="/admin/seo"><i class="fa fa-circle-thin" aria-hidden="true"></i>Настройки</a></li>
                <?php endif; ?>
                <?php if($user->hasAccess(['seo.list'])): ?>
                    <li><a href="/admin/seo/list"><i class="fa fa-circle-thin" aria-hidden="true"></i>Метатеги</a></li>
                <?php endif; ?>
                <?php if($user->hasAccess(['redirects.list'])): ?>
                    <li><a href="/admin/seo/redirects"><i class="fa fa-circle-thin" aria-hidden="true"></i>Редиректы</a></li>
                <?php endif; ?>
            </ul>
        </li>
    <?php endif; ?>
    <?php if($user->hasAccess(['media.list'])): ?>
    <li role="presentation">
        <a href="/admin/media"><i class="fa fa-file-image-o" aria-hidden="true"></i>Медиафайлы</a>
    </li>
    <?php endif; ?>
    <?php if($user->hasAccess(['users.list'])): ?>
        <li role="presentation">
            <p data-toggle="collapse" data-target="#users-collapse" class="nav-collapse"><i class="fa fa-users" aria-hidden="true"></i>Пользователи</p>
            <ul id="users-collapse" class="collapse nav nav-pills nav-stacked nav-collapse">
                <li><a href="/admin/managers"><i class="fa fa-circle-thin" aria-hidden="true"></i>Менеджеры</a></li>
                <li><a href="/admin/moderators"><i class="fa fa-circle-thin" aria-hidden="true"></i>Модераторы</a></li>
                <li><a href="/admin/marketers"><i class="fa fa-circle-thin" aria-hidden="true"></i>Маркетологи</a></li>
                <li><a href="/admin/users"><i class="fa fa-circle-thin" aria-hidden="true"></i>Покупатели</a></li>
            </ul>
        </li>
    <?php endif; ?>
</ul><?php /**PATH C:\OSPanel\domains\milam.lh\resources\views/admin/layouts/sidebar.blade.php ENDPATH**/ ?>