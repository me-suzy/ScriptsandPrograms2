<?php

/***************************************************************************

 m_excelexport.php
 --------------------
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

// Authentifizierung
include_once ("rootdatapath.php");

$GLOBALS["form"] = 'excelexport';
$GLOBALS["validaccess"] = VerifyAdminLogin();

includeLanguageFiles('admin','excelexport');

   global $_POST, $EZ_SESSION_VARS;

   adminformheader();
   admintitle(1,$GLOBALS["texFormTitle"]);
?>

<tr>
<td class="teaserheadercontent">
<?
// Einstellungen -------------------------------------

//error_reporting (E_ALL);
$URLself = "m_excelexport.php";
$orderrow = "authorname";
$page_template = "excelexport/template_mitglieder.htm";


// Includes
require ("../include/broxx/broxx_template.class.php");
require ("../include/broxx/broxx_db_mysql.class.php");
require ("excelexport/functions.php");

// Alte Excelfiles in Temp loeschen
$timegrenze = time() - (60*60*24); // 1 Tag zurueck
$handle=opendir("excelexport/excelexporter/temp");
while ($file = readdir ($handle)) {
  if ($file != "." && $file != "..") {
    if (strpos ($file, "mitgliederexport_") == 1) {
      $filetimestamp = substr ($file, 17);
      $filetimestamp = str_replace (".xls", "", $filetimestamp);
      if (preg_match ("/^[0-9]+$/", $filetimestamp)) {
        if ($filetimestamp < $timegrenze) { @unlink ("excelexport/excelexporter/temp/".$file); }
        }
      }
    }
  }
closedir($handle);

// MySQL Settings in config.php

// MySQL Connection ----------------------------------
$db = new broxx_db_mysql;
$db->connect ($GLOBALS["ezContentsDBServer"], $GLOBALS["ezContentsDBLogin"], $GLOBALS["ezContentsDBPassword"], $GLOBALS["ezContentsDBName"]);

// Deklarierung & Initialisierung --------------------
$PAGEcontent = "";
reg_getpostvars (array ("form_name","form_plz","form_ort","p"));
$str_suchergebnis = "";
if ($p == "") { $p = 1; }
$excelfile_name = "mitgliederexport_".time().".xls";
$excelfile = "excelexport/excelexporter/temp/".$excelfile_name;
$gesuchtnach_forExcel = "";
$zeilencount = 4;

function RenderGroups($GroupName)
{
        $sqlQuery = "SELECT usergroupname,usergroupdesc FROM ".$GLOBALS["eztbUsergroups"]." WHERE language='".$GLOBALS["gsLanguage"]."' ORDER BY usergroupdesc";
        $result = dbRetrieve($sqlQuery,true,0,0);
        $selectlist_usergroup .= '<option value="">-- '.$GLOBALS["tShowAll"].' --</option>';
        while ($rs = dbFetch($result)) {
                $selectlist_usergroup .= '<option ';
                $selectlist_usergroup .= 'value="'.$rs["usergroupname"].'">'.$rs["usergroupdesc"];
        }
        dbFreeResult($result);
		return $selectlist_usergroup;
} // function RenderGroups()

function RenderCountries($CountryCode)
{
	$sqlQuery = "SELECT countrycode,countryname FROM ".$GLOBALS["eztbCountries"]." order by countryname";
	$result = dbRetrieve($sqlQuery,true,0,0);
	$selectlist_laender .= '<option value="">-- '.$GLOBALS["tShowAll"].' --</option>';
	while ($rs = dbFetch($result)) {
		$selectlist_laender .= '<option ';
		$selectlist_laender .= 'value="'.$rs["countrycode"].'">'.$rs["countryname"];
	}
	dbFreeResult($result);
	return $selectlist_laender;
} // function RenderCountries()

$selectlist_laender .= RenderCountries();

$selectlist_usergroup .= RenderGroups();

// Gesucht nach
$gesuchtnach = ""; $gesuchtnach_forExcel = "";
if ($form_name != "") { $gesuchtnach .= $GLOBALS["texName"].": <b>".htmlentities($form_name) . "</b>, "; $gesuchtnach_forExcel .= $GLOBALS["texName"].": $form_name, "; }
if ($form_plz != "") { $gesuchtnach .= $GLOBALS["texPLZ"].": <b>".htmlentities($form_plz) . "</b>, "; $gesuchtnach_forExcel .= $GLOBALS["texPLZ"].": $form_plz, "; }
if ($form_ort != "") { $gesuchtnach .= $GLOBALS["texOrt"].": <b>".htmlentities($form_ort) . "</b>, "; $gesuchtnach_forExcel .= $GLOBALS["texOrt"].": $form_ort, "; }
if ($form_land != "") { $gesuchtnach .= $GLOBALS["texCountry"].": <b>".htmlentities($form_land) . "</b>, "; $gesuchtnach_forExcel .= $GLOBALS["texCountry"].": $form_land, "; }
if ($form_usergroup != "") { $gesuchtnach .= $GLOBALS["texUsergroup"].": <b>".htmlentities($form_usergroup) . "</b>, "; $gesuchtnach_forExcel .= $GLOBALS["texUsergroup"].": $form_usergroup, "; }
if ($gesuchtnach != "") { $gesuchtnach = substr ($gesuchtnach, 0, strlen ($gesuchtnach) - 2); }
if ($gesuchtnach != "") { $str_suchergebnis = '<br><b>'.$GLOBALS["texSearchKriteria"].':</b><br>'.$gesuchtnach; }
if ($gesuchtnach_forExcel != "") { $gesuchtnach_forExcel = substr ($gesuchtnach_forExcel, 0, strlen ($gesuchtnach_forExcel) - 2); }
else { $gesuchtnach_forExcel = $GLOBALS["texNoKriteria"]; }

// Template laden
$page_template = new broxx_template ($page_template);
$page_template -> loop_new ("loop", '{LOOP_start}', '{LOOP_end}');
$page_template -> loop_register_tags ("loop", array('{LOOP_FAHRSCHULE}','{LOOP_STRASSE}','{LOOP_PLZ}','{LOOP_ORT}','{LOOP_TEL}','{LOOP_FAX}','{LOOP_EMAIL}','{LOOP_URL}'));

// Suchabfrage
if ($send) {
  // query bauen
  $query = "";
  if ($form_name != "") { $query .= "(authorname like '%$form_name%' OR login like '%$form_name%' OR phone like '%$form_name%') AND "; }
  if ($form_plz != "") { $query .= "zip like '$form_plz%' AND "; }
  if ($form_ort != "") { $query .= "city like '%$form_ort%' AND "; }
  if ($form_land != "") { $query .= "countrycode = '$form_land' AND "; }
  if ($form_usergroup != "") { $query .= "usergroup = '$form_usergroup' AND "; }
  if ($query != "") {
    // ergebnisanzahl
    $query = "SELECT * FROM ".$GLOBALS["eztbAuthors"]." WHERE " . $query . "authorid >= 1 ORDER BY authorname";
   } else {
    $query = "SELECT * FROM ".$GLOBALS["eztbAuthors"]." ORDER BY authorname";
   }
    list($RESULT, $RESULTnum) = $db->read ($query);
    // ergebnisliste
    if ($RESULTnum <= 0) {
      $str_suchergebnis .= '<br><br>'.$GLOBALS["texNoResults"];
      }
    else {
      $str_suchergebnis .= '<br>'.$GLOBALS["texFoundResults"].': <b>'.$RESULTnum.'</b>';
      // link zu excelfile
      $str_suchergebnis .= '<br><br><b>'.$GLOBALS["texDlExcel"].':</b> <a href="'.$excelfile.'" target="_blank"><img src="excelexport/xls.gif" align=absmiddle hspace=7 border=0>'.$excelfile_name.'</a>';
      // liste erstellen
      /*
      while ($row = mysql_fetch_assoc($RESULT)) {
        if ($row["authoremail"] != "") { $row["authoremail"] = '<a href="mailto:'.htmlentities($row["authoremail"]).'">'.htmlentities($row["authoremail"]).'</a>'; } else { $row["authoremail"] = htmlentities($row["authoremail"]); }
        if ($row["website"] != "") { $row["website"] = '<a href="'.htmlentities($row["website"]).'" target="_blank">'.htmlentities($row["website"]).'</a>'; } else { $row["website"] = htmlentities($row["website"]); }
        if ($row["address"] == "") { $row["address"] = "-"; }
        if ($row["city"] == "") { $row["city"] = "-"; }
        if ($row["phone"] == "") { $row["phone"] = "-"; }
        if ($row["fax"] == "") { $row["fax"] = "-"; }
        if ($row["authoremail"] == "") { $row["authoremail"] = "-"; }
        if ($row["website"] == "") { $row["website"] = "-"; }
        $page_template -> loop_make ("loop", array(htmlentities($row["address"]),htmlentities($row["zip"]),htmlentities($row["city"]),htmlentities($row["phone"]),htmlentities($row["fax"]),$row["authoremail"],$row["website"]));
        }
      */
      }
