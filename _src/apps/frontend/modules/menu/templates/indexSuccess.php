<?php use_javascript('/js/jquery-1.3.2.min.js'); ?>
<?php use_javascript('/sfFormExtraPlugin/js/jquery.autocompleter.js') ?>
<?php use_stylesheet('/sfFormExtraPlugin/css/jquery.autocompleter.css') ?>


<form action="<?php echo url_for('menu/milkshake') ?>" method="POST">
<div style="text-align: center;">
  <?php echo $form['url_slug']; ?>
  <input type="submit" value="Search" />
</div>
</form>

<?php $i=0 ?>

<?php foreach($milkshakeObj->getResults() as $milkshake): ?>

<?php print_r($milkshake); ?>
    <div style="width: 176px; float: left; padding-bottom: 20px; margin-right: 16px; margin-bottom: 16px;">
      <div style="height: 250px; ">
          <?php //echo image_tag('/images/'.$milkshake->getImageUrl(), array('alt'=>$milkshake->getName())); ?>
      </div>
      <div>
        Name:<?php echo link_to($milkshake['name'], 'menu_item', $milkshake) ?><br />
        Calories: <?php echo $milkshake->getCalories() ?>
      </div>
    </div>

    <?php if(($i%3) == 2):  ?>
        <div style="clear:both"></div>
    <?php endif ?>

  <?php $i++ ?>

<?php endforeach; ?>

<?php include_partial('pagination', array('paginationObj'=>$milkshakeObj)) ?>