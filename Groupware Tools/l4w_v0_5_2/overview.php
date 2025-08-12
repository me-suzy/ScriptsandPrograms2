<?php

   	/*=====================================================================
	// $Id: overview.php,v 1.1 2004/10/20 12:21:12 carsten Exp $
    // copyright evandor media Gmbh 2003
	//=====================================================================*/

    include ("inc/pre_include_standard.inc.php");
    include ("classes/class.calendar.php");
    include ("classes/class.color.php");
    include ("classes/class.colormanager.php");

    $checked_news = var_include ("checked_news", "GET");

    $color_arr   = array ();
	$color_arr[] = "ff0000";
	$color_arr[] = "00ff00";
	$color_arr[] = "0000ff";
	$color_arr[] = "666699";
	$color_arr[] = "333366";
	$color_arr[] = "0000ff";

    $action    = var_include ("action", "POST");
    $day       = var_include ("day" , "POST");
    $month     = var_include ("month", "POST");
    $year      = var_include ("year", "POST");
    //evt. im den GET-Variablen?
    if ($day == "")    $day =   var_include ("day", "GET");
    if ($month == "")  $month = var_include ("month", "GET");
    if ($year  == "")  $year = var_include ("year", "GET");

    // pagestats
    set_page_stats(__FILE__);

    // Falls Datum nicht gesetzt, heutiges Datum setzen:
    if ((!$month) OR ($month == "")) $month = date ("m");
    if ((!$day) OR ($day == ""))     $day   = date ("d");
    if ((!$year) OR ($year == ""))   $year  = date ("Y");

    // Ist Action vorhanden?
    if ($action == "next") {
        $month++;
        if ($month == 13) { $month = 1; $year++; }
    }
    elseif ($action == "previous") {
        $month--;
        if ($month == 0) { $month = 12; $year--; }
    }

    include ("header.inc");

    $headline =  "<img src='".$img_path."overview.gif' align=top>&nbsp;";
    $headline .= get_from_texte ("Uebersicht", $language)."&nbsp;";
	$headline .= "<a href='javascript:helpsystem(\"".HELP_SYSTEM_URL."help.php?use_language=".$language."&name=overview&mytype=page\")'>";
	$headline .= "<img src='".$img_path."info.gif' border=0 align=top></a>";

	include ("leiste.php");

    //=================================================================
    // Terminmuster berechnen:
    $muster_start = mktime (0,0,0, $month, $day, $year);
    $muster_ende  = mktime (0,0,0, $month, ($day+7), $year)-(60*60);

    // Termine finden, deren Ende nach muster_start und deren Anfang vor muster_ende sind:
    //$valid_groups = get_all_groups ($user_id);
    //$where_groups = "";
    //for ($i=0; $i < count ($valid_groups); $i++)
    //    $where_groups .= " OR (grp='".$valid_groups[$i]."' AND group_read='true') ";
    $termin_query = "SELECT * FROM termine3_0 WHERE (
                      (owner='$user_id')

                            )
                            AND von<'$muster_ende' AND bis>'$muster_start'";
	$res = mysql_query ($termin_query, $db);
    logDBError (__FILE__, __LINE__, mysql_error());


	// Alle Termine dieser Art einlesen:
	$termin_array = array ();
	$k=0;
	while ($row = mysql_fetch_array ($res)) {
    	$termin_array[$k]['id']       = $row['id'];
    	$termin_array[$k]['von']      = $row['von'];
    	$termin_array[$k]['bis']      = $row['bis'];
    	$termin_array[$k]['owner']    = $row['owner'];
    	$termin_array[$k]['ort']      = $row['ort'];
    	$termin_array[$k]['text']     = $row['text'];
    	$termin_array[$k]['betreff']  = $row['betreff'];
    	$k++;
	}

    // Colormanager
    $colormgr = new colormanager ();
    $bg1      = new color (204,204,204);
    $bg2      = new color (255,255,204);
    $colormgr->init (0);  // Startfarbe mit Index 0
    $colormgr->add_bg_color ($bg1);
    $colormgr->add_bg_color ($bg2);

