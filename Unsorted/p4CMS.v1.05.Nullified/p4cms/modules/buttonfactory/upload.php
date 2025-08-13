<?
session_start();
session_name('d4sess');
if (isset($_REQUEST[d4sess]) and !($_REQUEST[d4sess]=="")) {
	session_id($_REQUEST[d4sess]);
} 
$sessid = session_id();
$d4sess = session_id();

include("../../include/config.inc.php");
include("../../include/mysql-class.inc.php");
include("../../include/functions.inc.php");

if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
 SessionError();
 exit;
}
?>
<html>
<head>
<title>Motiv hochladen</title>
<link rel="stylesheet" href="../../style/style.css">
<script language="javascript" src="../../include/common.js"></script>
</head>
<body>

<?
if (!isset($_REQUEST['action'])) {
	?>
	<script>
	function checks() {
  if (document.all.mfile.value != "") {
    var ext1 = document.all.mfile.value;
    ext1 = ext1.substring(ext1.length-3,ext1.length);
    ext1 = ext1.toLowerCase();
    if(ext1 != 'png') { 
      alert('Sie haben eine .'+ext1+' Datei gewählt, erlaubt sind nur *.png - Dateien!');
      return false; 
    }
	
  }
   if(document.all.mfile.value == "") { 
	 alert('Bitte geben Sie ein Motiv an.');
      return false; 
	 }
}
	</script>
	<center>
	<form action="" method="post" enctype="multipart/form-data" onSubmit="return checks();">
	<input type="hidden" name="action" value="up">
	Motiv (*.png): <input type="file" name="mfile"><br><br><input type="submit" class="button" value=" Weiter ">
	</form>
	</center>
	<?
}
if ($_REQUEST['action']=="up") {
	while(list($key,$val) = each($_REQUEST)) {
		if (ereg("([0-9]*)\,([0-9]*)", $key)) {
			list($l,$t) = explode(",", str_replace("?","",$key));
		}
	}
	$fn = time() . ".png";
	if (!isset($_REQUEST[datei])) {
		move_uploaded_file($_FILES['mfile']['tmp_name'], str_replace("upload.php", $fn, $_SERVER[SCRIPT_FILENAME]));
	} else {
		$fn = $_REQUEST[datei];
	}
	?>
	<form style="display:inline;" action="" method="post">
	<input type="hidden" name="datn" value="<?=$fn;?>">
	<input type="hidden" name="action" value="save">
	<input type="hidden" name="t" value=<?=$t;?>">
	<input type="hidden" name="l" value="<?=$l;?>">
	<b>Textposition:</b><br>
	<a style="cursor:crosshair;" href="upload.php?action=up&datei=<?=$fn;?>&"><img src="place.php?bbutton=<?=$fn;?>&bl=<?=$l;?>&bt=<?=$t;?>&bfarbe=%23000000&bsize=10&bfont=arial.ttf" ismap border="0"></a>
	<br><br>
	<b>Schriftfarbe:</b><br>
	<input type="hidden" name="farbe" value="#000000">
	<table height="48" width="100%" border="0" bordercolor="#000000" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
				<tbody>
					<tr>
						<script language="JavaScript">
							var c = new Array();
							c[1] = "FF";
							c[2] = "CC";
							c[3] = "99";
							c[4] = "66";
							c[5] = "33";
							c[6] = "00";
							var d = 0;
							for(i=1; i <=6; i++) {
								if(i > 0) {
									document.write("</tr><tr>"); 
								}
								for(m=1;m <=6;m++) {

									for(n=1;n <=6;n++) {
										d++;
										color = c[i] + c[m] + c[n];
										document.write("<td style=\"cursor:hand;\" onClick=\"document.all.farbe.value='#" + color + "';\" bgcolor=\"#"+color+"\" width=6><img src=\"_i3/img/pix.gif\" width=1 height=1  border=0 alt=\"#"+color+"\"></td>");
									}
								}
							}
						</script>
					</tr>
				</tbody>
			</table>    
            <br><br>
            <b>Schriftart:</b><br>   
            <select name="afont">
                <option value="arial.ttf">Arial</option>
                <option value="verdana.ttf">Verdana</option>
                <option value="trebuc.ttf">Trebuchet</option>
                <option value="cour.ttf">Courier New</option>
                <option value="gara.ttf">Garamond</option>
                <option value="tahoma.ttf">Tahoma</option>
            </select>  
            <br><br>
            <center><input type="submit" class="button" value=" Übernehmen "></center>
			</form>
            <?
}

if ($_REQUEST['action']=="save") {
	$sql =& new MySQLq();
	$sql->Query("INSERT INTO " . $sql_prefix . "buttons(bild,t,l,font,farbe) VALUES('$_REQUEST[datn]','$_REQUEST[t]','$_REQUEST[l]','$_REQUEST[afont]','$_REQUEST[farbe]')");
	$sql->Close();
	?>
	<script>
	window.opener.document.location.href=window.opener.document.location.href;
	window.close();
	</script>
	<?
}
?>

</body>
</html>