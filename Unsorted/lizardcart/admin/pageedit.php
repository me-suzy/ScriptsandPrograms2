<?php
include ("atho.inc.php");
include ("config.inc.php");


define (INITIAL_PAGE,0);
define (UPDATE_ENTRY,1);
define (DELETE_ENTRY,2);
define (ADD_ENTRY,3);

if (empty ($action))
        $action = INITIAL_PAGE;

$title="Lizard Cart Product Administration";
?>

<? include ("header.php");?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#3366CC">
  <tr bgcolor=ffffff>
    <td colspan=2></td></tr>
    <td width="50?">
        <font face="Verdana, Arial, Helvetica, sans-serif" size="2">
  <tr>
    <td width="50?"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Edit Pages</b></font></td>
    <td>
      <div align="right"><font size="1" face="Verdana, Arial, Helvetica, sans-serif" color="white">Click
        on an item for Details</font></div>
    </td>
  </tr>
  <tr>
    <td colspan=3 align=center>
        <a href="<? echo "pageedit.php?action=3"?>"><font size=1 face="Verdana, Arial, Helvetica, sans-serif" color='#336699'>[ Add A Page ]</a></td>
  </tr>
</table>
<?
switch($action)
{
case DELETE_ENTRY:
        delete_entry($id,$confirmed);
        break;
case UPDATE_ENTRY:
	if ($id) {
		$query = "UPDATE pages ";
		$query .= "SET ";
	$query.="id=\"$id\",page_title=\"$page_title\",page_content=\"$page_content\"";
	$query .= " WHERE id = \"$id\"";
	if (mysql_query ($query) && mysql_affected_rows () > 0)
		print ("Entry $id updated successfully.\n");
	else
		print ("Entry not updated.\n");
	}
	break;
case ADD_ENTRY;
	if ($page_title) {
		$id = add_new($id,$page_title,$page_content) ;
	}
        break;
default:
        break;
}

$dbResult = mysql_query("select * from pages where id='$id'");
$row=mysql_fetch_object($dbResult);

?>
<div> 
    <form name="edit" METHOD=POST action="<? echo "$PHP_SELF"?>">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td> 

          <table border=0>
          <tr valign=top>
                <td nowrap>
                        <font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Page ID: </b></font>
                </td>
                <td>
                        <font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b><? echo "$row->id" ?></b></font>
                </td>
          </tr>

	  <tr valign=top>
		<td nowrap>
			<font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Page Title: </b></font>
		</td>
		<td>
			<input name=page_title type=text size=35 value="<?echo "$row->page_title"?>">
		</td>
	  </tr>
          <TR valign=top>
		<td nowrap>
			<font face="Verdana, Arial, Helvetica, sans-serif" size="2">
			<b>Page Content:</b></font>
		</td>
		<td>
			<textarea name=page_content cols=55 rows=20 wrap="virtual"><? echo "$row->page_content"?></textarea>
		</td>
	  </tr>
	  <tr valign=top>
		<td colspan=2>
			<font face="Verdana, Arial, Helvetica, sans-serif" size="1">
			<input type=hidden name=id value="<?echo "$row->id"?>">
			<? if ($row->id) { 
				$value="Update" ;
				print "<input type=hidden name=action value=1>";
			} else {
				$value="Add";
				print "<input type=hidden name=action value=3>";
			}?>
			<input type=submit value="<?echo "$value"?>">
			<a href="<? echo "$PHP_SELF?action=2&id=$row->id"?>">Delete</a>
			</font>
		</td>
	  </tr>
	  </table>

        </td>
      </tr>
    </table>
    </form>
    <br>
  </div>
<?


function add_new($id,$page_title,$page_content) {
$q="INSERT INTO pages (id,page_title,page_content) VALUES (\"$id\",\"$page_title\",\"$page_content\") ";
if(!mysql_query($q))
        die("Could not add Page");
return mysql_insert_id();
echo mysql_errno() . ": " . mysql_error(). "\n";


}

function delete_entry($id,$confirmed)
{
if ($confirmed == "yes") {
        $q = "DELETE FROM pages where id=\"$id\"";
	if (!mysql_query($q)) {
		die("Cound not delete id $id\n");
	} else {
		print "$id Deleted from Products Table.";
		?>
		              <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><a href="pagelist.php"><font color="#336699">Back
			                  to pages</font></a></font></div>
		<?
		exit;
	}
} else if ($confirmed == "no") {
    //Do nothing
}else{
        print "<TABLE ALIGN=CENTER><TR><TD>\n";
	print "<form action=\"$PHP_SELF\">";
	print "<input type=hidden name=\"id\" value=$id>";
	print "<input type=hidden name=action value=2>";
		   
	print "<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\">";
        print "Are you sure you want to delete id $id?<br>\n";
                print "<TABLE><TR><TD>YES</TD><TD>NO</TD></TR>\n";
        print "<TR><TD><input type=radio name=confirmed value=yes></TD>\n";
        print "<TD><input type=radio name=confirmed value=no><input type=hidden name=DELETE value=1></TD></TR>";
        print "<TR><TD><input type=submit value=CONFIRM></td></tr></TABLE>\n";
        print "</TD></TR>\n";
	?>
	              <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><a href="pagelist.php"><font color="#336699">Back
		                  to pages</font></a></font></div>
	<?
 	exit;
}
}
?>
          <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><a href="pagelist.php"><font color="#336699">Back 
            to pages</font></a></font></div>
	
<? include ("footer.php");?>
		
<?
exit;
?>


