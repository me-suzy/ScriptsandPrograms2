<?php

// chat.php - PHProjekt Version 5.0
// copyright © 2000-2005 Albrecht Guenther ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: chat.php,v 1.38.2.1 2005/09/07 14:02:32 fgraf Exp $

$module = 'chat';
$path_pre = '../';
global $chatfreq;

$include_path = $path_pre.'lib/lib.inc.php';
include_once $include_path;

$include_path = $path_pre.'lib/dbman_lib.inc.php';
include_once $include_path;

$chatfreq = (!PHPR_CHATFREQ) ? '10000' : PHPR_CHATFREQ;


if ($toggle_html_editor_flag == 1) html_editor_mode($module);
//html_editor_mode('chat');
$he_add = array();
// if (eregi("gecko", $_SERVER["HTTP_USER_AGENT"]))
$he_add[] = '
<script type="text/javascript">
<!--
window.setTimeout("chat_reload()",'.$chatfreq.');
function chat_reload() {
    var obj = encodeURIComponent(document.frm.content.value);
    var loc = "chat.php?content=" + obj;
    location.href = loc;
}
//-->
</script>
';

// for all other browsers
/* else {
    $he_add[] = '  <meta http-equiv="refresh" content="'.ceil($chatfreq/1000).
           '; URL=chat.php?mode='.$mode.'&'.session_name().'='.session_id().'" />'."\n";
}*/
//$onload[] = 'document.frm.content.focus();';

$include_path = $path_pre.'lib/lib.inc.php';
include_once $include_path;

$_SESSION['common']['module'] = 'chat';

// ****************
// check role
if (check_role('chat') < 1) die('You are not allowed to do this!');


// assign person to his group chat
$alivefile = $user_group.'_'.PHPR_ALIVEFILE;
$chatfile  = $user_group.'_'.PHPR_CHATFILE;

if ($mode == 'write') {
    writetext();
    $content = '';
}

echo set_page_header();

include_once($path_pre.'lib/navigation.inc.php');

$output = '
<div class="outer_content">
    <div class="content">
';

// tabs
$tabs = array();
$output .= get_tabs_area($tabs);

// context menu
include_once($path_pre.'lib/contextmenu.inc.php');
$menu3 = new contextmenu();
$output .= $menu3->menu_page_chat();
// end context menu

// button bar
$buttons = array();
$buttons[] = array('type' => 'link', 'href' => '../index.php?'.session_name().'='.session_id().'&amp;chataction=logout', 'text' => __('Quit chat'), 'active' => false);
$output .= get_buttons_area($buttons, 'oncontextmenu="startMenu(\''.$menu3->menusysID.'\',\''.$field_name.'\',this)"');
$output .= '
        <div class="hline"></div>
        <div class="inner_content">
            <a name="content"></a>
            <div class="chatUsers">'.alive().'</div>
            <div class="chatContent">'.chat().'</div>
            <div class="chatInput">'.input().'</div>
        </div>
    </div>
</div>

</body>
</html>
';

echo $output;

/* TODO: remove this, should be obsolete now
// Menu
if      ($mode == 'input') input();
else if ($mode == 'chat')  chat();
else if ($mode == 'alive') alive();
else if ($mode == 'check') check();
*/


function input () {
    global $css_style, $chat_entry_type, $content;

/*
    $onload[] = 'document.frm.content.focus();';
    echo set_page_header();
*/

    if (check_role('chat') > 1) {
        $output1 = "<form action='chat.php' method='post' name='frm'>\n";
        if ($chat_entry_type == 'textfield') {
            $output1 .= "<input type='text' name='content' size='70' />\n";
            $output1 .= get_buttons(array(array('type' => 'submit', 'name' => 'submit', 'value' => __('submit'), 'active' => false)));
        }
        else {
            $GLOBALS['show_html_editor']['chat'] > 0 ? $textarea_id = 'id="html_editor"' : $textareea_id = '';
            $output1 .= "<textarea name='content' rows='3' cols='70' $textarea_id>".xss(stripslashes($content))."</textarea>\n";
            $output1 .= get_buttons(array(array('type' => 'submit', 'name' => 'submit', 'value' => __('submit'), 'active' => false)));
        }
        $output1 .= "<input type='hidden' name='mode' value='write' />\n";
        $output1 .= "<input type='hidden' name='".session_name()."' value='".session_id()."' />\n";
        $output1 .= "</form>\n";
    }
    return $output1;
}


