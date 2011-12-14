<div class="container_12">
  <div class="prefix_3 suffix_3 grid_6">
    <form method="post" action="<?php echo url_for('@sf_guard_signup'); ?>">
      <?php echo $form->renderGlobalErrors(); ?>
      <fieldset>
        <legend><?php echo __('Inscription'); ?></legend>
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
      <input type="submit" value="<?php echo __('Créer mon compte') ?>" class="btn btn_blue" />
    </form>
  </div>
  <div class="clear"></div>
</div>