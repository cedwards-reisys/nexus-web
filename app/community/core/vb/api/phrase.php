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
 * vB_Api_Phrase
 *
 * @package vBApi
 * @access public
 */
class vB_Api_Phrase extends vB_Api
{
	protected $disableWhiteList = array('fetch');

	protected $styles = array();
	protected $phrasecache = array();

	/**
	 *
	 * @var vB_Library_Phrase
	 */
	protected $library;

	protected function __construct()
	{
		parent::__construct();
		$this->library = vB_Library::instance('phrase');
	}

	/**
	 * Fetch phrases by group
	 *
	 * @param mixed	Groups(s) to retrieve
	 * @param int $languageid Language ID. If not set, it will use current session's languageid
	 *
	 * @return array Phrase' texts
	 */
	public function fetchByGroup($groups, $languageid = NULL)
	{
		if (empty($groups))
		{
			return array();
		}

		if (!is_array($groups))
		{
			$groups = array($groups);
		}

		if ($languageid === NULL)
		{
			$currentsession = vB::getCurrentSession();
			if ($currentsession)
			{
				$languageid = $currentsession->get('languageid');
				if (!$languageid)
				{
					$userinfo = vB::getCurrentSession()->fetch_userinfo();
					$languageid = $userinfo['languageid'];
				}
			}

			// Still no languageid, try to get current default languageid
			if (!$languageid)
			{
				$languageid = vB::getDatastore()->getOption('languageid');
			}

			// Still don't have a language, fall back to master language
			if (!$languageid)
			{
				$languageid = -1;
			}
		}

		$phrasesdata = array();
		$phrasesdata = vB::getDbAssertor()->getRows('phrase', array(
			vB_dB_Query::TYPE_KEY => vB_dB_Query::QUERY_SELECT,
			'fieldname' => $groups,
			'languageid' => array($languageid, 0, -1),
		));

		return $this->parsePhrases($phrasesdata);
	}

	/**
	 * Fetch the "best" languageid in the order of current session's languageid,
	 * the default languageid from datastore,  or the master languageid (-1).
	 * This is the languageid that's used by fetch().
	 *
	 * @param bool	$getCharset		(Optional) true to also return charset of current
	 *								languageid. Default false.
	 *
	 * @return array 'languageid' => languageid used for current session's phrases
	 *
	 */
	public function getLanguageid($getCharset = false)
	{
		$currentsession = vB::getCurrentSession();
		if ($currentsession)
		{
			$languageid = $currentsession->get('languageid');
			if (!$languageid)
			{
				$userinfo = vB::getCurrentSession()->fetch_userinfo();
				$languageid = $userinfo['languageid'];
			}
		}

		// Still no languageid, try to get current default languageid
		if (!$languageid)
		{
			$languageid = vB::getDatastore()->getOption('languageid');
		}

		// Still don't have a language, fall back to master language
		if (!$languageid)
		{
			$languageid = -1;
		}

		$returnArray = array('languageid' => $languageid);

		if ($getCharset)
		{
			$charset = vB::getDbAssertor()->getColumn('language', 'charset', array('languageid' => $languageid));
			if (is_array($charset))
			{
				$charset = $charset[0];
			}

			$returnArray['charset'] = $charset;
		}

		return $returnArray;
	}

