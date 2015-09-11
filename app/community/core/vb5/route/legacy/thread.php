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

class vB5_Route_Legacy_Thread extends vB5_Route_Legacy_Page
{
	protected $idkey = array('t', 'threadid');

	protected $prefix = 'showthread.php';

	// use postid if available
	protected function getNewRouteInfo()
	{
		$param = & $this->queryParameters;
		if(isset($param['p']) AND $oldid=intval($param['p']) OR isset($param['postid']) AND $oldid=intval($param['postid']))
		{
			$node = vB::getDbAssertor()->getRow('vBForum:fetchLegacyPostIds', array(
				'oldids' => $oldid,
				'postContentTypeId' => vB_Types::instance()->getContentTypeID('vBForum_Post'),
			));

			if (empty($node))
			{
				throw new vB_Exception_404('invalid_page');
			}

			$this->arguments['nodeid'] = $node['starter'];
			$this->arguments['innerPost'] = $node['nodeid'];
			return $node['routeid'];
		}
		$this->oldcontenttypeid = vB_Types::instance()->getContentTypeID(array('package' => 'vBForum', 'class' =>'Thread'));
		return parent::getNewRouteInfo();
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83435 $
|| #######################################################################
\*=========================================================================*/
