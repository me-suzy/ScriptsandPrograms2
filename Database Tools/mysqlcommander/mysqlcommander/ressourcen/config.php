<?php 
$config->data_path = "./data/";
// check for superconfig
if (!isset($home)) $home = "./";
if (file_exists($home."ressourcen/class.superconfig.php")) 
	include $home."ressourcen/class.superconfig.php";
else {
	echo "Error: <font color='#800000'>Please start the <a href='./ressourcen/install/install.php'>Configuration</a> first!</font>";
	exit;
}

session_start();
if (isset($HTTP_GET_VARS['logout']) and $HTTP_GET_VARS['logout']==1) {
	$HTTP_SESSION_VARS['commander_user'] = "";
	$HTTP_SESSION_VARS['commander_pass'] = "";
}

if (isset($HTTP_POST_VARS["change_db"])) $HTTP_GET_VARS["change_db"] = $HTTP_POST_VARS["change_db"];
if (isset($HTTP_GET_VARS["change_db"]) and ($HTTP_GET_VARS["change_db"]>=0)) {
	$HTTP_SESSION_VARS['which_db'] = $HTTP_GET_VARS['change_db'];
}

if (!isset($HTTP_SESSION_VARS['commander_user'])) $HTTP_SESSION_VARS['commander_user'] = "";
if (!isset($HTTP_SESSION_VARS['commander_pass'])) $HTTP_SESSION_VARS['commander_pass'] = "";

if (!isset($HTTP_SESSION_VARS['which_db'])) $HTTP_SESSION_VARS['which_db'] = 1;
if (!isset($HTTP_SESSION_VARS['mysql_user']) or !$HTTP_SESSION_VARS['mysql_user']) $HTTP_SESSION_VARS['mysql_user'] = "";
if (!isset($HTTP_SESSION_VARS['mysql_pass']) or !$HTTP_SESSION_VARS['mysql_pass']) $HTTP_SESSION_VARS['mysql_pass'] = "";
if (!isset($HTTP_SESSION_VARS['mysql_server']) or !$HTTP_SESSION_VARS['mysql_server']) $HTTP_SESSION_VARS['mysql_server'] = "";

if (isset($HTTP_POST_VARS['mysql_user']) and $HTTP_POST_VARS['mysql_user']) $HTTP_SESSION_VARS['mysql_user'] = $HTTP_POST_VARS['mysql_user'];
if (isset($HTTP_POST_VARS['mysql_pass'])) $HTTP_SESSION_VARS['mysql_pass'] = $HTTP_POST_VARS['mysql_pass'];
if (isset($HTTP_POST_VARS['mysql_server']) and $HTTP_POST_VARS['mysql_server']) $HTTP_SESSION_VARS['mysql_server'] = $HTTP_POST_VARS['mysql_server'];

//echo "DB: ".$HTTP_SESSION_VARS['which_db'];

// get project_info
if (file_exists($home."./project_info.php")) $a_project_info = file($home."./project_info.php");
for ($i=0; $i<count($a_project_info); $i++) {
	$a_data = explode(";", $a_project_info[$i]);
	if (strtolower($a_data[0]) == "version") $commander_version = $a_data[1];
}

/***************************************************/

