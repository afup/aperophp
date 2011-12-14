<div id="cs_right">
  <p>Soyez informé en avant première de la mise en ligne du site et des premiers apéros symfony !</p>
    <form action="<?php echo url_for('@comingsoon'); ?>" method="post" name="<?php echo $Form->getName(); ?>">
      <?php echo $Form->renderHiddenFields(); ?>
      <div id="infos">
        <?php echo $Form['email']->renderError(); ?>
        <?php if ($sf_user->hasFlash('notice')): ?>
        <span class="notice"><?php echo $sf_user->getFlash('notice'); ?></span>
        <?php endif; ?>
      </div>
      <div id="newsletter">
        <?php echo $Form['email']->render(array('class' => 'email')); ?>
        <input type="submit" class="submit" value="" />
      </div>
    </form>
</div>

<div id="cs_left">
  <h1><?php echo image_tag('/images/cs_logo.png', array('alt' => 'Apéro symfony')); ?></h1>
</div>

<div class="clearboth"></div>

<div id="cs_footer">AFUP&nbsp;&copy;&nbsp;<?php echo date('Y'); ?> - Les apéros PHP sont organisés par l'AFUP. Pour plus de renseignement : <?php echo mail_to('bureau@afup.org', 'bureau@afup.org', 'encode=true'); ?></div>