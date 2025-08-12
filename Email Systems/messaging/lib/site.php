<?php
session_start();

error_reporting(0);

require_once _LIBPATH . "common.php";
require_once _LIBPATH . "xml.php";
require_once _LIBPATH . "template.php";
require_once _LIBPATH . "config.php";
require_once _LIBPATH . "html.php";
require_once _LIBPATH . "database.php";
require_once _LIBPATH . "vars.php";
require_once _LIBPATH . "menu.php";
require_once _LIBPATH . "library.php";
require_once _LIBPATH . "sqladmin.php";
require_once _LIBPATH . "forms.php";
require_once _LIBPATH . "mail.php";
require_once _LIBPATH . "sendmail.php";

class CBase {
	/**
	* description
	*
	* @var type
	*
	* @access type
	*/
	var $html;
	
}
class CSite {

	/**
	* description
	*
	* @var type
	*
	* @access type
	*/
	var $admin;
	/**
	* description
	*
	* @var type
	*
	* @access type
	*/
	var $html;
	

	/**
	* description
	*
	* @param
	*
	* @return
	*
	* @access
	*/
	function CSite($xml , $admin = false) {
		global $_CONF , $base;

		$this->admin = $admin;

		//loading the config
		$tmp_config = new CConfig($xml);

		$_CONF = $tmp_config->vars["config"];

		//loading the templates
		if ($this->admin) {
			if (is_array($_CONF["templates"]["admin"])) {
				foreach ($_CONF["templates"]["admin"] as $key => $val) {
					if ($key != "path")
						$this->templates[$key] = new CTemplate($_CONF["templates"]["admin"]["path"] . $_CONF["templates"]["admin"][$key]);
				}			
			}			
		} else {

			if (is_array($_CONF["templates"])) {
				foreach ($_CONF["templates"] as $key => $val) {
					if (($key != "path" ) && ($key != "admin"))
						$this->templates[$key] = new CTemplate($_CONF["templates"]["path"] . $_CONF["templates"][$key]);
				}				
			}
		}
		

		$base = new CBase();
		$base->html = new CHtml();
		$this->html = &$base->html;

		//make a connection to db
		if (is_array($_CONF["database"])) {
			$this->db = new CDatabase($_CONF["database"]);

			//vars only if needed
			if ($_CONF["tables"]["vars"]) {
				$this->vars = new CVars($this->db , $_CONF["tables"]["vars"]);
				$base->vars = &$this->vars;
			}

			$this->tables = &$_CONF["tables"];
		}				
		
	}

	function TableFiller($item) {
		if (file_exists("pb_tf.php")) {
			include("pb_tf.php");
		}
	}

