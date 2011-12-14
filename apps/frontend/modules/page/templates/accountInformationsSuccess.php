<?php include_partial('page/headerAccount'); ?>
<div class="container_12">

  <div class="grid_10 suffix_2">
    <form method="post" action="<?php echo url_for('@account_informations'); ?>" enctype="multipart/form-data">
      <?php echo $form->renderGlobalErrors(); ?>
      <fieldset>
        <legend><?php echo __('Informations personnelles'); ?></legend>
        <?php foreach ($form as $widget): ?>
        <?php if ($widget->isHidden()): ?>
        <?php echo $widget->render(); ?>
        <?php else: ?>
         <div class="form_row last<?php $widget->hasError() and print ' errors'; ?>">
            <div>
              <?php echo $widget->renderError(); ?>
              <?php echo $widget->renderLabel(); ?>
              <div class="content">
                <?php echo $widget->render(); ?>
              </div>
            </div>
          </div>
        <?php endif; ?>
        <?php endforeach ;?>
      </fieldset>
      <input type="submit" value="Modifier" class="btn btn_blue" />
    </form>
  </div>

  <div class="clear"></div>
  
</div>