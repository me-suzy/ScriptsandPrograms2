<?php
// -------------------------------------------------------------
//
// $Id: function.php,v 1.18 2005/06/25 05:24:25 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

function check_email($email)
{
	return preg_match('#^[a-z0-9_\.\-]+?@[a-z0-9_\.\-]+?\.[a-z]{2,6}$#si', $email);
}

function close_tags($str)
{
	$tags = array(
		'a',
		'abbr',
		'acronym',
		'address',
		'b',
		'bdo',
		'big',
		'blockquote',
		'body',
		'button',
		'caption',
		'cite',
		'code',
		'colgroup',
		'dd',
		'del',
		'dfn',
		'div',
		'dl',
		'dt',
		'em',
		'fieldset',
		'form',
		'h1',
		'h2',
		'h3',
		'h4',
		'h5',
		'h6',
		'head',
		'html',
		'i',
		'ins',
		'kbd',
		'label',
		'legend',
		'li',
		'map',
		'noscript',
		'object',
		'ol',
		'optgroup',
		'option',
		'p',
		'pre',
		'q',
		'samp',
		'script',
		'select',
		'small',
		'span',
		'strong',
		'style',
		'sub',
		'sup',
		'table',
		'tbody',
		'td',
		'textarea',
		'tfoot',
		'th',
		'thead',
		'title',
		'tr',
		'tt',
		'ul',
		'var');
	foreach ($tags as $v)
	{
		$opened_tags = preg_match_all('#<(' . $v . ')( .*?)>#si', $str, $opened_matches, PREG_PATTERN_ORDER);
		$closed_tags = preg_match_all('#</(' . $v . ')>#si', $str, $closed_matches, PREG_PATTERN_ORDER);
		if ($opened_tags > $closed_tags)
		{
			for ($i = 0; $i < ($opened_tags - $closed_tags); $i++)
			{
				$str = $str . '</' . $opened_matches[1][$i] . '>';
			}
		}
	}
	return $str;
}

