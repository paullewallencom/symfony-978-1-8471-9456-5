<?php if ($paginationObj->haveToPaginate()): ?>
<div style="margin-top: 10px;">
        <?php if($paginationObj->getFirstPage() != $paginationObj->getPage()): ?>
                <?php echo link_to('&laquo;', '@menu?page='.$paginationObj->getFirstPage(), 'class="pageLink"') ?>
                <?php echo link_to('&lt;', '@menu?page='.$paginationObj->getPreviousPage(), 'class="pageLink"') ?>
        <?php endif ?>

        <?php $links = $paginationObj->getLinks(); foreach ($links as $page): ?>
                <?php echo ($page == $paginationObj->getPage()) ? '<span class="selectedPageBox">'.$page.'</span>' : link_to($page, '@menu?page='.$page , 'class="pageLink"') ?>
        <?php endforeach ?>

        <?php if($paginationObj->getLastPage() != $paginationObj->getPage()): ?>
                <?php echo link_to('&gt;', '@menu?page='.$paginationObj->getNextPage(), 'class="pageLink"') ?>
                <?php echo link_to('&raquo;', '@menu?page='.$paginationObj->getLastPage(), 'class="pageLink"') ?>
        <?php endif ?>
</div>
<?php endif ?>