<h3 style="margin-top:0; padding-top:0">We are currently in</h3>
<?php foreach ($locationsArray as $location): ?>
<div style="margin-bottom: 16px;">
	<?php echo $location->getAddress1(); ?><br />
	<?php echo ($location->getAddress2() != "")? $location->getAddress2(). "<br />": ""; ?>
	<?php echo ($location->getAddress3() != "")? $location->getAddress3(). "<br />": ""; ?>
	<?php echo $location->getCity(); ?><br />
	<?php echo $location->getPostcode(); ?><br />
	<?php echo $location->getCountry(); ?><br />
	<?php echo $location->getPhone(); ?><br />
	<?php echo $location->getFax(); ?><br />
</div>
<?php endforeach ?>