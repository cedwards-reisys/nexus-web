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

class vB5_Route_Legacy_Tag extends vB5_Route_Legacy
{
	protected $prefix = 'tags.php';
	
	// translate param, if fail just go to search page
	protected function getNewRouteInfo()
	{
		$param = & $this->queryParameters;
		$tag = array();
		if (!empty($param['tag']))
		{
			$tag['searchJSON'] = "{\"tag\":[\"$param[tag]\"]}";
		}
		$param = $tag;
		return 'search';
	}

	public function getRedirect301()
	{
		$data = $this->getNewRouteInfo();
		return $data;
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83435 $
|| #######################################################################
\*=========================================================================*/