function writetext () {
    global $chatfile, $user_name, $user_firstname, $content, $chat_direction;

    // small irc hack - replace /me with the username
    $content = ereg_replace('/me', $user_firstname, $content);
    $content = ereg_replace("\r\n", "\n", $content);
    $content = ereg_replace("\n", "<br />", $content);

    // add time to new line
    $newcontent = '<tr valign="top"><td class="chats">';
    if (PHPR_CHAT_TIME == 2) $newcontent .= date('m-d H:i');
    else if (PHPR_CHAT_TIME) $newcontent .= date('H:i');
    $newcontent .= '</td><td> <b>';
    // first name and/or last name of the user depending on the setting inthe config
    switch (PHPR_CHAT_NAMES) {
        case '1':
            $newcontent .= $user_firstname;
            break;
        case '2':
            $newcontent .= $user_firstname.', '.$user_name;
            break;
        case '3':
            $newcontent .= $user_name.', '.$user_firstname;
            break;
        default:
            $newcontent .= $user_name;
    }

    $newcontent .= ":</b></td><td>".$content."</td></tr>\n";

    // fetch current chat file
    if (is_file($chatfile)) {
        $lines = file($chatfile);
        $lines2 = array_reverse($lines);
        //if ($chat_direction == 'bottom') $lines2[] = $newcontent;
        //else array_unshift ($lines2,$newcontent);
        array_unshift ($lines2,$newcontent);
        foreach ($lines2 as $line) $contents = $line.$contents;
        $contents = ereg_replace("\r\n", "\n", $contents);
        //$contents = ereg_replace("\n", "<br />\n", $contents);
        $deleted = unlink($chatfile);
    }
    // oh, first message?
    else $contents = $newcontent;
    // write whole message stuff to file
    $fp = fopen($chatfile, 'a+');
    flock($fp, 2);
    $fw = fwrite($fp, xss($contents));
    fclose($fp);
    // show input form
    //input();
}


function chat () {
    global $chatfile, $chatfreq, $css_style;
/*
    if (eregi("gecko", $_SERVER["HTTP_USER_AGENT"])) {
        echo "<script language=\"JavaScript\">window.setTimeout(\"location.reload()\",$chatfreq)</script>\n";
    }
    // for all other browsers
    else {
        $chatfreq = $chatfreq/1000;
        echo"<meta http-equiv=\"refresh\" content=\"$chatfreq; URL=chat.php?mode=chat".'&'.session_name()."=".session_id()."\" />\n";
    }
    echo "<link rel='stylesheet' type='text/css' href='$css_style'>\n</head>\n";
    echo set_body_tag();
*/
    // Here's start of the table
    $output1 .= "<table class='chat' width='95%'>\n";
    // no chat file? exit this frame in this moment and wait, until the first posting has been made.
    if (!file_exists($chatfile)) return '';

    // read the file into a array - one line one element
    $lines = file($chatfile);
    // put the lines in a reverse order and remove the backslashes
    for ($i = count($lines); $i >= (count($lines)-PHPR_MAX_LINES);$i--) {
        $output1 .= stripslashes($lines[$i]);
    }
    $output1 .= '</table>';/*and here the end of the table :-) */
    return  $output1;
}


