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

// ######################## SET PHP ENVIRONMENT ###########################
error_reporting(E_ALL & ~E_NOTICE);

// ##################### DEFINE IMPORTANT CONSTANTS #######################
define('CVS_REVISION', '$RCSfile$ - $Revision: 84012 $');

// #################### PRE-CACHE TEMPLATES AND DATA ######################
$phrasegroups = array('forum');
$specialtemplates = array();

// ########################## REQUIRE BACK-END ############################
require_once('./global.php');

// ############################# LOG ACTION ###############################
$vbulletin->input->clean_array_gpc('r', array(
	'moderatorid' => vB_Cleaner::TYPE_INT,
	'forumid'     => vB_Cleaner::TYPE_INT
));
log_admin_action(iif($vbulletin->GPC['moderatorid'] != 0, " moderator id = " . $vbulletin->GPC['moderatorid'], iif($vbulletin->GPC['forumid'] != 0, "forum id = " . $vbulletin->GPC['forumid'])));

// ########################################################################
// ######################### START MAIN SCRIPT ############################
// ########################################################################

print_cp_header($vbphrase['forum_manager']);

if (empty($_REQUEST['do']))
{
	$_REQUEST['do'] = 'modify';
}

/**
 * VBV-13125 No longer applicable moderator permissions that should be removed Removed
 * Removed the logic that handles forum password change since it is not used in vB5
 */

// ################# Start modify ###################
if ($_REQUEST['do'] == 'modify')
{
	/******** Global Announcements ****/
	if ($permissions['adminpermissions'] & $vbulletin->bf_ugp_adminpermissions['ismoderator'])
	{
		$forumannouncements = $vbulletin->db->query_read("
			SELECT title, FROM_UNIXTIME(startdate) AS startdate, FROM_UNIXTIME(enddate) AS enddate, announcementid
			FROM " . TABLE_PREFIX . "announcement AS announcement
			WHERE announcement.forumid = -1
		");

		print_form_header('', '');
		print_table_header($vbphrase['global_announcements'], 4);
		print_cells_row(array($vbphrase['title'], $vbphrase['start_date'], $vbphrase['end_date'], $vbphrase['modify']), 1);

		if ($vbulletin->db->num_rows($forumannouncements))
		{
			while ($announcement = $vbulletin->db->fetch_array($forumannouncements))
			{
				$cell = array(htmlspecialchars_uni($announcement['title']), $announcement['startdate'], $announcement['enddate']);
				$cell[] = construct_link_code($vbphrase['edit'], 'announcement.php?' . vB::getCurrentSession()->get('sessionurl') . "do=edit&amp;a=$announcement[announcementid]") .
					construct_link_code($vbphrase['delete'],'announcement.php?' . vB::getCurrentSession()->get('sessionurl') . "do=remove&amp;a=$announcement[announcementid]");
				print_cells_row($cell);
			}
		}
		else
		{
			print_description_row($vbphrase['no_global_announcements_defined'], '', 4, '', 'center');
		}
		print_description_row(construct_link_code($vbphrase['add_announcement'], 'announcement.php?' . vB::getCurrentSession()->get('sessionurl') . "do=add"), '', 4, 'thead', vB_Template_Runtime::fetchStyleVar('right'));
		print_table_footer();
	}

	/******** Forums List ****/
	//require_once(DIR . '/includes/functions_databuild.php');
	//cache_forums();

	$forums = array();
	print_form_header('', '');
	print_table_header($vbphrase['forums'], 2);
	//There used to be  long datastore->forumscache check here but forumcache doesn't do anything in vB5 and isn't even created
	print_table_footer();
}

print_cp_footer();

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 84012 $
|| #######################################################################
\*=========================================================================*/
