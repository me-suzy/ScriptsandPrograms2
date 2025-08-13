<?php
// +------------------------------------------------------------------+
// | evoArticles
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+

chdir("admin");
require_once("./common.php");
chdir("..");
unset($root);

$site['title'] = "Setup";
// ** define stuff *** //
$tpl = new template;
$tpl->templatefolder="";
$tpl->cssfile="";
$tpl->extension="inc";
$cssfile = $tpl->cssfile;

$admin = new admin;
$admin->imgfolder=$root."img";
$script[imgfolder] = $admin->imgfolder;
start(); // start timer
$PHP_SELF = $_SERVER['PHP_SELF'];
$site[name] = "evoArticles";
$site['prefix'] = ($_REQUEST['prefix'] != '') ? $_REQUEST['prefix']:$site['prefix'];

require("admin/lib/db_tables.php");
/*------------------------------------------------------------*/
class Setup
{
        var $file_lock = "out/installed.lock";
        var $ins_lock = 'out/license.lock';
        var $chmod_lock = 'out/chmod.lock';

        function announce($text)
        {
                return "<div style='font-size: 8pt;'> Table $text successfully created..<br />";
        }

        function text($text)
        {
                return "<div style='font-weight:bold;font-size: 10.5pt;'> $text </div> <br />";
        }

        function cleardb()
        {
                global $_SERVER,$udb,$admin,$database,$site,$db,$script,$_GET;

                $result = $udb->query("SHOW tables");
                while ($row = $udb->fetch_array($result))
                {
                        $udb->query("DROP TABLE IF EXISTS $row[0]");
                }

                $a .= $this->announce("<br /><br /><b>Db Cleared!</b>");
                $a .= $admin->redirect($_SERVER['PHP_SELF']);
                return $a;
        }

        function header($text)
        {
                return "<h1>$text</h1><br />";
        }

        function makenext($text,$loc)
        {
                return "<div align='right'><b style='font-size:10pt'><a href='$loc'>$text</a> ></b></div><br />";
        }

