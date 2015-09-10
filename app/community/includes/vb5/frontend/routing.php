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

class vB5_Frontend_Routing
{
	protected $routeId;
	protected $routeGuid;
	protected $controller;
	protected $action;
	protected $template;
	protected $arguments;
	protected $queryParameters;
	protected $pageKey;
	protected $breadcrumbs;
	protected $headlinks;

	protected $whitelist = array('actionLoginForm', 'actionLogin');

	protected function processQueryString()
	{
		if (!isset($_SERVER['QUERY_STRING']))
		{
			$_SERVER['QUERY_STRING'] = '';
		}

		parse_str($_SERVER['QUERY_STRING'], $params);

		if (isset($params['styleid']))
		{
			$styleid = intval($params['styleid']);
			$styleid = $styleid > 0 ? $styleid : 1;
			vB5_Cookie::set('userstyleid', $styleid, 0, false);
			$prefix = vB5_Config::instance()->cookie_prefix;
			$_COOKIE[$prefix . 'userstyleid'] = $styleid; // set it for the rest of this request as well
		}
	}

	public function setRoutes()
	{
		$this->processQueryString();

		//TODO: this is a very basic and straight forward way of parsing the URI, we need to improve it
		//$path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';

		if (isset($_GET['routestring']))
		{
			$path = $_GET['routestring'];

			// remove it from $_GET
			unset($_GET['routestring']);

			// remove it from $_SERVER
			parse_str($_SERVER['QUERY_STRING'], $queryStringParameters);
			unset($queryStringParameters['routestring']);
			$_SERVER['QUERY_STRING'] = http_build_query($queryStringParameters, '', '&'); // Additional parameters of http_build_query() is required. See VBV-6272.
		}
		else if (isset($_SERVER['PATH_INFO']))
		{
			$path = $_SERVER['PATH_INFO'];
		}
		else
		{
			$path = '';
		}

		if (strlen($path) AND $path{0} == '/')
		{
			$path = substr($path, 1);
		}

		//If there is an invalid image, js, or css request we wind up here. We can't process any of them
		if (strlen($path) > 2 )
		{
			$ext = strtolower(substr($path, -4)) ;
			if (($ext == '.gif') OR ($ext == '.png') OR ($ext == '.jpg') OR ($ext == '.css')
				OR (strtolower(substr($path, -3)) == '.js') )
			{
				header("HTTP/1.0 404 Not Found");
				die('');
			}
		}

		try
		{
			$message = ''; // Start with no error.
			$route = Api_InterfaceAbstract::instance()->callApi('route', 'getRoute', array('pathInfo' => $path, 'queryString' => $_SERVER['QUERY_STRING']));
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();

			if ($message != 'no_vb5_database')
			{
				/* Some other exception happened */
				vB5_ApplicationAbstract::handleException($e, true);
			}
		}

		if (isset($route['errors']))
		{
			$message = $route['errors'][0][1];

			if ($message != 'no_vb5_database')
			{
				/* Some other exception happened */
				throw new vB5_Exception($message);
			}
		}

		if ($message == 'no_vb5_database')
		{
			/* Seem we dont have a valid vB5 database */
			// TODO: as we removed baseurl from config.php, we need to find a way redirecting user to installer correctly.
			header('Location: core/install/index.php');
			exit;
		}

		if (!empty($route))
		{
			if (isset($route['redirect']))
			{
				header('Location: ' . vB5_Template_Options::instance()->get('options.frontendurl') . $route['redirect'], true, 301);
				exit;
			}
			else if (isset($route['internal_error']))
			{
				vB5_ApplicationAbstract::handleException($route['internal_error']);
			}
			else if (isset($route['banned_info']))
			{
				vB5_ApplicationAbstract::handleBannedUsers($route['banned_info']);
			}
			else if (isset($route['no_permission']))
			{
				vB5_ApplicationAbstract::handleNoPermission();
			}
			else if (isset($route['forum_closed']))
			{
				vB5_ApplicationAbstract::showMsgPage('', $route['forum_closed'], 'bbclosedreason'); // Use 'bbclosedreason' as state param here to match the one specified in vB_Api_State::checkBeforeView()
				die();
			}
			else
			{
				$this->routeId         = $route['routeid'];
				$this->routeGuid       = $route['routeguid'];
				$this->controller      = $route['controller'];
				$this->action          = $route['action'];
				$this->template        = $route['template'];
				$this->arguments       = $route['arguments'];
				$this->queryParameters = $route['queryParameters'];
				$this->pageKey         = $route['pageKey'];

				if (!empty($route['userAction']) AND is_array($route['userAction']))
				{
					$this->userAction['action'] = array_shift($route['userAction']);
					$this->userAction['params'] = $route['userAction'];
				}
				else
				{
					$this->userAction = false;
				}

				$this->breadcrumbs = $route['breadcrumbs'];
				$this->headlinks = $route['headlinks'];

				if (!in_array($this->action, $this->whitelist))
				{
					vB5_ApplicationAbstract::checkState($route);
				}

				return;
			}
		}
		else
		{
			// if no route was matched, try to parse route as /controller/method
			$stripped_path = preg_replace('/[^a-z0-9\/-]+/i', '', trim(strval($path), '/'));
			if (strpos($stripped_path, '/'))
			{
				list($controller, $method) = explode('/', strtolower($stripped_path), 2);
			}
			else
			{
				$controller = $stripped_path;
				$method = 'index';
			}

			$controller = preg_replace_callback('#(?:^|-)(.)#', function($matches)
			{
				return strtoupper($matches[1]);
			}, strtolower($controller));
			$method = preg_replace_callback('#(?:^|-)(.)#', function($matches)
			{
				return strtoupper($matches[1]);
			}, strtolower($method));

			$controllerClass = 'vB5_Frontend_Controller_' . $controller;
			$controllerMethod = 'action' . $method;

			if (class_exists($controllerClass) AND method_exists($controllerClass, $controllerMethod))
			{
				$this->controller = strtolower($controller);
				$this->action = $controllerMethod;
				$this->template = '';
				$this->arguments = array();
				$this->queryParameters = array();
				if (!in_array($this->action, $this->whitelist))
				{
					vB5_ApplicationAbstract::checkState(array('controller' => $this->controller, 'action' => $this->action));
				}
				return;
			}
		}

		//this could be a legacy file that we need to proxy.  The relay controller will handle
		//cases where this is not a valid file.  Only handle files in the "root directory".  We'll
		//handle deeper paths via more standard routes.
		if (strpos($path, '/') === false)
		{
			$this->controller = 'relay';
			$this->action = 'legacy';
			$this->template = '';
			$this->arguments = array($path);
			$this->queryParameters = array();
			return;
		}

		vB5_ApplicationAbstract::checkState();

		throw new vB5_Exception_404("invalid_page_url");
	}


