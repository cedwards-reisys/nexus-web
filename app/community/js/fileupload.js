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
window.vBulletin=window.vBulletin||{};window.vBulletin.phrase=window.vBulletin.phrase||{};window.vBulletin.phrase.precache=window.vBulletin.phrase.precache||[];window.vBulletin.phrase.precache=$.merge(window.vBulletin.phrase.precache,["add_caption","attach_files","click_to_add_caption","error_uploading_image","invalid_image_allowed_filetypes_are","remove_all_photos_confirmation_message","remove_all_photos_q","upload","upload_more","uploading","you_are_already_editing_continue","you_must_be_logged_in_to_upload_photos","enter_url_file"]);(function(D){window.vBulletin=window.vBulletin||{};vBulletin.upload=vBulletin.upload||{};vBulletin.gallery=vBulletin.gallery||{};vBulletin.permissions=vBulletin.permissions||{};var C=3,E=2,G=[];vBulletin.gallery.onBeforeSerializeEditForm=function(H){H.find(".caption-box").each(function(){var I=D(this);if(I.hasClass("placeholder")&&I.val()==I.attr("placeholder")){I.val("")}});return true};vBulletin.upload.changeButtonText=function(H,I){if(!H.data("default-text")){H.data("default-text",H.text())}H.text(I)};vBulletin.upload.restoreButtonText=function(H){if(H.data("default-text")){H.text(H.data("default-text"))}};vBulletin.upload.getAttachmentPermissions=function(J,H){if(typeof vBulletin.permissions[J]==="undefined"){vBulletin.permissions[J]={};var I={data:{extension:J,uploadFrom:H}};D.ajax({url:vBulletin.getAjaxBaseurl()+"/ajax/api/content_attach/getAttachmentPermissions",data:I,type:"POST",dataType:"json",async:false,success:function(K){var L;if(!K.errors){for(L in K){if(K.hasOwnProperty(L)){vBulletin.permissions[J][L]=K[L]}}}else{console.warn("/ajax/api/content_attach/getAttachmentPermissions error: "+K.errors[0])}},error:function(M,L,K){console.warn("/ajax/api/content_attach/getAttachmentPermissions error: "+K)}})}};vBulletin.upload.initializePhotoUpload=function(J){console.log("Fileupload: vBulletin.upload.initializePhotoUpload");if(!J||J.length==0){J=D("body");if(D(".js-photo-display",J).length>1){console.log("Fileupload: multiple upload forms, abort");return false}}D(".js-continue-button",J).off("click").on("click",function(O){console.log("Fileupload: continue");var M=D(document).data("gallery-container");var L=M.find(".js-photo-postdata");var N={};var K=D(this).closest(".js-photo-display");var P=[];L.empty();K.find(".photo-item-wrapper:not(.h-hide)").each(function(){var S=D(this).find(".filedataid"),R=S.val(),U=S.data("nodeid"),T=D.trim(D(this).find(".caption-box").val());D(this).removeClass("tmp-photo");if(typeof U!=="undefined"){D(this).removeClass("tmp-photo");var Q=D.inArray(U,G);if(Q==-1){G.push(U)}}L.append(D("<input />").attr({type:"hidden",name:"filedataid[]"}).val(R)).append(D("<input />").attr({type:"hidden",name:"title_"+R}).val(T));P.push({filedataid:R,title:T})});N.photocount=P.length;N.photos=P;K.find(".photo-item-wrapper:hidden").remove();K.dialog("close");if(N.photos.length>0){D.ajax({url:vBulletin.getAjaxBaseurl()+"/ajax/render/editor_gallery_photoblock",data:N,type:"POST",dataType:"json",success:function(Q){console.log("Fileupload: continue success");var R=D(Q);R.find(".b-gallery-thumbnail-list__aside").addClass("h-invisible");M.find(".js-gallery-content").removeClass("h-hide").find(".js-panel-content").empty().append(R);setTimeout(function(){R.find(".b-gallery-thumbnail-list__aside").removeClass("h-invisible")},500);vBulletin.upload.initializePhotoUpload(M);D(document).data("gallery-container",null)}});D(".js-edit-photos",M).removeClass("h-hide");vBulletin.upload.changeButtonText(D(".b-button--upload .js-upload-label",M),vBulletin.phrase.get("upload_more"))}else{D(".photo-display-result",M).empty();D(".js-edit-photos",M).addClass("h-hide");vBulletin.upload.restoreButtonText(D(".b-button--upload .js-upload-label",M))}});D(".js-edit-photos",J).off("click").on("click",function(L){console.log("Fileupload: edit photos");D(document).data("gallery-container",D(this).closest(".b-content-entry-panel__content--gallery"));var K=vBulletin.upload.getUploadedPhotosDlg(false);vBulletin.upload.relocateLastInRowClass(K.find(".photo-item-wrapper"));I(K);K.dialog("open");vBulletin.upload.adjustPhotoDialogForScrollbar(K);D(".b-button--upload",K).trigger("focus")});D(".js-cancel-button",J).off("click").on("click",function(M){console.log("Fileupload: cancel");var K=D(this).closest(".js-photo-display"),L=D(document).data("gallery-container");D(".photo-item-wrapper.tmp-photo",K).remove();D(".photo-item-wrapper",K).removeClass("h-hide");if(D(".js-panel-content",L).length>0){vBulletin.upload.changeButtonText(D(".b-button--upload .js-upload-label",L),vBulletin.phrase.get("upload_more"))}else{vBulletin.upload.restoreButtonText(D(".b-button--upload .js-upload-label",L))}K.dialog("close")});var I=function(K){if(D(".photo-display .photo-item-wrapper:not(.h-hide)",K).length>0){vBulletin.upload.changeButtonText(D(".b-button--upload .js-upload-label",K),vBulletin.phrase.get("upload_more"));D(".js-continue-button, .btnPhotoUploadSave",K).show()}else{vBulletin.upload.restoreButtonText(D(".b-button--upload .js-upload-label",K));D(".js-continue-button, .btnPhotoUploadSave",K).hide()}};D(".b-content-entry-panel__content--gallery, .js-profile-media-photoupload-dialog",J).fileupload({dropZone:null,dataType:"json",url:vBulletin.getAjaxBaseurl()+"/uploader/upload_photo",formData:function(L){console.log("Fileupload: gallery formData");var K=L.find(".b-content-entry-panel__content--gallery");if(K.length==0){K=D("<form>")}if(K.find('input[name="securitytoken"]').length){K.find('input[name="securitytoken"]').val(pageData.securitytoken)}else{K.append('<input type="hidden" name="securitytoken" value="'+pageData.securitytoken+'" />')}return K.find(":input").filter(function(){return !D(this).parent().hasClass("js-photo-postdata")}).serializeArray()},acceptFileTypes:/(gif|jpg|jpeg|jpe|png)$/i,add:function(N,M){console.log("Fileupload: gallery add");var L=D(this);D(document).data("gallery-container",L);var K=vBulletin.upload.getUploadedPhotosDlg(true);D(".upload-status-bar",K).removeClass("h-hide");vBulletin.upload.changeButtonText(D(".b-button--upload .js-upload-label",K),vBulletin.phrase.get("uploading"));M.submit();D(".b-button--upload",K).trigger("focus")},done:function(R,Q){console.log("Fileupload: gallery done");var N=D(this);D(document).data("gallery-container",N);var M=vBulletin.upload.getUploadedPhotosDlg(false);var S=vBulletin.phrase.get("error_uploading_image");var O="error";if(Q&&Q.result&&(!Q.result.errors||Q.result.errors.length==0)&&Q.result.edit){var K=D(Q.result.edit);var P=D(".photo-display",M);var L=M.parent();var T=D(".photo-item-wrapper:not(.h-hide)",P).length;K.addClass("tmp-photo");if((T+1)%C==0){K.addClass("last-in-row")}if((T+1)>vBulletin.contentEntryBox.ATTACHLIMIT){D(".js-attach-limit-warning",M).show()}vBulletin.upload.adjustPhotoDialogForScrollbar(M);P.append(K);K.fadeIn("slow",function(){A(P);M.dialog("option","position","center");if(L.hasClass("has-scrollbar")){P.animate({scrollTop:P[0].scrollHeight-P.height()},"slow")}});D(".js-continue-button, .btnPhotoUploadSave",M).show();return }else{if(Q&&Q.result&&Q.result.errors&&Q.result.errors.length>0){S=Q.result.errors[0][0]||Q.result.errors[0][1];switch(S){case"please_login_first":S=vBulletin.phrase.get("you_must_be_logged_in_to_upload_photos");O="warning";break;default:S=vBulletin.phrase.get(Q.result.errors[0]);O="warning";break}}else{S=vBulletin.phrase.get("unknown_error");O="warning"}}I(M);openAlertDialog({title:vBulletin.phrase.get("upload"),message:vBulletin.phrase.get(S),iconType:O})},fail:function(O,N){console.log("Fileupload: gallery fail");var L=vBulletin.phrase.get("error_uploading_image");var K="error";if(N&&N.files.length>0){switch(N.files[0].error){case"acceptFileTypes":L=vBulletin.phrase.get("invalid_image_allowed_filetypes_are");K="warning";break}}var M=vBulletin.upload.getUploadedPhotosDlg(false);I(M);openAlertDialog({title:vBulletin.phrase.get("upload"),message:L,iconType:K})},always:function(M,L){console.log("Fileupload: gallery always");var K=vBulletin.upload.getUploadedPhotosDlg(false);K.find(".upload-status-bar").addClass("h-hide");I(K)}});D(".b-content-entry-panel__content--attachment",J).off("click",".js-upload-from-url").on("click",".js-upload-from-url",function(L){var K=D(this);$promtDlg=openPromptDialog({title:vBulletin.phrase.get("enter_url_file"),message:"",buttonLabel:{okLabel:vBulletin.phrase.get("ok"),cancelLabel:vBulletin.phrase.get("cancel")},dontCloseOnOk:false,onClickOK:function(M){vBulletin.AJAX({async:false,url:vBulletin.getAjaxBaseurl()+"/uploader/url",data:{urlupload:M,attachment:1,uploadFrom:D(".js-uploadFrom",J).val()},skipdefaultsuccess:true,success:function(N){if(N.errors){var O=N.errors;if(D.isArray(O)&&O.length>0){O=O[0]}openAlertDialog({title:vBulletin.phrase.get("error"),message:vBulletin.phrase.get("error_x",vBulletin.phrase.get(O)),iconType:"error"})}else{if(N.imageUrl){$panel=D(".b-content-entry-panel__content--attachment");N.name=N.filename;H.call($panel,N)}}},fail:function(O){var P=vBulletin.phrase.get("error_uploading_image");var N="error";if(O&&O.files.length>0){switch(O.files[0].error){case"acceptFileTypes":P=vBulletin.phrase.get("invalid_image_allowed_filetypes_are");N="warning";break}}openAlertDialog({title:vBulletin.phrase.get("upload"),message:vBulletin.phrase.get(P),iconType:N})}})}});return false});D(".b-content-entry-panel__content--attachment",J).fileupload({dropZone:null,dataType:"json",url:vBulletin.getAjaxBaseurl()+"/uploader/upload",previewAsCanvas:false,autoUpload:true,formData:function(L){console.log("Fileupload: attachments formData");var K=L.find(".b-content-entry-panel__content--attachment");if(K.length==0){K=D("<form>")}if(K.find('input[name="securitytoken"]').length){K.find('input[name="securitytoken"]').val(pageData.securitytoken)}else{K.append('<input type="hidden" name="securitytoken" value="'+pageData.securitytoken+'" />')}return K.find(":input").filter(function(){return !D(this).parent().hasClass("js-attach-postdata")}).serializeArray()},add:function(L,K){console.log("Fileupload: attachments add");K.submit()},done:function(O,N){console.log("Fileupload: attachments done");var L=vBulletin.phrase.get("error_uploading_image");var K="error";var P=[];var M=this;if(N&&N.result){D.each(N.result,function(Q,R){if(!R.error){H.call(M,R);return }else{if(R.error[0]=="please_login_first"){P.push(vBulletin.phrase.get("you_must_be_logged_in_to_upload_photos"));return false}else{P.push(vBulletin.phrase.get(R.error)+" "+R.name)}}})}else{P.push(vBulletin.phrase.get("unknown_error"))}if(P.length>0){openAlertDialog({title:vBulletin.phrase.get("upload"),message:P.join("\n"),iconType:"warning"})}},fail:function(N,M){console.log("Fileupload: attachments fail");var L=vBulletin.phrase.get("error_uploading_image");var K="error";if(M&&M.files.length>0){switch(M.files[0].error){case"acceptFileTypes":L=vBulletin.phrase.get("invalid_image_allowed_filetypes_are");K="warning";break}}openAlertDialog({title:vBulletin.phrase.get("upload"),message:L,iconType:K})},always:function(L,K){}});var H=function(L){console.log("Fileupload: attachDone");var S=(this instanceof D)?this:D(this),O=S.find(".js-attach-list"),N=D(".js-uploadFrom").val();if(!L.name){openAlertDialog({title:vBulletin.phrase.get("upload"),message:vBulletin.phrase.get("unknown_error"),iconType:"warning"})}var M,K,P=false;if(L.name.match(/\.(gif|jpg|jpeg|jpe|png|bmp)$/i)){K=D("<img />").attr("src",pageData.baseurl+"/filedata/fetch?type=thumb&filedataid="+L.filedataid).addClass("b-attach-item__image");P=true}else{K=D("<span />").addClass("b-icon b-icon__doc--gray h-margin-bottom-m")}var Q=O.find(".js-attach-item-sample").first().clone(true);if(N=="signature"){O.find(".js-attach-item").not(".js-attach-item-sample").remove();P=false;D('[data-action="insert"]',Q).data("action","insert_sigpic").attr("data-action","insert_sigpic")}Q.removeClass("js-attach-item-sample");Q.find(".js-attach-item-image").append(K);Q.find(".js-attach-item-filename").text(L.name);Q.append(D("<input />").attr({type:"hidden",name:"filedataids[]"}).val(L.filedataid)).append(D("<input />").attr({type:"hidden",name:"filenames[]"}).val(L.name));var R="";if(M=L.name.match(/\.([a-z]+)$/i)){R=M[1]}Q.data("fileext",R);Q.data("filename",L.name);Q.data("filedataid",L.filedataid);Q.data("attachnodeid",0);if(!P){Q.find(".js-attach-ctrl").filter(function(){return D(this).data("action")=="insert_image"||D(this).data("action")=="insert_label"}).addClass("h-hide");Q.find(".js-attach-ctrl").filter(function(){return D(this).data("action")=="insert"}).html(vBulletin.phrase.get("insert"))}if(!vBulletin.ckeditor.checkEnvironment()){Q.find(".js-attach-ctrl").filter(function(){return D(this).data("action")=="insert"||D(this).data("action")=="insert_image"||D(this).data("action")=="insert_label"}).addClass("h-hide")}Q.appendTo(O).removeClass("h-hide");O.removeClass("h-hide")};D(".gallery-submit-form",J).submit(function(){D(".js-photo-display .photo-display input",D(this).closest(".gallery-editor")).appendTo(D(this));var K=D("input[type=hidden][name=ret]",this);if(D.trim(K.val())==""){K.val(location.href)}});D(document).off("click.photoadd",".action-buttons .js-photo-selector-continue").on("click.photoadd",".action-buttons .js-photo-selector-continue",function(){console.log("Fileupload: continue 2");var K=D(this).closest(".js-photo-selector-container"),L={};K.find(".photo-item-wrapper").each(function(){var M=D(this).find(".filedataid"),P=M.data("nodeid");if(M.is(":checked")){var N=M.val(),O=D.trim(D(this).find(".photo-title").text());L[P]={imgUrl:vBulletin.getAjaxBaseurl()+"/filedata/fetch?filedataid="+N+"&thumb=1",filedataid:N,title:O}}});if(!D.isEmptyObject(L)){D.ajax({url:vBulletin.getAjaxBaseurl()+"/ajax/render/photo_item",data:{items:L,wrapperClass:"tmp-photo"},type:"POST",dataType:"json",success:function(O){var Q=vBulletin.upload.getUploadedPhotosDlg(false),R=D(".photo-display",Q),N,M,P=1;R.append(O);N=D(".photo-item-wrapper:not(.h-hide)",R);D.each(N,function(S,T){M=D(T);if(P%C==0){M.addClass("last-in-row")}else{M.removeClass("last-in-row")}P++});if(P>0){vBulletin.upload.changeButtonText(D(".b-button--upload .js-upload-label",Q),vBulletin.phrase.get("upload_more"));D(".js-continue-button, .btnPhotoUploadSave",Q).show()}else{D(".js-continue-button, .btnPhotoUploadSave",Q).hide()}vBulletin.upload.adjustPhotoDialogForScrollbar(Q);Q.dialog("option","position","center");Q.dialog("open")}})}D(".photo-selector-galleries",K).tabs("destroy");K.dialog("close")});D(".action-buttons .js-photo-selector-cancel",J).off("click").on("click",function(){console.log("Fileupload: cancel 2");D(document).data("gallery-container",null);var K=D(this).closest(".js-photo-selector-container");D(".photo-selector-galleries",K).tabs("destroy");K.dialog("close")});console.log("Fileupload: vBulletin.upload.initializePhotoUpload finished")};vBulletin.upload.getUploadedPhotosDlg=function(H,J){var I;if(!J||J.length==0){J=D(document).data("gallery-container");if(!J){return D()}}if(J.hasClass("profile-media-photoupload-dialog")){I=J;if(H){I.dialog("open");vBulletin.upload.adjustPhotoDialogForScrollbar(I)}return I}I=J.find(".js-photo-display");if(I.length==0){D(".js-photo-display").each(function(){if(D(this).data("associated-editor")==J.get(0)){I=D(this);return false}})}else{I.dialog({modal:true,width:602,autoOpen:false,showCloseButton:false,closeOnEscape:false,resizable:false,showTitleBar:false,dialogClass:"dialog-container upload-photo-dialog-container dialog-box"});I.data("orig-width",I.dialog("option","width"));I.data("associated-editor",J.get(0));I.find(".b-form-input__file--hidden").prop("disabled",false)}if(H){I.dialog("open");vBulletin.upload.adjustPhotoDialogForScrollbar(I)}return I};vBulletin.upload.adjustPhotoDialogForScrollbar=function(K){var L=D(".photo-display",K);var J=K.parent();var I=D(".photo-item-wrapper:not(.h-hide)",L).length;if(!J.hasClass("has-scrollbar")&&I>=(C*E)){var H=window.vBulletin.getScrollbarWidth();J.addClass("has-scrollbar");K.dialog("option","width",K.dialog("option","width")+H+11)}};vBulletin.upload.relocateLastInRowClass=function(H){H.removeClass("last-in-row").filter(":not(.h-hide)").filter(function(I){return((I%C)==(C-1))}).addClass("last-in-row")};function F(){D(document).off("click.removephoto",".photo-display .photo-item .remove-icon").on("click.removephoto",".photo-display .photo-item .remove-icon",function(){var I=D(this).closest(".photo-item-wrapper"),M=D(".filedataid",I).data("nodeid"),L=I.parents(".js-photo-display").last();if(typeof M!=="undefined"){var K=D.inArray(M,G);if(K!=-1){G.splice(K,1)}}I.addClass("h-hide");var H=L.find(".photo-item-wrapper:not(.h-hide)"),J=H.length;if(J<=vBulletin.contentEntryBox.ATTACHLIMIT){D(".js-attach-limit-warning",L).hide()}vBulletin.upload.relocateLastInRowClass(H);if(H.length<=(C*E)){L.parent().removeClass("has-scrollbar");L.dialog("option","width",L.data("orig-width"));D(".action-buttons",L).css("margin-right","")}if(H.length>0){vBulletin.upload.changeButtonText(D(".b-button--upload .js-upload-label",L),vBulletin.phrase.get("upload_more"));D(".js-continue-button, .btnPhotoUploadSave",L).show()}else{vBulletin.upload.restoreButtonText(D(".b-button--upload .js-upload-label",L));D(".js-continue-button, .btnPhotoUploadSave",L).hide()}});D(document).off("blur.photocaption",".photo-display .photo-caption .caption-box").on("blur.photocaption",".photo-display .photo-caption .caption-box",function(){D(this).scrollTop(0)});vBulletin.conversation.bindEditFormEventHandlers("gallery")}function A(I){var H=D(".photo-item .photo-caption .caption-box",I);H.filter("[placeholder]").placeholder()}function B(H){D(this).replaceWith(H);A(H)}D(document).ready(function(){vBulletin.upload.initializePhotoUpload();F()})})(jQuery);function submitUrl(){var A="urlupload="+$("#urlupload").val()+"&urlretrieve="+$("#urlretrieve").val();data=$.ajax({type:"POST",url:uploadUrlTarget,data:A,success:function(C){fileInfo=jQuery.parseJSON(C);var B=document.createElement("input");B.setAttribute("type","hidden");B.setAttribute("value",fileInfo.filedataid);B.setAttribute("name","filedataids[]");B.setAttribute("id","filedataid_"+fileInfo.filedataid);var B=document.createElement("input");B.setAttribute("type","hidden");B.setAttribute("value",fileInfo.name);B.setAttribute("name","filename_"+fileInfo.filedataid);B.setAttribute("id","filename_"+fileInfo.filedataid);document.getElementById("newTextForm").appendChild(B);newItem='<div id ="uploadedUrl_'+fileInfo.filedataid+'"><img src="'+fileInfo.thumbUrl+'"></img><a href="#" onclick="javascript:deleteExistingLink('+fileInfo.filedataid+')"> Click Here to Remove</a>&nbsp;&nbsp;'+$("#urlupload").val()+"</div>";$("#urlupload").val("");$("#tab-3").append(newItem)}});return false}function deleteExistingLink(A){$("#uploadedUrl_"+A).remove();$("#filedataid_"+A).remove();return false}function removePhoto(A){$("#vb_photoEdit_"+A).remove()}function showCaptionEdit(A){$("#photoCtrl_"+A).addClass("h-hide");$("#photoCaption_"+A).removeClass("h-hide")}function closePhotoCaption(A){$("#photoCtrl_"+A).removeClass("h-hide");$("#photoCaption_"+A).addClass("h-hide");caption=$("#caption_edit_"+A).val();if(caption.length){$("#addcaptionText_"+A).html(caption.substr(0,10))}else{$("#addcaptionText_"+A).html(vBulletin.phrase.get("add_caption"))}}function cancelPhotoCaption(A){$("#photoCtrl_"+A).removeClass("h-hide");$("#photoCaption_"+A).addClass("h-hide");$("#addcaptionText_"+A).html(vBulletin.phrase.get("add_caption"));$("#caption_edit_"+A).val("")}var openGalleryEdit=false;function editGallery(B){redirect=true;if(B.data){B=B.data;redirect=false}if(openGalleryEdit!==false){if(!confirm(vBulletin.phrase.get("you_are_already_editing_continue"))){return }$("#gallery_edit_holder_"+openGalleryEdit).addClass("h-hide");$("#gallery_edit_holder_"+openGalleryEdit).html("")}var A=0;openGalleryEdit=B;$.ajax({type:"POST",url:vBulletin.getAjaxBaseurl()+"/uploader/get_photoedit",data:"nodeid="+B,success:function(E,D,C){$("#gallery_edit_holder_"+B).removeClass("h-hide");$("#gallery_edit_holder_"+B).html(C.responseText);$("#gallery_pageid_"+B).val(pageData.pageid);A=$("#photoCount"+B).val();$("#gallery_edit-"+B).fileupload({dropZone:null,add:function(G,F){A++;$("#photoCount"+B).val(A);$("#photo_uploader_status").css("display","block");F.submit()},done:function(H,G){if(G.result&&G.result.filedataid){var F=G.result.edit;if(typeof (G.result.galleryid)=="undefined"){galleryid=""}else{galleryid=G.result.galleryid}$("#photo_display"+galleryid).css("display","block");$("#photo_display_edit"+galleryid).append(F);$("#photo_uploader_status").css("display","none")}},fail:function(G,F){},always:function(G,F){$("#photo_display").css("display","block");$("#photo_uploader_status").css("display","none")}})},dataType:"html"})}function closeGalleryEdit(A){$("#gallery_edit_holder_"+A).addClass("h-hide");$("#gallery_edit_holder_"+A).html("");openGalleryEdit=false};