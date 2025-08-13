<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+

class usergroups
{
	//add
	//edit
	//delete
	var $db_table;
	var $perm_array = array();

	function usergroups()
	{
		//constructor
	}

	function init()
	{
		global $_POST,$_GET,$_REQUEST,$admin,$udb;
		
		switch ($_GET['do'])
		{
			case "add":
				$content = $this->addgroup();
			break;
			/* ----------------------------------------- */
			case "edit":
				$content = $this->editgroup($_GET['id']);
			break;
			/* ----------------------------------------- */
			case "delete":
				$content = $this->deletegroup($_GET['id']);
			break;
			/* ----------------------------------------- */
			case "submit":
				if ($_POST['addgroup']) $this->process_addgroup();
				if ($_POST['editgroup']) $this->process_editgroup();

			break;
		}

		return $content;
	}

	function wrap_confirm($text,$loc)
	{
		$js = "var tanya = confirm('Are you sure?'); if (tanya == true) { window.location = '".$loc."'}";
		return "<span style=\"cursor: hand; border-bottom: 1px dashed #333\" onclick=\"".$js."\">".$text."</span>";
	}
	
	function main()
	{
		global $admin,$_SERVER,$udb;

		$html .= $admin->link_button("Add Usergroup",$_SERVER['PHP_SELF']."?do=add")          .  "<br /><br />";
		
		$sql = $udb->query("SELECT * FROM ".$this->db_table);
		
		$admin->row_width = "60%";
		$a = $admin->add_spacer("Usergroups"); 

		while ( $row = $udb->fetch_array($sql) )
		{
			$a .= $admin->add_row($row['name'],"[ ".$admin->makelink("edit",$_SERVER['PHP_SELF']."?do=edit&id=$row[gid]")."]  [".$this->wrap_confirm("delete",$_SERVER['PHP_SELF']."?do=delete&id=".$row['gid'])."]");
		}
		
		$html .= $admin->add_table($a,"90%");
		


		return $html;

	}

	function redirect($loc)
	{
		header('location: '.$loc);
	}
	
	function deletegroup($id)
	{
		global $admin,$_POST,$udb;
		if ($id == "") return;
		
		$udb->query("DELETE FROM ".$this->db_table." WHERE gid = '".$id."'");
		
		$this->redirect($_SERVER['PHP_SELF']);
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
							ADD GROUP
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function addgroup()
	{
		global $udb,$_SERVER,$admin;
		
		$admin->row_align = "center";
		$admin->row_width = "60%";
		/* ------------------------------------------------ */
		$admin->do_check = 1; // enable js validating
		$admin->check_element = array(
										"name"        => "Usergroup Name",
									 );
		// element condition
		/* ------------------------------------------------ */

		$html  .= $admin->form_start("asg",$_SERVER['PHP_SELF']."?do=submit");
		$table .= $admin->add_spacer("Add Usergroup");
		$table .= $admin->add_row("Name",$admin->form_input("name"));
		$table .= $admin->add_spacer("Permissions");
		$table .= $this->makerows();
		$table .= $admin->form_submit("addgroup");
		$html  .= $admin->add_table($table,"80%");

		return $html;
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

	function process_addgroup()
	{
		global $admin,$_POST,$udb;
		$_POST = $admin->slash_array($_POST);

		$udb->insert_into = $this->db_table;
		$udb->insert_data['name'] = $_POST['name'];
		
		foreach ($_POST['conf'] as $fname => $fval)
		{
			$udb->insert_data[$fname] = $fval;
		}
		
		$udb->query_insert();

		$this->redirect( $_SERVER['PHP_SELF'] );
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
							EDIT GROUP
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function editgroup($id='')
	{
		global $udb,$_SERVER,$admin;
		if ($id=='') return;

		$admin->row_align = "center";
		$admin->row_width = "60%";
		/* ------------------------------------------------ */
		$admin->do_check = 1; // enable js validating
		$admin->check_element = array(
										"name"        => "Usergroup Name",
									 );
		// element condition
		/* ------------------------------------------------ */
		$row = $udb->query_once (" SELECT * FROM ".$this->db_table." WHERE gid='$id'");
		$row = $admin->strip_array($row);

		$html  .= $admin->form_start("asg",$_SERVER['PHP_SELF']."?do=submit").$admin->form_hidden("gid",$row['gid']);
		$table .= $admin->add_spacer("Edit Usergroup: ".$row['name']);
		$table .= $admin->add_row("Name",$admin->form_input("name",$row['name']));
		$table .= $admin->add_spacer("Permissions");
		$table .= $this->makerows($row);
		$table .= $admin->form_submit("editgroup");
		$html  .= $admin->add_table($table,"80%");

		return $html;
	}

	function process_editgroup()
	{
		global $udb,$_POST,$admin;

		$_POST = $admin->slash_array($_POST);

		foreach ($_POST['conf'] as $name => $value)
		{
			$i++;
			$fields .= $name." = '".$value."'";
			$fields .= ($i < (count($_POST['conf'])) ) ? ",":"";			
		}
		
		$udb->query("UPDATE ".$this->db_table." SET name='".$_POST['name']."',".$fields." WHERE gid='".$_POST['gid']."'");

		$this->redirect( $_SERVER['PHP_SELF']."?do=edit&id=".$_POST['gid'] );
	}

	function makerows($array='')
	{
		global $admin,$evoLANG;
		
		//process array : perm_array
		foreach ($this->perm_array as $name => $type)
		{
			$value = is_array($array) ? $array[$name] : "";

			$html .= $admin->add_row(
										$evoLANG[$name],
										$this->convert_type($type,$name,$value),
										$evoLANG['d_'.$name]
									   );
		}
		return $html;
	}

	/*
	function makerows()
	{
		global $admin,$evoLANG;
		//process array : perm_array
		foreach ($this->perm_array as $name => $type)
		{
			$html .= $admin->add_row(
										$evoLANG[$name],
										$this->convert_type($type,$name,""),
										$evoLANG['d_'.$name]
									   );
		}
		return $html;
	}
	*/

}
?>