<div class="container_12">
  <div class="grid_6">
    <form action="" method="post">
      <fieldset>
        <legend><?php echo __('Je n\'ai pas de compte'); ?></legend>
        <p>Prenez quelques secondes pour vous créer un compte et participer aux apéros PHP.</p>
      </fieldset>
      <input type="button" onclick="document.location.href='<?php echo url_for('@sf_guard_signup') ?>'" value="<?php echo __('Inscription') ?>" class="btn btn_blue" />
    </form>
  </div>
  <div class="grid_6">
    <form action="<?php echo url_for('@sf_guard_signin') ?>" method="post">
      <fieldset>
        <legend><?php echo __('J\'ai déjà un compte'); ?></legend>
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
      <input type="submit" value="<?php echo __('Connexion') ?>" class="btn btn_blue" />
    </form>
  </div>
  <div class="clear"></div>
</div>