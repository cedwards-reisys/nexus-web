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

/**
 *	@package vBInstall
 */


/*
 * Options for final upgrade step 15 where themes are imported
 *
 * Parameters:
 *	bool	'overwrite'		Default true. Set it to false to skip importing any existing themes.
 */
$upgrade_options['theme_import'] = array(
	'overwrite' => true,
);


/*
 * Options for final upgrade step 20 which attempts to three-way-merge the old default,
 * new default, & custom templates.
 *
 * Parameters:
 *	bool	'skip'			Default false.	Set to true to forcibly skip this step.
 *	bool	'skip_themes'	Default true.	Skip trying to merge theme templates with the default templates.
 *	int		'time_limit'	Default 4, min 1. Seconds allowed to elapse before breaking the merge
 *							process and moving onto the next iteration of this step. Note that
 *							if the very last merge takes a long time, the step might go past this limit.
 *							If that is causing the step to time out prematurely, try setting the
 *							'batch_size' below.
 *	int		'batch_size'	Default 99999, min 1. Number of templates we should attemp to merge per
 *							iteration. Try setting this to a small value if the 'time_limit' above does not help
 *							resolve timeout issues.
 */
$upgrade_options['template_merge'] = array(
	'skip' => false,
	'skip_themes' => true,
	'time_limit' => 4,
	'batch_size' => 99999,
);

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 84758 $
|| #######################################################################
\*=========================================================================*/
