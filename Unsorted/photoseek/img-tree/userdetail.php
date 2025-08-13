<?php
// file: userdetail.php
// desc: rusers maintenance for PhotoSeek
// code: Todd Kirby (kirbyt@yahoo.com)
// lic : GPL, v2

include "config.inc";

openDatabase ();
photoseek_authenticate_admin ();

include "class.user_data_object.inc";

$user_table = new user_data_object ();

switch ($action)
{
	case "Save" :
		if ($id) // it's an update
		{
			$user_table->update($username, $userdesc, $userpass, $userlevel, $id);
		}
		else // it's a new user
		{
			$user_table->add($username, $userdesc, $userpass, $userlevel);
		}
		header("Location: userlist.php"); // bail back to userlist
		break;
	case "Delete" :
		$user_table->delete($id);
	case "Cancel" :
		header("Location: userlist.php"); // bail back to userlist
		break;
	default :
		if($id)	
		{
			$result = $user_table->load($id);
			$r = $user_table->db->fetch_array ($result);
			$page_name="Edit User";
		}
		else
		{
			$page_name="New User";
		}
}

include "header.php";
?>
  
 <FORM METHOD=POST ACTION="<?php echo $PHP_SELF ?>">
 <INPUT TYPE=HIDDEN NAME="id" VALUE="<?php echo $id ?>">
 <TABLE ALIGN=CENTER VALIGN=MIDDLE BORDER=0 CELLPADDING=1 CELLSPACING=2>
	<TH COLSPAN="2">
		<?php echo $page_name ?>
	</TH>
	<TR BGCOLOR="#cccccc">
		<TD>User Name</TD>
		<TD><input TYPE=TEXT NAME="username"
		 VALUE="<?php echo stripslashes($r[username]) ?>"
		 STYLE="width:300px;" MAXLENGTH=30 SIZE=24></TD>
	</TR>
	<TR BGCOLOR="#dddddd">
		<TD>Description</TD>
		<TD><INPUT TYPE=TEXT NAME="userdesc"
		 VALUE="<?php echo stripslashes($r[userdesc]) ?>"
		 STYLE="width:300px;" MAXLENGTH=30 SIZE=24></TD>
	</TR>
	<TR BGCOLOR="#cccccc">
		<TD>Password</TD>
		<TD><INPUT TYPE=PASSWORD NAME="userpass"
		 VALUE="<?php echo stripslashes($r[userpass]) ?>"
		 STYLE="width:300px;" MAXLENGTH=30 SIZE=24></TD>
	</TR>
	<TR BGCOLOR="#dddddd">
		<TD>Level</TD>
		<TD> 
		 <SELECT NAME="userlevel">
	       <OPTION VALUE="0" <?php echo $r[userlevel] == 0 ? "SELECTED" : "" ?>>Public
	       <OPTION VALUE="1" <?php echo $r[userlevel] == 1 ? "SELECTED" : "" ?>>Level 1
	       <OPTION VALUE="2" <?php echo $r[userlevel] == 2 ? "SELECTED" : "" ?>>Level 2
	       <OPTION VALUE="3" <?php echo $r[userlevel] == 3 ? "SELECTED" : "" ?>>Level 3
     	</SELECT>
     </TD>
	</TR>
	<TH COLSPAN=2>
		<INPUT TYPE=SUBMIT NAME="action" VALUE="Save">
		<INPUT TYPE=SUBMIT NAME="action" VALUE="Cancel">
		<?php
		if($page_name == "Edit User")
		{
		?>
			<INPUT TYPE=SUBMIT NAME="action" VALUE="Delete">
		<?php 
		}
		?>
	</TH>
</TABLE>
</FORM>

<?php
include "footer.php";
?>
