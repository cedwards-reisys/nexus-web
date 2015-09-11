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

class vB5_Route_PrivateMessage_New
{
	protected $subtemplate = 'privatemessage_newpm';

	protected $userid = 0;

	public function __construct(&$routeInfo, &$matches, &$queryString = '')
	{
		$cleaner = vB::getCleaner();
		if (isset($matches['params']) AND !empty($matches['params']))
		{
			$paramString = (strpos($matches['params'], '/') === 0) ? substr($matches['params'], 1) : $matches['params'];
			list($this->userid) = explode('/', $paramString);
		}
		else if (isset($matches['userid']))
		{
			$this->userid = $matches['userid'];
		}
		$this->userid = $cleaner->clean($this->userid, vB_Cleaner::TYPE_INT);

		$routeInfo['arguments']['subtemplate'] = $this->subtemplate;

		$userid = vB::getCurrentSession()->get('userid');
		$pmquota = vB::getUserContext($userid)->getLimit('pmquota');
		$vboptions = vB::getDatastore($userid)->getValue('options');
		$canUsePmSystem = ($vboptions['enablepms'] AND $pmquota);
		if (!$canUsePmSystem)
		{
			throw new vB_Exception_NodePermission('privatemessage');
		}
	}

	public function validInput(&$data)
	{
		//We don't actually need anything. userid is optional
		if ($this->userid)
		{
			$data['arguments'] = serialize(array(
				'userid' => $this->userid
			));

			return true;
		}
		else
		{
			return true;
		}
	}

	public function getUrlParameters()
	{
		return "/{$this->userid}";
	}

	public function getParameters()
	{
		return array('userid' => $this->userid);
	}

	public function getBreadcrumbs()
	{
		$breadcrumbs = array(
			array(
				'phrase' => 'inbox',
				'url'	=> vB5_Route::buildUrl('privatemessage')
			),
			array(
				'phrase' => 'messages',
				'url' => ''
			),
			array(
				'phrase' => 'new_message',
				'url' => ''
			)
		);

		return $breadcrumbs;
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 84761 $
|| #######################################################################
\*=========================================================================*/