	/**
	 * Fetch phrases
	 *
	 * @param array $phrases An array of phrase ID to be fetched
	 * @param int $languageid Language ID. If not set, it will use current session's languageid
	 *
	 * @return array Phrase' texts
	 */
	public function fetch($phrases, $languageid = NULL)
	{
		if (empty($phrases))
		{
			return array();
		}

		if (!is_array($phrases))
		{
			$phrases = array($phrases);
		}

		if ($languageid === NULL)
		{
			$languageid = $this->getLanguageid();
			if (isset($languageid['languageid']))
			{
				$languageid = $languageid['languageid'];
			}
			else
			{
				$languageid = -1;
			}
		}

		// Unset phrases which have already been fetched
		$cachedphrasevars = array_keys($this->phrasecache);
		$phrasestofetch = array();
		$fromcache = array();
		foreach ($phrases as $phrasevar)
		{
			if (!in_array($phrasevar, $cachedphrasevars))
			{
				$phrasestofetch[] = $phrasevar;
			}
			else
			{
				$fromcache[] = $phrasevar;
			}
		}

		$return = array();
		if (!empty($phrasestofetch))
		{
			//First try from fastds
			$fastDS =  vB_FastDS::instance();

			if ($fastDS AND ($languageid > -1))
			{
				$cached = $fastDS->getPhrases($phrasestofetch, $languageid);
			}
			else if ($fastDS)
			{
				$cached = $fastDS->getPhrases($phrasestofetch, -1);
			}

			if (!empty($cached))
			{
				foreach ($phrasestofetch as $index => $phraseKey)
				{
					if (!empty($cached[$phraseKey]))
					{
						unset($phrasestofetch[$index]);
						$return[$phraseKey] = $cached[$phraseKey];
					}
				}
			}

			if (!empty($phrasestofetch))
			{
				$phrasesdata = vB::getDbAssertor()->getRows('phrase', array(
					vB_dB_Query::TYPE_KEY => vB_dB_Query::QUERY_SELECT,
					'varname' => $phrasestofetch,
					'languageid' => array($languageid, 0, -1),
				));
				$parsed = $this->parsePhrases($phrasesdata, $phrases);
				$return = array_merge($return, $parsed);
			}

		}

		if (!empty($fromcache))
		{
			foreach ($fromcache AS $phraseid)
			{
				$phrasesdata[$phraseid] = $this->phrasecache[$phraseid];
			}

			$parsed = $this->parsePhrases($phrasesdata, $phrases);
			$return = array_merge($return, $parsed);
		}

		return $return;
	}

	/**
	 * Handled output from query from fetch() and fetchByGroup()
	 *
	 * @param	array	Phrases from db
	 * @param	array	Phrases sent into to get from the db
	 *
	 * @return array	Phrases phrases
	 */
	protected function parsePhrases($phrasesdata, $phrases = array())
	{
		$realphrases = array();
		foreach ($phrasesdata AS $phrase)
		{
			// User-selected language (>=1) overwrites custom phrase (0), which overwrites master language phrase (-1)
			if (empty($realphrases[$phrase['varname']]) OR $realphrases[$phrase['varname']]['languageid'] < $phrase['languageid'] )
			{
				$realphrases[$phrase['varname']] = $phrase;
			}
		}

		$this->phrasecache = array_merge($this->phrasecache, $realphrases);

		foreach ($phrases AS $phrasevar)
		{
			if (empty($realphrases[$phrasevar]) AND !empty($this->phrasecache[$phrasevar]))
			{
				$realphrases[$phrasevar] = $this->phrasecache[$phrasevar];
			}
		}

		$return = array();
		foreach ($realphrases AS $phrase)
		{
			// TODO: store this somewhere? -- might as well store phrases converted now to
			// stop all this real time conversion
			//if (strpos($phrase['text'], '{1}') !== false)	// We can't rely on strpos as users may
			// edit phrases & remove {1} for ex.
			if (preg_match('/\{([0-9]+)\}/', $phrase['text']))
			{
				$search = array(
					'/%/s',
					'/\{([0-9]+)\}/siU',
				);
				$replace = array(
					'%%',
					'%\\1$s',
				);
				$return[$phrase['varname']] = preg_replace($search, $replace, $phrase['text']);
			}
			else
			{
				$return[$phrase['varname']] = $phrase['text'];
			}
		}

		return $return;
	}

