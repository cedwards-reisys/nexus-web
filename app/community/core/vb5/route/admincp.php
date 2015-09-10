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

class vB5_Route_Admincp extends vB5_Route
{
	public function getUrl()
	{
		//bburl isn't correct as it will create a link to core rather than admincp.  This happens to work
		//for admincp for the time being, but is the wrong url.
		//		$bburl = vB::getDatastore()->getOption('bburl');
		//		return $bburl . '/' . $this->prefix . '/' . $this->arguments['file'] . '.php';

		//user the header hack instead.
		$url = vB::getDatastore()->getOption('frontendurl') . '/' . $this->prefix . '/' . $this->arguments['file'] . '.php';
		if (strtolower(vB_String::getCharset()) != 'utf-8')
		{
			$url = vB_String::encodeUtf8Url($url);
		}
		return $url;
	}

	public static function resolvePath($path)
	{
		$currentDir = getcwd();
		chdir(DIR . '/admincp');
		$path = realpath($path);
		chdir($currentDir);
		return $path;
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83435 $
|| #######################################################################
\*=========================================================================*/
