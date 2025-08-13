<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+

class User
{
	var $db = array();
	var $table_lookup = array(); // search table user lookup thingy
	// $db['user'] , $db['field']

	function User()
	{
		// constructor
		$this->avatar_dir = OUT_FOLDER."avatar/";
		$this->cache_dir = OUT_FOLDER."cache/";
	}
	
	function makeoptions($id='0',$selected='',$depth=1)
	{
		global $group_cache,$udb,$database;

		if (is_array($group_cache))
		{
			foreach ($group_cache as $group)
			{
				unset($sel);

				if ($group['gid'] == $selected && $selected != "")
				{
					$sel = "selected";
				}

				if ($this->uselink == "1")
				{
					$a .= "<option value=\"?cat=".$group['gid']."&cn=".$group['name']."\"".$sel.">- ";	
					$a .= $group['name']."</option>";	
				}
				else
				{
					$a .= "<option value=\"".$group['gid']."\"".$sel.">";
					$a .= str_repeat("&nbsp;",3).$group['name']."</option>";
				}							
				
			} 
		}

		$udb->free_result($sql);
		return $a;
	}

	function makeselectbox($name,$selected='')
	{
		global $udb,$admin,$database,$group_cache,$evoLANG;

		if (!is_array($group_cache))
		{
			$sql = $udb->query("SELECT * FROM ".$this->db['usergroup']." ORDER by gid ASC");
			while ($row = $udb->fetch_array($sql))
			{
				$group_cache[$row['gid']] = $row;
			}

			$group_cache = $admin->strip_array($group_cache);
		}
		

		$b = $this->makeoptions("0",$selected);

		$words = $this->select_word != "" ? $this->select_word:$evoLANG['selectgroup'];
		$a .= "<select name=\"".$name."\">\n";
		$a .= "<option value=''> ".$words." </option>\n";
		$a .= $b;
		$a .= "</select>";

		return $a;
	}

	function wrap_confirm($text,$loc)
	{
		$js = "var tanya = confirm('Are you sure?'); if (tanya == true) { window.location = '".$loc."'}";
		return "<span style=\"cursor: hand; border-bottom: 1px dashed #333\" onclick=\"".$js."\">".$text."</span>";
	}

	function redirect($loc)
	{
		header('location: '.$loc);
	}

