<?php

/*****************************************
* File      :   upgrade.php
* Project   :   Contenido
* Descr     :   Contenido upgrade script
*
* Authors   :   Timo A. Hummel
*
* Created   :   20.06.2003
* Modified  :   20.06.2003
*
* © four for business AG, www.4fb.de
******************************************/

$cfg["path"]["classes"] = getcwd() . "/classes/";
$cfg["path"]["includes"] = getcwd() . "/includes/";
$cfg["path"]["conlib"] = getcwd() . "/../conlib/";

include_once ($cfg["path"]["conlib"] . 'prepend.php3');
include_once ($cfg["path"]["includes"] . 'cfg_sql.inc.php');
include_once ($cfg["path"]["includes"] . 'functions.general.php');
include_once ($cfg["path"]["includes"] . 'functions.str.php');
include_once ($cfg["path"]["includes"] . 'functions.con.php');
include_once ($cfg["path"]["includes"] . 'functions.database.php');
include_once ($cfg["path"]["conlib"] . 'local.php');

class DB_Upgrade extends DB_Sql {

  var $Host;
  var $Database;
  var $User;
  var $Password;

  var $Halt_On_Error = "report";

  //Konstruktor
  function DB_Upgrade()
  {
  	  global $setup_host, $setup_database, $setup_user, $setup_password;
      $this -> Host = $setup_host;
      $this -> Database = $setup_database;
      $this -> User = $setup_user;
      $this -> Password = $setup_password;
  }

  function haltmsg($msg) {
    $fp = fopen("logs/install.log.txt", "a+");
    
    if (!$fp)
    {
    	die("Could not open file install.log.txt in directory ".getcwd());
	}
    $msg = sprintf("%s: error %s (%s) - %s\n",
      date("Y-M-D H:i:s"),
      $this->Errno,
      $this->Error,
      $msg);
     echo $msg;
    fputs($fp, $msg);
    fclose($fp);
  }
  
  function copyResultToArray ()
  {
  		$values = array();
  		
  		$metadata = $this->metadata();
		
		if (!is_array($metadata))
		{
			return false;
		}
		
		foreach ($metadata as $entry)
		{
			$values[$entry['name']] = $this->f($entry['name']);
		}
		
		return $values;
  }
}




# Create Contenido classes
//$notification = new Contenido_Notification;

