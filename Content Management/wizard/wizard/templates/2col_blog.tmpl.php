<?php
//load all the configuration and database settings
include_once '../page_header.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="Content-Language" content="en-us" />
<meta name="robots" content="<?php echo $config['robots']; ?>" />
<meta name="author" content="<?php echo $config['siteAdmin']; ?>" />
<meta name="description" content="<?php echo $config['description']; ?>" />
<meta name="keywords" content="<?php echo $config['keywords']; ?>" />
<link href="../templates/css/basestyles.css" rel="stylesheet" type="text/css">
<?php
if ($config[topmenu] >= 1)
	{ echo "<link href=\"../templates/css/topmenu.css\" rel=\"stylesheet\" type=\"text/css\">"; }
else { echo "<link href=\"../templates/css/leftmenu.css\" rel=\"stylesheet\" type=\"text/css\">"; }
?>
<link rel="shortcut icon" type="image/ico" href="favicon.ico" />
<title><?php echo $config['name']; ?></title>

<!-- javascript for DHTML (Suckerfish) menu -->
<?php include("../inc/js/suckerfish.js"); ?>
</head>
<body id="bd">

<!-- Register, Login etc -->
<?php include("../searchandlogin.php"); ?>


<!-- Main Layout Table -->
<table align="center" cellpadding="20" cellspacing="0" id="mainTable">

	
<!-- Header -->
	<tr>
		<td colspan="2" id="header">&nbsp;</td>
	</tr>

<!-- Menu Bar -->
<?php
	if ($config[topmenu] >= 1)
	{
	echo "<tr>";
		echo "<td  id=\"topMenu\" colspan=\"2\">";
		 	include("../inc/functions/topMenu.php"); 
		echo "</td>";
	echo "</tr>";
	}
?>


<!-- end of Menubar -->

<!-- Sidebar -->
<tr>
	<td valign="top" >
		<table id="sidebar">
			<!-- sidebar vertical menu -->
    				<?php
						if (!$config[topmenu] >= 1)
						{	echo "<tr><td><table id=\"sidebarmenu\"><tr><td valign=\"top\">";
 							include("../inc/functions/leftMenu.php"); 
							echo "</td></tr></table></td></tr>";
     					}
					?>
			<tr>
				<td valign="top" id="sidebarbottom" >
			Sidebar Bottom
				</td>
			</tr>
		</table>
  	</td>

<!-- Main Content -->
<td valign="top" id="maincontent">
<table width="100%"><tr><td>
<p><strong>2 Column Blog Template</strong></p>
<ul>
  <li> Same as the 2 Column Basic Template, with the addtion of a comments feature at the bottom of this column. <br />
    &nbsp;</li>
</ul></td>
</tr></table>

<!-- Blog Begins -->
<?php
echo "<table width=\"100%\" ><tr><td class=\"colorNormalText\"><hr width=\"100%\"  color=\"#666666\" >Add your comment:</td></tr>";
//if the visitor just submitted a comment
$comment = $_GET['comment'];
$username = $_GET['username'];
$subject = $_GET['subject'];
$contact = $_GET['contact'];
$page = $_GET['page'];


if (!isset($page))
   {$page = stripslashes($_SERVER['REQUEST_URI']);}

$page = explode("?", $page); 
$page = $page[0]; //strips the url encoding from the url path

//search for comments on this page
	$db = new DB();
	$db->query("SELECT * FROM ". DB_PREPEND . "comments WHERE page='$page' ORDER BY time ASC");
	
    $previous = $db->num_rows();

	if($previous > 0) {
 
   	echo "<tr><td><table width=\"100%\" class=\"smallText\">";
    $num = 1;
 	while($info2 = $db->next_record()) {	
		echo '<tr>';   
			echo '<td><span class=colorNormalText>' . $num++ . '  </span>&nbsp;&nbsp;' .' "'.htmlspecialchars(stripslashes($info2['subject'])).'"&nbsp;&nbsp; by: <a href="'.$info2['contact'].'">'.htmlspecialchars(stripslashes(	$info2['username'])).'</a></td> <td><div align="right"> '.date('h:i:s a', $info2['time']).' on '.$info2['date'].'</div></td>';
		
		echo '</tr><tr>';
			echo '<td colspan="2"> '.htmlspecialchars(stripslashes(nl2br($info2['comment']))).' ';
			echo '<hr width="100%" noshade></td>';
		echo '</tr>';
	}//end while
		echo '</table></td></tr>';
		
	} else { echo ""; }

$form_processor = CMS_WWW . "/inc/functions/blog_page_pro.php";

echo "<form name=\"comments\" action=\"".$form_processor."\" method=\"post\">";
echo "<input type=\"hidden\" name=\"page\" value=\"".$page."\">";
echo "<input type=\"hidden\" name=\"date\" value=\"".(date('F j, Y.'))."\">";
echo "<input type=\"hidden\" name=\"time\" value=\"".(time())."\">";

echo "<tr><td><table width=\"90%\" class=\"smallText\">";
if ($message) { echo "<tr><td class=\"message\" colspan=\"2\">&nbsp;&nbsp;" . $message . "<br />&nbsp;</td></tr>"; }
echo "   <tr>"; 
echo "      <td><div align=\"right\">Username:   </div></td>"; 
echo "       <td><input name=\"username\" type=\"text\" size=\"30\" value=\"".$username."\"></td>";
echo "   </tr>";
echo "    <tr>"; 
echo "      <td><div align=\"right\">Contact:   </div></td>";
echo "      <td><input type=\"text\" name=\"contact\" size=\"30\" value=\"".$contact."\"> (Will not be displayed.)</td>";
echo "    </tr>";
echo "    <td><div align=\"right\">Subject:   </div></td>";
echo "    <td><input type=\"text\" name=\"subject\" size=\"30\" value=\"".$subject."\"></td>";
echo "    </tr>";
echo "    <tr>";
echo "      <td><div align=\"right\">Comment:   </div></td>";
echo "      <td><textarea name=\"comment\" cols=\"80\" rows=\"5\" wrap=\"VIRTUAL\">".$comment."</textarea></td>";
echo "    </tr>";
echo "    <tr> ";
echo "      <td></td>";
echo "      <td colspan=\"2\"><input type=\"reset\" value=\"Reset\">";    
echo "        <input type=\"submit\" name=\"submit\" value=\"Submit\"></td>";
 echo "   </tr>";
 echo " </table></td></tr>";
echo "</form>";

echo "</table>";

?> 

<!-- Blog Ends -->
</td>
</tr>
<tr>

<!-- Footer -->
<td id="footer" colspan="2">
<?php include("../footer.php"); ?>
</td>
</tr>
</table>
<!-- end Main Layout Table -->	
</body>
</html>