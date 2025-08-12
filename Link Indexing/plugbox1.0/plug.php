<?php

/**
* Plugbox
* Copyright 2004 - 2005 Donnie Adams. All rights reserved.
* Author: Donnie Adams (pluggingit.com)
*
* Terms of Uses:
* You are allowed to distribute (unmodified versions), alter for personal use, 
* and make a profit off of Plugbox. You are NOT allowed to remove or alter 
* any of the copyright and TOS notices in any way, shape, or form. You may 
* distribute UNMODIFIED versions of this script that are directly downloaded
* from http://pluggingit.com. You may NOT use or sell any code snippets
* from this script for any other purposes without prior written consent. This 
* script is provided "AS IS" and by using it you agree to indemnify us from liability 
* of any kind that may arise from its use. We reserves the right to change the 
* above copyright statement at anytime, for any reason, without notice.
*/

error_reporting(E_ALL ^ E_NOTICE);// Production
//error_reporting(E_ALL);// Development
set_magic_quotes_runtime(0);

$pb_paths['root']      = str_replace('\\', '/', dirname(__FILE__)) . '/';
$pb_paths['plug_db']   = $pb_paths['root'] . 'plugs.txt';
$pb_paths['banned_db'] = $pb_paths['root'] . 'banned.txt';
$pb_paths['config_db'] = $pb_paths['root'] . 'config.php';

require_once($pb_paths['config_db']);

