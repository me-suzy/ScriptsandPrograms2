<html>
<head>
<title>Elenco codici paesi</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<table width="239" border="0">
  <tr> 
    <td width="46"><strong><font color="#FF0000" size="2">Code</font></strong></td>
    <td width="183">Stato</td>
  </tr>
<?php

include('JipfromX.php');
$link = mysql_connect($db_host,$db_user,$db_pass) or die ("Errore: ".mysql_error());
$query = mysql_query("select distinct(country_code) , country_name from ipfrom order by country_code",$link) or die ("".mysql_error());
while ( $valore = mysql_fetch_object($query)){
?>


  <tr> 
    <td><strong><font color="#FF0000" size="2"><?=$valore->country_code;?></font></strong></td>
    <td><?=$valore->country_name;?></td>
  </tr>


<?
}
?>

  

  
  
</table>

</body>
</html>
