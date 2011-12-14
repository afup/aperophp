<?php include_partial('page/headerAccount'); ?>

<?php use_helper('I18N', 'Date') ?>
<?php include_partial('adminUser/assets') ?>
<div class="container_12">
  <div class="grid_12">

    <div id="sf_admin_container">
      <h1><?php echo __('Liste des utilisateurs', array(), 'messages') ?></h1>

      <?php include_partial('adminUser/flashes') ?>

      <div id="sf_admin_header">
        <?php include_partial('adminUser/list_header', array('pager' => $pager)) ?>
      </div>

      <div id="sf_admin_content">
        <form action="<?php echo url_for('sf_guard_user', array('action' => 'batch')) ?>" method="post">
        <?php include_partial('adminUser/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
        <ul class="sf_admin_actions">
          <?php include_partial('adminUser/list_batch_actions', array('helper' => $helper)) ?>
          <?php include_partial('adminUser/list_actions', array('helper' => $helper)) ?>
        </ul>
        </form>
      </div>

      <div id="sf_admin_footer">
        <?php include_partial('adminUser/list_footer', array('pager' => $pager)) ?>
      </div>
    </div>

  </div>
  <div class="clear"></div>
</div>
