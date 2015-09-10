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

// This class is used for install scripts
class vB_Session_Cli extends vB_Session
{
	public function __construct(&$dBAssertor, &$datastore, &$config, $userid)
	{
		$restoreSessionInfo = array('userid' => $userid);
		parent::__construct($dBAssertor, $datastore, $config, '', $restoreSessionInfo);
		$this->set('userid', $userid);

		//If we are in unit test we need to force a load of userinfo.
		if (defined('VB_AREA') AND (VB_AREA == 'Unit Test') AND ($userid > 0))
		{
			$useroptions = array();
			if (defined('IN_CONTROL_PANEL'))
			{
				$useroptions[] = vB_Api_User::USERINFO_ADMIN;
			}
			$this->userinfo = vB_User::fetchUserInfo($this->vars['userid'], $useroptions, $this->vars['languageid'], true);
		}
		//needed for error message handling.
		$_SERVER['HTTP_HOST'] = 'commandline';
	}

	protected function loadExistingSession($sessionhash, $restoreSessionInfo)
	{
		// CLI doesn't need to use stored sessions
		return false;
	}

	/**
	 * Sets the attribute sessionIdHash
	 */
	protected function createSessionIdHash()
	{
		// API session idhash won't have User Agent compiled.
		$this->sessionIdHash = md5('session_' . $this->userinfo['userid'] . time());
	}

	public function save()
	{
		// CLI doesn't need to use stored sessions
		return false;
	}

	public function saveForTesting()
	{
		parent::save();
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83435 $
|| #######################################################################
\*=========================================================================*/
