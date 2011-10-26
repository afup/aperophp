<div class="container_16">
  <div class="grid_7 suffix_2">
    <p><i>Les apéros PHP sont organisés avec le concours de l'AFUP.</i></p>
    <div id="logo_afup">
      <?php echo link_to(image_tag('/images/afup_logo.png', array('alt' => '')), 'http://www.afup.org', array('target' => '_blank')); ?>
    </div>
    <p>
      AFUP - Apéro PHP<br />
      <span id="address">119 rue du chemin vert<br />75011 Paris</span><br />
      France
    </p>
    <p><strong>Email</strong> : <?php echo mail_to('bureau@afup.org', 'bureau@afup.org', 'encode=true'); ?></p>
    <div id="map" style="height:250px;"></div>
  </div>
  <div class="grid_7">
    <h2>Mentions légales</h2>
    <h2>Hébergement</h2>
    <p>
      Nexen Services SAS<br />
      1 rue Royale - 227 Bureaux de la colline<br />
      92210 Saint-Cloud
    </p>
    <br />

    <h2><?php echo __('Respect de la vie privée'); ?></h2>
    <p>www.aperophp.net est déclaré à la CNIL sous le numéro 00000.</p>
    <p>Les informations recueillies par le biais des formulaires en lignes sont exclusivement pour l'usage de l'AFUP et exploitées à des fins d'analyses ou avec votre accord pour des propositions commerciales concernant uniquement l'AFUP. Ces informations sont confidentielles et conservées par l'AFUP. Conformément à la loi « Informatique et Libertés » n°78-17, vous disposez d'un droit d'accès et de rectification de ces données. L'AFUP ne divulgera sous aucune forme ces informations à des tiers. Toutes demandes d'accès ou de rectification devront être demandées par écrit à l'adresse du siège social ou par e-mail. Vous trouverez nos coordonnées dans la page contact.</p>
    <br />

    <h2><?php echo __('Droits de propriété intellectuelle'); ?></h2>
    <p>Ce site constitue une oeuvre dont l'AFUP est l'auteur selon les articles L. 111.1 et suivants du Code de la propriété intellectuelle. Les textes, dessins, mises en page, images, sont la propriété de l'AFUP.</p>
    <p>Ce site est basé sur le <a href="https://github.com/yzalis/aperosymfony">code original de Benjamin Laugueux</a></p>
    <br />
    <h2>Réalisation du site</h2>
    <p>Ce site a été réalisé par l'AFUP.</p>
  </div>
  <div class="clear"></div>
</div>

<script type="text/javascript">
  jQuery(document).ready(function(){
    var address = jQuery('#address').html();
    initialize('map');
    codeAddress(address.replace('<br>', ' '));
  });
</script>
