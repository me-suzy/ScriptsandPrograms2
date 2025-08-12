<?php
/*
Copyright 2005 VUBB
*/
// Connect  to the database
function database_connect($db_host, $db_user, $db_pass, $db)
{
	@mysql_pconnect($db_host, $db_user, $db_pass);
	@mysql_select_db($db);
}

// Menu
function menu()
{
	global $lang,$menu,$user_info;
	
	// check to see if we need to show them the logged in menu
	if (isset($_SESSION['user']) && isset($_SESSION['user']))
	{
		$menu = $lang['text']['welcome'] . " " . $user_info['user'] . " " . "- <a href='index.php?act=usercp' class='tlinks'>" . $lang['text']['usercp'] . "</a> - <a href='index.php?logout=yes' class='tlinks'>" . $lang['text']['logout'] . "</a>";
	}
	
	// check if they are admin to show admin link
	if (isset($_SESSION['user']) && isset($_SESSION['user']) && $user_info['group'] == '4')
	{
		$menu .= " - <a href='admin.php' class='tlinks'>" . $lang['text']['admin'] . "</a>";
	}
		
	// check if there not logged in so we can show them the guest menu
	if (!isset($_SESSION['user']) && !isset($_SESSION['user']))
	{
		$menu = $lang['text']['not_logged'] . " " .  "- <a href='index.php?act=login' class='tlinks'>" . $lang['text']['login'] . "</a> - <a href='index.php?act=register' class='tlinks'>" . $lang['mix']['register'] . "</a>";
	}
}

// Pagination
// $table - what table to use
// $where - any WHERE statements to add
// $order_by - any ORDER BY statements to add
// $limit - amount per page
// $page - page its on
// $and - any &something to use in $page
function pagination_start($table, $where, $order_by, $limit, $page, $and="")
{
	// make it global so we can use it outside of function
	global $pagination_query, $pagination_link;
	
	$pg = $_GET['pg'];
	
	if(!ctype_digit($pg)) 
	{
		$pg = '1';
	}
	
	$start = ($pg - 1) * $limit;
	
	$pagination_query = mysql_query("SELECT * FROM `" . $table . "` " . $where . " " . $order_by . " LIMIT " . $start . ", " . $limit);

	if(floor(mysql_num_rows($pagination_query)/$limit>0))
	{
		$pages = ceil(mysql_num_rows(mysql_query("SELECT * FROM `" . $table . "` " . $where . " " . $order_by . ""))/$limit);
		$prev_page = $pg - '1';
		$next_page = $pg + '1';
		
		// if the current page is not equal to 1 then show a pevious page link
        if($pg != '1')
        {
            $pagination_link .= "<a href='" . $page . "&pg=" . $prev_page . $and ."'>&lt;&lt;</a> ";
        }
        
        for($index=1; $index<=$pages; $index++)
        {
            // if page is different to the index (page 1) then display a link to main index (page 1)
            if($index != $pg)
            {
                $pagination_link .= "<a href='" . $page . "&pg=" . $index . $and . "'>" . $index . "</a> ";
            }
            
            // else page is index then just show index (page 1)
            else
            {
                $pagination_link .= $index . " ";
            }
        }
        
        // if current page is not equal to number of pages display next page link
        if($pg != $pages)
        {
            $pagination_link .= "<a href='" . $page . "&pg=" . $next_page . $and ."'>&gt;&gt;</a>";
        }
        
        // else display nothing
        else
        {
            $pagination_link .= "";
        }
    } 
}

