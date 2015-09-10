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
 * vB_Api_Vb4_report
 *
 * @package vBApi
 * @access public
 */
class vB_Api_Vb4_report extends vB_Api
{
	public function sendemail($postid, $reason)
	{
		$cleaner = vB::getCleaner();
		$postid = $cleaner->clean($postid, vB_Cleaner::TYPE_UINT);
		$reason = $cleaner->clean($reason, vB_Cleaner::TYPE_STR);

		if (empty($postid))
		{
			return array('response' => array('errormessage' => array('invalidid')));
		}

		if (empty($reason))
		{
			return array('response' => array('errormessage' => array('invalidid')));
		}

		$userinfo = vB_Api::instance('user')->fetchUserinfo();

		$data = array(
			'reportnodeid' => $postid,
			'rawtext' => $reason,
			'created' => vB::getRequest()->getTimeNow(),
			'userid' => $userinfo['userid'],
			'authorname' => $userinfo['username'],
		);
		$result = vB_Api::instance('content_report')->add($data, array('wysiwyg' => false));

		if ($result === null || isset($result['errors']))
		{
			return vB_Library::instance('vb4_functions')->getErrorResponse($result);
		}

		return array('response' => array('errormessage' => array('redirect_reportthanks')));
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 84710 $
|| #######################################################################
\*=========================================================================*/
