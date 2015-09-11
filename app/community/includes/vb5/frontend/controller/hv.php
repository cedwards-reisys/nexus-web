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

class vB5_Frontend_Controller_Hv extends vB5_Frontend_Controller
{
	public function actionImage()
	{
		$api = Api_InterfaceAbstract::instance();

		$image = $api->callApi('hv', 'fetchHvImage', array('hash' => $_REQUEST['hash']));

		switch ($image['type'])
		{
			case 'gif':
				header('Content-transfer-encoding: binary');
				header('Content-disposition: inline; filename=image.gif');
				header('Content-type: image/gif');
				break;

			case 'png':
				header('Content-transfer-encoding: binary');
				header('Content-disposition: inline; filename=image.png');
				header('Content-type: image/png');
				break;

			case 'jpg':
				header('Content-transfer-encoding: binary');
				header('Content-disposition: inline; filename=image.jpg');
				header('Content-type: image/jpeg');
				break;
		}

		echo $image['data'];
	}

}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83435 $
|| #######################################################################
\*=========================================================================*/
