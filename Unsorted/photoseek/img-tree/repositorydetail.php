<?php
// file: repositorydetail.php
// desc: repository maintenance for PhotoSeek
// code: Todd Kirby (kirbyt@yahoo.com)
// lic : GPL, v2

include "config.inc";

openDatabase();
photoseek_authenticate_admin ();

include "class.repository_data_object.inc";
$repository_table = new repository_data_object();

switch ($action)
{
	case "Save" :
		if ($id) // it's an update
		{
			$repository_table->update($rname, $rdesc, $rpath, $rlevel, $id);
		}
		else // it's a new user
		{
			$repository_table->add($rname, $rdesc, $rpath, $rlevel);
		}
		header("Location: repositorylist.php"); // bail back to repositorylist
		break;
	case "Delete" :
		$repository_table->delete($id);
	case "Cancel" :
		header("Location: repositorylist.php"); // bail back to repositorylist
		break;
	default :
		if($id)	
		{
			$result = $repository_table->load($id);
			$r = $repository_table->db->fetch_array ($result);
			$page_name="Edit Repository";
		}
		else
		{
			$page_name="New Repository";
		}
}

include "header.php";
?>
  
 <form method="post" action="<?php echo $PHP_SELF ?>">
 <INPUT TYPE=HIDDEN NAME="id" VALUE="<?php echo $id ?>">
<table align="center" valign="middle" border="0" cellpadding="1" cellspacing="2">
	<th colspan="2">
		<?php echo $page_name ?>
	</th>
	<tr bgcolor="#cccccc">
		<td>Repository Name</td>
		<td><input type="text" name="rname" value="<?php echo stripslashes($r[rname]) ?>" style="width:300px;" maxlength="100" size="50"></td>
	</tr>
	<tr bgcolor="#dddddd">
		<td>Description</td>
		<td><input type="text" name="rdesc" value="<?php echo stripslashes($r[rdesc]) ?>" style="width:300px;" maxlength="100" size="50"></td>
	</tr>
	<tr bgcolor="#cccccc">
		<td>Path</td>
		<td><input type="text" name="rpath" value="<?php echo stripslashes($r[rpath]) ?>" style="width:300px;" maxlength="100" size="50"></td>
	</tr>
	<tr bgcolor="#dddddd">
		<td>Level</td>
		<td> 
		 <SELECT NAME="rlevel">
	       <OPTION VALUE="0" <?php echo $r[rlevel] == "0" ? "SELECTED" : "" ?>>Public
	       <OPTION VALUE="1" <?php echo $r[rlevel] == "1" ? "SELECTED" : "" ?>>Level 1
	       <OPTION VALUE="2" <?php echo $r[rlevel] == "2" ? "SELECTED" : "" ?>>Level 2
	       <OPTION VALUE="3" <?php echo $r[rlevel] == "3" ? "SELECTED" : "" ?>>Level 3
     	</SELECT>
     </td>
	</tr>
	<th colspan="2">
		<input type="submit" name="action" value="Save">
		<input type="submit" name="action" value="Cancel">
		<?php
		if($page_name == "Edit Repository")
		{
		?>
			<input type="submit" name="action" value="Delete">
		<?php 
		}
		?>
	</th>
</table>
</form>

<?php
include "footer.php";
?>