// Grab smilies
function display_clickable_smilies_bbcode()
{
	global $smilies, $bbcode, $lang;
	
	$grab_smilies = mysql_query("SELECT * FROM `smilies` ORDER BY `id`");
	while ($echo_smilies = mysql_fetch_array($grab_smilies))
	{
		$echo_smilies['code'] = htmlspecialchars($echo_smilies['code']);
		$echo_smilies['code'] = stripslashes($echo_smilies['code']);
		$smilies .= '<a href="javascript:smile(\'';
		// rather than adding slashes to all things, just to single quotes.
		$smilies .= str_replace('\'', '\\\'', $echo_smilies['code']);
		$smilies .= '\')">';
		$smilies .= '<img src="';
		$smilies .= $site_config['site_url'];
		$smilies .= $echo_smilies['image'];
		$smilies .= '" border="0" alt="smilie">';
		$smilies .= '</a>'; 
	}
	
	$bbcode = "
	<a href=javascript:smile(\"[b][/b]\")>" . $lang['text']['bold'] . "</a>
	<a href=javascript:smile(\"[u][/u]\")>" . $lang['text']['underline'] . "</a>
	<a href=javascript:smile(\"[i][/i]\")>" . $lang['text']['italic'] . "</a>
	<a href=javascript:smile(\"[img][/img]\")>" . $lang['text']['image'] . "</a>
	<a href=javascript:smile(\"[quote][/quote]\")>" . $lang['text']['quote'] . "</a>
	<a href=javascript:smile(\"[code][/code]\")>" . $lang['text']['code'] . "</a>
	";
}

// Register
function register($user, $email, $vemail, $pass, $vpass)
{
	global $site_config, $fadyd, $lang;
	
	if (isset($_GET['action']) && $_GET['action'] == "register") 
	{
		// Check for duplicates
		$dupe_user = mysql_num_rows(mysql_query("SELECT * FROM `members` WHERE `user` = '".$user."'"));
		$dupe_email = mysql_num_rows(mysql_query("SELECT * FROM `members` WHERE `email` = '".$email."'"));
		
		// count username
		$count_user = strlen($user);
	
		if (empty($user) || empty($email) || empty($vemail) || empty($pass) || empty($vpass) || !isset($_POST['agree'])) 
		{ 
			message($lang['title']['missing'],$lang['text']['fill_all_fields']);
		} 
		
		else if ($count_user > '32')
		{
			message($lang['title']['error'], $lang['text']['user_too_long']);
		}
		
		else if ($dupe_user > '0') 
		{
			message($lang['title']['error'], $lang['text']['user_taken']);
		}
		
		else if ($dupe_email > '0') 
		{
			message($lang['title']['error'], $lang['text']['email_taken']);
		}
		
		else if ($email != $vemail) 
		{
			message($lang['title']['error'], $lang['text']['email_not_match']);
		}
		
		else if ($pass != $vpass) 
		{
			message($lang['title']['error'], $lang['text']['pass_not_match']);
		}
		
		else
		{
			// Prevents sql injection.
			$user = strip_tags($user);
			$user = addslashes($user);
			$user = htmlspecialchars($user, ENT_QUOTES);
			$email = addslashes($email);
			$email = strip_tags($email);
			$encrypted_pass = md5($pass);
		
			mysql_query("INSERT INTO `members` SET `user` = '" . $user . "', `email` = '" . $email . "', `pass` = '" . $encrypted_pass . "', `avatar_link` = '" . $site_config['site_url'] . 'images/noav.gif'."', `datereg` = '" . $fadyd . "', `ip` = '" . $ip . "'") or die("Could not register.");
			
			message($lang['title']['welcome'] . ' ' . $site_config['site_name'], $lang['text']['reg_welcome']);
		}
	}
}

// Login
function login($user, $pass)
{	
	global $lang;
	
	// Make sure they have enter in a username and password
	if (!isset($user) || !isset($pass)) 
	{
		message($lang['title']['missing'], $lang['text']['fill_all_fields']);
	}
	
	else
	{
		// Replace nasty things to stop sql injection
		$user = addslashes($user);
		$user = strip_tags($user);
		$user = htmlspecialchars($user, ENT_QUOTES);
		$encrypted_pass = md5($pass);
		
		
		$login_check = mysql_num_rows(mysql_query("SELECT * FROM `members` WHERE `user` = '" . $user . "' AND `pass` = '" . $encrypted_pass . "'"));
		$lock_check = mysql_fetch_array(mysql_query("SELECT * FROM `members` WHERE `locked` = '1' AND `user` = '" . $user . "'"));
		
		if ($login_check <= '0')
		{
			message($lang['title']['loggin_failed'], $lang['text']['login_failed']);
		} 
		
		else if ($lock_check >= '1')
		{
			message($lang['title']['locked'], $lang['text']['account_locked']);
			
			unset($_SESSION['user']);
			unset($_SESSION['pass']);
			session_destroy();
		}
		
		else 
		{		
			$_SESSION['user'] = $user;
			$_SESSION['pass'] = $encrypted_pass;
			
			message($lang['title']['logged'], $lang['text']['logged_in']);
		}
	}
}

// logout
function logout()
{
	unset($_SESSION["user"]);
	unset($_SESSION["pass"]);
	setcookie("user", $_SESSION['user'], time()+1);
	setcookie("pass", $_SESSION['pass'], time()+1);
	session_destroy();
}

// cookies
function cookies()
{	
	if (isset($_SESSION['user']) && isset($_SESSION['pass']))
	{
		setcookie('user', $_SESSION['user'], time()+3600*24*365, $site_config['site_path'], $site_config['site_url']);
		setcookie('pass', $_SESSION['pass'], time()+3600*24*365, $site_config['site_path'], $site_config['site_url']);
	}
	
	if (isset($_COOKIE['user']) && isset($_COOKIE['pass']))
	{
		$_SESSION['user'] = $_COOKIE['user'];
		$_SESSION['pass'] = $_COOKIE['pass'];	
	}
}

// Make sure there not locked (banned)
function lock_checker()
{
	global $lang, $user_info;
	
	if (isset($_SESSION['user']) && isset($_SESSION['pass']))
	{
		$lock_check = mysql_fetch_array(mysql_query("SELECT * FROM `members` WHERE `locked` = '1' AND `user` = '".$user_info['user']."'"));
		if ($lock_check >= '1')
		{
			message($lang['title']['locked'], $lang['text']['account_locked']);
			
			unset($_SESSION['user']);
			unset($_SESSION['pass']);
			session_destroy();
		}
	}
}

// users online
function update_users_online()
{
	global $user_info;
	
	// Current time
	$ctime = time();
	
	if (isset($_SESSION['user']) && isset($_SESSION['pass']))
	{
		mysql_query("UPDATE `members` SET `lpv` = '" . $ctime . "' WHERE `id` = '" . $user_info['id'] . "'");
		
		$check1 = mysql_query("SELECT `id`,`lpv` FROM `members` ORDER BY `id`");
		
		while ($info = mysql_fetch_array($check1))
		{
			// do the check
			$check2 = ($ctime - $info['lpv']);
			
			if ($check2 <= '300')
			{
				mysql_query("UPDATE `members` SET `online` = '1' WHERE `id` = '" . $info['id'] . "'");
			}
			
			else if ($check2 > '300')
			{
				mysql_query("UPDATE `members` SET `online` = '0' WHERE `id` = '" . $info['id'] . "'");
			}
		}
	}
}

// guests online
function update_guests_online()
{
	global $ip;
	
	// how long you want before a guest is inactive.
	// 300 secs = 5mins
	$info['life'] = '300';
	
	// Do not edit below, unless you know how too
	$info['time'] = time();
	$info['time_work'] = $info['time'] - $info['life'];
	
	mysql_query("DELETE FROM `guests_online` WHERE `time` < '" . $info['time_work'] . "'");
	
	$check = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS `count` FROM `guests_online` WHERE `ip` = '" . $ip . "'"));
	
	if ($check['count'] >= '1' && isset($_SESSION['user']) && isset($_SESSION['pass']))
	{
		 mysql_query("DELETE FROM `guests_online` WHERE `ip` = '" . $ip . "'");
	}
	
	else if ($check['count'] >= '1' && !isset($_SESSION['user']) && !isset($_SESSION['pass']))
	{
		 mysql_query("UPDATE `guests_online` SET `time` = '" . $info['time'] . "' WHERE `ip` = '" . $ip . "'");
	}
	
	else if ($check['count'] != '1' && !isset($_SESSION['user']) && !isset($_SESSION['pass']))
	{
		 mysql_query("INSERT INTO `guests_online` SET `ip` = '" . $ip . "', `time` = '" . $info['time'] . "'");
	}
}

// Errors
function error($error_title,$error_message)
{
	global $start_time;
	
	$title = $error_title;
	$message = $error_message;
	get_template('message');

	include('includes/footer.php');
	
	// End timer
	$end_time = explode(' ', microtime());
	$total_time = round($end_time[1] + $end_time[0] - $start_time[1] - $start_time[0], 3);
	
	echo "<div align='center'>" . $total_time . "</div>";
	
	die;
}

function admin_error($error_title,$error_message)
{	
	$title = $error_title;
	$message = $error_message;
	get_template('message');
	
	die;
}

// Messages
function message($message_title,$message_message)
{		
	global $title, $message, $lang;
	
	$title = $message_title;
	$message = $message_message;
	get_template('message');
}
?>