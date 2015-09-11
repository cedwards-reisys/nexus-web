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

class vB_Xml_Import_Theme extends vB_Xml_Import
{
	const DEFAULT_PARENT_GUID = 'vbulletin-theme-parent-53516be9caa311.54941284';
	const DEFAULT_THEME_GUID = 'vbulletin-theme-default-ead8b80bfd72e5257178b41f45c64178';

	protected static $themeParent;

	/*
	 *	Import theme from an array of already parsed XML data. This function should be called from the adminCP
	 */
	public function importAdminCP($parsedXML, $startat = 0, $perpage = 1, $overwrite = false, $styleid = -1, $anyversion = false, $extra = array())
	{
		/*
		 *	Since this function allows passing in a string rather than pulling a file from the filesystem, we should
		 *	be more careful about who can call it
		 *	This check is based on the admincp/template.php script @ if ($_REQUEST['do'] == 'upload'). We should keep them in line.
		 */
		if (!vB::getUserContext()->hasAdminPermission('canadmintemplates') OR !vB::getUserContext()->hasAdminPermission('canadminstyles'))
		{
			require_once(DIR . '/includes/adminfunctions.php');
			print_cp_no_permission();
		}

		if (empty($parsedXML['guid']))
		{
			// todo: some error handling here if basic xml file validation isn't okay.
		}
		$this->parsedXML['theme'] = $parsedXML;

		// make sure we have the theme parent, as any imported themes will be its children
		if (empty(self::$themeParent['guid']))
		{
			$this->getDefaultParentTheme();
		}

		/*
		 *	drop any unexpected extra variables.
		 *	Let's also clean them, since there might be someway a user w/ the right permissions
		 *	hits this function directly. So here we have an issue. If coming through the adminCP page,
		 *	things will already be cleaned, so STRINGS will already be escaped. However, I don't think
		 *	the title should contain any special HTML characters, so I think we don't have to worry about
		 *	double escaping here. If we do end up having to worry about double escaping, we need to remove
		 *	the cleaning here, and just rely on the adminCP page's cleaning, then make sure NOTHING HERE
		 *	GOES STRAIGHT TO DB without going through the assertor in adminfunctions_template.php
		 */
		$unclean = $extra;
		$extra = array();
		$cleanMap = array(
			'title' => vB_Cleaner::TYPE_STR,
			'parentid' => vB_Cleaner::TYPE_INT,
			'displayorder' => vB_Cleaner::TYPE_INT,
			'userselect' => vB_Cleaner::TYPE_BOOL,
		);
		foreach ($unclean AS $key => $value)
		{
			if (isset($cleanMap[$key]))
			{
				$extra[$key] = vB::getCleaner()->clean($value,  $cleanMap[$key]);
			}
		}

		return $this->import($startat, $perpage, $overwrite, $styleid, $anyversion, $extra);
	}

