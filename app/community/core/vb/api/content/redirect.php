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
 * vB_Api_Content_Redirect
 *
 * @package vBApi
 * @access public
 */
class vB_Api_Content_Redirect extends vB_Api_Content_Text
{
	//override in client- the text name
	protected $contenttype = 'vBForum_Redirect';

	//The table for the type-specific data.
	protected $tablename = array('redirect');

	/**
	 * Constructor, no external instantiation.
	 */
	protected function __construct()
	{
		parent::__construct();
		$this->library = vB_Library::instance('Content_Redirect');
	}

	/**
	 * Adds a new node.
	 *
	 * @param  mixed   $data Array of field => value pairs which define the record.
	 * @param  array   $options Array of options for the content being created.
	 *                 Understands skipTransaction, skipFloodCheck, floodchecktime, skipDupCheck, skipNotification, nl2br, autoparselinks.
	 *                 - nl2br: if TRUE, all \n will be converted to <br /> so that it's not removed by the html parser (e.g. comments).
	 *                 - wysiwyg: if true convert html to bbcode.  Defaults to true if not given.
	 *
	 * @return integer the new nodeid
	 */
	public function add($data, $options = array())
	{
		if (!$this->validate($data, self::ACTION_ADD))
		{
			throw new vB_Exception_Api('no_create_permission');
		}

		$this->cleanInput($data);
		$this->cleanOptions($options);

		$wysiwyg = true;
		if(isset($options['wysiwyg']))
		{
			$wysiwyg = (bool) $options['wysiwyg'];
		}

		$result = $this->library->add($data, $options, $wysiwyg);
		return $result['nodeid'];
	}

	/**
	 * Redirect is not allowed to be updated.
	 *
	 * @throws vB_Exception_Api
	 *
	 * @param  $nodeid
	 * @param  $data
	 *
	 * @return void
	 */
	public function update($nodeid, $data)
	{
		throw new vB_Exception_Api('not_implemented');
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 84682 $
|| #######################################################################
\*=========================================================================*/
