/*=======================================================================*\
|| ###################################################################### ||
|| # vBulletin 5.1.9
|| # ------------------------------------------------------------------ # ||
|| # Copyright 2000-2015 vBulletin Solutions Inc. All Rights Reserved.  # ||
|| # This file may not be redistributed in whole or significant part.   # ||
|| # ----------------- VBULLETIN IS NOT FREE SOFTWARE ----------------- # ||
|| # http://www.vbulletin.com | http://www.vbulletin.com/license.html   # ||
|| ###################################################################### ||
\*========================================================================*/
var vBulletin_ColorPicker=function(A,C){$("<link>").appendTo("head").attr({rel:"stylesheet",type:"text/css",href:pageData.baseurl+"/js/colorpicker/css/colorpicker.css"});B();function B(){$(A).each(function(){var D=$(this);var F=$('<span class="'+(C.triggerClass?C.triggerClass:"colorPickerTrigger")+'"></span>').insertBefore(D);F.css("backgroundColor",D.val());var G=F.css("backgroundColor");D.off("keyup").on("keyup",function(){F.css("backgroundColor",D.val());F.ColorPickerSetColor(F.css("backgroundColor"));if(C.onChange){var H=F.ColorPickerGetColor();C.onChange.call(D,"#"+H.hex)}});var E={color:C.color?C.color:G,onChange:function(H,J,I){D.val("#"+J);F.css("backgroundColor","#"+J);if(C.onChange){C.onChange.call(D,"#"+J)}},onSubmit:function(H,K,I,J){$(J).val(K);D.val("#"+K);F.css("backgroundColor","#"+K);if(C.onSubmit&&typeof C.onSubmit=="function"){C.onSubmit.call(D,"#"+K)}}};if(C.fadeIn){E.onShow=function(H){$(H).fadeIn(C.fadeSpeed?C.fadeSpeed:500);if(C.onShow){C.onShow.call(H)}return false}}if(C.fadeOut){E.onHide=function(H){$(H).fadeIn(C.fadeSpeed?C.fadeSpeed:500);if(C.onHide){C.onHide.call(H)}return false}}if(!C.fadeIn&&C.onShow){E.onShow=C.onShow}if(!C.fadeOut&&C.onHide){E.onHide=C.onHide}F.ColorPicker(E)})}};