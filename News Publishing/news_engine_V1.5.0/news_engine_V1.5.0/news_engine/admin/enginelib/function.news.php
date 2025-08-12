<?php
/*
+--------------------------------------------------------------------------
|   Alex News Engine
|   ========================================
|   by Alex Höntschel
|   (c) 2002 AlexScriptEngine
|   http://www.alexscriptengine.de
|   ========================================
|   Web: http://www.alexscriptengine.de
|   Email: info@alexscriptengine.de
+---------------------------------------------------------------------------
|
|   > Beschreibung
|   > Funktionsbibliothek
|
|	> Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-
|	> bedingungen (read-me.html) für weitere Informationen.
|	>-----------------------------------------------------------------
|	> This script is NOT freeware! Please read the Copyright Notice
|	> (read-me_eng.html) for further information. 
|
|	> $Id: function.news.php 2 2005-10-08 09:40:29Z alex $
|
+--------------------------------------------------------------------------
*/


function postcolor($comno) {
    global $config;
    if(($comno/2) != floor($comno/2)) {
        $background = $config['postcol1'];
    } else {
        $background = $config['postcol2'];
    }
    return $background;
}

function postCss($comno) {
    global $config;
    if(($comno/2) != floor($comno/2)) {
        $css = 'list_dark';
    } else {
        $css = 'list_light';
    }
    return $css;
}

function InsertPost($text) {
    $text = str_replace("'","&acute;", $text);
    $text = str_replace("\"","&quot;", $text);		
    return $text;		 
}		 		 
		
function sBBcode($text,$imgcode = 1,$urlparsing = 1,$smilie = 1) {
    global $sBBcode,$config;
	if($imgcode == 1) $sBBcode->imgcode = 1;
    if($urlparsing == 1) $text = $sBBcode->url_parse($text);

    $text= stripslashes($text);
    $text = htmlspecialchars($text);
    $text = $sBBcode->parsen($text);
    $text = nl2br($text);
    $text = str_replace(":-)","<image src=\"$config[smilieurl]/smile.gif\">",$text);
    $text = str_replace(";-)","<image src=\"$config[smilieurl]/wink.gif\">",$text);
    $text = str_replace(":O","<image src=\"$config[smilieurl]/wow.gif\">",$text);
    $text = str_replace(";-(","<image src=\"$config[smilieurl]/sly.gif\">",$text);
    $text = str_replace(":D","<image src=\"$config[smilieurl]/biggrin.gif\">",$text);
    $text = str_replace("8-)","<image src=\"$config[smilieurl]/music.gif\">",$text);
    $text = str_replace(":-O","<image src=\"$config[smilieurl]/cry.gif\">",$text);
    $text = str_replace(":-(","<image src=\"$config[smilieurl]/confused.gif\">",$text);
    $text = str_replace("(?)","<image src=\"$config[smilieurl]/sneaky2.gif\">",$text);
    $text = str_replace("(!)","<image src=\"$config[smilieurl]/notify.gif\">",$text);
    $text = str_replace(":!","<image src=\"$config[smilieurl]/thumbs-up.gif\">",$text);
    $text = str_replace(":zzz:","<image src=\"$config[smilieurl]/sleepy.gif\">",$text);
    $text = str_replace(":baaa:","<image src=\"$config[smilieurl]/baaa.gif\">",$text);
    $text = str_replace(":blush:","<image src=\"$config[smilieurl]/blush.gif\">",$text);
    $text = str_replace(":inlove:","<image src=\"$config[smilieurl]/inlove.gif\">",$text);
    $text = str_replace(":stupid:","<image src=\"$config[smilieurl]/withstupid.gif\">",$text);
    $text = str_replace(":xmas:","<image src=\"$config[smilieurl]/xmas.gif\">",$text);
    return $text;
}  		

