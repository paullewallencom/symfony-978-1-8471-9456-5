<?php if (strtotime($vacancy->getClosingDate()) < time()): ?>
  <span style="color: red; font-weight: bold;">
    <?php echo $vacancy->getClosingDate('d/m/Y'); ?>
  </span>
<?php else: ?>
  <?php echo $vacancy->getClosingDate('d/m/Y'); ?>
<?php endif; ?>