	/**
	 * Fetch phrases
	 *
	 * @param array $phrases An array of phrase ID to be fetched
	 * @param int $languageid Language ID. If not set, it will use current session's languageid
	 *
	 * @return array Phrase' texts
	 */
	public static function fetchStatic($phrases, $languageid = NULL)
	{
		if (empty($phrases))
		{
			return array();
		}
		$languageIds = array($languageid, 0, -1);
		$languages = vB::getDatastore()->getValue('languagecache');
		$fastDS = vB_FastDS::instance();

		//First try from fastds
		if ($fastDS AND ($languageid > -1))
		{
			$cached = $fastDS->getPhrases((array)$phrases, $languageid);
		}
		else if ($fastDS)
		{
			$cached = $fastDS->getPhrases((array)$phrases, -1);
		}
		$return = array();

		if (!empty($cached))
		{
			foreach ($phrases as $index => $phraseKey)
			{
				if (!empty($cached[$phraseKey]))
				{
					unset($phrases[$index]);
					$return[$phraseKey] = $cached[$phraseKey];
				}
			}
		}

		if (!empty($phrases))
		{
			$phrasesdata = vB::getDbAssertor()->assertQuery('fetchPhraseList', array( 'varname' => $phrases,
				'languageid' => $languageIds));

			$realphrases = array();
			foreach ($phrasesdata AS $phrase)
			{
				// User-selected language (>=1) overwrites custom phrase (0), which overwrites master language phrase (-1)
				if (empty($realphrases[$phrase['varname']]) OR $realphrases[$phrase['varname']]['languageid'] < $phrase['languageid'])
				{
					$realphrases[$phrase['varname']] = $phrase;
					if (strpos($phrase['text'], '{1}') !== false)
					{
						$search = array(
							'/%/s',
							'/\{([0-9]+)\}/siU',
						);
						$replace = array(
							'%%',
							'%\\1$s',
						);
						$thisPhrase = preg_replace($search, $replace, $phrase['text']);
					}
					else
					{
						$thisPhrase = $phrase['text'];
					}

					// Conversion to UTF-8 is done in sendAsJson(), so if we do it here, we'll end up double-encoding and potentially corrupting
					// multibyte characters.
					/*
					if (!empty($languages[$phrase['languageid']])
						AND !empty($languages[$phrase['languageid']]['charset'])
						AND ($languages[$phrase['languageid']]['charset'] !== 'UTF-8'))
					{
						$thisPhrase = vB5_String::toCharset($phrase['text'], $languages[$phrase['languageid']]['charset'], 'UTF-8');
					}
					*/
					$return[$phrase['varname']] =  $thisPhrase;
				}
			}
		}

		return $return;
	}

	/**
	 * Fetch orphan phrases
	 * @return array Orphan phrases
	 */
	public function fetchOrphans()
	{
		$this->checkHasAdminPermission('canadminlanguages');

		$phrases = vB::getDbAssertor()->getRows('phrase_fetchorphans', array(
			vB_dB_Query::TYPE_KEY => vB_dB_Query::QUERY_STORED,
		));

		return $phrases;
	}

	/**
	 * Process orphan phrases
	 * @param array $del Orphan phrases to be deleted. In format array('varname@fieldname')
	 * @param array $keep Orphan phrases to be kept
	 * @return void
	 */
	public function processOrphans($del, $keep)
	{
		$this->checkHasAdminPermission('canadminlanguages');

		require_once(DIR . '/includes/adminfunctions_language.php');

		if ($del)
		{
			vB::getDbAssertor()->assertQuery('deleteOrphans', array(
				vB_dB_Query::TYPE_KEY => vB_dB_Query::QUERY_METHOD,
				'del' => $del,
			));
		}

		if ($keep)
		{
			vB::getDbAssertor()->assertQuery('keepOrphans', array(
				vB_dB_Query::TYPE_KEY => vB_dB_Query::QUERY_METHOD,
				'keep' => $keep,
			));
		}
	}

