<?php

$loader = new admin_users();

class admin_users
{
	var $output;
	var $html;
	
	function admin_users()
	{
		global $IN, $OUTPUT;

		//$this->html = $OUTPUT->load_template("skin_users");
		
		switch($IN["act"])
		{
			case 'editusers':
				$this->users_userSearch();
				break;
			
			case 'addmember':
				$this->users_editMemberForm($IN["id"]);
				break;

			case 'editmember':
				$this->users_editMember();
				break;

            case 'deletemember':
                $this->users_deleteUser();
                break;
				
			case 'glist':
				$this->users_listMembers();
				break;
				
			default:
				return;
		}
		
		$OUTPUT->add_output($this->output);
	}
	
	function users_listMembers()
	{
		global $CONFIG, $DB, $IN, $sid, $guser;

		$DB->query("SELECT * FROM dl_users WHERE `group`=1");
		
		
		$this->output .= new_table();
		$this->output .= new_row(-1, "acptablesubhead");
			$this->output .=  GETLANG("username");
			$this->output .= new_col();
			$this->output .=  "<center>".GETLANG("email")."</center>";
			$this->output .= new_col();
			$this->output .=  "&nbsp;";
			$this->output .= new_col();
			$this->output .=  "&nbsp;";
		while ( $u = $DB->fetch_row() )
		{
			$mid = $u['id'];
			$this->output .= new_row();
				$this->output .=  $u['username'];
				$this->output .= new_col();
				$this->output .=  "<center>";
				$this->output .=  $u['email'];
				$this->output .=  "</center>";
				$this->output .= new_col();
				$this->output .=  "<center>"."[ <a href='admin.php?sid={$sid}&area=users&act=addmember&id={$mid}'>".GETLANG("edit")."</a> ]"."</center>";
				$this->output .= new_col();
				$this->output .=  "<center>"."[ <a href='admin.php?sid={$sid}&area=users&act=deletemember&id={$mid}'>".GETLANG("delete")."</a> ]"."</center>";
		};
		$this->output .= end_table();

	}		

	function users_userSearch()
	{
		global $sid, $guser, $DB, $IN;
		$this->output .= admin_head(GETLANG("nav_users"), GETLANG("nav_editusers"));
		$this->users_userSearchMain("admin.php?sid=$sid&area=users&act=editusers");
		
		$this->users_listMembers();
		$this->output .= admin_foot();
	}
	function users_userSearchMain($formurl)
	{
		global $sid, $std, $guser, $DB, $IN;
		
		if ( empty($IN["submit"]) )
		{	
			$this->output .= "<form method=POST action='{$formurl}'>";
			$this->output .= new_table();
			$this->output .= new_row(2, "acptablesubhead");
				$this->output .= GETLANG("searchusers");
			$this->output .= new_row();
				$this->output .= GETLANG("username").": ";
				$this->output .= new_col();
				$this->output .= "<input name='username' type='text' size='30'> <input type='submit' name='submit' value='".GETLANG("submit")."'>";
			$this->output .= end_table();
			$this->output .= "<input type='hidden' name='gid' value='$id'>";
			$this->output .= "</form>";
		}
		else
		{
			//TODO: Make this a real search
			$un = $IN["username"];
			$result = $guser->userdb->query("SELECT * FROM `{$guser->mem_table}` WHERE {$guser->db_name}='$un'");
			if ( $myrow = $guser->userdb->fetch_row($result) )
			{
				$this->output .= new_table();
				$this->output .= new_row(4, "acptablesubhead");
					$this->output .= GETLANG("nav_editusers");
				$this->output .= new_row(-1, "acptableboldsub");
					$this->output .= GETLANG("username");
					$this->output .= new_col();
					$this->output .= GETLANG("email");
					$this->output .= new_col();
					$this->output .= "&nbsp;";
					$this->output .= new_col();
					$this->output .= "&nbsp;";
				$this->output .= new_row();
					$this->output .= $myrow[$guser->db_name];
					$this->output .= new_col();
					$this->output .= $myrow[$guser->db_email];
					$this->output .= new_col();
					$id=$myrow[$guser->db_id];
					$this->output .= "<a href='admin.php?sid=$sid&area=users&act=addmember&id=$id'>".GETLANG("edit")."</a>";
					$this->output .= new_col();
					$this->output .= "<a href='admin.php?sid=$sid&area=users&act=deletemember&id=$id'>".GETLANG("delete")."</a>";
					
				$this->output .= end_table();
			}
			else
			{
				$std->warning(GETLANG("warn_nouser"));
			}
		}
		
	}

