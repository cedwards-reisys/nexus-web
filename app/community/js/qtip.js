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
window.vBulletin=window.vBulletin||{};window.vBulletin.phrase=window.vBulletin.phrase||{};window.vBulletin.phrase.precache=window.vBulletin.phrase.precache||[];window.vBulletin.phrase.precache=$.merge(window.vBulletin.phrase.precache,["no_preview_text_available","loading"]);window.vBulletin.options=window.vBulletin.options||{};window.vBulletin.options.precache=window.vBulletin.options.precache||[];window.vBulletin.options.precache=$.merge(window.vBulletin.options.precache,["threadpreview"]);(function(){(function(){function B(F,E){var D=E.elements.tooltip;window.setTimeout(function(){var M=parseInt(D.css("left"),10),G=$(window).scrollLeft();if(M<G){D.css("left",G+1)}else{var L=D.outerWidth(),I=$(window).width(),J=M+L,H=G+I;if(J>H){var N=J-H,K=M-N;D.css("left",K-1)}}},0)}var C=$.fn.qtip;$.fn.qtip=function(){if(typeof arguments[0]=="object"){if(typeof arguments[0].events=="undefined"){arguments[0].events={}}if(typeof arguments[0].events.move=="function"){arguments[0].events.move=vBulletin.runBeforeCallback(arguments[0].events.move,B)}else{arguments[0].events.move=B}}return C.apply(this,arguments)}})();$(".js-tooltip[title]").qtip({style:{classes:"ui-tooltip-shadow"}});if(vBulletin.options.get("threadpreview")>0&&(($(".channel-content-widget").length>0&&$(".channel-content-widget").eq(0).attr("data-canviewtopiccontent"))||$(".search-results-widget").length>0)){var A=function(){var B=$(this);if(B.data("vb-qtip-preview-initialized")=="1"){return }B.data("vb-qtip-preview-initialized","1");var C=B.closest(".topic-item").attr("data-node-id")||B.closest(".js-post").attr("data-node-id");B.qtip({content:{text:function(E,D){console.log("Qtip fetching topic preview.");$.ajax({url:vBulletin.getAjaxBaseurl()+"/ajax/fetch-node-preview?nodeid="+C,dataType:"json"}).done(function(F){if(typeof (F)!="string"&&typeof (F[0])=="string"){F=F[0]}if($.trim(F)!=""){var G='<div class="b-topicpreview__previewtext">'+F+"</div>";D.set("content.text",G)}else{var G='<div class="b-topicpreview__previewtext">'+vBulletin.phrase.get("no_preview_text_available")+"</div>";D.set("content.text",G)}}).fail(function(H,F,G){D.set("content.text",F+": "+G)});return vBulletin.phrase.get("loading")}},show:{delay:500},position:{my:"top left",at:"bottom right",viewport:$(window)},style:{classes:"ui-tooltip-shadow ui-tooltip-rounded b-topicpreview"}});console.log("Qtip topic preview initialized.");B.trigger("mouseover")};$(document).off("mouseover",".topic-list-container .topic-title, .conversation-list .post-title, .conversation-list .b-post__title").on("mouseover",".topic-list-container .topic-title, .conversation-list .post-title, .conversation-list .b-post__title",A)}})();