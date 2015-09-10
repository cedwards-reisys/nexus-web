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

class vB5_Exception_Api extends vB5_Exception
{
	protected $template;
	protected $controller;
	protected $method;
	protected $arguments;
	protected $errors;
	
	function __construct($controller, $method, $arguments, $errors)
	{
		$this->template = '';
		$this->controller = $controller;
		$this->method = $method;
		$this->arguments = $arguments;
		$this->errors = $errors;

		$message = '<b>API Error</b><br><b>Controller:</b> ' . htmlspecialchars($controller) . '<br><b>Method:</b> ' . htmlspecialchars($method) . '<br><b>Error(s):</b> ';

		if (is_string($errors))
		{
			$message .= $errors;
		}
		else
		{
			$message .= '<br><pre style="font-family:Lucida Console,Monaco5,monospace;font-size:small;overflow:auto;border:1px solid #CCC;">';
			$message .= htmlspecialchars(var_export($errors, true));
			$message .= '</pre>';
		}

		$message .= '<br>';

		parent::__construct($message);
	}
	
	function prependTemplate($template)
	{
		if (empty($this->template))
		{
			$this->template = $template;
		}
		else
		{
			$this->template = "$template => {$this->template}";
		}
	}
	
	function getTemplate()
	{
		return $this->template;
	}
	
	function getController()
	{
		return $this->controller;
	}
	
	function getMethod()
	{
		return $this->method;
	}
	
	function getArguments()
	{
		return $this->arguments;
	}
	
	function getErrors()
	{
		return $this->errors;
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83435 $
|| #######################################################################
\*=========================================================================*/
