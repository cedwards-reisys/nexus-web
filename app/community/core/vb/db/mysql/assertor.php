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
 * The vB core class.
 * Everything required at the core level should be accessible through this.
 *
 * The core class performs initialisation for error handling, exception handling,
 * application instatiation and optionally debug handling.
 *
 * @TODO: Much of what goes on in global.php and init.php will be handled, or at
 * least called here during the initialisation process.  This will be moved over as
 * global.php is refactored.
 *
 * @package vBulletin
 * @version $Revision: 83435 $
 * @since $Date: 2014-12-10 10:32:27 -0800 (Wed, 10 Dec 2014) $
 * @copyright vBulletin Solutions Inc.
 */
class vB_dB_MYSQL_Assertor extends vB_dB_Assertor
{
	/*Properties====================================================================*/

	protected static $db_type = 'MYSQL';

	protected function __construct(&$config)
	{
		parent::__construct($config);

		$this->load_database($config);

		self::$dbSlave = (!empty($config['SlaveServer']['servername'])) AND (!empty($config['SlaveServer']['port'])) AND
			(!empty($config['SlaveServer']['username']));

	}

	private function load_database(&$config)
	{
		// load database class
		switch (strtolower($config['Database']['dbtype']))
		{
			// load standard MySQL class
			case 'mysql':
			{
				$db = new vB_Database_MySQL();
				break;
			}

			// load MySQLi class
			case 'mysqli':
			{
				$db = new vB_Database_MySQLi();
				break;
			}

			// load extended, non MySQL class
			default:
			{
				// this is not implemented fully yet
				//	$db = 'vB_Database_' . $vbulletin->config['Database']['dbtype'];
				//	$db = new $db($vbulletin);
				die('Fatal error: Database class not found');
			}
		}


		// get core functions
		if (!empty($db->explain))
		{
			$db->timer_start('Including Functions.php');
			require_once(DIR . '/includes/functions.php');
			$db->timer_stop(false);
		}
		else
		{
			require_once(DIR . '/includes/functions.php');
		}

		// make the usepconnect config option optional (VBV-12036)
		if (empty($config['MasterServer']['usepconnect']))
		{
			$config['MasterServer']['usepconnect'] = 0;
		}
		if (empty($config['SlaveServer']['usepconnect']))
		{
			$config['SlaveServer']['usepconnect'] = 0;
		}

// make database connection
		$db->connect(
				$config['Database']['dbname'],
				$config['MasterServer']['servername'],
				$config['MasterServer']['port'],
				$config['MasterServer']['username'],
				$config['MasterServer']['password'],
				$config['MasterServer']['usepconnect'],
				$config['SlaveServer']['servername'],
				$config['SlaveServer']['port'],
				$config['SlaveServer']['username'],
				$config['SlaveServer']['password'],
				$config['SlaveServer']['usepconnect'],
				$config['Mysqli']['ini_file'],
				(isset($config['Mysqli']['charset']) ? $config['Mysqli']['charset'] : '')
		);
//if (!empty($vb5_config['Database']['force_sql_mode']))
//{
		$db->force_sql_mode('');
//}
//30443 Right now the product doesn't work in strict mode at all.  Its silly to make people have to edit their
//config to handle what appears to be a very common case (though the mysql docs say that no mode is the default)
//we no longer use the force_sql_mode parameter, though if the app is fixed to handle strict mode then we
//may wish to change the default again, in which case we should honor the force_sql_mode option.
//added the force parameter
//if (!empty($vbulletin->config['Database']['force_sql_mode']))
//if (empty($vbulletin->config['Database']['no_force_sql_mode']))
//{
//	$db->force_sql_mode('');
//}

		if (defined('DEMO_MODE') AND DEMO_MODE AND function_exists('vbulletin_demo_init_db'))
		{
			vbulletin_demo_init_db();
		}

		self::$db = $db;
		return $db;
	}

}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83435 $
|| #######################################################################
\*=========================================================================*/
