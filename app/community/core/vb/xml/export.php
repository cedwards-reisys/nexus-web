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

abstract class vB_Xml_Export
{
	/**
	 *
	 * @var vB_dB_Assertor 
	 */
	protected $db;
	
	protected $productid;
	
	public function __construct($product = 'vbulletin')
	{
		$this->db = vB::getDbAssertor();
		$this->productid = $product;
	}
	
	public static function createGUID($record, $source = 'vbulletin')
	{
		return vB_GUID::get("$source-");
	}
	
	/**
	 * Export objects to the specified filepath
	 */
	public function export($filepath, $overwrite = TRUE)
	{
		if (!$overwrite AND file_exists($filepath))
		{
			throw new Exception('Target file already exists');
		}
		
		file_put_contents($filepath, $this->getXml());
	}
	
	/**
	 * If an xml builder is passed it appends the objects. Otherwise, an XML string is returned.
	 */
	public abstract function getXml(vB_XML_Builder &$xml = NULL);
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83435 $
|| #######################################################################
\*=========================================================================*/
