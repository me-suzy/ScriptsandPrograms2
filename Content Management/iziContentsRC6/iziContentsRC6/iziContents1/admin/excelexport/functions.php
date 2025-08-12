<?php
/***************************************************************************

 functions.php
 -------------------
 copyright : (C) 2005 - The iziContents Development Team

 iziContents version : 1.0
 fileversion : 1.0.1
 change date : 23 - 04 - 2005
 ***************************************************************************/

/***************************************************************************
 The iziContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding iziContents and ezContents must remain intact on the scripts and in the HTML for the scripts.

 For more info on iziContents,
 visit http://www.izicontents.com*/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the License which can be found within the
 *   zipped package. Under the licence of GPL/GNU.
 *
 ***************************************************************************/


//####################################################
//## funktion zur initialisierung und wertzuweisung
//## von uebergebenen variablen
//##
//## dazu ein array mit den gewuenschten
//## variablenbezeichnungen als parameter
//## rueckgabe: keine

function reg_getpostvars ($vars)
  {
  reset ($vars);
  while (list ($arraypos, $var_name) = each ($vars)) {
    global $$var_name; $$var_name = "";
    if (isset ($_GET["$var_name"])) $$var_name = $_GET["$var_name"];
    if (isset ($_POST["$var_name"])) $$var_name = $_POST["$var_name"];
    }
  }


//####################################################
//## funktion zur ausgabe einer seitennavigation (weiterblaettern)
//##
//## Parameter: Aktuelle Seite, Anzahl der gesamten Elemente, Elemente pro Seite,
//##            Link zu dieser Seite, Tabellenbreite der Navi,
//##            Bezeichnung der enthaltenen Elemente, Uebergabevariablenname
//## rueckgabe: html code der navigation

function pagenavi ($page=1, $numelemente=0, $numperpage=6, $link="", $naviwidth="100%", $elementbezeichnung="", $pagevar="p", $showbacknextbuttons="1", $align="center", $tablepadding="5")
  {
  // check
  $navi = "";
  $numpages = ceil ($numelemente/$numperpage);
  if ($numpages <= 0) $numpages = 1;
  if ($page > $numpages || $page < 1) $page = 1;
  // teile
  $str_back = "";
  if ($showbacknextbuttons == "1") { if ($page > 1) $str_back = '&lt; <a class=linkmitglied href="'.$link.'&'.$pagevar.'='.($page-1).'">ZUR&Uuml;CK</a>'; }
  else { $str_back = ''; }
  $str_next = "";
  if ($showbacknextbuttons == "1") { if ($page < $numpages) $str_next = '<a class=linkmitglied href="'.$link.'&'.$pagevar.'='.($page+1).'">WEITER</a> &gt;'; }
  else { $str_next = ''; }
  $str_arrow_start = ""; if ($page > 1) $str_arrow_start = '<a class=linkmitglied href="'.$link.'&'.$pagevar.'=1">&laquo;</a>';
  $str_arrow_end = ""; if ($page < $numpages) $str_arrow_end = '<a class=linkmitglied href="'.$link.'&'.$pagevar.'='.$numpages.'">&raquo;</a>';
  $str_arrow_next = ""; if ($page < $numpages) $str_arrow_next = '<a class=linkmitglied href="'.$link.'&'.$pagevar.'='.($page+1).'">&gt;</a>';
  $str_arrow_back = ""; if ($page > 1) $str_arrow_back = '<a class=linkmitglied href="'.$link.'&'.$pagevar.'='.($page-1).'">&lt</a>';
  $str_punkti_back = ""; if ($page > 2) $str_punkti_back = ' ... ';
  $str_punkti_next = ""; if ($page+3 < $numpages) $str_punkti_next = ' ... ';
  $str_pages_back = ""; if ($page > 1) $str_pages_back = '<a class=linkmitglied href="'.$link.'&'.$pagevar.'='.($page-1).'">'.($page-1).'</a>&nbsp;';
  $str_pages_page = '<b>'.$page.'</b>&nbsp;';
  $str_pages_next = "";
  for ($n=1;$n<=3;$n++)
    if ($page+$n <= $numpages) $str_pages_next .= '<a class=linkmitglied href="'.$link.'&'.$pagevar.'='.($page+$n).'">'.($page+$n).'</a> ';
  // output
  $navi .= '
<table width="'.$naviwidth.'" border="0" cellpadding="'.$tablepadding.'" cellspacing="0">
<tr>';
  if ($showbacknextbuttons == "1") { $navi .= '
<td nowrap width="30%">
<div align="left">'.$str_back.'</div></td>
<td nowrap width="40%">'; } else {  $navi .= '
<td nowrap width="100%">'; }
  $navi .= '
<div align="'.$align.'">'.$elementbezeichnung.' ('.$numpages.' Seiten) | '.$str_arrow_start.' '.$str_arrow_back.'
'.$str_punkti_back.$str_pages_back.$str_pages_page.$str_pages_next.$str_punkti_next.$str_arrow_next.'
'.$str_arrow_end.'</div></td>';
  if ($showbacknextbuttons == "1") { $navi .= '
<td nowrap width="30%"><div align="right">'.$str_next.'</div></td>'; }
  $navi .= '
</tr>
</table>';

  return $navi;
  }


//####################################################
//## funktion zur ausgabe des speicherbedarfs eines verzeichnisses
//##
//## Parameter: Verzeichnis desen groesses ermittelt wird
//## rueckgabe: speichergroesse in byte

function verzsize ($base)
  {
  if (!isset ($sumsize)) $sumsize = 0;
  $handle=opendir($base);
  while ($file = readdir ($handle)) {
    if ($file != "." && $file != "..") {
      if (is_dir ($base.$file))
        verzsize ($base.$file."/");
      else
        $sumsize = $sumsize + filesize ($base.$file);
      }
    }
  closedir($handle);
  return $sumsize;
  }

//####################################################
//## funktion zur rueckgabe eines string der abhaengig
//## vom charset mit htmlentities ersetzt wurde
//##
//## Parameter: String
//## rueckgabe: Formatierter String

function htmlentities_lang ($input)
  {
  global $LANGversions,$l;
  $output = "";
  if (isset ($LANGversions[$l]["charset"])) {
    if (strtolower ($LANGversions[$l]["charset"]) == "iso-8859-1") {
      $output = htmlentities ($input);
      }
    else {
      $output = $input;
      }
    }
  return $output;
  }

?>