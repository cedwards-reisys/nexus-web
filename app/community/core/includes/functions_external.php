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

// ###################### Start makejs #######################
function print_js($typename, $data, $dates)
{
	// make the javascript function definition
	global $vbulletin;

	// make the function

	if ($vbulletin->db->num_rows($data))
	{

		echo 'function ' . $typename . ' (';

		$firstline = $vbulletin->db->fetch_array($data);

		$firstitem = false;
		foreach ($firstline AS $name => $value)
		{

			if ($firstitem)
			{
				echo ', ';
			}
			$firstitem = true;

			echo $name;

		}

		echo ")
		{\n";

		foreach ($firstline AS $name => $value)
		{

			if (in_array($name, $dates))
			{ // handling for date type variables
				echo "\tthis." . $name . ' = new Date((' . $name . " - " . $vbulletin->options['hourdiff'] . ") * 1000);\n";
			}
			else

			{
				echo "\tthis." . $name . ' = ' . $name . ";\n";
			}

		}

		echo "}\n\n"; // end function

		echo 'var ' . $typename . 's = new Array(' . $vbulletin->db->num_rows($data) . ");\n\n";

		print_js_data($typename, $firstline, 1);

		$counter = 1;
		while ($datarow = $vbulletin->db->fetch_array($data))
		{
			$counter++;

			print_js_data($typename, $datarow, $counter);
		}

		echo "\n\n";

	}
}

// ###################### Start printdata #######################
function print_js_data($typename, $datarow, $number)
{
	echo $typename . 's[' . ($number - 1) . '] = new ' . $typename . '(';

	$firstitem = false;
	foreach ($datarow AS $name => $value)
	{

		if ($firstitem)
		{
			echo ', ';
		}
		$firstitem = true;

		echo "'" . addslashes_js($value) . "'";

	}

	echo ");\n";
}

// ###################### Start makejs_array #######################
function print_js_array($typename, $data, $dates)
{
	global $vbulletin;

	if (is_array($data))
	{
		// make the function

		echo 'function ' . $typename . ' (';

		reset($data);

		$firstline = current($data);
		$firstitem = false;
		foreach ($firstline AS $name => $value)
		{

			if ($firstitem)
			{
				echo ', ';
			}
			$firstitem = trues;

			echo $name;

		}

		echo ")
	{\n";

		reset ($firstline);

		foreach ($firstline AS $name => $value)
		{

			if (in_array($name, $dates))
			{ // handling for date type variables
				echo "\tthis." . $name . ' = new Date((' . $name . " - " . $vbulletin->options['hourdiff'] . ") * 1000);\n";
			}
			else

			{
				echo "\tthis." . $name . ' = ' . $name . ";\n";
			}

		}

		echo "}\n\n"; // end function

		echo 'var ' . $typename . 's = new Array(' . sizeof($data) . ");\n\n";

		print_js_data($typename, $firstline, 1);

		$counter = 1;
		while ($datarow = next($data))
		{
			$counter++;

			print_js_data($typename, $datarow, $counter);

		}
		echo "\n\n";

	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 83432 $
|| #######################################################################
\*=========================================================================*/