	/**
	 * Find custom phrases that need updating
	 * @return array Updated phrases
	 */
	public function findUpdates()
	{
		$this->checkHasAdminPermission('canadminlanguages');

		require_once(DIR . '/includes/adminfunctions_template.php');
		require_once(DIR . '/includes/adminfunctions.php');
		$full_product_info = fetch_product_list(true);
		// query custom phrases
		$customcache = array();
		$phrases = vB::getDbAssertor()->getRows('phrase_fetchupdates', array(
			vB_dB_Query::TYPE_KEY => vB_dB_Query::QUERY_STORED,
		));

		foreach ($phrases as $phrase)
		{
			if ($phrase['globalversion'] == '')
			{
				// No version on the global phrase. Wasn't edited in 3.6,
				// can't tell when it was last edited. Skip it.
				continue;
			}

			if ($phrase['customversion'] == '' AND $phrase['globalversion'] < '3.6')
			{
				// don't know when the custom version was last edited,
				// and the global was edited before 3.6, so we don't know what's newer
				continue;
			}

			if (!$phrase['product'])
			{
				$phrase['product'] = 'vbulletin';
			}

			$product_version = $full_product_info["$phrase[product]"]['version'];

			if (is_newer_version($phrase['globalversion'], $phrase['customversion']))
			{
				$customcache["$phrase[languageid]"]["$phrase[phraseid]"] = $phrase;
			}
		}

		return $customcache;
	}

	/**
	 * Search phrases
	 * @param array $criteria Criteria to search phrases. It may have the following items:
	 *              'searchstring'	=> Search for Text
	 *              'searchwhere'	=> Search in: 0 - Phrase Text Only, 1 - Phrase Variable Name Only, 2 - Phrase Text and  Phrase Variable Name
	 *              'casesensitive' => Case-Sensitive 1 - Yes, 0 - No
	 *              'exactmatch'	=> Exact Match 1 - Yes, 0 - No
	 *              'languageid'	=> Search in Language. The ID of the language
	 *              'phrasetype'	=> Phrase Type. Phrase group IDs to search in.
	 *              'transonly'		=> Search Translated Phrases Only  1 - Yes, 0 - No
	 *              'product'		=> Product ID to search in.
	 *
	 * @return array Phrases
	 */
	public function search($criteria)
	{
		//This should only be called from admincp, and the permission there is 'canadminlanguages'.
		if (!vB::getUserContext()->hasAdminPermission('canadminlanguages'))
		{
			throw new vB_Exception_Api('no_permission');
		}

		//if searchstring is not set, throw exception
		if ($criteria['searchstring'] == ''){
			throw new vB_Exception_Api('please_complete_required_fields');
		}
		$criteria['searchstring'] = vB::getCleaner()->clean($criteria['searchstring'], vB_Cleaner::TYPE_STR);

		//if searchwhere criteria is not set, defaults to 0 - Phrase Text Only search, mimicking admincp phrase search settings
		if (!isset($criteria['searchwhere'])) {
			$criteria['searchwhere'] = 0;
		}
		$criteria['searchwhere'] = vB::getCleaner()->clean($criteria['searchwhere'], vB_Cleaner::TYPE_INT);

		//if casesensitive criteria is not set, defaults to 0, mimicking admincp phrase search settings
		if (!isset($criteria['casesensitive'])){
			$criteria['casesensitive'] = 0;
		}
		$criteria['casesensitive'] = vB::getCleaner()->clean($criteria['casesensitive'], vB_Cleaner::TYPE_INT);

		//if exactmatch criteria is not set, defaults to 0, mimicking admincp phrase search settings
		if (!isset($criteria['exactmatch'])){
			$criteria['exactmatch'] = 0;
		}
		$criteria['exactmatch'] = vB::getCleaner()->clean($criteria['exactmatch'], vB_Cleaner::TYPE_INT);

		//if language criteria is not set, defaults to -10, mimicking admincp phrase search settings
		if(!isset($criteria['languageid'])){
			$criteria['languageid'] = -10;
		}
		$criteria['languageid'] = vB::getCleaner()->clean($criteria['languageid'], vB_Cleaner::TYPE_INT);

		//if transonly criteria is not set, defaults to 0, mimicking admincp phrase search settings
		if (!isset($criteria['transonly'])){
			$criteria['transonly'] = 0;
		}
		$criteria['transonly'] = vB::getCleaner()->clean($criteria['transonly'], vB_Cleaner::TYPE_INT);

		//if product criteria is not set, defaults to all products, mimicking admincp phrase search settings
		if(!isset($criteria['product'])){
			$criteria['product']='';
		}
		$criteria['product'] = vB::getCleaner()->clean($criteria['product'], vB_Cleaner::TYPE_STR);


		$phrases = vB::getDbAssertor()->getRows('searchPhrases', array(
			vB_dB_Query::TYPE_KEY => vB_dB_Query::QUERY_METHOD,
			'criteria' => $criteria,
		));

		if (empty($phrases))
		{
			return array();
		}

		$phrasearray = array();
		foreach ($phrases as $phrase)
		{
			// check to see if the languageid is already set
			if ($criteria['languageid'] > 0 AND isset($phrasearray["$phrase[fieldname]"]["$phrase[varname]"]["{$criteria['languageid']}"]))
			{
				continue;
			}
			$phrasearray["{$phrase['fieldname']}"]["{$phrase['varname']}"]["{$phrase['languageid']}"] = $phrase;
		}

		return $phrasearray;
	}

