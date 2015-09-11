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
 * Default Content View
 * Provides default functionality for common content fields.
 *
 * @package vBulletin
 * @author vBulletin Development Team
 * @version $Revision: 83435 $
 * @since $Date: 2014-12-10 10:32:27 -0800 (Wed, 10 Dec 2014) $
 * @copyright vBulletin Solutions Inc.
 */
class vB_View_Content extends vB_View
{
	/*Render========================================================================*/

	/**
	 * Prepares properties for rendering.
	 */
	protected function prepareProperties()
	{
		$this->description = htmlspecialchars_uni($this->description);
		$this->contenttype = vB_Types::instance()->getContentTypeTitle(array('package' => $this->package, 'class' => $this->class));
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83435 $
|| #######################################################################
\*=========================================================================*/
