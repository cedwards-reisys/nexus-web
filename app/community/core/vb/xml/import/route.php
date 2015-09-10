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

class vB_Xml_Import_Route extends vB_Xml_Import
{
	protected function import($onlyGuid = false)
	{
		// get all columns but the key
		$routeTable = $this->db->fetchTableStructure('routenew');
		$routeTableColumns = array_diff($routeTable['structure'], array('arguments', 'contentid', $routeTable['key']));

		if (empty($this->parsedXML['route']))
		{
			$this->parsedXML['route'] = array();
		}

		$routes = is_array($this->parsedXML['route'][0]) ? $this->parsedXML['route'] : array($this->parsedXML['route']);

		foreach ($routes AS $route)
		{
			if ($onlyGuid AND $onlyGuid != $route['guid'])
			{
				continue;
			}

			$values = array();
			foreach($routeTableColumns AS $col)
			{
				if (isset($route[$col]))
				{
					$values[$col] = $route[$col];
				}
			}

			if (!isset($route['class']))
			{
				$values['class'] = '';
			}
			$condition = array('guid' => $route['guid']);
			$existing = $this->db->getRow('routenew', $condition);

			if ($existing AND !empty($existing['routeid']))
			{
				//If we have a route with this guid we leave it alone. The customer may have intentionally changed it
				//see VBV-13586.
				$routeid = $existing['routeid'];
			}
			else
			{
				$class = (isset($route['class']) AND !empty($route['class']) AND class_exists($route['class'])) ? $route['class'] : vB5_Route::DEFAULT_CLASS;
				$values['arguments'] = call_user_func_array(array($class, 'importArguments'), array($route['arguments']));
				$values['contentid'] = call_user_func_array(array($class, 'importContentId'), array(unserialize($values['arguments'])));

				$routeid = $this->db->insertIgnore('routenew', $values);
				//We need to make sure the name is unique. Collisions should be very rare but not impossible.

				if (is_array($routeid))
				{
					$routeid = array_pop($routeid);
				}
			}

			vB_Xml_Import::setImportedId(vB_Xml_Import::TYPE_ROUTE, $route['guid'], $routeid);
		}
	}

}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 84968 $
|| #######################################################################
\*=========================================================================*/
