<?
    include "auth.php";
	include "./dbfunctions.php";
?>
<html>
<head>
<title>Preview your page</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="./style.css" rel="stylesheet" type="text/css">
</head>
<body>
<table border="0" class="maintable" align="center" width="340">
  <tr> 
    <td align="center" height="40" colspan="2"> <span class="titel2">News Preview</span> 
      <hr> </td>
  </tr>
  <tr>
    <td height="21" colspan="2"> 
      <?php 
  $id = $_GET['id'];
  database_connect();
  $query = "select * from content where id = $id";
  $result = mysql_query($query);
  while ($rij = mysql_fetch_object($result)){
  $tijd = timestamp2datime($rij->posting_time);
  $titel = rieplees($rij->title);
  $tekst = rieplees($rij->text);
  $foto = $rij->fotos;
  $last_updated = timestamp2datime($rij->last_updated);
  if (!$foto == 0) {
      $fotoja = "<td>
		 		 <a href=\"../img/nieuws/$rij->fotos\" target=\"_blank\"><img src=\"../img/nieuws/thumb_$rij->fotos\" border=\"0\"></a>
		 		 </td>";
	  $colspan = "";
  }else{
      $fotoja ="";
	  $colspan = "colspan=\"2\"";
	  } 
  print("<table class=\"preview\">
         <tr>
		 <td width=\"40%\">
		 $tijd
		 </td>
		 <td width=\"60%\" align=\"right\">
		 <b>$titel<b>
		 </td>
		 </tr>
		 <tr>
         $fotoja
		 <td $colspan>
		 $tekst
		 </td>
		 </tr>
		 <tr>
		 <td colspan=\"2\" valign=\"bottom\">
		 <br>
		 <br>
		 <i>Last updated on $last_updated</i>
		 </td>
		 </tr>
		 </table>
  		");
  }
  ?>
    </td>
  </tr>
</table>
</body>
</html>
