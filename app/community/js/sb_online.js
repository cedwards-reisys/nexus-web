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
window.vBulletin=window.vBulletin||{};window.vBulletin.phrase=window.vBulletin.phrase||{};window.vBulletin.phrase.precache=window.vBulletin.phrase.precache||[];window.vBulletin.phrase.precache=$.merge(window.vBulletin.phrase.precache,["error","online_error_resolving_ip","showing_x_to_y_of_z_users","x_members_y_guests_online"]);(function(A){var B=[".wollist-widget"];if(!vBulletin.pageHasSelectors(B)){return false}A(document).ready(function(){var E=new vBulletin.pagination({context:A(".wollist-widget"),onPageChanged:function(G,H){var I=A(this).closest(".wollist-widget").data("widget-instance-id");C(I)}});function D(){A(".js-wol-username").each(function(){var G=A(this);G.qtip({content:G.parent().find(".js-wol-userinfo-tooltip"),style:{classes:"ui-tooltip-shadow ui-tooltip-rounded b-topicpreview"}})})}function F(){A(document).off("click",".js-pagenav .js-pagenav-button").on("click",".js-pagenav .js-pagenav-button",function(J){var I=A(this),H=I.closest(".wollist-widget").data("widget-instance-id"),G=I.data("page"),K=A(".pagenav-form");E.updatePageNavigation(K,G);C(H);return false})}function C(S,R){var G=A('div.wollist-widget[data-widget-instance-id="'+S+'"]');var Q=A('.pagenav-controls :input[name="page"]',G).val(),N=A(".widget-content",G),P=N.data("perpage"),M=N.data("thispageonly"),H=N.data("pagekey"),K=N.data("who"),I=N.data("resolve"),J=N.data("pagerouteid"),L={who:K,pagenumber:Q,perpage:P,resolveIp:I,pageRouteId:J};if(typeof R=="object"){L.sortfield=R.sortby;L.sortorder=R.sortorder}else{var O=A(".js-wol-sort-by").filter(function(){return A(this).find(".js-sort-arrow").is(".vb-icon-triangle-up-wide, .vb-icon-triangle-down-wide")});L.sortfield=O.data("sortby");L.sortorder=O.data("sortorder")}if(M=="1"){L.pagekey=H}A("body").css("cursor","wait");A.ajax({url:vBulletin.getAjaxBaseurl()+"/ajax/api/wol/refreshUsers",data:L,type:"POST",dataType:"json",complete:function(U,T){console.log("wol-refreshUsers complete.");A("body").css("cursor","auto")},success:function(T,W,X){console.log("wol-fetchAll successful!");if(T){if(!T.error){var U=((Q-1)*P)+1,Y=Q*P;Y=(Y>T.userCounts.total)?T.userCounts.total:Y;var V=A(".js-wol-sort-by");if(typeof R!="undefined"){V.find(".js-sort-arrow").removeClass("vb-icon-triangle-up-wide vb-icon-triangle-down-wide");V.each(function(){var Z=A(this);if(Z.data("sortby")==R.sortby){Z.find(".js-sort-arrow").addClass(R.sortorder=="asc"?"vb-icon-triangle-up-wide":"vb-icon-triangle-down-wide")}else{Z.data("sortorder","desc")}})}A(".widget-content .js-table-content",G).empty().html(T.template);A(".widget-content .user-counters",G).html(vBulletin.phrase.get("showing_x_to_y_of_z_users",U,Y,T.userCounts.total));A(".widget-content .user-counts",G).html(vBulletin.phrase.get("x_members_y_guests_online",T.userCounts.members,T.userCounts.guests));D();F()}else{openAlertDialog({title:"Online Users",message:vBulletin.phrase.get("error_x",T.error),iconType:"warning"})}}else{openAlertDialog({title:"Online Users",message:vBulletin.phrase.get("invalid_server_response_please_try_again"),iconType:"error"})}},error:function(V,U,T){console.log("wol-fetchAll failed! error:"+T);openAlertDialog({title:"Online Users",message:vBulletin.phrase.get("error_fetching_online_users_x",V.status),iconType:"error"})}})}A(".wollist-widget").each(function(){var H=A(this).attr("data-widget-instance-id"),G=parseInt(A(".widget-content",A(this)).attr("data-refresh"));if(G>0){window.setInterval(function(){C(H)},(G*1000))}});A(document).off("click",".js-wol-sort-by").on("click",".js-wol-sort-by",function(K){var G=A(this),I=G.closest(".wollist-widget").attr("data-widget-instance-id"),H=G.data("sortorder"),J={sortby:G.data("sortby"),sortorder:(H=="desc")?"asc":"desc"};G.data("sortorder",J.sortorder);C(I,J)});A(document).off("click",".resolveIpLink").on("click",".resolveIpLink",function(H){H.preventDefault();var G=A(this);A.ajax({url:vBulletin.getAjaxBaseurl()+"/ajax/call/bbcode/resolveIp",type:"post",data:{ip:G.text()},dataType:"json",success:function(I){if(!I||I.errors){openAlertDialog({title:vBulletin.phrase.get("error"),message:vBulletin.phrase.get("online_error_resolving_ip")+": "+vBulletin.phrase.get(I.errors[0]),iconType:"error"})}else{G.replaceWith(I)}},complete:function(){A("body").css("cursor","default")}})});D();F()})})(jQuery);