<?php

// forum.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: forum.inc.php,v 1.8.2.2 2005/08/22 08:39:11 fgraf Exp $

// check whether lib.inc.php has been included
if (!defined("lib_included")) { die("Please use index.php!"); }

/**
* lists all threads in a specified forum
* @author Nina Schmitt
* @param int fID: forum ID
*/
function threads($fID) {
  global $a, $user_ID, $antwort, $sid, $mode, $tree_mode, $user_group,$int, $sql_user_group, $page, $perpage, $keyword, $filter, $nr_answers, $img_path,  $langua, $fields, $forum_view_both,
$perpage_values, $user_kurz, $arrproj, $fID, $dbTSnull, $acc, $where, $max, $liste,$output1 ;

  $result = db_query("select * FROM ".DB_PREFIX."forum
            WHERE parent='$fID' AND (antwort=0 OR antwort IS NULL OR antwort='')")or db_die();
  $liste= make_list($result);


  //tabs
  $tabs = array();
  $output .= get_tabs_area($tabs);

  // button bar
  $buttons = array();
  $buttons[] = array('type' => 'text', 'text' => '<b>'.slookup("forum","titel","ID",$fID).'</b>');
  $buttons[] = array('type' => 'separator');

  // form start
  $hidden = array('mode' => 'forms', 'page' => $page, 'fID' => $fID);
  if(SID) $hidden[session_name()] = session_id();
  $buttons[] = array('type' => 'form_start', 'hidden' => $hidden);
  // submit
  $buttons[] = array('type' => 'submit', 'name' => 'newbei', 'value' => __('New posting'), 'active' => false);
  // form end
  $buttons[] = array('type' => 'form_end');
  // delete posting
  $buttons[] = array('type' => 'link', 'href' => 'forum.php?mode=options&amp;tree_mode='.$tree_mode.'&amp;sort='.$sort.'&amp;up='.$up.'&amp;page='.$page.'&amp;perpage='.$perpage.'&amp;keyword='.$keyword.'&amp;filter='.$filter.'&amp;fID='.$fID.$sid, 'text' => __('Delete posting'), 'active' => false);

  $output .= get_buttons_area($buttons);
  $output .='<div class="hline"></div>';
  $output .= get_forum_path_bar();
  $output .='<div class="hline"></div>';
  $output .= get_top_page_navigation_bar();
  $output .='<div class="hline"></div><a name="content"></a>';

   $output .="<table class=\"ruler\" id=\"contacts\" summary=\"__('In this table you can find all threads listed')\">
    <thead>
        <tr>
            <th class=\"column2\" scope=\"col\" title=\"Titel\">".__('Title')."</th>
            <th scope=\"col\" title=\"Autor\">".__('Author')."</th>
            <th scope=\"col\" title=\"Datum\">".__('Date')."</th>
        </tr>
    </thead><tbody>";
   $int=0;
  for ($i=($page*$perpage); $i < $max; $i++) {
        $result = db_query("SELECT * from ".DB_PREFIX."forum where ID = '".$liste[$i]."'")
        or db_die();

        $row = db_fetch_row($result);
        //$output.= "<tr";
        $output1='';
        tr_tag("forum.php?mode=forms&amp;ID=$row[0]&amp;fID=$fID&amp;page=$page",'',$row[0]);
        /**
        if($int%2==1){

             $output.= " class=\"unev\" ";
        }*/
        $output.=$output1;
        $output.=" <td scope='row'  class=\"column-1\">".

        forum_buttons($row[0])."
        <a href=forum.php?mode=forms&amp;ID=$row[0]&amp;fID=$fID&amp;page=$page>".html_out($row[3])."</a></td>
        <td>".slookup("users","nachname,vorname","ID",$row[2])."</td>
        <td>".show_iso_date2($row[6])."</td>
        </tr>";
        $a = 0;
        $nr_answers = 0;
        $int++;
            if ($arrproj[$liste[$i]]) {
        $r=antworten($row[0], $int);
        $output.= $r[0];
        $int= $r[1];
        }

    }

if($int==0)$output.="<tr><td></td><td></td><td></td></tr>";
$output.="</tbody></table>";
$output .='<div class="hline"></div>';
$output .= get_bottom_page_navigation_bar();
$output .='<div class="hline"></div>';
echo $output;

}
/**
* shows answers for an existing thread
* @author Nina Schmitt
* @param int fID: forum ID
* @param int ID: thread ID
* @return output
*/
function show_ans($fID, $ID) {
  global $a, $user_ID, $antwort, $sid, $mode, $tree_mode, $user_group,$int, $sql_user_group, $page, $perpage, $keyword, $filter, $nr_answers, $img_path,  $langua, $fields, $forum_view_both,
$perpage_values, $user_kurz, $arrproj, $fID, $dbTSnull, $acc, $where, $max, $liste, $output1;

  $result = db_query("select * FROM ".DB_PREFIX."forum
            WHERE parent='$fID'  AND antwort='$ID' ")or db_die();
  $liste= make_list($result);

   $output.="<table class=\"ruler\" id=\"contacts\" summary=\"__('In this table you can find all threads listed')\">
    <thead>
        <tr>
            <th class=\"column-1\" scope=\"col\" title=\"Titel\">".__('Succeeding answers')."</th>
            <th scope=\"col\" title=\"Autor\">".__('Author')."</th>
            <th scope=\"col\" title=\"Datum\">".__('Date')."</th>
        </tr>
    </thead><tbody>";

  for ($i=($page*$perpage); $i < $max; $i++) {
        $result = db_query("SELECT * from ".DB_PREFIX."forum where ID = '".$liste[$i]."'")
        or db_die();

        $row = db_fetch_row($result);
        /**
        $output.= "<tr";
        if($int%2==1){
             $output.= " class=\"unev\" ";
        }*/
        $output1='';
        tr_tag("forum.php?mode=forms&amp;ID=$row[0]&amp;fID=$fID&amp;page=$page",'',$row[0]);
        $output.=$output1;
        $output.="<td scope='row'  class=\"column-1\">".
        forum_buttons($row[0])."
        <a href=forum.php?mode=forms&amp;ID=$row[0]&amp;fID=$fID&amp;page=$page>".html_out($row[3])."</a></td>
        <td>".slookup("users","nachname,vorname","ID",$row[2])."</td>
        <td>".show_iso_date2($row[6])."</td>
        </tr>";
        $a = 0;
        $nr_answers = 0;
        $int++;
            if ($arrproj[$liste[$i]]) {
        $r=antworten($row[0], $int);
        $output.= $r[0];
        $int= $r[1];
        }

    }
$output.="</tbody></table>";
  return $output;
}

/**
* generates answers for existing topic
* @author Nina Schmitt
* @param int antwort: parent
* @param int int: count for line layout
* @return array output and int
*/
function antworten($antwort,$int="",$output2="") {
  global $a, $sid, $sql_user_group, $keyword, $filter,$fID, $mode, $tree_mode, $nr_answers, $type, $page, $perpage, $ID,
         $user_group, $user_kurz, $user_ID, $arrproj,$output1;

   $output1='';
  $result = db_query("select ID,antwort,von,titel,remark,kat,datum,gruppe,lastchange,notify
                        from ".DB_PREFIX."forum
                       where antwort = '$antwort' and
                             (von = '$user_ID' or acc like 'system' or
                             ((acc like 'group'  or acc like '%$user_kurz%') and
                             ".DB_PREFIX."forum.gruppe = '$user_group'))
                    order by lastchange desc") or db_die();
  while ($row = db_fetch_row($result)) {
    $nr_answers++;

     if ($tree_mode == "open" or $row[0] > 0) {
        /**
        $output2.= "<tr";
            if($int%2==1){
                 $output2.= " class=\"unev\" ";
            }*/

            tr_tag("forum.php?mode=forms&amp;ID=$row[0]&amp;fID=$fID&amp;page=$page",'',$row[0]);
            $output2.=$output1;
            $output2.=" <td scope='row'  class=\"column-1\">";
            for ($e = 0; $e <= $a; $e++) { $output2.="&nbsp;&nbsp;&nbsp;&nbsp;\n";  }
            $output2.=forum_buttons($row[0])."
            <a href=forum.php?mode=forms&amp;ID=$row[0]&amp;fID=$fID&amp;page=$page>".html_out($row[3])."</a></td>
            <td>".slookup("users","nachname,vorname","ID",$row[2])."</td>
            <td>".show_iso_date2($row[6])."</td>
        </tr>";
    // only display answers if $mode = "open"

    }
     $int++;
    $a++;
    if ($arrproj[$row[0]]) {

        $r=antworten($row[0], $int);
        $output2.= $r[0];
        $int= $r[1]; }
    $a--;
  }
  return array($output2, $int);
}

/**
* form to create buttons for topics with answers (+/-)
* @author Nina Schmitt
* @param int ID: topic ID
* @return string string with link to image
*/
function forum_buttons($ID) {
  global $arrproj, $sid,  $filter, $keyword, $up, $sort, $page, $perpage, $img_path, $tree_mode,  $fID;

  // if the radio button 'open' was selected: set all main projects to open:
  if ($tree_mode == "open") { $arrproj[$ID] = 1; }
  // find out whether there is at at least 1 subproject
  $result = db_query("select ID
                        from ".DB_PREFIX."forum
                       where antwort = '$ID'") or db_die();
  $row = db_fetch_row($result);

  if ($row[0] > 0) {
    // show button 'open'
    if (!$arrproj[$ID]) { $ret= "<a name='A".$row[0]."' href='forum.php?element_mode=open&amp;ID=$ID&amp;fID=$fID&amp;filter=$filter&amp;keyword=$keyword&amp;sort=$sort&amp;up=$up&amp;page=$page&amp;perpage=$perpage".$sid."#A$row[0]'><img src='$img_path/close.gif' border=0>&nbsp;</a>"; }
    // show button 'close'
    else { $ret= "<a name='A".$row[0]."' href='forum.php?element_mode=close&amp;ID=$ID&amp;fID=$fID&amp;filter=$filter&amp;keyword=$keyword&amp;sort=$sort&amp;up=$up&amp;page=$page&amp;perpage=$perpage".$sid."#A$row[0]'><img src='$img_path/open.gif' border=0>&nbsp;</a>"; }
  }
  // otherwise indent it
  else { $ret= "<img src='$img_path/t.gif' width=12 height=5 border=0>"; }
  return  $ret;
}

/**
* function whiuch returns the number of articles for a specific forum
* @author Nina Schmitt
* @param int ID: forum ID
* @param string field: field in which forum:ID is saved in Database
* @param string cons: string wit additional constraints for sql query
* @return int number of articles in forum
*/
function get_articles($ID, $field, $cons="") {
    $result = db_query("SELECT COUNT(ID) FROM ".DB_PREFIX."forum
                         WHERE ".qss($field)." = '$ID' $cons") or db_die();
    $row = db_fetch_row($result);
    return $row[0];
}

/**
* function which returns the date of the last articles in a specified forum
* @author Nina Schmitt
* @param int ID: forum ID
* @param string field: field in which forum:ID is saved in Database
* @param string cons: string wit additional constraints for sql query
* @return string date of last article in forum
*/
function get_lastarticle($ID, $field, $cons="") {
    $result = db_query("SELECT datum  FROM ".DB_PREFIX."forum
                         WHERE ".qss($field)." = '$ID' $cons ORDER BY datum DESC LIMIT 1") or db_die();
    $row = db_fetch_row($result);
    return show_iso_date2($row[0]);
}

?>
