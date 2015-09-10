<?php
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

class vB5_Frontend_Controller_Admin extends vB5_Frontend_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * This method was previously used by pagetemplate_edit
	 * @deprecated
	 */
	public function pagetemplateSave()
	{
		$api = $this->getApi();

		// we need an input cleaner
		$input = array(
			'templatetitle' => trim(strval($_POST['templatetitle'])),
			'screenlayoutid' => intval($_POST['screenlayoutid']),
			'pagetemplateid' => intval($_POST['pagetemplateid']),
		);

		if (empty($input['templatetitle']))
		{
			echo 'The title cannot be empty. Please go back and correct this problem.';
			exit;
		}
		if ($input['screenlayoutid'] < 1)
		{
			echo 'You must specify a screen layout. Please go back and correct this problem.';
			exit;
		}


		// page template
		$valuePairs = array(
			'title' => $input['templatetitle'],
			'screenlayoutid' => $input['screenlayoutid'],
		);

		$pagetemplateid = $input['pagetemplateid'];
		if ($pagetemplateid < 1)
		{
			// If no widgets were configured on the page template, we won't have a page template ID.
			$pagetemplateid = $api->callApi('database', 'insert', array('pagetemplate', $valuePairs));
		}
		else
		{
			$api->callApi('database', 'update', array('pagetemplate', $valuePairs, "pagetemplateid = $pagetemplateid"));
		}

		// widgets

		// we need a dedicated input cleaner
		$columns = array();
		$input['displaysections'] = (array) $_POST['displaysections'];
		foreach ($input['displaysections'] AS $sectionNumber => $widgetInfo)
		{
			$columns[intval($sectionNumber)] = explode(',', trim(strval($widgetInfo)));
		}

		$widgets = array();

		foreach ($columns as $displaycolumn => $columnwidgets)
		{
			$displayorder = 0;
			foreach ($columnwidgets as $columnwidget)
			{
				if (strpos($columnwidget, '=') !== false)
				{
					list($columnwidgetid, $columnwidgetinstanceid) = explode('=', $columnwidget, 2);
					$columnwidgetid = (int) $columnwidgetid;
					$columnwidgetinstanceid = (int) $columnwidgetinstanceid;
				}
				else
				{
					$columnwidgetid = (int) $columnwidget;
					$columnwidgetinstanceid = 0;
				}

				if (!$columnwidgetid)
				{
					continue;
				}

				$widgets[] = array(
					'widgetinstanceid' => $columnwidgetinstanceid,
					'pagetemplateid'   => $pagetemplateid,
					'widgetid'         => $columnwidgetid,
					'displaysection'   => $displaycolumn,
					'displayorder'     => $displayorder,
				);

				++$displayorder;
			}
		}

		foreach ($widgets as $widget)
		{
			$widgetinstanceid = $widget['widgetinstanceid'];
			unset($widget['widgetinstanceid']);

			if ($widgetinstanceid > 0)
			{
				$api->callApi('database', 'update', array('widgetinstance', $widget, "widgetinstanceid = $widgetinstanceid"));
			}
			else
			{
				$api->callApi('database', 'insert', array('widgetinstance', $widget));
			}
		}



		// return to the page they were on (if applicable)
		$returnUrl = vB5_Template_Options::instance()->get('options.frontendurl');
		if (isset($_REQUEST['return']) AND $_REQUEST['return'] == 'page')
		{
			$returnPageId = (int) $_REQUEST['pageid'];
			$page = $api->callApi('page', 'fetchPageById', array($returnPageId));
			if ($page)
			{
				$returnUrl = $page['url'];
			}
		}

		header('Location: ' . $returnUrl);
		exit;
	}

	public function actionSavepage()
	{
		$input = $_POST['input'];
		$url = $_POST['url'];

		//parse_url doesn't work on relative urls and I don't want to assume that
		//we have an absolute url.  We probably don't have a query string, but bad assumptions
		//about the url are what got us into this problem to begin with.
		$parts = explode('?', $url, 2);
		$url = $parts[0];

		$query = '';
		if (sizeof($parts) == 2)
		{
			$query = $parts[1];
		}

		if (preg_match('#^http#', $url))
		{
			$base = vB5_Template_Options::instance()->get('options.frontendurl');
			if (preg_match('#^' . preg_quote($base, '#') . '#', $url))
			{
				$url = substr($url, strlen($base)+1);
			}
		}

		$api = Api_InterfaceAbstract::instance();
		$route = $api->callApi('route', 'getRoute', array('pathInfo' => $url, 'queryString' => $query));

		//if we have a redirect try to find the real route -- this should only need to handle one layer
		//and if that also gets a redirect things are broken somehow.
		if (!empty($route['redirect']))
		{
			$route = $api->callApi('route', 'getRoute', array('pathInfo' => ltrim($route['redirect'], '/'), 'queryString' => $query));
		}

		$result = $api->callApi('page', 'pageSave', array($input));
		if (empty($result['errors']))
		{
			//$url = $api->callApi('route', 'getUrl', array('route' => 'profile', 'data' => array('userid' => $loginInfo['userid']), array()));
			$page = $api->callApi('page', 'fetchPageById', array('pageid' => $result['pageid'], 'routeData' => $route['arguments']));
			$result['url'] = $page['url'];
		}
		$this->sendAsJson($result);
	}

	protected function getApi()
	{
		return Api_InterfaceAbstract::instance();
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 85170 $
|| #######################################################################
\*=========================================================================*/
