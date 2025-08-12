<?php
/*


	Copyright (C) 2004-2005 Alex B

	E-Mail: dirmass@devplant.com
	URL: http://www.devplant.com
	
    This file is part of SayOp.

    SayOp is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2.1 of the License, or
    (at your option) any later version.

    SayOp is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with SayOp; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA


*/
session_start();
include("inc/auth.php");
include("inc/redir.php");
if($user == $_SESSION["username"] && $pass == $_SESSION["password"]) {
    echo "
<!DOCTYPE html
PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN'
'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html>
   <head>
      <title>SayOp - New Article</title>
      <link rel='stylesheet' type='text/css' href='style_1.css' />
      <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
   </head>
<body class='admin'>
<h2 class='index' align='left'>SayOp Admin Control Panel</h2>
<div class='we' align='left'>Welcome " . $_SESSION['username'] .  "<br /></div>
";

include("com/db.php");
$sql = mysql_query("SELECT * FROM ".$so_prefix."_obj") or die("Sql error:" . mysql_error());
$rsql = mysql_query("SELECT * FROM ".$so_prefix."_main") or die("Sql error:" . mysql_error());
$nrows = mysql_num_rows($sql);
$nr = mysql_num_rows($rsql);
$nodef;
$nocase1 = "There are <b>$nrows</b> objects";

$nocase2 = "There is <b>$nrows</b> object";
$comdef;
$comcase1 = "comments";
$comcase2 = "comment";
if($nrows=='1') { 
$nodef=$nocase2; 
} else { $nodef=$nocase1; 
}
if($nr=='1') { 
$comdef=$comcase2; 
} else { $comdef=$comcase1; 
}
echo "
<span style='float: left; background: #FFFFFF; margin-left: 30px;' align='left'>
$nodef and <b>$nr</b> posted $comdef in the database.</span>
<div style='float: right; margin-right: 50px; background: #CCFF99;'><a href='http://www.devplant.com/sayop' target='_blank'>SayOp 1.3</a> &copy 2004-2005 <a href='http://www.devplant.com' target='_blank'>Alex B</a> - Licensed under the <a href='http://www.gnu.org/copyleft/gpl.html'>GPL</a></div><br />
<br /><br />
<div align='left' class='obj_name'>
<div class='oselect'>Comments active in:</div><br /><br />
<p>
";


include("com/db.php");
$sql = mysql_query("SELECT * FROM ".$so_prefix."_obj") or die("Sql error:" . mysql_error());
$nrows = mysql_num_rows($sql);




if($nrows=='0') {
echo "No objects found";
}
else {
while ($irow = mysql_fetch_row($sql)) {
$countrows = mysql_query("SELECT * FROM ".$so_prefix."_main WHERE catid='$irow[0]'") or die("Sql error:" . mysql_error());

$countedrows = mysql_num_rows($countrows);

echo" <a href='index.php?obj_name=$irow[1]&catid=$irow[0]'>$irow[1]</a> ($countedrows) - ID: $irow[0]<br /> ";
                                      }
     }
echo "
</p>
</div>


<div class='ilay'>

<div class='rselect'><b>Add new object</b> / <a href='delobj.php'>Delete objects</a> / <a href='index.php'>Add new entries</a> / <a href='del.php'>Delete entries</a> / <a href='ip.php'>IP control</a> / <a href='smile.php'>Smilies</a> /  <a href='com/logout.php'>Logout</a></div> 
";

////////////////////////////////////////////////////
////////////////////////////////////////////////////


echo "

<hr />
<div style='width: 35%; background-color: ; border-top: 0px; border-bottom: 1px groove #333; padding: 5px;
margin: 0px auto; text-align: left;'>

<p>Add a new object to allow comments on.</p>
  <form name='f1' action='com/addobj.php' method='POST'>
    <div class='row' style='font-size: 11px;'>
      Object name: <input type='text' size='25' name='obj_name' />
    </div>
   <div class='row'>
<input type='submit' name='Add' value='Add object' />
   </div>
  <div class='spacer'>
  </div>
 </form>

</div>
<div align='left' style='font-size: 11px'>
<h4><u>Displaying the comments</u></h4>
<br />
To show the comments on a page copy and paste the following code:
<br />
<pre style='border : 1px solid #999999'>
<code>
&lt;?php
define(PATH, \"Path_To_Sayop_Directory_Goes_Here\");
define(ID, \"ID_Goes_Here\");

include(PATH.\"/sayop.php\");
showComms(ID,PATH);
?&gt;
</code>
</pre>
<p>
Modify the values inisde the double quotes in the first two lines. <br />
Write the full URL where the SayOp files are stored in the first line (NO trailing slash) and the ID of the article in the second line.
</p>

<p>


To show MORE then one set of comments on the same page, simply use the code above for the first set of comments and then use the following for the rest:

<pre style='border : 1px solid #999999'>
<code>
&lt;?php
showComms(ID_Goes_Here , PATH);
?&gt;
</code>
</pre>
<br />
(Just enter the object ID).



</p>

<h4>Don't forget to include the stylesheet between the document HEAD tags!</h4>
<code>&lt;link rel='stylesheet' type='text/css' href='path_to_sayop_dir/sayop_style_1.css' /&gt;</code>
<br /><br />
You can change the text style or color of the comments by editing the CSS file above.
</div>
";





echo "

</div>
</body>
</html>


";

} else {
smsg("<b>Access denied</b> <br /> Please use <a href='admin.php'>admin.php</a> to login.");
}
?> 
