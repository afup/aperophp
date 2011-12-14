<?php use_helper('I18N', 'Date') ?>
<?php include_partial('adminUser/assets') ?>

<div class="container_12">
  <div class="grid_12">

    <div id="sf_admin_container">
      <h1><?php echo __('Ajout d\'un  nouvel utilisateur', array(), 'messages') ?></h1>

      <?php include_partial('adminUser/flashes') ?>

      <div id="sf_admin_header">
        <?php include_partial('adminUser/form_header', array('sf_guard_user' => $sf_guard_user, 'form' => $form, 'configuration' => $configuration)) ?>
      </div>

      <div id="sf_admin_content">
        <?php include_partial('adminUser/form', array('sf_guard_user' => $sf_guard_user, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
      </div>

      <div id="sf_admin_footer">
        <?php include_partial('adminUser/form_footer', array('sf_guard_user' => $sf_guard_user, 'form' => $form, 'configuration' => $configuration)) ?>
      </div>
    </div>
    
  </div>
  <div class="clear"></div>
</div>
