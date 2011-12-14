<?php include_partial('page/headerAccount'); ?>
<div class="container_12">

  <div class="grid_10 suffix_2">
    <fieldset>
        <legend><?php echo __('Vos apéros à venir'); ?></legend>
        <?php if (count($comingAperos) > 0): ?>
          <ul><?php foreach ($comingAperos as $apero): ?>
            <li class="apero_micro">
              <span class="date"><?php echo format_date($apero->getDateAt(), 'p'); ?> <?php echo format_date($apero->getTimeAt(), 't'); ?></span>
              <span class="location"><?php echo link_to($apero->getLocationCity().', '.$apero->getLocationName(), 'apero', $apero); ?></span>
              <span class="actions">
                <?php echo link_to(image_tag('/images/icons/famfamfam/date.png', array('alt' => 'ics')), 'apero_ics', $apero, array('title' => 'Ajouter cet évenement à mon agenda')); ?>
                <?php echo link_to(image_tag('/images/google.gif', array('alt' => 'gcal')), 'apero_gcal', $apero, array('title' => 'Ajouter cet évenement à mon agenda Google', 'target' => '_blank')); ?>
                <?php echo link_to(image_tag('/images/outlook.jpg', array('alt' => 'vcs')), 'apero_vcs', $apero, array('title' => 'Ajouter cet évenement à mon outlook')); ?>
                <?php echo link_to(image_tag('/images/icons/famfamfam/cancel.png', array('alt' => 'cancel')), 'apero_unsubscribe', $apero, array('title' => 'Annuler ma participation', 'confirm' => 'Etes vous sur de vouloir annuler votre participation ?')); ?>
              </span>
            </li>
          <?php endforeach; ?></ul>
        <?php else: ?>
          <ul>
            <li class="apero_micro">
              <?php echo __('Vous n\'avez pas d\'apéros de prévu'); ?>
            </li>
          </ul>
        <?php endif; ?>
    </fieldset>
    <fieldset>
        <legend><?php echo __('Les apéros auxquels vous avez participé'); ?></legend>
        <?php if (count($passedAperos) > 0): ?>
          <ul><?php foreach ($passedAperos as $apero): ?>
            <li class="apero_micro">
              <span class="date"><?php echo format_date($apero->getDateAt(), 'p'); ?> <?php echo format_date($apero->getTimeAt(), 't'); ?></span>
              <span class="location"><?php echo link_to($apero->getLocationCity().', '.$apero->getLocationName(), 'apero', $apero); ?></span>
              <span class="actions">
              </span>
            </li>
          <?php endforeach; ?></ul>
        <?php else: ?>
          <ul>
            <li class="apero_micro">
              <?php echo __('Vous n\'avez pas encore participé à un apéro PHP'); ?>
            </li>
          </ul>
        <?php endif; ?>
    </fieldset>
  </div>

  <div class="clear"></div>
  
</div>