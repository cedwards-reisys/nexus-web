<?php if (!defined('VB_ENTRY')) die('Access denied.');
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

class vB_Request_Test extends vB_Request
{
	public function __construct($vars)
	{
		$serverVars = array('ipAddress','altIp','sessionHost','userAgent', 'referrer');
		foreach ($serverVars as $serverVar) {
			if (!empty($vars[$serverVar]))
			{
				$this->$serverVar = $vars[$serverVar];
				unset($vars[$serverVar]);
			}
		}

		parent::__construct();

		foreach ($vars as $var=>$value)
		{
			$this->$var = $value;
		}
	}

	public function createSession($userid = 1)
	{
		//$this->session = vB_Session_Web::getSession(1);
		$this->session = new vB_Session_Cli(
		 	vB::getDbAssertor(),
		 	vB::getDatastore(),
			vB::getConfig(),
			$userid
		);
		vB::setCurrentSession($this->session);
		$this->timeNow = time();
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83435 $
|| #######################################################################
\*=========================================================================*/
