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

error_reporting(E_ALL & ~E_NOTICE);

// ###################### Start updatereputationids #######################
function build_reputationids()
{
	$assertor = vB::getDbAssertor();
	$count = 1;
	
	$reputations = $assertor->getRows('vBForum:reputationlevel', array(), array('minimumreputation'));
	
	$ourreputation = array();
	foreach ($reputations AS $reputation)
	{
		$ourreputation[$count]['value'] = $reputation['minimumreputation'];
		$ourreputation[$count]['index'] = $reputation['reputationlevelid'];
		$count++;
	}

	if ($count > 1)
	{
		$assertor->assertQuery('vBForum:buildReputationIds', array('ourreputation' => $ourreputation));
	}
	else
	{
		// it seems we have deleted all of our reputation levels??
		$assertor->assertQuery('user', array(
			vB_dB_Query::TYPE_KEY => vB_dB_Query::QUERY_UPDATE,
			'reputationlevelid' => 0,
			vB_dB_Query::CONDITIONS_KEY => array(
				array('field' => 'userid', 'value' => 0, 'operator' => vB_dB_Query::OPERATOR_GT)
			) 
		));
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83432 $
|| #######################################################################
\*=========================================================================*/
