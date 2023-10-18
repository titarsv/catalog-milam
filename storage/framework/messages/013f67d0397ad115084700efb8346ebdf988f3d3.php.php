
<?php $__env->startSection('page_vars'); ?>
    <?php if(!empty($seo)): ?>
        <?php echo $__env->make('public.layouts.microdata.open_graph', [
         'title' => $seo->meta_title,
         'description' => $seo->meta_description,
         'image' => '/images/logo.png'
         ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <main class="main">
        <div class="section main-section">
            <div class="main-slider slick-slider" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "fade": true, "autoplay": true, "arrows": true, "dots": true, "infinite": true}'>
                <?php $__currentLoopData = $fields['slider']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a class="slide" href="<?php echo e(!empty($slide->link) ? base_url($slide->link) : 'javascript:void(0)'); ?>">
                    <?php if(!empty($slide->image)): ?>
                        <picture>
                            <?php if(!empty($slide->image_mob)): ?>
                                <source media="(max-width: 480px)" srcset="/images/pixel.webp"
                                        data-original="<?php echo e($slide->image_mob['image']->url_webp([640, 420])); ?>" class="lazy-web" type="image/webp">
                                <source media="(max-width: 480px)" srcset="/images/pixel.jpg"
                                        data-original="<?php echo e($slide->image_mob['image']->url([640, 420])); ?>" class="lazy-web" type="image/jpg">
                            <?php endif; ?>
                            <source srcset="/images/pixel.webp" data-original="<?php echo e($slide->image['image']->url_webp([3840, 1100])); ?>" class="lazy-web"
                                    type="image/webp">
                            <source srcset="/images/pixel.jpg" data-original="<?php echo e($slide->image['image']->url([3840, 1100])); ?>" class="lazy-web"
                                    type="image/jpg">
                            <img src="/images/pixel.jpg" data-original="<?php echo e($slide->image['image']->url([3840, 1100])); ?>" class="lazy" alt="<?php echo e(!empty($seo->name) ? $seo->name : $page->name); ?>">
                        </picture>
                    <?php endif; ?>
                    <div class="container">
                        <div class="main-wrapper">
                            <div>
                                <span><?php echo e($slide->text); ?></span>
                                <?php if(!empty($slide->button)): ?>
                                <div class="btn"><?php echo e($slide->button); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <div class="section products-section">
            <div class="section-title">
                <div><?php echo e($categories[0]->name); ?></div>
            </div>
            <div class="container">
                <div class="products-wrapper">
                    <?php $__currentLoopData = $categories[0]->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e($subcategory->link); ?>" class="product-item">
                        <div class="product-pic">
                            <svg width="235" height="244" viewBox="0 0 235 244" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path class="path1" d="M130.128 22.5249C171.811 29.0213 200.162 52.6834 212.976 82.328C225.802 112.001 223.163 147.898 202.368 179.066L206.527 181.841C228.224 149.322 231.093 111.639 217.566 80.3442C204.026 49.0212 174.164 24.3276 130.898 17.5845L130.128 22.5249Z" fill="#78BEE1"/>
                                <path class="path2" d="M233.934 121.105C233.934 182.506 184.463 232.271 123.45 232.271C62.438 232.271 12.9673 182.506 12.9673 121.105C12.9673 89.8149 25.8143 61.5466 46.4914 41.3447C66.3879 21.9055 93.5298 9.93945 123.45 9.93945C155.619 9.93945 184.577 23.7716 204.771 45.8523C222.877 65.6511 233.934 92.0774 233.934 121.105Z" stroke="#DFDFDF" stroke-width="2"/>
                                <path class="path3" d="M129.703 233.334L129.016 223.358L129.016 223.358L129.703 233.334ZM10.6538 129.428L0.67727 130.112L10.6538 129.428ZM129.016 223.358C73.0199 227.211 24.4805 184.867 20.6304 128.743L0.67727 130.112C5.2814 197.228 63.3421 247.925 130.389 243.311L129.016 223.358ZM20.6304 128.743C16.7802 72.618 59.0836 24.0195 115.082 20.1659L113.709 0.213049C46.663 4.82701 -3.92674 62.9983 0.67727 130.112L20.6304 128.743ZM195.824 191.825C178.895 209.723 155.497 221.535 129.016 223.358L130.389 243.311C162.079 241.13 190.113 226.968 210.354 205.568L195.824 191.825Z" fill="#DFDFDF"/>
                            </svg>
                            <?php echo $subcategory->image; ?>

                        </div>
                        <span class="product-title"><?php echo e($subcategory->name); ?></span>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <div class="section popular-section">
            <div class="section-title">
                <div><?php echo e($fields['popular_title']); ?></div>
            </div>
            <div class="popular-wrapper">
                <div class="container">
                    <div class="popular-slider slick-slider" data-slick='{"slidesToShow": 3, "slidesToScroll": 1, "arrows": true, "dots": true, "infinite": false, "responsive":[{"breakpoint":991,"settings":{"slidesToShow": 2}}]}'>
                        <?php $__currentLoopData = $fields['popular']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $popular): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e($popular->product['product']->link()); ?>" class="slide">
                            <div class="popular-pic">
                                <?php echo $popular->product['product']->image->webp([694, 694], ['alt' => $popular->product['product']->name], 'slider'); ?>

                            </div>
                            <span class="popular-title"><?php echo e($popular->product['product']->name); ?></span>
                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="section about-section">
            <div class="about-top">
                <div class="section-title">
                    <div><?php echo e($fields['about_title']); ?></div>
                </div>
                <div class="container">
                    <div class="about-wrapper">
                        <div class="about-pic">
                            <?php echo $fields['about_image']['image']->webp([960, 700], ['alt' => $fields['about_title']], 'static'); ?>

                        </div>
                        <div class="about-descr">
                            <?php echo $fields['about_text']; ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="about-bot">
                <div class="container">
                    <div class="row about-brands">
                        <?php $__currentLoopData = $fields['brands']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="about-brand b<?php echo e($i + 1); ?>">
                            <div class="about-brand__pic">
                                <?php echo $brand->logo['image']->webp([345, 204], ['alt' => $brand->name], 'static'); ?>

                            </div>
                            <div class="about-brand__text">
                                <span><?php echo e($brand->name); ?></span>
                                <p><?php echo e($brand->description); ?></p>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php if($articles->count()): ?>
        <div class="section blog-section">
            <div class="section-title">
                <div><?php echo e(__('Блог')); ?></div>
            </div>
            <div class="blog-wrapper">
                <div class="container">
                    <div class="row blog-sliders">
                        <div class="col-md-6 col-sm-12 col-xs-12 blog-slider__txt-wrapper">
                            <div class="blog-slider__txt slick-slider" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "adaptiveHeight": true, "arrows": false, "dots": false, "infinite": false, "asNavFor": ".blog-slider__pic"}'>
                                <?php $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="slide">
                                    <span><?php echo e($article->name); ?></span>
                                    <?php echo $article->body; ?>

                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12 blog-slider__pic-wrapper">
                            <div class="blog-slider__pic slick-slider" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "arrows": true, "dots": false, "infinite": false, "asNavFor": ".blog-slider__txt", "responsive":[{"breakpoint":574,"settings":{"fade": true}}]}'>
                                <?php $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="slide">
                                        <?php echo $article->image->webp([1080, 640], ['alt' => $article->name], 'slide'); ?>

                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php echo $__env->make('public.layouts.consult', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('public.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>