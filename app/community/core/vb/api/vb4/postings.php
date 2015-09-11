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
 * vB_Api_Vb4_postings
 *
 * @package vBApi
 * @access public
 */
class vB_Api_Vb4_postings extends vB_Api
{
	public function docopythread($threadid, $destforumid)
	{
		$cleaner = vB::getCleaner();
		$threadid = $cleaner->clean($threadid, vB_Cleaner::TYPE_UINT);
		$destforumid = $cleaner->clean($destforumid, vB_Cleaner::TYPE_UINT);

		if (empty($threadid) || empty($destforumid))
		{
			return array('response' => array('errormessage' => 'invalidid'));
		}

		$result = vB_Api::instance('node')->cloneNodes(array($threadid), $destforumid);
		if($result === null || isset($result['errors']))
		{
			return vB_Library::instance('vb4_functions')->getErrorResponse($result);
		}
		else
		{
			return array('response' => array('errormessage' => array('redirect_movethread')));
		}
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83435 $
|| #######################################################################
\*=========================================================================*/