function convertOrderBy($table, $order) {
    if($order == "nameA")	    return "$table.username ASC"; 
    if($order == "pnameA")	    return "$table.postername ASC"; 
    if($order == "rankA")	    return "$table.groupid ASC";
    if($order == "sinceA")	    return "$table.regdate ASC";
    if($order == "dateA")	    return "$table.newsdate ASC";
    if($order == "headA")	    return "$table.headline ASC";
    if($order == "nameD")	    return "$table.username DESC"; 
    if($order == "pnameD")	    return "$table.postername DESC"; 
    if($order == "rankD")	    return "$table.groupid DESC";
    if($order == "sinceD")	    return "$table.regdate DESC";
    if($order == "dateD")	    return "$table.newsdate DESC";
    if($order == "headD")	    return "$table.headline DESC";
}
		 
function getCommentNo($news) { 
    global $config,$newscomment_table,$db_sql;
    return $db_sql->num_rows($db_sql->sql_query("SELECT comid FROM $newscomment_table where newsid='$news' and com_status='1'"));
}
		 
function relatedComments($comments,$newsid) {
    global $config, $lang, $sess; 
    if ($comments == 1) {
        return GetCommentNo($newsid);
    }
}		 
		 
function relatedLinks($newsid) {
    global $config,$newslinks_table,$db_sql, $lang, $tpl, $links_loop;
    $result = $db_sql->sql_query("SELECT * FROM $newslinks_table WHERE newsid='$newsid'");
    $anzahl = $db_sql->num_rows($result);
    if ($anzahl != "0") {
		$news_loop = array();
        while ($links = $db_sql->fetch_array($result)) {
            $links = stripslashes_array($links);
            if($links['link_target'] == 1) {
                $target = "target=\"_blank\"";
            } else {
                $target = "target=\"_self\"";
            }
			$link_url = $links['link_url'];
			$link_name = $links['link_name'];
			
			$links_loop[] = array('target' => $target,
								'link_url' => $link_url,
								'link_name' => $link_name);
        }
		return true;
    } else {
		return false;
	}
}

function News($news) {
    global $news_table,$db_sql;
    $sql = $db_sql->query_array("SELECT * FROM $news_table WHERE newsid='$news'");
    return stripslashes_array($sql);
}

function holeComment($comid) {
    global $config, $newscomment_table,$db_sql;
    $sql = $db_sql->query_array("SELECT * FROM $newscomment_table WHERE comid='$comid'");
    return stripslashes_array($sql);
}	
		
function NewsPic($newcatid,$newspic) {
    global $config, $newscat_table,$db_sql;
    if ($newspic == 0) {
        $cat = $db_sql->query_array("SELECT cat_image FROM $newscat_table WHERE catid='$newcatid'");
        $pic = "<img src=\"$config[catgrafurl]/$cat[cat_image]\" alt=\"\" border=\"0\" align=\"middle\" />&nbsp;&nbsp;";
    }    
    if ($newspic == 1) $pic = "<img src=\"$config[catgrafurl]/$news[pic_name]\" alt=\"\" border=\"0\" align=\"middle\" />&nbsp;&nbsp;";
    if ($newspic == 2) $pic = "";

    return $pic;
}		
		
function NewsPoster($userid) {
    global $user_table,$db_sql,$userid_table_column;
    $sql = $db_sql->query_array("SELECT * FROM $user_table WHERE $userid_table_column='$userid'");
    return stripslashes_array($sql);
}	 
		
