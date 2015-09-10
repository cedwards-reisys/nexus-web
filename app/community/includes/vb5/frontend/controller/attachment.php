<?php
/*========================================================================*\
|| ###################################################################### ||
|| # vBulletin 5.1.9 - Licence Number LD18132D6F
|| # ------------------------------------------------------------------ # ||
|| # Copyright 2000-2015 vBulletin Solutions Inc. All Rights Reserved.  # ||
|| # This file may not be redistributed in whole or significant part.   # ||
|| # ----------------- VBULLETIN IS NOT FREE SOFTWARE ----------------- # ||
|| # http://www.vbulletin.com | http://www.vbulletin.com/license.html   # ||
|| ###################################################################### ||
\*========================================================================*/

class vB5_Frontend_Controller_Attachment extends vB5_Frontend_Controller {
	public function actionFetch() {
		if (!empty($_REQUEST['id']) AND intval($_REQUEST['id'])) {
			$request = array('id' => $_REQUEST['id']);

			if (!empty($_REQUEST['thumb']) AND intval($_REQUEST['thumb'])) {
				$request['thumb'] = $_REQUEST['thumb'];
			}
			$api = Api_InterfaceAbstract::instance();
			$fileInfo = $api->callApi('attach', 'fetchImage', $request);
			if (!empty($fileInfo)) {
				header('Cache-control: max-age=31536000, private');
				header('Expires: ' . gmdate("D, d M Y H:i:s", TIMENOW + 31536000) . ' GMT');
				header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $fileInfo['dateline']) . ' GMT');
				header('ETag: "' . $fileInfo['filedataid'] . '"');
				header('Accept-Ranges: bytes');
				header('Content-transfer-encoding: binary');
				header("Content-Length:\"" . $fileInfo['filesize'] );
				header('Content-Type: ' . $fileInfo['htmlType'] );
				header("Content-Disposition: inline filename*=" . $fileInfo['filename']);
				echo $fileInfo['filedata'];
			}
		}
	}

	public function actionRemove() {
		//Note that we shouldn't actually do anything here. If the filedata record isn't
		//used it will soon be deleted.
		if (!empty($_REQUEST['id']) && intval($_REQUEST['id'])) {
			$request = array('id' => $_REQUEST['id']);

			$api = Api_InterfaceAbstract::instance();
			// AFAIK, there is no "attach" api, and vb_api_content_attach doesn't have a removeAttachment().
			// TODO: Figure out where this going/supposed to be going.
			$api->callApi('attach', 'removeAttachment', $request);
		}
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83435 $
|| #######################################################################
\*=========================================================================*/
