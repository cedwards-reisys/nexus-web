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
window.vBulletin=window.vBulletin||{};window.vBulletin.phrase=window.vBulletin.phrase||{};window.vBulletin.phrase.precache=window.vBulletin.phrase.precache||[];window.vBulletin.phrase.precache=$.merge(window.vBulletin.phrase.precache,["follow","following","following_pending"]);window.vBulletin.options=window.vBulletin.options||{};window.vBulletin.options.precache=window.vBulletin.options.precache||[];window.vBulletin.options.precache=$.merge(window.vBulletin.options.precache,[]);(function(){vBulletin.subscribe={};vBulletin.subscribe.subscribe=function(A){if($(A).hasClass("isSubscribed")||$(A).hasClass("is-pending")){return true}var E=parseInt($(A).attr("data-node-id"),10),D={},B="";if($(A).hasClass("is-topic")){B="/ajax/api/follow/add";D.follow_item=E;D.type="follow_contents";vBulletin.subscribe.subscribeRequest(A,B,D)}else{var C={};C.channelid=E;C.recipient=parseInt($(A).attr("data-owner-id"),10);if($(A).hasClass("is-blog-channel")){C.requestType="member"}else{if($(A).hasClass("is-sg-channel")){C.requestType="sg_subscriber"}else{C.requestType="subscriber"}}vBulletin.AJAX({url:pageData.baseurl+"/ajax/api/node/requestChannel",data:C,success:function(F){vBulletin.subscribe.subscribeRequestSuccess(A,F)},error:function(){openAlertDialog({title:vBulletin.phrase.get("follow"),message:vBulletin.phrase.get("follow_error"),iconType:"error"})}})}};vBulletin.subscribe.subscribeRequest=function(A,B,C){$.ajax({url:pageData.baseurl+B,data:C,type:"POST",dataType:"json",success:function(D){vBulletin.subscribe.subscribeRequestSuccess(A,D)},error:function(){openAlertDialog({title:vBulletin.phrase.get("follow"),message:vBulletin.phrase.get("follow_error"),iconType:"error"})}})};vBulletin.subscribe.subscribeRequestSuccess=function(B,A){if(!isNaN(A)&&A==1){$(B).children(".button-text-primary").text(vBulletin.phrase.get("following"));$(B).addClass("isSubscribed special").removeClass("secondary")}else{if(!isNaN(A)&&A>1){$(B).children(".button-text-primary").text(vBulletin.phrase.get("following_pending"));$(B).addClass("is-pending special").removeClass("secondary")}else{if(A.errors){openAlertDialog({title:vBulletin.phrase.get("follow"),message:vBulletin.phrase.get(A.errors[0][0]),iconType:"error"})}else{openAlertDialog({title:vBulletin.phrase.get("follow"),message:vBulletin.phrase.get("follow_error"),iconType:"error"})}}}};vBulletin.subscribe.join=function(A){var C=parseInt($(A).attr("data-node-id"),10),B=parseInt($(A).attr("data-owner-id"),10);$.ajax({url:vBulletin.getAjaxBaseurl()+"/ajax/api/node/requestChannel",data:({channelid:C,recipient:B,requestType:"sg_member"}),type:"POST",dataType:"json",success:function(D){if(D.errors){openAlertDialog({title:vBulletin.phrase.get("join"),message:vBulletin.phrase.get(D.errors[0]),iconType:"error"})}else{if(D===true){$(A).children(".button-text-primary").text(vBulletin.phrase.get("joined"));$(A).addClass("has-joined special").removeClass("secondary")}else{if(!isNaN(D)){$(A).children(".button-text-primary").text(vBulletin.phrase.get("following_pending"));$(A).addClass("is-pending special").removeClass("secondary")}}}},error:function(){openAlertDialog({title:vBulletin.phrase.get("join"),message:vBulletin.phrase.get("join_error"),iconType:"error"})}})};vBulletin.subscribe.leave=function(A){var B=parseInt($(A).attr("data-node-id"),10);$.ajax({url:vBulletin.getAjaxBaseurl()+"/ajax/api/blog/leaveChannel",data:({channelId:B}),type:"POST",dataType:"json",success:function(C){if(C.errors){openAlertDialog({title:vBulletin.phrase.get("leave"),message:vBulletin.phrase.get(C.errors[0]),iconType:"error"})}else{if(C===true){var D=$(A);if(D.hasClass("follow-btn")){D.children(".button-text-primary").text(vBulletin.phrase.get("follow"));D.removeClass("isSubscribed unfollow-btn");D.addClass("special")}else{D.children(".button-text-primary").text(vBulletin.phrase.get("join"));D.removeClass("has-joined special")}location.reload()}else{openAlertDialog({title:vBulletin.phrase.get("leave"),message:vBulletin.phrase.get("invalid_server_response_please_try_again"),iconType:"error"})}}},error:function(){openAlertDialog({title:vBulletin.phrase.get("leave"),message:vBulletin.phrase.get("invalid_server_response_please_try_again"),iconType:"error"})}})};vBulletin.subscribe.unsubscribe=function(A){var B=$(A).attr("data-node-id");$.ajax({url:vBulletin.getAjaxBaseurl()+"/ajax/api/follow/delete",data:({follow_item:B,type:"follow_contents"}),type:"POST",dataType:"json",success:function(C){if(C==1){$(A).removeClass("isSubscribed unfollow-btn special").addClass("secondary").children(".button-text-primary").text(vBulletin.phrase.get("follow"))}else{if(C.errors){openAlertDialog({title:vBulletin.phrase.get("following_remove"),message:vBulletin.phrase.get(C.errors[0][0]),iconType:"error"})}else{openAlertDialog({title:vBulletin.phrase.get("follow"),message:vBulletin.phrase.get("unfollow_error"),iconType:"error"})}}},error:function(){openAlertDialog({title:vBulletin.phrase.get("follow"),message:vBulletin.phrase.get("unfollow_error"),iconType:"error"})}})};vBulletin.subscribe.getUnsubscribeOverlay=function(G){var H="",A=$(G).closest(".list-item").find("ul.unfollow_panel").filter(function(){var I=parseInt($(this).attr("data-node-id"),10);if(typeof I=="number"){H=I;return true}}),F=A.children(),D=false,C=false,B=false,E="";$.each(F,function(I,J){if($(J).hasClass("unfollow_content")){C=$(J).attr("data-item-id")}else{if($(J).hasClass("unfollow_member")){D=$(J).attr("data-item-id")}else{if($(J).hasClass("unfollow_channel")){B=$(J).attr("data-item-id")}}}});if(C){E+="&content="+C}if(D){E+="&member="+D}if(B){E+="&channel="+B}if(H){$.ajax({url:vBulletin.getAjaxBaseurl()+"/profile/getUnsubscribeOverlay?userId="+pageData.userid+E+"&nodeId="+H,success:function(I){var J=$(".unsubscribe-overlay-container");if(J){J.html(I);J.children(".unsubscribe-overlay").dialog({title:vBulletin.phrase.get("following_remove"),autoOpen:false,modal:true,resizable:false,closeOnEscape:false,showCloseButton:false,width:450,dialogClass:"dialog-container unsubscribe-dialog-container dialog-box"}).dialog("open")}},error:function(){openAlertDialog({title:vBulletin.phrase.get("following_remove"),message:vBulletin.phrase.get("unable_to_contact_server_please_try_again"),iconType:"error"})}})}};vBulletin.subscribe.removeUnsubscribeOptions=function(E,B){var D=$(E).parents(".unsubscribe-overlay"),F=parseInt(D.attr("data-node-id"),10),C=$(D).children(".unsubscribe-options"),A=[];if(typeof F!="number"){return false}var G="li :checkbox[name=unsubscribeItems]";if(!(($(C).find("li #"+F+"-unsubscribeAll:checkbox:checked")).length===1)){G+=":checked"}$.each($(C).find(G),function(H,K){var I=$(K).attr("data-type"),J=parseInt($(K).attr("data-item-id"),10);if(J&&typeof J=="number"){A.push({type:I,itemId:J})}});if(A.length>0){$.ajax({url:vBulletin.getAjaxBaseurl()+"/ajax/api/follow/unsubscribeItems",data:({unsubscribeItems:A}),type:"POST",dataType:"json",success:function(H){if(H===true){$(D).dialog("close").remove();B.updatePageNumber(1).applyFilters(false)}else{if(H.errors!=undefined){openAlertDialog({title:vBulletin.phrase.get("profile_guser"),message:vBulletin.phrase.get("unable_to_contact_server_please_try_again"),iconType:"error"})}}},error:function(){openAlertDialog({title:vBulletin.phrase.get("profile_guser"),message:vBulletin.phrase.get("unable_to_contact_server_please_try_again"),iconType:"error"})}})}else{openAlertDialog({title:vBulletin.phrase.get("profile_guser"),message:vBulletin.phrase.get("unsubscribe_overlay_error"),iconType:"error"})}};vBulletin.subscribe.updateSubscribeButton=function(B){if(typeof B!="undefined"){var A=$(".follow-btn").filter(function(){return $(this).data("node-id")==pageData.nodeid&&pageData.nodeid!=pageData.channelid});switch(B){case 0:A.addClass("secondary").removeClass("isSubscribed unfollow-btn special").children(".button-text-primary").text(vBulletin.phrase.get("follow"));break;case 1:A.addClass("isSubscribed special").removeClass("secondary").children(".button-text-primary").text(vBulletin.phrase.get("following"));break;case 2:A.addClass("is-pending special").removeClass("secondary").children(".button-text-primary").text(vBulletin.phrase.get("following_pending"));break}}};$(".follow-btn").on("click",function(A){if(!$(this).hasClass("isSubscribed")){vBulletin.subscribe.subscribe(this)}else{if($(this).hasClass("unfollow-btn")){if($(this).hasClass("is-blog-channel")){vBulletin.subscribe.leave(this)}else{vBulletin.subscribe.unsubscribe(this)}}}});$(".follow-btn").on("mouseover",function(A){if($(this).hasClass("isSubscribed")&&!$(this).hasClass("is-owner")){$(this).addClass("unfollow-btn secondary").removeClass("special");var B=vBulletin.phrase.get("following_remove");$(this).children(".button-text-primary").text(B)}});$(".follow-btn").on("mouseout",function(A){if($(this).hasClass("unfollow-btn")){$(this).removeClass("unfollow-btn secondary").addClass("special");var B=vBulletin.phrase.get("following");$(this).children(".button-text-primary").text(B)}});$(".share-btn").on("click",function(A){alert("Not yet implemented");return false});$(".join-btn").on("click",function(A){if(!$(this).hasClass("has-joined")&&!$(this).hasClass("is-pending")){vBulletin.subscribe.join(this)}else{if($(this).hasClass("leave-btn")&&!$(this).hasClass("is-owner")){vBulletin.subscribe.leave(this)}}});$(".join-btn").on("mouseover",function(A){if($(this).hasClass("has-joined")&&!$(this).hasClass("is-owner")){$(this).addClass("leave-btn secondary").removeClass("special");var B=vBulletin.phrase.get("leave");$(this).children(".button-text-primary").text(B)}});$(".join-btn").on("mouseout",function(A){if($(this).hasClass("leave-btn")&&!$(this).hasClass("is-owner")){$(this).removeClass("leave-btn secondary").addClass("special");var B=vBulletin.phrase.get("joined");$(this).children(".button-text-primary").text(B)}})})();