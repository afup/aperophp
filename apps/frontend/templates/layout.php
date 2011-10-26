<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="<?php echo image_path('/images/favicon.ico'); ?>" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  <body>
    <?php include_partial('global/flashes'); ?>
    <div id="header">
      <div class="container_12">
        <div class="grid_4">
          <?php echo link_to(image_tag('/images/logo.png', array('alt' => 'Apéro PHP')), '@homepage'); ?>
        </div>
        <div class="grid_8 alignright">
          <ul id="menu">
            <li><?php echo link_to('Apéros', '@homepage', array('class' => (in_array($sf_context->getActionName(), array('homepage', 'apero')))?'current':'')); ?></li>
            <li><?php echo link_to('Concept', '@concept', array('class' => ($sf_context->getActionName() == 'concept')?'current':'')); ?></li>
            <li><?php echo link_to('Contact', '@contact', array('class' => ($sf_context->getActionName() == 'contact')?'current':'')); ?></li>
          <?php if ($sf_user->isAuthenticated()): ?>
          <li><?php echo link_to('Mon compte', '@account', array('class' => (in_array($sf_context->getActionName(), array('account', 'accountInformations', 'accountAperos', 'accountPassword')))?'current':'')); ?></li>
          <?php else: ?>
          <li><?php echo link_to('Connexion / Inscription', '@sf_guard_signin', array('class' => ($sf_context->getActionName() == 'signin')?'current':'')); ?></li>
          <?php endif; ?>
          </ul>

          <h3>Un langage, des éléPHPants, une communauté...</h3>
          <p>Pour se retrouver tranquillement autour d'un verre, partager et rencontrer les acteurs du web.</p>
        </div>
        <div class="clear"></div>
      </div>
    </div>
    <div id="container">
      <div class="spacer container_16">
        <div class="grid_16">
          &nbsp;
        </div>
      </div>

      <?php echo $sf_content ?>
    </div>
    <div id="footer">
      <div class="spacer container_16">
        &nbsp;
      </div>
      <div class="container_12">
        <div class="alignleft grid_3">
          <?php echo link_to(image_tag('/images/twitter-16x16.png', array('alt' => 'Twitter')), 'http://twitter.com/afup'); ?> <?php echo link_to(image_tag('/images/facebook-16x16.png', array('alt' => 'Facebook')), 'http://www.facebook.com/'); ?>
        </div>
        <div class="aligncenter copyright grid_6">
          <?php echo date('Y'); ?>&nbsp;&copy;&nbsp;<?php echo link_to('Afup', 'http://www.afup.org', array('target' => '_blank')); ?> - Propulsé par <?php echo link_to('symfony' , 'http://www.symfony-project.com', array('target' => '_blank')); ?>
        </div>
        <div class="alignright copyright grid_3">
          <?php echo link_to(image_tag('/images/email-16x16.png', array('alt' => 'bureau@afup.org')), '@contact'); ?>
        </div>
        <div class="clear"></div>
      </div>
    </div>
    <script type="text/javascript">
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-192127-3']);
      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    </script>
  </body>
</html>