	/*
	 *	Performs the gritty work of importing the parsed XML data into the database
	 *	Before calling this function, the caller must ensure that $this->parsedXML['theme'] is set with
	 *	the parsed XML data. See where this function is called from within function importThemes() for
	 *	an example.
	 *
	 *	Todo: We need some sort of consistent error handling here when provided data is incomplete or
	 *	some errors arise while importing the XML data. AFAIK this function will be accessed from two
	 *	locations, the installer & adminCP.
	 */
	protected function import($startat = 0, $perpage = 1, $overwrite = false, $styleid = -1, $anyversion = false, $extra = array())
	{
		require_once(DIR . '/includes/adminfunctions_template.php');
		require_once(DIR . '/includes/adminfunctions.php');

		$xml = $this->parsedXML['theme'];
		$themeName = $xml['name'];
		unset($this->parsedXML['theme']);

		$phrases = vB_Api::instanceInternal('phrase')->fetch(
			array(
				'theme_x_exists_skipped',
				'theme_x_invalid_data_skiped',
				'theme_x_imported',
			)
		);
		// At the very least we need guid (icon is optional) for a theme
		if (empty($xml['guid']))
		{
			return array(
				'done'    => true,
				'output'  => construct_phrase($phrases['theme_x_invalid_data_skiped'], $xml['name']),
				"error" => "GUID and icon must be specified for a theme!",
			);
		}



		/*
			On import the theme import should fail if the theme is already installed (per the guid value)
			unless an overwrite is requested. If an overwrite is requested than any parent/title information
			for the import is ignored and the existing theme is overwritten by the import.
		*/
		// we don't add the GUID until we're done, so this check works even when we're in the middle of
		// importing a theme in partitions
		$existingTheme = $this->db->getRow('style', array('guid' => $xml['guid']));
		if (!empty($existingTheme))
		{
			// TODO: need to test that overwrite works correctly
			if ($overwrite)
			{
				// ignore parent & title info, overwrite existing theme.
				unset($xml['name']);
				$title = $existingTheme['title'];
				// If styleid is not -1, xml_import_style will ignore the parentid, so it's only used for the style record creation.
				// This works with overwrite (ignore parent relation).
				$parentid = $existingTheme['parentid'];
				$styleid = $existingTheme['styleid'];
				$product = (empty($xml['product']) ? 'vbulletin' : $xml['product']);
				// grab rest form user input if set. Otherwise, use defaults.
				$userselect = (empty($extra['userselect']) ? false : $extra['userselect']);
				$displayorder = (empty($extra['displayorder']) ? 1 : $extra['displayorder']);
			}
			else
			{
				/*
					TODO: The admincp should be updated to attempt the import without overwrite and request
						confirmation for overwrite if the install fails due to the theme already existing.
				 */
				return array(
					'done'    => true,
					'output'  => construct_phrase($phrases['theme_x_exists_skipped'], $xml['name']),
					"warning" => "The theme exists, and overwrite was not specified. Skipping import.",
				);
				// we're not in overwrite mode, and theme already exists. Let's just return.
			}
		}
		elseif ($startat == 0)
		{
			/*
			 *	We need to add this new theme. Setting styleid to -1 should allow the existing import code
			 *	to take care of it for us.
			 */

			$styleid = -1;
			$product = (empty($xml['product']) ? 'vbulletin' : $xml['product']);
			/*
			 *	For a new import, use user selected data for title, parentid and userselect. If no user provided
			 *	input is available, use xml provided info or defaults.
			 *	User input will only be available for adminCP imports, not install/upgrades
			 */
			$title = (empty($extra['title']) ? (!empty($xml['name']) ? $xml['name'] : '') : $extra['title']);
			$parentid = (empty($extra['parentid']) ? self::$themeParent['styleid'] : $extra['parentid']);
			$userselect = (empty($extra['userselect']) ? false : $extra['userselect']);
			$displayorder = (empty($extra['displayorder']) ? 1 : $extra['displayorder']);
		}

		// If styleid is not -1, xml_import_style will ignore the parentid, so it's only used for the style record creation.
		// This works with overwrite (ignore parent relation).
		$info = xml_import_style(false, $styleid, $parentid, $title, $anyversion, $displayorder, $userselect, $startat, $perpage, false, $xml);

		// If we're done importing all the templates 1 by 1, the stylevar definitions & style vars. Let's
		// add the theme-specific data.
		if ($info['done'])
		{
			// If import finished on 1 iteration (which it does if the theme/style has no template groups to import),
			// we need to grab the styleid of the new style record that was created because $styleid is still -1 at
			// this point.
			if ($info['overwritestyleid'] > -1)
			{
				$styleid = $info['overwritestyleid'];
			}

			$updateParams = array('guid' =>	$xml['guid']);

			if (!empty($xml['icon']))
			{
				try
				{
					$filedataid = $this->uploadThemeImage($xml['icon']);
					if ($filedataid)
					{
						$updateParams['filedataid'] = $filedataid;
					}
				}
				catch (Exception $e)
				{
					// ATM we just ignore errors in icon/preview image uploads and just import the theme without it
					// since icons & preview images are optional
					// A neat TODO would be to add a warning here instead, and have the upgrader or adminCP
					// pull all warnings out and display them after each theme's import is finished.
				}
			}

			if (!empty($xml['previewimage']))
			{
				try
				{
					$filedataid = $this->uploadThemeImage($xml['previewimage']);
					if ($filedataid)
					{
						$updateParams['previewfiledataid'] = $filedataid;
					}
				}
				catch (Exception $e)
				{
					// ATM we just ignore errors in icon/preview image uploads and just import the theme without it
					// since icons & preview images are optional
					// A neat TODO would be to add a warning here instead, and have the upgrader or adminCP
					// pull all warnings out and display them after each theme's import is finished.
				}
			}

			// In addition to the filedataid and previewdataid, handle any other theme-specific style table fields here

			$this->db->update('style',
				$updateParams,
				array('styleid' => $styleid)
			);

			// TODO: Figure out what this is used for
			vB_Xml_Import::setImportedId(vB_Xml_Import::TYPE_THEME, $xml['guid'], $styleid);

			if (empty($info['output']))
			{
				$info['output'] = construct_phrase($phrases['theme_x_imported'], $themeName);
			}
		}

		/*
			xml_import_style returns:
			return array(
				'version' => $version,
				'master'  => $master,
				'title'   => $title,
				'product' => $product,
				'done'    => $done,
				'overwritestyleid' => $styleid,
				'output'  => $outputtext,
				);
		 */
		return $info;
	}

