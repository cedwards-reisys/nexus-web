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
var announcement_url="http://www.vbulletin.com/forum/";var membersarea_url="http://members.vbulletin.com/";if(typeof (vb_version)!="undefined"&&isNewerVersion(current_version,vb_version)){var t=fetch_object("news_table");var t_head_r=t.insertRow(0);t_head_c=t_head_r.insertCell(0);t_head_c.className="thead";t_head_c.innerHTML=newer_version_string.bold();var t_body_r=t.insertRow(1);var t_body_c=t_body_r.insertCell(0);t_body_c.className="alt1";t_body_p1=document.createElement("p");t_body_p1.className="smallfont";t_body_a1=document.createElement("a");t_body_a1.href=announcement_url+"forum/vbulletin-announcements/"+vb_announcementid;t_body_a1.target="_blank";t_body_a1.innerHTML=construct_phrase(latest_string,vb_version).bold();t_body_p1.appendChild(t_body_a1);t_body_p1.innerHTML+=". "+construct_phrase(current_string,current_version.bold())+".";t_body_c.appendChild(t_body_p1);t_body_p2=document.createElement("p");t_body_p2.className="smallfont";t_body_a2=document.createElement("a");t_body_a2.href=membersarea_url;t_body_a2.target="_blank";t_body_a2.innerHTML=construct_phrase(download_string,vb_version.bold());t_body_p2.appendChild(t_body_a2);t_body_c.appendChild(t_body_p2)}function create_cp_table(B){var A=document.createElement("table");A.cellPadding=4;A.cellSpacing=0;A.border=0;A.align="center";A.width="90%";A.className="tborder";if(B){A.id=B}return A}function news_loader(I){if(I.responseXML){var J=done_table;var A=false;var F="";var M="";var U="";var P="";var B=I.responseXML.getElementsByTagName("item");if(done_table){G=fetch_object("news_table");A=true}else{var G=fetch_object("news_table");if(B.length){fetch_object("admin_news").style.display="";var C=G.insertRow(0);cell=C.insertCell(0);cell.className="tcat";cell.align="center";cell.innerHTML=news_header_string.bold();A=true}}var R;for(R=0;R<B.length;R++){F=B[R].getElementsByTagName("guid")[0].firstChild.nodeValue;if(PHP.in_array(F,dismissed_news)==-1){J=true;M=B[R].getElementsByTagName("title")[0].firstChild.nodeValue;U=B[R].getElementsByTagName("description")[0].firstChild.nodeValue;P=B[R].getElementsByTagName("link")[0].firstChild.nodeValue;var V=U.match(/\[local\]((?!\[\/local\]).)*\[\/local\]/g);if(V!=null){sessionurl=(SESSIONHASH==""?"":"s="+SESSIONHASH+"&");var W;for(W=0;W<V.length;W++){U=U.replace(V[W],V[W].replace(/^\[local\](.*)\.php(\??)(.*)\[\/local\]$/,"$1"+local_extension+"?"+sessionurl+"$3"))}}var S=G.insertRow(G.rows.length);S.id="r1_"+F;var N=S.insertCell(0);N.className="thead";var H=document.createElement("input");H.type="submit";H.name="acpnews["+F+"]";H.className="button";if(is_ie){H.style.styleFloat=stylevar_right}else{H.style.cssFloat=stylevar_right}H.title="id="+F;H.value=dismiss_string;N.appendChild(H);var E=document.createTextNode(construct_phrase(vbulletin_news_string,M));N.appendChild(E);var Q=G.insertRow(G.rows.length);Q.id="r2_"+F;var L=Q.insertCell(0);L.className="alt2 smallfont";L.innerHTML=U+" ";if(P&&P!="http://"){link_elem=document.createElement("a");link_elem.href=P;link_elem.target="_blank";link_elem.innerHTML=view_string.bold();L.appendChild(link_elem)}}}if(A){if(B.length){var O=G.insertRow(G.rows.length);var K=O.insertCell(0);K.className=(J?"tfoot":"alt1");K.align="center";var X=document.createElement("a");X.href=show_all_news_link;X.innerHTML=show_all_news_string;if(K.currentStyle){X.style.color=K.currentStyle.color}else{if(window.getComputedStyle&&window.getComputedStyle(K,null)){X.style.color=window.getComputedStyle(K,null).color}}K.appendChild(X)}var D=fetch_tags(fetch_object("news_table"),"td");var T="alt1";for(R=0;R<D.length;R++){if(D[R].className=="alt1"||D[R].className=="alt2"){T=D[R].className}else{if(D[R].className=="alt2 smallfont"){if(T=="alt1"){T="alt2"}else{D[R].className="alt1 smallfont";T="alt1"}}}}}}}if(AJAX_Compatible){dismissed_news=dismissed_news.split(",");YAHOO.util.Connect.asyncRequest("POST","newsproxy.php",{success:news_loader,timeout:vB_Default_Timeout},SESSIONURL)};