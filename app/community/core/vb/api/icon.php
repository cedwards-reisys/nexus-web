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
 * vB_Api_Icon
 *
 * @package vBApi
 * @access public
 */
class vB_Api_Icon extends vB_Api
{

	protected function __construct()
	{
		parent::__construct();
	}

	/**
	 * Returns the array of post icons
	 *
	 * @param array $orderby Sort orders
	 * @return    array    The icons
	 */
	public function fetchAll($orderby = array())
	{
		$result = vB::getDbAssertor()->assertQuery(
			'icon',
			array(
				vB_dB_Query::TYPE_KEY => vB_dB_Query::QUERY_SELECT,
			),
			$orderby
		);

		$icons = array();
		if ($result->valid())
		{
			foreach ($result AS $icon)
			{
				$icons[$icon['iconid']] = $icon;
			}
		}

		return $icons;
	}

	/**
	 * Writes debugging output to the filesystem for AJAX calls
	 *
	 * @param	mixed	Output to write
	 */
	protected function _writeDebugOutput($output)
	{
		$fname = dirname(__FILE__) . '/_debug_output.txt';
		file_put_contents($fname, $output);
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83435 $
|| #######################################################################
\*=========================================================================*/
