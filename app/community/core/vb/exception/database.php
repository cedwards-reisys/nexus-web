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
 * Assertor Exception
 * Exception thrown by assertor classes.
 */
class vB_Exception_Database extends vB_Exception
{
	protected $data;

	/** Standard vB exception constructor for database exceptions.
	*
	*	@param	string	text message
	* 	@param	mixed	array of data- intended for debug mode
	* 	@code	mixed	normally an error flog.  If passed FALSE we won't send an email.
	*/
	public function __construct($message="", $data=array(), $code=0)
	{
		$this->sql = $message;
		$this->data = $data;
		$message = $this->createMessage();
		$config = vB::getConfig();
		parent::__construct($message, $code);

		if (!empty($config['Database']['technicalemail']) AND ($code !== FALSE))
		{
			// This text is purposely hard-coded since we don't have
			// access to the database to get a phrase
			vB_Mail::vbmail($config['Database']['technicalemail'], 'Database Error', $message, true, $config['Database']['technicalemail'], '', '', true);
		}
	}

	//get the original error message in cases where we want to do our
	//own data formatting (most non default handling of the exception).
	public function getSql()
	{
		return $this->sql;
	}

	public function getData()
	{
		return $this->data;
	}

	protected function createMessage()
	{
		if (empty($this->data))
		{
			// we have no info available
			return 'A database error occured, please check the database settings in the config file or enable debug mode for additional information.';
		}

		// This text is purposely hard-coded since we don't have
		// access to the database to get a phrase
		$message = "
			Database error in {$this->data['appname']} {$this->data['templateversion']}:

			{$this->sql}

			MySQL Error   : {$this->data['error']}
			Error Number  : {$this->data['errno']}
			Request Date  : {$this->data['requestdate']}
			Error Date    : {$this->data['date']}
			Script        : http://{$this->data['host']}{$this->data['scriptpath']}
			Referrer      : {$this->data['referer']}
			IP Address    : {$this->data['ipaddress']}
			Username      : {$this->data['username']}
			Classname     : {$this->data['classname']}
			MySQL Version : {$this->data['mysqlversion']}
		";

		if (!empty($this->data['trace']))
		{
			$message .= "\n\n";
			$message .= $this->getTraceString($this->data['trace']);
		}
		return $message;
	}

	protected function getTraceString($trace)
	{
		$trace_output = "Stack Trace:\n";
		foreach ($trace AS $index => $trace_item)
		{
			$param = (
				in_array($trace_item['function'], array('require', 'require_once', 'include', 'include_once')) ?
					$trace_item['args'][0] : ''
			);

			// ensure we don't access undefined indexes
			foreach (array('file', 'class', 'type', 'function', 'line') as $index)
			{
				if (!isset($trace_item[$index]))
				{
					$trace_item[$index] = '';
				}
			}

			// remove path
			$param = str_replace(DIR, '[path]', $param);
			$trace_item['file'] = str_replace(DIR, '[path]', $trace_item['file']);

			$trace_output .= "#$index $trace_item[class]$trace_item[type]$trace_item[function]($param) called in $trace_item[file] on line $trace_item[line]\n";
		}
		$trace_output .= "\n";
		return $trace_output;
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83435 $
|| #######################################################################
\*=========================================================================*/