	/**
	 * Uploads a theme image (icon or preview image) from the image data
	 *
	 * @param	binary		Image data to upload
	 *
	 * @return	int|bool	Filedataid for the image or false if the image could not be uploaded.
	 */
	public function uploadThemeImageData($imageData)
	{
		$result = $this->uploadImageStreamStringToFiledata($imageData, 'vb_theme_image_');

		// Make the file data public & update style record w/ this filedataid
		if ($result AND !empty($result['filedataid']))
		{
			$this->db->assertQuery('incrementFiledataRefcountAndMakePublic', array('filedataid' => $result['filedataid']));

			return $result['filedataid'];
		}

		return false;
	}

	/**
	 * Uploads a theme image (icon or preview image)
	 *
	 * @param	string	Base64-encoded image data.
	 *
	 * @return	int|bool	Filedataid for the image or false if the image could not be uploaded.
	 */
	protected function uploadThemeImage($encodedImageData)
	{
		// Upload Icon
		$imageData = base64_decode($encodedImageData);

		if ($imageData !== false)
		{
			$result = $this->uploadImageStreamStringToFiledata($imageData, 'vb_theme_image_');

			// Make the file data public & update style record w/ this filedataid
			if ($result AND !empty($result['filedataid']))
			{
				$this->db->assertQuery('incrementFiledataRefcountAndMakePublic', array('filedataid' => $result['filedataid']));

				return $result['filedataid'];
			}
		}

		return false;
	}

