<?php

// votum_view.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: nina $
// $Id: votum_view.php,v 1.21 2005/07/08 11:37:26 nina Exp $

// check whether lib.inc.php has been included
if (!defined("lib_included")) die("Please use index.php!");

// check role
if (check_role("votum") < 1) die("You are not allowed to do this!");


// link for a new vote
if (check_role("votum") > 1) {

    // tabs
    $tabs = array();
    $output = get_tabs_area($tabs);

    // button bar
    $buttons = array();
    if (!$todo_view_both and check_role("todo") > 1) {
        $buttons[] = array('type' => 'link', 'href' => 'votum.php?mode=forms&amp;sort='.$sort.'&amp;up='.$up.'&amp;page='.$page.'&amp;perpage='.$perpage.'&amp;keyword='.$keyword.'&amp;filter='.$filter.$sid, 'text' => __('New'), 'active' => false);
    }
    $output .= get_buttons_area($buttons);
    $output .= '<div class="hline"></div>';
    $output .= get_status_bar();

    /*******************************
    *         get polls
    *******************************/
    $result = db_query("SELECT ID, datum, von, thema, modus, an, fertig,
                               text1, text2, text3, zahl1, zahl2, zahl3, kein
                          FROM ".DB_PREFIX."votum") or db_die();
    $polls = '';
    $countp=0;
    while ($row = db_fetch_row($result)) {
    	 $countp++;
        if ($row[5] == "") $row[5] = "null";
        if ($row[6] == "") $row[6] = "null";
        // have a look whether the user is 1. participant of this poll but not 2. already answered this poll :-)
        if (ereg("\"$user_ID\"",$row[5])  and !ereg("\"$user_ID\"",$row[6])) {
            $day = substr($row[1],6,2);
            $month = substr($row[1],4,2);
            // begin form to vote
            $polls .= "
            <form style='display:inline;' action='votum.php' method='post'>
            <fieldset>
            <legend></legend>
            <input type='hidden' name='mode' value='data' />
            <input type='hidden' name='mode2' value='votum' />
            <input type='hidden' name='votum_ID' value='$row[0]' />
            <input type='hidden' name='datum' value='' />\n";
            if (SID) $polls .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
            // fetch author from user table
            $result2 = db_query("SELECT nachname
                                   FROM ".DB_PREFIX."users
                                  WHERE ID='$row[2]'") or db_die();
            $row2 = db_fetch_row($result2);
            // display poll
            $polls .= "<img src='$img_path/b.gif' border='0' width='7' alt='' /> &nbsp;&nbsp;<label for='radiopo$countp"."1"."'>".html_out($row[3])."</label><br />\n";
            // is it a poll where you can vote 1. alternatively (-> radio button)
            if ($row[4] == 'r') {
                // scan all three available option fields
                for ($i=1; $i<=3; $i++) {
                    // only display the option of a text is given
                    if ($row[$i+6]) {
                        $polls .= "<input type='radio' name='radiopoll' id='radiopo$countp".$i."' value='zahl".$i."' /> ".html_out($row[$i+6])."<br />\n";
                    }
                }
            }
            // ... or to click several options at once (-> checkboxes)
            else {
                // scan all three available option fields
                for ($i=1; $i<=3; $i++) {
                    // only display the option of a text is given
                    if ($row[$i+6] <> "") {
                        $polls .= "<input type='checkbox' name='zahl".$i."' value='yes' /> ".html_out($row[$i+6])."<br />\n";
                    }
                }
            }
            $polls .= get_go_button()."</fieldset></form>\n";
        }
    }
    if ($polls == '') {
        $polls = '<br />'.__('currently no open polls').'<br /><br />';
    }

    /*******************************
    *      get poll results
    *******************************/
    $result = db_query("SELECT ID, datum, von, thema, modus, an, fertig,
                               text1, text2, text3, zahl1, zahl2, zahl3, kein
                          FROM ".DB_PREFIX."votum
                         WHERE (an LIKE '%\"$user_kurz\"%' OR von = '$user_ID')
                      ORDER BY ID DESC") or db_die();
    while ($row = db_fetch_row($result)) {
        // start output
        // if the user is the author of this vote, he has the right to delete it
        if ($user_ID == $row[2]) {
            $poll_results .= "<br /><a href='".$path_pre."votum/votum.php?mode=data&amp;action=delete&amp;ID=".$row[0].$sid."' onclick=\"return confirm('".__('Are you sure?')."');\"><img src='$img_path/r.gif' alt='".__('Delete')."' title='".__('Delete')."' border='0' width='7'  /></a>&nbsp;\n";
        }
        $poll_results .= __('Poll Question: ')."<b>".html_out($row[3])."</b><br />\n";
        if ($row[4] == "c") {
            $poll_results .= __('several answers possible')."<br />\n";
        }
        $poll_results .= "<table class='opt'>";
        for ($i=7; $i<=9; $i++) {
            if($row[$i]) {
                $poll_results .=" <tr><td width='150'>";
                if ($row[4] == "r") {
                    $d = $row[$i + 3]*100/count(unserialize($row[6]));
                }
                else {
                    $d = $row[$i + 3];
                }
                if ($row[$i]) {
                    $poll_results .= __('Alternative ')." '".html_out($row[$i])."':</td><td align='right' width='35'>";
                    $poll_results .= sprintf ("%2.0d",$d);
                }
                if ($row[4] == "r") {
                    $poll_results .=" %";
                }
                else {
                    $poll_results .= "&nbsp;&nbsp;&nbsp;";
                }
                $poll_results .= "</td><td width='200'>";
                // draw line
                if ($d > 0 and count(unserialize($row[6])) > 0) {
                    $length = $row[$i + 3]/count(unserialize($row[6])) * 200;
                    $poll_results .= "<img src='$img_path/b.gif' width='$length' height='6' alt='' />";
                }
                $poll_results .= "&nbsp;</td></tr>";
            }
        }
        // show users with no vote
        if ($row[4] == "r") {
            $d = $row[13]*100/count(unserialize($row[6]));
        }
        else {
            $d = $row[13];
        }
        $poll_results .= "<tr><td>";
        $poll_results .= sprintf ("<i>".__('no vote: ').":</i></td><td align='right' width='35'> %2.0d",$d);
        if ($row[4] == "r") {
            $poll_results .= " %";
        }
        else {
            $poll_results .= "&nbsp;&nbsp;&nbsp;";
        }
        $poll_results .= "</td><td>";
        if ($row[13] > 0) {
            $length = $row[13]/count(unserialize($row[6])) * 200;
            $poll_results .= "<img src='$img_path/b.gif' width='$length' height='6' alt='' />";
        }
        $poll_results .= "&nbsp;</td></tr></table>\n";
        // statistic
        if (strlen($row[6]) < 6) {
            $c = 0;
        }
        else {
            $c = count(unserialize($row[6]))/count(unserialize($row[5]))*100;
        }
        $poll_results .= sprintf("%2.0d",$c);
        $poll_results .= ' % '.__('of').' '.count(unserialize($row[5])).' '.__('participants have voted in this poll');
        $poll_results .= "<br /><br />" ;
    }

    /*******************************
    *          output
    *******************************/
    $output .= '
    <br />
    <div class="inner_content">
    <a name="content"></a>
    <div class="boxHeader">'.__('Current Open Polls').'</div>
    <div class="boxContent">'.$polls.'</div>
    <br style="clear:both" /><br />

    <div class="boxHeader">'.__('Results of Polls').'</div>
    <div class="boxContent">'.$poll_results.'</div>
    <br style="clear:both" /><br />
    </div>

';

}

echo $output;

?>
