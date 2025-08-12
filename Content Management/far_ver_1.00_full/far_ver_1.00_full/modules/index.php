<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>The FAR-PHP project</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?php
// se preiau datele vizitatorului
$ip_vizitator = $_SERVER['REMOTE_ADDR'];
$semnatura_browser = $_SERVER['HTTP_USER_AGENT'];
$venit_de_la = $_SERVER['HTTP_REFERER'];
$pagina_curenta = $_SERVER['PHP_SELF'];
$timp_curent = time();
//echo "<br>ip_vizitator = ".$ip_vizitator;
//echo "<br>semnatura_browser = ".$semnatura_browser;
//echo "<br>venit_de_la = ".$venit_de_la;
// echo "<br>pagina_curenta = ".$pagina_curenta;
// echo "<br>timp_curent = ".$timp_curent;

// se include fisierul de configurare
function config()
	{
	clearstatcache();
	if (file_exists("config.php"))
		{
		//echo "<br>0";
		return("config.php"); // director curent		
		}
	if (file_exists("../config.php"))
		{
		//echo "<br>1";
		return("../config.php"); // un director		
		}
	if (file_exists("../../config.php"))
		{
		//echo "<br>2";
		return("../../config.php"); // 2 directoare		
		}
	if (file_exists("../../../config.php"))
		{
		//echo "<br>3";
		return("../../../config.php"); // 3 directoare		
		}
	if (file_exists("../../../../config.php"))
		{
		//echo "<br>4";
		return("../../../../config.php"); // 4 directoare		
		}
	if (file_exists("../../../../../config.php"))
		{
		//echo "<br>5";
		return("../../../../../config.php"); // 5 directoare		
		}
	}
$fisier_config = config();
include_once($fisier_config);
// se salveaza datele in bd
$conectare = mysql_connect($server_bd, $user_bd, $parola_bd) OR die("<br>Eroare: Nu se poate conecta la baza de date");
mysql_select_db($nume_bd, $conectare) OR die("<br>Eroare: Nu se poate selecta baza de date.");
$interogare = "INSERT INTO ".$prefix_tabel_bd."robots (ip, browser, referer, timp, adresa) 
		VALUES ('".$ip_vizitator."', '".$semnatura_browser."', '".$venit_de_la."', '".$timp_curent."', 
		'".$pagina_curenta."')";
// echo "<br>".$interogare;
$rezultat = mysql_query($interogare, $conectare) OR die("<br>Eroare: Nu se poate executa interogarea");
mysql_close($conectare);

// se redirecteaza
echo '<META HTTP-EQUIV = "Refresh" Content = "0; URL ='.$adresa_url.'">';

?>
</body>
</html>