	/*
	 *	Saves a string as a temporary file and uploads it to create a filedata record
	 *	TODO: Should we move this into the content_attach library?
	 *
	 *	@param	string	$imageContent	the binary image stream
	 *	@param 	string	$prefix			(Optional) filename prefix for the temporary file
	 *										created by this function. This file will be
	 *										deleted after the filedata record is created.
	 *	@return	array
	 *					key 'filedataid' : the filedataid of the new record that's created
	 */
	protected function uploadImageStreamStringToFiledata($imageContent, $prefix = 'vb_')
	{
		// It doesn't seem like we have existing functions to just save the raw image stream as filedata.
		// The existing content_attach functions seem to all require some temporary file to exist somewhere.
		// So let's save the image in a temporary file, save it to the file system using existing functions,
		// then remove the file.
		$tempIconFileLocation = vB_Utilities::getTmpFileName('', $prefix); // removal happens below, look for the unlink() call
		file_put_contents($tempIconFileLocation, $imageContent);

		/*
		 * Below depends on the GD library
		 * http://www.php.net/manual/en/function.getimagesize.php
		 *	Index 0 and 1 contains respectively the width and the height of the image.
		 *	Index 2 is one of the IMAGETYPE_XXX constants indicating the type of the image.
		 *	Index 3 is a text string with the correct height="yyy" width="xxx" string that can be used directly in an IMG tag.
		 *	mime is the correspondant MIME type of the image. This information can be used to deliver images with the correct HTTP Content-type header:
		 */
		$imageSize = getimagesize($tempIconFileLocation);
		$extension = image_type_to_extension($imageSize[2], false);
		$fileArray = array();
		$fileArray['tmp_name'] = $tempIconFileLocation;

		// apparently fetchThumbnail() requires that the NAME has a valid extension... This is just bogus because we totally made up this file, but whatever
		$fileArray['name'] = 'image_' . md5(microtime(true)) . "." . $extension;

		$userid = vB::getCurrentSession()->get('userid');

		$result = vB_Library::instance('content_attach')->saveUpload(
			$userid,
			$fileArray,
			$imageContent,
			filesize($tempIconFileLocation),
			$extension,
			true,
			true	// $skipUploadPermissionCheck IS A TEMPORARY SOLUTION.
		);

		// temp file deletion is not done automatically, so we must do it.
		if (!empty($tempIconFileLocation))
		{
			@unlink($tempIconFileLocation);
		}

		if (!isset($result['filedataid']))
		{
			/*
			 *	If we cannot upload an image, we should just continue importing the theme as
			 *	icons and preview image are optional.
			 *	I'm leaving an exception here in case we want to handle it better, or we want
			 *	to move this into an API class, so the caller should catch the exception and
			 *	handle it accordingly.
			 */
			throw new vB_Exception_Api('theme_icon_upload_error');
		}

		 /*
		 *	Note, this function returns an array to adhere to API standards just in	case this
		 *	ends up being moved into an API class. If we do move it into an API class, we may
		 *	want to change the Exception above to be a vB_Exception_Api
		 */
		return array('filedataid' => $result['filedataid']);
	}

	/*
	 *	Gets the default parent theme & sets it to self::$themeParent class variable as well as
	 *	returning it. If the theme isn't found, it inserts one to the style table.
	 */
	protected function getDefaultParentTheme()
	{
		// If the default parent theme exists, return it. Else, create it & return it.
		self::$themeParent = $this->db->getRow('style', array('guid' => self::DEFAULT_PARENT_GUID));
		if (!empty(self::$themeParent['guid']))
		{
			return self::$themeParent;
		}
		else
		{
			/*
				VBV-12430 Automatically install packaged themes :
					If there are themes to install, create an empty style call themes.
					Make all installed themes children of this new style.
					This style should itself be a theme (so it is not editable, etc) but should not be displayed or selected
			 */
			$parentTitle = vB_Api::instanceInternal('phrase')->fetch('theme_parent_name');
			$parentTitle = $parentTitle['theme_parent_name'];
			$defaultData = array(
				'guid' => self::DEFAULT_PARENT_GUID,
				'title' => $parentTitle,
				'parentid' => -1,
				'displayorder' => 0,
				'userselect' => 0,
			);
			// we need to add the blank style
			$styleid = $this->db->insert('style', $defaultData);
			if (empty($styleid) OR !is_numeric($styleid))
			{
				// if we can't create the default parent, we don't want to recurse forever.
				throw new vB_Exception_AdminStopMessage('theme_failed_to_create_parent');
			}

			// no reason to copy code. The DB Fetch at the beginning of this function will handle retrieving
			// the newly created style.
			return $this->getDefaultParentTheme();
		}
	}

