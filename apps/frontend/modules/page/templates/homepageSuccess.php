<?php if (count($comingAperos) > 0): ?>

<div class="container_12">
  <div class="grid_12">
    <h2>Les apéros à venir</h2>
  </div>
  <div class="clear"></div>
</div>

<?php foreach ($comingAperos as $apero): ?>
  <?php include_partial('page/aperoLight', array('apero' => $apero, 'moreInformations' => true)); ?>
<?php endforeach; ?>

<?php else: ?>

  <div class="container_12">
    <div class="grid_12">
      <p>Bientôt un prochain apéro sera mis en place. Revenez d'ici quelques jours...</p>
    </div>
    <div class="clear"></div>
  </div>

<?php endif; ?>

<?php if (count($passedAperos) > 0): ?>

<div class="container_12">
  <div class="grid_12">
    <h2>Les apéros déjà finis</h2>
  </div>
  <div class="clear"></div>
</div>

<?php foreach ($passedAperos as $apero): ?>
  <?php include_partial('page/aperoLight', array('apero' => $apero, 'moreInformations' => true)); ?>
<?php endforeach; ?>

<?php endif; ?>