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

// ####################### SET PHP ENVIRONMENT ###########################
error_reporting(E_ALL);

// #################### DEFINE IMPORTANT CONSTANTS #######################
define('THIS_SCRIPT', 'login');
//define('CSRF_PROTECTION', true);
define('CSRF_SKIP_LIST', 'login');
define('CONTENT_PAGE', false);
if (!defined('VB_ENTRY'))
{
	define('VB_ENTRY', 1);
}
// ################### PRE-CACHE TEMPLATES AND DATA ######################
// get special phrase groups
global $phrasegroups, $specialtemplates, $globaltemplates, $actiontemplates, $show;
$phrasegroups = array();

// get special data templates from the datastore
$specialtemplates = array();

// pre-cache templates used by all actions
$globaltemplates = array();

// pre-cache templates used by specific actions
$actiontemplates = array(
	'lostpw' => array(
		'lostpw',
		'humanverify'
	)
);

// ######################### REQUIRE BACK-END ############################
require_once(dirname(__FILE__) . '/global.php');
require_once(DIR . '/includes/functions_login.php');

global $vbulletin, $vbphrase;
// #######################################################################
// ######################## START MAIN SCRIPT ############################
// #######################################################################

if (empty($_REQUEST['do']))
{
	exec_header_redirect(vB5_Route::buildUrl('home|fullurl'));
}

// ############################### start logout ###############################
if ($_REQUEST['do'] == 'logout')
{
	define('NOPMPOPUP', true);

	if (!VB_API)
	{
		$vbulletin->input->clean_gpc('r', 'logouthash', vB_Cleaner::TYPE_STR);

		if ($vbulletin->userinfo['userid'] != 0 AND !verify_security_token($vbulletin->GPC['logouthash'], $vbulletin->userinfo['securitytoken_raw']))
		{
			eval(standard_error(fetch_error('logout_error', vB::getCurrentSession()->get('sessionurl'), $vbulletin->userinfo['securitytoken'])));
		}
	}

	process_logout();

	$vbulletin->url = fetch_replaced_session_url($vbulletin->url);
	$forumHome = vB_Library::instance('content_channel')->getForumHomeChannel();
	if (strpos($vbulletin->url, 'do=logout') !== false)
	{
		$vbulletin->url = vB5_Route::buildUrl($forumHome['routeid'] . '|fullurl');
	}
	$show['member'] = false;

	eval(standard_error(fetch_error('cookieclear', create_full_url($vbulletin->url),  vB5_Route::buildUrl($forumHome['routeid'] . '|fullurl')), '', false));
}

