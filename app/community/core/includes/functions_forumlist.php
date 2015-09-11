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

// ###################### Start getimodcache #######################
function cache_moderators($userid = false)
{
	global $imodcache, $mod;

	$imodcache = array();
	$mod = array();
	try
	{
		$forummoderators = vB::getDbAssertor()->assertQuery('vBForum:getCacheModerators', array('userid' => $userid));
	}
	// @TODO improve this exception handling from the assertor
	catch (Exception $ex)
	{
		$forummoderators = false;
	}

	while($forummoderators AND $forummoderators->valid())
	{
		$moderator = $forummoderators->current();
		try
		{
			$moderator['musername'] = vB_Api::instanceInternal('user')->fetchMusername($moderator);
			$imodcache["$moderator[nodeid]"]["$moderator[userid]"] = $moderator;
			$mod["$moderator[userid]"] = 1;
		}
		catch (vB_Exception_Api $ex)
		{
			// do nothing...
		}
		$forummoderators->next();
	}
}

/**
* A version of cache_moderators that can be safely called multiple times
* without doing extra work.
*/
function cache_moderators_once($userid = null)
{
	global $imodcache;
	if (!isset($imodcache))
	{
		cache_moderators($userid);
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83432 $
|| #######################################################################
\*=========================================================================*/
