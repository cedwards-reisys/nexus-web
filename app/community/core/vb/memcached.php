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
 * This implements an object wrapper for Memcached
 */
class vB_Memcached extends vB_Memcache
{
	protected function __construct()
	{
		$this->memcached = new Memcached;
		$this->memcached->setOption(Memcached::OPT_COMPRESSION, TRUE);
	}

	protected function addServers()
	{
		if (is_array($this->config['Misc']['memcacheserver']))
		{
			$connected = false;
			foreach (array_keys($this->config['Misc']['memcacheserver']) AS $key)
			{
				$res = $this->memcached->addServer(
					$this->config['Misc']['memcacheserver'][$key],
					$this->config['Misc']['memcacheport'][$key],
					$this->config['Misc']['memcacheweight'][$key]
				);

				if ($res === true)
				{
					$connected = true;
				}
			}

			if (!$connected)
			{
				return 3;
			}
		}
		else if (!$this->memcached->addServer($this->config['Misc']['memcacheserver'], $this->config['Misc']['memcacheport']))
		{
			return 3;
		}

		return 1;
	}

	public function add($key, $value, $expiration = NULL)
	{
		if (!$this->memcached_connected)
		{
			return FALSE;
		}

		if ($expiration === NULL)
		{
			$expiration = $this->defaultExpiration;
		}

		return $this->memcached->add($key, $value, $expiration);
	}

	public function set($key, $value, $expiration = NULL)
	{
		if (!$this->memcached_connected)
		{
			return FALSE;
		}

		if ($expiration === NULL)
		{
			$expiration = $this->defaultExpiration;
		}

		return $this->memcached->set($key, $value, $expiration);
	}

	/**
	 * Close any memcache open connections
	 *
	 * @return	Bool	Whether closing connection was success or failure.
	 */
	public function close()
	{
		if (!$this->memcached_connected)
		{
			return false;
		}

		if (method_exists($this->memcached, 'quit'))
		{
			return $this->memcached->quit();
		}
		else
		{
			return true;
		}
	}

	/**
	 * Retrieve an item
	 *
	 * @param mixed string or array of strings
	 * @return mixed
	 */
	public function get($keys)
	{
		if (!$this->memcached_connected)
		{
			return FALSE;
		}

		if (is_array($keys))
		{
			return $this->memcached->getMulti($keys);
		}

		return $this->memcached->get($keys);
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83435 $
|| #######################################################################
\*=========================================================================*/
