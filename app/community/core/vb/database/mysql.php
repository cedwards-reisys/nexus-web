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

// MySQL Database Class

/**
* Class to interface with a MySQL 4.1 database
*
* This class also handles data replication between a master and slave(s) servers
*
* @package	vBulletin
* @version	$Revision: 83435 $
* @date		$Date: 2014-12-10 10:32:27 -0800 (Wed, 10 Dec 2014) $
*/
class vB_Database_MySQL extends vB_Database
{
	function errno()
	{
		if ($this->connection_recent === null)
		{
			$this->errno = 0;
		}
		else
		{
			if (!($this->errno = @$this->functions['errno']($this->connection_recent)))
			{
				$this->errno = 0;
			};
		}

		/*	1046 = No database, 
			1146 = Table Missing.
			This is quite likely not a valid vB5 database */
		if ((!defined('VB_AREA') OR VB_AREA != 'Install')
			AND (
				strpos($this->sql, 'routenew') 
				OR strpos($this->sql, 'cache')
				OR strpos($this->sql, 'setting')
			)
			AND (in_array($this->errno, $this->getCriticalErrors()))
		)
		{
			$this->errno = -1;
		}

		return $this->errno;
	}

	/**
	* Function to return the codes of critical errors when testing if a database 
	* is a valid vB5 database - normally database not found and table not found errors.
	*
	* This should be set by the child class for each database type.
	*
	* @return	array	An array of error codes.
	*/
	function getCriticalErrors()
	{
	/*	1046 = No database, 
		1146 = Table Missing */
		return array(1046, 1146);
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83435 $
|| #######################################################################
\*=========================================================================*/
