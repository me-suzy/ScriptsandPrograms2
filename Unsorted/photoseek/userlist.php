<?php
 // file: userlist.php
 // desc: rusers list for PhotoSeek
 // code: Todd Kirby (kirbyt@yahoo.com)
 // lic : GPL, v2

include "config.inc";

openDatabase ();

 photoseek_authenticate_admin ();
 
 if (!defined(USER_DATA_OBJECT))	include "class.user_data_object.inc";

 $user_table = new user_data_object();

 $page_name = "User List";
 
 include "header.php";
 
?>
   <table valign="top" border="1" bgcolor="white" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td valign="top">
			<table align="center" border="0" width="100%" height="100%" cellpadding="2" cellspacing="2" bgcolor="white">

<?php
	$result = $user_table->find();

   if ($user_table->db->num_rows($result))
   {
?>
	<TR>
     <th width="25%" bgcolor="#d3d3d3" nowrap><B>User Name</B></Th>
     <th width="60%" bgcolor="#d3d3d3" nowrap><B>Description</B></th>
     <th width="1%" bgcolor="#d3d3d3" nowrap><B>Level</B></th>
    </TR>
<?php
   
    while ($r = $user_table->db->fetch_array ($result))
    {
?>
      <TR>
      	<TD bgcolor="#f5f5f5">
      	<table cellpadding="0" cellspacing="0" border="0">
       	<tr>
       	<TD>
       		<a href="userdetail.php?action=edit&id=<?php echo $r[id] ?>"><img src="user.gif" border="0"></a>
       	</TD>
       	<TD>
      		&nbsp;<a href="userdetail.php?action=edit&id=<?php echo $r[id] ?>"><font color="darkblue"><?php echo stripslashes($r[username]) ?></font></a>
       	</TD>
       	</tr>
       	</table>
       	</TD>
       	 <TD bgcolor="#f5f5f5">&nbsp;<?php echo stripslashes($r[userdesc]) ?></TD>
       <TD bgcolor="#f5f5f5" ALIGN="CENTER">&nbsp;<?php echo stripslashes($r[userlevel]) ?></TD>
      </TR>
<?php
    } // end while
  } // end if
?>  
  </TD>
      </TR>
      </table>
        </TD>
      </TR>
      <TR>
      	<TD>
      <table align="center" border="0" width="100%" cellpadding="3" cellspacing="0" bgcolor="white">
		<tr align=right>
		<td bgcolor="#d3d3d3" nowrap>
				<A HREF="userdetail.php">Add User</A>
				&#149;
				<A HREF="admin.php">Back to Admin Menu</A>
		</td>
	</tr>
	</table>		
      </TD>
     </TR>
    </table>
<?php
 include "footer.php";
?>
