<?php

	/*=====================================================================
	// $Id: calendar.php,v 1.3 2005/07/28 05:58:08 carsten Exp $
    // copyright evandor media Gmbh 2003
	//=====================================================================*/

	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");               // Date from past
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");   // HTTP/1.1
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");                                     // HTTP/1.0

    // --- standard inclusions --------------------------------------
    include ("../config/config.inc.php");
    include ("../connect_database.php");
    include ("../inc/functions.inc.php");

    // --- calendar class -------------------------------------------
    include ("../modules/common/calendar.class.php");
    
    // --- Header ---------------------------------------------------
	include ("../modules/common/header.calendar.php");

    // --- init -----------------------------------------------------
    $action = "";
    $day    = "";
    $month  = "";
    $year   = "";

    // Zusätzliche Variablen:
    isset ($_REQUEST["timestamp"]) ? $timestamp   = $_REQUEST["timestamp"] : $timestamp = null;
    $element   = $_REQUEST["element"];

    isset ($_REQUEST["action"]) && $action = $_REQUEST["action"];
    isset ($_REQUEST["day"])    && $day    = $_REQUEST["day"];
    isset ($_REQUEST["month"])  && $month  = $_REQUEST["month"];
    isset ($_REQUEST["year"])   && $year   = $_REQUEST["year"];

    // pagestats
    set_page_stats($user_id, "small_calendar");

    // Grunddatum: Wird gesetzt, falls timestamp nicht leer ist
    if ($timestamp <> "") {
       $day   = date ("d", $timestamp);
       $month = date ("m", $timestamp);
       $year  = date ("Y", $timestamp);
    }

    // Ist Action vorhanden?
    if ($action == "next") {
        $month++;
        if ($month == 13) { $month = 1; $year++; }
    }
    elseif ($action == "previous") {
        $month--;
        if ($month == 0) { $month = 12; $year--; }
    }

    // =====================================================================
    // Header und Javascripts
    //include ("header.inc");
?>
     <script language=javascript>
	
		function update_date (day, month, year) {
            opener.document.formular.<?=$element?>.value=day+"."+month+"."+year;
            window.close();
        }

        function change_date (action, act_month, act_year) {
            if (action == "vor")     document.Formular.action.value = 'next';
            if (action == "zurueck") document.Formular.action.value = 'previous';
            document.Formular.submit();
        }
     </script>

     <form name="Formular" method="post" action="calendar.php">
     <input type=hidden name=action value="">
     <input type=hidden name=element value="<?=$element?>">
<?php
    // Set Locale
    /*$lang_res = mysql_query ("SELECT set_local_str FROM sprachen WHERE sprache_id='$language'");
    logDBError (__FILE__, __LINE__, mysql_error());
	$lang_row = mysql_fetch_array ($lang_res);
    if ($lang_row <> "")
       setlocale (LC_TIME, $lang_row[0]);
    */
    
    $calendar = new calendar ();
    $calendar->init ($day, $month, $year);
    $calendar->choose_me("javascript", "update_date");
?>
    </form>

    </body>
</html>