	/**
	 * Find and replace phrases in languages
	 *
	 * @param array $replace A list of phrase ID to be replaced
	 * @param string $searchstring Search string
	 * @param string $replacestring Replace string
	 * @param int $languageid Language ID
	 * @return void
	 */
	public function replace($replace, $searchstring, $replacestring, $languageid)
	{
		$this->checkHasAdminPermission('canadminlanguages');

		if (empty($replace))
		{
			throw new vB_Exception_Api('please_complete_required_fields');
		}

		$userinfo = vB::getCurrentSession()->fetch_userinfo();

		require_once(DIR . '/includes/adminfunctions.php');
		$full_product_info = fetch_product_list(true);

		$phrases = vB::getDbAssertor()->assertQuery('phrase', array(
			vB_dB_Query::TYPE_KEY => vB_dB_Query::QUERY_SELECT,
			'phraseid' => $replace
		));

		$products =array();

		foreach ($phrases as $phrase)
		{
			$phrase['product'] = (empty($phrase['product']) ? 'vbulletin' : $phrase['product']);
			$phrase['text'] = str_replace($searchstring, $replacestring, $phrase['text']);

			if ($phrase['languageid'] == $languageid)
			{ // update
				vB::getDbAssertor()->assertQuery('phrase', array(
					vB_dB_Query::TYPE_KEY => vB_dB_Query::QUERY_UPDATE,
					'text' => $phrase['text'],
					'username' => $userinfo['username'],
					'dateline' => vB::getRequest()->getTimeNow(),
					'version' => $full_product_info["$phrase[product]"]['version'],
					vB_dB_Query::CONDITIONS_KEY => array(
						'phraseid' => $phrase['phraseid']
					)
				));
			}
			else
			{ // insert
				/*insert query*/
				vB::getDbAssertor()->assertQuery('phrase_replace', array(
					'languageid' => $languageid,
					'varname' => $phrase['varname'],
					'text' => $phrase['text'],
					'fieldname' => $phrase['fieldname'],
					'product' => $phrase['product'],
					'username' => $userinfo['username'],
					'dateline' => vB::getRequest()->getTimeNow(),
					'version' => $full_product_info["$phrase[product]"]['version'],
				));
			}
			$products[$phrase['product']] = 1;
		}
		$this->setPhraseDate();
		return array_keys($products);
	}