Class Config extends superConfig {
	function Config() {
		global $HTTP_SESSION_VARS;
		
		$this->SuperConfig();
		
		$this->dbtext = array();
		$this->dbuser = array();
		$this->dbpass = array();
		$this->dbserver = array();
		$this->dbase = array();
		$this->dbase_ex = array();
		
		$i=0;
		$this->dbtext[$i] = "Manual Server";
		$this->dbuser[$i] = $HTTP_SESSION_VARS['mysql_user'];
		$this->dbpass[$i] = $HTTP_SESSION_VARS['mysql_pass'];
		$this->dbserver[$i] = $HTTP_SESSION_VARS['mysql_server'];
		
		$i++;
		$this->dbtext[$i] = $this->mysql_databasename1;
		$this->dbuser[$i] = $this->mysql_user1;
		$this->dbpass[$i] = $this->mysql_password1;
		$this->dbserver[$i] = $this->mysql_server1;
		
		$i++;
		$this->dbtext[$i] = $this->mysql_databasename2;
		$this->dbuser[$i] = $this->mysql_user2;
		$this->dbpass[$i] = $this->mysql_password2;
		$this->dbserver[$i] = $this->mysql_server2;
		
		$i++;
		$this->dbtext[$i] = $this->mysql_databasename3;
		$this->dbuser[$i] = $this->mysql_user3;
		$this->dbpass[$i] = $this->mysql_password3;
		$this->dbserver[$i] = $this->mysql_server3;
		
		$i++;
		$this->dbtext[$i] = $this->mysql_databasename4;
		$this->dbuser[$i] = $this->mysql_user4;
		$this->dbpass[$i] = $this->mysql_password4;
		$this->dbserver[$i] = $this->mysql_server4;
		
		$i++;
		$this->dbtext[$i] = $this->mysql_databasename5;
		$this->dbuser[$i] = $this->mysql_user5;
		$this->dbpass[$i] = $this->mysql_password5;
		$this->dbserver[$i] = $this->mysql_server5;
		
		$i++;
		$this->dbtext[$i] = $this->mysql_databasename6;
		$this->dbuser[$i] = $this->mysql_user6;
		$this->dbpass[$i] = $this->mysql_password6;
		$this->dbserver[$i] = $this->mysql_server6;
		
		$this->dbase = explode(" ", $this->list_dbase);
		$this->dbase_ex = explode(" ", $this->not_list_dbase);
		
		$this->breite_menu = 150;
		$this->breite_inhalt = 580;
		$this->breite_rand = 15;
		global $home;
		$this->home = $home;
		$this->ressourcen = "ressourcen/";
		$this->filename = basename(getenv("SCRIPT_NAME"));
		if (substr($this->data_path, -1) != "/") $this->data_path ."/";
		
		$this->bgcolor = "#ebebeb";
		$this->maincolor = "#0F5F9F";
		$this->maincolor2 = "blau";
		
		global $commander_version;
		$this->commander_version = $commander_version;
		
		if (!isset($this->commander_user)) $this->commander_user = "";
		if (!isset($this->commander_pass)) $this->commander_pass = "";
		
		if (isset($GLOBALS["home"])) $this->home = $GLOBALS["home"];
		
		$this->menu[0] = array (
			0 => array("S E R V E R", "index.php"), 
			1 => array("M A N U A L", "manual.php", "What's new", "manual_whatsnew.php"), 
		);
		
		$this->menu[1] = array (
			0 => array("B A C K U P", "backup.php"), 
			1 => array("P A R A M E T E R", "backup_param.php"), 
			2 => array("B I G T A B L E", "backup_bigtable.php"), 
		);
		
		$this->menu[2] = array (
			0 => array("R E S T O R E", "restore.php"), 
			1 => array("D O W N L O A D", "download.php"), 
			2 => array("O P T I M I Z E", "optimize.php"), 
			3 => array("C R E A T E &nbsp; D B", "create.php"), 
			4 => array("D E L E T E &nbsp; D B", "delete.php"), 
		);
		
		$this->menu[3] = array (
			0 => array("K I L L &nbsp; F I L E S", "kill.php"), 
			1 => array("K I L L &nbsp; A L L", "del_data.php"), 
		);
		
		$this->menu[4] = array (
			0 => array("A D M I N", "update.php"), 
		);
		
		if (isset($this->commander_user) and $this->commander_user) $this->menu[3][] = array("L O G O U T", "index.php?logout=1");
	}
}

$config = new Config;

if ($config->language=="german") include $home.$config->ressourcen."class.language_d.php";
else include $home.$config->ressourcen."class.language_e.php";

include $config->home.$config->ressourcen."funcs.php";
$funcs = new Funcs;

include $config->home.$config->ressourcen."page.php";
$page = new Page;

include $config->home.$config->ressourcen."content.php";
$content = new Content;

if (( !isset($blnLoginCheck) OR $blnLoginCheck) AND !ereg("login", $config->filename)  ) {
	include $config->home.$config->ressourcen."install/class.installProperties.php";
	
	// check login
	// compare POST-Vars with the config-Vars
	if (isset($HTTP_POST_VARS['checklogin']) and $HTTP_POST_VARS['checklogin']) {
		if ($HTTP_POST_VARS['commander_user'] == $config->commander_user and $HTTP_POST_VARS['commander_pass'] == $config->commander_pass) {
			$HTTP_SESSION_VARS['commander_user'] = $HTTP_POST_VARS['commander_user'];
			$HTTP_SESSION_VARS['commander_pass'] = $HTTP_POST_VARS['commander_pass'];
			
			$o_inst_props = new installProperties("superconfig", $config->home.$config->ressourcen, $config->home.$config->ressourcen);
			$o_inst_props->setDefaultMode("private");
			$o_inst_props->removeAllUser();
			$o_inst_props->addUser($HTTP_SESSION_VARS['commander_user'], $HTTP_SESSION_VARS['commander_pass'], "public");
		}
	}
	
	if (isset($config->commander_user) and $config->commander_user != "" and (!isset($HTTP_SESSION_VARS['commander_user']) or $HTTP_SESSION_VARS['commander_user'] == "")) {
		Header("location: ./login.php");
		exit();
	} elseif (!isset($config->commander_user) or $config->commander_user == "") {
		$o_inst_props = new installProperties("superconfig", $config->home.$config->ressourcen, $config->home.$config->ressourcen);
		$o_inst_props->setDefaultMode("public");
	}
	
}

?>