//    }
 }

// Template ausgeben
$page_template -> replace_loop ("loop");
$page_template -> replace_tag ("{FORM_LIST_LAENDER}", $selectlist_laender);
$page_template -> replace_tag ("{FORM_LIST_USERGROUPS}", $selectlist_usergroup);
$page_template -> replace_tag ("{URL_SELF}", $URLself);
$page_template -> replace_tag ("{FORM_NAME}", $form_name);
$page_template -> replace_tag ("{FORM_PLZ}", $form_plz);
$page_template -> replace_tag ("{FORM_ORT}", $form_ort);
$page_template -> replace_tag ("{TITLE}", $GLOBALS["texTitel"]);
$page_template -> replace_tag ("{NAME}", $GLOBALS["texName"]);
$page_template -> replace_tag ("{PLZ}", $GLOBALS["texPLZ"]);
$page_template -> replace_tag ("{ORT}", $GLOBALS["texOrt"]);
$page_template -> replace_tag ("{LAENDER}", $GLOBALS["texCountry"]);
$page_template -> replace_tag ("{USERGROUP}", $GLOBALS["texUsergroup"]);
$page_template -> replace_tag ("{SUBMIT}", $GLOBALS["texSubmit"]);
$page_template -> replace_tag ("{STR_GESUCHTNACH}", $str_gesuchtnach);
$page_template -> replace_tag ("{STR_SUCHERGEBNIS_HEAD}", $str_suchergebnis);

