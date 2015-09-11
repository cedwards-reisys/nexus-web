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
 * vB_Api_Vb4_visitormessage
 *
 * @package vBApi
 * @access public
 */
class vB_Api_Vb4_visitormessage extends vB_Api
{
	public function message($message, $userid)
	{
		$cleaner = vB::getCleaner();
		$message = $cleaner->clean($message, vB_Cleaner::TYPE_STR);
		$userid = $cleaner->clean($userid, vB_Cleaner::TYPE_STR);

		$parentid = vB_Api::instanceInternal('node')->fetchVMChannel();
		$data = array(
			'title' => '(Untitled)',
			'parentid' => $parentid,
			'channelid' => '',
			'nodeid' => '',
			'setfor' => $userid,
			'rawtext' => $message,
		);
		$result = vB_Api::instanceInternal('content_text')->add($data, array('wysiwyg' => false));
		if (!empty($result['errors'])) {
			return array('response' => array('postpreview' => array('invalidid')));
		}
		return array('response' => array('errormessage' => array('visitormessagethanks')));
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 84709 $
|| #######################################################################
\*=========================================================================*/