	/*
	 *	Automatically import the themes included in the theme folder
	 */
	public function importThemes($perpage = 1, $overwrite = false)
	{
		$themesdir = DIR . '/install/themes/';
		if (is_dir($themesdir))
		{
			/*
			 *	There's a bug in the upgrader where only the 'startat' parameter can be returned & used in
			 *	the upgrade steps. Since the same upgrade step will be run for a single file, and we have
			 *	multiple files to process, we need to keep track of 1) which file we were on and 2) which
			 *	iteration of that file we are on. Since we only have 1 variable to keep track of at least
			 *	multiple values, we need another way to keep track of data between sessions.
			 */
			$datastore = vB::getDatastore();
			$dataTitle = "themeImportProgress";
			$importProgress = $datastore->getValue($dataTitle);
			if (is_null($importProgress))
			{
				$importProgress = '';
			}
			$importProgress = json_decode($importProgress, true);
			/*
			 * json_encode-d array of
			 *	'finished' => 	{array of fully imported filenames},
			 *	'current'  => 	{array
			 *						'filename' => {current filename},
			 *						'startat' => {current progress index} ,
			 *						'styleid' => {styleid of added style record if we're not at startat==0}
			 *					},
			 *	'remaining'	=> 	{array of remaining filenames}
			 *
			 *	I'm not storing the parsed XML in here, because I'm not certain if the json_encoded data with the parsed
			 *	XML can be guaranteed to be less than mediumtext's (type of datastore.data) limit of	2^24 + 3 bytes.
			 */
			if (empty($importProgress))
			{
				$importProgress = array(
					'finished' => array(),
					'remaining' =>
						array_filter(
							scandir($themesdir),
							function($filename) use ($themesdir)
							{
								return (
									!is_dir($themesdir . '/' . $filename)
									AND (strtolower(pathinfo($filename, PATHINFO_EXTENSION)) === 'xml')
								); // skip directories and make sure it has xml extension
							}
						),
				);
				// TODO: need better check to grab REAL files only. One approach is to use a filename prefix and check for that, like how customlanguages does it
			}

			// Let's grab the file to work on next if we don't have one
			if (empty($importProgress['current']))
			{
				// we have no files remaining to import
				if (empty($importProgress['remaining']))
				{
					$datastore->build($dataTitle, '', 0);
					return array('done' => true, 'continue' => false, 'output' => '');
				}
				else
				{
					$importProgress['current']['filename'] = $themesdir . '/' . array_shift($importProgress['remaining']);
					$importProgress['current']['startat'] = 0;
					$importProgress['current']['styleid'] = -1;
				}
			}

			// make sure we have the theme parent, as any imported themes will be its children
			if (empty(self::$themeParent['guid']))
			{
				$this->getDefaultParentTheme();
			}

			// fetch the XML file and parse it into something we can understand
			$this->parsedXML['theme'] = vB_Xml_Import::parseFile($importProgress['current']['filename']);

			// ensure that imported theme is a child of the blank parent theme. Any other kind of
			// default data setting global to all "auto-import @ install" themes should happen here.
			$this->parsedXML['theme']['parentid'] = self::$themeParent['styleid'];

			// overwrite check happens inside import()
			$info = $this->import($importProgress['current']['startat'], $perpage, $overwrite, $importProgress['current']['styleid'], true);

			if ($info['done'])
			{
				$importProgress['finished'][] = $importProgress['current']['filename'];
				unset($importProgress['current']);
			}
			else
			{
				$importProgress['current']['styleid'] = $info['overwritestyleid'];
				$importProgress['current']['startat'] += $perpage;
			}

			$importProgress = json_encode($importProgress);

			$datastore->build($dataTitle, $importProgress, 0);

			// move onto the next iteration of this file (or the next file, whichever is appropriate).
			return array('done' => false, 'continue' => true, 'output' => $info['output']);
		}
		else
		{
			// themes folder does not exist. nothing to import
			return array('done' => true, 'continue' => false, 'output' => '');
		}
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 84835 $
|| #######################################################################
\*=========================================================================*/
