<?php

	/*////////////////////////////////////////////////////////////
	
	iWare Professional 4.0.0
	Copyright (C) 2002,2003 David N. Simmons 
	http://www.dsiware.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

	A COPY OF THE GPL LICENSE FOR THIS PROGRAM CAN BE FOUND WITHIN THE
	/admin/docs/ DIRECTORY OF THE INSTALLATION PACKAGE.

	// XHTML Compliant = Yes

	/////////////////////////////////////////////////////////////*/

	// Handle inclusion of external dependancies
	if(isset($ModLoader) && $ModLoader==1)
		{
		if(!file_exists("../../admin/iware_config.php"))
			{die("ERROR- CONFIGURATION FILE NOT FOUND.");}
		include "../../admin/iware_config.php";	
		include "../../admin/gui.php";
		include "../../admin/lang/".IWARE_LANG;
		}
	else
		{
		if(isset($ClientLoader) && $ClientLoader==1)
			{
			if(!file_exists("admin/iware_config.php"))
				{die("ERROR- CONFIGURATION FILE NOT FOUND.");}			
			include "admin/iware_config.php";	
			include "admin/gui.php";
			}
		else
			{
			if(!file_exists("iware_config.php"))
				{die("ERROR- CONFIGURATION FILE NOT FOUND.");}
			include "iware_config.php";	
			include "gui.php";
			include "lang/".IWARE_LANG;
			}
		}

	// Instantiate GUI class
	$GUI=new GUI();

	/** 
	 * Core iWare application class
	 *
	 * @package iWare Professional
	 * @author David N. Simmons <http://www.dsiware.com>
	 * @version 3.0.9
	 * @access public
	 * @copyright iWare
	 *
	 */
	class IWARE {

		/** 
		 * Variable used to store the active database connection handle
		 *
		 * @var integer
		 * @access private
		 */
		var $dbcon;

		/** 
		 * Class constructor (runs upon intialization of the class). Establishes a datbase connection and selectes the database for use by the application.
		 *
		 * @access private
		 */
		function IWARE ()
			{
			session_start();
			$this->connectDb (IWARE_HOSTNAME, IWARE_USERNAME, IWARE_PASSWORD);
			$this->selectDb(IWARE_DATABASE);		
			}

		///////////////////////////////////////////////////////////////////////////////
		// Database Abstraction
		///////////////////////////////////////////////////////////////////////////////

		/** 
		 * Connects to the configured MySQL database and stores the connection handle returned to class variable
		 *
		 * @param string $host MySQL server hostname or IP address
		 * @param string $user MySQL server username
		 * @param string $pwd MySQL server password
		 * @access private
		 */
		function connectDb ($host,$user,$pwd)
			{$link = mysql_connect ($host,$user,$pwd);$this->dbcon = $link;}
		
		/** 
		 * Closes an open connection to the MySQL database (if open)
		 *
		 * @access private
		 */
		function closeDb ()
			{if(!empty($this->dbcon)){mysql_close ($this->dbcon);}}
		
		/** 
		 * Selects the configured MySQL database for use
		 *
		 * @param string $dbname Database name to select for use by the application
		 * @access private
		 */
		function selectDb ($dbname)
			{mysql_select_db($dbname,$this->dbcon);}

		/** 
		 * Issues a MySQL query and returns the returned result from the query where applicable
		 *
		 * @param string $sql MySQL query string to be executed
		 * @return array
		 * @access private
		 */
		function query ($sql)
			{$resultset = mysql_query($sql,$this->dbcon) or die ("SQL Error ".mysql_error());return $resultset;}

		/** 
		 * Returns the data present in a result set for a specific row number and field name
		 *
		 * @param array $result Sql result set to be used
		 * @param integer $row Result set row number to be used when fetching the requested data
		 * @param string $field Result set field name to be used when fetching the requested data
		 * @access private
		 */
		function result ($result,$row,$field)
			{return mysql_result($result,$row,$field);}

		/** 
		 * Returns the total number of rows returned from a sql query
		 *
		 * @param array $result Result set returned from a sql query to be used
		 * @access private
		 */
		function countResult ($result)
			{return mysql_num_rows($result);}

		/** 
		 * Frees a existing MySQL result set from memory
		 *
		 * @param array $result Result set returned from a sql query that should be freed from memory
		 * @access private
		 */
		function freeResult ($result)
			{mysql_free_result($result);}

		/** 
		 * Returns the current version of the installed MySQL server software
		 *
		 * @return string 
		 * @access private
		 */
		function StatDB (){return mysql_get_server_info();} 

		/** 
		 * Checks if a given table exists in the configured database. Returns TRUE if the table exists, false otherwise.
		 *
		 * @param string $tablename The database table name to check for existance in the configured database
		 * @return boolean
		 * @access private
		 */
		function tableExists ($tablename)
			{
			$tables = Array ();
			$i=0;
			$result = mysql_list_tables(IWARE_DATABASE);
			if (!$result) {return false;}
			while ($row = mysql_fetch_row($result)) {$tables[$i]=$row[0];$i++;}
			$this->freeResult($result);
			if (in_array($tablename,$tables)){return true;}
			else {return false;}
			}

		///////////////////////////////////////////////////////////////////////////////
		// Login & Sessions
		///////////////////////////////////////////////////////////////////////////////

		/**
		 * Checks if a user is already logged into the application by checking for the presence of a defined PHP session variable. If the variable is defined then the method will return TRUE
		 *
		 * @return boolean
		 * @access private
		 */
		function alreadyLoggedIn()
			{if( !empty($_SESSION['uid'])){return true;}}

		/**
		 * Returns the value of an existing PHP session variable
		 *
		 * @return string
		 * @access private
		 */
		 function getId()
			{return $_SESSION['uid'];}

		/**
		 * Determines whether or not to display the login interface based on whether or not a user has logged into the system and established a valid PHP session variable. If a sesion variable has been defined ( the user is logged in ) then the method will simply return. If the session has not yet been defined then the login dialog will be displayed.
		 *
		 * @return boolean
		 * @access private
		 */
		 function maybeOpenLogInWindow()
			{
			GLOBAL $LOGIN,$username,$password;
			if ($this->alreadyLoggedIn())
				return;
			if ($LOGIN == 1){if ($this->logon($username,$password)){$this->displayLogInDialog(TRUE);	exit();}}
			else{$this->displayLogInDialog(FALSE);exit;}	
			}

		/**
		 * This method is now deprecated
		 *
		 * @return boolean
		 * @access private
		 */ 
		function authUserControl()
			{
			GLOBAL $LOGIN,$username,$password;
			if ($this->alreadyLoggedIn())
				return;
			else{die("access denied : no valid session present.");}	
			}
		
		/**
		 * Attempts to authenticate a user by credentials posted from the login interface. If authentication is sucessfull then this method will return FALSE, otherwise it will return TRUE
		 *
		 * @param string $user Username to authenticate
		 * @param string $userpwd Password to authenticate
		 * @return boolean
		 * @access private
		 */
		 function logon($user,$userpwd)
			{
			$this->selectDb(IWARE_DATABASE);
			$matches = $this->query("select id,username,password from ".IWARE_USERS."  where username='$user' limit 1");
			if ($this->countResult($matches) > 0)
				{
				$password = $this->result($matches,0,"password");
				if (crypt($userpwd,$password) == $password)
					{
					$_SESSION['uid'] = $this->result($matches,0,"id");
					$this->freeResult($matches);
					return FALSE;
					}
				else
					{
					return TRUE;
					}
				}
			$this->freeResult($matches);
			return TRUE;
			}

		/**
		 * Log a user out of the application by destroying the existing PHP session variable
		 *
		 * @access private
		 */
		function logoff ()
			{
			$_SESSION['uid']='';
			unset($_SESSION['uid']);			
			}

		/**
		 * Outputs the user login interface in two states - first time login and failed login
		 *
		 * @param boolean $badLogIn Variable to define the state of the login interface if TRUE then the login interface will be in failed login state, otherwise the login interface will be in first time login state
		 * @access private
		 */
		function displayLogInDialog($badLogIn)
			{
			global $GUI,$PHP_SELF,$username;
			?>
			<html>
			<head>
			<title>Powered By iWare Professional <?php echo IWARE_VERSION; ?></title>		
			<link rel=stylesheet href="iware.css"></link>
			</head>
			<?php $GUI->PageBody (); ?>
			<p><br /><br />
			<center>
			<?php
				echo "<form method=POST action=\"index.php\">\n";
				$GUI->OpenForm("loginForm","index.php?LOGIN=1","");
				$GUI->OpenWidget("iWare Professional ".IWARE_1);
				echo $GUI->Hidden("LOGIN","1");
				echo "<center>";
				if($badLogIn) {$GUI->Message(IWARE_2);}					
				echo "<table border=\"0\" cellpadding=3 cellspacing=0>";							
				echo "<tr><td>".$GUI->Label(IWARE_3)."</td><td><input type=\"text\" name=\"username\" value=\"$username\"></td></tr>\n";
				echo "<tr><td>".$GUI->Label(IWARE_4)."</td><td><input type=\"password\" name=\"password\"></td></tr>\n";
				echo "<tr><td colspan=2 align=right>".$GUI->Button(IWARE_5)."</td></tr>\n";
				echo "</table></center>";
				$GUI->CloseWidget ();
				$GUI->CloseForm ();	
			?>
			</center>
			</p>
			</body>
			</html>
		<?php
		}

		/**
		 * Returns an encrypted versionof a supplied password string
		 *
		 * @param string $input Password string to encrypt
		 * @return string
		 * @access private
		 */
		function EncryptPassword ($input)
			{
			$output = crypt($input,$input);
			return $output;
			}
		
		///////////////////////////////////////////////////////////////////////////////
		// System Summary
		///////////////////////////////////////////////////////////////////////////////

		/**
		 * Outputs the system summary interface
		 *
		 * @access private
		 */
		function SystemSummary ()
			{
			global $GUI,$_SERVER;
			$GUI->OpenWidget(IWARE_6);
			echo "<table width=\"90%\" cellpadding=3 cellspacing=0>";
			echo "<tr><td colspan=2><img src=\"images/splash.jpg\" width=\"550\" height=\"100\" border=1 alt=\"iWare Professional\"></td></tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_7)."</td>\n";
			echo "<td>".$_SERVER['SERVER_SOFTWARE'] ."</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_8)."</td>\n";
			echo "<td>".phpversion ()."</td>\n";
			echo "</tr>";			
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_9)."</td>\n";
			echo "<td>".$this->StatDB ()."</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label("iWare ".IWARE_10)."</td>\n";
			echo "<td>".IWARE_VERSION."</td>\n";
			echo "</tr>";		
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_11)."</td>\n";
			echo "<td>".$this->Users_GetCount ()."</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_12)."</td>\n";
			echo "<td>".$this->Group_GetCount ()."</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_13)."</td>\n";
			echo "<td>".$this->Docs_GetCount ()."</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_14)."</td>\n";
			echo "<td>".$this->File_GetCount ()."</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_15)."</td>\n";
			echo "<td>".$this->Nav_GetCount ()."</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_16)."</td>\n";
			echo "<td>".$this->Skins_GetCount ()."</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_17)."</td>\n";
			echo "<td>".$this->Mod_GetCount ()."</td>\n";
			echo "</tr>";
			echo "</table>";
			$GUI->CloseWidget();
			}

		///////////////////////////////////////////////////////////////////////////////
		// User Management
		///////////////////////////////////////////////////////////////////////////////

		/**
		 * Outputs the user management interface
		 *
		 * @access private
		 */
		function Users_Manager ()
			{
			global $GUI;
			$GUI->OpenWidget(IWARE_18);
			$result=$this->query("select * from ".IWARE_USERS." order by username");
			echo "<table width=\"90%\" cellpadding=3 cellspacing=0>";
			echo "<tr>";
			echo "<td bgcolor=\"#c0c0c0\" align=\"center\">".$GUI->Label(IWARE_19)."</td>";
			echo "<td bgcolor=\"#c0c0c0\" align=\"center\">".$GUI->Label(IWARE_20)."</td>";
			echo "<td bgcolor=\"#c0c0c0\" align=\"center\">".$GUI->Label(IWARE_21)."</td>";
			$GUI->OpenForm("","users.php?S=1","");			
			echo "<td colspan=2 bgcolor=\"#c0c0c0\" align=\"center\">".$GUI->Button(IWARE_22)."</td>";
			$GUI->CloseForm();
			echo "</tr>";
			$row=0;
			for($i=0;$i<$this->countResult($result);$i++)
				{
				if($row==0){$color="#ffffff";}
				else if($row==1){$color="#f5f5f5";}
				echo "<tr>";
				echo "<td bgcolor=\"$color\">".$this->result($result,$i,"realname")."</td>";	
				echo "<td bgcolor=\"$color\">".$this->result($result,$i,"username")."</td>";	
				echo "<td bgcolor=\"$color\">".$this->Group_GetGroupName($this->result($result,$i,"group_id"))."</td>";
				$GUI->OpenForm("","users.php?S=3&id=".$this->result($result,$i,"id"),"");			
				echo "<td  bgcolor=\"$color\" align=\"center\">".$GUI->Button(IWARE_23)."</td>";
				$GUI->CloseForm();
				if($this->result($result,$i,"is_admin")==1)
					{echo "<td  bgcolor=\"$color\" align=\"center\">".$GUI->Label("X")."</td>";}
				else
					{
					$GUI->OpenForm("","users.php?S=5&id=".$this->result($result,$i,"id"),"return ConfirmDeleteUser ()");			
					echo "<td  bgcolor=\"$color\" align=\"center\">".$GUI->Button(IWARE_24)."</td>";
					$GUI->CloseForm();
					}
				echo "</tr>";
				if($row==0){$row=1;}
				else if($row==1){$row=0;}
				}
			echo "</table>";
			$this->freeResult($result);
			$GUI->CloseWidget();
			}

		/**
		 * Outputs the add new user interface
		 *
		 * @access private
		 */
		function Users_AddForm ()
			{
			global $GUI;
			$GUI->OpenForm("userForm","users.php?S=2","return ValidateUserForm ()");
			$GUI->OpenWidget(IWARE_25);
			echo "<table width=\"90%\" cellpadding=3 cellspacing=0>\n";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_26)."</td>\n";
			echo "<td>".$GUI->TextBox("realname","",30) ."</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_27)."</td>\n";
			echo "<td>\n";
			$GUI->OpenListBox("group_id",1);
			$groups=$this->Group_GetGroupList();
			for($i=0;$i<count($groups);$i++)
				{$GUI->ListOption ($groups[$i],$this->Group_GetGroupName ($groups[$i]));}
			$GUI->CloseListBox ();
			echo "</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_28)."</td>\n";
			echo "<td>".$GUI->TextBox("username","",12) ."</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_29)."</td>\n";
			echo "<td>".$GUI->PwdBox("password","",12) ."</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_30)."</td>\n";
			echo "<td>".$GUI->PwdBox("cpassword","",12) ."</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td colspan=2 align=center><br />".$GUI->Button(IWARE_31)."</td>\n";
			echo "</tr>";
			echo "</table>\n";
			$GUI->CloseWidget();
			$GUI->CloseForm ();
			}

		/**
		 * Adds a user account to the system
		 *
		 * @param string $realname The desired name for the user account
		 * @param string $group_id The desired group ID for the user account
		 * @param string $username The desired username for the user account
		 * @param string $password The desired password for the user account
		 * @access private
		 */
		function Users_Add ($realname,$group_id,$username,$password)
			{
			global $GUI;
			$id=md5(uniqid(rand(),1));
			$username=trim($username);
			$password=trim($password);
			$password = $this->EncryptPassword ($password);
			$this->query("insert into ".IWARE_USERS." (id,is_admin,group_id,realname,username,password) values ('$id','0','$group_id','$realname','$username','$password')");
			$GUI->Message(IWARE_32);
			$GUI->Navigate("users.php?");
			}

		/**
		 * Outputs the edit user account interface
		 *
		 * @param string $id The record ID of the user account to be displayed for editing
		 * @access private
		 */
		function Users_UpdateForm ($id)
			{
			global $GUI;
			$result=$this->query("select * from ".IWARE_USERS." where id='$id' limit 1");
			$GUI->OpenForm("userForm","users.php?S=4&id=".$this->result($result,0,"id")."","return ValidateUserForm ()");
			$GUI->OpenWidget(IWARE_33);
			echo "<table width=\"90%\" cellpadding=3 cellspacing=0>\n";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_34)."</td>\n";
			echo "<td>".$GUI->TextBox("realname",$this->result($result,0,"realname"),30) ."</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_35)."</td>\n";
			echo "<td>\n";
			$GUI->OpenListBox("group_id",1);
			$groups=$this->Group_GetGroupList();
			$group=$this->result($result,0,"group_id");
			$GUI->ListOption($group,$this->Group_GetGroupName ($group),1);
			for($i=0;$i<count($groups);$i++)
				{$GUI->ListOption ($groups[$i],$this->Group_GetGroupName ($groups[$i]),0);}
			$GUI->CloseListBox ();
			echo "</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_36)."</td>\n";
			echo "<td>".$GUI->TextBox("username",$this->result($result,0,"username"),12) ."</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_37)."</td>\n";
			$password = $this->result($result,0,"password");
			echo "<td>".$GUI->PwdBox("password",$password,12) ."</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_38)."</td>\n";
			echo "<td>".$GUI->PwdBox("cpassword",$password,12) ."</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td colspan=2 align=center><br />".$GUI->Button(IWARE_39)."</td>\n";
			echo "</tr>";
			echo "</table>\n";
			$GUI->CloseWidget();
			$GUI->CloseForm ();	
			$this->freeResult($result);
			}

		/**
		 * Updates an existing user account in the system
		 *
		 * @param string $id The record ID for the user account that is to be updated
		 * @param string $realname The desired name for the user account
		 * @param string $group_id The desired group ID for the user account
		 * @param string $username The desired username for the user account
		 * @param string $password The desired password for the user account
		 * @access private
		 */
		function Users_Update ($id,$realname,$group_id,$username,$password)
			{
			global $GUI;
			$username=trim($username);
			$password=trim($password);
			$data=$this->query("select * from ".IWARE_USERS." where id='$id' limit 1");
			$oldpwd = $this->Result($data,0,"password");
			$this->freeResult($data);
			if ($oldpwd != $password)
				{
				$password = $this->EncryptPassword ($password);
				$this->query("update ".IWARE_USERS." set realname='$realname',group_id='$group_id',username='$username',password='$password' where id='$id' ");
				}
			else
				{
				$this->query("update ".IWARE_USERS." set realname='$realname',group_id='$group_id',username='$username' where id='$id' ");
				}
			$GUI->Message(IWARE_40);
			$GUI->Navigate("users.php?");			
			}

		/**
		 * Deletes a user account from the system
		 *
		 * @param string $id The record ID of the user account to be deleted from the system
		 * @access private
		 */
		function Users_Delete ($id)
			{
			global $GUI;
			$this->query("delete from ".IWARE_USERS." where id='$id' ");
			$GUI->Message(IWARE_41);
			$GUI->Navigate("users.php");
			}

		/**
		 * Returns a total count of all user accounts defined in the system
		 *
		 * @return integer
		 * @access private
		 */
		function Users_GetCount ()
			{
			$result=$this->query("select id from ".IWARE_USERS);
			$count=$this->countResult($result);
			$this->freeResult($result);
			return $count;			
			}

		/**
		 * Returns the name of a user account by the user accounts record ID
		 *
		 * @param string $id The record ID of the user account
		 * @return string
		 * @access private
		 */
		function Users_GetUserName ($id)
			{
			$result=$this->query("select username from ".IWARE_USERS." where id='$id' limit 1");
			$username=$this->result($result,0,"username");
			$this->freeResult($result);
			return $username;		
			}

		/**
		 * Returns the ID of a user group of which a user account belongs by the user accounts record ID
		 *
		 * @param string $id The record ID of the user account
		 * @return string
		 * @access private
		 */
		function Users_GetUserGroup ($id)
			{
			$result=$this->query("select group_id from ".IWARE_USERS." where id='$id' limit 1");
			$group=$this->result($result,0,"group_id");
			$this->freeResult($result);
			return $group;		
			}

		///////////////////////////////////////////////////////////////////////////////
		// Group Permission Levels
		///////////////////////////////////////////////////////////////////////////////

		/**
		 * Outputs the group management interface
		 *
		 * @access private
		 */
		function Group_Manager ()
			{
			global $GUI;
			$result=$this->query("select * from ".IWARE_GROUPS." order by groupname");
			$GUI->OpenWidget(IWARE_42);
			echo "<table width=\"90%\" cellpadding=3 cellspacing=0>";
			echo "<tr>";
			echo "<td bgcolor=\"#c0c0c0\" align=\"center\">".$GUI->Label(IWARE_43)."</td>";
			$GUI->OpenForm("","groups.php?S=1","");			
			echo "<td colspan=2 bgcolor=\"#c0c0c0\" align=\"center\">".$GUI->Button(IWARE_44)."</td>";
			$GUI->CloseForm();
			echo "</tr>";
			$row=0;
			for($i=0;$i<$this->countResult($result);$i++)
				{
				if($row==0){$color="#FFFFFF";}
				else if($row==1){$color="#f5f5f5";}
				echo "<tr>";
				echo "<td bgcolor=\"$color\">".$this->result($result,$i,"groupname")."</td>";	
				$GUI->OpenForm("","groups.php?S=3&id=".$this->result($result,$i,"id"),"");			
				echo "<td  bgcolor=\"$color\" align=\"center\">".$GUI->Button(IWARE_45)."</td>";
				$GUI->CloseForm();
				if($this->result($result,$i,"id")==1)
					{echo "<td  bgcolor=\"$color\" align=\"center\">".$GUI->Label("X")."</td>";}
				else
					{
					$GUI->OpenForm("","groups.php?S=5&id=".$this->result($result,$i,"id"),"return ConfirmDeleteGroup ()");			
					echo "<td  bgcolor=\"$color\" align=\"center\">".$GUI->Button(IWARE_46)."</td>";
					$GUI->CloseForm();
					}
				echo "</tr>";
				if($row==0){$row=1;}
				else if($row==1){$row=0;}
				}
			echo "</table>";
			$this->freeResult($result);
			$GUI->CloseWidget();
			}

		/**
		 * Outputs the add new group interface
		 *
		 * @access private
		 */
		function Group_AddForm ()
			{
			global $GUI;
			$GUI->OpenForm("groupForm","groups.php?S=2","return ValidateGroupForm ()");
			$GUI->OpenWidget(IWARE_47);
			echo "<table width=\"90%\" cellpadding=3 cellspacing=0>\n";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_48)."</td>\n";
			echo "<td>".$GUI->TextBox("groupname","",30) ."</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_49)."</td>\n";
			echo "<td>\n";
			$GUI->OpenListBox("allow_users",1);
			$GUI->ListOption (1,"Yes");
			$GUI->ListOption (0,"No",1);
			$GUI->CloseListBox ();
			echo "</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_50)."</td>\n";
			echo "<td>\n";
			$GUI->OpenListBox("allow_groups",1);
			$GUI->ListOption (1,"Yes");
			$GUI->ListOption (0,"No",1);
			$GUI->CloseListBox ();
			echo "</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_51)."</td>\n";
			echo "<td>\n";
			$GUI->OpenListBox("allow_header",1);
			$GUI->ListOption (1,"Yes");
			$GUI->ListOption (0,"No",1);
			$GUI->CloseListBox ();
			echo "</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_52)."</td>\n";
			echo "<td>\n";
			$GUI->OpenListBox("allow_footer",1);
			$GUI->ListOption (1,"Yes");
			$GUI->ListOption (0,"No",1);
			$GUI->CloseListBox ();
			echo "</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_53)."</td>\n";
			echo "<td>\n";
			$GUI->OpenListBox("allow_skin",1);
			$GUI->ListOption (1,"Yes");
			$GUI->ListOption (0,"No",1);
			$GUI->CloseListBox ();
			echo "</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_54)."</td>\n";
			echo "<td>\n";
			$GUI->OpenListBox("allow_nav",1);
			$GUI->ListOption (1,"Yes");
			$GUI->ListOption (0,"No",1);
			$GUI->CloseListBox ();
			echo "</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_55)."</td>\n";
			echo "<td>\n";
			$GUI->OpenListBox("allow_order",1);
			$GUI->ListOption (1,"Yes");
			$GUI->ListOption (0,"No",1);
			$GUI->CloseListBox ();
			echo "</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_56)."</td>\n";
			echo "<td>\n";
			$GUI->OpenListBox("allow_docs",1);
			$GUI->ListOption (1,"Yes");
			$GUI->ListOption (0,"No",1);
			$GUI->CloseListBox ();
			echo "</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_57)."</td>\n";
			echo "<td>\n";
			$GUI->OpenListBox("allow_files",1);
			$GUI->ListOption (1,"Yes");
			$GUI->ListOption (0,"No",1);
			$GUI->CloseListBox ();
			echo "</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_58)."</td>\n";
			echo "<td>\n";
			$GUI->OpenListBox("allow_mods",1);
			$GUI->ListOption (1,"Yes");
			$GUI->ListOption (0,"No",1);
			$GUI->CloseListBox ();
			echo "</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td colspan=2 align=center><br />".$GUI->Button(IWARE_59)."</td>\n";
			echo "</tr>";
			echo "</table>\n";
			$GUI->CloseWidget();
			$GUI->CloseForm ();
			}

		/**
		 * Adds a user group to the system
		 *
		 * @access private
		 */
		function Group_Add ()
			{
			global $GUI;
			global $groupname,$allow_users,$allow_groups,$allow_header,$allow_footer,$allow_skin;
			global $allow_docs,$allow_nav,$allow_order,$allow_files,$allow_mods;
			$id=md5(uniqid(rand(),1));
			$this->query("insert into ".IWARE_GROUPS." (id,groupname,allow_users,allow_groups,allow_header,allow_footer,allow_skin,allow_docs,allow_nav,allow_order,allow_files,allow_mods) values ('$id','".trim($groupname)."','$allow_users','$allow_groups','$allow_header','$allow_footer','$allow_skin','$allow_docs','$allow_nav','$allow_order','$allow_files','$allow_mods')");
			$GUI->Message(IWARE_60);
			$GUI->Navigate("groups.php?");
			}

		/**
		 * Outputs the edit user group interface
		 *
		 * @param string $id The record ID of the user group to be displayed for editing
		 * @access private
		 */
		function Group_UpdateForm ($id)
			{
			global $GUI;
			$result=$this->query("select * from ".IWARE_GROUPS." where id='$id' ");
			$GUI->OpenForm("groupForm","groups.php?S=4&id=$id","return ValidateGroupForm ()");
			$GUI->OpenWidget(IWARE_61);
			echo "<table width=\"90%\" cellpadding=3 cellspacing=0>\n";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_62)."</td>\n";
			echo "<td>".$GUI->TextBox("groupname",$this->result($result,0,"groupname"),30) ."</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_63)."</td>\n";
			echo "<td>\n";
			$GUI->OpenListBox("allow_users",1);
			$allow=$this->result($result,0,"allow_users");
			$GUI->ListOption ($allow,($allow==1)?"Yes":"No",1);
			$GUI->ListOption (1,"Yes");
			$GUI->ListOption (0,"No");
			$GUI->CloseListBox ();
			echo "</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_64)."</td>\n";
			echo "<td>\n";
			$GUI->OpenListBox("allow_groups",1);
			$allow=$this->result($result,0,"allow_groups");
			$GUI->ListOption ($allow,($allow==1)?"Yes":"No",1);
			$GUI->ListOption (1,"Yes");
			$GUI->ListOption (0,"No");
			$GUI->CloseListBox ();
			echo "</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_65)."</td>\n";
			echo "<td>\n";
			$GUI->OpenListBox("allow_header",1);
			$allow=$this->result($result,0,"allow_header");
			$GUI->ListOption ($allow,($allow==1)?"Yes":"No",1);
			$GUI->ListOption (1,"Yes");
			$GUI->ListOption (0,"No");
			$GUI->CloseListBox ();
			echo "</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_66)."</td>\n";
			echo "<td>\n";
			$GUI->OpenListBox("allow_footer",1);
			$allow=$this->result($result,0,"allow_footer");
			$GUI->ListOption ($allow,($allow==1)?"Yes":"No",1);
			$GUI->ListOption (1,"Yes");
			$GUI->ListOption (0,"No");
			$GUI->CloseListBox ();
			echo "</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_67)."</td>\n";
			echo "<td>\n";
			$GUI->OpenListBox("allow_skin",1);
			$allow=$this->result($result,0,"allow_skin");
			$GUI->ListOption ($allow,($allow==1)?"Yes":"No",1);
			$GUI->ListOption (1,"Yes");
			$GUI->ListOption (0,"No");
			$GUI->CloseListBox ();
			echo "</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_68)."</td>\n";
			echo "<td>\n";
			$GUI->OpenListBox("allow_nav",1);
			$allow=$this->result($result,0,"allow_nav");
			$GUI->ListOption ($allow,($allow==1)?"Yes":"No",1);
			$GUI->ListOption (1,"Yes");
			$GUI->ListOption (0,"No");
			$GUI->CloseListBox ();
			echo "</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_69)."</td>\n";
			echo "<td>\n";
			$GUI->OpenListBox("allow_order",1);
			$allow=$this->result($result,0,"allow_order");
			$GUI->ListOption ($allow,($allow==1)?"Yes":"No",1);
			$GUI->ListOption (1,"Yes");
			$GUI->ListOption (0,"No");
			$GUI->CloseListBox ();
			echo "</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_70)."</td>\n";
			echo "<td>\n";
			$GUI->OpenListBox("allow_docs",1);
			$allow=$this->result($result,0,"allow_docs");
			$GUI->ListOption ($allow,($allow==1)?"Yes":"No",1);
			$GUI->ListOption (1,"Yes");
			$GUI->ListOption (0,"No");
			$GUI->CloseListBox ();
			echo "</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_71)."</td>\n";
			echo "<td>\n";
			$GUI->OpenListBox("allow_files",1);
			$allow=$this->result($result,0,"allow_files");
			$GUI->ListOption ($allow,($allow==1)?"Yes":"No",1);
			$GUI->ListOption (1,"Yes");
			$GUI->ListOption (0,"No");
			$GUI->CloseListBox ();
			echo "</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_72)."</td>\n";
			echo "<td>\n";
			$GUI->OpenListBox("allow_mods",1);
			$allow=$this->result($result,0,"allow_mods");
			$GUI->ListOption ($allow,($allow==1)?"Yes":"No",1);
			$GUI->ListOption (1,"Yes");
			$GUI->ListOption (0,"No");
			$GUI->CloseListBox ();
			echo "</td>\n";
			echo "</tr>";
			echo "<tr>\n";
			echo "<td colspan=2 align=center><br />".$GUI->Button(IWARE_73)."</td>\n";
			echo "</tr>";
			echo "</table>\n";
			$GUI->CloseWidget();
			$GUI->CloseForm ();
			$this->freeResult($result);
			}

		/**
		 * Updates an existing user group in the system
		 *
		 * @param string $id The record ID for the user group that is to be updated
		 * @access private
		 */
		function Group_Update ($id)
			{
			global $GUI;
			global $groupname,$allow_users,$allow_groups,$allow_header,$allow_footer,$allow_skin;
			global $allow_docs,$allow_nav,$allow_order,$allow_files,$allow_mods;
			$this->query("update ".IWARE_GROUPS." set groupname='$groupname',allow_users='$allow_users',allow_groups='$allow_groups',allow_header='$allow_header',allow_footer='$allow_footer',allow_skin='$allow_skin',allow_docs='$allow_docs',allow_nav='$allow_nav',allow_order='$allow_order',allow_files='$allow_files',allow_mods='$allow_mods' where id='$id' ");
			$GUI->Message(IWARE_74);
			$GUI->Navigate("groups.php?");
			}

		/**
		 * Deletes a user group from the system
		 *
		 * @param string $id The record ID of the user group to be deleted from the system
		 * @access private
		 */
		function Group_Delete ($id)
			{
			global $GUI;
			$this->query("delete from ".IWARE_GROUPS." where id='$id' ");
			$GUI->Message(IWARE_75);
			$GUI->Navigate("groups.php?");
			}

		/**
		 * Returns a total count of all user groups defined in the system
		 *
		 * @return integer
		 * @access private
		 */
		function Group_GetCount ()
			{
			$result=$this->query("select id from ".IWARE_GROUPS);
			$count=$this->countResult($result);
			$this->freeResult($result);
			return $count;					
			}

		/**
		 * Returns an array containing the record ID of all user groups defined in the system
		 *
		 * @return array
		 * @access private
		 */
		function Group_GetGroupList ()
			{
			$groups=Array();
			$result=$this->query("select id from ".IWARE_GROUPS);
			for($i=0;$i<$this->countResult($result);$i++)
				{array_push($groups,$this->result($result,$i,"id"));}
			$this->freeResult($result);
			return $groups;				
			}

		/**
		 * Retuns the name of a user group by its record ID
		 *
		 * @param string $id The record ID of the user group
		 * @return string
		 * @access private
		 */
		function Group_GetGroupName ($id)
			{
			$result=$this->query("select groupname from ".IWARE_GROUPS." where id='$id' limit 1");
			$group=$this->result($result,0,"groupname");
			$this->freeResult($result);
			return $group;		
			}

		/**
		 * Returns an associative array of permission levels for control panel functions for a given user group
		 *
		 * @param string $id The record ID of the user group
		 * @return array
		 * @access private
		 */
		function Group_GetGroupAuth ($id)
			{
			$auth=Array();
			$result=$this->query("select * from ".IWARE_GROUPS." where id='$id' limit 1");
			$auth['allow_users']=$this->result($result,0,"allow_users");
			$auth['allow_groups']=$this->result($result,0,"allow_groups");
			$auth['allow_header']=$this->result($result,0,"allow_header");
			$auth['allow_footer']=$this->result($result,0,"allow_footer");
			$auth['allow_skin']=$this->result($result,0,"allow_skin");
			$auth['allow_docs']=$this->result($result,0,"allow_docs");
			$auth['allow_nav']=$this->result($result,0,"allow_nav");
			$auth['allow_order']=$this->result($result,0,"allow_order");
			$auth['allow_files']=$this->result($result,0,"allow_files");
			$auth['allow_mods']=$this->result($result,0,"allow_mods");
			$this->freeResult($result);
			return $auth;
			}

		///////////////////////////////////////////////////////////////////////////////
		// Site Header
		///////////////////////////////////////////////////////////////////////////////

		/**
		 * Outputs the site header management interface
		 *
		 * @access private
		 */
		function Header_Manager ()
			{
			global $GUI;
			$result=$this->query("select * from ".IWARE_HEADER." limit 1");
			$mode=$this->result($result,0,"display_mode");
			$GUI->OpenForm("headerForm","header.php?S=1","");
			$GUI->OpenWidget(IWARE_76);
			echo "<table width=\"90%\" cellpadding=3 cellspacing=0>\n";
			echo "<tr>\n";
			echo "<td colspan=2>".$GUI->CheckBox("is_enabled",1,$this->result($result,0,"is_enabled"))." ".$GUI->Label(IWARE_77)."<br /><br /></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "<td colspan=2 bgcolor=#FFFFFF>".$GUI->RadioOption("display_mode",0,($mode==0)?1:0)." ".$GUI->Label(IWARE_78)."</td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "<td colspan=2>".$GUI->TextArea("title_text",$this->result($result,0,"title_text"),8,70)."</td>\n";
			echo "<script language=\"javascript1.2\">editor_generate('title_text');</script>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "<td colspan=2 bgcolor=#FFFFFF>".$GUI->RadioOption("display_mode",1,($mode==1)?1:0)." ".$GUI->Label(IWARE_83)."</td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_84)."</td>\n";
			echo "<td>\n";
			$GUI->OpenListBox("image_name",1);
			$GUI->ListOption($this->result($result,0,"image_name"),$this->result($result,0,"image_name"),1);
			$this->Header_ImageListBox();
			$GUI->CloseListBox ();
			echo "</td>\n";
			echo "</tr>\n";			
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_85)."</td>\n";
			echo "<td>".$GUI->TextBox("image_alt",$this->result($result,0,"image_alt"),30)."</td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_86)."</td>\n";
			echo "<td>".$GUI->TextBox("image_border",$this->result($result,0,"image_border"),2)." (px.)</td>\n";
			echo "</tr>\n";
			echo "</tr>\n";	
			echo "<td colspan=2 align=center><br />".$GUI->Button(IWARE_87)."</td>\n";
			echo "</tr>\n";			
			echo "</table>\n";
			$GUI->CloseWidget();
			$GUI->CloseForm ();
			$this->freeResult($result);
			}

		/**
		 * Updates changes made to the site header
		 *
		 * @access private
		 */
		function Header_Update ()
			{
			global $GUI;
			global $is_enabled,$display_mode,$image_name,$image_alt,$image_border,$title_text;
			$this->query("update ".IWARE_HEADER." set is_enabled='$is_enabled',display_mode='$display_mode',image_name='$image_name',image_alt='$image_alt',image_border='$image_border',title_text='$title_text' ");
			$GUI->Message(IWARE_88);
			$GUI->Navigate("header.php?");
			}

		/**
		 * Outputs a number of HTML form option tags containing names of images present in the system from the files/ directory of the installation
		 *
		 * @access private
		 */
		function Header_ImageListBox ()
			{
			global $GUI;
			if ($handle = opendir('../files/')) 
				{
				while (false !== ($file = readdir($handle))){if ($file != "." && $file != ".."){$GUI->ListOption("$file","$file");}}
				closedir($handle); 
				}
			}

		/**
		 * This method is an alias to LoadFontsListBox ()
		 *
		 * @access private
		 */
		function Header_FontListBox ()
			{
			$this->LoadFontsListBox ();
			}

		/**
		 * Outputs a number of HTML form option elements containing font size in points from 1 to 8
		 *
		 * @access private
		 */
		function Header_FontSizeListBox ()
			{
			global $GUI;
			for($i=1;$i<8;$i++){$GUI->ListOption($i,$i);}
			}

		///////////////////////////////////////////////////////////////////////////////
		// Site Footer
		///////////////////////////////////////////////////////////////////////////////

		/**
		 * Outputs the site footer management interface
		 *
		 * @access private
		 */
		function Footer_Manager ()
			{
			global $GUI;
			$result=$this->query("select * from ".IWARE_FOOTER." limit 1");
			$GUI->OpenForm("footerForm","footer.php?S=1","");
			$GUI->OpenWidget(IWARE_89);
			echo "<table width=\"90%\" cellpadding=3 cellspacing=0>\n";
			echo "<tr>\n";
			echo "<td>".$GUI->CheckBox("is_enabled",1,$this->result($result,0,"is_enabled"))." ".$GUI->Label(IWARE_90)."<br /><br /></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_91)."<br />".$GUI->TextArea("footer_text",$this->result($result,0,"footer_text"),8,70)."</td>\n";
			echo "<script language=\"javascript1.2\">editor_generate('footer_text');</script>\n";
			echo "</tr>\n";
			echo "<td align=center><br />".$GUI->Button(IWARE_92)."</td>\n";
			echo "</tr>\n";			
			echo "</table>\n";
			$GUI->CloseWidget();
			$GUI->CloseForm ();
			$this->freeResult($result);
			}

		/**
		 * Updates changes made to the site footer
		 *
		 * @access private
		 */
		function Footer_Update ()
			{
			global $GUI;
			global $is_enabled,$footer_text;
			$this->query("update ".IWARE_FOOTER." set is_enabled='$is_enabled',footer_text='$footer_text' ");
			$GUI->Message(IWARE_93);
			$GUI->Navigate("footer.php?");
			}

		///////////////////////////////////////////////////////////////////////////////
		// Skins
		///////////////////////////////////////////////////////////////////////////////

		/**
		 * Outputs the choose active skin interface
		 *
		 * @access private
		 */
		function Skins_Manager ()
			{
			global $GUI;
			$result=$this->query("select active_skin from ".IWARE_CONFIG." limit 1");
			$GUI->OpenForm("skinForm","skin.php?S=1","");
			$GUI->OpenWidget(IWARE_94);
			echo "<table cellpadding=3 cellspacing=0>\n";
			echo "<tr><td colspan=3 align=center><br />";
			$this->Skins_PreviewImage ($this->result($result,0,"active_skin"));
			echo "<br /><br /></td></tr>";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_95)."</td>\n";
			echo "<td>\n";
			$GUI->OpenListBox("active_skin",1);
			$GUI->ListOption($this->result($result,0,"active_skin"),$this->result($result,0,"active_skin"),1);
			$this->Skins_ListBox();
			$GUI->CloseListBox ();
			echo "</td>\n";
			echo "<td>".$GUI->Button(IWARE_96)."</td>\n";
			echo "</tr>\n";	
			echo "</table>\n";
			$GUI->CloseWidget();
			$GUI->CloseForm ();
			$this->freeResult($result);
			}

		/**
		 * Sets the active skin for the site at a global level
		 *
		 * @access private
		 */
		function Skins_Choose ()
			{
			global $GUI;
			global $active_skin;
			$this->query("update ".IWARE_CONFIG." set active_skin='$active_skin' ");
			$GUI->Message(IWARE_97);
			$GUI->Navigate("skin.php?");		
			}

		/**
		 * Outputs a number of HTML form option elements containing names of skins available to the system from the skins/ directory of the installation
		 *
		 * @access private
		 */
		function Skins_ListBox ()
			{
			global $GUI;
			if ($handle = opendir('../skins/')) 
				{
				while (false !== ($file = readdir($handle))){if ($file != "." && $file != ".."){$GUI->ListOption("$file","$file");}}
				closedir($handle); 
				}
			}

		/**
		 * Outputs a thumbnail screenshot of the currently active skin in the system. If a thumbnail is not available for the given skin name then a no screenshot available message will be displayed instead. The thumbnail image for a given skin must be named screenshot.jpg.
		 *
		 * @param string $skin The name of the skin to display a thumbnail for
		 * @access private
		 */
		function Skins_PreviewImage ($skin)
			{
			$screen="../skins/".$skin."/screenshot.jpg";
			if(!file_exists("../skins/".$skin."/screenshot.jpg"))
				{echo "<i>".IWARE_98."</i>\n";}
			else
				{
				$size=getImageSize($screen);
				echo "<img src=\"$screen\" ".$size[3]." border=1>\n";
				}
			}

		/**
		 * Returns a total count of all skins available to the system
		 *
		 * @return integer
		 * @access private
		 */
		function Skins_GetCount ()
			{
			$count=0;
			if ($handle = opendir('../skins/')) 
				{
				while (false !== ($file = readdir($handle))){if ($file != "." && $file != ".."){$count++;}}
				closedir($handle); 
				}
			else {$count="error";}
			return $count;
			}

		///////////////////////////////////////////////////////////////////////////////
		// Document Control
		///////////////////////////////////////////////////////////////////////////////

		/**
		 * Works with Docs_Manager () to recursively output the site document structure to the user
		 *
		 * @param string $id The record ID of a given document to recurse from, the supplied record will be used as the starting point for the document recursion
		 * @param integer $indent The curent indice of the recursion used to create a spacer to form the document tree view
		 * @access private
		 */
		function Docs_Recurse ($id,$indent=0)
			{
			global $GUI;
			if($this->Nav_HasSubNav ($id))
				{
				$indent++;
				$links=$this->Nav_GetSubNav ($id);
				for($i=0;$i<count($links);$i++)
					{
					echo "<tr><td>";
					echo "<img src=\"images/tree.gif\" height=1 width=\"".($indent * 20)."\">";
					echo $this->Nav_GetLinkText ($links[$i]). "</td>";
					$GUI->OpenForm("","editor.php?mode=1&id=".$links[$i],"");
					echo "<td>".$GUI->Button(IWARE_104)."</td>";
					$GUI->CloseForm ();
					$GUI->OpenForm("","docs.php?S=3&id=".$links[$i],"");
					echo "<td>".$GUI->Button(IWARE_105)."</td>";
					$GUI->CloseForm ();
					echo "</tr>";
					$this->Docs_Recurse($links[$i],$indent);
					}
				$indent--;
				}
			}

		/**
		 * Outputs the documents manager interface
		 *
		 * @access private
		 */
		function Docs_Manager ()
			{
			global $GUI;			
			$GUI->OpenWidget(IWARE_99);			
			echo "<table width=90% cellpadding=3 cellspacing=0>\n";		
			$result=$this->query("select * from ".IWARE_DOCS." where parent_id='0' order by nav_order");
			echo "<tr>\n";
			echo "<td bgcolor=#c0c0c0>".$GUI->Label(IWARE_100)."</td>\n";
			$GUI->OpenForm("","editor.php?mode=0","");
			echo "<td bgcolor=#c0c0c0>".$GUI->Button(IWARE_102)."</td>";
			$GUI->CloseForm ();
			echo "<td bgcolor=#c0c0c0>&nbsp;</td>";
			echo "</tr>\n";				
			$row=0;
			for($i=0;$i<$this->countResult($result);$i++)
				{
				echo "<tr><td>".$this->Nav_GetLinkText ($this->result($result,$i,"id")). "</td>";
				$GUI->OpenForm("","editor.php?mode=1&id=".$this->result($result,$i,"id"),"");
				echo "<td>".$GUI->Button(IWARE_104)."</td>";
				$GUI->CloseForm ();
				$GUI->OpenForm("","docs.php?S=3&id=".$this->result($result,$i,"id"),"");
				if($this->result($result,$i,"id")!="1"){echo "<td>".$GUI->Button(IWARE_105)."</td>";}
				else{echo "<td align=center><b>X</b></td>";}
				$GUI->CloseForm ();
				echo "</tr>";
				$this->Docs_Recurse($this->result($result,$i,"id"));			
				}
			$this->freeResult($result);
			echo "</table>\n";
			$GUI->CloseWidget();
			}

		/**
		 * Returns the next available document navigation ordering value for use when creating a new document in the system
		 *
		 * @return integer
		 * @access private
		 */
		function Docs_GetNextDocOrder ()
			{
			$count=$this->Docs_GetCount ();
			$count++;
			return $count;
			}

		/**
		 * Returns the link text for a given document by its record ID
		 *
		 * @param string $id The record ID of the document
		 * @return string
		 * @access private
		 */
		function Docs_GetName ($id)
			{
			global $GUI;
			$result=$this->query("select link_text from ".IWARE_DOCS." where id='$id' ");
			$link=$this->result($result,0,"link_text");
			$this->freeResult($result);
			return $link;
			}

		/**
		 * Adds a document to the system
		 *
		 * @param integer $use_text Optional vlue to indicate whether to use a 32 char key or the link text as the document ID, 1 uses the documents link text, 0 creates a 32 char ID. If this value is not given the default of 0 will be used.
		 * @access private
		 */
		function Docs_Add ($use_text=0)
			{
			global $GUI;
			global $is_hidden,$parent_id,$is_sublink,$use_mod;
			global $link_text,$meta_title,$meta_keywords,$meta_description,$htmlSource,$module;	
			if($use_text==0){$id=md5(uniqid(rand(),1));}
			else{$id=strtolower(str_replace(" ","_",$link_text));}
			if(!isset($is_hidden)){$is_hidden=1;}
			if(!isset($is_sublink)){$is_sublink=0;}
			if($is_sublink==0){$parent_id=0;}
			if(!isset($use_mod)){$use_mod=0;}
			if($use_mod==0){$module="";}
			$nav_order=$this->Docs_GetNextDocOrder ();
			$this->query("insert into ".IWARE_DOCS." (id,nav_order,is_hidden,parent_id,link_text,meta_title,meta_keywords,meta_description,doc_content,module) values ('$id','$nav_order','$is_hidden','$parent_id','$link_text','$meta_title','$meta_keywords','$meta_description','$htmlSource','$module')");
			$GUI->Message(IWARE_106);
			$GUI->Navigate("docs.php?");
			}

		/**
		 * Updates changes made to an existing document in the system
		 *
		 * @param string $id The record ID of the document to be displayed for editing
		 * @access private
		 */
		function Docs_Update ($id)
			{
			global $GUI;
			global $is_hidden,$parent_id,$is_sublink,$use_mod;
			global $link_text,$meta_title,$meta_keywords,$meta_description,$htmlSource,$module;
			if(!isset($is_hidden)){$is_hidden=1;}
			if(!isset($is_sublink)){$is_sublink=0;}
			if($is_sublink==0){$parent_id=0;}
			if(!isset($use_mod)){$use_mod=0;}
			if($use_mod==0){$module="";}
			$this->query("update ".IWARE_DOCS." set is_hidden='$is_hidden',parent_id='$parent_id',link_text='$link_text',meta_title='$meta_title',meta_keywords='$meta_keywords',meta_description='$meta_description',doc_content='$htmlSource',module='$module' where id='$id'");
			$GUI->Message(IWARE_107);
			$GUI->Navigate("docs.php?");
			}

		/**
		 * Deletes an existing document from the system
		 *
		 * @param string $id The record ID of the document to be deleted from the system
		 * @access private
		 */
		function Docs_Delete ($id)
			{
			global $GUI;
			if($this->Nav_HasSubNav($id))
				{
				$GUI->Message(IWARE_108);
				}
			else
				{
				$this->query("delete from ".IWARE_DOCS." where id='$id' ");
				$GUI->Message(IWARE_109);
				}
			$GUI->Navigate("docs.php?");
			}

		/**
		 * Returns a total count of all documents defined within the system
		 *
		 * @return integer
		 * @access private
		 */
		function Docs_GetCount ()
			{
			$result=$this->query("select id from ".IWARE_DOCS);
			$count=$this->countResult($result);
			$this->freeResult($result);
			return $count;	
			}

		/**
		 * Outputs a number of HTML form option elements containing names of documents defined in the system
		 *
		 * @access private
		 */
		function Docs_ListBox ()
			{
			global $GUI;
			$result=$this->query("select id,link_text from ".IWARE_DOCS." order by link_text");
			$count=$this->countResult($result);
			for($i=0;$i<$count;$i++)
				{$GUI->ListOption($this->result($result,$i,"id"),$this->result($result,$i,"link_text"));}
			$this->freeResult($result);
			}

		///////////////////////////////////////////////////////////////////////////////
		// Navigation
		///////////////////////////////////////////////////////////////////////////////

		/**
		 * Outputs the navigation manager interface
		 *
		 * @access private
		 */
		function Nav_Manager ()
			{
			global $GUI;
			$result=$this->query("select navbar_style from ".IWARE_CONFIG." limit 1");
			$GUI->OpenForm("navForm","navbar.php?S=1","");
			$GUI->OpenWidget(IWARE_110);
			echo "<table cellpadding=3 cellspacing=0>\n";
			echo "<tr>\n";
			echo "<td>".$GUI->Label(IWARE_111)."</td>\n";
			echo "<td>\n";
			$GUI->OpenListBox("navbar_style",1);
			$GUI->ListOption($this->result($result,0,"navbar_style"),$this->result($result,0,"navbar_style"),1);
			$this->Nav_ListBox();
			$GUI->CloseListBox ();
			echo "</td>\n";
			echo "<td>".$GUI->Button(IWARE_112)."</td>\n";
			echo "</tr>\n";	
			echo "</table>\n";
			$GUI->CloseWidget();
			$GUI->CloseForm ();
			$this->freeResult($result);
			}

		/**
		 * Updates the active navigation for the site at a global level
		 *
		 * @access private
		 */
		function Nav_Choose ()
			{
			global $GUI;
			global $navbar_style;
			$this->query("update ".IWARE_CONFIG." set navbar_style='$navbar_style' ");
			$GUI->Message(IWARE_113);
			$GUI->Navigate("navbar.php?");		
			}

		/**
		 * Outputs a number of HTML form option elements containing navigation styles available to the system form the navbar/ directory of the installation
		 *
		 * @access private
		 */
		function Nav_ListBox ()
			{
			global $GUI;
			if ($handle = opendir('../navbar/')) 
				{
				while (false !== ($file = readdir($handle))){if ($file != "." && $file != ".."){$GUI->ListOption("$file","$file");}}
				closedir($handle); 
				}
			}

		/**
		 * Works with Nav_Order () to recursively output the site document structure to the user
		 *
		 * @param string $id The record ID of a given document to recurse from, the supplied record will be used as the starting point for the document recursion
		 * @param integer $indent The curent indice of the recursion used to create a spacer to form the document tree view
		 * @access private
		 */
		function Nav_Recurse ($id,$indent=0)
			{
			global $GUI;
			if($this->Nav_HasSubNav ($id))
				{
				$indent++;
				$links=$this->Nav_GetSubNav ($id);
				for($i=0;$i<count($links);$i++)
					{
					echo "<tr>\n";
					echo "<td>";
					echo "<img src=\"images/tree.gif\" height=1 width=\"".($indent * 30)."\">";
					echo $GUI->TextBox("SUB_".$links[$i],$i,3)."&nbsp;\n";
					echo $GUI->Label($this->Nav_GetLinkText ($links[$i]))."</td>\n";
					echo "</tr>\n";
					$this->Nav_Recurse($links[$i],$indent);
					}
				$indent--;
				}
			}

		/**
		 * Outputs the navigation ordering interface
		 *
		 * @access private
		 */
		function Nav_Order ()
			{
			global $GUI;		
			$result=$this->query("select * from ".IWARE_DOCS." where parent_id='0' order by nav_order");
			$GUI->OpenForm("orderForm","order.php?S=1","return ValidateNavOrder ()");
			$GUI->OpenWidget(IWARE_114);
			echo "<table width=90% cellpadding=3 cellspacing=0>\n";			
			for($i=0;$i<$this->countResult($result);$i++)
				{
				echo "<tr>\n";
				echo "<td>".$GUI->TextBox("DOC_".$this->result($result,$i,"id"),$i,3)."&nbsp;\n";
				echo $GUI->Label($this->result($result,$i,"link_text"))."</td>\n";
				echo "</tr>\n";
				if($this->Nav_HasSubNav($this->result($result,$i,"id")))
					{$this->Nav_Recurse($this->result($result,$i,"id"));}
				}
			echo "<tr>\n";
			echo "<td colspan=2 align=center><br />".$GUI->Button(IWARE_115)."</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
			$GUI->CloseWidget();
			$GUI->CloseForm ();
			$this->freeResult($result);
			}

		/**
		 * Works with Nav_OrderUpdate () to recursively update the site document structure navigation ordering
		 *
		 * @param string $id The record ID of a given document to recurse from, the supplied record will be used as the starting point for the document recursion
		 * @access private
		 */
		function Nav_OrderUpdateRecurse ($id)
			{
			$sublinks=$this->query("select * from ".IWARE_DOCS." where parent_id='".$id."' ");
			for($j=0;$j<$this->countResult($sublinks);$j++)
				{
				$sub="SUB_".$this->result($sublinks,$j,"id");
				global ${$sub};
				$split=explode("_",$sub);
				$this->query("update ".IWARE_DOCS." set nav_order='".${$sub}."' where id='".$split[1]."' ");
				$this->Nav_OrderUpdateRecurse ($split[1]);
				}
			$this->freeResult($sublinks);	
			}

		/**
		 * Updates changes made to the site navigation order
		 *
		 * @access private
		 */
		function Nav_OrderUpdate ()
			{		
			global $GUI;
			$result=$this->query("select * from ".IWARE_DOCS." where parent_id='0' order by nav_order");
			for($i=0;$i<$this->countResult($result);$i++)
				{
				$doc="DOC_".$this->result($result,$i,"id");
				global ${$doc};
				$split=explode("_",$doc);
				$this->query("update ".IWARE_DOCS." set nav_order='".${$doc}."' where id='".$split[1]."' ");
				if($this->Nav_HasSubNav($this->result($result,$i,"id")))
					{$this->Nav_OrderUpdateRecurse ($this->result($result,$i,"id"));}
				}
			$GUI->Message(IWARE_116);
			$GUI->Navigate("order.php?");			
			}

		/**
		 * Returns the link text for a given document by its record ID
		 *
		 * @param string $id The record ID of the document
		 * @return string
		 * @access private
		 */
		function Nav_GetLinkText ($id)
			{
			$result=$this->query("select * from ".IWARE_DOCS." where id='$id' limit 1");
			$text=$this->result($result,0,"link_text");
			$this->freeResult($result);		
			return $text;
			}

		/**
		 * Returns an array containg the ID of all documents defined in the system for use with navigation
		 *
		 * @return array
		 * @access private
		 */
		function Nav_GetNav ()
			{
			$links=Array ();
			$result=$this->query("select * from ".IWARE_DOCS." where parent_id='0' order by nav_order");
			$count=$this->countResult($result);
			for($i=0;$i<$count;$i++)
				{array_push($links,$this->result($result,$i,"id"));}
			$this->freeResult($result);		
			return $links;
			}

		/**
		 * Returns an array containg the ID of all documents defined in the system beneath a given document by its record ID
		 *
		 * @param string $id The record ID of a document
		 * @return array
		 * @access private
		 */
		function Nav_GetSubNav ($id)
			{
			$links=Array ();
			$result=$this->query("select * from ".IWARE_DOCS." where parent_id='$id' order by nav_order");
			$count=$this->countResult($result);
			for($i=0;$i<$count;$i++)
				{array_push($links,$this->result($result,$i,"id"));}
			$this->freeResult($result);		
			return $links;
			}

		/**
		 * Returns TRUE if a given document has existing documents defined as child documents beneath it, returns FALSE otherwise
		 *
		 * @param string $id The record ID of a document
		 * @return boolean
		 * @access private
		 */
		function Nav_HasSubNav ($id)
			{
			$result=$this->query("select * from ".IWARE_DOCS." where parent_id='$id' ");
			$count=$this->countResult($result);
			$this->freeResult($result);		
			if($count>0){return true;}
			else{return false;}
			}

		/**
		 * Retuns a total count of all navigation styles availabkle to the system from the navbar/ directory of the installation
		 *
		 * @return integer
		 * @access private
		 */
		function Nav_GetCount ()
			{
			$count=0;
			if ($handle = opendir('../navbar/')) 
				{
				while (false !== ($file = readdir($handle))){if ($file != "." && $file != ".."){$count++;}}
				closedir($handle); 
				}
			else {$count="error";}
			return $count;
			}

		///////////////////////////////////////////////////////////////////////////////
		// Content Authoring
		///////////////////////////////////////////////////////////////////////////////

		/**
		 * Ouputs a number of HTML form option elements containing names of fonts available to the system from the fonts/DEFAULT support file
		 *
		 * @access private
		 */
		function LoadFontsListBox ()
			{
			$fd = @fopen ("fonts/DEFAULT", "r");
			$row=0;
			while (!feof($fd)) 
				{
				$buffer = fgets($fd, 4096);
				echo "<option value=\"".trim($buffer)."\">$buffer</option>\n";
				}	
			@fclose ($fd);			
			}

		/**
		 * Returns an array of font names avaialble to the system from the fonts/DEFAULT support file
		 *
		 * @return array
		 * @access private
		 */
		function GetFontsArray ()
			{
			$fonts=Array ();
			$fd = @fopen ("fonts/DEFAULT", "r");
			$row=0;
			while (!feof($fd)) 
				{
				$buffer = fgets($fd, 4096);
				$fonts[$row]=trim($buffer);
				$row++;
				}	
			@fclose ($fd);
			return $fonts;
			}

		///////////////////////////////////////////////////////////////////////////////
		// File Management
		///////////////////////////////////////////////////////////////////////////////

		/**
		 * Outputs the file upload interface
		 *
		 * @access private
		 */
		function File_UploadForm ()
				{
				global $GUI;
				echo "<form enctype=\"multipart/form-data\" method=post action=\"files.php?S=1\" onSubmit=\"\">";	
				$GUI->OpenWidget(IWARE_117);
				echo "<table width=90% cellpadding=3 cellspacing=0>\n";
				echo "<tr><td>";
				echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"1000000\">\n";
				echo "<input name=\"userfile\" type=\"file\" size=50>\n";		
				echo "</td></tr>";
				echo "<tr><td><input type=\"checkbox\" name=\"useIR\" value=1> ".IWARE_118." :<br><br><center>".IWARE_119." <input type=text size=5 name=resizeH> px  X  ".IWARE_120." <input type=text size=5 name=resizeW> px</center><br></td></tr>";
				echo "</table>";
				echo $GUI->Button(IWARE_121);
				$GUI->CloseWidget ();
				$GUI->CloseForm ();		
				}

		/**
		 * Uploads a file from the users local hard disk to the webserver and if indicated to do so uses resizeImage () to resixze an image to the specified dimensions after upload
		 *
		 * @access private
		 */
		function File_Upload ()
			{
			global $GUI,$_FILES,$useIR,$resizeW,$resizeH;
			$allowFileTypes=Array("image/gif","image/pjpeg","image/jpeg"); // array of allowed file mime types
			$type=$_FILES['userfile']['type'];
			$match=0;
			for($i=0;$i<count($allowFileTypes);$i++){if($allowFileTypes[$i]==$type){$match=1;}}
			if($match==1)
				{		
				$sent=@move_uploaded_file($_FILES['userfile']['tmp_name'], "../files/".$_FILES['userfile']['name']);
				if(!$sent){$GUI->Message(IWARE_122);}
				else
					{
					$GUI->Message(IWARE_123);
					if(isset($useIR)&&$useIR==1)
						{
						if(!$this->resizeImage("","",$_FILES['userfile']['name'],$resizeW,$resizeH,$type))
							{$GUI->Message(IWARE_124);	}
						}}}
				else{$GUI->Message(IWARE_125);}	
			$GUI->Navigate("files.php?");								
			}

		/**
		 * Outputs the file management interface
		 *
		 * @access private
		 */
		function File_Manager ()
			{
			global $GUI;
			$GUI->OpenForm("fileForm","files.php?S=2","return ConfirmDeleteFile ()");
			$GUI->OpenWidget(IWARE_126);
			$GUI->OpenListBox ("filename",12);
			$this->File_ListBox ();
			$GUI->CloseListBox ();
			echo "<br /><br />\n".$GUI->Button(IWARE_127);
			$GUI->CloseWidget ();
			$GUI->CloseForm ();
			}

		/**
		 * Deletes a file from the files/ directory of the installation
		 *
		 * @param string $filename The filename of the file that is to be deleted from the files/ directory
		 * @access private
		 */
		function File_Delete ($filename)
			{
			global $GUI;
			if(@file_exists("../files/$filename"))
				{@unlink("../files/$filename");}
			$GUI->Navigate("files.php?");		
			}

		/**
		 * Outputs a number of HTML form option elements containg file names from the files/ directory of the installation
		 *
		 * @access private
		 */
		function File_ListBox ()
			{
			global $GUI;
			if ($handle = opendir('../files/')) 
				{
				while (false !== ($file = readdir($handle))){if ($file != "." && $file != ".."){$GUI->ListOption("$file","$file -- ".filesize("../files/$file")." bytes");}}
				closedir($handle); 
				}
			}

		/**
		 * Returns a total count of files available to the system located in the files/ directory of the installation
		 *
		 * @access private
		 */
		function File_GetCount ()
			{
			$count=0;
			if ($handle = opendir('../files/')) 
				{
				while (false !== ($file = readdir($handle))){if ($file != "." && $file != ".."){$count++;}}
				closedir($handle); 
				}
			else {$count="error";}
			return $count;
			}

		///////////////////////////////////////////////////////////////////////////////
		// Modules
		///////////////////////////////////////////////////////////////////////////////

		/**
		 * Outputs the initial modules management interface to the user, if no modules are present in the mods/ directory of the installation the interface will not be displayed
		 *
		 * @access private
		 */
		function Mod_Manager ()
			{
			global $GUI;
			if($this->Mod_GetCount ()<1)
				{
				$GUI->Message(IWARE_128);
				$GUI->Navigate("main.php?");
				}
			else
				{
				$GUI->OpenWidget(IWARE_129);
				if ($handle = opendir('../mods/')) 
					{
					echo "<table width=90% cellpadding=3 cellspacing=0>\n";
					$row=0;
					while (false !== ($file = readdir($handle)))
						{
						if ($file != "." && $file != "..")
							{
							if($row==0){$color="#ffffff";}
							else if($row==1){$color="#f5f5f5";}
							echo "<tr>\n";
							echo "<td bgcolor=$color>".$GUI->Label("$file")."</td>\n";
							if(!file_exists("../mods/$file/admin.php"))
								{
								echo "<td bgcolor=$color><i>".IWARE_130."</i></td>\n";							
								}
							else
								{
								$GUI->OpenForm("","../mods/$file/admin.php","");
								echo "<td bgcolor=$color>".$GUI->Button(IWARE_131)."</td>\n";
								$GUI->CloseForm ();
								}
							echo "</tr>\n";
							if($row==0){$row=1;}
							else if($row==1){$row=0;}					
							}
						}
					echo "</table>\n";	
					closedir($handle); 
					}
				$GUI->CloseWidget();
				}
			}

		/**
		 * Outputs a number of HTML form option elements containing names of modules avaialable to the system from the mods/ directory of the installation
		 *
		 * @access private
		 */
		function Mod_ListBox ()
			{
			global $GUI;
			if ($handle = opendir('../mods/')) 
				{
				while (false !== ($file = readdir($handle))){if ($file != "." && $file != ".."){$GUI->ListOption("$file","$file");}}
				closedir($handle); 
				}
			}

		/**
		 * Returns a total count of all modules avalable to the system from the mods/ directory of the installation
		 *
		 * @access private
		 */
		function Mod_GetCount ()
			{
			$count=0;
			if ($handle = opendir('../mods/')) 
				{
				while (false !== ($file = readdir($handle))){if ($file != "." && $file != ".."){$count++;}}
				closedir($handle); 
				}
			else {$count="error";}
			return $count;
			}

		/**
		 * This method is now deprecated
		 *
		 * @access private
		 */
		function Mod_MissingDB ()
			{
			echo "<html>\n<body>\n";
			echo IWARE_132;
			echo "</body>\n</html>\n";
			die("");
			}
		
		///////////////////////////////////////////////////////////////////////////////
		// Image Tools
		///////////////////////////////////////////////////////////////////////////////

		/**
		 * Uses GDLib supprt in PHP to resize an uploaded image to specified dimensions. Returns FALSE if the required GDLib support is not enabled for PHP, TRUE otherwise.
		 *
		 * @param string $image_path The source path of the original image
		 * @param string $thumb_path The output path of the new image
		 * @param string $image_name The filename of the uploaded image
		 * @param integer $thumb_width The new width dimension to resize to
		 * @param integer $thumb_height The new height dimension to resize to
		 * @param string $type String specifying which image MIME type to use either image/gif or image/pjpeg
		 * @return boolean
		 * @access private
		 */
		function resizeImage($image_path,$thumb_path,$image_name,$thumb_width,$thumb_height,$type) 
				{ 
				if(!function_exists('imagecreatefromjpeg')) {return false;}
				else
					{
					if($type=="image/gif"){$src_img = imagecreatefromgif("../files/".$image_name);}
					elseif($type=="image/pjpeg"||$type=="image/jpeg"){$src_img = imagecreatefromjpeg("../files/".$image_name); }
					else{return false;}
					$origw=imagesx($src_img); 
					$origh=imagesy($src_img); 
					$new_w = $thumb_width; 
					$diff=$origw/$new_w; 
					$new_h=$thumb_height;
					$dst_img = imagecreate($new_w,$new_h); 
					 imagecopyresized($dst_img,$src_img,0,0,0,0,$new_w,$new_h,imagesx($src_img),imagesy($src_img)); 
					if($type=="image/gif"){imagegif($dst_img, "../files/".$image_name.""); }
					elseif($type=="image/pjpeg"||$type=="image/jpeg"){imagejpeg($dst_img, "../files/".$image_name.""); }
					return true; 
					}
				} 
		
		///////////////////////////////////////////////////////////////////////////////
		// Client Side Data
		///////////////////////////////////////////////////////////////////////////////

		/**
		 * Returns the META title for a given document by its record ID
		 *
		 * @param string $id The record ID of the document
		 * @return string
		 * @access private
		 */
		function Client_GetMETATitle ($id)
			{
			$result=$this->query("select * from ".IWARE_DOCS." where id='$id' ");
			$data=$this->result($result,0,"meta_title");
			$this->freeResult($result);
			return $data;
			}

		/**
		 * Returns the META keywords for a given document by its record ID
		 *
		 * @param string $id The record ID of the document
		 * @return string
		 * @access private
		 */
		function Client_GetMETAKeywords ($id)
			{
			$result=$this->query("select * from ".IWARE_DOCS." where id='$id' ");
			$data=$this->result($result,0,"meta_keywords");
			$this->freeResult($result);
			return $data;
			}

		/**
		 * Returns the META description for a given document by its record ID
		 *
		 * @param string $id The record ID of the document
		 * @return string
		 * @access private
		 */
		function Client_GetMETADescription ($id)
			{
			$result=$this->query("select * from ".IWARE_DOCS." where id='$id' ");
			$data=$this->result($result,0,"meta_description");
			$this->freeResult($result);
			return $data;
			}

		/**
		 * Returns the current active skin name for the site
		 *
		 * @return string
		 * @access private
		 */
		function Client_GetSkin ()
			{
			$result=$this->query("select * from ".IWARE_CONFIG);
			$data=$this->result($result,0,"active_skin");
			$this->freeResult($result);
			return $data;
			}

		/**
		 * Returns the current active navigation style for the site
		 *
		 * @return string
		 * @access private
		 */
		function Client_GetNav ()
			{
			$result=$this->query("select * from ".IWARE_CONFIG);
			$data=$this->result($result,0,"navbar_style");
			$this->freeResult($result);
			return $data;
			}

		/**
		 * Outputs the content for a requested document, and also optionally processes variables posted for use with any embedded modules in a given doument
		 *
		 * @param string $D The record ID of the requested document content to be displayed
		 * @param string $modVars The rA pipe (|) delimited string of variable names posted by a document for use with a given module. If not supplied no module variables will be processed and a default empty string will be used.
		 * @access private
		 */
		function Client_GetContent ($D,$modVars="")
			{
			global $IW;
			$result=$this->query("select * from ".IWARE_DOCS." where id='$D' ");
			$data=$this->result($result,0,"doc_content");
			$mod=$this->result($result,0,"module");
			$this->freeResult($result);
			echo $data;
			if(!empty($modVars))
				{
				$vars=explode("|",$modVars);
				for($i=0;$i<count($vars);$i++)
					{global ${$vars[$i]};}
				}
			if(!empty($mod)){include "mods/".$mod."/module.php";}
			}

		/**
		 * Ouputs the site header as HTML / text or as an image dependingon the configured header display mode
		 *
		 * @access private
		 */
		function Client_GetHeader ()
			{
			$result=$this->query("select * from ".IWARE_HEADER);
			$enabled=$this->result($result,0,"is_enabled");
			if($enabled==1)
				{
				$mode=$this->result($result,0,"display_mode");
				if($mode==0)
					{
					echo $this->result($result,0,"title_text")."\n";  
					}
				elseif($mode==1)
					{
					echo "<img src=\"files/".$this->result($result,0,"image_name")."\" alt=\"".$this->result($result,0,"image_alt")."\" border=\"".$this->result($result,0,"image_border")."\">\n";
					}
				}
			$this->freeResult($result);			
			}

		/**
		 * Outputs the site footer as HTML / text
		 *
		 * @access private
		 */
		function Client_GetFooter ()
			{
			$result=$this->query("select * from ".IWARE_FOOTER);
			$enabled=$this->result($result,0,"is_enabled");
			if($enabled==1){echo $this->result($result,0,"footer_text");}
			$this->freeResult($result);	
			}

	// End Class
	}
?>