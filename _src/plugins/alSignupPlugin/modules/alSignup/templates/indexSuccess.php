<?php if(!$sf_user->hasFlash('Form')): ?>
<form action="<?php echo url_for('@signup_submit') ?>" method="post" name="Newsletter">
	<div style="height: 30px;">
		<div style="width: 150px; float: left"><?php echo $form['first_name']->renderLabel() ?></div>
		<?php echo $form['first_name']->render(($form['first_name']->hasError())? array('class'=>'boxError'): array('class'=>'box')) ?>
			<?php echo ($form['first_name']->hasError())? ' <span class="errorMessage">* '.$form['first_name']->getError(). '</span>': '' ?>
		<div style="clear: both"></div>
	</div>
	<div style="height: 30px;">
		<div style="width: 150px; float: left"><?php echo $form['surname']->renderLabel() ?></div>
		<div style="width: 300px; float: left;">
			<?php echo $form['surname']->render(($form['surname']->hasError())? array('class'=>'boxError'): array('class'=>'box')) ?>
			<?php echo ($form['surname']->hasError())? ' <span class="errorMessage">* '.$form['surname']->getError(). '</span>': '' ?>
		</div>
		<div style="clear: both"></div>
	</div>
	<div style="height: 30px;">
		<div style="width: 150px; float: left"><?php echo $form['email']->renderLabel() ?></div>
			<?php echo $form['email']->render(($form['email']->hasError())? array('class'=>'boxError'): array('class'=>'box')) ?>
			<?php echo ($form['email']->hasError())? ' <span class="errorMessage">* '.$form['email']->getError(). '</span>': '' ?>
		</div>
		<div style="clear: both"></div>
	</div>
<div style="height: 30px;">
		<div style="width: 150px; float: left"><?php echo $form['newsletter_adverts_id']->renderLabel() ?></div>
			<?php echo $form['newsletter_adverts_id']->render(($form['newsletter_adverts_id']->hasError())? array('class'=>'boxError'): array('class'=>'box')) ?>
			<?php echo ($form['newsletter_adverts_id']->hasError())? ' <span class="errorMessage">* '.$form['newsletter_adverts_id']->getError(). '</span>': '' ?>
		<div style="clear: both"></div>
	</div>

  <?php echo $form['_csrf_token']; ?>

	<input type="submit" />
</form>
<?php else: ?>
<h1>Thank you</h1>
You are now signed up.
<?php endif ?>