<?php if ($form->getObject()->getThumbUrl()): ?>
<div class="sf_admin_form_row sf_admin_text 
    sf_admin_form_field_image_url">
  <div>
    <label for="milkshake_image_url">Image preview</label>
    <?php echo image_tag('/uploads/milkshakes/'.
       $form->getObject()->getThumbUrl()
    ); ?>
  </div>
</div>
<?php endif; ?>