function GetArchivLinks() {
    global $news_table,$config,$db_sql, $lang, $newscat_table, $sess;
    $m = "";
    $y = "";
    if($config['archivsort'] == 1) {
        $sort = "ORDER BY newsdate ASC";
    } else {
        $sort = "ORDER BY newsdate DESC";
    }
    $result = $db_sql->sql_query("SELECT $news_table.*, $newscat_table.* FROM $news_table 
                                    LEFT JOIN $newscat_table ON ($newscat_table.catid = $news_table.catid) $sort");
    $i = 1;
    while ($news = $db_sql->fetch_array($result)) {
        unset($archiv_bitlink);
        $news = stripslashes_array($news);
        $monate = getdate($news['newsdate']);
        switch($monate['month']) {
            case January;
                $monate['month'] = $lang['php_fu_month_1'];
                break;
            case February;
                $monate['month'] = $lang['php_fu_month_2'];
                break;
            case March;
                $monate['month'] = $lang['php_fu_month_3'];
                break;
            case April;
                $monate['month'] = $lang['php_fu_month_4'];
                break;
            case May;
                $monate['month'] = $lang['php_fu_month_5'];
                break;
            case June;
                $monate['month'] = $lang['php_fu_month_6'];
                break;
            case July;
                $monate['month'] = $lang['php_fu_month_7'];
                break;
            case August;
                $monate['month'] = $lang['php_fu_month_8'];
                break;
            case September;
                $monate['month'] = $lang['php_fu_month_9'];
                break;
            case October;
                $monate['month'] = $lang['php_fu_month_10'];
                break;
            case November;
                $monate['month'] = $lang['php_fu_month_11'];
                break;						 
            default;
                $monate['month'] = $lang['php_fu_month_12'];
                break;
        }

    	if($config['archive_view']) {
            // Links nach Monaten unterteilt
            if ($monate['year'] != $y || $monate['month'] != $m) {
                $j = $i;
                $i++;            
                $news_link = $sess->url("newsarchiv.php?month=".$monate['mon']."&mlong=".$monate['month']."&year=".$monate['year']);
                $news_link_text = $monate['month']." ".$monate['year'];
                if($j != 1) $archiv_link .= "</table></legend></fieldset>\n\n";                
                $archiv_link .= "<fieldset><legend><a href=\"".$sess->url("newsarchiv.php?month=".$monate['mon']."&mlong=".$monate['month']."&year=".$monate['year'])."\"><b>".$monate['month']." ".$monate['year']."</b></a></legend><label><table width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\">";
                $m = $monate['month'];
                $y = $monate['year'];
            }  
        } else {
            // Links nur nach Jahren unterteilt
            if ($monate['year'] != $y) {
                $j = $i;
                $i++;
                $news_link = $sess->url("newsarchiv.php?month=yearly&year=".$monate['year']);
                $news_link_text = $monate['year'];            
                if($j != 1) $archiv_link .= "</table></legend></fieldset>\n\n";
                $archiv_link .= "<fieldset><legend><a href=\"".$sess->url("newsarchiv.php?month=yearly&year=".$monate['year'])."\"><b>".$monate['year']."</b></a></legend><label><table width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\">";                
                $m = $monate['month'];
                $y = $monate['year'];
            } 
        }
        	  
        if ($monate['year'] != $y) {
            $m = $monate['month'];
            $y = $monate['year'];
        }
				  
        if($monate['month'] == $m || $monate['year'] == $y) {
            $archiv_link .= "<tr>\n<td width=\"120\">".aseDate($config['longdate'], $news['newsdate'])."</td><td width=\"150\">".$news['titel']."</td><td><a href=\"".$sess->url("news.php?newsid=".$news['newsid'])."\">".$news['headline']."</a></td>\n</tr>";
            //$archiv_link .= "<tr>\n<td colspan=\"3\"><hr></td>\n</tr>";
        }
		  
    }
    return $archiv_link."</legend></fieldset>\n\n";
}		
		
function lastDay($monat) {
    global $lang;
    if ($monat == $lang['php_fu_month_1'] ||
        $monat == $lang['php_fu_month_3'] ||
        $monat == $lang['php_fu_month_5'] ||
        $monat == $lang['php_fu_month_7'] ||
        $monat == $lang['php_fu_month_8'] ||
        $monat == $lang['php_fu_month_10'] ||
        $monat == $lang['php_fu_month_12']) {
        $lastday = "31";
    } elseif ($monat == $lang['php_fu_month_2']) {
        $lastday = "28";
    } else {
        $lastday = "30";
    }	
    return $lastday;
}		
		
function WriteNewNews($poster,$headline,$catid,$hometext,$newstext,$comments_allowed,$published,$news_links,$is_html=0) {
    global $news_table,$db_sql;
    $newsdate =  time();
    $db_sql->sql_query("INSERT INTO $news_table (headline,hometext,newstext,catid,published,comments_allowed,newsdate,userid,news_links,is_html) 
                        VALUES ('".addslashes(htmlspecialchars($headline))."','".addslashes($hometext)."','".addslashes($newstext)."','$catid','$published','$comments_allowed','$newsdate','".addslashes(htmlspecialchars($poster))."','$news_links','".intval($is_html)."')");
    return $db_sql->insert_id();
}				
		
function WriteLinks($newsid,$link_name,$link_url,$link_target) {
    global $config,$newslinks_table,$db_sql;
    $db_sql->sql_query("INSERT INTO $newslinks_table (newsid,link_url,link_name,link_target) 
                        VALUES ('$newsid','".addslashes(htmlspecialchars($link_url))."','".addslashes(htmlspecialchars($link_name))."','".addslashes($link_target)."')");
}		
		
function GetActPostCat($catid) {
    global $newscat_table,$link;
    $result = sql_query("SELECT * FROM $newscat_table",$link);
    while ($cat = mysql_fetch_array($result)) {
        if ($cat['catid'] == $catid) {
            $sel = "selected=\"selected\"";
        } else {
            $sel = "";
        }
        echo "<option value=\"$cat[catid]\" $sel>".stripslashes($cat[titel])."</option>\n\t\t\t\t";
    }
}		
		
function pickupImage($pic) {
    global $config;
    $size = @getimagesize("./catgrafs/".$pic);
    if($size[0] != "") $catgraf[0] = "width=\"".$size[0]."\"";
    if($size[1] != "") $catgraf[1] = "height=\"".$size[1]."\"";    
    return $catgraf;		
}		

function getPosticon($value) {
    global $config;
    switch($value) {
        case $config['smilieurl']."/posticons/ausrufezeichen.gif":
            $ch = 1;
            break;
        case $config['smilieurl']."/posticons/biggrin.gif":
            $ch = 2;
            break;
        case $config['smilieurl']."/posticons/boese.gif":
            $ch = 3;
            break;
        case $config['smilieurl']."/posticons/cool.gif":
            $ch = 4;
            break;
        case $config['smilieurl']."/posticons/eek.gif":
            $ch = 5;
            break;
        case $config['smilieurl']."/posticons/frage.gif":
            $ch = 6;
            break;
        case $config['smilieurl']."/posticons/frown.gif":
            $ch = 7;
            break;
        case $config['smilieurl']."/posticons/icon1.gif":
            $ch = 8;
            break;
        case $config['smilieurl']."/posticons/lampe.gif":
            $ch = 9;
            break;
        case $config['smilieurl']."/posticons/mad.gif":
            $ch = 10;
            break;
        case $config['smilieurl']."/posticons/sad.gif":
            $ch = 11;
            break;
        case $config['smilieurl']."/posticons/smilie.gif":
            $ch = 12;
            break;
        case $config['smilieurl']."/posticons/thumb_down.gif":
            $ch = 13;
            break;
        case $config['smilieurl']."/posticons/thumb_up.gif":
            $ch = 14;
            break;
        case $config['smilieurl']."/posticons/tongue.gif":
            $ch = 15;
            break;
        case $config['smilieurl']."/posticons/noicon.gif":
            $ch = 16;
            break;                                                                                                                                                                                                
    }
    return $ch;
}

function buildBreadCrumb($bread) {
    global $config, $lang;
    $no = count($bread);
    $i = 1;
    foreach($bread as $val => $key) {
        if($i == 0) $breadcrumb .= "<a href=\"".$key."\" class=\"catrow\">".$val."</a>";
        if($i == $no) {
            $breadcrumb .= "<b>".$val."</b>";
        } else {
            $breadcrumb .= "<a href=\"".$key."\" class=\"catrow\">".$val."</a>";
        }
        
        if($i != $no) $breadcrumb .= "&nbsp;&raquo;&nbsp;";
        $i++;
    }
    return $breadcrumb;
    
}
		
?>
