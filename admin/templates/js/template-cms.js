    /**
     *	Template CMS JS module
     *	@package TemplateCMS
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2011 - 2012 Romanenko Sergey / Awilum
     *	@version $Id$
     *	@since 2.0
     *  @license http://opensource.org/licenses/gpl-license.php GNU Public License
     *  TemplateCMS is free software. This version may have been modified pursuant
     *  to the GNU General Public License, and as distributed it includes or
     *  is derivative of works licensed under the GNU General Public License or
     *  other free or open source software licenses.
     *  See COPYING.txt for copyright notices and details.
     *  @filesource
     */

/* Confirm delete */
function confirmDelete(msg){var data=confirm(msg+" ?"); return data;}

/* jQuery Reveal Plugin 1.0 Copyright 2010, ZURB */
(function($){$("a[data-reveal-id]").live("click",function(e){e.preventDefault();var modalLocation=$(this).attr("data-reveal-id");$("#"+modalLocation).reveal($(this).data())});$.fn.reveal=function(options){var defaults={animation:"fade",animationspeed:300,closeonbackgroundclick:true,dismissmodalclass:"close-reveal-modal"};var options=$.extend({},defaults,options);return this.each(function(){var modal=$(this),topMeasure=parseInt(modal.css("top")),topOffset=modal.height()+topMeasure,locked=false,modalBG=$(".reveal-modal-bg");if(modalBG.length==0){modalBG=$('<div class="reveal-modal-bg" />').insertAfter(modal)}openModal();var closeButton=$("."+options.dismissmodalclass).bind("click.modalEvent",closeModal);if(options.closeonbackgroundclick){modalBG.css({cursor:"pointer"});modalBG.bind("click.modalEvent",closeModal)}function openModal(){modalBG.unbind("click.modalEvent");$("."+options.dismissmodalclass).unbind("click.modalEvent");if(!locked){lockModal();if(options.animation=="fadeAndPop"){modal.css({top:$(document).scrollTop()-topOffset,opacity:0,visibility:"visible"});modalBG.fadeIn(options.animationspeed/2);modal.delay(options.animationspeed/2).animate({top:$(document).scrollTop()+topMeasure,opacity:1},options.animationspeed,unlockModal())}if(options.animation=="fade"){modal.css({opacity:0,visibility:"visible",top:$(document).scrollTop()+topMeasure});modalBG.fadeIn(options.animationspeed/2);modal.delay(options.animationspeed/2).animate({opacity:1},options.animationspeed,unlockModal())}if(options.animation=="none"){modal.css({visibility:"visible",top:$(document).scrollTop()+topMeasure});modalBG.css({display:"block"});unlockModal()}}}function closeModal(){if(!locked){lockModal();if(options.animation=="fadeAndPop"){modalBG.delay(options.animationspeed).fadeOut(options.animationspeed);modal.animate({top:$(document).scrollTop()-topOffset,opacity:0},options.animationspeed/2,function(){modal.css({top:topMeasure,opacity:1,visibility:"hidden"});unlockModal()})}if(options.animation=="fade"){modalBG.delay(options.animationspeed).fadeOut(options.animationspeed);modal.animate({opacity:0},options.animationspeed,function(){modal.css({opacity:1,visibility:"hidden",top:topMeasure});unlockModal()})}if(options.animation=="none"){modal.css({visibility:"hidden",top:topMeasure});modalBG.css({display:"none"})}}}function unlockModal(){locked=false}function lockModal(){locked=true}})}})(jQuery);