	/**
	 * Delete a phrase
	 * @param int $phraseid Pharse ID to be deleted
	 * @return void
	 */
	public function delete($phraseid)
	{
		$this->checkHasAdminPermission('canadminlanguages');

		$getvarname = vB::getDbAssertor()->getRow('phrase', array(
			vB_dB_Query::TYPE_KEY => vB_dB_Query::QUERY_SELECT,
			'phraseid' => $phraseid,
		));

		if ($getvarname)
		{
			vB::getDbAssertor()->assertQuery('phrase', array(
				vB_dB_Query::TYPE_KEY => vB_dB_Query::QUERY_DELETE,
				'varname' => $getvarname['varname'],
				'fieldname' => $getvarname['fieldname'],
			));

			require_once(DIR . '/includes/adminfunctions.php');
			require_once(DIR . '/includes/adminfunctions_language.php');
			build_language(-1);
		}
		else
		{
			throw new vB_Exception_Api('invalid_phrase_specified');
		}
		return $getvarname;
	}

	/**
	 * Add a new phrase or update an existing phrase
	 * @param string $fieldname New Phrase Type for adding, old Phrase Type for editing
	 * @param string $varname New Varname for adding, old Varname for editing
	 * @param array $data Phrase data to be added or updated
	 *              'text' => Phrase text array.
	 *              'oldvarname' => Old varname for editing only
	 *              'oldfieldname' => Old fieldname for editing only
	 *              't' =>
	 *              'ismaster' =>
	 *              'product' => Product ID of the phrase
	 * @return void
	 */
	public function save($fieldname, $varname, $data)
	{
		$this->checkHasAdminPermission('canadminlanguages');

		$this->library->save($fieldname, $varname, $data);
	}

	/**
	 * Fetches an array of existing phrase types from the database
	 *
	 * @param	boolean	If true, will return names run through ucfirst()
	 *
	 * @return	array
	 */
	public function fetch_phrasetypes($doUcFirst = false)
	{
		$out = array();
		$phrasetypes = vB::getDbAssertor()->assertQuery('phrasetype', array(
			vB_dB_Query::TYPE_KEY => vB_dB_Query::QUERY_SELECT,
			vB_dB_Query::CONDITIONS_KEY => array(
				array('field' => 'editrows', 'value' => '0', 'operator' => vB_dB_Query::OPERATOR_NE)
			)
		));
		foreach ($phrasetypes as $phrasetype)
		{
			$out["{$phrasetype['fieldname']}"] = $phrasetype;
			$out["{$phrasetype['fieldname']}"]['field'] = $phrasetype['title'];
			$out["{$phrasetype['fieldname']}"]['title'] = ($doUcFirst ? ucfirst($phrasetype['title']) : $phrasetype['title']);
		}
		ksort($out);

		return $out;
	}

	/**
	 * Returns message and subject for an email.
	 *
	 * @param string $email_phrase Name of email phrase to fetch
	 * @param array $email_vars Variables for the email message phrase
	 * @param array $emailsub_vars Variables for the email subject phrase
	 * @param int $languageid Language ID from which to pull the phrase (see fetch_phrase $languageid)
	 * @param string	$emailsub_phrase If not empty, select the subject phrase with the given name
	 *
	 * @return array
	 */
	public function fetchEmailPhrases($email_phrase, $email_vars = array(), $emailsub_vars = array(), $languageid = 0, $emailsub_phrase = '')
	{
		if (empty($emailsub_phrase))
		{
			$emailsub_phrase = $email_phrase . '_gemailsubject';
		}

		$email_phrase .= '_gemailbody';

		$vbphrases = $this->fetch(array($email_phrase, $emailsub_phrase), $languageid);

		return array(
			'message' => vsprintf($vbphrases[$email_phrase], $email_vars),
			'subject' => vsprintf($vbphrases[$emailsub_phrase], $emailsub_vars),
		);
	}

	/**
	 * Resets the phrase cachebuster date.
	 */
	public function setPhraseDate()
	{
		vB_Cache::instance()->event("vB_Language_languageCache");
		$options = vB::getDatastore()->getValue('miscoptions');
		$options['phrasedate'] = vB::getRequest()->getTimeNow();
		vB::getDatastore()->build('miscoptions', serialize($options), 1);
	}

	/**
	 * Clears the phrase cache, needed primarily for unit test.
	 */
	public function clearPhraseCache()
	{
		$this->phrasecache = array();
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83849 $
|| #######################################################################
\*=========================================================================*/
