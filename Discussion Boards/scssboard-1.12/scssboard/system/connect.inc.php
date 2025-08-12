<?php

include("system/config.inc.php");

//-------------------------------------------------------
//Don't edit anything below
//-------------------------------------------------------

@mysql_connect("$_CON[host]","$_CON[user]","$_CON[pass]");
@mysql_select_db("$_CON[name]");

$setting_buffer = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]settings where setting_name = 'board_name'"));
$_MAIN[forumname] = $setting_buffer[setting_value];

$setting_buffer = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]settings where setting_name = 'default_style'"));
$_MAIN[default_style] = $setting_buffer[setting_value];

$setting_buffer = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]settings where setting_name = 'allow_signups'"));
$_MAIN[allow_signups] = $setting_buffer[setting_value];

$setting_buffer = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]settings where setting_name = 'cookie_path'"));
$_MAIN[cookie_path] = $setting_buffer[setting_value];

$setting_buffer = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]settings where setting_name = 'cookie_url'"));
$_MAIN[cookie_url] = $setting_buffer[setting_value];

$setting_buffer = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]settings where setting_name = 'redir_method'"));
$_MAIN[redir_method] = $setting_buffer[setting_value];

$setting_buffer = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]settings where setting_name = 'sig_bbcode'"));
$_MAIN[allow_sig_bbcode] = $setting_buffer[setting_value];

$setting_buffer = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]settings where setting_name = 'date_format'"));
$_MAIN[date_format] = $setting_buffer[setting_value];

$setting_buffer = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]settings where setting_name = 'use_relative_dates'"));
$_MAIN[use_relative_dates] = $setting_buffer[setting_value];

$setting_buffer = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]settings where setting_name = 'debug_level'"));
$_MAIN[debug_level] = $setting_buffer[setting_value];

$_MAIN[script_version] = "1.12";

$_MAIN[script_version_simp] = "112";

?>