	function init()
	{
		global $_POST,$_GET,$_REQUEST,$admin,$udb,$acc;
		
		switch ($_GET['do'])
		{
			
			case "finduser":
				$content = $this->lookup();
			break;
			/* ----------------------------------------- */
			case "add":
				$content = $this->adduser();
			break;
			/* ----------------------------------------- */
			case "edit":
				$content = $this->edituser($_GET['id']);
			break;
			/* ----------------------------------------- */
			case "delete":
				$content = $this->deleteuser($_GET['id']);
			break;
			/* ----------------------------------------- */
			case "managefields":
				$content = $this->fields_main();
			break;
			/* ----------------------------------------- */
			case "addfield":
				$content = $this->addfield();
			break;
			/* ----------------------------------------- */
			case "editfield":
				$content = $this->editfield($_GET['id']);
			break;
			/* ----------------------------------------- */
			case "deletefield":
				$content = $this->deletefield($_GET['id']);
			break;
			/* ----------------------------------------- */
			case "massemail":
				$content = $this->massemail();
			break;
			/* ----------------------------------------- */
			case "submit":
				if ($_POST['adduser']) $content = $this->process_adduser();
				if ($_POST['edituser']) $content = $this->process_edituser();
				/* ------------------------------------------------------------- */
				if ($_POST['addfield']) $content = $this->process_addfield();
				if ($_POST['editfield']) $content = $this->process_editfield();

			break;
			
			/* ---------------- addon crap ------------- */
			case "access":
				$content = $acc->accessmask($_GET['id']);
			break;
			/* ----------------------------------------- */
		}
		return $content;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
					START MAIN ADMIN PAGE
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function main()
	{
		global $admin,$_SERVER,$udb;

		$html .= $admin->link_button("Add User",$_SERVER['PHP_SELF']."?do=add")          .  "<br />";
		$html .= $admin->link_button("Manage Profile Fields",$_SERVER['PHP_SELF']."?do=managefields")          .  "<br />";
		$html .= $admin->link_button("Send Email",$_SERVER['PHP_SELF']."?do=massemail")          .  "<br />";
		$html .= $admin->link_button("View All Users",$_SERVER['PHP_SELF']."?do=finduser")          .  "<br />";

		$html .= "<br />";
		
		/*
		$sql = $udb->query("SELECT * FROM ".$this->db['user']);	
		
		$admin->row_width = "60%";
		$a = $admin->add_spacer("User"); 

		while ( $row = $udb->fetch_array($sql) )
		{
			$row = $admin->strip_array($row);
			$a .= $admin->add_row($row['username'],"[ ".$admin->makelink("edit",$_SERVER['PHP_SELF']."?do=edit&amp;id=$row[id]")."]  [".$this->wrap_confirm("delete",$_SERVER['PHP_SELF']."?do=delete&amp;id=".$row['id'])."]");
		}
		$html .= $admin->add_table($a,"90%")."<br />";
		*/

		$html .= $this->searchtable();

		return $html;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						MANAGE USER FIELDS
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function fields_main()
	{
		global $admin,$_SERVER,$udb;

		$html .= $admin->link_button("Add New Fields",$_SERVER['PHP_SELF']."?do=addfield")          .  "<br />";
		$html .= "<br />";
		
		$sql = $udb->query("SELECT * FROM ".$this->db['field']);
		
		$admin->row_width = "60%";
		$a = $admin->add_spacer("Custom Profile Fields"); 

		while ( $row = $udb->fetch_array($sql) )
		{
			$row = $admin->strip_array($row);
			$a .= $admin->add_row($row['name'],"[ ".$admin->makelink("edit",$_SERVER['PHP_SELF']."?do=editfield&amp;id=$row[fieldid]")."]  [".$this->wrap_confirm("delete",$_SERVER['PHP_SELF']."?do=deletefield&amp;id=".$row['fieldid'])."]",$row['description']);
		}
		
		$html .= $admin->add_table($a,"90%");

		return $html;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						ADD NEW USER FIELD
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function addfield()
	{
		global $udb,$_SERVER,$admin;
		
		$admin->row_align = "left";
		$admin->row_width = "60%";
		/* ------------------------------------------------ */
		$admin->do_check = 1; // enable js validating
		$admin->check_element = array(
										"name"        => "Name"
									 );
		// element condition
		/* ------------------------------------------------ */

		$html  .= $admin->form_start("asg",$_SERVER['PHP_SELF']."?do=submit");
		$table .= $admin->add_spacer("Add New Field");
		
		$opt = "text|Text Field,textarea|Text Area,yesno|Radio Button- Yes No";
		$table .= $admin->add_row("Type",$admin->form_select("type",$opt,"",""));
		
		$table .= $admin->add_row("Name",$admin->form_input("name"));
		$table .= $admin->add_row("Description",$admin->form_textarea("description"));
		$table .= $admin->add_row("Required",$admin->form_select_yesno("required"));
		$table .= $admin->add_row("Order",$admin->form_input("order","0"));


		$table .= $admin->form_submit("addfield");
		$html  .= $admin->add_table($table,"80%");

		return $html;
	}
	
	function process_addfield()
	{
		global $_POST,$udb,$admin;
		
		$_POST = $admin->slash_array($_POST);

		$udb->insert_into = $this->db['field'];
		$udb->insert_data = array (
										"name"        => $_POST['name'],
										"description" => $_POST['description'],
										"orders"      => $_POST['order'],
										"required"    => $_POST['required'],
										"type"		  => $_POST['type']
								   );
		$udb->query_insert();

		//cari plak latest id
		$row = $udb->query_once("SELECT * FROM ".$this->db['field']." ORDER BY fieldid DESC LIMIT 1");
		
		switch($_POST['type'])
		{
			case "text":
				$type = "ADD `custom_".$row['fieldid']."` VARCHAR(255) NOT NULL";
			break;
			case "textarea":
				$type = "ADD `custom_".$row['fieldid']."` TEXT NOT NULL";
			break;
			case "yesno":
				$type = "ADD `custom_".$row['fieldid']."` TINYINT(1) NOT NULL";
			break;
		}

		$udb->query('ALTER TABLE '.$this->db['user'].' '.$type);

		$this->redirect($_SERVER['PHP_SELF']."?do=managefields");
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						EDIT USER FIELD
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function editfield($id)
	{
		global $udb,$_SERVER,$admin;
		
		$admin->row_align = "left";
		$admin->row_width = "60%";
		/* ------------------------------------------------ */
		$admin->do_check = 1; // enable js validating
		$admin->check_element = array(
										"name"        => "Name"
									 );
		// element condition
		/* ------------------------------------------------ */
		$row = $udb->query_once("SELECT * FROM ".$this->db['field']." WHERE fieldid='".$id."'");
		$html  .= $admin->form_start("asg",$_SERVER['PHP_SELF']."?do=submit").$admin->form_hidden("id",$row['fieldid']);
		$table .= $admin->add_spacer("Edit Profile Field : ".$row['name']);
		
		$opt = "text|Text Field,textarea|Text Area,yesno|Radio Button- Yes No";
		$table .= $admin->add_row("Type",$admin->form_select("type",$opt,$row['type'],""));
		
		$table .= $admin->add_row("Name",$admin->form_input("name",$row['name']));
		$table .= $admin->add_row("Description",$admin->form_textarea("description",$row['description']));
		$table .= $admin->add_row("Required",$admin->form_select_yesno("required",$row['required']));
		$table .= $admin->add_row("Order",$admin->form_input("order",$row['orders']));


		$table .= $admin->form_submit("editfield");
		$html  .= $admin->add_table($table,"80%");

		return $html;
	}

	function process_editfield()
	{
		global $_SERVER,$admin,$udb;

		$_POST = $admin->slash_array($_POST);
		
		$udb->query("UPDATE ".$this->db['field']." SET name='".$_POST['name']."',type='".$_POST['type']."',description='".$_POST['description']."',required='".$_POST['required']."',orders='".$_POST['order']."' WHERE fieldid='".$_POST['id']."'");

		$this->redirect($_SERVER['PHP_SELF']."?do=managefields");
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
						DELETE USER FIELD
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function deletefield($id)
	{
		global $udb;
		if ($id == "") $this->redirect($_SERVER['PHP_SELF']);

		$udb->query("DELETE FROM ".$this->db['field']." WHERE fieldid='".$id."'");
		$udb->query("ALTER TABLE ".$this->db['user']." DROP `custom_".$id."`");
		$this->redirect($_SERVER['PHP_SELF']."?do=managefields");
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
							EDIT USER
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function edituser($id)
	{
		global $udb,$_SERVER,$admin,$settings;
		
		$admin->row_align = "center";
		$admin->row_width = "60%";				

		/* ------------------------------------------------ */
		$admin->do_check = 1; // enable js validating
		$admin->check_element = array(
										"gid"         => "Usergroup",
										"username"    => "Username",
										"password"    => "Password",
										"email"		  => "Email"
									 );
		// element condition
		/* ------------------------------------------------ */		
		$row = $udb->query_once ("SELECT * FROM ".$this->db['user']." WHERE id='$id'");
		$row = $admin->strip_array($row);

		if ($settings['useavatar'] == 1)
		{
			$avatar_url = $row['avatar'] != '' ? $admin->makelink($row['avatar'],$row['avatar']).$admin->form_hidden("old_av",$row['avatar']):'';
			$avatar_row = $admin->add_row("Avatar",$avatar_url.$admin->form_input("avatar","","file"),$this->get_uploadinfo() );
		}

		$fields =  $this->make_fields($row);
		$html  .= $admin->form_start("asg",$_SERVER['PHP_SELF']."?do=submit",1).$admin->form_hidden("id",$row['id']);

		$table .= $admin->add_spacer("Edit User : ".$row['username']);
		$table .= $admin->add_row("Usergroup",$this->makeselectbox("gid",$row['groupid']) );
		$table .= $admin->add_row("Username",$admin->form_input("username",$row['username']));
		$table .= $admin->add_row("Email",$admin->form_input("email",$row['email']));
		$table .= $avatar_row;

		$table .= $admin->add_spacer("New Password");
		$table .= $admin->add_row("New Password",$admin->form_input("newpass"),"only use if you want to change user's password");

		$table .= $admin->add_spacer("Optional Informations");
		$table .= $fields;
		$table .= $admin->form_submit("edituser");
		$html  .= $admin->add_table($table,"80%");

		return $html;
	}
	
	function get_uploadinfo()
	{
		global $evoLANG,$settings,$admin;
		
		$stifler .= $evoLANG['alloweddimension']." : ".$settings['maxdimension']."<br />";
		$stifler .= $evoLANG['allowedfiletype']." : <i>".$settings['avallowedmime']."</i> <br />";
		$stifler .= $evoLANG['maxsize']." : <i>".$admin->file_size($settings['avmaxsize'])."</i>";
		
		return $stifler;
	}

	function process_edituser()
	{
		global $admin,$udb,$_POST,$_SERVER,$_FILES,$evoLANG;
		
		$_POST = $admin->slash_array($_POST);
		if ($this->process_avatar() == false)
		{
			return $this->error;
		}
		
		$avatar = $this->av_url != '' ? ",avatar = '".$this->av_url."'":"";
		$sql = $udb->query("SELECT fieldid FROM ".$this->db['field']);
		while ( $row = $udb->fetch_array($sql) )
		{
			$custom .= "custom_".$row['fieldid']."='".$_POST['custom_'.$row['fieldid']]."', ";
		}

		$password = trim($_POST['newpass']) != "" ? "password='".md5($_POST['newpass'])."',":"";

		$query = "UPDATE ".$this->db['user']." SET ".$custom.$password."username='".$_POST['username']."',email='".$_POST['email']."',groupid='".$_POST['gid']."'".$avatar." WHERE id='".$_POST['id']."'";

		$udb->query($query);
		$this->redirect($_SERVER['PHP_SELF']."?do=edit&id=".$_POST['id']);

	}

	function process_avatar($id='')
	{
		global $_FILES,$admin,$tpl,$database,$udb,$_POST,$site,$userinfo,$settings,$evoLANG;
		
		if ($_FILES['avatar'])
		{
			if ($_FILES['avatar']['size'] > 0)
			{	
				@unlink($_POST['old_av']);
				@mkdir($this->avatar_dir,0777);
				$ext = $admin->get_ext($_FILES['avatar']['name']);
				
				if ( !in_array($ext,explode(",",$settings['avallowedmime'])) )
				{
					$this->error = $evoLANG['invalidtype'];
					return false;
				}
				
				$this->av_url = $this->avatar_dir.$_POST['id']."_".$_FILES['avatar']['name'];
				@copy($_FILES['avatar']['tmp_name'], $this->av_url);
				@chmod ($this->av_url, 0666);

				if(filesize($this->av_url) > $settings['avmaxsize'])
				{
					$this->error = $evoLANG['toobig'];
					unlink($this->av_url);
					return false;
				}
			}
		}
		return true;
	}
	
	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
							DELETE USER
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function deleteuser($id)
	{
		global $udb;
		if ($id == "") $this->redirect($_SERVER['PHP_SELF']);

		$udb->query("DELETE FROM ".$this->db['user']." WHERE id='".$id."'");
		$this->redirect($_SERVER['PHP_SELF']);
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
							ADD USER
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function adduser()
	{
		global $udb,$_SERVER,$admin;
		
		$admin->row_align = "center";
		$admin->row_width = "60%";				

		/* ------------------------------------------------ */
		$admin->do_check = 1; // enable js validating
		$admin->check_element = array(
										"gid"         => "Usergroup",
										"username"    => "Username",
										"password"    => "Password",
										"email"		  => "Email"
									 );
		// element condition
		/* ------------------------------------------------ */

		$fields =  $this->make_fields();
		$html  .= $admin->form_start("asg",$_SERVER['PHP_SELF']."?do=submit");

		$table .= $admin->add_spacer("Add User");
		$table .= $admin->add_row("Usergroup",$this->makeselectbox("gid") );
		$table .= $admin->add_row("Username",$admin->form_input("username"));
		$table .= $admin->add_row("Password",$admin->form_input("password","","password"));
		$table .= $admin->add_row("Email",$admin->form_input("email"));

		$table .= $admin->add_spacer("Optional Informations");
		$table .= $fields;
		$table .= $admin->form_submit("adduser");
		$html  .= $admin->add_table($table,"80%");

		return $html;
	}

	function make_fields($array='')
	{
		global $admin,$evoLANG,$udb;
		
		$sql = $udb->query("SELECT * FROM ".$this->db['field']);
		while ( $row = $udb->fetch_array($sql) )
		{
			if( $row['required'] == 1)
			{
				$admin->check_element['custom_'.$row['fieldid'].''] = $row['name'];
			}

			$html .= $admin->add_row($row['name'],$this->convert_type($row['type'],$row['fieldid'],$array['custom_'.$row['fieldid']]),$row['description']);
		}

		return $html;
	}

	function convert_type($type,$varname,$value)
	{
		global $admin;
		
		$varname = "custom_".$varname."";
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
		}

		return $newtype;
	}

	function process_adduser()
	{
		//
		global $udb,$_POST,$admin;

		$_POST = $admin->slash_array($_POST);

		$udb->insert_into = $this->db['user'];
		$udb->insert_data = array (
										"username" => $_POST['username'],
										"password" => md5($_POST['password']),
										"groupid"      => $_POST['gid'],
										"email"    => $_POST['email'],
										"regdate"  => time()
								   );

		$sql = $udb->query("SELECT * FROM ".$this->db['field']);
		while ($row = $udb->fetch_array($sql) )
		{
			$udb->insert_data['custom_'.$row['fieldid']] = $_POST['custom_'.$row['fieldid']];
		}

		$udb->query_insert();
		
		$this->redirect($_SERVER['PHP_SELF']);
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
							SEARCH TABLE
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */

	function searchtable()
	{
		global $admin,$evoLANG,$_SERVER;
		$admin->row_width = "30%";
		
		
		$table .= $admin->add_spacer($evoLANG['finduserby']);

		/* ------------------------------------------------ */
		$admin->do_check = 0; // enable js validating
		/* ------------------------------------------------ */

		if (is_array($this->table_lookup))
		{
			foreach ($this->table_lookup as $rowname => $rowdesc)
			{
				$admin->check_element[$rowname] = $evoLANG[$rowname];
				$table .= $admin->add_row($rowdesc." ".$evoLANG['contains'],$admin->form_input($rowname) );
			}
		}		

		$this->select_word = $evoLANG['all'];
		$table .= $admin->add_row($evoLANG['usergroup'],$this->makeselectbox("gid"));
		
		$loc = $this->loc == '' ? $_SERVER['PHP_SELF']:$this->loc;
		$html .= $admin->form_start("",$loc."?do=finduser");		
		$table .= $admin->form_submit("search");

		$html .= $admin->add_table($table,"80%");
		return $html;
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
							USER SEARCH
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */



	function lookup()
	{
		global $udb,$evoLANG,$_SERVER,$admin;
		
		$joiner = " AND ";
		
		if ($_POST['gid'] != "")
		{
			$usergroup = "groupid ='".$_POST['gid']."'";
		}
		
		
		
		foreach ($this->table_lookup as $rowname => $rowdesc)
		{
			if ($_POST[$rowname] != "") 
			{
				$count++;
			}
		}

		$usergroup .= is_array($this->table_lookup) && $_POST['gid'] != "" && $count != 0 ? " AND ":"";

		foreach ($this->table_lookup as $rowname => $rowdesc)
		{
			$i++;
			if ($_POST[$rowname] != "")
			{
				$lookup .= $rowname." LIKE '%".$_POST[$rowname]."%'";
				if ($i < $count) $lookup .= $joiner;
			}			
		}

		$where = $usergroup == "" && $lookup == "" ? "":" WHERE ";
			
		$query = "SELECT * FROM ".$this->db['user'].$where.$usergroup.$lookup;

		$sql = $udb->query($query);
		$results = $udb->num_rows($sql);
		
		while ($row = $udb->fetch_array($sql) )
		{
			$row = $admin->strip_array($row);

			$rows .= $admin->add_row($row['username'],"[ ".$admin->makelink("edit",$_SERVER['PHP_SELF']."?do=edit&amp;id=$row[id]")."]  [".$this->wrap_confirm("delete",$_SERVER['PHP_SELF']."?do=delete&amp;id=".$row['id'])."] [ ".$admin->makelink("access mask",$_SERVER['PHP_SELF']."?do=access&amp;id=$row[id]")."]");
		}
		
		$rows = $rows == "" ? $admin->add_row("",$evoLANG['tryagain']):$rows;
		
		$html = $admin->add_table($admin->add_spacer($evoLANG['searchresults'].": ".$results).$rows,"90%");
		$html .= $this->searchtable();

		return $html;
	}
	
	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
							ADD USER
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */


	function massemail()
	{
		global $admin,$udb,$evoLANG,$_POST;
		
		if ( !isset($_POST['massemail']) )
		{
			$admin->row_width = "30%";
			$admin->row_align = "left";
			/* ------------------------------------------------ */
			$admin->do_check = 1; // enable js validating
			$admin->check_element = array(
											"subject"        => $evoLANG[subject],
											"message"		 => $evoLANG[message]
										 );
			// element condition
			/* ------------------------------------------------ */

			$a .= $admin->form_start("",$_SERVER[PHP_SELF]."?do=massemail");
			$html .= $admin->add_spacer($evoLANG['emailuser']);
			$html .= $admin->add_row($evoLANG['subject'],$admin->form_input("subject",'Mailer : ') );
			$html .= $admin->add_row($evoLANG['message'],$admin->form_textarea("message","","60|12") );
			$html .= $admin->add_row($evoLANG['sendhtmlformat'],$admin->form_radio_yesno("usehtml") );
			$b .= $admin->form_submit("massemail");

			$html = $admin->add_table( $a.$html.$b , "80%" );

			return $html;
		}
		else
		{
			$sql = $udb->query("SELECT email FROM ".$this->db['user']);
			while ( $row = $udb->fetch_array($sql) )
			{
				$emails .= $row['email'].",";
			}

			$emails = substr($emails,0,-1);

			$mailer = new mailer;
			$mailer->to = $emails;
			$mailer->subject = $_POST['subject'];
			$mailer->usehtml = $_POST['usehtml'];
			$mailer->from = "$top_settings[name] Mailer|||".$script['email'];
			$mailer->message = $_POST['message'];
			$mailer->sendmail();

			return $evoLANG['emailsent'];
		}
	}

	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
							LOGIN
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= */
	function loginform($error='',$generate=0,$redirect='')
	{
		global $tpl,$evoLANG,$settings,$_GET;		
		
		if ($generate)
		{
			eval("\$content = \"".$tpl->gettemplate("login")."\";");
			eval("echo(\"".$tpl->gettemplate("main",1)."\");");
			exit;
				
		}
		else
		{
			eval("\$login = \"".$tpl->gettemplate("login")."\";");
			return $login;
		}

	}
	
	function exec_session()
	{
		global $_SESSION,$settings;
	}

	function checkperm($array,$perm,$return=0)
	{
		global $udb,$userinfo,$settings,$permarray;

		$for = $this->for == "" ? $userinfo['groupid']:$this->for;
		
		if (!is_array($array) && !is_array($permarray) )
		{
			$sql = "SELECT * FROM ".$this->db['usergroup']." WHERE gid='".$for."'";
			$permarray = $udb->query_once($sql);
		}
		elseif (is_array($permarray))
		{
			// what?
		}
		else
		{
			$permarray = &$array;
		}
		
		if ($permarray[$perm] == 0)
		{
			if ($return)
			{
				return false;
			}
			else
			{
				$this->die_noperm();
			}
		}
		else
		{
			return true;
		}
	}

	function die_noperm($message='')
	{
		global $tpl,$evoLANG,$page;

		$content = $message != "" ? $message:$evoLANG['noperm'];
		if (preg_match("/admin/",$_SERVER['REQUEST_URI']))
		{
			eval("echo(\"".$tpl->gettemplate("main",1)."\");");
		}
		else
		{
			$page->generate();
		}
		exit;
	}


	/*-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
							LOGIN
	-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= 
	function logincheck()
	{
		global $_POST,$_GET,$admin,$udb,$evoLANG,$_COOKIE,$_SESSION;	
		
		if (!$_SESSION['inadmin'])
		{
			if ( isset($_POST['username']) && isset($_POST['password']) && isset($_POST['auth']) )
			{
				$sql = $udb->query("SELECT * FROM ".$this->db['user']." WHERE username='".$_POST['username']."'");
				
				if ($udb->num_rows($sql) > 0)
				{
					while ($userinfo = $udb->fetch_array($sql) )
					{
						if ($userinfo['password'] == md5($_POST['password']))
						{
							//kalau password match
							$this->userinfo = array();
							$this->userinfo = $userinfo; //just make the array
							
							//$_SESSION['expire']  = time()+$settings['sestimeout'];
							//$_SESSION['inadmin'] = 1;
							//$_SESSION['id'] = session_id();
							$inadmin = 1;
							session_register("inadmin");
							
							//$this->exec_session();
						}
						else
						{
							//kalau tak match?
							$this->loginform($evoLANG['xpass'],1);
						}
					}
				}
				else
				{
					$this->loginform($evoLANG['xusername'],1);
				}
			}
			else
			{
				if (!$_SESSION['inadmin'])
				{
					$this->loginform('',1);
				}
			}
		}

	}
	*/

}
?>