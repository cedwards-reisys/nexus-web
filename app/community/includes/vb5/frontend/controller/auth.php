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

class vB5_Frontend_Controller_Auth extends vB5_Frontend_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function actionLoginForm(array $errors = array(), array $formData = array())
	{
		$disableLoginForm = false;

		//@TODO: Validate URL to check against whitelisted URLs
		// VBV-8394 Remove URLPATH querystring from Login form URL
		// use referer URL instead of querystring
		//  however, if the query string is provided, use that instead to handle older URLs
		if (empty($_REQUEST['url']))
		{
			// use referrer
			$url = filter_var(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : vB5_Template_Options::instance()->get('options.frontendurl'), FILTER_SANITIZE_STRING);
		}
		else
		{
			// it's an old url. Use the query string
			$url = filter_var(isset($_REQUEST['url']) ? $_REQUEST['url'] : vB5_Template_Options::instance()->get('options.frontendurl'), FILTER_SANITIZE_STRING);
		}

		// if it's encoded, we need to decode it to check if it's gonna try to redirect to the login or registration form.
		$url_decoded = base64_decode($url, true);
		$url_decoded = $url_decoded ? $url_decoded : $url;
		if (!empty($url_decoded) AND (strpos($url_decoded, '/auth/') !== false OR strpos($url_decoded, '/register') !== false))
		{
			$url = '';
		}

		// Try to resolve some XSS attack. See VBV-1124
		// Make sure the URL hasn't been base64 encoded already
		if (!base64_decode($url, true))
		{
			$url = base64_encode($url);
		}

		// VBV-7835 Stop search engine index this page
		header("X-Robots-Tag: noindex, nofollow");


		// START: Enforce using https for login if frontendurl_login is set to https (VBV-8474)

		// get the current URL and the base login URL for comparison
		$requestBaseUrl = vB5_Request::instance()->get('vBUrlWebroot');
		$loginBaseUrl = vB5_Template_Options::instance()->get('options.frontendurl_login');

		$matchA = preg_match('#^(https?)://#', $requestBaseUrl, $matchResultA);
		$matchB = preg_match('#^(https?)://#', $loginBaseUrl, $matchResultB);

		// if the URL scheme (http or https) doesn't match, redirect to the right one
		if (!($matchA AND $matchB AND $matchResultA[1] === $matchResultB[1]))
		{
			// avoid infinite redirects
			if (isset($_REQUEST['vb_login_redirected']) AND $_REQUEST['vb_login_redirected'] == 1)
			{
				// Something exteral to vB is redirecting back from https to http.
				// Since we can't allow logging in over http if configured for https,
				// we can't show the login form here
				if (!isset($errors['errors']))
				{
					$errors['errors'] = array();
				}
				$errors['errors'][] = 'unable_to_redirect_to_the_correct_login_url';
				$disableLoginForm = true;
			}
			else
			{
				header('Location: ' . $loginBaseUrl . '/auth/login-form?vb_login_redirected=1&url=' . urlencode($url));
				exit;
			}
		}

		// END: Enforce using https for login if frontendurl_login is set to https


		$user = vB5_User::instance();

		$templater = new vB5_Template('login_form');
		$templater->register('charset', $user['lang_charset']);
		$templater->register('errors', $errors);
		$templater->register('formData', $formData);
		$templater->register('url', $url);
		$templater->register('urlpath', $url);
		$templater->register('disableLoginForm', $disableLoginForm);

		$this->outputPage($templater->render());
	}

	public function actionLogin()
	{
		$api = Api_InterfaceAbstract::instance();

		// @todo password is currently sent as plain text
		if (!isset($_POST['username']) OR !isset($_POST['password']))
		{
			$this->actionLoginForm();
		}
		else
		{
			$loginInfo = $api->callApi('user', 'login', array($_POST['username'], $_POST['password'], $_POST['vb_login_md5password'], $_POST['vb_login_md5password_utf'], ''));

			if (isset($loginInfo['errors']) AND !empty($loginInfo['errors']))
			{
				$errorIds = array();
				foreach ($loginInfo['errors'] AS $k => $error)
				{
					$errorIds[] = $errorId = array_shift($error);
					// this enables the template code to parse phrases with unknown number of variables
					$loginInfo['errors'][$k] = array($errorId, $error);
				}

				$loginErrors = array(
					'errors' => $loginInfo['errors'],
					'errorIds' => implode(' ', $errorIds)
				);

				$this->actionLoginForm($loginErrors, array(
					'username' => $_POST['username'],
					'remembermeCheckedAttr' => ((isset($_POST['rememberme']) AND $_POST['rememberme']) ? ' checked="checked"' : ''),
				));
			}
			else
			{
				vB5_Auth::setLoginCookies($loginInfo, '', !empty($_POST['rememberme']));
				vB5_Auth::doLoginRedirect();
			}
		}
	}

	public function actionInlinemodLogin()
	{
		$api = Api_InterfaceAbstract::instance();

		$currentuser = vB5_User::instance();

		if (empty($currentuser['userid']))
		{
			if (!empty($_POST['username']))
			{
				$loginInfo = $api->callApi('user', 'login', array($_POST['username'], $_POST['password']));

				if (empty($loginInfo['errors']) AND !empty($loginInfo['userid']))
				{
					$userInfo = $api->callApi('user', 'fetchUserinfo', array($loginInfo['userid']));
					$username =  $userInfo['username'];

					vB5_Auth::setLoginCookies($loginInfo, '', !empty($_POST['rememberme']));
				}
				else
				{
					$this->sendAsJson(array('error' => 'inlinemod_auth_login_failed'));
					return false;
				}
			}

			if (empty($username))
			{
				$this->sendAsJson(array('error' => 'inlinemod_auth_login_first'));
				return false;
			}
		}
		else
		{
			$username = $currentuser['username'];
		}

		if (empty($_POST['password']))
		{
			$this->sendAsJson(array('error' => 'inlinemod_auth_password_empty'));
			return false;
		}

		$loginInfo = $api->callApi('user', 'login', array($username, $_POST['password'], '', '', 'cplogin'));

		if (isset($loginInfo['errors']) AND !empty($loginInfo['errors']))
		{
			$this->sendAsJson(array('error' => 'inlinemod_auth_login_failed'));
			return false;
		}
		else
		{
			vB5_Auth::setLoginCookies($loginInfo, 'cplogin', !empty($_POST['rememberme']));

			$this->sendAsJson(true);
			return true;
		}
	}

	/**
	 *	Logs a user in via an exernal login provider
	 *
	 *	Currently only facebook is supported.
	 *
	 *	Expects the a post with:
	 *	* provider -- currently ignored, should be passed as "facebook" for future compatibility
	 *	* auth -- Facebook auth token for FB user to connect to (provide by FB JS SDK)
	 *
	 * 	outputs the result of the the loginExternal API call as JSON
	 *	@return boolean
	 */
	public function actionLoginExternal()
	{
		$result = array();
		$api = Api_InterfaceAbstract::instance();
		$response = $api->callApi('user', 'loginExternal',
			array(
				$_REQUEST['provider'],
				array('token' => $_REQUEST['auth'])
			)
		);

		if ($this->handleErrorsForAjax($result, $response))
		{
			$this->sendAsJson($result);
			return false;
		}

		vB5_Auth::setLoginCookies($response['login'], 'external', true);
		$this->sendAsJson(array('response' => $response));
		return true;
	}

	/**
	 * 	Logs a user in via a vb login and connects them to a facebook account
	 *
	 *	Expects post fields for login (only one of the three password fields is strictly required --
	 *	Typically either the password (plain text) or the md5 pair are passed but not both):
	 *	* password
	 *	* vb_login_md5password
	 *	* vb_login_md5password_utf
	 *	* username
	 *	* auth -- Facebook auth token for FB user to connect to (provide by FB JS SDK)
	 *
	 *	If the connection fails then login tokens will not be set and the user will not be logged in even
	 *	if the login portion succeeds.
	 *
	 *	Will output a JSON object with either a standard error message or {'redirect' : $homepageurl}
	 *	@return boolean
	 */
	public function actionLoginAndAssociate()
	{
		$result = array();
		$api = Api_InterfaceAbstract::instance();

		//we might not get all of these
		$password = isset($_POST['password']) ? $_POST['password'] : '';
		$vb_login_md5password = isset($_POST['vb_login_md5password']) ? $_POST['vb_login_md5password'] : '';
		$vb_login_md5password_utf = isset($_POST['vb_login_md5password_utf']) ? $_POST['vb_login_md5password_utf'] : '';

		//login
		$loginInfo = $api->callApi('user', 'login', array($_POST['username'], $password, $vb_login_md5password, $vb_login_md5password_utf, ''));
		if ($this->handleErrorsForAjax($result, $response))
		{
			$this->sendAsJson($result);
			return false;
		}

		$api = Api_InterfaceAbstract::instance();
		$response = $api->callApi('facebook', 'connectCurrentUser', array('token' => $_POST['auth']));

		if ($this->handleErrorsForAjax($result, $response))
		{
			$this->sendAsJson($result);
			return false;
		}

		//don't set the auth cookies until after we have connected the user
		vB5_Auth::setLoginCookies($loginInfo, '', !empty($_POST['rememberme']));

		$homeurl = $api->callApi('route', 'getUrl', array('home', array(), array()));
		$this->sendAsJson(array('redirect' => $homeurl));
		return true;
	}

	public function actionLogout()
	{
		$api = Api_InterfaceAbstract::instance();
		$api->callApi('user', 'logout', array($_REQUEST['logouthash']));

		//delete all cookies with cookiePrefix
		vB5_Cookie::deleteAll();

		// @todo: this should redirect the user back to where they were
		header('Location: ' . vB5_Template_Options::instance()->get('options.frontendurl'));
		exit;
	}

	/**
	 * Forgot password form action
	 * Reset url = /auth/lostpw/?action=pwreset&userid=<n>&activationid=<xxxxx>
	 */
	public function actionLostpw()
	{
		$input = array(
			// Send request
			'email' => (isset($_POST['email']) ? trim(strval($_POST['email'])) : ''),
			'hvinput' => isset($_POST['humanverify']) ? (array)$_POST['humanverify'] : array(),

			// Reset Request
			'action' => (isset($_REQUEST['action']) ? trim($_REQUEST['action']) : ''),
			'userid' => (isset($_REQUEST['userid']) ? trim(strval($_REQUEST['userid'])) : ''),
			'activationid' => (isset($_REQUEST['activationid']) ? trim($_REQUEST['activationid']) : ''),
		);

		if (isset($_POST['recaptcha_challenge_field']) AND $_POST['recaptcha_challenge_field'])
		{
			$input['hvinput']['recaptcha_challenge_field'] = $_POST['recaptcha_challenge_field'];
		}
		if (isset($_POST['recaptcha_response_field']) AND $_POST['recaptcha_response_field'])
		{
			$input['hvinput']['recaptcha_response_field'] = $_POST['recaptcha_response_field'];
		}

		$api = Api_InterfaceAbstract::instance();

		if ($input['action'] == 'pwreset')
		{
			$response = $api->callApi('user', 'resetPassword', array('userid' => $input['userid'], 'activationid' => $input['activationid']));
			if(isset($response['errors']))
			{
				$phraseController = vB5_Template_Phrase::instance();
				$phraseController->register('error');

				//call message first so that we pull both phrases at the same time
				$message = call_user_func_array(array($phraseController, 'getPhrase'), $response['errors'][0]);
				$title = $phraseController->getPhrase('error');
			}
			else
			{
				$title = $response['password_reset'];
				$message = $response['resetpw_message'];
			}

			vB5_ApplicationAbstract::showMsgPage($title, $message);
		}
		else
		{
			$response = $api->callApi('user', 'emailPassword', array('userid' => 0, 'email' => $input['email'], 'hvinput' => $input['hvinput']));
			$this->sendAsJson(array('response' => $response));
		}
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83435 $
|| #######################################################################
\*=========================================================================*/
