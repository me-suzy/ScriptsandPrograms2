<?php
include ("atho.inc.php");
include ("config.inc.php");

if (!isset($PHP_AUTH_USER) && !isset($PHP_AUTH_PW))
     {
     header("WWW-Authenticate: Basic realm=\"Shockwave Password Protected Directory\"");
     Header("HTTP/1.0 401 Unauthorized");
    echo "<HTML><BODY BGCOLOR=000066 LINK=ffcc00 VLINK=ffcc00 ALINK=ffcc00>
    <DIV ALIGN=center>
    <FONT FACE=arial,verdana SIZE=3 COLOR=ffffff>
    <B>You must have a username and password to enter this page in
    <BR><BR><font size=6><A HREF=\"http://$SERVER_NAME\">$SERVER_NAME</a><B></FONT>
    <BR><BR>Your ip was logged as 
    <BR><FONT COLOR=ffcc00>$REMOTE_ADDR</FONT>
    <BR>Date of attempted entry 
    <BR><FONT COLOR=ffcc00>".$strDate."</FONT>
    <BR>If you feel this is an error please contact 
    <BR><A HREF=\"mailto:$SERVER_ADMIN\">$SERVER_ADMIN</A>
    <BR><BR>Back to <A HREF=\"$HTTP_REFERER\">$HTTP_REFERER</A>";
     exit;
     }    
  else if(($PHP_AUTH_USER=="$username") && ($PHP_AUTH_PW=="$password"))

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
    <td width="50?"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Edit Products</b></font></td>
    <td>
      <div align="right"><font size="1" face="Verdana, Arial, Helvetica, sans-serif" color="white">Click
        on an item for Details</font></div>
    </td>
  </tr>
  <tr>
    <td colspan=3 align=center>
        <a href="<? echo "detail.php?action=3"?>"><font size=1 face="Verdana, Arial, Helvetica, sans-serif" color='white'>[ Add Items ]</a></td>
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
	    
		$query = "UPDATE products ";
		$query .= "SET ";
	$query.="id=\"$id\",item_name=\"$item_name\",item_desc=\"$item_desc\",item_descde=\"$item_descde\",item_price=\"$item_price\",item_category=\"$item_category\",item_ship=\"$item_ship\",status=\"$status\"";
	$query .= " WHERE id = \"$id\"";
	if (mysql_query ($query) && mysql_affected_rows () > 0)
		print ("Entry $id updated successfully.\n");
	else
		print ("Entry not updated.\n");
	}// else {
	//	add_new($id,$item_name,$item_category,$item_desc,$item_price,$logo_location,$image_small,$image_large,$page_success,$page_cancel,$status) ;
//	}
	break;
case ADD_ENTRY;
	if ($item_name) {
		$id = add_new($id,$item_name,$item_desc,$item_descde,$item_price,$item_category,$item_ship,$status) ;
	}
        break;
default:
        break;
}


$dbResult = mysql_query("select * from products where id='$id'");
$row=mysql_fetch_object($dbResult);
?>
<div> 
    <form name="edit" METHOD=POST action="<? echo "$PHP_SELF"?>">
    <table width="500" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td> 
          <div> 
