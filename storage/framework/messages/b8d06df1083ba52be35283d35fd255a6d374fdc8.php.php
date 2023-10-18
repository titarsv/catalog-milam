<?php if($paginator->lastPage() > 1): ?>
    
        
    
        <div class="container">
            <ul class="pagination<?php echo e(!empty($js) ? ' js_pagination' : ''); ?>">
                <?php if($paginator->currentPage() != 1): ?>
                    <li class="prev">
                        <a href="<?php echo e($cp->url($paginator->url(1), 1)); ?>"><<</a>
                    </li>
                <?php endif; ?>

                <?php if($paginator->lastPage() <= 5): ?>

                    <?php for($c=1; $c<=$paginator->lastPage(); $c++): ?>
                        <li<?php echo $paginator->currentPage() == $c ? ' class="current"' : ''; ?>>
                            <?php if($paginator->currentPage() == $c): ?>
                              <span><?php echo e($c); ?></span>
                            <?php else: ?>
                                <a href="<?php echo e($cp->url($paginator->url($c), $c)); ?>"><?php echo e($c); ?></a>
                            <?php endif; ?>
                        </li>
                    <?php endfor; ?>

                <?php elseif($paginator->currentPage() < 4): ?>

                    <?php for($c=1; $c<=4; $c++): ?>
                        <li<?php echo $paginator->currentPage() == $c ? ' class="current"' : ''; ?>>
                            <?php if($paginator->currentPage() == $c): ?>
                                <span><?php echo e($c); ?></span>
                            <?php else: ?>
                                <a href="<?php echo e($cp->url($paginator->url($c), $c)); ?>"><?php echo e($c); ?></a>
                            <?php endif; ?>
                        </li>
                    <?php endfor; ?>

                    <?php if($paginator->lastPage() >= 6): ?>
                        <li class="dots"><a href="javascript:void(0)">...</a></li>
                    <?php endif; ?>

                    <li<?php echo $paginator->currentPage() == $paginator->lastPage() ? ' class="current"' : ''; ?>>
                        <?php if($paginator->currentPage() == $paginator->lastPage()): ?>
                            <span><?php echo e($paginator->lastPage()); ?></span>
                        <?php else: ?>
                            <a href="<?php echo e($cp->url($paginator->url($paginator->lastPage()), $paginator->lastPage())); ?>"><?php echo e($paginator->lastPage()); ?></a>
                        <?php endif; ?>
                    </li>

                <?php elseif($paginator->currentPage() > ($paginator->lastPage()-3)): ?>

                    <li<?php echo $paginator->currentPage() == 1 ? ' class="current"' : ''; ?>>
                        <?php if($paginator->currentPage() == 1): ?>
                            <span><?php echo e(1); ?></span>
                        <?php else: ?>
                            <a href="<?php echo e($cp->url($paginator->url(1), 1)); ?>"><?php echo e(1); ?></a>
                        <?php endif; ?>
                    </li>

                    <?php if($paginator->lastPage() >= 4): ?>
                        <li class="dots"><a href="javascript:void(0)">...</a></li>
                    <?php endif; ?>

                    <?php for($c=($paginator->lastPage()-3); $c<=$paginator->lastPage(); $c++): ?>
                        <li<?php echo $paginator->currentPage() == $c ? ' class="current"' : ''; ?>>
                            <?php if($paginator->currentPage() == $c): ?>
                                <span><?php echo e($c); ?></span>
                            <?php else: ?>
                                <a href="<?php echo e($cp->url($paginator->url($c), $c)); ?>"><?php echo e($c); ?></a>
                            <?php endif; ?>
                        </li>
                    <?php endfor; ?>

                <?php else: ?>

                    <li<?php echo $paginator->currentPage() == 1 ? ' class="current"' : ''; ?>>
                        <?php if($paginator->currentPage() == 1): ?>
                            <span><?php echo e(1); ?></span>
                        <?php else: ?>
                            <a href="<?php echo e($cp->url($paginator->url(1), 1)); ?>"><?php echo e(1); ?></a>
                        <?php endif; ?>
                    </li>

                    <?php if($paginator->currentPage() > 3): ?>
                        <li class="dots"><a href="javascript:void(0)">...</a></li>
                    <?php endif; ?>

                    <?php for($c=($paginator->currentPage()-1); $c<=($paginator->currentPage()+1); $c++): ?>
                        <li<?php echo $paginator->currentPage() == $c ? ' class="current"' : ''; ?>>
                            <?php if($paginator->currentPage() == $c): ?>
                                <span><?php echo e($c); ?></span>
                            <?php else: ?>
                                <a href="<?php echo e($cp->url($paginator->url($c), $c)); ?>"><?php echo e($c); ?></a>
                            <?php endif; ?>
                        </li>
                    <?php endfor; ?>

                    <?php if($paginator->currentPage() < $paginator->lastPage()-2): ?>
                        <li class="dots"><a href="javascript:void(0)">...</a></li>
                    <?php endif; ?>

                    <li<?php echo $paginator->currentPage() == $paginator->lastPage() ? ' class="current"' : ''; ?>>
                        <?php if($paginator->currentPage() == $paginator->lastPage()): ?>
                            <span><?php echo e($paginator->lastPage()); ?></span>
                        <?php else: ?>
                            <a href="<?php echo e($cp->url($paginator->url($paginator->lastPage()), $paginator->lastPage())); ?>"><?php echo e($paginator->lastPage()); ?></a>
                        <?php endif; ?>
                    </li>

                <?php endif; ?>

                <?php if($paginator->currentPage() != $paginator->lastPage()): ?>
                    <li class="next">
                        <a href="<?php echo e($cp->url($paginator->url($paginator->lastPage()), $paginator->lastPage())); ?>">>></a>
                    </li>
                <?php endif; ?>
            </ul>
            
        </div>
    
<?php endif; ?><?php /**PATH C:\OSPanel\domains\milam.lh\resources\views/public/layouts/pagination.blade.php ENDPATH**/ ?>