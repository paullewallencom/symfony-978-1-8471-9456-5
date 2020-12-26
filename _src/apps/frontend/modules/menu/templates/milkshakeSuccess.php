<div>
  <div style="width: 192px; float: left;">
  <?php echo image_tag('/images/'.$milkshakeObj->getMilkShake()->getImageUrl()); ?>
  </div>
  <div style="width: 300px; margin-left: 192px;">
  <h3 style="padding-top:0; margin-top:0"><?php echo $milkshakeObj->getMilkShake()->getName() ?></h3>
  Total Calories: <?php echo $milkshakeObj->getMilkShake()->getCalories() ?><br /><br />
  All milkshakes are made with Cornish cream ice cream and full fat milk. This milkshake also contains:<br />
  <br />

  <?php foreach($flavorArray as $flavor): ?>
    <strong><?php echo($flavor->getFlavor()->getName()) ?></strong><br />
  <?php endforeach ?>

  </div>
  <div style="clear: both"></div>
</div>