	/**
	* description
	*
	* @param
	*
	* @return
	*
	* @access
	*/
	function Run() {
		global $_TSM , $_SITE_IDENTITY_CODE, $_CONF , $_USER;
		$_USER = $_SESSION["minibase"]["raw"];

		if ($this->admin) {
			$_CONF["modules"] = $_CONF["modules"]["admin"];
			unset($_CONF["modules"]["admin"]);
		} else {
			unset($_CONF["modules"]["admin"]);
		}


		//replace some global vars in the template, i'm doing it here, becouse in modules i may want to change them
		if (is_array($_CONF["vars"])) {
			foreach ($_CONF["vars"] as $key => $var) {
				$_TSM["MINIBASE." . strtoupper($key)] = $var;
			}			
		}
		
		//do a module detection now
		if ($this->admin) {
			//add the menus for the navigation
			$_TSM["MINIBASE.POSTMENU"] = file_exists("templates/menu.post.htm") ? GetFileContents("templates/menu.post.htm") : "";
			$_TSM["MINIBASE.PREMENU"] = file_exists("templates/menu.pre.htm") ? GetFileContents("templates/menu.pre.htm") : "";
			
			//okay, first be a bitch and do the autentification thingy
			if (!$_SESSION["minibase"]["user"]) {
				//force to the auth module
				$_GET["mod"] = "auth";
				//no action = login screen
				$_GET["sub"] = ($_GET["sub"] == "recover") || ($_GET["sub"] == "recover.thanks") ? $_GET["sub"] : "";
				$_GET["action"] = "";
			}
			
			if (is_array($_CONF["modules"])) {
				//okay initialize the new module now;
				foreach ($_CONF["modules"] as $_KMOD => $_MOD) {
				
						$file = "modules/" . $_MOD . "/" . ($this->admin ? "admin.php" : "site.php");
						//detect if the file exists
						if (file_exists($file)) {
								require_once $file;
								eval("\$this->modules[\"". $_MOD. "\"] = new c{$_MOD}();");				
								//send the used params
								$this->modules[$_MOD]->templates = $this->templates;
								$this->modules[$_MOD]->tables = $this->tables;
								$this->modules[$_MOD]->vars = $this->vars;
								$this->modules[$_MOD]->db = $this->db;

								$_CONF["forms"]["adminpath"] = "modules/" . $_MOD . "/forms/";

								//read the module config if any exists
								if (file_exists("modules/" . $_MOD . "/" . "module.xml")) {
									$this->modules[$_MOD]->config = new CConfig("modules/" . $_MOD . "/" . "module.xml");

									//load the specific files
									if (is_array($this->modules[$_MOD]->config->vars["module"]["admin"]["templates"])) {
										foreach ($this->modules[$_MOD]->config->vars["module"]["admin"]["templates"] as $key => $val) {
											$this->modules[$_MOD]->private->templates[$key] = new CTemplate("modules/" . $_MOD . "/templates/" . $val );
										}								
									}

									//load the tables
									if (is_array($this->modules[$_MOD]->config->vars["module"]["admin"]["tables"])) {
										$this->modules[$_MOD]->private->tables = $this->modules[$_MOD]->config->vars["module"]["admin"]["tables"];

										//do a check for the private vars table if available
										foreach ($this->modules[$_MOD]->private->tables as $key => $val) {
											if ($key == "vars") {
												$this->modules[$_MOD]->private->vars = new CVars($this->db , $val);
											}									
										}															
									}							
								}
								

								if ($_GET["mod"] == $_MOD) {
									//if is the module then return in the layout the results
									$_TSM["PB_EVENTS"] = $this->modules[$_MOD]->DoEvents();

									//control variable to see if there was found a module
									$executed_module = true;
								} else {
									//elese simply execute for global routines fo the module
									$this->modules[$_MOD]->DoEvents();
								}								
					}
					//do a search for menus
					if (file_exists("modules/" . $_MOD . "/" . "menu.htm")) {
						//read the menus
						$tmp_menu = new CTemplate("modules/" . $_MOD . "/" . "menu.htm");

						//check if there is made any difference between users levels
						if (is_object($tmp_menu->blocks["MenuLevel" . (int)$_SESSION["minibase"]["raw"]["user_level"]]))
							$menus .= $tmp_menu->blocks["MenuLevel" . (int)$_SESSION["minibase"]["raw"]["user_level"]]->output;
						else
							//load a menu block depending the user level
							$menus .= $tmp_menu->output;
						
					} else {
						//here will be in future the xml menu
					}																
				}			
			}

			
			if (is_object($this->templates["menus"])) {
				$menus = new CTemplate($menus,"string");
				$_TSM["MINIBASE.MENU"] = $_SESSION["minibase"]["user"] ? $this->templates["menus"]->blocks["Menu"]->Replace(array("MENUS.CONTENT"=>$menus->Replace($_TSM))) : "";
			} else {
				$_TSM["MINIBASE.MENU"] = "";
			}
			//build the menus now
		}

		
		

		if (file_exists("pb_events.php") && !$executed_module) {
			include("pb_events.php");
			
			$_TSM["PB_EVENTS"] = @DoEvents($this);
		}

		if (is_object($this->templates["layout"])) {
			echo $this->templates["layout"]->Replace($_TSM) . $_SITE_IDENTITY_CODE ;
		}		
	}
}


?>