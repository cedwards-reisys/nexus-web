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

class Api_Interface_Light extends Api_Interface_Collapsed
{
	/**
	 * This enables a light session. The main issue is that we skip testing control panel, last activity, and shutdown queries.
	 */
	public function init()
	{
		if ($this->initialized)
		{
			return true;
		}

		//initialize core
		$core_path = vB5_Config::instance()->core_path;
		require_once($core_path . '/vb/vb.php');
		vB::init();

		$request = new vB_Request_WebApi();
		vB::setRequest($request);
		$config = vB5_Config::instance();
		$cookiePrefix = $config->cookie_prefix;

		$checkTimeout = false;
		if (empty($_COOKIE[$cookiePrefix . 'sessionhash']))
		{
			$sessionhash = false;
			if (!empty($_REQUEST['s']))
			{
				$sessionhash = (string) $_REQUEST['s'];
				$checkTimeout = true;
			}
		}
		else
		{
			$sessionhash = $_COOKIE[$cookiePrefix . 'sessionhash'];
		}


		if (empty($_COOKIE[$cookiePrefix . 'cpsession']))
		{
			$cphash = false;
		}
		else
		{
			$cphash = $_COOKIE[$cookiePrefix . 'cpsession'];
		}

		if (empty($_COOKIE[$cookiePrefix . 'languageid']))
		{
			$languageid = 0;
		}
		else
		{
			$languageid = $_COOKIE[$cookiePrefix . 'languageid'];
		}

		vB_Api_Session::startSessionLight($sessionhash, $cphash, $languageid, $checkTimeout);
		$this->initialized = true;
	}

}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 84898 $
|| #######################################################################
\*=========================================================================*/