function alive () {
    global $chatfile, $mode, $user_name, $user_firstname, $css_style;

    // workaround for gecko: use javascript
/*
    if (eregi("gecko", $_SERVER["HTTP_USER_AGENT"])) {
        echo "<script language=\"JavaScript\">window.setTimeout(\"location.reload()\",".PHPR_ALIVEFREQ.")</script>\n";
    }
    // for all other browsers
    else {
        $alivefreq2 = PHPR_ALIVEFREQ/1000;
        echo "<meta http-equiv=\"refresh\" content=\"$alivefreq2; URL=chat.php?mode=alive".'&'.session_name()."=".session_id()."\">\n";
    }
*/
    $i = 0;
    $a = 0;
    // read file alive and put into array $lines
    if (file_exists(PHPR_ALIVEFILE)) {
        $lines = file(PHPR_ALIVEFILE);
        // scan all users online
        while ($lines[$i]) {
            // extract  names and record time
            $li = explode(':', $lines[$i]);
            $time = time();  // take current time
            // exclude old records (probably crap from older sessions)
            if (($li[1] + PHPR_ALIVEFREQ/1000 + 5) > $time) {
                // entry for this user found?
                if ($li[0] == ($user_firstname.' '.$user_name)) {
                    // take the record into the new array with the current time
                    $lines2[] = $li[0].':'.$time;
                    $drin = 1;
                }
                // take current records of other users into the new array
                else {
                    $lines2[] = $li[0].':'.$li[1];
                }
            }
            $i++;
        }
    }

    //
    if (!$drin ) { $lines2[] = $user_firstname.' '.$user_name.':'.$time; }

    $fp = fopen(PHPR_ALIVEFILE, 'w+');
    flock($fp, 2);
    for ($i=0; $i < count($lines2); $i++) {
        $line = "$lines2[$i]\n";
        $fw = fwrite($fp,$line);
        $li = explode(':', $line);
        $output1 .= "&nbsp;&nbsp;$li[0] <br />";
    }
    fclose($fp);
    // inittialize chat file if it doesn't exist
    if (!isset($lines2[0])) {
        $fr = fopen($chatfile, 'w');
        fclose($fr);
    }
    return $output1;
}


/* TODO: remove this, should be obsolete now */
// // checks the chat file whether it has changed since the last refresh.
// // If yes, trigger the chat window to refresh
// function check() {
//     global $chatfile, $chatfreq;
//
//     echo "<html>\n<head>\n";
//     // reload this null frame
//     // workaround for gecko: use javascript
//     if (eregi("gecko", $_SERVER["HTTP_USER_AGENT"])) {
//         echo "<script type=\"text/javascript\">\nwindow.setTimeout(\"location.reload()\",$chatfreq)\n</script>\n";
//     }
//     // for all other browsers
//     else {
//         $chatfreq = $chatfreq/1000;
//         echo "<meta http-equiv=\"refresh\" content=\"$chatfreq; URL=chat.php?mode=check".'&'.session_name()."=".session_id()."\">\n";
//     }
//     clearstatcache();
//     $load = array();
//     // chek chatfile
//     if (file_exists($chatfile)) {
//         $stat = stat($chatfile);
//         if (!isset($_SESSION["lm_chatfile"]) || $_SESSION["lm_chatfile"] <> $stat[9]) {
//             // reload chat window
//             $load[] = 'parent.l.location.reload();';
//             // store new lastmod time in session
//             $_SESSION["lm_chatfile"] = $stat[9];
//         }
// /*
//         // check alivefile
//         $stat = stat(PHPR_ALIVEFILE);
//         if (!isset($_SESSION["lm_alivefile"]) || $_SESSION["lm_alivefile"] <> $stat[9]) {
//             // reload chat window
//             $load[] = 'parent.l.location.reload();';
//             // store new lastmod time in session
//             $_SESSION["lm_alivefile"] = $stat[9];
//         }
// */
//     }
//     echo "</head>\n";
//     if (count($load) > 0) {
//         echo "<body onload=\"".implode(' ', $load)."\">\n</body>\n";
//     }
//     echo "</html>\n";
// }

?>
