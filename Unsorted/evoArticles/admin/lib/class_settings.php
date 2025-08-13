<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+

class settings
{
	/*--
	CREATE TABLE settings_group (
	  sid smallint(11) NOT NULL auto_increment,
	  name varchar(100) NOT NULL default '',
	  description varchar(255) NOT NULL default '',
	  orders smallint(11) default '0',
	  PRIMARY KEY  (sid)
	) TYPE=MyISAM;


	CREATE TABLE settings (
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
	) TYPE=MyISAM;


	--*/
	var $db = array();
	var $db_file             =       "";
	var $db_file_loc         =       "";
	var $generate_file       =       "";
	var $array_name          =       "";

	//stupid constructor
	function settings()
	{
		$this->sort = "desc";
		//db information
		/*
		$this->db['sg'] = "settings_group";
		$this->db['s'] = "settings";

		//file information
		$this->db_file = "config_site.php";
		$this->db_file_loc = "";
		$this->generate_file = 0;
		
		// array name for settings to be used in config.php
		$this->array_name = "settings";

		// this is for additional information for select type of settings
		$this->add = array();
		*/
	}

	function init()
	{
		global $_POST,$_GET,$_REQUEST,$admin,$udb;

		switch ($_GET['do'])
		{
			case "add":
				$content = $this->addsetting();
			break;
			/* ----------------------------------------- */
			case "editsetting":
				$content = $this->editsetting($_GET['id']);
			break;
			/* ----------------------------------------- */
			case "addgroup":
				$content = $this->addgroup();
			break;
			/* ----------------------------------------- */
			case "editgroup":
				$content = $this->editgroup($_GET['id']);
			break;
			/* ----------------------------------------- */
			case "deletegroup":
				$content = $this->deletegroup($_GET['id']);
			break;
			/* ----------------------------------------- */
			case "deletesetting":
				$content = $this->deletesetting($_GET['id']);
			break;
			/* ----------------------------------------- */
			case "generate":
				$content = $this->generate();
			break;
			/* ----------------------------------------- */
			case "submit":
				if ($_POST['addgroup']) $this->process_addgroup();
				if ($_POST['editgroup']) $this->process_editgroup();
				if ($_POST['addsetting']) $this->process_addsetting();
				if ($_POST['editsetting']) $this->process_editsetting();

			break;
			/* ----------------------------------------- */
			case "adv":
				$content = $this->main();
			break;
			/* ----------------------------------------- */
		}

		return $content;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
							BASE FUNCTIONS
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */


	function makeoptions($id='0',$selected='',$depth=1)
	{
		global $group_cache,$udb,$database;

		if (is_array($group_cache))
		{
			foreach ($group_cache as $group)
			{
				unset($sel);

				if ($group['sid'] == $selected && $selected != "")
				{
					$sel = "selected";
				}

				if ($this->uselink == "1")
				{
					$a .= "<option value=\"?cat=".$group['sid']."&amp;cn=".$group['name']."\"".$sel.">";	
					$a .= str_repeat("&nbsp;",3).$group['name']."</option>";	
				}
				else
				{
					$a .= "<option value=\"".$group['sid']."\"".$sel.">";
					$a .= str_repeat("&nbsp;",3).$group['name']."</option>";
				}							
				
			} 
		}

		$udb->free_result($sql);
		return $a;
	}

	function makeselectbox($name,$selected='')
	{
		global $udb,$admin,$database,$group_cache;

		if (!is_array($group_cache))
		{
			$sql = $udb->query("SELECT * FROM ".$this->db['sg']." ORDER by sid ASC");
			while ($row = $udb->fetch_array($sql))
			{
				$group_cache[$row['sid']] = $row;
			}

			$group_cache = $admin->strip_array($group_cache);
		}
		

		$b = $this->makeoptions("0",$selected);

		$a .= "<select name=\"".$name."\">\n";
		$a .= "<option value=''> Select Group </option>\n";
		$a .= $b;
		$a .= "</select>";

		return $a;
	}

	function redirect($loc)
	{
		header('location: '.$loc);
	}
	
	function wrap_confirm($text,$loc)
	{
		$js = "var tanya = confirm('Are you sure?'); if (tanya == true) { window.location = '".$loc."'}";
		return "<span style=\"cursor: hand; border-bottom: 1px dashed #333\" onclick=\"".$js."\">".$text."</span>";
	}
	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						MANAGE GROUPS
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function list_settings($oid)
	{
		global $udb,$admin,$database,$set_cache;
		if (!is_array($set_cache))
		{
			$sql = $udb->query("SELECT * FROM ".$this->db['s']." ORDER BY orders DESC");
			while ($row = $udb->fetch_array($sql))
			{
				$set_cache[$row['oid']][$row['id']] = $row;
			}
		}

		if ( is_array($set_cache[$oid]) )
		{
			foreach ( $set_cache[$oid] as $set )
			{
				$set = $admin->strip_array($set);
				$a .= "<li> <b>".$set['name']."</b> [<a href=\"".$_SERVER['PHP_SELF']."?do=editsetting&id=".$set['id']."\">edit</a>] [".$this->wrap_confirm("delete",$_SERVER['PHP_SELF']."?do=deletesetting&id=".$set['id'])."] </li>";
			}
		}
		
		$html = $a != "" ? "<ul>".$a."</ul>":"";
		return $html;
	}

	function list_groups()
	{
		global $udb,$_SERVER,$admin;
		
		$sql = $udb->query("
							SELECT *
								FROM ".$this->db[sg]."
									ORDER BY orders 
										".$this->sort."
						   ");
		
		$html .= "<ul>";
		while ( $row = $udb->fetch_array( $sql ) )
		{
			$row = $admin->strip_array($row);
			$html .= "<li style=\"font-size:10pt\"> <b> $row[name] </b> [<a href=\"".$_SERVER['PHP_SELF']."?do=editgroup&id=".$row['sid']."\">edit</a>] [".$this->wrap_confirm("delete",$_SERVER['PHP_SELF']."?do=deletegroup&id=".$row['sid'])."] [<a href=\"".$_SERVER['PHP_SELF']."?do=add&oid=".$row['sid']."\">add setting</a>] </li>";
			$html .= $this->list_settings($row['sid']);
		}
		$html .= "</ul>";
		
		return $html;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
							ADD GROUP
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function addgroup()
	{
		global $udb,$_SERVER,$admin;
		
		$admin->row_align = "left";
		$admin->row_width = "30%";
		/* ------------------------------------------------ */
		$admin->do_check = 1; // enable js validating
		$admin->check_element = array(
										"name"        => "Group Name",
										"description" => "Description"
									 );
		// element condition
		/* ------------------------------------------------ */

		$html .= $admin->form_start("asg",$_SERVER['PHP_SELF']."?do=submit");
		$table .= $admin->add_spacer("Add Settings Group");
		$table .= $admin->add_row("Order",$admin->form_input("order") );
		$table .= $admin->add_row("Name",$admin->form_input("name"));
		$table .= $admin->add_row("Description",$admin->form_textarea("description"));
		$table .= $admin->form_submit("addgroup");
		$html .= $admin->add_table($table,"80%");

		return $html;
	}

	function process_addgroup()
	{
		global $admin,$_POST,$udb;
		$_POST = $admin->slash_array($_POST);

		$udb->insert_into = $this->db['sg'];
		$udb->insert_data = array (
										"name"        => $_POST['name'],
										"description" => $_POST['description'],
										"orders"       => $_POST['order']
								   );
		$udb->query_insert();

		$this->redirect( $_SERVER['PHP_SELF']."?do=adv" );
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
							EDIT GROUP
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function editgroup($id)
	{
		if ($id == '') return;

		global $udb,$_SERVER,$admin;
		
		$row = $udb->query_once("SELECT * FROM ".$this->db[sg]." WHERE sid='$id'");
		$row = $admin->strip_array($row);

		$admin->row_align = "left";
		$admin->row_width = "30%";
		/* ------------------------------------------------ */
		$admin->do_check = 1; // enable js validating
		$admin->check_element = array(
										"name"        => "Group Name",
										"description" => "Description"
									 );
		// element condition
		/* ------------------------------------------------ */

		$html .= $admin->form_start("asg",$_SERVER['PHP_SELF']."?do=submit");
		$table .= $admin->add_spacer("Edit Settings Group". $admin->form_hidden("id",$row['sid']) );
		$table .= $admin->add_row("Order",$admin->form_input("order",$row['orders']) );
		$table .= $admin->add_row("Name",$admin->form_input("name",$row['name']));
		$table .= $admin->add_row("Description",$admin->form_textarea("description",$row['description']));
		$table .= $admin->form_submit("editgroup");
		$html .= $admin->add_table($table,"80%");

		return $html;
	}
	
	function process_editgroup()
	{
		global $admin,$_POST,$udb;
		$_POST = $admin->slash_array($_POST);
		
		$udb->query(" UPDATE ".$this->db['sg']."
						SET 
							orders='".$_POST['order']."',
							description='".$_POST['description']."',
							name='".$_POST['name']."'

						WHERE sid = '".$_POST['id']."'
					");

		$this->redirect($_SERVER['PHP_SELF']."?do=adv");
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
							DELETE GROUP
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function deletegroup($id)
	{
		global $admin,$_POST,$udb;
		if ($id == "") return;
		
		$udb->query('DELETE FROM '.$this->db['sg'].' WHERE sid = \''.$id.'\'');
		$udb->query("DELETE FROM ".$this->db['s']." WHERE oid = '".$id."'");
		
		$this->redirect($_SERVER['PHP_SELF']);
	}
	
	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
							DELETE SETTING
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function deletesetting($id)
	{
		global $admin,$_POST,$udb;
		if ($id == "") return;
		
		$udb->query("DELETE FROM ".$this->db['s']." WHERE id = '".$id."'");
		
		$this->redirect($_SERVER['PHP_SELF']);
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						MAIN PAGE
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function main()
	{
		global $admin,$_SERVER;
		
		$html .= $admin->link_button("Generate Config File",$_SERVER['PHP_SELF']."?do=generate&print=1")  .  "<br /><br />";

		$html .= $admin->link_button("Add Settings Group",$_SERVER['PHP_SELF']."?do=addgroup")  .  "<br />";
		$html .= $admin->link_button("Add New Setting",$_SERVER['PHP_SELF']."?do=add")          .  "<br /><br />";
		$html .= $this->list_groups();

		return $html;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
							ADD SETTING
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function addsetting()
	{
		global $udb,$_SERVER,$admin,$_GET;
		
		$admin->row_align = "left";
		$admin->row_width = "30%";
		/* ------------------------------------------------ */
		$admin->do_check = 1; // enable js validating
		$admin->check_element = array(
										"varname"     => "Variable Name",
										"oid"         => "Group",
										"name"        => "Setting Name",
										"description" => "Description",
										"type"        => "Setting Type"										
									 );
		// element condition
		/* ------------------------------------------------ */

		$html .= $admin->form_start("asg",$_SERVER['PHP_SELF']."?do=submit");
		$table .= $admin->add_spacer("Add a Setting");
		
		$table .= $admin->add_row("Variable Name",$admin->form_input("varname"));

		$table .= $admin->add_row("Group",$this->makeselectbox("oid",$_GET['oid']) );
		$table .= $admin->add_row("Name",$admin->form_input("name"));
		$table .= $admin->add_row("Description",$admin->form_textarea("description","","40|4"));

		
		$table .= $admin->add_row("Setting Type",$admin->form_textarea("type","","40|4") , "text, yesno, textarea");

		//$table .= $admin->add_row("Value",$admin->form_textarea("value","","40|4") );
		$table .= $admin->add_row("Default Value",$admin->form_textarea("defvalue","","40|4") );
		
		$table .= $admin->add_row("Display Order",$admin->form_input("order","0") );
		$table .= $admin->form_submit("addsetting");
		$html .= $admin->add_table($table,"80%");

		return $html;
	}

	function process_addsetting()
	{
		global $admin,$_POST,$udb;
		$_POST = $admin->slash_array($_POST);

		$udb->insert_into = $this->db['s'];
		$udb->insert_data = array (
										"varname"        => $_POST['varname'],
										"oid"			 => $_POST['oid'],
							            "name"			 => $_POST['name'],
										"description"    => $_POST['description'],
										"type"			 => $_POST['type'],
									    "defvalue"		 => $_POST['defvalue'],
										"orders"         => $_POST['order']
								   );
		$udb->query_insert();

		$this->redirect( $_SERVER['PHP_SELF']."?do=adv" );
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
							EDIT SETTING
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function editsetting($id)
	{
		if ($id == "") return ;
		global $udb,$_SERVER,$admin;
		
		$row = $admin->strip_array( $udb->query_once("SELECT * FROM ".$this->db['s']." WHERE id='".$id."'") );

		$admin->row_align = "left";
		$admin->row_width = "30%";
		/* ------------------------------------------------ */
		$admin->do_check = 1; // enable js validating
		$admin->check_element = array(
										"varname"     => "Variable Name",
										"oid"         => "Group",
										"name"        => "Setting Name",
										"description" => "Description",
										"type"        => "Setting Type"										
									 );
		// element condition
		/* ------------------------------------------------ */

		$html .= $admin->form_start("asg",$_SERVER['PHP_SELF']."?do=submit");
		$table .= $admin->add_spacer("Edit Setting ".$row['name'].$admin->form_hidden("id",$id));
		
		$table .= $admin->add_row("Variable Name",$admin->form_input("varname",$row['varname']));

		$table .= $admin->add_row("Group",$this->makeselectbox("oid",$row['oid']) );
		$table .= $admin->add_row("Name",$admin->form_input("name",$row['name']));
		$table .= $admin->add_row("Description",$admin->form_textarea("description",$row['description'],"40|4"));

		
		$table .= $admin->add_row("Setting Type",$admin->form_textarea("type",$row['type'],"40|4") , "text, yesno, textarea");

		$table .= $admin->add_row("Default Value",$admin->form_textarea("defvalue",$row['defvalue'],"40|4") );
		
		$table .= $admin->add_row("Display Order",$admin->form_input("order",$row['orders']) );
		$table .= $admin->form_submit("editsetting");
		$html .= $admin->add_table($table,"80%");

		return $html;
	}

	function process_editsetting()
	{
		global $admin,$_POST,$udb;
		$_POST = $admin->slash_array($_POST);

		$udb->insert_data = array (
										"varname"        => $_POST['varname'],
										"oid"			 => $_POST['oid'],
							            "name"			 => $_POST['name'],
										"description"    => $_POST['description'],
										"type"			 => $_POST['type'],
									    "defvalue"		 => $_POST['defvalue'],
										"orders"         => $_POST['order']
								   );

		$udb->query(" UPDATE ".$this->db['s']."
						SET 
							varname='".$_POST['varname']."',
							oid='".$_POST['oid']."',
							name='".$_POST['name']."',
							description='".$_POST['description']."',
							type='".$_POST['type']."',
							defvalue='".$_POST['defvalue']."',
							orders='".$_POST['order']."'
							
						WHERE id = '".$_POST['id']."'
					");

		$this->redirect( $_SERVER['PHP_SELF']."?do=adv" );
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						GENERATE FILE
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function generate($usedef=1)
	{
		global $udb,$admin;

		if ( trim( $this->db_file != "") )
		{
			//mula_mula read this->db[s] and generate aje file ;)
			$sql = $udb->query("SELECT * FROM ".$this->db['s']);
			
			while( $row = $udb->fetch_array($sql) )
			{
				$row['value'] = $usedef && $row['value'] == "" ? $row['defvalue']:$row['value'];
				$row['value'] = str_replace("\"","\\\"",$row['value']);

				$file .= '$'.$this->array_name.'[\''.$row['varname'] ."'] = \"". $row['value']."\";\n\r";
			}
			
			//generate 
			$file = "<?php\n\r".$file."\n\r?>";
			$admin->write_file($this->db_file,$file);
			
			if ($_GET['print'] == 1) $this->redirect($_SERVER['PHP_SELF']."?do=adv");
			//return "<b>".$this->db_file."</b> generated";
		}
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
					GENERATE SETTINGS ARRAY (output)
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function get_setarray()
	{
		global $admin,$udb;

		$sql = $udb->query("SELECT * FROM ".$this->db['s']);
		while ($row = $udb->fetch_array ($sql) )
		{
			$array = $row['varname'];
		}

		return $array;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
					GENERATE SETTINGS TABLE
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function gen_table()
	{
		global $udb,$admin,$_POST;
		
		if ($_POST['settings']) $this->process_settings();

		$sql = $udb->query("SELECT * FROM ".$this->db['sg']." ORDER BY orders ".$this->sort);
		
		//start form
		$html = $admin->form_start("",$_SERVER['PHP_SELF']);

		while ( $row = $udb->fetch_array($sql) )
		{
			$row = $admin->strip_array($row);

			$html .= $admin->add_spacer( $row['name'] );
			$html .= $this->get_table_getsettings($row['sid']);
		}
		
		//end form
		$html .= $admin->form_submit("settings");
		
		$html = $admin->add_table( $html , "90%" );


		return $html;
	}

	function get_table_getsettings($oid)
	{
		global $udb,$admin,$set_cache;

		if (!is_array($set_cache))
		{
			$sql = $udb->query("SELECT * FROM ".$this->db['s']." ORDER BY orders DESC");
			while ($row = $udb->fetch_array($sql))
			{
				$set_cache[$row['oid']][$row['id']] = $row;
			}
		}

		if ( is_array($set_cache[$oid]) )
		{
			foreach ( $set_cache[$oid] as $set )
			{		
				$html .= $this->make_setting_row($set);	
			}
		}

		return $html;

	}

	function make_setting_row($row)
	{
		if (is_array($row))
		{
			global $udb,$admin;
			//$row['value'] = $row['value'] == "" ? $row['defvalue']:$row['value'];

			return $admin->add_row(
									$admin->strip($row['name']),
									$this->convert_type($row['type'],$row['varname'],$row['value']) ,
									$admin->strip($row['description'])
								   );

		}
	}
	
	function convert_type($type,$varname,$value)
	{
		global $admin;
		
		$varname = "conf[".$varname."]";
		switch($type)
		{
			case "text":
				$newtype = $admin->form_input($varname,$value);
			break;
			/* ----------------- */
			case "textarea":
				$newtype = $admin->form_textarea($varname,$value);
			break;
			/* ----------------- */
			case "yesno":
				$newtype = $admin->form_radio_yesno($varname,$value);
			break;
			/* ----------------- */
			default;
				$newtype = $type;
		}
		
		// for select lak
		if ( preg_match("/select/i",$newtype) )
		{
			$split = explode("|||",$newtype);
			
			$newtype = $admin->form_select($varname,$this->add[$split[1]],$value,"");
		}
		return $newtype;
	}
	
	function process_settings()
	{
		global $udb,$admin,$_SERVER,$_POST;
		
		//print_r($_POST);
		if ( is_array ($_POST['conf']) )
		{
			$total = count($_POST['conf']);


			$_POST['conf'] = $admin-> slash_array ($_POST['conf']);
			while ( list($name,$value) = each ($_POST['conf']) )
			{
				$udb->query( "UPDATE ".$this->db['s']." SET value='".$value."' WHERE varname = '".$name."'" );
			}
		}

		$this->generate();		
		$this->redirect($_SERVER['PHP_SELF']);
		//echo "<script> alert (' settings updated '); </script>";

	}
}
?>