function decode($str)
{
	$i = -1;
	$key = strtoupper(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']));
	$str = base64_decode($str);
	for ($j = 0; $j < strlen($str); $j++)
	{
		$i++;
		if ($i >= strlen($key))
		{
			$i = 0;
		}
		$word = ord($str[$j]) - ord($key[$i]);
		if ($word <= 0)
		{
			$word += 256;
		}
		$password .= chr($word);
	}
	return $password;
}

function do_bbcode($str)
{
	$str = preg_replace('#\[b\](.*?)\[/b\]#si', '<span style="font-weight: bold">\\1</span>', $str);
	$str = preg_replace('#\[i\](.*?)\[/i\]#si', '<span style="font-style: italic">\\1</span>', $str);
	$str = preg_replace('#\[u\](.*?)\[/u\]#si', '<span style="text-decoration: underline">\\1</span>', $str);
	$str = preg_replace('#\[color=(\#[0-9a-f]{3,6}|aqua|black|blue|fuchsia|gray|green|lime|maroon|navy|olive|purple|red|silver|teal|white|yellow)\](.*?)\[/color\]#si', '<span style="color: \\1">\\2</span>', $str);
	$str = preg_replace('#\[size=([1-2]?[0-9](px|pt)|smaller|larger)\](.*?)\[/size\]#si', '<span style="font-size: \\1; line-height: normal">\\3</span>', $str);
	$str = preg_replace('#\[quote=(.*?)\](.*?)\[/quote\]#si', '<div class="quoteHeader">\\1</div><div class="quoteText">\\2</div>', $str);
	$str = preg_replace('#\[url\]([\w]+?://[^ "\n\r\t<]*?)\[/url\]#si', '<a href="\\1" title="\\1">\\1</a>', $str);
	$str = preg_replace('#\[url\]((www|ftp)\.[^ "\n\r\t<]*?)\[/url\]#si', '<a href="http://\\1" title="\\1">\\1</a>', $str);
	$str = preg_replace('#\[url=([\w]+?://[^ "\n\r\t<]*?)\](.*?)\[/url\]#si', '<a href="\\1" title="\\2">\\2</a>', $str);
	$str = preg_replace('#\[url=((www|ftp)\.[^ "\n\r\t<]*?)\](.*?)\[/url\]#si', '<a href="http://\\1" title="\\3">\\3</a>', $str);
	$str = preg_replace('#\[img\]((ht|f)tp://)([^ "\r\n\t<]*?)\[/img\]#si', '<img src="\\1\\3" alt="." title="\\1\\3" />', $str);
	$str = preg_replace('#\[email\]([a-z0-9&\-_.]+?@[\w\-]+\.([\w\-\.]+\.)?[\w]+)\[/email\]#si', '<a href="mailto:\\1" title="\\1">\\1</a>', $str);
	return $str;
}

function encode($str)
{
	$i = -1;
	$key = strtoupper(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']));
	for ($j = 0; $j < strlen($str); $j++)
	{
		$i++;
		if ($i >= strlen($key))
		{
			$i = 0;
		}
		$word = ord($str[$j]) + ord($key[$i]);
		if ($word >= 256)
		{
			$word -= 256;
		}
		$password .= chr($word);
	}
	return base64_encode($password);
}

function error_template($msg)
{
	global $lang, $template;
	$template->set_file('error', 'error.tpl');
	$template->set_var(array(
		'BACK_HOME' => $lang['BACK_HOME'],
		'ERROR' => $msg));
}

function get_date_format()
{
	global $sql;
	if ($_COOKIE['date_format'])
	{
		$date_format = $_COOKIE['date_format'];
	}
	elseif ($_SESSION['user_id'])
	{
		$sql->query('SELECT user_date_format
				FROM ' . TABLE_USERS . '
				WHERE user_id = \'' . $_SESSION['user_id'] . '\'');
		$table_users = $sql->fetch();
		$date_format = $table_users['user_date_format'];
	}
	else
	{
		$sql->query('SELECT date_format
				FROM ' . TABLE_SETTINGS . '');
		$table_settings = $sql->fetch();
		$date_format = $table_settings['date_format'];
	}
	return $date_format;
}

function get_date_offset()
{
	global $sql;
	if ($_COOKIE['date_offset'])
	{
		$date_offset = ($_COOKIE['date_offset'] * 3600);
	}
	elseif ($_SESSION['user_id'])
	{
		$sql->query('SELECT user_date_offset
				FROM ' . TABLE_USERS . '
				WHERE user_id = \'' . $_SESSION['user_id'] . '\'');
		$table_users = $sql->fetch();
		$date_offset = ($table_users['user_date_offset'] * 3600);
	}
	else
	{
		$sql->query('SELECT date_offset
				FROM ' . TABLE_SETTINGS . '');
		$table_settings = $sql->fetch();
		$date_offset = ($table_settings['date_offset'] * 3600);
	}
	return $date_offset;
}

function get_language()
{
	global $sql;
	$sql->query('SELECT language, language_unique
			FROM ' . TABLE_SETTINGS . '');
	$table_settings = $sql->fetch();
	if ($table_settings['language_unique'] == 1)
	{
		$language = $table_settings['language'];
	}
	elseif ($_COOKIE['language'])
	{
		$language = $_COOKIE['language'];
	}
	elseif ($_SESSION['user_id'])
	{
		$sql->query('SELECT user_language
				FROM ' . TABLE_USERS . '
				WHERE user_id = \'' . $_SESSION['user_id'] . '\'');
		$table_users = $sql->fetch();
		$language = $table_users['user_language'];
	}
	else
	{
		$language = $table_settings['language'];
	}
	return $language;
}

function get_online_users()
{
	$file = './../online_users.txt';
	$ip = $_SERVER['REMOTE_ADDR'];
	$expiry = 300;
	$n = 0;
	if (file_exists($file))
	{
		$fp = fopen($file, 'r');
		while (!feof($fp))
		{
			$row = fgets($fp, 4096);
			$table = explode('|', $row);
			if ($table[0] != $ip)
			{
				if ((time() - $table[1]) <= $expiry)
				{
					$n++;
					$data .= rtrim($row) . "\n";
				}
			}
		}
		fclose($fp);
	}
	$n++;
	$data .= rtrim($ip) . '|' . rtrim(time()) . "\n";
	$fp = fopen($file, 'w');
	flock($fp, LOCK_EX);
	fwrite($fp, $data, strlen($data));
	flock($fp, LOCK_UN);
	fclose($fp);
	return $n;
}

function get_smilies_list()
{
	global $sql;
	$sql->query('SELECT allow_smilies
			FROM ' . TABLE_SETTINGS . '');
	$table_settings = $sql->fetch();
	$sql->query('SELECT *
			FROM ' . TABLE_SMILIES . '');
	while ($table_smilies = $sql->fetch())
	{
		if ($table_settings['allow_smilies'] == 1)
		{
			$smilies_list .= '<img src="' . $table_smilies['smiley_image'] . '" style="cursor: pointer" alt="." title="' . $table_smilies['smiley_code'] . '" onclick="insert(\'' . $table_smilies['smiley_code'] . '\',\'\');" />&nbsp;';
		}
		else
		{
			$smilies_list = '';
		}
	}
	return $smilies_list;
}

function get_template()
{
	global $sql;
	$sql->query('SELECT template, template_unique
			FROM ' . TABLE_SETTINGS . '');
	$table_settings = $sql->fetch();
	if ($table_settings['template_unique'] == 1)
	{
		$template = $table_settings['template'];
	}
	elseif ($_COOKIE['template'])
	{
		$template = $_COOKIE['template'];
	}
	elseif ($_SESSION['user_id'])
	{
		$sql->query('SELECT user_template
				FROM ' . TABLE_USERS . '
				WHERE user_id = \'' . $_SESSION['user_id'] . '\'');
		$table_users = $sql->fetch();
		$template = $table_users['user_template'];
	}
	else
	{
		$template = $table_settings['template'];
	}
	return $template;
}

function get_templates_list()
{
	$path = $_GET['path'];
	if(!$path)
	{
		$path = './../templates';
	}
	$handle = dir($path);
	$previous = substr($path, 0, (strrpos(dirname($path . '/.'), '/')));
	if ($path != './../templates')
	{
		$data .= '<a href="./../admin/index.php?action=edit_templates&amp;path=' . $previous . '" title="../">../</a><br />';
	}
	while ($file = $handle->read())
	{
		if ($file != '.' && $file != '..' && !strpos($file, '.htm'))
		{
			if (is_dir($path . '/' . $file))
			{
				$data .= '<a href="./../admin/index.php?action=edit_templates&amp;path=' . $path . '/' . $file . '" title="' . $file . '/">' . $file . '/</a><br />';
			}
			if (is_file($path . '/' . $file))
			{
				$data .= '<a href="./../admin/index.php?action=edit_template&amp;file=' . $path . '/' . $file . '" title="' . $file . '">' . $file . '</a>&nbsp;' . filesize($path . '/' . $file) . ' bytes<br />';
			}
		}
	}
	$handle->close();
	return $data;
}

function make_backend_rss()
{
	global $sql;
	$sql->query('SELECT headlines_per_backend, sitename, siteurl
			FROM ' . TABLE_SETTINGS . '');
	$table_settings = $sql->fetch();
	$rss .= '<?xml version="1.0" encoding="ISO-8859-1" ?>' . "\n";
	$rss .= '<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">' . "\n";
	$rss .= '<channel>' . "\n";
	$rss .= '<title>' . $table_settings['sitename'] . '</title>' . "\n";
	$rss .= '<link>' . $table_settings['siteurl'] . '</link>' . "\n";
	$rss .= '<description>' . $table_settings['sitename'] . '</description>' . "\n";
	$rss .= '<dc:language>en</dc:language>' . "\n";
	$sql->query('SELECT news_date, news_id, news_subject
			FROM ' . TABLE_NEWS . '
			WHERE news_active = \'1\'
			ORDER BY news_date DESC
			LIMIT 0, ' . $table_settings['headlines_per_backend'] . '');
	while ($table_news = $sql->fetch())
	{
		$date = date('Y-m-d\TH:i:sO', $table_news['news_date']);
		$date = substr($date, 0, -2) . ':00';
		$rss .= '<item>' . "\n";
		$rss .= '<title>' . $table_news['news_subject'] . '</title>' . "\n";
		$rss .= '<link>' . $table_settings['siteurl'] . '/comments/index.php?news_id=' . $table_news['news_id'] . '</link>' . "\n";
		$rss .= '<dc:date>' . $date . '</dc:date>' . "\n";
		$rss .= '</item>' . "\n";
	}
	$rss .= '</channel>' . "\n";
	$rss .= '</rss>';
	$fp = fopen('./../backends/backend.xml', 'w');
	flock($fp, LOCK_EX);
	fwrite($fp, $rss, strlen($rss));
	flock($fp, LOCK_UN);
	fclose($fp);
}

function make_backend_txt()
{
	global $sql;
	$sql->query('SELECT headlines_per_backend, siteurl
			FROM ' . TABLE_SETTINGS . '');
	$table_settings = $sql->fetch();
	$sql->query('SELECT news_date, news_id, news_subject
			FROM ' . TABLE_NEWS . '
			WHERE news_active = \'1\'
			ORDER BY news_date DESC
			LIMIT 0, ' . $table_settings['headlines_per_backend'] . '');
	while ($table_news = $sql->fetch())
	{
		$txt .= '%%' . "\n";
		$txt .= $table_news['news_date'] . "\n";
		$txt .= $table_news['news_subject'] . "\n";
		$txt .= $table_settings['siteurl'] . '/comments/index.php?news_id=' . $table_news['news_id'] . "\n";
	}
	$fp = fopen('./../backends/backend.txt', 'w');
	flock($fp, LOCK_EX);
	fwrite($fp, $txt, strlen($txt));
	flock($fp, LOCK_UN);
	fclose($fp);
}

function make_clickable($str)
{
	$str = preg_replace('#(^|[\n ])([\w]+?://[^ "\n\r\t<]*)#si', '\\1<a href="\\2" title="\\2">\\2</a>', $str);
	$str = preg_replace('#(^|[\n ])((www|ftp)\.[^ "\t\n\r<]*)#si', '\\1<a href="http://\\2" title="\\2">\\2</a>', $str);
	$str = preg_replace('#(^|[\n ])([a-z0-9&\-_.]+?@[\w\-]+\.([\w\-\.]+\.)?[\w]+)#si', '\\1<a href="mailto:\\2" title="\\2">\\2</a>', $str);
	return $str;
}

function page_footer()
{
	global $lang, $sql, $start_time, $template;
	$mtime = explode(' ', microtime());
	$end_time = $mtime[1] + $mtime[0];
	$total_time = number_format(($end_time - $start_time), 3, '.', ' ') . ' s';
	$sql->query('SELECT headlines_per_backend, sitename
			FROM ' . TABLE_SETTINGS . '');
	$table_settings = $sql->fetch();
	$template->set_file('footer', 'footer.tpl');
	$template->set_var(array(
		// --------------------
		'FOOTER_EXTRA_1' => $lang['FOOTER_EXTRA_1'],
		'FOOTER_EXTRA_2' => $lang['FOOTER_EXTRA_2'],
		'FOOTER_EXTRA_3' => $lang['FOOTER_EXTRA_3'],
		'FOOTER_EXTRA_4' => $lang['FOOTER_EXTRA_4'],
		'FOOTER_EXTRA_5' => $lang['FOOTER_EXTRA_5'],
		'FOOTER_EXTRA_6' => $lang['FOOTER_EXTRA_6'],
		'FOOTER_EXTRA_7' => $lang['FOOTER_EXTRA_7'],
		'FOOTER_EXTRA_8' => $lang['FOOTER_EXTRA_8'],
		'FOOTER_EXTRA_9' => $lang['FOOTER_EXTRA_9'],
		'FOOTER_EXTRA_10' => $lang['FOOTER_EXTRA_10'],
		'FOOTER_EXTRA_11' => $lang['FOOTER_EXTRA_11'],
		'FOOTER_EXTRA_12' => $lang['FOOTER_EXTRA_12'],
		'FOOTER_EXTRA_13' => $lang['FOOTER_EXTRA_13'],
		'FOOTER_EXTRA_14' => $lang['FOOTER_EXTRA_14'],
		'FOOTER_EXTRA_15' => $lang['FOOTER_EXTRA_15'],
		// --------------------
		'BACKEND_RSS' => parse_rss($table_settings['headlines_per_backend']),
		'BACKEND_TXT' => parse_txt($table_settings['headlines_per_backend']),
		'PAGE_COPYRIGHT' => sprintf($lang['PAGE_COPYRIGHT'], $table_settings['sitename']),
		'PAGE_GENERATION' => sprintf($lang['PAGE_GENERATION'], $total_time, $sql->num_queries()),
		'PAGE_POWERED' => sprintf($lang['PAGE_POWERED'], GENU_VERSION)));
	$template->pparse('', 'footer');
	$sql->close();
}

function page_header($page_title)
{
	global $lang, $sql, $template;
	$sql->query('SELECT user_name
			FROM ' . TABLE_USERS . '
			WHERE user_id = \'' . $_SESSION['user_id'] . '\'');
	$table_users = $sql->fetch();
	if (!$_SESSION['user_id'])
	{
		$login_form = '&middot; <a href="./../users/login.php" title="' . $lang['HEADER_BLOCK2_LINK1'] . '">' . $lang['HEADER_BLOCK2_LINK1'] . '</a>';
	}
	else
	{
		$login_form = sprintf($lang['HEADER_BLOCK2_CONTENT'], $table_users['user_name']);
	}
	$sql->query('SELECT user_id
			FROM ' . TABLE_USERS . '
			WHERE user_level != \'0\'');
	$num_users = $sql->num_rows();
	$sql->query('SELECT headlines_per_backend, sitename, siteurl
			FROM ' . TABLE_SETTINGS . '');
	$table_settings = $sql->fetch();
	$template->set_file('header', 'header.tpl');
	$template->set_var(array(
		'HEADER_BLOCK1_LINK1' => $lang['HEADER_BLOCK1_LINK1'],
		'HEADER_BLOCK1_LINK2' => $lang['HEADER_BLOCK1_LINK2'],
		'HEADER_BLOCK1_LINK3' => $lang['HEADER_BLOCK1_LINK3'],
		'HEADER_BLOCK1_LINK4' => $lang['HEADER_BLOCK1_LINK4'],
		'HEADER_BLOCK1_LINK5' => $lang['HEADER_BLOCK1_LINK5'],
		'HEADER_BLOCK1_LINK6' => $lang['HEADER_BLOCK1_LINK6'],
		'HEADER_BLOCK1_TITLE' => $lang['HEADER_BLOCK1_TITLE'],
		'HEADER_BLOCK2_LINK2' => $lang['HEADER_BLOCK2_LINK2'],
		'HEADER_BLOCK2_LINK3' => $lang['HEADER_BLOCK2_LINK3'],
		'HEADER_BLOCK2_TITLE' => $lang['HEADER_BLOCK2_TITLE'],
		'HEADER_BLOCK3_SUBJECT' => $lang['HEADER_BLOCK3_SUBJECT'],
		'HEADER_BLOCK3_TEXT' => $lang['HEADER_BLOCK3_TEXT'],
		'HEADER_BLOCK3_TITLE' => $lang['HEADER_BLOCK3_TITLE'],
		'HEADER_BLOCK4_CONTENT' => sprintf($lang['HEADER_BLOCK4_CONTENT'], get_online_users()),
		'HEADER_BLOCK4_TITLE' => $lang['HEADER_BLOCK4_TITLE'],
		// --------------------
		'HEADER_EXTRA_1' => $lang['HEADER_EXTRA_1'],
		'HEADER_EXTRA_2' => $lang['HEADER_EXTRA_2'],
		'HEADER_EXTRA_3' => $lang['HEADER_EXTRA_3'],
		'HEADER_EXTRA_4' => $lang['HEADER_EXTRA_4'],
		'HEADER_EXTRA_5' => $lang['HEADER_EXTRA_5'],
		'HEADER_EXTRA_6' => $lang['HEADER_EXTRA_6'],
		'HEADER_EXTRA_7' => $lang['HEADER_EXTRA_7'],
		'HEADER_EXTRA_8' => $lang['HEADER_EXTRA_8'],
		'HEADER_EXTRA_9' => $lang['HEADER_EXTRA_9'],
		'HEADER_EXTRA_10' => $lang['HEADER_EXTRA_10'],
		'HEADER_EXTRA_11' => $lang['HEADER_EXTRA_11'],
		'HEADER_EXTRA_12' => $lang['HEADER_EXTRA_12'],
		'HEADER_EXTRA_13' => $lang['HEADER_EXTRA_13'],
		'HEADER_EXTRA_14' => $lang['HEADER_EXTRA_14'],
		'HEADER_EXTRA_15' => $lang['HEADER_EXTRA_15'],
		// --------------------
		'BACKEND_RSS' => parse_rss($table_settings['headlines_per_backend']),
		'BACKEND_TXT' => parse_txt($table_settings['headlines_per_backend']),
		'LOGIN_FORM' => $login_form,
		'NUM_USERS' => $num_users,
		'PAGE_REVISION' => date('Ymd', getlastmod()),
		'PAGE_TITLE' => $page_title,
		'SEARCH' => $lang['SEARCH'],
		'SITENAME' => $table_settings['sitename'],
		'SITEURL' => $table_settings['siteurl'],
		'TEMPLATE' => get_template()));
	$template->pparse('', 'header');
}

function parse_rss($n)
{
	global $lang;
	$url = './../backends/backend.xml';
	$handle = @fopen($url, 'r');
	if (!$handle)
	{
		$rss = $lang['INVALID_BACKEND_FILE'];
	}
	else
	{
		$file = fread($handle, 4096);
		$items = eregi('<item>(.*)</item>', $file, $array);
		$item = explode('<item>', $array[0]);
		fclose($handle);
		if ($n <= (count($item) - 1))
		{
			for ($i = 1; $i <= $n; $i++)
			{
				ereg('<title>(.*)</title>', $item[$i], $title);
				ereg('<link>(.*)</link>', $item[$i], $link);
				$rss .= '&middot; <a href="' . $link[1] . '" title="' . $title[1] . '">' . $title[1] . '</a><br />';
			}
		}
		else
		{
			for ($i = 1; $i <= (count($item) - 1); $i++)
			{
				ereg('<title>(.*)</title>', $item[$i], $title);
				ereg('<link>(.*)</link>', $item[$i], $link);
				$rss .= '&middot; <a href="' . $link[1] . '" title="' . $title[1] . '">' . $title[1] . '</a><br />';
			}
		}
	}
	return $rss;
}

function parse_txt($n)
{
	global $lang;
	$date_format = get_date_format();
	$date_offset = get_date_offset();
	$url = './../backends/backend.txt';
	$array = @file($url);
	if (!$array)
	{
		$txt = $lang['INVALID_BACKEND_FILE'];
	}
	else
	{
		$item = array();
		if ($n <= ((int)(sizeof($array) / 4)))
		{
			for ($i = 0; $i < $n; $i++)
			{
				$item[$i]['date'] = date($date_format, ($array[4 * $i + 1] + $date_offset));
				$item[$i]['title'] = $array[4 * $i + 2];
				$item[$i]['link'] = $array[4 * $i + 3];
				$txt .= $item[$i]['date'] . ' - <a href="' . $item[$i]['link'] . '" title="' . $item[$i]['title'] . '">' . $item[$i]['title'] . '</a><br />';
			}
		}
		else
		{
			for ($i = 0; $i < ((int)(sizeof($array) / 4)); $i++)
			{
				$item[$i]['date'] = date($date_format, ($array[4 * $i + 1] + $date_offset));
				$item[$i]['title'] = $array[4 * $i + 2];
				$item[$i]['link'] = $array[4 * $i + 3];
				$txt .= $item[$i]['date'] . ' - <a href="' . $item[$i]['link'] . '" title="' . $item[$i]['title'] . '">' . $item[$i]['title'] . '</a><br />';
			}
		}
	}
	return $txt;
}

function set_user_cookies()
{
	global $sql;
	$sql->query('SELECT user_date_format, user_date_offset, user_language, user_template
			FROM ' . TABLE_USERS . '
			WHERE user_id = \'' . $_SESSION['user_id'] . '\'');
	$table_users = $sql->fetch();
	setcookie('date_format', $table_users['user_date_format'], (time() + COOKIE_EXPIRY), '/', '');
	setcookie('date_offset', $table_users['user_date_offset'], (time() + COOKIE_EXPIRY), '/', '');
	setcookie('language', $table_users['user_language'], (time() + COOKIE_EXPIRY), '/', '');
	setcookie('template', $table_users['user_template'], (time() + COOKIE_EXPIRY), '/', '');
}

function slash_input_data(&$data)
{
	if (is_array($data))
	{
		foreach ($data as $k => $v)
		{
			$data[$k] = (is_array($v)) ? slash_input_data($v) : addslashes($v);
		}
	}
	return $data;
}

function split_sql_file($sql)
{
	$q = array();
	$lines = explode("\n", $sql);
	for ($i = 0; $i < count($lines); $i++)
	{
		$l .= ($lines[$i]{0} != '#') ? $lines[$i] . "\n" : "\n";
	}
	$queries = explode(";\n", $l);
	for ($j = 0; $j < (count($queries) - 1); $j++)
	{
		$q[] = $queries[$j] . ';';
	}
	return $q;
}

function strip_input_data(&$data)
{
	if (is_array($data))
	{
		foreach ($data as $k => $v)
		{
			$data[$k] = (is_array($v)) ? strip_input_data($v) : stripslashes($v);
		}
	}
	return $data;
}

function success_template($msg)
{
	global $lang, $template;
	$template->set_file('success', 'success.tpl');
	$template->set_var(array(
		'BACK_HOME' => $lang['BACK_HOME'],
		'SUCCESS' => $msg));
}

function undo_bbcode($str)
{
	$str = preg_replace('#<span style="font-weight: bold">(.*?)</span>#si', '[b]\\1[/b]', $str);
	$str = preg_replace('#<span style="font-style: italic">(.*?)</span>#si', '[i]\\1[/i]', $str);
	$str = preg_replace('#<span style="text-decoration: underline">(.*?)</span>#si', '[u]\\1[/u]', $str);
	$str = preg_replace('#<span style="color: (.*?)">(.*?)</span>#si', '[color=\\1]\\2[/color]', $str);
	$str = preg_replace('#<span style="font-size: (.*?); line-height: normal">(.*?)</span>#si', '[size=\\1]\\2[/size]', $str);
	$str = preg_replace('#<div class="quoteHeader">(.*?)</div><div class="quoteText">(.*?)</div>#si', '[quote=\\1]\\2[/quote]', $str);
	$str = preg_replace('#<a href="([\w]+?://[^ "\n\r\t<]*?)" title="(.*?)">(.*?)</a>#si', '[url=\\1]\\3[/url]', $str);
	$str = preg_replace('#<img src="(.*?)" alt="." title="(.*?)" />#si', '[img]\\1[/img]', $str);
	$str = preg_replace('#<a href="mailto:(.*?)" title="(.*?)">(.*?)</a>#si', '[email]\\1[/email]', $str);
	return $str;
}

?>