// ############################### start do login ###############################
// this was a _REQUEST action but where do we all login via request?
if ($_POST['do'] == 'login')
{
	$vbulletin->input->clean_array_gpc('p', array(
		'vb_login_username'        => vB_Cleaner::TYPE_STR,
		'vb_login_password'        => vB_Cleaner::TYPE_STR,
		'vb_login_md5password'     => vB_Cleaner::TYPE_STR,
		'vb_login_md5password_utf' => vB_Cleaner::TYPE_STR,
		'postvars'                 => vB_Cleaner::TYPE_BINARY,
		'cookieuser'               => vB_Cleaner::TYPE_BOOL,
		'logintype'                => vB_Cleaner::TYPE_STR,
		'cssprefs'                 => vB_Cleaner::TYPE_STR,
		'inlineverify'             => vB_Cleaner::TYPE_BOOL,
	));

	// TODO: This is a temp fix for VBV-3475
	function admin_login_error($error, array $args = array())
	{
		global $vbulletin;
		if ($vbulletin->GPC['logintype'] === 'cplogin' OR $vbulletin->GPC['logintype'] === 'modcplogin')
		{
			require_once(DIR . '/includes/adminfunctions.php');

			$url = unhtmlspecialchars($vbulletin->url);

			$urlarr = vB_String::parseUrl($url);

			$urlquery = $urlarr['query'];

			$oldargs = array();
			if ($urlquery)
			{
				parse_str($urlquery, $oldargs);
			}

			$args = array_merge($oldargs, $args);

			unset($args['loginerror']);

			$argstr = http_build_query($args);

			$url = "/$urlarr[path]?loginerror=" . $error;

			if ($argstr) {
				$url .= '&' . $argstr;
			}

			print_cp_redirect($url);
		}
	}

	// can the user login?
	$strikes = vB_User::verifyStrikeStatus($vbulletin->GPC['vb_login_username']);
	if ($strikes === false)
	{
		admin_login_error('strikes');
		eval(standard_error(fetch_error('strikes', $vbulletin->options['bburl'], vB::getCurrentSession()->get('sessionurl'))));
	}

	if ($vbulletin->GPC['vb_login_username'] == '')
	{
		admin_login_error('badlogin', array('strikes' => $strikes));
		eval(standard_error(fetch_error('badlogin', $vbulletin->options['bburl'], vB::getCurrentSession()->get('sessionurl'), $strikes)));
	}

	// WE DON'T NEED THIS ANYMORE, AS verify_authentication WILL MODIFY vbulletin->userinfo ONLY IF IT PASSES THE CHECK
//	// make sure our user info stays as whoever we were (for example, we might be logged in via cookies already)
//	$original_userinfo = $vbulletin->userinfo;

	$auth = vB_User::verifyAuthentication($vbulletin->GPC['vb_login_username'], $vbulletin->GPC['vb_login_password'], $vbulletin->GPC['vb_login_md5password'], $vbulletin->GPC['vb_login_md5password_utf']);
	if (!$auth)
	{
		// Legacy Hook 'login_failure' Removed //

		// check password
		vB_User::execStrikeUser($vbulletin->userinfo['username']);

		if ($vbulletin->GPC['logintype'] === 'cplogin' OR $vbulletin->GPC['logintype'] === 'modcplogin')
		{
			// log this error if attempting to access the control panel
			require_once(DIR . '/includes/functions_log_error.php');
			log_vbulletin_error($vbulletin->GPC['vb_login_username'], 'security');
		}
//		$vbulletin->userinfo = $original_userinfo;

		// For vB_API we need to unlogin the users we logged in before
		if (defined('VB_API') AND VB_API === true)
		{
			$vbulletin->session->set('userid', 0);
			$vbulletin->session->set('loggedin', 0);
		}

		if ($vbulletin->GPC['inlineverify'] AND $vbulletin->userinfo)
		{
			require_once(DIR . '/includes/modfunctions.php');
			show_inline_mod_login(true);
		}
		else
		{
			define('VB_ERROR_PERMISSION', true);
			$show['useurl'] = true;
			$show['specificerror'] = true;
			$url = $vbulletin->url;
			if ($vbulletin->options['usestrikesystem'])
			{
				admin_login_error('badlogin_strikes_passthru', array('strikes' => $strikes + 1));
				eval(standard_error(fetch_error('badlogin_strikes_passthru', vB5_Route::buildUrl('lostpw|fullurl'), $strikes + 1)));
			}
			else
			{
				admin_login_error('badlogin_passthru', array('strikes' => $strikes + 1));
				eval(standard_error(fetch_error('badlogin_passthru', vB5_Route::buildUrl('lostpw|fullurl'), $strikes + 1)));
			}
		}
	}

	vB_User::execUnstrikeUser($vbulletin->GPC['vb_login_username']);

	// create new session
	$res = vB_User::processNewLogin($auth, $vbulletin->GPC['logintype'], $vbulletin->GPC['cssprefs']);

	// set cookies (temp hack for admincp)
	if (isset($res['cpsession']))
	{
		vbsetcookie('cpsession', $res['cpsession'], false, true, true);
	}
	vbsetcookie('userid', $res['userid'], false, true, true);
	vbsetcookie('password', $res['password'], false, true, true);
	vbsetcookie('sessionhash', $res['sessionhash'], false, false, true);

	// do redirect
	do_login_redirect();

}
else if ($_GET['do'] == 'login')
{
	// add consistency with previous behavior
	exec_header_redirect(vB5_Route::buildUrl('home|fullurl'));
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83432 $
|| #######################################################################
\*=========================================================================*/
