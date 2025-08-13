<?php 

/* ------------------------------------------------------------ */
/*								*/
/*	File Area Management System (FAMS)			*/
/*								*/
/*	Copyright (c) 2001 by Bastian 'Buddy' Grimm		*/
/*	Autor: Bastian Grimm					*/
/*	Publisher: [ BG-Studios ]				*/
/*	eMail: bastian@bg-studios.de				*/
/*	WebSite: http://www.bg-studios.de			*/
/*	Date: 05.05.01		Version: DL Counter 1.10	*/
/*	Geändert am: 05.05.01					*/
/*								*/
/* ------------------------------------------------------------	*/

require("./config.php");
    
    $result = mysql_fetch_array(mysql_query("SELECT * FROM dl_files WHERE (EID = '$id')"));
    $hits = $result['hits'];
    $hits++;
    $res = mysql_query("UPDATE dl_files SET hits='$hits' WHERE (EID = '$id')");
    $file_url = $result['file_url'];
    header("Location: $file_url");

?>