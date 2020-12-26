<?php use_helper('Date') ?>
<div style="width: 100px; float:right">
<?php echo link_to(image_tag('/images/flags/en_GB.png'), '@vacancies?sf_culture=en_GB') ?>&nbsp;
<?php echo link_to(image_tag('/images/flags/en_AU.png'), '@vacancies?sf_culture=en_AU') ?>&nbsp;
<?php echo link_to(image_tag('/images/flags/fr_FR.png'), '@vacancies?sf_culture=fr_FR') ?>
</div>
<h3 style="margin-top:0; padding-top:0"><?php echo __("Current vacanies"); ?></h3>
<?php foreach($vacanciesArray as $vacancy): ?>
<div style="margin-bottom: 16px;">
	<strong><?php echo $vacancy->getPosition()?></strong>
	<br />
	<?php echo $vacancy->getPositionDescription() ?>
	<br /><br />
	<?php echo __("Wanted in") ?> <?php echo $vacancy->getStoreLocation() ?>
	<br /><br />
	<?php echo __("Closing Date") ?>: <strong> <?php echo format_date($vacancy->getClosingDate()) ?></strong>
	<br /><br />

</div>
<?php endforeach ?>
