<div class="container_12">

  <div class="grid_2" style="margin-bottom:20px;">
    <p>
      Bonjour <?php echo $sf_user->getGuardUser()->getProfile()->getFirstName(); ?>&nbsp;!
    </p>
  </div>
  
  <div class="buttons grid_10 alignright" style="margin-bottom:20px;">
    <?php echo link_to('Mes apéros', '@account_aperos', array('class' => 'btn btn_blue')); ?>
    <?php echo link_to('Informations', '@account_informations', array('class' => 'btn btn_blue')); ?>
    <?php echo link_to('Mot de passe', '@account_password', array('class' => 'btn btn_blue')); ?>
    <?php echo link_to('Se déconnecter', '@sf_guard_signout', array('class' => 'btn btn_blue')); ?>
    <?php echo link_to('Gestion des apéros', '@apero_adminApero', array('class' => 'btn btn_blue')); ?>
    <?php if ($sf_user->isSuperadmin()): ?>
    <div style="margin-top:20px;">
      <?php echo link_to('Gestion des utilisateurs', '@sf_guard_user', array('class' => 'btn btn_blue')); ?>
    </div>
    
    <?php endif; ?>
  </div>
  <div class="clear"></div>

</div>