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

error_reporting(E_ALL & ~E_NOTICE);

//we want this to work from a couple of different locations/urls which makes
//figuring out where some of the other files live tricky.  Especially since
//we can't necesarily assume that bburl is set correctly in the config.
$trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS | DEBUG_BACKTRACE_PROVIDE_OBJECT);
$toplevel = empty($trace);

define('VERSION', '5.1.9');
define('THIS_SCRIPT', 'tools.php');
define('VB_AREA', 'tools');
define('VB_ENTRY', 1);

if (strlen('cfee121141f1f5655d685ea5b531df53') == 32)
{
	/**
	* @ignore
	*/
	define('CUSTOMER_NUMBER', 'cfee121141f1f5655d685ea5b531df53');
}
else
{
	/**
	* @ignore
	*/
	define('CUSTOMER_NUMBER', md5(strtoupper('cfee121141f1f5655d685ea5b531df53')));
}

$core = realpath(dirname(__FILE__) . '/../');
if (file_exists($core . '/includes/init.php'))
{ // need to go up a single directory, we must be in includes / admincp / modcp / install
	chdir($core);
}
else
{
	die('Please place this file within the "core/admincp" or "core/install" folder');
}

require_once( './install/includes/class_upgrade.php');
require_once('./install/init.php');
require_once(DIR . '/includes/functions.php');
require_once(DIR . '/includes/adminfunctions.php');

$vb5_config =& vB::getConfig();
$options = vB::getDatastore()->getValue('options');

if ($toplevel)
{
	$base_url = '..';
}
else
{
	$base_url = '../core';
}

$type = $vbulletin->input->clean_gpc('r', 'type', vB_Cleaner::TYPE_STR);
$customerid = $vbulletin->input->clean_gpc('p', 'customerid', vB_Cleaner::TYPE_STR);
$bbcustomerid = $vbulletin->input->clean_gpc('c', 'bbcustomerid', vB_Cleaner::TYPE_STR);
vB_Upgrade::createAdminSession();

// #############################################################################
if ($_POST['do'] == 'login')
{
	if (md5(strtoupper($vbulletin->GPC['customerid'])) == CUSTOMER_NUMBER)
	{
		setcookie('bbcustomerid', md5(strtoupper($vbulletin->GPC['customerid'])), 0, '/', '');
		$vbulletin->GPC['bbcustomerid'] = CUSTOMER_NUMBER;
		$_REQUEST['do'] = '';
	}
}

// #############################################################################
if ($vbulletin->GPC['bbcustomerid'] !== CUSTOMER_NUMBER)
{
	global $stylevar;

	// set the style folder
	if (empty($options['cpstylefolder']))
	{
		$options['cpstylefolder'] = 'vBulletin_5_Default';
	}
	// set the version
	$options['templateversion'] = VERSION;

	define('NO_PAGE_TITLE', true);
	print_cp_header('Tools');

	?>
	<form action="<?php echo THIS_SCRIPT; ?>?do=login" method="post">
	<input type="hidden" name="do" value="login" />
	<p>&nbsp;</p><p>&nbsp;</p>
	<table class="tborder" cellpadding="0" cellspacing="0" border="0" width="450" align="center"><tr><td>

		<!-- header -->
		<div class="tcat" style="text-align:center"><b>Enter Customer Number</b></div>
		<!-- /header -->

		<!-- logo and version -->
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="login-logo navbody">
		<tr valign="bottom">
			<td><img src="<?php echo $base_url;?>/cpstyles/<?php echo $options['cpstylefolder']; ?>/cp_logo.<?php echo $options['cpstyleimageext']; ?>" alt="" border="0" /></td>
			<td>
				<b><a href="../"><?php echo $options['bbtitle']; ?></a></b><br />
				<?php echo 'vBulletin ' . $options['templateversion'] . ' Tools'; ?><br />
				&nbsp;
			</td>
		</tr>
		</table>
		<!-- /logo and version -->

		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="alt1 logincontrols">
		<col width="50%" style="text-align:right; white-space:nowrap"></col>
		<col></col>
		<col width="50%"></col>
		<!-- login fields -->
		<tr valign="top">
			<td>&nbsp;<br />Customer Number<br />&nbsp;</td>
			<td class="smallfont"><input type="text" style="padding-left:5px; font-weight:bold; width:250px" name="customerid" value="" tabindex="1" /><br />This is the number with which you log in to the vBulletin.com Members&#8217; Area</td>
			<td>&nbsp;</td>
		</tr>
		<!-- /login fields -->
		<!-- submit row -->
		<tr>
			<td colspan="3" align="center">
				<input type="submit" class="button" value="Enter Tools System" accesskey="s" tabindex="3" />
			</td>
		</tr>
		<!-- /submit row -->
		</table>
	</td></tr></table>
	</form>
	<?php

	unset($debug, $GLOBALS['DEVDEBUG']);
	print_cp_footer();
}

