<?php
#######################################
# JIpFromX by jasx				  	  #
#  					                  #
#######################################
 //Current Version: v1.0
 //Date: 12/06/2003
 
 /*
 QUESTO SCRIPT PUO' ESSERE UTILIZZATE SOTTO LICENZA GPL 2.0 è SUCCESSIVE
 OGNI USO E DIFFUSIONE DI QUESTO SCRIPT E' CONSENTITO ED INCORAGGIATO DALL' AUTORE, ANCHE PER USI COMMERCIALI, PURCHE' NEL RISPETTO
 DI QUESTA LICENZA E A CONDIZIONE CHE, ANCHE NELLE VERSIONE MODIFICATE, VENGA LASCIATA INTATTA QUESTA LICENZA E I RIFERIMANTI 
 ALL'AUTORE ORIGINALE.  	                                                                                       

 L'AUTORE DECLINA OGNI GENERE DI RESPONSABILITA' DERIVANTE DALL' USO DI QUESTO SCRIPT, CHE VIENE FORNITO "COSI' COM'E'" SENZA ALCUN
 TIPO DI GARANZIA, NEANCHE IMPLICITA. CHI UTILIZZI QUESTO SCRIPT ACCETTA IMPLICITAMENTE QUESTA GARANZIA E LO FA SOTTO LA SUA DIRETTA 
 RESPONSABILITA'.
 
*/
#######################################
#  Per consigli , contatti , bugs	  #
#   ( e se volete donazioni:PppP )	  #
#			jasx@inwind.it			  #
#######################################


$db_host = 'localhost'; //host mysql
$db_user = 'root'; //nome utente db
$db_pass = ''; //password db
$db_table = 'application'; //tabella db



function ipFromLocate($params){
global $db_host;
global $db_user;
global $db_pass;
global $db_table;
$ip = $_SERVER['REMOTE_ADDR'];
$ip= sprintf("%u",ip2long($ip));
$link = mysql_connect($db_host,$db_user,$db_pass) or die ("Errore: ".mysql_error());
mysql_select_db($db_table);
$query = mysql_query("SELECT COUNTRY_CODE FROM ipfrom WHERE $ip BETWEEN IP_FROM AND IP_TO",$link) or die ("Errore: ".mysql_error());
while ( $valore = mysql_fetch_object($query)){
$code = $valore->COUNTRY_CODE;
foreach ( $params as $key=>$value){
$pos = strpos(trim($key),trim($code));
if($pos =='FALSE') {
header('location:'.$value);
break;}}}
}


function ipfromText(){
global $db_host;
global $db_user;
global $db_pass;
global $db_table;
$ip = $_SERVER['REMOTE_ADDR'];
$ip= sprintf("%u",ip2long($ip));
$link = mysql_connect($db_host,$db_user,$db_pass) or die ("Errore: ".mysql_error());
mysql_select_db($db_table);
$query = mysql_query("SELECT COUNTRY_NAME FROM ipfrom WHERE $ip BETWEEN IP_FROM AND IP_TO",$link) or die ("Errore: ".mysql_error());
while ( $valore = mysql_fetch_object($query)){
return $code = $valore->COUNTRY_NAME;
}
}

/*
ipfromLocate( 
		array(
	 //array formato dal codice paese e pagina di destinazione
	 //per un elenco completo dei codici consultare il file codici.htm	
     'IT' => 'index_it.htm', 
	 'GB' => 'index_gb.htm', 
	 'US' => 'index_us.htm'                          
));
*/

//IpFromText();
?>