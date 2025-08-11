<?php
//Auto generated header. Don't change by hand

//database
define(DB_TYPE, 'mysql');
define(DB_HOST, 'localhost');
define(DB_NAME, 'chakraweb');
define(DB_USER, 'root');
define(DB_PASSWORD, 'password');

//for sending email
define(MAIL_TYPE, 'smtp'); //mail or smtp
define(SMTP_HOST, 'localhost');
define(SMTP_HELO, 'localhost');
define(SMTP_PORT, '25');

//logging
$gLogDBase      = true;
$gLogVisitor    = true;

//prefix use on log files
define(DBLOG_PREFIX, 'db.');
define(STATLOG_PREFIX, 'mx.');

//base url path
$gBaseUrlPath = '';

//homepage url
$gHomePageUrl = 'http://localhost:80';

//maintenance time
$gMaintenanceTime = false;

//default values
define(DEFAULT_THEME, 'StdBlue');
define(DEFAULT_LID, 'en');
define(DEFAULT_ORDER, 9999);
define(DEFAULT_ROBOTS, 'index, follow');

//search result
define(MAX_ITEM_PERPAGE, 10);
define(MAX_PAGEPOS_SHOW, 5);

//system var
$gSysVar = array (
    'hp_name'           => 'YourHomePage Name',
    'hp_desc'           => 'Enter The Description Of YourHomePage',
    'hp_slogan'         => 'The Slogan Of YourHomePage',
    'hp_keywords'       => 'keywords',
    'hp_header'         => '<A href="/index.html">{nav_home}</A>&nbsp;|&nbsp; <A href="/sitemap.html">{nav_sitemap}</A>&nbsp;|&nbsp; <A href="/Feedback/index.html">{nav_feedback}</A>&nbsp;|&nbsp; <A href="/linktous.html">{nav_linktous}</A>&nbsp;|&nbsp; <A href="javascript:addToFavorites(\'{page_url}\',\'{page_title}\');">{nav_bookmarkus}</A>&nbsp;|&nbsp; <A href="/aboutus.html">{nav_aboutus}</A>&nbsp;&nbsp;',
    'hp_footer'         => '{mgmt_menu} <BR><A href="/aboutus.html">{nav_aboutus}</A>&nbsp;|&nbsp; <A href="/sitemap.html">{nav_sitemap}</A>&nbsp;|&nbsp; <A href="/Feedback/index.html">{nav_feedback}</A>&nbsp;|&nbsp; <A href="/linktous.html">{nav_linktous}</A>&nbsp;|&nbsp; <A href="javascript:addToFavorites(\'{page_url}\',\'{page_title}\');">{nav_bookmarkus}</A>&nbsp;|&nbsp; <A href="/terms_of_use.html">{nav_term_of_use}</A>&nbsp;|&nbsp; <A href="/privacy_policy.html">{nav_privacy_policy}</A> <BR>{made_by_chakraweb}<BR>URL: {page_url}. This website visited <B>{hp_visitors}</B> times since {hp_visited_since}. Hit count: <B>{hp_hits}</B>.',
    'hp_sidebar'        => '{subfolder_menu} {login_form} {search_form}{macro:feedback} {powered_by_chakraweb}',
    'svc_email_from'    => 'webmaster@yourdomain.com',
    'svc_email_replay'  => 'webmaster@yourdomain.com',
    'svc_email_subject' => '',
);

?>
