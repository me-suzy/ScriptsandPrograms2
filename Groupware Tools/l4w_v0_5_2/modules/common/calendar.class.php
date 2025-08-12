<?php

	/*=====================================================================
    // $Id: calendar.class.php,v 1.3 2005/07/28 05:58:08 carsten Exp $
    // copyright evandor media Gmbh 2004
    //=====================================================================*/


    class calendar {

        var $day   = 1;
        var $month = 1;
        var $year  = 1970;

        var $navigationfunction = "";

        function init ($day, $month, $year) {
            $this->day   = $day;
            $this->month = $month;
            $this->year  = $year;
            // Datumsüberprüfung:
            if (!checkdate ($this->month, $this->day, $this->year)) {
               // Korrekturversuch
               $dummy = mktime (1,1,1,$this->month, $this->day, $this->year);
               $this->day    = date ("d", $dummy);
               $this->month  = date ("m", $dummy);
               $this->year   = date ("Y", $dummy);
            }
            // Korrektur geklappt?
            if (!checkdate ($this->month, $this->day, $this->year))
               die ("Kalender fehlerhaft initialisiert!");
        }

        function get_day   () {return $this->day; }
        function get_month () {return $this->month; }
        function get_year  () {return $this->year; }

        function add_javascript ($javascript) {
            echo "\n";
            echo $javascript;
            echo "\n";
        }

        function print_header ($bold) { ?>
            <tr>
            <td colspan=8 class='calendar_head'>
                <?php if ($bold) echo "<b>"?><?= strftime ("%A, %d.%b %Y")?><?php if ($bold) echo "</b>"?>
            </td>
            </tr>
        <?php
        }

        function print_month ($bold) {
        	$dummy = mktime (1,1,1,$this->month, $this->day, $this->year);
        	?>
            <tr>
            <td colspan=8 class='month'>
                <?php if ($bold) echo "<b>"?><?= strftime ("%B %Y", $dummy)?><?php if ($bold) echo "</b>"?>
            </td>
            </tr>
        <?php
        }

        function print_navigation () {
        	global $img_path, $language;

            $day   = $this->get_day();
            $month = $this->get_month();
            $year  = $this->get_year();

            $title = translate ("back one month");
            ?>
            <tr height=25>
            <td align=center style='border-bottom-style:solid; border-bottom-width:1px; border-color:#000000;'>
            <input type=image
            	onClick='javascript:change_date("zurueck", "<?=$month?>", "<?=$year?>");'
                src='<?=$img_path?>arrow_left.gif' title='<?=$title?>' alt='<?=$title?>'>
            </td>
            <td colspan=6 align=center style='border-bottom-style:solid;
                    border-bottom-width:1px; border-color:#000000;'>
                <select name=month onChange="change_date('');">
                <?php
                    for ($a=1; $a<13; $a++) {
                        if ($a == $month)
                            echo "\t\t\t<option value='".date("m",mktime(1,1,1,$a,1,$year))."' selected>".strftime("%b", mktime(1,1,1,$a,1,$year))."\n";
                        else
                            echo "\t\t\t<option value='".date("m",mktime(1,1,1,$a,1,$year))."'>".strftime("%b", mktime(1,1,1,$a,1,$year))."\n";
                } ?>
                </select>
                <select name=year onChange="change_date('');">
                <?php
                    for ($i=2000; $i<=2029; $i++) {
                        if ( $i == $year) {echo "\t\t\t<option selected>$i\n";}
                        else {echo"\t\t\t<option>$i\n";}
                } ?>
                </select>

                <input type=hidden name=day    value='<?=$day?>'>
                <?php if (!isset ($mode)) $mode = "";?>
                <input type=hidden name=mode   value='<?=$mode?>'>
            </td>
            <td align=center style='border-bottom-style:solid; border-bottom-width:1px; border-color:#000000;'>
            <?php $title = translate ("next month"); ?>
            <input type=image
            	onClick='javascript:change_date("vor", "<?=$month?>", "<?=$year?>");'
            	src='<?=$img_path?>arrow_right.gif' title='<?=$title?>' alt='<?=$title?>'>
            </td>
            </tr>
        <?php
        }

        function print_core ($selectable, $protocol, $link) {
            $day   = $this->get_day();
            $month = $this->get_month();
            $year  = $this->get_year();
            // =====================================================================
            // Ausgabe Kalender:
            $erster_Tag_im_Monat_num  = strftime ("%w", mktime (1,1,1,$month,1,$year));
            if ($erster_Tag_im_Monat_num == 0) $erster_Tag_im_Monat_num = 7; // Ausnahme: Sonntag
            $tag   = 2 - $erster_Tag_im_Monat_num;
            $zeile = 1;
            $go_on = true;

            while ((($go_on) AND (checkdate ($month, $tag, $year)) OR ($tag <= 0))){
                $zeile++;
                echo "\t<tr height=17>\n";
                echo "\t\t<td class='nrofweek'>".strftime ("%W", mktime (1,1,1,$month,($zeile*7 - 6),$year))."</td>\n";
                for ($i=1; $i<=7; $i++) {
                	$style = "";
                	if ($i > 5) $style = "style='background-color:ffffcc'";
                	$day_starts = mktime (0,0,0, $month, $tag, $year);
					$day_ends   = $day_starts + (24*60*60) - 1;


                    echo "\t\t<td align='center' class='calendar' $style>\n";
                    if (checkdate ($month, $tag, $year)) {
                        //$additional = "";
                        if (($selectable) AND ($protocol == "javascript")) {
                            echo "\t\t\t<a href='javascript:".$link." (\"".strftime ("%d", mktime (1,1,1, $month, $tag, $year))."\",\"".$month."\",\"".$year."\")'>";
                        }
                        elseif (($selectable) AND ($protocol == "http")) {
                            echo "\t\t\t<a href='".$link."&day=".strftime ("%d", mktime (1,1,1, $month, $tag, $year))."&month=".$month."&year=".$year."'>";
                        }
                        if (date("njY") == $month.$tag.$year) {
                            echo "<font color='red'>".strftime ("%d", mktime (1,1,1, $month, $tag++, $year))."</font></a></td>\n";
                        }
                        else {
                            echo "\t\t\t".strftime ("%d", mktime (1,1,1, $month, $tag++, $year))."</td>\n";
                    	}
                    }
                    else {
                        echo "\t\t\t<font color='#AAAAAA' size=1>".strftime ("%d", mktime (1,1,1, $month, $tag++, $year))."</font></a></td>\n";
                        if ($tag > 1) $go_on = false;
                    }
                }
                echo "\t</tr>\n";
            }
        }

        function print_weekdays () {  ?>
            <tr>
                <td width=25 bgcolor='#EEEEEE' align='center' style='border-bottom-style:solid; border-bottom-width:1px; border-right-style:solid; border-right-width:1px; border-color:#000000;'><i>Wo</i></td>
                <?php
                for ($i=0; $i<7; $i++)
                    echo "\t\t<td width=20  bgcolor='#EEEEEE' align='center' style='border-bottom-style:solid; border-bottom-width:1px; border-color:#000000;'>".substr(strftime("%a", mktime(1,1,1,1,($i+3),2000)),0,1)."</td>\n";
                ?>
            </tr>
        <?php
        }

        function define_navigation_js ($functionname) {
            $this->navigation_function = $functionname;
        }

        function print_me ($header = true, $month=false, $navigation=true) {
            echo "<table border=0 cellpadding=0 cellspacing=0>\n";
            if ($month)      $this->print_month(true);
            if ($header)     $this->print_header(true);
            if ($navigation) $this->print_navigation();
            $this->print_weekdays();
            $this->print_core(false, "", "");
            echo "</table>\n";
        }

        function choose_me ($protocol, $link, $header = true, $month=false, $navigation=true) {
            echo "<table border=0 cellpadding=0 cellspacing=0>\n";

            if ($month)      $this->print_month(true);
            if ($header)     $this->print_header(true);
            if ($navigation) $this->print_navigation();
            $this->print_weekdays();
            $this->print_core(true, $protocol, $link);
            echo "</table>\n";
        }

    }
?>