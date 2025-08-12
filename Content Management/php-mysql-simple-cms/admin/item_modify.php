<?
require "auth.php";
require "./dbfunctions.php";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Modify Page</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
</head>

<body>
<span class="titel1">Modify Page</span> <br>
<?
if(!isset($_GET['id'])) {
		echo "wrong parameters";
		exit;
	}
	/*//----------------------------------
	// DELETE PACKSHOT
	//----------------------------------
	if($_GET['mode']=="delete") {
	$id = $_GET['id'];
	// foto uit database halen
	$query = "UPDATE `shop_artikel` SET `foto` = '0' WHERE `id` = '$id' ";
 	mysql_query($query,$link) or die("foto niet verwijderd uit database");
    
	$queryke = "SELECT foto from shop_artikel where id = $id";
	$resulteke = mysql_query(queryke);
	while($rij = mysql_fetch_object($resulteke)){
	$foto_naam = "$rij->foto";
	}
    // foto en thumb deleten
	if (!unlink("$abspackpath/$foto_naam")) exit("error bij deleten van foto {$abspackpath}/{$foto_naam}");			
	if (!unlink("$abspackpath/thumb_$foto_naam")) exit("error bij deleten van thumb");

		
	}
	//----------------------------------
	// EINDE DELETE PACKSHOT
	//----------------------------------*/
	
	if ($_POST['submit'])
	{
		database_connect();
		//--- TESTen
		$id = $_GET['id'];
		$titel = $_POST['titel'];
		$trefwoorden = $_POST['trefwoorden'];
		$tekst = $_POST['tekst'];
		
		//begin image uploaden

		if($titel=="") $foutbericht .= "Please fill in a title.<br>";
	    
		if (!$foto==0) {
		$fotovar = $foto;
		}else if (!isset($error_toevoegen)){
		$fotovar = $foto_naam;
		}
		else
		{
		$fotovar = 0;
		}
		
		if($foutbericht) echo "<br>" . $foutbericht . "<br><input name=\"back\" type=\"button\" value=\"&lt; Back\" onClick=\"history.go(-1)\">";
		else {
					$sql = "UPDATE content
							SET title='$titel', keywords='$trefwoorden', text='$Body'
							WHERE id='$id'"; 
							
					}
			$query = mysql_query($sql)or die("There's a problem with the query: ". mysql_error()); 	
			if($query) echo "<br>The page is succesfully edit.<br><br>\n<a href=\"item_list.php\" target=\"links\"><img src=\"../img/ico_overview.gif\" width=\"19\" height=\"19\" border=\"0\" alt=\"Pages\"></a>&nbsp;<a href=\"item_detail.php?id=$id\"><img src=\"../img/ico_detail.gif\" width=\"19\" height=\"19\" border=\"0\" alt=\"More info\"></a>&nbsp;<a href=\"item_modify.php?id=$id\"><img src=\"../img/ico_edit.gif\" width=\"19\" height=\"19\" border=\"0\" alt=\"Edit\"></a>";
        	
	} 
	else 
	{	
		database_connect();		
	
		$select = "SELECT *
					FROM content
					where id = '$id'";
		$query = 		mysql_query($select);
		$nieuws = 		mysql_fetch_object($query);
		$deid = 		$nieuws->id;
		$titel = 		$nieuws->title;
		$trefwoorden =  $nieuws->keywords;
		$tijd =         $nieuws->posting_time;
		$tekst = 		$nieuws->text;
?>
<form action="" method="post" enctype="multipart/form-data" id="Compose" name="Compose"> 
  <table border="0">
    <tr> 
      <td class="titel3">title</td>
      <td><input name="titel" type="text" value="<? echo $titel; ?>" size="10" maxlength="20"></td>
    </tr>
    <tr> 
      <td class="titel3" width="75">time of posting</td>
      <td><input disabled name="tijd" type="text" value="<? echo timestamp2datime($tijd); ?>" size="25" maxlength="50"></td>
    </tr>
    <tr valign="top"> 
      <td width="75" class="titel3">keywords</td>
      <td><textarea name="trefwoorden" cols="30" rows="3" id="trefwoorden"><? echo $trefwoorden; ?></textarea></td>
    </tr>
    <tr valign="top"> 
      <td width="75" class="titel3">text</td>
      <td> <? include("editor/editor.php"); ?></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><input name="submit" type="submit" value="submit" onClick="SetVals()"></td>
    </tr>
  </table>
  </form>
<? } ?>						               
</body>
</html>