#####################################
# phrases for import systems
#####################################
$vbphrase['importing_language'] = 'Importing Language';
$vbphrase['importing_style'] = 'Importing Style';
$vbphrase['importing_admin_help'] = 'Importing Admin Help';
$vbphrase['importing_settings'] = 'Importing Setting';
$vbphrase['please_wait'] = 'Please Wait';
$vbphrase['language'] = 'Language';
$vbphrase['master_language'] = 'Master Language';
$vbphrase['admin_help'] = 'Admin Help';
$vbphrase['style'] = 'Style';
$vbphrase['styles'] = 'Styles';
$vbphrase['settings'] = 'Settings';
$vbphrase['master_style'] = 'MASTER STYLE';
$vbphrase['templates'] = 'Templates';
$vbphrase['css'] = 'CSS';
$vbphrase['stylevars'] = 'Stylevars';
$vbphrase['replacement_variables'] = 'Replacement Variables';
$vbphrase['controls'] = 'Controls';
$vbphrase['rebuild_style_information'] = 'Rebuild Style Information';
$vbphrase['updating_style_information_for_each_style'] = 'Updating style information for each style';
$vbphrase['updating_styles_with_no_parents'] = 'Updating style sets with no parent information';
$vbphrase['updated_x_styles'] = 'Updated %1$s Styles';
$vbphrase['no_styles_needed_updating'] = 'No Styles Needed Updating';
$vbphrase['processing_complete_proceed'] = 'Processing Complete - Proceed';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>vBulletin Tools</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<link rel="stylesheet" href="<?php echo $base_url;?>/cpstyles/vBulletin_5_Default/controlpanel.css" />
	<script type="text/javascript">
		var SESSIONHASH = "";
		var toggleAllCheckboxesInForm = function(ctrl)
		{
			var i, el;
			for (i = 0; i < ctrl.form.elements.length; i++)
			{
				el = ctrl.form.elements[i];
				if (el.type == 'checkbox' && el != ctrl)
				{
					el.checked = ctrl.checked;
				}
			}
		};

	</script>
</head>
<body style="margin:0px">
<div class="acp-content-wrapper">
<h2 class="pagetitle" style="text-align:center">vBulletin 5 Tools</h2>
<!-- END CONTROL PANEL HEADER -->
<?php


