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

/**
 * vB_Api_ScreenLayout
 *
 * @package vBApi
 * @access public
 */
class vB_Api_ScreenLayout extends vB_Api
{
	/*
	 * Cache for screen layouts
	 */
	var $cache = null;

	protected function __construct()
	{
		parent::__construct();
	}

	/**
	 * Returns a list of all screen layouts.
	 *
	 * @param	bool	Force reload
	 * @return	array
	 */
	public function fetchScreenLayoutList($skipcache = false)
	{
		if (!is_array($this->cache) OR $skipcache)
		{
			$db = vB::getDbAssertor();
			$screenLayouts = $db->getRows('screenlayout', array(), array('displayorder', 'title'));

			if ($screenLayouts)
			{
				$this->cache = $screenLayouts;
			}
			else
			{
				$this->cache = array();
			}
		}

		return $this->cache;
	}

}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83435 $
|| #######################################################################
\*=========================================================================*/
