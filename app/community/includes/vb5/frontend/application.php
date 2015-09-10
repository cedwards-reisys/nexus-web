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

class vB5_Frontend_Application extends vB5_ApplicationAbstract
{
	public static function init($configFile)
	{
		parent::init($configFile);

		self::$instance = new vB5_Frontend_Application();
		self::$instance->router = new vB5_Frontend_Routing();
		self::$instance->router->setRoutes();
		$styleid = vB5_Template_Stylevar::instance()->getPreferredStyleId();

		if ($styleid)
		{
			vB::getCurrentSession()->set('styleid', $styleid);
		}

		self::ajaxCharsetConvert();
		self::setHeaders();

		return self::$instance;
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83435 $
|| #######################################################################
\*=========================================================================*/