?>
    <script language=javascript>
       function change_date (action, act_month, act_year) {
            if (action == "vor")     document.Formular.action.value = 'next';
            if (action == "zurueck") document.Formular.action.value = 'previous';
            document.Formular.submit();
        }

        function zeige_todo(nummer) {
            var notiz = new Array();
            var ziel = "show_todo.php?todo_id=" + nummer;
            var name = "notiz"+nummer;
            notiz[nummer] = window.open(ziel, name, "location=no,menubar=no,width=330,height=330,resizable=yes");
            notiz[nummer].focus();
        }

        function zeige_notiz (nummer) {
        	link = "show_note.php?note_id="+nummer;
			window.open (link, "_blank", "location=no, menubar=no, width=330, height=330, resizable=yes, scrollbars=yes");
		}

		function read_news (nummer) {
			var link = "overview.php?checked_news="+nummer;
			document.location.href=link;
		}

    </script>


	<table class="frame" width="100%">
	<tr><td align="center">

	<form name="Formular" action="overview.php" method="post">
    <input type=hidden name=action value="">

    <table border=0>
    <?php

    	if ($checked_news > 0) {
    		$read_sql = "UPDATE intern_news SET gelesen='true' WHERE news_id='$checked_news'";
    		$read_res = mysql_query ($read_sql);
		    logDBError (__FILE__, __LINE__, mysql_error());
    	}

    	// News für User?
    	$datum = date ("Y-m-d");
    	$news_sql = "SELECT * FROM intern_news WHERE owner='$user_id' AND gelesen='false'
    				 AND (vorlage<='$datum' OR vorlage='0000-00-00')";
    	$news_res = mysql_query ($news_sql);
	    logDBError (__FILE__, __LINE__, mysql_error());

	    $found = false;
	    while ($news_row = mysql_fetch_array ($news_res)) {
	    	echo "<tr>";
	    	echo "<td colspan=3 style='border:1px solid red; padding:3px'>";
	    	echo "<b>".$news_row['headline']."</b>&nbsp;";
	    	echo "<input type='checkbox' onClick='javascript:read_news (".$news_row['news_id'].")'> mark as read</td>";
	    	echo "</tr>\n";
	    	echo "<tr>";
	    	echo "<td colspan=3 style='padding:3px;background-color:#ffffcc'>".$news_row['news']."</td>";
	    	echo "</tr>\n";
	    	$found = true;
	    }

	    if ($found) echo "<tr><td colspan=3>&nbsp;</td></tr>\n";

    ?>
    <tr>

    	<td valign=top>
        <table align=center width=200 style="border-width:1px; border-style:solid; border-color:#000000;" width='100%'>
        	<tr><td valign=top>
        	<!-- Statistik -->
   		    <?php include ("inc/statistic_overview.inc.php"); ?>
        	<!-- Statistik Ende-->
			</td></tr>
        	<tr><td valign=top>
        	<!-- Wiedervorlagen -->
   		    <?php include ("inc/follow_up_overview.inc.php"); ?>
        	<!-- Wiedervorlagen Ende-->
			</td></tr>
		</table>
    	</td>

    	<td valign=top>
        <table align=center width=200 height=300 style="border-width:1px; border-style:solid; border-color:#000000;" width='100%'>
        	<tr><td valign=top>
        	<!-- Todos -->
   		    <?php include ("inc/todos_overview.inc.php"); ?>
        	<!-- Todos Ende-->
			</td></tr>
		</table>
    	</td>

    	<td valign=top>
        <table align=center width=200 height=300 style="border-width:1px; border-style:solid; border-color:#000000;" width='100%'>
        	<tr><td valign=top>
        	<!-- Kalender -->
   		    <?php include ("inc/calendar_overview.inc.php"); ?>
        	<!-- Kalender Ende-->
			</td></tr>
		</table>
    	</td>

	</tr>
    </table>
    </form>

	</td></tr></table>

<?php include ("inc/timer.inc");?>
</body>
</html>