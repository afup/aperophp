<?php use_helper('I18N', 'Date') ?>
<?php include_partial('adminApero/assets') ?>

<div class="container_12">
  <div class="grid_12">

    <div id="sf_admin_container">
      <h1><?php echo __('Ajout d\'un  nouvel apéro', array(), 'messages') ?></h1>

      <?php include_partial('adminApero/flashes') ?>

      <div id="sf_admin_header">
        <?php include_partial('adminApero/form_header', array('apero' => $apero, 'form' => $form, 'configuration' => $configuration)) ?>
      </div>

      <div id="sf_admin_content">
        <?php include_partial('adminApero/form', array('apero' => $apero, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
      </div>

      <div id="sf_admin_footer">
        <?php include_partial('adminApero/form_footer', array('apero' => $apero, 'form' => $form, 'configuration' => $configuration)) ?>
      </div>
    </div>
    
  </div>
  <div class="clear"></div>
</div>
