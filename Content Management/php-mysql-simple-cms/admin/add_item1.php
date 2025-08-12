<?
include "auth.php";
?><html>
<head>
<title>Add page</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
.maintable {
	background: #EEEEEE;
	color: Black;
	background-position: center;
	font: 11px;
	vertical-align: middle;
}
</style>

<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
<? 
/**
 * Sent - add news
*/
if($_POST['submit']) { 
	$fouten = array();
	if($_POST['title']=="") $fouten[] = "Title is needed";
	if($_POST['Body']=="") $fouten[] = "Content is needed";
	if(!$_FILES['foto']['tmp_name']){
	                         if(isset($_GET['image'])) $fouten[] = "Please add an img";
	}		  
	
	if(sizeof($fouten)==0) {
	    $foto = $_FILES['foto']['tmp_name'];
		$tijd = date("YmdHis");
		$titel = addslashes($titel);
		$Body = addslashes($Body);
		$trefwoorden = addslashes($trefwoorden);
		$lid_id = $_SESSION['userid'];
		$status = 0;
		$gedrag = "nieuws";
		if (isset($_GET['image'])){
		$error_toevoegen = checkImage($foto, "image/pjpeg", "102400", "1024", "768");
	
		if(!isset($error_toevoegen)){ //foto is okay.goe
			$foto_naam = date("YmdHis").".jpg";
			$foto_thumb = "thumb_".$foto_naam;
			$bestemming = $absnewspath."/"; 
			//echo "$bestemming$foto_naam<br><br>"; //test
			if(@move_uploaded_file($foto, $bestemming.$foto_naam)) { //uploaden
				createThumb($bestemming.$foto_naam, $bestemming.$foto_thumb, 100, 100, 100);
			}else $error_toevoegen = "Problem adding picture<br />";				
    	} 
		else 
		{
		print("<div align=\"center\">Problem uploading picture : $error_toevoegen</div>");
		}
		}	
		database_connect();			
		$insert = "INSERT INTO
					content (title, posting_time, text, keywords, status) 
					VALUES ('$title', '$tijd', '$Body', '$trefwoorden', '$status')"; 						
		mysql_query($insert) or die(mysql_error()); 
		
	}
	if (sizeof($fouten)>0) {
		echo "<br><br><div class=\"fout\">Ooops.. problem</div>";
		foreach ($fouten as $fout)  echo "&nbsp;- " . $fout . "<br>";
		echo "<br><a href=\"javascript:history.go(-1);\">Go Back</a>";
	}	
	else {
		echo "Ok ! Your page has been added succesfully<br>";
	}
} else { 
		
?>
        <form method="post" enctype="multipart/form-data" id="Compose" name="Compose">
          <table border="0" align="center">
            <tr valign="top"> 
              <td align="right" class="label">Title:</td>
              <td><input name="title" type="text" class="input" id="title" size="35" maxlength="100"></td>
            </tr>
            <tr valign="top"> 
              <td align="right" class="label">Keywords:</td>
              <td><textarea name="trefwoorden" cols="35" rows="3" class="input" id="trefwoorden"></textarea></td>
            </tr>
            <tr valign="top"> 
              <td align="right" class="label">Content:</td>
              <td> <? include("editor/editor.php"); ?></td>
            </tr>
			<?
			if ($_GET['image']){
			?>
			<tr>
			
      <td align="right"> Figuur<i>*</i>: </td>
			<td> 
            <input name="foto" type="file" class="input">
			<input type="hidden" name="image" value="true">
			</td>
			</tr>
			<?
			}
			?>
            <tr valign="top">
              <td align="right" class="label"></td>
              <td><input name="submit" type="submit" value="submit" onClick="SetVals()"></td>
            </tr>
          </table>
          </form>
<? } ?>
    
</body>
</html>
