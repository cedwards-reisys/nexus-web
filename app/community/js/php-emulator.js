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
function vB_PHP_Emulator(){}vB_PHP_Emulator.prototype.stripos=function(A,B,C){if(typeof C=="undefined"){C=0}index=A.toLowerCase().indexOf(B.toLowerCase(),C);return(index==-1?false:index)};vB_PHP_Emulator.prototype.ltrim=function(A){return A.replace(/^\s+/g,"")};vB_PHP_Emulator.prototype.rtrim=function(A){return A.replace(/(\s+)$/g,"")};vB_PHP_Emulator.prototype.trim=function(A){return this.ltrim(this.rtrim(A))};vB_PHP_Emulator.prototype.preg_quote=function(A){return A.replace(/(\+|\{|\}|\(|\)|\[|\]|\||\/|\?|\^|\$|\\|\.|\=|\!|\<|\>|\:|\*)/g,"\\$1")};vB_PHP_Emulator.prototype.match_all=function(C,E){var A=C.match(RegExp(E,"gim"));if(A){var F=new Array();var B=new RegExp(E,"im");for(var D=0;D<A.length;D++){F[F.length]=A[D].match(B)}return F}else{return false}};vB_PHP_Emulator.prototype.unhtmlspecialchars=function(F,E){var D=new Array(/&lt;/g,/&gt;/g,/&quot;/g,/&amp;/g);var C=new Array("<",">",'"',"&");for(var B in D){if(YAHOO.lang.hasOwnProperty(D,B)){F=F.replace(D[B],C[B])}}if(E){if(is_ie){F=F.replace(/\n/g,"<#br#>")}var A=document.createElement("textarea");A.innerHTML=F;F=A.value;if(null!=A.parentNode){A.parentNode.removeChild(A)}if(is_ie){F=F.replace(/<#br#>/g,"\n")}return F}return F};vB_PHP_Emulator.prototype.unescape_cdata=function(C){var B=/<\=\!\=\[\=C\=D\=A\=T\=A\=\[/g;var A=/\]\=\]\=>/g;return C.replace(B,"<![CDATA[").replace(A,"]]>")};vB_PHP_Emulator.prototype.htmlspecialchars=function(F){var C=(navigator.userAgent.toLowerCase().indexOf("mac")!=-1);var A=$.browser.msie;var E=new Array((C&&A?new RegExp("&","g"):new RegExp("&(?!#[0-9]+;)","g")),new RegExp("<","g"),new RegExp(">","g"),new RegExp('"',"g"));var D=new Array("&amp;","&lt;","&gt;","&quot;");for(var B=0;B<E.length;B++){F=F.replace(E[B],D[B])}return F};vB_PHP_Emulator.prototype.in_array=function(D,C,B){var E=new String(D);var A;if(B){E=E.toLowerCase();for(A in C){if(YAHOO.lang.hasOwnProperty(C,A)){if(C[A].toLowerCase()==E){return A}}}}else{for(A in C){if(YAHOO.lang.hasOwnProperty(C,A)){if(C[A]==E){return A}}}}return -1};vB_PHP_Emulator.prototype.str_pad=function(C,A,B){C=new String(C);B=new String(B);if(C.length<A){padtext=new String(B);while(padtext.length<(A-C.length)){padtext+=B}C=padtext.substr(0,(A-C.length))+C}return C};vB_PHP_Emulator.prototype.urlencode=function(D){D=escape(D.toString()).replace(/\+/g,"%2B");var B=D.match(/(%([0-9A-F]{2}))/gi);if(B){for(var C=0;C<B.length;C++){var A=B[C].substring(1,3);if(parseInt(A,16)>=128){D=D.replace(B[C],"%u00"+A)}}}D=D.replace("%25","%u0025");return D};vB_PHP_Emulator.prototype.ucfirst=function(D,A){if(typeof A!="undefined"){var B=D.indexOf(A);if(B>0){D=D.substr(0,B)}}D=D.split(" ");for(var C=0;C<D.length;C++){D[C]=D[C].substr(0,1).toUpperCase()+D[C].substr(1)}return D.join(" ")};var PHP=new vB_PHP_Emulator();