$PAGEcontent = $page_template -> templ_content;

// Ausgabe
echo $PAGEcontent;

// MySQL Close Connection ----------------------------
$db -> kill();

?>
</td>
</tr>
</table>
</body>
</html>
<?php
if ($RESULTnum > 0) {

  // ############ exceldatei erstellen
  require_once "excelexport/excelexporter/Writer.php";
  $workbook = new Spreadsheet_Excel_Writer ($excelfile);
  $worksheet =& $workbook -> addWorksheet ("Export ".date ("j.n.Y"));
  // ## formatierungen
  // seitenueberschrift
  $format_mainheader =& $workbook -> addFormat();
  $format_mainheader -> setBold();
  $format_mainheader -> setFgColor(23);
  $format_mainheader -> setColor('white');
  $format_mainheader -> setFontFamily('Arial');
  $format_mainheader -> setSize(12);
  // suchkriterien
  $format_kriterien =& $workbook -> addFormat();
  $format_kriterien -> setFgColor(43);
  $format_kriterien -> setColor('black');
  $format_kriterien -> setFontFamily('Arial');
  $format_kriterien -> setSize(9);
  // spaltentitel
  $format_spaltentitel =& $workbook -> addFormat();
  $format_spaltentitel -> setBold();
  $format_spaltentitel -> setFgColor(51);
  $format_spaltentitel -> setColor('black');
  $format_spaltentitel -> setFontFamily('Arial');
  $format_spaltentitel -> setSize(10);
  $format_spaltentitel -> setBorder(1);
  $format_spaltentitel -> setBorderColor('black');
  // ## daten
  // datenarray ertellen
  for ($r=0;$r<$RESULTnum+10;$r++) { for ($c=0;$c<20;$c++) { $exceldata[$r][$c] = ""; } }
  // seitenueberschrift
  $exceldata[0][0] = $GLOBALS["texFormTitle"];
  // suchkriterien
  $exceldata[1][0] = $GLOBALS["texSearchKriteria"].': ';
  $exceldata[1][2] = $gesuchtnach_forExcel;
  // spaltentitel
  $worksheet -> setColumn(0,0,5);
  $exceldata[2][1] = 'ID';
  $worksheet -> setColumn(1,1,5);
  $exceldata[2][2] = 'NAME';
  $worksheet -> setColumn(2,2,30);
  $exceldata[2][3] = 'LOGIN';
  $worksheet -> setColumn(3,3,25);
  $exceldata[2][4] = 'EMAIL';
  $worksheet -> setColumn(4,4,25);
  $exceldata[2][5] = $GLOBALS["texAdresse"];
  $worksheet -> setColumn(5,5,30);
  $exceldata[2][6] = $GLOBALS["texPLZ"];
  $worksheet -> setColumn(6,6,7);
  $exceldata[2][7] = $GLOBALS["texOrt"];
  $worksheet -> setColumn(7,7,20);
  $exceldata[2][8] = $GLOBALS["texCountry"];
  $worksheet -> setColumn(8,8,10);
  $exceldata[2][9] = $GLOBALS["texPhone"];
  $worksheet -> setColumn(9,9,20);
  $exceldata[2][10] = 'FAX';
  $worksheet -> setColumn(10,10,20);
  $exceldata[2][11] = 'HOMEPAGE';
  $worksheet -> setColumn(11,11,30);
  $exceldata[2][12] = $GLOBALS["texUsergroup"];
  $worksheet -> setColumn(12,12,15);
  // mitgliederdaten
  for($n=0;$n<$RESULTnum;$n++) {
    $index = $zeilencount-1;
    $exceldata[$index][0] = $zeilencount;
    $exceldata[$index][1] = mysql_result($RESULT,$n,"authorid");
    $exceldata[$index][2] = mysql_result($RESULT,$n,"authorname");
    $exceldata[$index][3] = mysql_result($RESULT,$n,"login");
    $exceldata[$index][4] = mysql_result($RESULT,$n,"authoremail");
    $exceldata[$index][5] = mysql_result($RESULT,$n,"address");
    $exceldata[$index][6] = mysql_result($RESULT,$n,"zip");
    $exceldata[$index][7] = mysql_result($RESULT,$n,"city");
    $exceldata[$index][8] = mysql_result($RESULT,$n,"countrycode");
    $exceldata[$index][9] = mysql_result($RESULT,$n,"phone");
    $exceldata[$index][10] = mysql_result($RESULT,$n,"fax");
    $exceldata[$index][11] = mysql_result($RESULT,$n,"website");
    $exceldata[$index][12] = mysql_result($RESULT,$n,"usergroup");
    $zeilencount++;
    }
  // ## rendern
  for ($r=0;$r<count($exceldata);$r++) {
    for ($c=0;$c<count($exceldata[$r]);$c++) {
      // formatzuweisung
      $format = "";
      if ($r == 0) { $format = $format_mainheader; }
      if ($r == 1) { $format = $format_kriterien; }
      if ($r == 2) { $format = $format_spaltentitel; }

      // schreiben
      $worksheet -> write ($r, $c, $exceldata[$r][$c], $format);
      }
    }
  $workbook -> close();
  }
?>