switch ((empty($_REQUEST['act']) ? '' : $_REQUEST['act']))
{
	// ------------------------------------
	// Redirect to plugger's web site
	// ------------------------------------

	case 'go':

		if (!empty($_GET['id']) && is_numeric($_GET['id']))
		{
			//-------------------------------------
			// Find plug and update click count
			//-------------------------------------

			if ($plugs = @file($pb_paths['plug_db']))
			{
				$token = '|' . intval($_GET['id']);

				foreach ($plugs as $key => $plug)
				{
					if (strpos($plug, $token) !== false)
					{
						// Array $plug
						// index 0: button URL
						// index 1: web site URL
						// index 2: click total
						// index 3: IP address
						// index 4: UNIX timestamp

						$plug			= explode('|', $plug);
						$plug[2]		= $plug[2] + 1;
						$plugs[$key]	= implode('|', $plug);

						//-------------------------------------
						// Write plugs with updated click count to file.
						//-------------------------------------

						$plugs = implode('', $plugs);

						ignore_user_abort(true);

						if ($fp = @fopen($pb_paths['plug_db'], 'wb'))
						{
							@flock($fp, LOCK_EX);
							$bytes = fwrite($fp, $plugs, strlen($plugs));
							@flock($fp, LOCK_UN);
							fclose($fp);
						}

						ignore_user_abort(false);

						if (strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') === false)
						{
							header('Location: http://' . $plug[1]);
						}
						else
						{
							header('Refresh: 0; http://' . $plug[1]);
						}

						exit();
						break;
					}
				}

				die('<strong>Redirection Failed</strong>: Plug ID not found in database.');
			}

			$filename = basename($pb_paths['plug_db']);
			die("<strong>Redirection Failed</strong>: The file <em>{$filename}</em> doesn't exists or is not readable.");
		}

		die('<strong>Redirection Failed</strong>: Missing or invalid plug ID characters.');
		break;

	// ------------------------------------
	// Add plugs to database
	// ------------------------------------

	case 'add':

		// -------------------------------------
		// Print Plugboard User Errors
		// -------------------------------------

		function pb_print_error($message)
		{
			global $pb_cfg;
			exit(
				str_replace(
					array(
						'{ERROR_MESSAGE}',
						'{BASE_URL}',
						'{WEBSITE_URL}',
						'{IP_ADDRESS}',
					),
					array(
						$message,
						$pb_cfg['base_url'],
						$pb_cfg['site_url'],
						$_SERVER['REMOTE_ADDR']
					),
					$pb_cfg['error_tpl']
				)
			);
		}

		// -------------------------------------
		// Validate a HTTP URL
		// -------------------------------------

		function pb_is_valid_url($url)
		{
			$url_regex = "|^(http:\/\/)?(([a-z0-9\-]+\.)+[a-z]{2,6})([^\s\w]([\w$-.+~!*'()@:?=&/;#])*)?$|i";
			return preg_match($url_regex, $url) ? true : false;
		}

		// -------------------------------------
		// Validate a button URL
		// -------------------------------------

		function pb_is_valid_button($img_url)
		{
			$valid_extentions = array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'php');

			if ( in_array(substr(strrchr($img_url, '.'), 1), $valid_extentions) )
			{
				if (pb_is_valid_url($img_url))
				{
					return true;
				}
			}
			return false;
		}

		// -------------------------------------
		// Format, encode, & remove redundancies 
		// from an URL
		// -------------------------------------

		function format_url($url)
		{
			$url = str_replace(array('http://', 'www.'), '', $url);
			$url = htmlspecialchars(utf8_decode($url), ENT_QUOTES);
			return rtrim($url, '/');
		}

		//-------------------------------------
		// Has the plugger's IP address been banned?
		//-------------------------------------

		if ($ip_bans = @file($pb_paths['banned_db']))
		{
			foreach ($ip_bans as $val)
			{
				if (rtrim($val) == $_SERVER['REMOTE_ADDR'])
				{
					pb_print_error('You have been banned from plugging on this plugboard.');
				}
			}

			unset($ip_bans);
		}

		//-------------------------------------
		// Initiate variables
		//-------------------------------------

		$button_url  = empty($_POST['button_url']) ? '' : trim($_POST['button_url']);
		$website_url = empty($_POST['website_url']) ? '' : trim($_POST['website_url']);

		if (get_magic_quotes_gpc())
		{
			$button_url  = stripslashes($button_url);
			$website_url = stripslashes($website_url);
		}

		$button_url  = format_url($button_url);
		$website_url = format_url($website_url);


		//-------------------------------------
		// Flood control
		//-------------------------------------
		
		if ($plugs = @file($pb_paths['plug_db']))
		{
			$out_of_range = false;
			$now = time();

			foreach ($plugs as $key => $val)
			{

				// Array $val
				// index 0: button URL
				// index 1: web site URL
				// index 2: click total
				// index 3: IP address
				// index 4: UNIX timestamp

				$val = explode('|', rtrim($val));

				if (!$out_of_range)
				{
					if (($now - $val[4]) < $pb_cfg['timeout'])
					{
						// Has a person using the same IP address plugged already?
						if (strpos($plugs[$key], ('|' . $_SERVER['REMOTE_ADDR'])))
						{
							pb_print_error("Sorry, this plugboard only allows a new plug once every {$pb_cfg['timeout']} seconds.");
						}
					}
					else
					{
						$out_of_range = true;
					}
				}

				if ( ! $pb_cfg['allow_milti_post'])
				{
					if ($val[3] == $_SERVER['REMOTE_ADDR'])
					{
						pb_print_error('You can not plug more than once.');
					}
				}

				if (($website_url === $val[1]) || ($button_url === $val[0]))
				{
					pb_print_error('You can not plug the same button or website twice.');
				}
			}
		}

		//-------------------------------------
		// URL validation
		//-------------------------------------

		// Button URL validation
		if ( ! pb_is_valid_button($button_url))
		{
			pb_print_error('You must enter a valid button URL.');
		}

		// Web site URL validation
		if ( ! pb_is_valid_url($website_url))
		{
			pb_print_error('You must enter a valid web website URL.');
		}

		//-------------------------------------
		// Add plug to database
		//-------------------------------------

		$plugs = array_merge(
					array("{$button_url}|{$website_url}|0|{$_SERVER['REMOTE_ADDR']}|".time()."\n"),
					(array)$plugs);

		if (sizeof($plugs) > $pb_cfg['max_plugs'])
		{
			$plugs = array_slice($plugs, 0, $pb_cfg['max_plugs']);
		}

		ignore_user_abort(true);

		if ($fp = @fopen($pb_paths['plug_db'], 'wb'))
		{
			$plugs = implode('', $plugs);

			@flock($fp, LOCK_EX);
			$result = fwrite($fp, $plugs, strlen($plugs));
			@flock($fp, LOCK_UN);
			fclose($fp);
		}

		unset($plugs);

		ignore_user_abort(false);

		if (strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') === false)
		{
			header('Location: ' . $pb_cfg['site_url']);
		}
		else
		{
			header('Refresh: 0; ' . $pb_cfg['site_url']);
		}
		exit();
		break;

	// ------------------------------------
	// Print plugs to web browser
	// ------------------------------------

	default:

		if ($plugs = @file($pb_paths['plug_db']))
		{
			$plug_count = count($plugs);

			if ($pb_cfg['max_plugs'] < $plug_count)
			{
				$plugs = array_slice($plugs, 0, (int)$pb_cfg['max_plugs']);
			}

			if ($plug_count > 1)
			{
				switch (strtoupper($pb_cfg['display_order']))
				{
					case 'RANDOM':
						shuffle($plugs);
						break;

					case 'ASC':
						$plugs =& array_reverse($plugs);
						break;
				}
			}

			foreach ($plugs as $plug)
			{
				@list($button_url, $website_url, $click_count, $ip_address, $timestamp) = explode('|', rtrim($plug));

				$redirect_url = $pb_cfg['base_url'] . 'plug.php?act=go&amp;id=' . $timestamp;
				$plug_date    = @date($pb_cfg['date_format'], $timestamp);

				echo "<a href='{$redirect_url}' target='_blank'><img src='http://{$button_url}' " .
					"border='0' width='{$pb_cfg['button_width']}' height='{$pb_cfg['button_height']}' " .
					"title='Clicks: {$click_count} Date: {$plug_date} URL: {$website_url}' " .
					"alt='' /></a>\n";
			}

			unset($plugs, $pb_cfg);
		}
?>

<form method="post" id="plugform" name="plugform" action="<?php echo $pb_cfg['base_url']; ?>plug.php?act=add">
	<p><input type="text" name="button_url" value="Button" size="20" title="Button URL" 
		onFocus="if(this.value==this.defaultValue)value=''" onBlur="if(this.value=='')value=this.defaultValue;" />
	<input type="text" name="website_url" value="http://" size="20" title="Website URL"  />
	<input type="submit" class="button" name="submit" value="Plug" /></p>
</form>

<?php
}
// End Switch
?>