	/** Sets route information. Used by applicationLight to skip calling the full router. Mainly for template rendering
	 * @param mixed		array can include routeid, routeGuid, action, arguments. template, queryParameters, or breadcrumbs.
	 *
	 */
	public function setRouteInfo($routeInfo)
	{
		if (is_array($routeInfo))
		{
			foreach (array('routeid', 'routeGuid', 'action', 'arguments', 'template', 'queryParameters', 'breadcrumbs', 'headlinks')
				AS $key => $value)
			{
				if (!empty($routeInfo[$key]))
				{
					$this->$key = $value;
				}
			}
		}
	}

	function getRouteId()
	{
		return $this->routeId;
	}

	function getRouteGuid()
	{
		return $this->routeGuid;
	}

	function getController()
	{
		return $this->controller;
	}

	function getAction()
	{
		return $this->action;
	}

	function getTemplate()
	{
		return $this->template;
	}

	function getArguments()
	{
		return $this->arguments;
	}

	function getQueryParameters()
	{
		return $this->queryParameters;
	}

	function getPageKey()
	{
		return $this->pageKey;
	}

	function getUserAction()
	{
		return $this->userAction;
	}

	function getBreadcrumbs()
	{
		return $this->breadcrumbs;
	}

	function getHeadLinks()
	{
		return $this->headlinks;
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83435 $
|| #######################################################################
\*=========================================================================*/
