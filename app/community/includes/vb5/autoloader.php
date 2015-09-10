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

abstract class vB5_Autoloader
{
	protected static $_paths = array();

	public static function register($path)
	{
		self::$_paths[] = (string) $path . '/includes/'; // includes

		spl_autoload_register(array(__CLASS__, '_autoload'));
	}

	/**
	 * Extremely primitive autoloader
	 */
	protected static function _autoload($class)
	{
		if (preg_match('/[^a-z0-9_]/i', $class))
		{
			return false;
		}

		$fname = str_replace('_', '/', strtolower($class)) . '.php';

		foreach (self::$_paths AS $path)
		{
			if (file_exists($path . $fname))
			{
				include($path . $fname);
				if (class_exists($class, false))
				{
					return true;
				}
			}
		}

		return class_exists($class, false);
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83435 $
|| #######################################################################
\*=========================================================================*/
