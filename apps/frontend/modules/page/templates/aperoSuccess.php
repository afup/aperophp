<?php include_partial('page/aperoLight', array('apero' => $apero, 'moreInformations' => false)); ?>

<div class="container_12 apero_full">

  <div class="grid_6">
    <h2>Thématique</h2>
    <?php echo $apero->getRawValue()->getDescription(); ?></p>
    <p class="address"><strong>Lieu</strong> :<br /><?php echo $apero->getLocationName(); ?>, <span id="address"><?php echo $apero->getAddress(); ?></span></p>
    <p class="max_people"><strong>Maximum</strong> :<br /><?php echo $apero->getMaxPeople(); ?> personnes</p>
    <?php if ($apero->getPrice() > 0): ?><p class="price"><strong>Participation</strong> :<br /><?php echo format_currency($apero->getPrice(), 'EUR'); ?></p><?php endif; ?>
    <div id="map"></div>
  </div>

  <div class="prefix_1 grid_5">
    <h2>Liste des participants</h2>
    <?php $i = 0; foreach ($apero->getAperoUser() as $aperoUser): ?>
    <?php $user = $aperoUser->getsfGuardUser()->getProfile(); ?>
    <div class="people_card_mini<?php echo ($i % 2)?' even':' odd'; ?>">
      <?php if ($sf_user->isAuthenticated()): ?>
      <a href="#" class="toggle"><?php echo image_tag('/images/toggle_closed.png', array('alt' => 'toggle')); ?></a>
      <?php endif; ?>
      <?php echo thumbnail_tag(($user->getAvatar())?'/uploads/avatar/'.$user->getAvatar():'/images/noavatar.png', 60, 60, array('alt' => 'avatar', 'class' => 'avatar')); ?>
      <p class="name"><?php echo $user->getFullName(); ?></p>
      <p><?php echo ($user->getWebsite())?link_to($user->getWebsite(), $user->getWebsite(), array('target' => '_blank')):''; ?></p>
      <div class="clearleft"></div>
      <?php if ($sf_user->isAuthenticated()): ?>
      <div class="more">
        <p><strong>Email</strong> : <?php echo mail_to($user->getEmail(), $user->getEmail(), 'encode=true'); ?></p>
        <?php if ($user->getMobilePhone()): ?>
        <p><strong>Mobile</strong> : <?php echo $user->getMobilePhone(); ?></p>
        <?php endif; ?>
        <?php if ($user->getCompany()): ?>
        <p><strong>Société</strong> : <?php echo $user->getCompany(); ?></p>
        <?php endif; ?>
        <?php if ($user->getFunction()): ?>
          <p><strong>Fonction</strong> : <?php echo $user->getFunction(); ?></p>
        <?php endif; ?>
        <?php if ($user->getFacebook() || $user->getTwitter()): ?>
        <p class="social">
          <strong>Social</strong> :
          <?php if ($user->getFacebook()): ?>
          <?php echo link_to(image_tag('/images/facebook-16x16.png', array('alt' => 'facebook')), $user->getFacebook(), array('target' => '_blank')); ?>
          <?php endif; ?>
          <?php if ($user->getTwitter()): ?>
          <?php echo link_to(image_tag('/images/twitter-16x16.png', array('alt' => 'twitter')), $user->getTwitter(), array('target' => '_blank')); ?>
          <?php endif; ?>
        </p>
        <?php endif; ?>
        <?php if ($user->getFunction()): ?>
          <p><strong>Description</strong> : <br /><?php echo nl2br($user->getDescription()); ?></p>
        <?php endif; ?>

      </div>
      <?php endif; ?>

    </div>
    <?php $i++; endforeach; ?>
  </div>
  
  <div class="clear"></div>

</div>

<script type="text/javascript">
  jQuery(document).ready(function(){
    var address = jQuery('#address').html();
    initialize('map');
    codeAddress(address);
  });
</script>