// #############################################################################
if (empty($_REQUEST['do']))
{
	function getXmlVersion($file, $read, $regex)
	{
		// Get versions of .xml files for header diagnostics
		if ($fp = @fopen('./install/' . $file, 'rb'))
		{
			$data = fread($fp, $read);
			if (preg_match($regex, $data, $matches))
			{
				$version = $matches[1];
			}
			else
			{
				$version = 'Unknown';
			}
			fclose($fp);
		}
		else
		{
			$version = 'N/A';
		}

		return $version;
	}

	$style_xml = getXmlVersion('vbulletin-style.xml', 256, '#vbversion="(.*?)"#');
	$language_xml = getXmlVersion('vbulletin-language.xml', 256, '#vbversion="(.*?)"#');
	$settings_xml = getXmlVersion('vbulletin-settings.xml', 300, '#<defaultvalue>(.*?)</defaultvalue>#');

	$language = $db->query_read('SELECT title FROM ' . TABLE_PREFIX . 'language WHERE languageid = ' . intval($options['languageid']));
	if ($db->num_rows($language) == 1)
	{
		$language = $db->fetch_array($language);
		$language = $language['title'];
	}
	else
	{
		$language = 'Unknown';
	}

	print_form_header();

	print_table_header('Import XML Files');
	print_column_style_code(array('width:30%'));
	print_label_row(construct_link_code('Style', THIS_SCRIPT . '?do=xml&amp;type=style'), "This will take the latest style from ./install/vbulletin-style.xml<dfn>Version: <b>$style_xml</b></dfn>");
	print_label_row(construct_link_code('Settings', THIS_SCRIPT . '?do=xml&amp;type=settings'), "This will take the latest settings from ./install/vbulletin-settings.xml<dfn>Version: <b>$settings_xml</b></dfn>");
	print_label_row(construct_link_code('Language', THIS_SCRIPT . '?do=xml&amp;type=language'), "This will take the latest language from ./install/vbulletin-language.xml<dfn>Version: <b>$language_xml</b></dfn>");
	print_label_row(construct_link_code('Admin Help', THIS_SCRIPT . '?do=xml&amp;type=adminhelp'), 'This will take the latest admin help from ./install/vbulletin-adminhelp.xml');
	print_table_break();

	print_table_header('Datastore Cache');
	print_column_style_code(array('width:30%'));
	print_label_row(construct_link_code('Usergroup / Channel Cache', THIS_SCRIPT . '?do=cache&amp;type=channel'), 'Update the channel and usergroup cache');
	print_label_row(construct_link_code('Options Cache', THIS_SCRIPT . '?do=cache&amp;type=options'), 'Update the options cache from the setting table');
	print_label_row(construct_link_code('Bitfield Cache', THIS_SCRIPT . '?do=bitfields'), 'Update the bitfields cache from the xml/bitfields_<em>???</em>.xml files');
	print_label_row(construct_link_code('Password Schemes', THIS_SCRIPT . '?do=pwschemes'), 'Update the password schemes from the xml/pwschemes_<em>???</em>.xml files');
	print_table_break();

	print_table_header('Cookies');
	print_column_style_code(array('width:30%'));
	$options['cookiedomain'] = $options['cookiedomain'] == '' ? ' ( blank ) ' : '<b>' . htmlspecialchars_uni($options['cookiedomain']) . '</b>';
	$options['cookiepath'] = $options['cookiepath'] == '' ? ' ( blank ) ' : '<b>' . htmlspecialchars_uni($options['cookiepath']) . '</b>';
	print_label_row('Cookie Prefix', '<b>' . htmlspecialchars_uni(COOKIE_PREFIX) . '</b> (<em>set in includes/config.php</em>)');
	print_label_row(construct_link_code('Reset Cookie Domain', THIS_SCRIPT . '?do=cookie&amp;type=domain'), 'Reset the cookie domain to be blank<dfn>Currently: ' . $options['cookiedomain'] . '</dfn>');
	print_label_row(construct_link_code('Reset Cookie Path', THIS_SCRIPT . '?do=cookie&amp;type=path'), 'Reset the cookie path to be <b>/</b><dfn>Currently: ' . $options['cookiepath'] . '</dfn>');
	print_table_break();

	print_table_header('MySQL');
	print_column_style_code(array('width:30%'));
	print_label_row(construct_link_code('Run Query', THIS_SCRIPT . '?do=mysql&amp;type=query'), 'This allows you to run alter and update queries on the database');
	print_label_row(construct_link_code('Repair Tables', THIS_SCRIPT . '?do=mysql&amp;type=repair'), 'You can select tables that need repaired here');
	print_label_row(construct_link_code('Reset Admin Access', THIS_SCRIPT . '?do=user&amp;type=access'), 'Reset admin access for a user');
	print_label_row(construct_link_code('Scan Content Tables', THIS_SCRIPT . '?do=scancontent'), 'Scan & Repair node, closure, and content tables. You should only do this if vBulletin Technical Support Staff advises you to do so, and make sure you have a back up first. ');
	print_label_row(construct_link_code('Restore Pages', THIS_SCRIPT . '?do=pages'), 'This allows you to revert pages to their default configuration. This modifies the route, page, pagetemplate, and widgets for the page. You should only use this if vBulletin Technical Support Staff advises you to do so, and after backing up your database first.');
	print_table_break();

	$randnumb = vbrand(0, 100000000);
	print_table_header('Other Tools');
	print_column_style_code(array('width:30%'));
	print_label_row(construct_link_code($options['bbactive'] ? 'Turn Off Forum' : 'Turn On Forum', THIS_SCRIPT . '?do=bbactive'), 'Your forum is <b>' . ($options['bbactive'] ? 'On' : 'Off') . '</b>');
	print_label_row(construct_link_code('Default Language', THIS_SCRIPT . '?do=language'), 'Reset board default language.<dfn>Currently: <b>' . htmlspecialchars($language) . '</b></dfn>');
	print_label_row(construct_link_code('Location of Core Directory', THIS_SCRIPT . '?do=bburl'), 'Change location of core directory. (This is the vboptions[bburl] setting) <br /><dfn>Currently: <b>' . htmlspecialchars($options['bburl']) . '</b></dfn>');
	print_table_break();

	print_table_header('Time');
	print_column_style_code(array('width:30%'));
	print_label_row('System Time', $systemdate = date('r T'));
	print_label_row('Your Time', $userdate = vbdate('r T'));
	print_table_footer();
}
// #############################################################################
else if ($_REQUEST['do'] == 'xml')
{
	switch ($vbulletin->GPC['type'])
	{
		case 'style':
			require_once('./includes/adminfunctions_template.php');

			if (!($xml = file_read('./install/vbulletin-style.xml')))
			{
				echo '<p>Uh oh, ./install/vbulletin-style.xml doesn\'t appear to exist! Upload it and refresh the page.</p>';
				print_cp_footer();
			}

			echo '<p>Importing vbulletin-style.xml</p>';

			$startat = $vbulletin->input->clean_gpc('r', 'startat', vB_Cleaner::TYPE_UINT);

			$vbphrase['go_back'] = 'Go Back';
			$vbphrase['template_group_x'] = 'Template Group: %1$s';

			$perpage = 10;
			$imported = xml_import_style($xml, -1, -1, '', false, 1, false, $startat, $perpage);
			if (!$imported['done'])
			{
				//build the next page url;
				$startat = $startat + $perpage;
				print_cp_redirect2('tools', array('do' => 'xml', 'type' => 'style', 'startat' => $startat));
			}
			// define those phrases that are used for the import
			$vbphrase['style'] = 'Style';
			$vbphrase['please_wait'] = 'Please Wait';

			build_all_styles(0, 1);
			print_cp_redirect2('tools', array('do' => 'templatemerge'));
		break;
		case 'settings':
			require_once('./includes/adminfunctions_options.php');

			if (!($xml = file_read('./install/vbulletin-settings.xml')))
			{
				echo '<p>Uh oh, ./install/vbulletin-settings.xml doesn\'t appear to exist! Upload it and refresh the page.</p>';
				print_cp_footer();
			}

			echo '<p>Importing vbulletin-settings.xml';
			xml_import_settings($xml);
			echo '<br /><span class="smallfont"><b>Okay</b></span></p>';
		break;
		case 'language':
			require_once('./includes/adminfunctions_language.php');

			if (!($xml = file_read('./install/vbulletin-language.xml')))
			{
				echo '<p>Uh oh, ./install/vbulletin-language.xml doesn\'t appear to exist! Upload it and refresh the page.</p>';
				print_cp_footer();
			}

			echo '<p>Importing vbulletin-language.xml';
			xml_import_language($xml);
			build_language();
			echo '<br /><span class="smallfont"><b>Okay</b></span></p>';
		break;
		case 'adminhelp':
			require_once('./includes/adminfunctions_help.php');

			if (!($xml = file_read('./install/vbulletin-adminhelp.xml')))
			{
				echo '<p>Uh oh, ./install/vbulletin-adminhelp.xml doesn\'t appear to exist! Upload it and refresh the page.</p>';
				print_cp_footer();
			}

			echo '<p>Importing vbulletin-adminhelp.xml';
			xml_import_help_topics($xml);
			echo "<br /><span class=\"smallfont\"><b>Okay</b></span></p>";
		break;
	}
	define('SCRIPT_REDIRECT', true);
}
// #############################################################################
else if ($_REQUEST['do'] == 'templatemerge') // after importing style
{
	$vbulletin->input->clean_array_gpc('r', array(
		'startat' => vB_Cleaner::TYPE_UINT,
	));

	require_once(DIR . '/includes/class_template_merge.php');

	$merge_data = new vB_Template_Merge_Data($vbulletin);
	$merge_data->start_offset = $vbulletin->GPC['startat'];
	$merge_data->add_condition("tnewmaster.product IN ('', 'vbulletin')");

	$merge = new vB_Template_Merge($vbulletin);
	$merge->time_limit = 5;
	$completed = $merge->merge_templates($merge_data, $output);

	if ($completed)
	{
		// completed
		$vbphrase['style'] = 'Style';
		$vbphrase['please_wait'] = 'Please Wait';

		build_all_styles();

		define('SCRIPT_REDIRECT', true);
	}
	else
	{
		// more templates to merge
		print_cp_redirect2('tools', array('do' => 'templatemerge', 'startat' => ($merge_data->start_offset + $merge->fetch_processed_count())));
	}
}
// #############################################################################
else if ($_REQUEST['do'] == 'cache')
{
	switch ($vbulletin->GPC['type'])
	{
		case 'channel':
			build_channel_permissions();
			define('SCRIPT_REDIRECT', true);
		break;
		case 'options':
			vB::getDatastore()->build_options();
			define('SCRIPT_REDIRECT', true);
		break;
	}
}
// #############################################################################
else if ($_REQUEST['do'] == 'cookie')
{
	switch ($vbulletin->GPC['type'])
	{
		case 'domain':
			$db->query_write("
				UPDATE " . TABLE_PREFIX . "setting
				SET value = ''
				WHERE varname = 'cookiedomain'
			");
			vB::getDatastore()->build_options();
			define('SCRIPT_REDIRECT', true);
		break;
		case 'path':
			$db->query_write("
				UPDATE " . TABLE_PREFIX . "setting
				SET value = '/'
				WHERE varname = 'cookiepath'
			");
			vB::getDatastore()->build_options();
			define('SCRIPT_REDIRECT', true);
		break;
	}
}
// #############################################################################
else if ($_REQUEST['do'] == 'bitfields')
{
	require_once(DIR . '/includes/class_bitfield_builder.php');
	vB_Bitfield_Builder::save($db);
	build_channel_permissions();
	define('SCRIPT_REDIRECT', true);
}
// #############################################################################
else if ($_REQUEST['do'] == 'pwschemes')
{
	try
	{
		vB_Library::instance('login')->importPasswordSchemes();
		define('SCRIPT_REDIRECT', true);
	}
	catch(vB_Exception_Api $e)
	{
		$errors = $e->get_errors();
		print_stop_message2($errors[0]);
	}
}
// #############################################################################
else if ($_REQUEST['do'] == 'mysql')
{
	$vbulletin->input->clean_array_gpc('p', array('query' => vB_Cleaner::TYPE_STR, 'tables' => vB_Cleaner::TYPE_ARRAY));

	switch ($vbulletin->GPC['type'])
	{
		case 'query':
			if (empty($vbulletin->GPC['query']) OR !preg_match('#^(Alter|Update)#si', $vbulletin->GPC['query']))
			{
				print_form_header('tools', 'mysql');
				construct_hidden_code('type', 'query');
				print_table_header('Please paste alter / update query below');
				print_textarea_row('Query to run', 'query','', 6, 60, 0, 0);
				print_submit_row('Run', '');
			}
			else
			{
				$db->query_write($vbulletin->GPC['query']);
				define('SCRIPT_REDIRECT', true);
			}
			break;
		case 'repair':
			if (empty($vbulletin->GPC['tables']))
			{
				print_form_header('tools', 'mysql');
				construct_hidden_code('type', 'repair');
				print_table_header('Please select tables to repair');
				print_label_row('Table', "<label><input type=\"checkbox\" name=\"allbox\" title=\"Check All\" onclick=\"toggleAllCheckboxesInForm(this);\" />Check All</label>", 'thead');
				$result = $db->query_write("SHOW TABLE STATUS");
				while ($currow = $db->fetch_array($result, vB_Database::DBARRAY_NUM))
				{
					if (!in_array(strtolower($currow[1]), array('heap', 'memory')))
					{
						print_checkbox_row($currow[0], "tables[$currow[0]]", 0);
					}
				}
				print_submit_row('Repair', '');
			}
			else
			{
				echo '<ul>';
				foreach($vbulletin->GPC['tables'] AS $key => $val)
				{
					if ($val == 1)
					{
						echo "<li>Repairing <b>$key</b>... ";
						flush();
						$db->query_write("REPAIR TABLE $key");
						echo "Repair Complete</li>\n";
					}
				}
				echo '</ul>';
				echo "<p>Overall Repair complete</p><br />";
				define('SCRIPT_REDIRECT', true);
			}
		break;
	}
}
// #############################################################################
else if ($_REQUEST['do'] == 'user')
{
	$vbulletin->input->clean_array_gpc('p', array('user' => vB_Cleaner::TYPE_STR));

	switch ($vbulletin->GPC['type'])
	{
		case 'access':
		if (empty($vbulletin->GPC['user']))
		{
			print_form_header('tools', 'user');
			construct_hidden_code('type', 'access');
			print_table_header('Enter username to restore access to');
			print_input_row('User Name', 'user', '');
			print_submit_row('Submit', '');
		}
		else
		{
			$userid = $db->query_first("SELECT userid, usergroupid FROM " . TABLE_PREFIX . "user WHERE username = '" . $db->escape_string(htmlspecialchars_uni($vbulletin->GPC['user'])) . "'");
			if (empty($userid['userid']))
			{
				echo '<p align="center">Invalid username</p>';
			}
			else
			{
				// let's check that usergroupid 6 is still admin
				$bf_ugp_adminpermissions = vB::getDatastore()->get_value('bf_ugp_adminpermissions');
				$ugroup = $db->query_first("SELECT * FROM " . TABLE_PREFIX . "usergroup WHERE usergroupid = 6 AND (adminpermissions & " . $bf_ugp_adminpermissions['cancontrolpanel'] . ")");
				if (empty($ugroup['usergroupid']))
				{ // lets give them admin permissions again
					$db->query_write("UPDATE " . TABLE_PREFIX . "usergroup SET adminpermissions = 3 WHERE usergroupid = 6");
					build_channel_permissions();
				}
				/*insert query*/
				$db->query_write("REPLACE INTO " . TABLE_PREFIX . "administrator
					(userid, adminpermissions)
				VALUES
					($userid[userid], " . (array_sum($bf_ugp_adminpermissions) - 3) . ")
				");
				$db->query_write("UPDATE " . TABLE_PREFIX . "user SET usergroupid = 6 WHERE userid = $userid[userid]");
				define('SCRIPT_REDIRECT', true);

				vB_Cache::instance(vB_Cache::CACHE_FAST)->event('perms_changed');
				vB_Cache::instance(vB_Cache::CACHE_FAST)->event('userChg_' . $userid['userid']);
				vB_Cache::instance(vB_Cache::CACHE_LARGE)->event('userChg_' . $userid['userid']);
				vB::getUserContext()->rebuildGroupAccess();
			}
		}
		break;
	}
}
// #############################################################################
else if ($_REQUEST['do'] == 'bbactive')
{
	$db->query_write("
		UPDATE " . TABLE_PREFIX . "setting
		SET value = " . ($options['bbactive'] ? 0 : 1) . "
		WHERE varname = 'bbactive'
	");
	vB::getDatastore()->build_options();
	define('SCRIPT_REDIRECT', true);
}
// #############################################################################
else if ($_REQUEST['do'] == 'language')
{
	$vbulletin->input->clean_array_gpc('p', array('languageid' => vB_Cleaner::TYPE_UINT));

	require_once(DIR . '/includes/adminfunctions_language.php');

	$languages = $db->query_read('SELECT * FROM ' . TABLE_PREFIX . 'language');
	if ($db->num_rows($languages) == 0)
	{
		// this is just taken from install.php
		$db->query_write("INSERT INTO " . TABLE_PREFIX . "language (title, languagecode, charset, decimalsep, thousandsep) VALUES ('English (US)', 'en', 'ISO-8859-1', '.', ',')");
		$_languageid = $db->insert_id();

		$db->query_write("
			UPDATE " . TABLE_PREFIX . "setting
			SET value = " . $_languageid . "
			WHERE varname = 'languageid'
		");

		$db->query_write("
			UPDATE " . TABLE_PREFIX . "user
			SET languageid = 0
		");
		vB::getDatastore()->build_options();
		build_language($_languageid);
		build_language_datastore();
		define('SCRIPT_REDIRECT', true);
	}
	else
	{
		$sellanguages = array();
		while ($language = $db->fetch_array($languages))
		{
			$sellanguages[$language['languageid']] = $language['title'];
		}

		$languageids = implode(',', array_keys($sellanguages));

		$db->query_write("
			UPDATE " . TABLE_PREFIX . "user
			SET languageid = 0
			WHERE languageid NOT IN ($languageids)
		");

		if (empty($vbulletin->GPC['languageid']))
		{
			print_form_header('tools', 'language');
			print_table_header('Select the new default language');
			print_select_row('Language', 'languageid', $sellanguages, $options['languageid']);
			print_submit_row('Submit', '');
		}
		else
		{
			vB_Api::instanceInternal('language')->setDefault($vbulletin->GPC['languageid']);
			//$db->query_write("
			//	UPDATE " . TABLE_PREFIX . "setting
			//	SET value = " . $vbulletin->GPC['languageid'] . "
			//	WHERE varname = 'languageid'
			//");
			//vB::getDatastore()->build_options();
			//build_language($vbulletin->GPC['languageid']);
			//build_language_datastore();
			define('SCRIPT_REDIRECT', true);
		}
	}
}
// #############################################################################
else if ($_REQUEST['do'] == 'bburl')
{
	$vbulletin->input->clean_array_gpc('p', array('bburl' => vB_Cleaner::TYPE_STR));

	if (empty($vbulletin->GPC['bburl']))
	{
		print_form_header('tools', 'bburl');
		print_table_header('Enter the new core directory location (bburl)');
		print_input_row('Core directory location (bburl).<br />Note: do not add a trailing slash. (\'/\')', 'bburl', htmlspecialchars($options['bburl']));
		print_submit_row('Submit', '');
	}
	else
	{
		$db->query_write("
			UPDATE " . TABLE_PREFIX . "setting
			SET value = '" . $db->escape_string($vbulletin->GPC['bburl']) . "'
			WHERE varname = 'bburl'
		");
		vB::getDatastore()->build_options();
		define('SCRIPT_REDIRECT', true);
	}
}
// #############################################################################
else if ($_REQUEST['do'] == 'scancontent')
{
	if (!isset($_REQUEST['perpage']))
	{
		print_form_header('tools', 'scancontent');
		print_table_header('Don\'t do this without a good reason, and don\'t do it without a backup.' );
		print_input_row('Enter the number of records to process in a step. (\'/\')', 'perpage', 50000);
		print_submit_row('Submit', '');
	}
	else
	{
		//First get the parameters.
		$params = vB::getCleaner()->cleanArray($_REQUEST, array('perpage' => vB_Cleaner::TYPE_UINT,
			'startat' => vB_Cleaner::TYPE_UINT, 'step' => vB_Cleaner::TYPE_UINT ));

		if (empty($params['perpage']) OR !is_numeric($params['perpage']))
		{
			$params['perpage'] = 50000;
		}

		if (empty($params['startat']) OR !is_numeric($params['startat']))
		{
			$params['startat'] = 1;
		}

		if (!isset($params['step']))
		{
			$params['step'] = 0;
		}
		//First step is content-type by contenttype.  So we need to get the types available.
		//Last step is scannning the closure table.

		$assertor = vB::getDbAssertor();
		$contentTypes = $assertor->getRows('vBAdmincp:getContentTypes', array());
		$maxId = $assertor->getRow('vBAdmincp:getMaxId', array());
		$maxId = $maxId['maxid'];

		if ($params['step'] == count($contentTypes))
		{
			echo sprintf('Scanning %s nodes %u through %u for invalid records.', 'closure', $params['startat'],
				$params['startat'] + $params['perpage'])."<br />\n";

			//scanning closure table
			$missing1 = $assertor->getColumn('vBAdmincp:getMissingClosureParents', 'nodeid', array('start' => $params['startat'],
				'end' => $params['startat'] + $params['perpage']));
			$missing2 = $assertor->getColumn('vBAdmincp:getMissingClosureSelf', 'nodeid', array('start' => $params['startat'],
				'end' => $params['startat'] + $params['perpage']));

			if (!empty($missing1) OR !empty($missing2))
			{
				$missing = array_merge($missing1, $missing2);
				$assertor->assertQuery('vBAdmincp:insertMissingClosureSelf', array('nodeid' => $missing));
				$assertor->assertQuery('vBAdmincp:insertMissingClosureParent', array('nodeid' => $missing));
			}
			$params['startat'] += $params['perpage'];
		}
		else if ($params['step'] > count($contentTypes))
		{
			print_stop_message2('completed_content_table_scan', 'tools');
		}
		else
		{
			//We can skip ahead to the next record of this content type, and save some steps
			$contenttypeid = $contentTypes[$params['step']]['contenttypeid'];
			$contentLib = vB_Library_Content::getContentLib($contenttypeid);
			while ($contentLib->getCannotDelete())
			{
				$params['step']++;

				if ($params['step'] >= count($contentTypes))
				{
					break;
				}
				$contenttypeid = $contentTypes[$params['step']]['contenttypeid'];
				$contentLib = vB_Library_Content::getContentLib($contenttypeid);
				$params['startat'] = 1;
			}

			if ($params['step'] < count($contentTypes))
			{
				$nextIdQry = $assertor->assertQuery('vBAdmincp:getNextNode', array('start' => $params['startat'],
					'contenttypeid' => $contenttypeid));

				if ($nextIdQry->valid() AND ($nextId = $nextIdQry->current()) AND (!empty($nextId['nextid'])))
				{
					$nextId = $nextId['nextid'];

					echo sprintf('Scanning %s nodes %u through %u for invalid records.',
							vB_Types::instance()->getContentTypeClass($contenttypeid),
							$nextId, $nextId + $params['perpage'])."<br />\n";
					vbflush();

					$damaged = $assertor->assertQuery('vBAdmincp:getDamagedNodes', array('start' => $nextId,
						'end' => $nextId + $params['perpage'],'contenttypeid' => $contenttypeid));

					//If we found anything, time to fix it.
					if ($damaged->valid())
					{
						$contentLib->setDoIncompleteNodeCleanup(true);
						foreach($damaged AS $node)
						{
							if (!empty($node['nodeid']))
							{
								echo sprintf('Found defective node %u- fixing now.', $node['nodeid'])."<br />\n";
								vbflush();
								//The next line forces a call to checkContent and incompleteNodeCleanup
								// because doIncompleteNodeCleanup has been turned on
								$node = $contentLib->getFullContent($node['nodeid']);
							}
						}
						$contentLib->setDoIncompleteNodeCleanup(false);
					}
					$params['startat'] = $nextId + $params['perpage'];
				}
				else
				{
					//we're done with this type
					$params['startat'] = $maxId + 1;
				}
			}
		}

		if ($params['startat'] > $maxId)
		{
			$params['step']++;
			$params['startat'] = 1;

			if ($params['step'] > count($contentTypes))
			{
				print_stop_message2('completed_content_table_scan', 'tools');
			}
		}
		$params['do'] = 'scancontent';
		print_cp_redirect2('tools', $params);
	}
}
// #############################################################################
else if ($_REQUEST['do'] == 'pages')
{
	$vbulletin->input->clean_array_gpc('p', array(
		'action'   => vB_Cleaner::TYPE_STR,
		'pageguid' => vB_Cleaner::TYPE_ARRAY_STR,
	));

	$xmldir = str_replace('\\', '/', DIR) . (empty($vb5_config['Misc']['debug']) ? '/includes/xml' : '/install');

	$pageRestore = new vB_Utility_PageRestore($xmldir);

	if ($vbulletin->GPC['action'] == 'confirm' AND !empty($vbulletin->GPC['pageguid']))
	{
		?>
		<style>
		.alt1 {
			border-bottom: 1px solid #AAA;
		}
		</style>
		<?php
		$cols = 5;
		print_form_header('tools', 'pages');
		construct_hidden_code('action', 'revert');
		print_table_header('Revert pages to their default settings', $cols);
		print_description_row('<br />The following page(s) will be reverted. <b>This cannot be undone. Ensure you have a database backup before proceeding.</b><br /><br />', false, $cols);
		print_cells_row(array('', 'Title', 'URL', 'PageTemplate', 'Page GUID'), true);

		$xmlpages = $pageRestore->getPagesFromXml();

		foreach ($vbulletin->GPC['pageguid'] AS $pageguid)
		{
			$xmlpage = $xmlpages[$pageguid];
			$xmlroute = $pageRestore->getXmlRouteByPageGuid($xmlpage['guid']);
			$xmlpagetemplate = $pageRestore->getXmlPageTemplateByPageGuid($xmlpage['guid']);
			$dbpage = $pageRestore->getMatchingPageFromDbByXmlGuid($xmlpage['guid']);
			$dbroute = $pageRestore->getDbRouteByRouteId($dbpage['routeid']);
			$dbpagetemplate = $pageRestore->getDbPageTemplateByPageTemplateId($dbpage['pagetemplateid']);

			print_cells_row(array(
				'<b>Current:</b>',
				$pageRestore->getPageTitleByGuid($dbpage['guid']),
				'<code>&lt;site&gt;/' . $dbroute['prefix'] . '</code>',
				$dbpagetemplate['title'],
				$dbpage['guid'],
			));

			print_cells_row(array(
				'<b>Default:</b>',
				$xmlpage['title'],
				'<code>&lt;site&gt;/' . $xmlroute['prefix'] . '</code>',
				$xmlpagetemplate['title'],
				$xmlpage['guid'],
			), false);

			// can't use construct_hidden_code(), since the name will overwrite
			// a previous element with the same name
			echo "<input type=\"hidden\" name=\"pageguid[]\" value=\"$pageguid\" />\n";
		}

		print_submit_row('Permanently Revert These Pages', '', $cols, 'Cancel');

	}
	else if ($vbulletin->GPC['action'] == 'revert' AND !empty($vbulletin->GPC['pageguid']))
	{
		set_time_limit(60 * 3);
		echo '<div><b>Restoring pages...</b></div>';
		echo '<ul>';
		foreach ($vbulletin->GPC['pageguid'] AS $pageguid)
		{
			echo '<li>';
			$pageRestore->restorePage($pageguid, true);
			echo '</li>';
			vbflush();
		}
		echo '</ul>';
		echo '<div><b>Done.</b></div>';
		define('SCRIPT_REDIRECT', true);

	}
	else // select pages to revert
	{
		$cols = 5;
		print_form_header('tools', 'pages');
		construct_hidden_code('action', 'confirm');
		print_table_header('Revert pages to their default settings', $cols);
		print_description_row('Select one or more pages to revert to default vBulletin settings. <br /><br /><div class="warning"><b>WARNING</b>: This will this will completely revert the selected pages, including page, route, page template, and module information. Remember that the page template may be in use by another page. This will remove all customizations for the page(s) and cannot be reversed. <b>Please ensure you have a database backup before proceeding</b>.</div><br />Pages will be restored to their default configuration from the XML files. Reading files from: <code>' . htmlspecialchars($xmldir) . '</code><br /><ul><li>' . implode('</li><li>', $pageRestore->getFileVersions()) . '</li></ul>', false, $cols);
		print_cells_row(array('Title', 'URL', 'PageTemplate', 'Page GUID', '<label><input type="checkbox" name="selectall" id="selectall" /> Select All</label>'), true);

		$xmlpages = $pageRestore->getPagesFromXml();
		foreach ($xmlpages AS $guid => $xmlpage)
		{
			$xmlroute = $pageRestore->getXmlRouteByPageGuid($xmlpage['guid']);
			$xmlpagetemplate = $pageRestore->getXmlPageTemplateByPageGuid($xmlpage['guid']);

			print_cells_row(array(
				$xmlpage['title'],
				'<code>&lt;site&gt;/' . $xmlroute['prefix'] . '</code>',
				$xmlpagetemplate['title'],
				$xmlpage['guid'],
				'<label><input type="checkbox" class="guidcheckbox" name="pageguid[]" value="' . $xmlpage['guid'] . '" /> Revert</label>',
			));
		}

		print_submit_row('Revert Selected Pages', '', $cols);

		?>
		<script>
		(function()
		{
			var selall = document.getElementById('selectall');
			selall.onchange = function()
			{
				var i, boxes = document.querySelectorAll('.guidcheckbox');
				for (i in boxes)
				{
					boxes[i].checked = this.checked;
				}
			};

		})();
		</script>
		<?php

	}

}

if (defined('SCRIPT_REDIRECT'))
{
	$vbphrase['redirecting'] = empty($vbphrase['redirecting']) ? 'Redirecting' : $vbphrase['redirecting'];
	echo '<p align="center" class="smallfont"><a href="tools.php" onclick="javascript:clearTimeout(timerID);">' . $vbphrase['processing_complete_proceed'] . '</a></p>';
	echo '<p align="center">' . $vbphrase['redirecting'] . '... (<b id="countdown"></b>) <a href="#" onclick="javascript:clearTimeout(timerID);return false;">Stop Redirect</a></p>';
	echo "\n<script type=\"text/javascript\">\n";
	echo "myvar = \"\"; timeout = " . (5) . ";
	function exec_refresh()
	{
		document.getElementById('countdown').innerHTML = timeout;
		window.status=\"" . $vbphrase['redirecting'] . "\"+myvar; myvar = myvar + \" .\";
		timerID = setTimeout(\"exec_refresh();\", 1000);
		if (timeout > 0)
		{ timeout -= 1; }
		else { clearTimeout(timerID); window.status=\"\"; window.location=\"tools.php\"; }
	}
	exec_refresh();";
	echo "\n</script>\n";
}

?>
<!-- START CONTROL PANEL FOOTER -->
<p align="center" class="smallfont"><a href="http://www.vbulletin.com/" target="_blank" class="copyright">vBulletin <?php echo VERSION; ?>, Copyright &copy;2000-<?php echo date('Y'); ?>, vBulletin Solutions Inc.</a></p>

</div> <!-- acp-content-wrapper -->
</body>
</html>
<?php

/*======================================================================*\
|| ####################################################################
|| # Downloaded: 15:45, Tue Sep 8th 2015
|| # CVS: $RCSfile$ - $Revision: 84968 $
|| ####################################################################
\*======================================================================*/