	// =================================================================
	// Edit Member
	//      This function will add and edit members details
	// =================================================================
	function users_editMember()
	{
		global $DB, $sid, $std, $guser, $IN;

		$this->output .= admin_head(GETLANG("nav_users"), GETLANG("nav_editusers"));

		if ( $IN["id"] )
		{
            if ( DEMO )
            {
			    $this->output .= "This information has not been saved in this demo as it would be possible for users to edit the public admin password";
				return;
			}
			
			// Ensure member already exists
			$DB->query("SELECT * FROM dl_users WHERE id='{$IN['id']}'");
			if (!$myrow = $DB->fetch_row())
			{
				$std->error(GETLANG("er_edituser"));
				admin_foot();
				return;
			}

			// Encrypt password
			if ( $IN["password"] )
				$password = md5($IN["password"]);

			// Make the email address safe
			$email = addslashes($IN["email"]);
				
			$update = array( "username" => $IN["username"],
							 "email" => $email );
			if ( $password )
				$update["password"] = $password;
							 
			$DB->update($update, "dl_users", "id={$IN['id']}");
			
			$this->output .= GETLANG("useredited")."<br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=users&act=addmember&id={$IN['id']}'>".GETLANG("backto")." ".GETLANG("nav_editusers")." ".$IN["username"]."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=users&act=addmember'>".GETLANG("backto")." ".GETLANG("nav_adduser")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>";
		}
		else
		{
			// Ensure a password was entered
			if ( $IN["password"] == "" )
			{
				$std->error(GETLANG("er_nopass"));
				admin_foot();
				return;
			}
			// Ensure member doesn't already exist with that username
			$result = $DB->query( "SELECT * FROM `dl_users` WHERE `username`='{$IN['username']}'");
			if ($myrow = $DB->fetch_row($result))
			{
				$std->error(GETLANG("er_dupUsers"));
				admin_foot();
				return;
			}

			// Encrypt password
			$password = md5($IN["password"]);

			// Make the email address safe
			$email = addslashes($IN["email"]);

			$insert = array( "username" => $IN["username"],
							 "password" => $password,
							 "group" => 1,
							 "email" => $email );

			$DB->insert($insert, "dl_users");
			
			$this->output .= GETLANG("newusernoconfirm")."<br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=users&act=editusers'>".GETLANG("backto")." ".GETLANG("nav_editusers")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=users&act=addmember'>".GETLANG("backto")." ".GETLANG("nav_adduser")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>";

		}

		$this->output .= admin_foot();
	}

	// Display the form for editting and adding members
	function users_editMemberForm($id = "")
	{
		global $DB, $sid, $std, $guser, $CONFIG;
		if ( $id )
		{
            $DB->query("SELECT * FROM `dl_users` WHERE `id`='{$id}'");
			
			if ( !$data = $DB->fetch_row() )
				$std->error(GETLANG("er_useredit"));
		}
		$this->output .= admin_head(GETLANG("nav_users"), GETLANG("nav_editusers"));
		
		$this->output .= "<form method=POST action='admin.php?sid=$sid&area=users&act=editmember'>";
		$this->output .= new_table();
		$this->output .= new_row(2, "acptablesubhead");
			$this->output .= GETLANG("userdetails");
		
		$this->output .= new_row();
			$this->output .= GETLANG("username").": ";
			$this->output .= new_col();
			$this->output .= "<input name='username' type='text' size='30' value='".$data['username']."'>";
		$this->output .= new_row();
			$this->output .= GETLANG("email").": ";
			$this->output .= new_col();
			$this->output .= "<input name='email' type='text' size='30' value='".$data['email']."'>";
			
		$this->output .= new_row();
			$this->output .= GETLANG("password").": ";
			$this->output .= new_col();
			$this->output .= "<input name='password' type='password' size='30'>";
		
		$this->output .= end_table();
		$this->output .= "<input type='hidden' name='id' value='$id'>";
		$this->output .= "<center><input type='submit' name='submit' value='".GETLANG("submit")."'>
		  <input type='reset' name='reset' value='".GETLANG("reset")."'></center>";
		$this->output .= "</form>";
		$this->output .= admin_foot();
	}

    function users_deleteUser()
    {
        global $sid, $DB, $IN, $CONFIG;
		$this->output .= admin_head(GETLANG("nav_users"), GETLANG("nav_users"));

        $id = $IN["id"];

		if (DEMO)
		{
			$this->output .= "Sorry but for security reasons we can not allow you to modify the user database type in the demo version.";
			$this->output .= admin_foot();
			return;
		}
        if (!empty($IN["cancel"]))
		{
            $this->output .= GETLANG("users_userDeleteCancel")."<br>";
            $this->output .= "+ <a href='admin.php?sid=$sid&area=users&act=editmember&id={$IN[id]}'>".GETLANG("backto")." ".GETLANG("nav_editusers")."</a><br>";
            $this->output .= "+ <a href='admin.php?sid=$sid&area=users&act=editusers'>".GETLANG("backto")." ".GETLANG("nav_users")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>";

			$this->output .= admin_foot();
			return;
        }
		// If confirming delete
        if (!empty($IN["submit"]))
		{

            if ( $id == 1 )
            {
				$std->error(GETLANG("er_deletegod"));
            }
            else
            {
    			$DB->query("DELETE FROM dl_users WHERE id=$id");
                $DB->query("DELETE FROM dl_memberextra WHERE mid=$id");
    			$this->output .= GETLANG("users_userDelete")."<br>";
            }
			$this->output .= "+ <a href='admin.php?sid=$sid&area=users&act=editusers'>".GETLANG("backto")." ".GETLANG("nav_users")."</a><br>";
			$this->output .= "+ <a href='admin.php?sid=$sid&area=main'>".GETLANG("backto")." ".GETLANG("nav_adhome")."</a><br>";

			$this->output .= admin_foot();
			return;
		}

		$this->output .= "<form method=POST action='admin.php?sid=$sid&area=users&act=deletemember'>";
		$this->output .= new_table();
		$this->output .= new_row(2, "acptablesubhead");
			$this->output .= GETLANG("deleteuser");
		$this->output .= new_row(2);
			$this->output .= GETLANG("confirmdeleteuser");

		$this->output .= end_table();
		$this->output .= "<input type='hidden' name='id' value='$id'>";
		$this->output .= "<center><input type='submit' name='submit' value='".GETLANG("yes")."'> <input type='submit' name='cancel' value='".GETLANG("no")."'>";
		$this->output .= "</center></form>";
		$this->output .= admin_foot();
    }

}

?>