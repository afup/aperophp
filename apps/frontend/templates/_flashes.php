<?php if ($sf_user->hasFlash('notice') || $sf_user->hasFlash('error') || $sf_user->hasFlash('info') || $sf_user->hasFlash('warning')): ?>
<div id="flash">
  <?php if ($sf_user->hasFlash('notice')): ?>
    <div class="notice"><?php echo __($sf_user->getFlash('notice'), array(), 'sf_admin') ?></div>
  <?php endif; ?>

  <?php if ($sf_user->hasFlash('info')): ?>
    <div class="info"><?php echo __($sf_user->getFlash('info'), array(), 'sf_admin') ?></div>
  <?php endif; ?>

  <?php if ($sf_user->hasFlash('warning')): ?>
    <div class="warning"><?php echo __($sf_user->getFlash('warning'), array(), 'sf_admin') ?></div>
  <?php endif; ?>

  <?php if ($sf_user->hasFlash('error')): ?>
    <div class="error"><?php echo __($sf_user->getFlash('error'), array(), 'sf_admin') ?></div>
  <?php endif; ?>
</div>
<?php endif; ?>