<? if ($row->image_large) { ?>
            <div align="center"><img src="<? echo "$url" ?><?echo "$row->image_large"?>"> 
            </div>
<?}?>
          </div>
          <br>
          <table border=0>
          <tr valign=top>
                <td nowrap>
                        <font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Item ID: </b></font>
                </td>
                <td>
                        <font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b><? echo "$row->id" ?></b></font>
                </td>
          </tr>

	  <tr valign=top>
		<td nowrap>
			<font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Item Name: </b></font>
		</td>
		<td>
			<input name=item_name type=text size=35 value="<?echo "$row->item_name"?>">
		</td>
	  </tr>
          <TR valign=top>
		<td nowrap>
			<font face="Verdana, Arial, Helvetica, sans-serif" size="2">
			<b>Item Small Description:</b></font>
		</td>
		<td>
			<textarea name=item_desc cols=55 rows=20><?echo "$row->item_desc"?></textarea>
		</td>
	  </tr>
	            <TR valign=top>
		<td nowrap>
			<font face="Verdana, Arial, Helvetica, sans-serif" size="2">
			<b>Item Detail Description:</b></font>
		</td>
		<td>
			<textarea name=item_descde cols=55 rows=20><?echo "$row->item_descde"?></textarea>
		</td>
	  </tr>
	  <tr valign=top>
		<td nowrap>
			<font face="Verdana, Arial, Helvetica, sans-serif" size="2">
			<b>Item Price:</b>
			</font>
		</td>
		<td>
			<input name=item_price value="<? echo "$row->item_price" ?>" type=text size=10>
		</td>
	  </tr>
	  	  <tr valign=top>
		<td nowrap>
			<font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Category:</b></font>
		</td>
		<td>
			<input name="item_category" value="<?echo "$row->item_category"?>" size=10>
		</td>
   	  </tr>
	  	  	  <tr valign=top>
		<td nowrap>
			<font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Shipping:</b></font>
		</td>
		<td>
			<input name="item_ship" value="<?echo "$row->item_ship"?>" size=10>
		</td>
   	  </tr>
	  <tr valign=top>
		<td nowrap>
			<font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Status:</b></font>
		</td>
		<td>
			<input name="status" value="<?echo "$row->status"?>" size=10>
		</td>
   	  </tr>
	  <tr valign=top>
		<td colspan=2>
			<font face="Verdana, Arial, Helvetica, sans-serif" size="1">
			<input type=hidden name=id value="<?echo "$row->id"?>">
			<? if ($row->id) { 
				$value=Update ;
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

function showcursettings()
{
}

function check_image($imfile)
{
 if(!file_exists($imfile)) return "nopicture.gif";
 $image=getimagesize($imfile);
 $im_width=$image[0];
 $im_height=$image[1];
 switch($image[2])
    {
    case 1:
        $im_type = ".gif";
        break;
    case 2:
        $im_type = ".jpg";
        break;
    case 3:
        $im_type = ".png";
        break;
    default:
        $im_type="not picture";
        break;
     }
 return $im_type;
}

function process_file($file_body,$file_name,$path)
{
  global $error;
  if(!file_exists($path))
    {
    }
  $im_type=check_image($file_body);
  if($im_type=="not picture")    {  $error="Wrong file type"; return 0; }
  if(!file_exists($path . $file_name))
        $ffilename=$file_name;
  else
    {
        $n="0";
        $pos = strrpos($file_name, ".");
        $tfilename = substr($file_name, 0, $pos);

        do
          {
            $ffilename= $tfilename."_".$n.$im_type;
            $n++;

          } while (file_exists($path . $ffilename));
    }

 if(!copy($file_body,$path . $ffilename))
   { $error="Error saving file $ffilename"; return 0; }
  else print "File uploaded....";
 return $ffilename;

}
function add_new($id,$item_name,$item_desc,$item_descde,$item_price,$item_category,$item_ship,$status) {
$q="INSERT INTO products (id,item_name,item_desc,item_descde,item_price,item_category,item_ship,status) VALUES (\"$id\",\"$item_name\",\"$item_desc\",\"$item_descde\",\"$item_price\",\"$item_category\",item_ship=\"$item_ship\",\"$status\") ";
if(!mysql_query($q))
        die("Could not add Item");
return mysql_insert_id();

}

function delete_entry($id,$confirmed)
{
if ($confirmed == "yes") {
        $q = "DELETE FROM products where id=\"$id\"";
	if (!mysql_query($q)) {
		die("Cound not delete id $id\n");
	} else {
		print "$id Deleted from Products Table.";
		?>
		              <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><a href="index.php"><font color="#336699">Back
			                  to products</font></a></font></div>
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
	              <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><a href="index.php"><font color="#336699">Back
		                  to products</font></a></font></div>
	<?
 	exit;
}
}
?>
          <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><a href="index.php"><font color="#336699">Back 
            to products</font></a></font></div>
	
<? include ("footer.php");?>
		
<?
exit;
?>


