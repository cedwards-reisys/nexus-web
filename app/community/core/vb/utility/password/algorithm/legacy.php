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
 *	@package vBUtility
 */

/**
 *	@package vBUtility
 */
class vB_Utility_Password_Algorithm_Legacy extends vB_Utility_Password_Algorithm
{
	public function generateToken($password)
	{
		$salt = $this->generateSalt(); 
		$hash = md5($password . $salt);
		
		//space is pretty much the only character guarentee to be in neither the hash or the salt
		//we need to be able to extract the values for verification so we need to seperate the
		//somehow, preferable in a way that is human readable.
		return ("$hash $salt");
	}

	public function verifyPassword($password, $token)
	{
		list($hash, $salt) = explode(' ', $token);
		return ($hash == md5($password . $salt));
	}

	protected function generateSalt()
	{
		$length = 30;
		$salt = '';
		for ($i = 0; $i < $length; $i++)
		{
			$salt .= chr(rand(33, 126));
		}

		return $salt;
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83435 $
|| #######################################################################
\*=========================================================================*/
