<div class="apero_light container_12">
  <div class=" grid_1">
    <div class="date">
      <span class="day"><?php echo format_date($apero->getDateAt(), 'dd'); ?></span>
      <span class="month"><?php echo format_date($apero->getDateAt(), 'MMM'); ?></span>
      <span class="time"><?php echo format_date($apero->getTimeAt(), 't'); ?></span>
    </div>
  </div>
  <div class="informations grid_6">
    <?php if ($moreInformations): ?>
    <h2><?php echo link_to($apero->getLocationCity().', '.$apero->getLocationName(), 'apero', $apero); ?></h2>
    <?php else: ?>
    <h2><?php echo $apero->getLocationCity().', '.$apero->getLocationName(); ?></h2>
    <?php endif; ?>
    <p><?php echo format_number_choice('[0]Aucun inscrit pour le moment.|[1]1 personne est attendue.|(1,+Inf]%count% personnes sont attendues.', array('%count%' => count($apero->getAperoUser())), count($apero->getAperoUser())); ?></p>
  </div>
  <div class="buttons grid_5 alignright">
    <?php if ($moreInformations): ?>
      <?php echo link_to('plus d\'infos', 'apero', $apero, array('class' => 'btn btn_blue')); ?>
    <?php endif; ?>
    <?php if ($sf_user->isAuthenticated() && $apero->isRegister($sf_user->getRawValue()->getGuardUser())): ?>
    <?php echo link_to('je suis inscrit', 'account_aperos', $apero, array('class' => 'btn btn_blue')); ?>
    <?php else: ?>
    <?php echo link_to('je m\'inscrit !', 'apero_register', $apero, array('class' => 'btn btn_blue')); ?>
    <?php endif; ?>
  </div>
  <div class="clear"></div>
</div>