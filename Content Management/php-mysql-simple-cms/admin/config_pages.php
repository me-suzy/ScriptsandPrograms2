<?php
include ("auth.php");
?>
<html>
<head>
<title>Config pages</title>
<link href="style.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<?php 
database_connect();
if ($_POST['submit'] == Save){
	$title = $_POST['title'];
	$description = $_POST['description'];
	$keywords = $_POST['keywords'];
	$startpage = $_POST['startpage'];
	
	$fouten = array();
	if($title=="") $fouten[] = "Title is needed";
	if($description=="") $fouten[] = "Description is needed";
	if($keywords=="") $fouten[] = "keywords are needed";
	if($startpage=="") $fouten[] = "startpage is needed";
	
	if(sizeof($fouten)==0) {
	  			$sql = "UPDATE config
						SET titel='$title', description='$description', keywords='$keywords', startpage='$startpage'
						WHERE id=1"; 
				$query3 = mysql_query($sql) or die(mysql_error());
				if($query3) echo "<br>The configuration is succesfully saved.<br><br>\n";
	       
	}
	else if (sizeof($fouten)>0) {
		echo "<br><br><div class=\"fout\">Ooops.. problem</div>";
		foreach ($fouten as $fout)  echo "&nbsp;- " . $fout . "<br>";
		echo "<br><a href=\"javascript:history.go(-1);\">Go Back</a>";
		}


}else{

$query ="SELECT * 
         FROM config 
		 WHERE ID = 1;";
$result = mysql_query($query) or die(mysql_error());
while ($row = mysql_fetch_object($result)){
$startpage = $row->startpage;
$title = $row->titel;
$description = $row->description;
$keywords = $row->keywords;
}
?>
<form enctype="multipart/form-data" method="post">
<table>
<tr>
<td>Website title :</td>
<td><input type="text" name="title" value="<? echo $title; ?>"></td>
</tr>
<tr>
<td valign="top">Website description :</td>
<td><textarea name="description" class="input" rows="8" cols="19"><? echo $description; ?></textarea></td>
</tr>
<tr>
<td valign="top">Website keywords (search enging) :</td>
<td><textarea name="keywords" class="input" rows="8" cols="19"><? echo $keywords; ?></textarea></td>
</tr>
<tr>
<td>Startpage(homepage) :</td>
<td><select name="startpage" class="input">
<?php
$query2 = "SELECT *
           FROM content
		   ORDER BY position ASC;";
$result2 = mysql_query($query2) or die(mysql_error());
while($row2 = mysql_fetch_object($result2)){
$id = $row2->id;
$title = $row2->title;
print("<option value=\"$id\"> $title </option>");
}		    
?>
</select>
<?php
$result4 = mysql_query("SELECT title from content where id = '$startpage';") or die(mysql_error());
$row4 = mysql_fetch_object($result4);
$title4 = $row4->title;
 ?>
 <br>
<i>current homepage = <? echo "$title4"; ?> </i>
</td>
</tr>
<tr>
<td></td>
<td><input type="submit" class="input" value="Save" name="submit"></td>
</tr>
</table>
</form>
<? } ?>
</body>
</html>
