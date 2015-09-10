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

error_reporting(E_ALL & ~E_NOTICE);

require_once(dirname(__FILE__) . '/includes/class_bootstrap.php');

define('VB_AREA', 'Forum');
if (!defined('VB_ENTRY'))
{
	define('VB_ENTRY', 1);
}

global $bootstrap, $actiontemplates, $globaltemplates, $specialtemplates;
$bootstrap = new vB_Bootstrap_Forum();
$bootstrap->datastore_entries = $specialtemplates;
$bootstrap->cache_templates = vB_Bootstrap::fetch_required_template_list(
	empty($_REQUEST['do']) ? '' : $_REQUEST['do'],
	$actiontemplates, $globaltemplates
);

$bootstrap->bootstrap();

// Deprecated as of release 4.0.2, replaced by global_bootstrap_init_start
// Legacy Hook 'global_start' Removed //

$bootstrap->load_style();

// legacy code needs this
global $permissions;
$permissions = $vbulletin->userinfo['permissions'];

// Deprecated as of release 4.0.2, replaced by global_bootstrap_complete
// Legacy Hook 'global_setup_complete' Removed //

if (!empty($db->explain))
{
	$aftertime = microtime(true) - TIMESTART;
	echo "End call of global.php: $aftertime\n";
	echo "\n<hr />\n\n";
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83432 $
|| #######################################################################
\*=========================================================================*/