        function upgrade()
        {
                global $PHP_SELF,$_REQUEST,$udb,$database,$site,$_SERVER;

                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (40, 15, 'internal_canhtml', '1', '', 'Internal : Allow HTML in comments?', 'Is html allowed in comments?', 'yesno', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (41, 15, 'internal_canbbcode', '1', '', 'Internal: Parse evoCodes?', 'Parse evoCodes(ie: [b]) in comments?', 'yesno', 0)";

                foreach ($sets as $setting)
                {
                        $udb->query($setting);
                }
        }

        function color($value=0)
        {
                if ($value != 1)
                {
                        $this->c_error = '1';
                        return "<div style=\"background-color:red;border:1px solid #000; width:25px; height: 14px;\">&nbsp;</div>";
                }
                else
                {
                        return "<div style=\"background-color:green;border:1px solid #000; width:25px; height: 14px;\">&nbsp;</div>";
                }

        }

        function do_main()
        {
                global $evoLANG_script,$evoLANG_admin,$admin,$HTTP_POST_VARS,$script,$site,$db,$_SERVER;

                        $a .= $this->text("New Installation");
                        $a .= "<div class='tblhead' style='padding:5px;border:1px solid black;'><a href='$_SERVER[PHP_SELF]?do=clear'><b style='color:red;font-size:12px'>Clear Database</b></a><br />This will reset the database and delete all tables</div><br />";

                        $admin->row_width = '80%';
                        $admin->rowname_size = '10px';
                        $admin->row_nobold = 1;

                        $a .= "<br />If you are on a *nix server and you're not sure what CHMOD is, ".$admin->makelink('read this article','http://catcode.com/teachmod/').'<br /><br />' ;

                        $rows .= $admin->add_spacer(" Perform these following tasks before proceeding ");
                        $rows .= $admin->add_row('CHMOD <b>current folder</b> (where evoArticles is installed) to 777 if you wish to use the Search-Engine-Safe URL feature which <b>requires mod_rewrite</b> (please ask your server admin if you are unsure)','');

                        $rows .= $admin->add_row('CHMOD "<b>out</b>" folder to 777',$this->color( is_writable('out/')) );
                        $rows .= $admin->add_row('CHMOD "<b>backup</b>" folder to 777',$this->color( is_writable('backup/')) );
                        $rows .= $admin->add_row('CHMOD "<b>templates/styles</b>" folder to 777 (the <b>styles</b> folder only)',$this->color( is_writable('templates/styles')) );
                        $rows .= $admin->add_row('CHMOD "<b>config_site.php</b>" file in <b>admin</b> folder to 777 (admin/config_site.php)',$this->color( is_writable('admin/config_site.php')) );

                        $a .= $admin->add_table($rows,'95%');
                        $a .= '<br /><br />';

                        $a .= $this->c_error == '1' ? $admin->warning('You still have yet to complete a task above') : $admin->write_file($this->chmod_lock,'you have passed the permission tests. hurra!').' <b style="font-size:12px" class="green"> You\'ve completed all the tasks, please proceed.</b>';

                        $a .= "<br /><br />Proceed to next step<br />";
                        $a .=  $this->makenext("Step 1",$PHP_SELF."?do=step1");
                        return $a;
        }

        function unpacker($dir='')
        {
                global $admin,$_GET;

                $maindir = ($dir=='') ? "templates/styles":$dir;
                $a2 = opendir($maindir);
                while ($ddir= readdir($a2))
                {
                        if(($ddir != ".") && ($ddir != ".."))
                        {
                                $dfile = explode(".",$ddir);
                                if ($admin->get_ext($ddir) == "tmpl")
                                {
                                $c .= "Folder : <b>$dfile[0]</b><Br />";
                                /*----------------------------------------*/

                                        $get = $admin->get_file($maindir.$ddir);
                                        $get2 = explode("******",$get);
                                        unset($i);
                                        while ($i < count($get2))
                                        {
                                                $i++;
                                                @mkdir($maindir.$dfile[0],0777);
                                                @chmod($maindir.$dfile[0],0777);
                                                if (trim($get2[$i]) != "")
                                                {
                                                        $get3 = explode("|||",$get2[$i]);
                                                        //$get3[0] //filename
                                                        //$get3[1] //content

                                                        $admin->write_file($maindir.$dfile[0]."/".$get3[0],$get3[1]);
                                                        @chmod($maindir.$dfile[0]."/".$get3[0],0666);
                                                        $c .= $maindir.$dfile[0]."/".$get3[0]."<Br />";
                                                }

                                        }
                                /*----------------------------------------*/
                                $c .= "<br />";
                                }
                        }
                }
                $return = ($_GET['return'] != "") ? $_GET['return']:"";
                //$c .= $admin->redirect($_SERVER['PHP_SELF'].$return);
                closedir($a2);
                return $c;
        }

        function settings_process()
        {
                global $PHP_SELF,$_REQUEST,$udb,$database,$site,$_SERVER;

                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (1, 2, 'dateformat', 'F j, Y', 'd m y', 'Date Format', 'date format. <a href=\\\"http://www.php.net/date\\\">here</a> for more info', 'text', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (4, 2, 'email', 'tech@email.com', 'd\\\"o@yeeha.com', 'Techical Email', 'Your Email', 'text', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (6, 13, 'closereason', 'This section of the site is currently closed.', '', 'Close Reasons', 'Do you have a reason for closing this script?', 'textarea', 1)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (8, 2, 'deflang', 'lang_english', '', 'Default Language', 'Default language used on system', 'select|||lang', 1)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (9, 7, 'usewysiwyg', '1', '', 'Use WYSIWYG?', 'Do you wish to enable the WYSIWYG feature?', 'yesno', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (10, 11, 'tplfolder', 'templates/', '', 'Templates Folder', 'Where is the topsite folder located? <br />\r\ni.e : templates<b>/</b><br />\r\nadd trailing slash at the end', 'text', 2)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (11, 2, 'navsplit', '/', '', 'Navigation Spliter', 'The thing that splits your navigation (i.g home / blah)', 'text', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (12, 12, 'cookiedomain', '', '', 'Cookie Domain', 'Domain for the cookie <br />\r\ni.e: .domain.com', 'text', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (13, 12, 'seccookie', '0', '', 'Use Secure Cookies', 'some server requires secure cookies enabled.', 'yesno', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (14, 12, 'sestimeout', '3600', '3600', 'Session Timeout', 'How long does a session last (in seconds)? <br />\r\n1 hour = 3600 sec.', 'text', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (15, 2, 'defstyle', 'default', '', 'Default Style', 'Select a default style', 'select|||style', 1)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (38, 15, 'usecomment', '0', '', 'Enable Comments ?', 'Do you wish to enable comments/feedback system for articles?', 'yesno', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (16, 2, 'sitename', 'SiteName.com', '', 'Site Name', 'Name of your site', 'text', 3)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (17, 13, 'isclose', '0', '', 'Close for public viewing?', 'Do you wish to close script for public viewing?', 'yesno', 2)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (18, 11, 'imgfolder', 'img/', 'img/', 'IMG Folder', 'Where is the \\\"img\\\" folder located?\r\n<br />\r\ndefault: img/', 'text', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (36, 14, 'allowmeta_cat', '1', '', 'Allow Custom Meta tag for Categories?', 'Allow custom meta tag for categories.', 'yesno', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (20, 7, 'showlatest', '1', '', 'Show Latest articles?', 'Do you wish to list latest articles on the main page?', 'yesno', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (21, 7, 'showhowmany', '10', '', 'Total Latest Articles shown on main page?', 'How many new articles do you wish to show on the main page?<br />\r\nrecommended : 10', 'text', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (37, 14, 'allowmeta_art', '1', '', 'Allow Custom Meta tag for Articles?', 'Allow Custom Meta tag for Articles?', 'yesno', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (23, 2, 'siteurl', 'http://siteurl.com', '', 'Site URL', 'Main site URL', 'text', 3)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (27, 9, 'useavatar', '1', '', 'Enable Avatars for Authors?', 'This option determine whether the use of avatar is allowed or not.', 'yesno', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (26, 7, 'canhtmlsummary', '0', '', 'Enable HTML in Article Summary?', 'Do you wish to enable HTML in Article Summary that will appear in article listing?', 'yesno', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (28, 9, 'maxdimension', '60', '', 'Maximum Width & Height', 'Maximum width and height in pixels of avatars allowed.', 'text', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (29, 9, 'avmaxsize', '20000', '', 'Maximum Avatar Filesize', 'Maximum avatar file size (in bytes). \r\n<br />1 MB = 1048576 bytes', 'text', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (30, 9, 'avallowedmime', 'jpg,gif,png', '', 'Allowed avatar file type', 'saparated by comma.', 'text', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (31, 7, 'uniqueonly', '1', '', 'Count unique views only for article?', 'By enabling this will count article views based on unique visitors.', 'yesno', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (32, 2, 'usecache', '0', '', 'Enable Article SmartCache?', 'This will reduce server load', 'yesno', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (33, 2, 'tpldetail', '1', '', 'Wrap Templates with template name?', 'This will add <br />\r\n&lt;!-- template name -- &gt;\r\ntemplate\r\n&lt;!-- template name -- &gt;\r\n\r\n<br />\r\nit is useful if you\'re planning to identify which template is which\r\n\r\n', 'yesno', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (34, 10, 'rss_total', '5', '', 'Total Article Headlines shown?', 'how many latest headlines link?', 'text', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (35, 2, 'useses', '1', '', 'Enable Search-Engine-Safe URL? (requires mod_rewrite)', 'This will turn url that looks like index.php?art/id:x to articles/1/', 'yesno', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (39, 15, 'commentsystem', 'internal', '', 'Comments System', 'Select a comment system you would like to use.', 'select|||comments', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (40, 15, 'internal_canhtml', '1', '', 'Internal : Allow HTML in comments?', 'Is html allowed in comments?', 'yesno', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (41, 15, 'internal_canbbcode', '1', '', 'Internal: Parse evoCodes?', 'Parse evoCodes(ie: [b]) in comments?', 'yesno', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (42, 15, 'internal_censor', 'shit\r\nfuck\r\ncrap\r\nfuckers\r\nasshole\r\nbullshit\r\nass', '', 'Internal: Words Filter', 'Filter unwanted words in comments. <br />Saparated by line breaks like so:<br />\r\nshit <br />\r\ncrap', 'textarea', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (43, 15, 'internal_usevalidation', '1', '', 'Internal: Enable Validation?', 'Do you wish to enable validation? New comments requires approval from admin before being listed.', 'yesno', 5)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (44, 7, 'art_showcustom', '1', '', 'Hide Custom Article Fields if empty?', 'If a custom article field is not filled, the field wont be showed.', 'yesno', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (45, 7, 'art_useimage', '1', '', 'Enable Article Image?', 'Article Image is used for listing purposes. i.e Article Image can be seen when viewing a category', 'yesno', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (46, 16, 'img_maxdimension', '250', '', 'Maximum Width & Height', 'Maximum width and height in pixels of avatars allowed.', 'text', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (47, 16, 'img_maxsize', '20000', '', 'Maximum Image Filesize', 'Maximum avatar file size (in bytes). \r\n<br />1 MB = 1048576 bytes', 'text', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (48, 16, 'img_allowedmime', 'jpg,gif,png', '', 'Allowed avatar file type', 'saparated by comma.', 'text', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (49, 7, 'use_relatedart', '1', '', 'Enable Related Articles field?', 'Enable this option if you wish to show related articles to an article a user is viewing (requires 1 extra query)', 'yesno', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (50, 2, 'usegzip', '', '', 'Enable GZIP?', 'Do you wish to enable gZip? This is good for reducing server loads.', 'yesno', 0)";
                $sets[] = "INSERT INTO ".$database['article_settings']." VALUES (51, 7, 'site_listarticles', '1', '', 'List Articles in Sitemap?', '<b class=\"red\">warning!</b> Not recommended for large sites. Enabling this will lists ALL articles in the sitemap.', 'yesno', 0)";


                foreach ($sets as $setting)
                {
                        $udb->query($setting);
                }
        }

        function step1()
        {
                global $PHP_SELF,$_REQUEST,$udb,$database,$site,$_SERVER;

                if ($this->c_error == '1')
                {
                        header('location: '.$_SERVER['PHP_SELF']);
                        exit;
                }

                $udb->query("DROP TABLE IF EXISTS ".$database['article_article']);
                $udb->query("CREATE TABLE ".$database['article_article']." (
                id int(50) NOT NULL auto_increment,
                  pid int(50) NOT NULL default '0',
                  author int(50) NOT NULL default '0',
                  date varchar(100) NOT NULL default '',
                  subject varchar(255) NOT NULL default '',
                  summary varchar(255) NOT NULL default '',
                  article longtext NOT NULL,
                  views int(50) NOT NULL default '0',
                  numvote smallint(11) NOT NULL default '0',
                  totalvotes int(50) NOT NULL default '0',
                  totalcomments int(50) NOT NULL default '0',
                  uniqid varchar(100) NOT NULL default '',
                  validated tinyint(1) NOT NULL default '1',
                  featured tinyint(1) NOT NULL default '0',
                  autobr tinyint(1) NOT NULL default '1',
                  style varchar(100) NOT NULL default '0',
                  meta_key varchar(255) NOT NULL default '',
                  meta_desc varchar(255) NOT NULL default '',
                  usecomment tinyint(1) NOT NULL default '1',
                  userating tinyint(1) NOT NULL default '1',
                  artimg varchar(250) NOT NULL default '',
                  related varchar(250) NOT NULL default '',
                  custom_1 varchar(255) NOT NULL default '',
                  PRIMARY KEY  (id)
                ) TYPE=MyISAM
                ");

                $udb->query("INSERT INTO ".$database['article_article']." VALUES (1, 1, 1, '".time()."', 'Welcome to evoArticles', 'All summaries are belong to us :)', 'This is an article test', 0, 0, 0, 0, 'E5132144', 1, 0, 0, '', '', '', 1, 1, '', '', '')");


                $udb->query("DROP TABLE IF EXISTS ".$database['article_field']);
                $udb->query("CREATE TABLE ".$database['article_field']." (
                  fieldid int(11) NOT NULL auto_increment,
                  name varchar(100) NOT NULL default '',
                  description varchar(255) NOT NULL default '',
                  required tinyint(1) NOT NULL default '0',
                  type varchar(100) NOT NULL default '',
                  orders smallint(11) NOT NULL default '0',
                  PRIMARY KEY  (fieldid)
                ) TYPE=MyISAM");

                $udb->query("INSERT INTO ".$database['article_field']." VALUES (1, 'Sources', 'Is there another source creditable for this article?', 0, 'text', 0)");

                $udb->query("DROP TABLE IF EXISTS ".$database['article_cat']);
                $udb->query("CREATE TABLE ".$database['article_cat']." (
                  cid smallint(11) NOT NULL auto_increment,
                  pid smallint(11) NOT NULL default '0',
                  name varchar(255) NOT NULL default '',
                  description varchar(255) NOT NULL default '',
                  cat_image varchar(255) NOT NULL default '',
                  style varchar(100) NOT NULL default '0',
                  meta_key varchar(255) NOT NULL default '',
                  meta_desc varchar(255) NOT NULL default '',
                  usecomment tinyint(1) NOT NULL default '1',
                  PRIMARY KEY  (cid),
                  FULLTEXT KEY description (description)
                ) TYPE=MyISAM");

                $udb->query("INSERT INTO ".$database['article_cat']." VALUES (1, 0, 'Test Category 1', 'Test Category Description', '', '', '', '', 1)");


                $udb->query("DROP TABLE IF EXISTS ".$database['article_catfield']);
                $udb->query("CREATE TABLE ".$database['article_catfield']." (
                  fieldid int(11) NOT NULL auto_increment,
                  name varchar(100) NOT NULL default '',
                  description varchar(255) NOT NULL default '',
                  required tinyint(1) NOT NULL default '0',
                  type varchar(100) NOT NULL default '',
                  orders smallint(11) NOT NULL default '0',
                  PRIMARY KEY  (fieldid)
                ) TYPE=MyISAM");

                $udb->query("DROP TABLE IF EXISTS ".$database['article_comment']);
                $udb->query("CREATE TABLE ".$database['article_comment']." (
                  id smallint(11) NOT NULL auto_increment,
                  artid smallint(11) default NULL,
                  name varchar(255) NOT NULL default '',
                  comment mediumtext NOT NULL,
                  ip varchar(50) NOT NULL default '',
                  email varchar(100) NOT NULL default '',
                  date int(50) NOT NULL default '0',
                  approved tinyint(1) NOT NULL default '1',
                  PRIMARY KEY  (id)
                ) TYPE=MyISAM");

                $udb->query("DROP TABLE IF EXISTS ".$database['article_image']);
                $udb->query("CREATE TABLE ".$database['article_image']." (
                 id smallint(11) NOT NULL auto_increment,
                  aid varchar(100) NOT NULL default '0',
                  loc varchar(255) NOT NULL default '',
                  description varchar(100) NOT NULL default '',
                  align varchar(100) NOT NULL default '',
                  PRIMARY KEY  (id)
                ) TYPE=MyISAM
                ");

                $udb->query("DROP TABLE IF EXISTS ".$database['article_styles']);
                $udb->query("CREATE TABLE ".$database['article_styles']." (
                 id smallint(11) NOT NULL auto_increment,
                  name varchar(100) NOT NULL default '',
                  tplfolder varchar(100) NOT NULL default '',
                  isdefault smallint(11) NOT NULL default '0',
                  bgcolor varchar(100) NOT NULL default '',
                  fontcolor varchar(100) NOT NULL default '',
                  subfontcolor varchar(100) NOT NULL default '',
                  fontfamily varchar(100) NOT NULL default '',
                  fontsize varchar(10) NOT NULL default '',
                  link varchar(100) NOT NULL default '',
                  linkvisited varchar(100) NOT NULL default '',
                  linkhover varchar(100) NOT NULL default '',
                  firstalt varchar(100) NOT NULL default '',
                  secondalt varchar(100) NOT NULL default '',
                  thirdalt varchar(100) NOT NULL default '',
                  tblborder varchar(100) NOT NULL default '',
                  tabletitlebgcolor varchar(100) NOT NULL default '',
                  tabletitlefontcolor varchar(100) NOT NULL default '',
                  PRIMARY KEY  (id)
                ) TYPE=MyISAM");

                $udb->query("INSERT INTO ".$database['article_styles']." VALUES (1, 'Default', 'default', 1, '#ffffff', '#000000', '#990000', 'Verdana, Arial, Helvetica, sans-serif', '10px', '#0000FF', '#5A267F', '#000000', '#F4F4F4', '#F2F2EB', '#BABAAC', '#A8A89A', '#990000', '#ffffff')");

                $udb->query("DROP TABLE IF EXISTS ".$database['article_support']);
                $udb->query("CREATE TABLE ".$database['article_support']." (
                  id smallint(11) NOT NULL auto_increment,
                  mime varchar(100) NOT NULL default '',
                  loc varchar(255) NOT NULL default '',
                  downloads int(50) NOT NULL default '0',
                  aid smallint(11) NOT NULL default '0',
                  PRIMARY KEY  (id)
                ) TYPE=MyISAM
                ");

                $udb->query("DROP TABLE IF EXISTS ".$database['article_settings']);
                $udb->query("CREATE TABLE ".$database['article_settings']." (
                                  id int(11) NOT NULL auto_increment,
                                  oid int(11) NOT NULL default '0',
                                  varname varchar(100) NOT NULL default '',
                                  value text NOT NULL,
                                  defvalue text NOT NULL,
                                  name varchar(255) NOT NULL default '',
                                  description varchar(255) NOT NULL default '',
                                  type varchar(100) NOT NULL default '',
                                  orders int(11) NOT NULL default '0',
                                  PRIMARY KEY  (id)
                                ) TYPE=MyISAM");

                $this->settings_process();

                $udb->query("DROP TABLE IF EXISTS ".$database['article_sgroup']);
                $udb->query("CREATE TABLE ".$database['article_sgroup']." (
                  sid smallint(11) NOT NULL auto_increment,
                  name varchar(100) NOT NULL default '',
                  description varchar(255) NOT NULL default '',
                  orders smallint(11) default '0',
                  PRIMARY KEY  (sid)
                ) TYPE=MyISAM");


                $udb->query("INSERT INTO ".$database['article_sgroup']." VALUES (2, 'Main', 'bleh', 3)");
                $udb->query("INSERT INTO ".$database['article_sgroup']." VALUES (16, 'Article Image', 'Article Image settings', 0)");
                $udb->query("INSERT INTO ".$database['article_sgroup']." VALUES (7, 'Articles', 'some article related options', 1)");
                $udb->query("INSERT INTO ".$database['article_sgroup']." VALUES (9, 'Avatars', 'Little images for authors', 0)");
                $udb->query("INSERT INTO ".$database['article_sgroup']." VALUES (10, 'RSS Feed', 'RSS Feed settings\r\n', 0)");
                $udb->query("INSERT INTO ".$database['article_sgroup']." VALUES (11, 'Location', 'folders path and etc.', 2)");
                $udb->query("INSERT INTO ".$database['article_sgroup']." VALUES (12, 'Sessions & Cookies', 'Sessions & Cookies stuff', 0)");
                $udb->query("INSERT INTO ".$database['article_sgroup']." VALUES (13, 'Status', 'status of the script', 4)");
                $udb->query("INSERT INTO ".$database['article_sgroup']." VALUES (14, 'Custom Meta Tag', 'Meta tag & Keywords', 0)");
                $udb->query("INSERT INTO ".$database['article_sgroup']." VALUES (15, 'Comments System', 'Comments Configuration', 0)");

                $udb->query("DROP TABLE IF EXISTS ".$database['article_user']);
                $udb->query("CREATE TABLE ".$database['article_user']." (
                  id int(11) NOT NULL auto_increment,
                  groupid int(11) NOT NULL default '0',
                  username varchar(100) NOT NULL default '',
                  password varchar(100) NOT NULL default '',
                  email varchar(100) NOT NULL default '',
                  regdate int(50) NOT NULL default '0',
                  custom_1 varchar(255) NOT NULL default '',
                  avatar varchar(100) NOT NULL default '',
                  PRIMARY KEY  (id)
                ) TYPE=MyISAM");

                $udb->query("DROP TABLE IF EXISTS ".$database['article_userfield']);
                $udb->query("CREATE TABLE ".$database['article_userfield']." (
                 fieldid int(11) NOT NULL auto_increment,
                  name varchar(100) NOT NULL default '',
                  description varchar(100) NOT NULL default '',
                  required tinyint(1) NOT NULL default '0',
                  type varchar(100) NOT NULL default '',
                  orders smallint(11) NOT NULL default '0',
                  PRIMARY KEY  (fieldid)
                ) TYPE=MyISAM");

                 $udb->query("INSERT INTO ".$database['article_userfield']." VALUES (1, 'Short Information', 'A brief description of author', 0, 'textarea', 0)");


                $udb->query("DROP TABLE IF EXISTS ".$database['article_usergroup']);
                $udb->query("CREATE TABLE ".$database['article_usergroup']." (
                 gid int(11) NOT NULL auto_increment,
                  name varchar(50) NOT NULL default '',
                  canapprove tinyint(1) NOT NULL default '0',
                  canpost tinyint(1) NOT NULL default '0',
                  candelete tinyint(1) NOT NULL default '0',
                  isadmin tinyint(1) NOT NULL default '0',
                  reqvalidation tinyint(1) NOT NULL default '0',
                  showbox tinyint(1) NOT NULL default '0',
                  editown tinyint(1) NOT NULL default '0',
                  editall tinyint(1) NOT NULL default '0',
                  PRIMARY KEY  (gid)
                ) TYPE=MyISAM");

                $udb->query("INSERT INTO ".$database['article_usergroup']." VALUES (1, 'Administrator', 1, 1, 1, 1, 0, 1, 1, 1)");
                $udb->query("INSERT INTO ".$database['article_usergroup']." VALUES (2, 'Editor', 1, 1, 1, 0, 0, 0, 1, 1)");
                $udb->query("INSERT INTO ".$database['article_usergroup']." VALUES (3, 'In-House Writer', 0, 1, 0, 0, 0, 0, 1, 0)");
                $udb->query("INSERT INTO ".$database['article_usergroup']." VALUES (4, 'Writer', 0, 1, 0, 0, 1, 0, 1, 0)");
                //$udb->query("INSERT INTO ".$database['article_usergroup']." VALUES (5, 'Guest', 0, 0, 0, 0, 1, 0, 0, 0)");

                $udb->query("DROP TABLE IF EXISTS ".$database['article_access']);
                $udb->query("CREATE TABLE ".$database['article_access']." (
                          userid int(50) NOT NULL default '0',
                          cat_id varchar(255) NOT NULL default '0'
                        ) TYPE=MyISAM");

                /* -------------------------------------------------------------------------------- */

                $a .= "<br /><hr size=\"1\" />".$this->unpacker("templates/styles/");
                $a .= "<br /><b>Proceed to next step</b><br />";
                $a .=  $this->makenext("Step 2",$_SERVER[PHP_SELF]."?do=step2");
                return $a;
        }
        function step2()
        {
                global $PHP_SELF,$_GET,$_POST,$_SERVER;
                $a .= $this->text("Step 3 - Admin Account");

                $url = str_replace(basename($_SERVER['HTTP_REFERER']),"index.php",$_SERVER['HTTP_REFERER']);

                $a .= "<script language=\"javascript\">
                                function Check_Form()
                                {
                                if (document.form_1.name.value == \"\" )
                                {
                                alert(\"Invalid Value: Username\");
                                return false;
                                }
                                if (document.form_1.pass.value == \"\" )
                                {
                                alert(\"Invalid Value: Password\");
                                return false;
                                }
                                if (document.form_1.email.value == \"\" )
                                {
                                alert(\"Invalid Value: Email\");
                                return false;
                                }
                                if (document.form_1.pass.value != document.form_1.cpass.value )
                                {
                                alert(\"Password does not match\");
                                return false;
                                }

                                return true;
                                }
                                </script>";
                $a .= "<form method='post' action='$PHP_SELF?do=step3' name='form_1' onsubmit='return Check_Form();'>";
                $a .= "<table border=0 cellpadding=0 cellspacing=0 width=80% align=center>
                                <tr><td class=tblborder>
                                <table border=0 cellspacing=1 cellpadding=4 width=100% align=center>
                                <tr><td colspan=2 class=tblhead> Admin Account</td></tr>
                                <tr class=\"secondalt\">
                                  <td><b>Username </b></td>
                                  <td><input type=\"text\" name=\"name\" size=\"30\"></td>
                                </tr>
                                <tr class=\"secondalt\">
                                  <td><b>Password </b></td>
                                  <td><input type=\"password\" name=\"pass\" size=\"30\"></td>
                                </tr>
                                <tr class=\"secondalt\">
                                  <td><b>Confirm Password </b></td>
                                  <td><input type=\"password\" name=\"cpass\" size=\"30\"></td>
                                </tr>
                                <tr class=\"secondalt\">
                                  <td><b>Email </b><br /> Email</td>
                                  <td><input type=\"text\" name=\"email\" size=\"30\"></td>
                                </tr>
                                <tr><td colspan=2 align=center class=tblhead><input type=\"submit\" value=\"Proceed\"></td></tr>
                                </table>
                                </td></tr></table>";

                return $a;
        }

        function step3()
        {
                global $PHP_SELF,$_GET,$_POST,$_SERVER,$database,$udb,$tpl;

                $udb->query("INSERT INTO $database[article_user] VALUES (1, 1, '$_POST[name]', '".md5($_POST['pass'])."', '".$_POST['email']."', ".time().", '','')");

                $a .= "<br /><b>Proceed to next step</b><br />";
                $a .=  $this->makenext("Step 4",$_SERVER[PHP_SELF]."?do=step4");
                return $a;
        }

        function step4()
        {
                global $PHP_SELF,$_SERVER,$admin;
                $a .= $this->text("Step 4");

                $a .= "Ok. Done installing. You can log into the <b><a href=\"admin/index.php\">Admin Panel</a></b> using your Username & Password. Please delete the setup.php before proceeding.";

                $admin->write_file($this->file_lock,"moooooo... said the cow");
                $get = $admin->get_file($this->ins_lock);

                return $a;
        }
}

$set = new Setup;

if (!file_exists($set->chmod_lock))
{
        $content = $set->do_main();
        $set->bypass=1;
}
else
{
        if ($_POST['license'])
        {
                if ($lc == '1')
                {
                        $content = 'Our record tells us that you have already installed this script on another place/domain or that your license is invalid. Please send an email to <a href="mailto:support@evo-dev.com">support@evo-dev.com</a> if this information is incorrect';
                                $set->skip = 1;
                }
                else
                {
                        $admin->write_file($set->ins_lock,$_POST['l']);
                }
        }
        else
        {
                if (!file_exists($set->ins_lock))
                {
                        $content = $set->lxc();
                        $set->skip=1;
                }
                else
                {
                        if (file_exists($set->file_lock))
                        {
                                $get = $admin->get_file($set->ins_lock);
                                if ($lc == '1')
                                {
                                        $content = 'Our record tells us that you have already installed this script on another place/domain or that your license is invalid. Please send an email to <a href="mailto:support@evo-dev.com">support@evo-dev.com</a> if this information is incorrect';
                                        $set->skip = 1;
                                }
                        }
                }
        }
}

if ($set->bypass != 1)
{
        if (file_exists($set->file_lock))
        {
                $content = $admin->warning("This script has been installed. Please remove <b>out/installed.lock</b> to reinstall");
        }
        else
        {
                if ($set->skip != 1)
                {
                        switch($_REQUEST['do'])
                        {
                                /* ----------------------------- */
                                case "clear":
                                        $content .= $set->cleardb();
                                break;
                                /* ----------------------------- */
                                case "update":
                                        $content .= $set->do_update();
                                break;
                                case "step1":
                                        $content .= $set->step1();
                                break;
                                case "step2":
                                        $content .= $set->step2();
                                break;
                                case "step3":
                                        $content .= $set->step3();
                                break;
                                case "step4":
                                        $content .= $set->step4();
                                break;
                                case "removedir":
                                        $content .= $set->deldir($HTTP_GET_VARS['thedir']);
                                break;
                                case "upgrade":
                                        $content .= $set->upgrade();
                                break;
                                case "unpack":
                                        $set->unpacker("templates/styles/");
                                break;
                                /* ----------------------------- */
                                default;
                                        $content = $admin->link_button('Install evoArticles',$_SERVER['PHP_SELF'].'?do=step1');
                        }
                }
        }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><? echo $site['name'] ."-". $site[title] ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<style type="text/css">
body
{
        margin:0px;
        font-family:Verdana, Arial, Helvetica, sans-serif;
        font-size:10px;
        background-color:#EFEFEF;
}

#maintable
{
        border:1px solid #B2B2B2;
}

a:link                {color: #064259; text-decoration: none}
a:visited        {color: #064259; text-decoration: none}
a:hover                {color: #064259; text-decoration: underline}
a:active        {color: #064259;}

tr,td,p,li,ul,ol,form
{

        FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif;
        FONT-SIZE: 10px;
}

h1,h2,h3,h4,h5,h6
{
   FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif;
}



img {
        border-style: none;
}


/* -- first alternating cell color -- */
.firstalt
{
        background-color: #eeeeee;
        color: #000000;
}

/* -- second alternating cell color -- */
.secondalt
{
        background-color: #F8F8F8;
        color: #000000;
}

/* -- third alternating cell color -- */
.thrdalt
{
        background-color: #fefefe; color: #000000;
}

/* -- table title  -- */
.tblhead
{
        color:#666;
        text-decoration:none;
        font-size: 10px;
        font-weight: bold;
        background-image:url(images/trbg.gif);
}

/* -- table border / border color -- */
.tblborder  { background-color: #B2B2B2; }


/* -- additional stuff-- */
.red
{
        color: red;
}

.green
{
        color: green;
}
</style>
</head>
<body>
<br /><br />
<table width="780" border="0" cellpadding="0" cellspacing="0" align="center" id="maintable">
        <tr>
                <td rowspan="2">
                        <img src="images/setup_01.gif" width="341" height="130" alt="" /></td>
                <td rowspan="2">
                        <img src="images/setup_02.gif" width="168" height="130" alt="" /></td>
                <td style="background-image:url(images/setup_03.gif);width:271px;height:61px">
                <!-- top -->

                <!-- /top -->
                </td>
        </tr>
        <tr>
                <td>
                        <img src="images/setup_04.gif" width="271" height="69" alt="" /></td>
        </tr>
        <tr>
            <td colspan="3" style="padding:10px;background-color:#ffffff;border-top:1px dashed #efefef" valign="top">
                <br /><br />
                <!-- content -->
                <? echo $content ?>
                <!-- /content -->
                <br /><br />
                </td>
        </tr>
        <tr>
            <td colspan="3" style="padding:5px;" align="right">
                <? echo $credits ?>
                </td>
        </tr>
</table>
</body>
</html>