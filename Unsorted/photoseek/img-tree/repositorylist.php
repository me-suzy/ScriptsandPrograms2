<?php
 // file: repositorylist.php
 // desc: repository list for PhotoSeek
 // code: Todd Kirby (kirbyt@yahoo.com)
 // lic : GPL, v2

include "config.inc";

openDatabase ();

 photoseek_authenticate_admin ();
 
 if (!defined(REPOSITORY_DATA_OBJECT))	include "class.repository_data_object.inc";

 $repository_table = new repository_data_object();

 $page_name = "Repositories";
 
 include "header.php";
 
?>
   <table valign="top" border="1" bgcolor="white" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td valign="top">
			<table align="center" border="0" width="100%" height="100%" cellpadding="2" cellspacing="2" bgcolor="white">

<?php
	$result = $repository_table->find();

   if ($repository_table->db->num_rows($result))
   {
?>
	<TR>
     <th width="25%" bgcolor="#d3d3d3" nowrap><B>Repository Name</B></Th>
     <th width="30%" bgcolor="#d3d3d3" nowrap><B>Description</B></th>
     <th width="30%" bgcolor="#d3d3d3" nowrap><B>Path</B></th>
     <th width="1%" bgcolor="#d3d3d3" nowrap><B>Level</B></th>
    </TR>
<?php
   
    while ($r = $repository_table->db->fetch_array ($result))
    {
?>
      <TR>
      	<TD bgcolor="#f5f5f5">
      	<table cellpadding="0" cellspacing="0" border="0">
       	<tr>
       	<TD>
       		<a href="repositorydetail.php?action=edit&id=<?php echo $r[id] ?>"><img src="repository.gif" border="0"></a>
       	</TD>
       	<TD>
      		&nbsp;<a href="repositorydetail.php?action=edit&id=<?php echo $r[id] ?>"><font color="darkblue"><?php echo stripslashes($r[rname]) ?></font></a>
       	</TD>
       	</tr>
       	</table>
       	</TD>
       	 <TD bgcolor="#f5f5f5">&nbsp;<?php echo stripslashes($r[rdesc]) ?></TD>
       	 <TD bgcolor="#f5f5f5">&nbsp;<?php echo stripslashes($r[rpath]) ?></TD>
       <TD bgcolor="#f5f5f5" ALIGN="CENTER">
       		<?php echo $r[rlevel] == 0 ? "<i>Public</i>" : stripslashes($r[rlevel]) ?>
       	</TD>
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
				<A HREF="repositorydetail.php">Add Repository</A>
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
