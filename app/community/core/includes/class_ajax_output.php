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
* Class to simplify AJAX output operations
*
* @package	vBulletin
* @version	$Revision: 83432 $
* @date		$Date: 2014-12-10 08:53:03 -0800 (Wed, 10 Dec 2014) $
*/
class vB_AJAX_Output
{
	/**
	* The vBulletin registry object
	*
	* @var	vB_Registry
	*/
	var $registry = null;

	/**
	* Character set to use
	*
	* @var	string
	*/
	var $charset = 'windows-1252';
	
	/**
	* Content type
	*
	* @var	string
	*/
	var $content_type = 'text/xml';

	/**
	* Sets the correct character set for AJAX pages.
	* Browsers tend to interpret character set iso-8859-1 as windows-1252,
	* but Microsoft.XMLHttp doesn't so we need to tell it to do so
	*
	* @param	vB_Registry	Instance of the vBulletin data registry object
	* @param	string	Content type (text/xml etc.)
	* @param	string	Chosen character set - leave blank to use $vbulletin->userinfo['lang_charset']
	*/
	function vB_AJAX_Output(&$registry, $content_type = 'text/xml', $charset = NULL)
	{
		$this->registry =& $registry;
		
		if ($charset == NULL)
		{
			$charset = $this->registry->userinfo['lang_charset'];
		}
		
		$this->charset = (strtolower($charset) == 'iso-8859-1') ? 'windows-1252' : $charset;
		
		$this->set_content_type($content_type);
	}
	
	/**
	* Sets the content type
	*
	* @param	string	Content type
	*/
	function set_content_type($content_type)
	{
		$this->content_type = $content_type;
	}
	
	/**
	* Sends the content type header with $this->content_type
	*/
	function exec_content_type_header()
	{
		@header('Content-Type: ' . $this->content_type . ($this->charset == '' ? '' : '; charset=' . $this->charset));
	}
	
	/**
	* Returns the <?xml tag complete with $this->charset character set defined
	*
	* @return	string	<?xml tag
	*/
	function fetch_xml_tag()
	{
		return '<?xml version="1.0" encoding="' . $this->charset . '"?>' . "\r\n";
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83432 $
|| #######################################################################
\*=========================================================================*/
