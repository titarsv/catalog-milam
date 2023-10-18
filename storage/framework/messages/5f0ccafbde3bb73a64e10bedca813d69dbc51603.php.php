
<?php $__env->startSection('page_vars'); ?>
    <?php echo $__env->make('public.layouts.microdata.product', ['product' => $product, 'reviews' => $reviews], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('public.layouts.microdata.open_graph', [
     'title' => $seo->meta_title,
     'description' => $seo->meta_description,
     'image' => !empty($product->image) ? $product->image->url() : '/images/logo.png'
     ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php if(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome-Lighthouse') === false && config('app.debug') === false): ?>
        <!-- Facebook Pixel Code -->
        <script>
            !function (f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function () {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window,
                document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '867264487143692');
            fbq('track', 'PageView');
        </script>
        <script>
            var fbqProductsData = [];
            fbqProductsData[<?php echo e($product->id); ?>] = {
                content_type: 'product',
                content_ids: ['<?php echo e($product->id); ?>'],
                content_name: '<?php echo e($product->name); ?>',
                content_category: '<?php echo e($product->categories->count() ? $product->categories->first()->name : ''); ?>',
                value: <?php echo e(round($product->price)); ?>,
                currency: 'UAH'
            };
            if (typeof fbq !== 'undefined') {
                fbq('track', 'ViewContent', fbqProductsData[<?php echo e($product->id); ?>]);
            }
        </script>
        <!-- End Facebook Pixel Code -->
    <?php endif; ?>
    <!-- Код тега ремаркетинга Google -->
    <script>
        /* <![CDATA[ */
        var google_tag_params = {
            ecomm_prodid: '<?php echo e($product->id); ?>',
            ecomm_pagetype: 'product',
            ecomm_totalvalue: <?php echo e(round($product->price)); ?>

        };
        /* ]]> */
        var fbqProductsData = [];
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <main class="main">
        <?php echo Breadcrumbs::render('product', $product, $product->category); ?>

        <div class="product">
            <div class="container">
                <div class="product-inner row">
                    <div class="product-photo col-md-5">
                        <div class="product-slider-main slick-slider"
                             data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "asNavFor": ".product-slider-previews", "fade": true, "arrows": true, "infinite": true}'>
                            <?php $__currentLoopData = $gallery; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(!empty($slide['image'])): ?>
                                    <div class="slide">
                                        <?php echo $slide['image']->webp([890, 890], ['alt' => $product->name, 'width' => 890, 'height' => 890], 'slider', 'contain'); ?>

                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php if($gallery->count() > 1): ?>
                        <div class="product-slider-previews slick-slider" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "variableWidth": true, "asNavFor": ".product-slider-main", "focusOnSelect": true,  "arrows": false, "infinite": true,
            "responsive":[{"breakpoint":480,"settings":{"slidesToShow": 6}}]}'>
                            <?php $__currentLoopData = $gallery; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(!empty($slide['image'])): ?>
                                    <div class="slide">
                                        <?php echo $slide['image']->webp([890, 890], ['alt' => $product->name, 'width' => 890, 'height' => 890], 'slider', 'contain'); ?>

                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="product-description col-md-7">
                        <h1><?php echo e(!empty($seo->name) ? $seo->name : $product->name); ?></h1>
                        <?php if(!empty($product->description)): ?>
                        <span><?php echo e(__('Описание')); ?>:</span>
                        <?php echo $product->description; ?>

                        <?php endif; ?>
                        <?php if($related->count()): ?>
                        <span><?php echo e(__('Объем')); ?>:</span>
                        <ul class="product-description-size">
                            <li>
                                <div>
                                    <?php echo $product->image->webp([150, 150], ['alt' => $product->name, 'width' => 75, 'height' => 75], 'static', 'contain'); ?>

                                    <span><?php echo e($product->capacity->name); ?></span>
                                </div>
                            </li>
                            <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related_product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <a href="<?php echo e($related_product->link()); ?>">
                                    <?php echo $related_product->image->webp([150, 150], ['alt' => $related_product->name, 'width' => 75, 'height' => 75], 'static', 'contain'); ?>

                                    <span><?php echo e($related_product->capacity->name); ?></span>
                                </a>
                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <?php endif; ?>
                        <?php if(!empty($product->purposes)): ?>
                        <span><?php echo e(__('Назначение')); ?>:</span>
                        <p><?php echo e($product->purposes); ?></p>
                        <?php endif; ?>
                        <?php if(!empty($product->instructions)): ?>
                        <span><?php echo e(__('Инструкция по применению')); ?></span>
                        <?php echo $product->instructions; ?>

                        <?php endif; ?>
                        <?php if(!empty($product->security)): ?>
                        <span><?php echo e(__('Меры безопасности')); ?></span>
                        <?php echo $product->security; ?>

                        <?php endif; ?>
                        <?php if(!empty($documents)): ?>
                        <span><?php echo e(__('Документы / Сертификаты')); ?>:</span>
                        <div class="product-description-doc">
                            <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e($document->url()); ?>" target="_blank"><?php echo e(__('Сертификат')); ?><?php echo e($i + 1); ?>.<?php echo e(substr($document->image->title, strrpos($document->image->title, '.') + 1)); ?></a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php endif; ?>
                        <?php if(!empty($product->compound)): ?>
                        <span><?php echo e(__('Состав')); ?>:</span>
                        <?php echo $product->compound; ?>

                        <?php endif; ?>
                        <?php if(!empty($product->shelf_life)): ?>
                        <span><?php echo e(__('Срок годности')); ?></span>
                        <p><?php echo e($product->shelf_life); ?></p>
                        <?php endif; ?>
                        <?php if(!empty($product->storage_conditions)): ?>
                        <span><?php echo e(__('Условия хранения')); ?></span>
                        <p><?php echo e($product->storage_conditions); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $__env->make('public.layouts.consult', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </main>
    
    
        
            
            
            
                
                    
                        
                            
                                 
                                
                                    
                                        
                                            
                                                
                                                    
                                                        
                                                            
                                                        
                                                    
                                                
                                                
                                                
                                                     
                                                     
                                                    
                                                            
                                                            
                                                
                                            
                                        
                                    
                                
                            
                        
                        
                            
                                
                                    
                                        
                                            
                                                
                                                    
                                                        
                                                            
                                                        
                                                    
                                                
                                                
                                                    
                                                
                                                
                                                    
                                                
                                                
                                                     
                                                     
                                                    
                                                    
                                                            
                                                            
                                                
                                            
                                        
                                    
                                        
                                            
                                                
                                                    
                                                        
                                                            
                                                        
                                                    
                                                
                                                
                                            
                                        
                                    
                                
                            
                        
                        
                            
                                
                                    
                                        
                                            
                                                
                                                    
                                                        
                                                    
                                                
                                            
                                            
                                                
                                            
                                            
                                                
                                            
                                            
                                                 
                                                 
                                                
                                                
                                                        
                                                        
                                            
                                        
                                    
                                
                            
                        
                        
                            
                                
                                
                                    
                                         
                                        
                                                
                                                
                                    
                                    
                                
                                
                                    
                                
                                
                                
                                    
                                    
                                         
                                        
                                                
                                                
                                    
                                
                                
                            
                            
                                
                                    
                                
                                
                                
                                    
                                        
                                    
                                    
                                
                                
                                
                                
                                    
                                        
                                            
                                            
                                            
                                                 
                                                
                                                        
                                                        
                                            
                                        
                                        
                                    
                                    
                                        
                                            
                                                
                                                    
                                                        
                                                            
                                                            
                                                                
                                                                     
                                                                    
                                                                            
                                                                            
                                                                
                                                            
                                                        
                                                    
                                                
                                            
                                            
                                                
                                                    
                                                    
                                                        
                                                             
                                                            
                                                                    
                                                                    
                                                        
                                                    
                                                
                                            
                                        
                                    
                                
                                
                                
                                    
                                        
                                            
                                            
                                                 
                                                
                                                        
                                                        
                                            
                                        
                                        
                                      
                                        
                                                
                                                
                                      
                                      
                                    
                                    
                                    
                                    
                                        
                                            
                                                
                                                
                                            
                                            
                                                
                                                    
                                                        
                                                            
                                                                
                                                                    
                                                                
                                                                
                                                                
                                                                
                                                            
                                                            
                                                                
                                                                    
                                                                    
                                                                
                                                                
                                                                    
                                                                        
                                                                            
                                                                            
                                                                        
                                                                        
                                                                            
                                                                            
                                                                        
                                                                        
                                                                            
                                                                            
                                                                        
                                                                    
                                                                        
                                                                            
                                                                                
                                                                                    
                                                                                
                                                                                    
                                                                                
                                                                                    
                                                                                
                                                                                    
                                                                                
                                                                            
                                                                            
                                                                        
                                                                    
                                                                
                                                            
                                                        
                                                    
                                                
                                            
                                            
                                                
                                                    
                                                        
                                                            
                                                                
                                                                    
                                                                
                                                                
                                                                    
                                                                
                                                            
                                                            
                                                                
                                                                    
                                                                    
                                                                
                                                                
                                                                    
                                                                        
                                                                            
                                                                            
                                                                        
                                                                        
                                                                            
                                                                            
                                                                        
                                                                        
                                                                            
                                                                            
                                                                        
                                                                    
                                                                        
                                                                            
                                                                                
                                                                                    
                                                                                
                                                                                    
                                                                                
                                                                                    
                                                                                
                                                                                    
                                                                                
                                                                            
                                                                            
                                                                        
                                                                    
                                                                
                                                            
                                                        
                                                    
                                                
                                            
                                        
                                        
                                            
                                        
                                    
                                    
                                
                                
                                    
                                        
                                          
                                            
                                                    
                                            
                                                    
                                            
                                          
                                        
                                        
                                          
                                            
                                                    
                                            
                                                    
                                            
                                          
                                        
                                        
                                            
                                                
                                                    
                                                        
                                                                
                                                                
                                                        
                                                                
                                                                
                                                        
                                                             
                                                             
                                                    
                                                
                                                
                                                
                                            
                                            
                                                
                                                    
                                                        
                                                                
                                                                
                                                        
                                                                
                                                                
                                                        
                                                             
                                                             
                                                    
                                                
                                                
                                                
                                            
                                        
                                    
                                
                                
                                
                                
                                
                                    
                                        
                                             
                                          
                                                  
                                                  
                                        
                                        
                                    
                                    
                                        
                                             
                                          
                                                  
                                                  
                                        
                                        
                                    
                                    
                                            
                                                 
                                              
                                                      
                                                      
                                              
                                                      
                                                      
                                              
                                                      
                                                      
                                              
                                                      
                                                      
                                            
                                            
                                  
                                
                                
                                    
                                
                                
                                    
                                        
                                            
                                                
                                                     
                                                    
                                                            
                                                            
                                                
                                            
                                            
                                            
                                        
                                        
                                            
                                                
                                                    
                                                    
                                                        
                                                        
                                                            
                                                                
                                                                
                                                            
                                                                
                                                            
                                                        
                                                    
                                                    
                                                
                                            
                                        
                                    
                                    
                                        
                                            
                                                
                                                     
                                                    
                                                          
                                                          
                                                
                                            
                                            
                                            
                                        
                                        
                                            
                                                
                                                
                                            
                                        
                                    
                                    
                                        
                                            
                                                
                                                     
                                                    
                                                            
                                                            
                                                
                                            
                                            
                                            
                                        
                                        
                                            
                                                
                                                    
                                                        
                                                             
                                                            
                                                                  
                                                                  
                                                        
                                                    
                                                
                                                
                                                    
                                                        
                                                             
                                                            
                                                                    
                                                                    
                                                        
                                                    
                                                
                                                
                                                    
                                                        
                                                             
                                                            
                                                                    
                                                                    
                                                        
                                                    
                                                
                                                
                                                    
                                                        
                                                             
                                                            
                                                                    
                                                                    
                                                        
                                                        
                                                    
                                                
                                            
                                        
                                    
                                    
                                    
                                        
                                            
                                                
                                                     
                                                    
                                                          
                                                          
                                                    
                                                          
                                                          
                                                    
                                                          
                                                          
                                                    
                                                          
                                                          
                                                    
                                                          
                                                          
                                                    
                                                          
                                                          
                                                    
                                                          
                                                          
                                                    
                                                    
                                                            
                                                            
                                                
                                            
                                            
                                            
                                        
                                        
                                            
                                                
                                                    
                                                    
                                                
                                                
                                                    
                                                
                                                
                                                    
                                                
                                            
                                        
                                    
                                    
                                
                            
                        
                    
                
            
        
        
        
            
                
                    
                    
                        
                            
                                 
                                
                                        
                                        
                            
                        
                        
                        
                            
                                 
                                
                                        
                                        
                            
                        
                    
                
                
                    
                         
                        
                            
                        
                    
                
            
        
        
        
            
                
                    
                    
                        
                            
                                 
                                
                                        
                                        
                            
                        
                        
                        
                            
                                 
                                
                                        
                                        
                            
                        
                    
                
                
                    
                         
                        
                            
                        
                    
                
            
        
        
        
            
                
                
                
                
                    
                    
                        
                        
                        
                            
                        
                        
                            
                                
                                    
                                    
                                        
                                        
                                            
                                                 
                                                
                                                        
                                                        
                                            
                                        
                                        
                                        
                                            
                                                 
                                                
                                                        
                                                        
                                            
                                        
                                        
                                        
                                            
                                                 
                                                
                                                        
                                                        
                                            
                                        
                                        
                                        
                                            
                                                 
                                                
                                                        
                                                        
                                            
                                        
                                        
                                        
                                            
                                                 
                                                
                                                        
                                                        
                                            
                                        
                                    
                                
                            
                            
                                
                                    
                                    
                                    
                                
                            
                        
                        
                            
                                
                            
                            
                                
                            
                        
                        
                            
                            
                        
                        
                    
                
                
                
                    
                        
                            
                                
                                    
                                        
                                    
                                
                            
                        
                    
                    
                        
                            
                            
                                
                                    
                                         
                                        
                                                
                                                
                                    
                                
                            
                        
                    
                    
                        
                        
                            
                            
                                
                            
                            
                            
                                
                                    
                                    
                                        
                                    
                                    
                                
                            
                        
                        
                    
                
                
            
        
    

    
    
        
            
                
                    
                            
                            
                
            
            
            
            
                  
                  
                
                    
                           
                
                
                    
                           
                
                
            
        
        
        
            
                
                    
                            
                            
                
            
            
                
                  
                          
                          
                
                
            
            
            
            
            
                
                    
                        
                        
                    
                    
                        
                        
                    
                    
                        
                        
                    
                
                    
                        
                            
                                
                            
                                
                            
                                
                            
                                
                            
                        
                        
                    
                
            
            
            
                
                    
                        
                            
                                
                                
                            
                        
                    
                
            
            
        
        
    
    
        
          
            
                    
                    
          
        
        
            
            
                
                    
                
                
                    
                    
                    
                    
                    
                
            
            
                
                    
                    
                    
                        
                            
                            
                                
                                        
                                        
                            
                        
                        
                            
                            
                            
                        
                    
                    
                    
                    
                
                
                    
                        
                            
                            
                            
                            
                            
                            
                        
                        
                            
                            
                            
                            
                            
                            
                        
                        
                            
                            
                            
                            
                            
                            
                        
                        
                            
                            
                            
                            
                            
                            
                        
                        
                            
                            
                            
                            
                            
                            
                        
                        
                            
                            
                            
                            
                            
                            
                        
                    
                
                
                    
                        
                            
                            
                            
                            
                            
                            
                        
                        
                            
                            
                            
                            
                            
                            
                        
                        
                            
                            
                            
                            
                            
                            
                        
                        
                            
                            
                            
                            
                            
                            
                        
                        
                            
                            
                            
                            
                            
                            
                        
                        
                            
                            
                            
                            
                            
                            
                        
                    
                
                
                    
                        
                            
                            
                            
                            
                            
                            
                        
                        
                            
                            
                            
                            
                            
                            
                        
                        
                            
                            
                            
                            
                            
                            
                        
                        
                            
                            
                            
                            
                            
                            
                        
                        
                            
                            
                            
                            
                            
                            
                        
                        
                            
                            
                            
                            
                            
                            
                        
                    
                
            
            
                
                
                
                    
                        
                        
                        
                            
                                    
                                    
                            
                                    
                                    
                            
                                 
                        
                        
                            
                                    
                        
                        
                             
                            
                            
                                    
                                    
                        
                    
                
                
                    
                        
                            
                                
                                        
                                        
                                
                                        
                                        
                                
                                     
                            
                        
                        
                    
                    
                        
                            
                                
                                        
                                        
                                
                                        
                                        
                                
                                     
                            
                        
                        
                    
                    
                        
                            
                                
                                        
                                        
                                
                                        
                                        
                                
                                     
                            
                        
                        
                    
                
            
        
        
            
        
    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('public.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OSPanel\domains\milam.lh\resources\views/public/product.blade.php ENDPATH**/ ?>