dbUpgradeTable($prefix."_art", 'idart', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_art", 'idclient', 'int(10)', '', '', '0', '','');

dbUpgradeTable($prefix."_art_lang", 'idartlang', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_art_lang", 'idart', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_art_lang", 'idlang', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_art_lang", 'idtplcfg', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_art_lang", 'title', 'varchar(255)', '', '', '', '','');
dbUpgradeTable($prefix."_art_lang", 'pagetitle', 'varchar(255)', '', '', '', '','');
dbUpgradeTable($prefix."_art_lang", 'summary', 'text', 'YES', '', '', '','');
dbUpgradeTable($prefix."_art_lang", 'created', 'datetime', '', '', '0000-00-00 00:00:00', '','');
dbUpgradeTable($prefix."_art_lang", 'lastmodified', 'datetime', '', '', '0000-00-00 00:00:00', '','');
dbUpgradeTable($prefix."_art_lang", 'author', 'varchar(32)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_art_lang", 'modifiedby', 'varchar(32)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_art_lang", 'online', 'tinyint(1)', '', '', '0', '','');
dbUpgradeTable($prefix."_art_lang", 'redirect', 'int(6)', '', '', '0', '','');
dbUpgradeTable($prefix."_art_lang", 'redirect_url', 'varchar(255)', '', '', '', '','');
dbUpgradeTable($prefix."_art_lang", 'artsort', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_art_lang", 'timemgmt', 'tinyint(1)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_art_lang", 'datestart', 'datetime', 'YES', '', '', '','');
dbUpgradeTable($prefix."_art_lang", 'dateend', 'datetime', 'YES', '', '', '','');
dbUpgradeTable($prefix."_art_lang", 'status', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_art_lang", 'free_use_01', 'mediumint(7)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_art_lang", 'free_use_02', 'mediumint(7)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_art_lang", 'free_use_03', 'mediumint(7)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_art_lang", 'time_move_cat', 'mediumint(7)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_art_lang", 'time_target_cat', 'mediumint(7)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_art_lang", 'time_online_move', 'mediumint(7)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_art_lang", 'external_redirect', 'char(1)', '', '', '', '','');
dbUpgradeTable($prefix."_art_lang", 'locked', 'int(1)', '', '', '0', '','');

dbUpgradeTable($prefix."_cat", 'idcat', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_cat", 'idclient', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_cat", 'parentid', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_cat", 'preid', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_cat", 'postid', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_cat", 'status', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_cat", 'author', 'varchar(32)', '', '', '', '','');
dbUpgradeTable($prefix."_cat", 'created', 'datetime', '', '', '0000-00-00 00:00:00', '','');
dbUpgradeTable($prefix."_cat", 'lastmodified', 'datetime', '', '', '0000-00-00 00:00:00', '','');

dbUpgradeTable($prefix."_cat_art", 'idcatart', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_cat_art", 'idcat', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_cat_art", 'idart', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_cat_art", 'is_start', 'tinyint(1)', '', '', '0', '','');
dbUpgradeTable($prefix."_cat_art", 'status', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_cat_art", 'author', 'varchar(32)', '', '', '', '','');
dbUpgradeTable($prefix."_cat_art", 'created', 'datetime', '', '', '0000-00-00 00:00:00', '','');
dbUpgradeTable($prefix."_cat_art", 'lastmodified', 'datetime', '', '', '0000-00-00 00:00:00', '','');
dbUpgradeTable($prefix."_cat_art", 'createcode', 'tinyint(1)', '', '', '1', '','');

dbUpgradeTable($prefix."_cat_tree", 'idtree', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_cat_tree", 'idcat', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_cat_tree", 'level', 'int(2)', '', '', '0', '','');

dbUpgradeTable($prefix."_cat_lang", 'idcatlang', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_cat_lang", 'idcat', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_cat_lang", 'idlang', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_cat_lang", 'idtplcfg', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_cat_lang", 'name', 'varchar(255)', '', '', '', '','');
dbUpgradeTable($prefix."_cat_lang", 'visible', 'tinyint(1)', '', '', '0', '','');
dbUpgradeTable($prefix."_cat_lang", 'public', 'tinyint(1)', '', '', '0', '','');
dbUpgradeTable($prefix."_cat_lang", 'status', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_cat_lang", 'author', 'varchar(32)', '', '', '', '','');
dbUpgradeTable($prefix."_cat_lang", 'created', 'datetime', '', '', '0000-00-00 00:00:00', '','');
dbUpgradeTable($prefix."_cat_lang", 'lastmodified', 'datetime', '', '', '0000-00-00 00:00:00', '','');

dbUpgradeTable($prefix."_clients", 'idclient', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_clients", 'name', 'varchar(255)', '', '', '', '','');
dbUpgradeTable($prefix."_clients", 'author', 'varchar(32)', '', '', '', '','');
dbUpgradeTable($prefix."_clients", 'created', 'datetime', '', '', '0000-00-00 00:00:00', '','');
dbUpgradeTable($prefix."_clients", 'lastmodified', 'datetime', '', '', '0000-00-00 00:00:00', '','');
dbUpgradeTable($prefix."_clients", 'path', 'varchar(255)', '', '', '', '','');
dbUpgradeTable($prefix."_clients", 'frontendpath', 'varchar(255)', '', '', '', '','');
dbUpgradeTable($prefix."_clients", 'htmlpath', 'varchar(255)', '', '', '', '','');
dbUpgradeTable($prefix."_clients", 'errsite_cat', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_clients", 'errsite_art', 'int(10)', '', '', '0', '','');

dbUpgradeTable($prefix."_clients_lang", 'idclientslang', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_clients_lang", 'idclient', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_clients_lang", 'idlang', 'int(10)', '', '', '0', '','');

dbUpgradeTable($prefix."_code", 'idcode', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_code", 'idcatart', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_code", 'idlang', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_code", 'idclient', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_code", 'code', 'text', '', '', '', '','');

dbUpgradeTable($prefix."_content", 'idcontent', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_content", 'idartlang', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_content", 'idtype', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_content", 'typeid', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_content", 'value', 'text', '', '', '', '','');
dbUpgradeTable($prefix."_content", 'version', 'varchar(32)', '', '', '', '','');
dbUpgradeTable($prefix."_content", 'author', 'varchar(32)', '', '', '', '','');
dbUpgradeTable($prefix."_content", 'created', 'datetime', '', '', '0000-00-00 00:00:00', '','');
dbUpgradeTable($prefix."_content", 'lastmodified', 'datetime', '', '', '0000-00-00 00:00:00', '','');

dbUpgradeTable($prefix."_lang", 'idlang', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_lang", 'name', 'varchar(255)', '', '', '', '','');
dbUpgradeTable($prefix."_lang", 'active', 'tinyint(1)', '', '', '0', '','');
dbUpgradeTable($prefix."_lang", 'author', 'varchar(32)', '', '', '', '','');
dbUpgradeTable($prefix."_lang", 'created', 'datetime', '', '', '0000-00-00 00:00:00', '','');
dbUpgradeTable($prefix."_lang", 'lastmodified', 'datetime', '', '', '0000-00-00 00:00:00', '','');
dbUpgradeTable($prefix."_lang", 'encoding', 'varchar(32)', '', '', '', '','');

dbUpgradeTable($prefix."_lay", 'idlay', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_lay", 'idclient', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_lay", 'name', 'varchar(255)', '', '', '', '','');
dbUpgradeTable($prefix."_lay", 'description', 'text', 'YES', '', '', '','');
dbUpgradeTable($prefix."_lay", 'deletable', 'tinyint(1)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_lay", 'code', 'text', '', '', '', '','');dbUpgradeTable($prefix."_lay", 'author', 'varchar(32)', '', '', '', '','');
dbUpgradeTable($prefix."_lay", 'created', 'datetime', '', '', '0000-00-00 00:00:00', '','');
dbUpgradeTable($prefix."_lay", 'lastmodified', 'datetime', '', '', '0000-00-00 00:00:00', '','');

dbUpgradeTable($prefix."_mod", 'idmod', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_mod", 'idclient', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_mod", 'name', 'varchar(255)', '', '', '', '','');
dbUpgradeTable($prefix."_mod", 'description', 'text', 'YES', '', '', '','');
dbUpgradeTable($prefix."_mod", 'deletable', 'tinyint(1)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_mod", 'input', 'longtext', '', '', '', '','');
dbUpgradeTable($prefix."_mod", 'output', 'longtext', '', '', '', '','');
dbUpgradeTable($prefix."_mod", 'template', 'text', '', '', '', '','');
dbUpgradeTable($prefix."_mod", 'static', 'tinyint(1)', '', '', '0', '','');
dbUpgradeTable($prefix."_mod", 'author', 'varchar(32)', '', '', '', '','');
dbUpgradeTable($prefix."_mod", 'created', 'datetime', '', '', '0000-00-00 00:00:00', '','');
dbUpgradeTable($prefix."_mod", 'lastmodified', 'datetime', '', '', '0000-00-00 00:00:00', '','');

dbUpgradeTable($prefix."_news", 'idnews', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_news", 'idart', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_news", 'idclient', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_news", 'idlang', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_news", 'name', 'varchar(255)', '', '', '', '','');
dbUpgradeTable($prefix."_news", 'subject', 'text', 'YES', '', '', '','');
dbUpgradeTable($prefix."_news", 'message', 'text', 'YES', '', '', '','');
dbUpgradeTable($prefix."_news", 'newsfrom', 'varchar(255)', '', '', '', '','');
dbUpgradeTable($prefix."_news", 'newsdate', 'datetime', 'YES', '', '', '','');
dbUpgradeTable($prefix."_news", 'author', 'varchar(32)', '', '', '', '','');
dbUpgradeTable($prefix."_news", 'created', 'datetime', '', '', '0000-00-00 00:00:00', '','');
dbUpgradeTable($prefix."_news", 'lastmodified', 'datetime', '', '', '0000-00-00 00:00:00', '','');

dbUpgradeTable($prefix."_news_rcp", 'idnewsrcp', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_news_rcp", 'idclient', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_news_rcp", 'idlang', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_news_rcp", 'email', 'varchar(255)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_news_rcp", 'name', 'varchar(255)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_news_rcp", 'deactivated', 'int(1)', '', '', '0', '','');
dbUpgradeTable($prefix."_news_rcp", 'author', 'varchar(32)', '', '', '', '','');
dbUpgradeTable($prefix."_news_rcp", 'created', 'datetime', '', '', '0000-00-00 00:00:00', '','');
dbUpgradeTable($prefix."_news_rcp", 'lastmodified', 'datetime', '', '', '0000-00-00 00:00:00', '','');

dbUpgradeTable($prefix."_stat", 'idstat', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_stat", 'idcatart', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_stat", 'idlang', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_stat", 'idclient', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_stat", 'visited', 'int(6)', '', '', '0', '','');
dbUpgradeTable($prefix."_stat", 'visitdate', 'timestamp(14)', 'YES', '', '', '','');

dbUpgradeTable($prefix."_stat_archive", 'idstatarch', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_stat_archive", 'archived', 'varchar(6)', '', '', '', '','');
dbUpgradeTable($prefix."_stat_archive", 'idcatart', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_stat_archive", 'idlang', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_stat_archive", 'idclient', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_stat_archive", 'visited', 'int(6)', '', '', '0', '','');
dbUpgradeTable($prefix."_stat_archive", 'visitdate', 'timestamp(14)', 'YES', '', '', '','');

dbUpgradeTable($prefix."_status", 'idstatus', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_status", 'description', 'varchar(255)', '', '', '', '','');
dbUpgradeTable($prefix."_status", 'author', 'varchar(32)', '', '', '', '','');
dbUpgradeTable($prefix."_status", 'created', 'datetime', '', '', '0000-00-00 00:00:00', '','');
dbUpgradeTable($prefix."_status", 'lastmodified', 'datetime', '', '', '0000-00-00 00:00:00', '','');

dbUpgradeTable($prefix."_template", 'idclient', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_template", 'idlay', 'int(10)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_template", 'idtpl', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_template", 'idtplcfg', 'int(10)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_template", 'name', 'varchar(255)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_template", 'description', 'varchar(255)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_template", 'deletable', 'tinyint(1)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_template", 'status', 'int(10)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_template", 'author', 'varchar(32)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_template", 'created', 'timestamp(14)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_template", 'lastmodified', 'timestamp(14)', 'YES', '', '', '','');

dbUpgradeTable($prefix."_template_conf", 'idtplcfg', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_template_conf", 'idtpl', 'int(10)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_template_conf", 'status', 'int(10)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_template_conf", 'author', 'varchar(255)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_template_conf", 'created', 'timestamp(14)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_template_conf", 'lastmodified', 'timestamp(14)', 'YES', '', '', '','');

dbUpgradeTable($prefix."_type", 'idtype', 'int(6)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_type", 'type', 'varchar(255)', '', '', '', '','');
dbUpgradeTable($prefix."_type", 'code', 'text', '', '', '', '','');
dbUpgradeTable($prefix."_type", 'description', 'varchar(255)', '', '', '', '','');
dbUpgradeTable($prefix."_type", 'status', 'int(11)', '', '', '0', '','');
dbUpgradeTable($prefix."_type", 'author', 'varchar(32)', '', '', '', '','');
dbUpgradeTable($prefix."_type", 'created', 'datetime', '', '', '0000-00-00 00:00:00', '','');
dbUpgradeTable($prefix."_type", 'lastmodified', 'datetime', '', '', '0000-00-00 00:00:00', '',''); 

dbUpgradeTable($prefix."_upl", 'idupl', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_upl", 'idclient', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_upl", 'filename', 'varchar(255)', '', '', '', '','');
dbUpgradeTable($prefix."_upl", 'dirname', 'varchar(255)', '', '', '', '','');
dbUpgradeTable($prefix."_upl", 'filetype', 'varchar(255)', '', '', '', '','');
dbUpgradeTable($prefix."_upl", 'size', 'varchar(255)', '', '', '', '','');
dbUpgradeTable($prefix."_upl", 'description', 'text', '', '', '', '','');
dbUpgradeTable($prefix."_upl", 'status', 'int(11)', '', '', '0', '','');
dbUpgradeTable($prefix."_upl", 'author', 'varchar(32)', '', '', '', '','');
dbUpgradeTable($prefix."_upl", 'created', 'datetime', '', '', '0000-00-00 00:00:00', '','');
dbUpgradeTable($prefix."_upl", 'lastmodified', 'datetime', '', '', '0000-00-00 00:00:00', '','');

dbUpgradeTable($prefix."_keywords", 'idkeyword', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_keywords", 'keyword', 'varchar(50)', '', '', '', '','');
dbUpgradeTable($prefix."_keywords", 'exp', 'text', '', '', '', '','');
dbUpgradeTable($prefix."_keywords", 'auto', 'text', '', '', '', '','');
dbUpgradeTable($prefix."_keywords", 'self', 'text', '', '', '', '','');
dbUpgradeTable($prefix."_keywords", 'idlang', 'int(10)', '', '', '0', '','');

dbUpgradeTable($prefix."_area", 'idarea', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_area", 'parent_id', 'varchar(255)', '', '', '0', '','');
dbUpgradeTable($prefix."_area", 'name', 'varchar(255)', '', '', '', '','');
dbUpgradeTable($prefix."_area", 'relevant', 'tinyint(1)', '', '', '0', '','');
dbUpgradeTable($prefix."_area", 'online', 'tinyint(1)', '', '', '0', '','');

dbUpgradeTable($prefix."_actions", 'idaction', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_actions", 'idarea', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_actions", 'alt_name', 'varchar(255)', '', '', '', '','');
dbUpgradeTable($prefix."_actions", 'name', 'varchar(255)', '', '', '', '','');
dbUpgradeTable($prefix."_actions", 'code', 'text', '', '', '', '','');
dbUpgradeTable($prefix."_actions", 'location', 'varchar(255)', '', '', '', '','');
dbUpgradeTable($prefix."_actions", 'relevant', 'tinyint(1)', '', '', '0', '','');

dbUpgradeTable($prefix."_nav_main", 'idnavm', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_nav_main", 'location', 'varchar(255)', '', '', '', '','');

dbUpgradeTable($prefix."_nav_sub", 'idnavs', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_nav_sub", 'idnavm', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_nav_sub", 'idarea', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_nav_sub", 'level', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_nav_sub", 'location', 'varchar(255)', '', '', '', '','');
dbUpgradeTable($prefix."_nav_sub", 'online', 'tinyint(1)', '', '', '0', '','');

dbUpgradeTable($prefix."_rights", 'idright', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_rights", 'user_id', 'varchar(32)', '', '', '', '','');
dbUpgradeTable($prefix."_rights", 'idarea', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_rights", 'idaction', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_rights", 'idcat', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_rights", 'idclient', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_rights", 'idlang', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_rights", 'type', 'tinyint(1)', '', '', '0', '','');

dbUpgradeTable($prefix."_container", 'idcontainer', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_container", 'idtpl', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_container", 'number', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_container", 'idmod', 'int(10)', 'YES', '', '', '','');

dbUpgradeTable($prefix."_container_conf", 'idcontainerc', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_container_conf", 'idtplcfg', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_container_conf", 'number', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_container_conf", 'container', 'text', 'YES', '', '', '','');

dbUpgradeTable($prefix."_files", 'idfile', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_files", 'idarea', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_files", 'filename', 'varchar(255)', '', '', '', '','');
dbUpgradeTable($prefix."_files", 'filetype', 'varchar(4)', '', '', 'main', '','');

dbUpgradeTable($prefix."_frame_files", 'idframefile', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_frame_files", 'idarea', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_frame_files", 'idframe', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_frame_files", 'idfile', 'int(10)', '', '', '0', '','');

dbUpgradeTable($prefix."_plugins", 'idplugin', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_plugins", 'idclient', 'int(10)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_plugins", 'name', 'varchar(255)', '', '', '', '','');
dbUpgradeTable($prefix."_plugins", 'description', 'text', '', '', '', '','');
dbUpgradeTable($prefix."_plugins", 'path', 'varchar(255)', '', '', '', '','');
dbUpgradeTable($prefix."_plugins", 'installed', 'tinyint(1)', '', '', '0', '','');
dbUpgradeTable($prefix."_plugins", 'active', 'tinyint(1)', 'YES', '', '', '','');

dbUpgradeTable($prefix."_phplib_active_sessions", 'sid', 'varchar(32)', '', 'PRI', '', '','');
dbUpgradeTable($prefix."_phplib_active_sessions", 'name', 'varchar(32)', '', '', '', '','');
dbUpgradeTable($prefix."_phplib_active_sessions", 'val', 'longblob', 'YES', '', '', '','');
dbUpgradeTable($prefix."_phplib_active_sessions", 'changed', 'varchar(14)', '', '', '', '','');

dbUpgradeTable($prefix."_phplib_auth_user_md5", 'user_id', 'varchar(32)', '', 'PRI', '', '','');
dbUpgradeTable($prefix."_phplib_auth_user_md5", 'username', 'varchar(32)', '', '', '', '','');
dbUpgradeTable($prefix."_phplib_auth_user_md5", 'password', 'varchar(32)', '', '', '', '','');
dbUpgradeTable($prefix."_phplib_auth_user_md5", 'perms', 'varchar(255)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_phplib_auth_user_md5", 'realname', 'varchar(255)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_phplib_auth_user_md5", 'email', 'varchar(255)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_phplib_auth_user_md5", 'telephone', 'varchar(255)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_phplib_auth_user_md5", 'address_street', 'varchar(255)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_phplib_auth_user_md5", 'address_zip', 'varchar(10)', '', '', '', '','');
dbUpgradeTable($prefix."_phplib_auth_user_md5", 'address_city', 'varchar(255)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_phplib_auth_user_md5", 'address_country', 'varchar(255)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_phplib_auth_user_md5", 'wysi', 'tinyint(2)', 'YES', '', '', '','');

dbUpgradeTable($prefix."_actionlog", 'idlog', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_actionlog", 'user_id', 'varchar(32)', '', '', '', '','');
dbUpgradeTable($prefix."_actionlog", 'idclient', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_actionlog", 'idlang', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_actionlog", 'idaction', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_actionlog", 'idcatart', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_actionlog", 'logtimestamp', 'datetime', 'YES', '', '0000-00-00 00:00:00', '','');

dbUpgradeTable($prefix."_link", 'idlink', 'int(6)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_link", 'idartlang', 'int(10)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_link", 'idcat', 'int(10)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_link", 'idart', 'int(10)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_link", 'linkpath', 'varchar(255)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_link", 'internal', 'tinyint(1)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_link", 'active', 'tinyint(1)', 'YES', '', '', '','');

dbUpgradeTable($prefix."_meta_type", 'idmetatype', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_meta_type", 'metatype', 'varchar(32)', '', '', '', '','');
dbUpgradeTable($prefix."_meta_type", 'fieldtype', 'varchar(32)', '', '', '', '','');
dbUpgradeTable($prefix."_meta_type", 'maxlength', 'int(11)', '', '', '0', '','');

dbUpgradeTable($prefix."_meta_tag", 'idmetatag', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_meta_tag", 'idartlang', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_meta_tag", 'idmetatype', 'int(10)', '', '', '0', '','');
dbUpgradeTable($prefix."_meta_tag", 'metavalue', 'text', '', '', '', '','');

dbUpgradeTable($prefix."_groups", 'group_id', 'varchar(32)', '', 'PRI', '', '','');
dbUpgradeTable($prefix."_groups", 'groupname', 'varchar(32)', '', '', '', '','');
dbUpgradeTable($prefix."_groups", 'perms', 'varchar(255)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_groups", 'description', 'varchar(255)', 'YES', '', '', '','');

dbUpgradeTable($prefix."_group_prop", 'idgroupprop', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_group_prop", 'group_id', 'varchar(32)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_group_prop", 'type', 'varchar(32)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_group_prop", 'name', 'varchar(32)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_group_prop", 'value', 'text', 'YES', '', '', '','');
dbUpgradeTable($prefix."_group_prop", 'idcatlang', 'int(11)', '', '', '0', '','');

dbUpgradeTable($prefix."_groupmembers", 'idgroupuser', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_groupmembers", 'group_id', 'varchar(32)', '', '', '', '','');
dbUpgradeTable($prefix."_groupmembers", 'user_id', 'varchar(32)', '', '', '', '','');

dbUpgradeTable($prefix."_config", 'idconfig', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_config", 'abs_path', 'varchar(255)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_config", 'url_path', 'varchar(255)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_config", 'css_path', 'varchar(255)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_config", 'js_path', 'varchar(255)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_config", 'filename', 'varchar(127)', 'YES', '', '', '','');

dbUpgradeTable($prefix."_config_client", 'idconfc', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_config_client", 'idclient', 'int(10)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_config_client", 'abs_path', 'varchar(255)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_config_client", 'url_path', 'varchar(255)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_config_client", 'css_path', 'varchar(255)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_config_client", 'js_path', 'varchar(255)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_config_client", 'filename', 'varchar(127)', 'YES', '', '', '','');

dbUpgradeTable($prefix."_data", 'iddata', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_data", 'idclient', 'int(10)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_data", 'abs_path', 'varchar(255)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_data", 'url_path', 'varchar(255)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_data", 'dir_hide', 'varchar(255)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_data", 'dir_not', 'varchar(255)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_data", 'ext_not', 'varchar(255)', 'YES', '', '', '','');

dbUpgradeTable($prefix."_lang_bereich", 'idbereich', 'int(4)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_lang_bereich", 'brvalue', 'char(40)', '', '', '', '','');
dbUpgradeTable($prefix."_lang_bereich", 'expanded', 'int(1)', '', '', '1', '','');
dbUpgradeTable($prefix."_lang_bereich", 'client', 'int(4)', '', '', '0', '','');

dbUpgradeTable($prefix."_lang_key", 'idkey', 'int(4)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_lang_key", 'valuekey', 'text', '', '', '', '','');
dbUpgradeTable($prefix."_lang_key", 'idbereich', 'int(4)', '', '', '0', '','');

dbUpgradeTable($prefix."_lang_value", 'idlangvalue', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_lang_value", 'idlangfile', 'int(4)', '', '', '0', '','');
dbUpgradeTable($prefix."_lang_value", 'value', 'text', '', '', '', '','');
dbUpgradeTable($prefix."_lang_value", 'idkey', 'int(4) unsigned', '', '', '0', '','');
dbUpgradeTable($prefix."_lang_value", 'idlang', 'int(4)', '', '', '0', '','');
dbUpgradeTable($prefix."_lang_value", 'idbereich', 'int(4)', '', '', '0', '','');

dbUpgradeTable($prefix."_sequence", 'seq_name', 'varchar(127)', '', 'PRI', '', '','');
dbUpgradeTable($prefix."_sequence", 'nextid', 'int(10)', '', '', '0', '','');

dbUpgradeTable($prefix."_user_prop", 'iduserprop', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_user_prop", 'user_id', 'varchar(32)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_user_prop", 'type', 'varchar(32)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_user_prop", 'name', 'varchar(32)', 'YES', '', '', '','');
dbUpgradeTable($prefix."_user_prop", 'value', 'text', 'YES', '', '', '','');
dbUpgradeTable($prefix."_user_prop", 'idcatlang', 'int(11)', '', '', '0', '','');

dbUpgradeTable($prefix."_inuse", 'idinuse', 'int(10)', '', 'PRI', '0', '','');
dbUpgradeTable($prefix."_inuse", 'type', 'varchar(32)', '', '', '', '','');
dbUpgradeTable($prefix."_inuse", 'objectid', 'varchar(32)', '', '', '', '','');
dbUpgradeTable($prefix."_inuse", 'session', 'varchar(32)', '', '', '', '','');
dbUpgradeTable($prefix."_inuse", 'userid', 'varchar(32)', '', '', '', '','');


//$result = $notification->returnNotification("info", "Datenbankstruktur ist jetzt up-to-date.");

?>
