jQuery(document).ready(function(){
 
  if(jQuery('#flash'))
  {
    var flash = jQuery("#flash");
    var flashHeight = flash.height();
    flash.css({height:flashHeight + "px", top:"-" + (flashHeight + 10) + "px", lineHeight:flashHeight + "px", opacity:0});
    flash.animate({top:0, opacity:0.9}, "fast", "swing", function(){
      setTimeout(function(){if (!flash.is(":animated")) {flash.animate({top:"-" + (flashHeight + 10), opacity:0},"fast");}}, 2500);
    });
  }

  jQuery('.people_card_mini .toggle').click(function(){
    if (jQuery(this).parent().find('.more').is(':visible'))
    {
      jQuery(this).find('img').attr('src', '/images/toggle_closed.png');
      jQuery(this).parent().find('.more').slideUp('fast');
    }
    else
    {
      jQuery(this).find('img').attr('src', '/images/toggle_open.png');
      jQuery('.people_card_mini .more').slideUp('fast');
      jQuery(this).parent().find('.more').slideDown('fast');
    }
    return false;
  });

});

