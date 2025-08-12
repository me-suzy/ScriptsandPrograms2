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
|   > externe Datei, um die News auf jeder beliebigen Seite einzubinden
|
|	> Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-
|	> bedingungen (read-me.html) für weitere Informationen.
|	>-----------------------------------------------------------------
|	> This script is NOT freeware! Please read the Copyright Notice
|	> (read-me_eng.html) for further information. 
|
|	> $Id: newsinfo.php 2 2005-10-08 09:40:29Z alex $
|
+--------------------------------------------------------------------------
*/

// Diese Zeile anpassen, um die News per include-Befehl einzubinden
$path2news = "c:/inetpub/wwwroot/projekte/tpl_news/";

// Das Design der Ausgabe wird in folgender Funktion angepasst.
// Zwischen <<<EOT und EOT; kann normaler HTML-Code stehen.
// die Platzhalter stehen immer in geschweiften Klammern {}
//-------------------------------------------------------------------
// Folgende Befehle können VOR dem include verwendet werden:
//
//     Nur Überschriften anzeigen:
//     $news_parse_only_headlines = true; 
//     Nur diese Anzahl von Überschriften anzeigen (muss mit $news_parse_only_headlines
//     verwendet werden
//     $news_use_this_no =10;
//     Nur News aus dieser Kategorie anzeigen
//     $news_use_only_catid = 3;
//     Und danach folgt der include wie hier beispielhaft:
//     include("tpl_news/newsinfo.php");
//   

function parseNewsLoop($news) {
    return <<<EOT
				  <a name="{$news[newsid]}"></a>
                  <table width="99%" cellspacing="2" cellpadding="2" border="0">
                    <tr> 
                      <td>
					  	{$news[image_headline]} <span>{$news[fulldate]}</span><br />{$news[newshead]}</td>
                    </tr>
                    <tr>
                      <td style="text-align : justify;">
					  	{$news[image_text]} {$news[hometext]}
						{$news[morenews]}
						{$news[linkshow]}
                      </td>
                    </tr>
                    <tr> 
                      <td>
					  	{$news[writerlink]}
						{$news[recommend_link]}
						{$news[commentlink]}
					  </td>
                    </tr>
                  </table>
				  <hr />
EOT;
}

function parseNewsHead($news) {
    return <<<EOT
        &nbsp;&nbsp;<b>&raquo;&nbsp;<a href="{$news[headline_link]}">{$news[headline_title]}</a></b> <span>{$news[headline_date]}</span><br />
EOT;
}

// ----------------------------------------------------------------
// --------------- ab hier keine Anpassungen mehr notwendig -------
// ----------------------------------------------------------------
error_reporting(E_ALL & ~E_NOTICE);

require_once($path2news."include/config.inc.php");
if(!class_exists(db_sql)) require_once($path2news."admin/enginelib/class.db.php");
if(!$info_added) require_once($path2news."admin/enginelib/driver/function.driver.".BOARD_DRIVER.".php");

$news_sql = new db_sql($dbName,$hostname,$dbUname,$dbPasswort);
// ----------------------------------------------------------------
//----------------------------------- Functions Start -------------
// ----------------------------------------------------------------

/**
* loadEngineSettingNews()
*
* Einstellungen der Engine laden, und im Array
* $setting speichern, Url im Array $_ENGINE ablegen
*/
function loadEngineSettingNews() {
    global $news_sql,$set_table,$_ENGINE;
    
    $result = $news_sql->sql_query("SELECT * FROM $set_table");
    while($set = $news_sql->fetch_array($result)) {
        $set = stripslashesNewsArray($set);
        $setting[$set['find_word']] = $set['replace_value'];
    }
    
    $_ENGINE['main_url'] = $setting['newsscripturl'];
    $_ENGINE['languageurl'] = $_ENGINE['main_url']."/lang/".$setting['language']."/images";  
    $_ENGINE['std_group'] = $setting['std_group']; 
    $setting['engine_mainurl'] = $setting['newsscripturl'];
      
    return $setting;    
}

/**
* GetGerDayNews()
*
* Erstellt Datum - Tag
*/
function GetGerDayNews($day_number) {
    global $lang;
    $name_tag[0] = $lang['php_fu_day_0'];
    $name_tag[1] = $lang['php_fu_day_1'];
    $name_tag[2] = $lang['php_fu_day_2'];
    $name_tag[3] = $lang['php_fu_day_3'];
    $name_tag[4] = $lang['php_fu_day_4'];
    $name_tag[5] = $lang['php_fu_day_5'];
    $name_tag[6] = $lang['php_fu_day_6'];
    
    return $name_tag[$day_number];
}
	
/**
* GetGerMonthNews()
*
* Erstellt Datum - Monat
*/		
function GetGerMonthNews($month_number) {		
    global $lang;
    $name_monat[1] = $lang['php_fu_month_1'];
    $name_monat[2] = $lang['php_fu_month_2'];
    $name_monat[3] = $lang['php_fu_month_3'];
    $name_monat[4] = $lang['php_fu_month_4'];
    $name_monat[5] = $lang['php_fu_month_5'];
    $name_monat[6] = $lang['php_fu_month_6'];
    $name_monat[7] = $lang['php_fu_month_7'];
    $name_monat[8] = $lang['php_fu_month_8'];
    $name_monat[9] = $lang['php_fu_month_9'];
    $name_monat[10] = $lang['php_fu_month_10'];
    $name_monat[11] = $lang['php_fu_month_11'];
    $name_monat[12] = $lang['php_fu_month_12'];
    
    return $name_monat[$month_number];
}		

/**
* aseDateNews()
*
* Datum auf Basis der DB-Abfragen erstellen
* @param string $format
* @param integer $timeformat
* @param integer $month
*/
function aseDateNews($format,$timestamp,$month=0) {
	global $setting;
	$time = $timestamp+(3600*$setting['timeoffset']);	
	if($month && (eregi(m,$format) || eregi(n,$format))) {
		$month = GetGerMonthNews(date(n,$time));
		$output = date(d,$time).". ".$month." ".date(Y,$time);
	} else {
		$output = date("$format",$time);
	}
	return $output;
}	

/**
* newsUrl()
*
* Gibt die Url zu einer Seite inkl.Query-String zurück
* 
* @access public
* @param string $filename
* @return string
*/
function newsUrl($filename) {
    global $_ENGINE;
    $return_url = $_ENGINE['main_url']."/".$filename;
    return $return_url;

}

/**
* stripslashes_array()
*
* Führt die Funktion stripslashes auf ein Array aus
* @param array $array
*/
function stripslashesNewsArray(&$array) {
    reset($array);
    if(is_array($array)) {
    	foreach ($array as $key => $val) {
    		$array[$key] = (is_array($val)) ? stripslashesNewsArray($val) : stripslashes($val);
   		}
      	return $array;
	}	
}	

function getNewsCommentNo($news) { 
    global $setting,$newscomment_table,$news_sql;
    return $news_sql->num_rows($news_sql->sql_query("SELECT comid FROM $newscomment_table where newsid='$news' and com_status='1'"));
}

function sBBcodeNews($text,$imgcode = 1,$urlparsing = 1,$smilie = 1) {
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

function pickupNewsImage($pic) {
    global $config;
    $size = @getimagesize("./catgrafs/".$pic);
    if($size[0] != "") $catgraf[0] = "width=\"".$size[0]."\"";
    if($size[1] != "") $catgraf[1] = "height=\"".$size[1]."\"";    
    return $catgraf;		
}		

// ----------------------------------------------------------------
//----------------------------------- Functions END ---------------
// ----------------------------------------------------------------
$info_added = true;

$setting = loadEngineSettingNews();

$lang = array();
require_once($path2news."lang/".$setting['language']."/".$setting['language'].".php");

if($news_parse_only_headlines == true) {
    if($setting['headlineno'] > $setting['newsno']) $setting['headlineno'] = $setting['newsno'];
    if($news_use_this_no) $setting['headlineno'] = $news_use_this_no;
    $result = $news_sql->sql_query("SELECT * FROM $news_table 
                                    WHERE newsdate <= '".time()."' 
                                    AND (news_enddate >= '".time()."' 
                                    OR ISNULL(news_enddate)) 
                                    AND published = '1' 
                                    ORDER BY newsdate DESC LIMIT $setting[headlineno]");
    while($headl = $news_sql->fetch_array($result)) {
        $headl = stripslashesNewsArray($headl);	
        $headdate = aseDateNews($setting['shortdate'],$headl['newsdate'])." - ".aseDateNews($setting['shortdate'],$headl['newsdate'],1);		
        $headlink = "<a class=\"inbox\" href=\"".newsUrl("index.php")."#$headl[newsid]\">".stripslashes($headl['headline'])."</a>";	
		$headline = array('headline_link' => newsUrl("index.php")."#$headl[newsid]",
								'headline_title' => stripslashes($headl['headline']),
								'headline_date' => aseDateNews($setting['shortdate'],$headl['newsdate'],1)." - ".aseDateNews($setting['timeformat'],$headl['newsdate']));
        $newsprint .= parseNewsHead($headline);
    }
} else {	    	
    $newsno = 0;
    if($news_use_only_catid) $categorie = "AND $news_table.catid='".$news_use_only_catid."'";
    $result = $news_sql->sql_query("SELECT $news_table.*, $newscat_table.titel, $newscat_table.cat_image, $user_table.$username_table_column  AS postname, $user_table.$useremail_table_column AS postmail,COUNT($newslinks_table.linkid) AS linkC FROM $news_table
    							  LEFT JOIN $newslinks_table ON ($newslinks_table.newsid = $news_table.newsid)
    							  LEFT JOIN $newscat_table ON ($newscat_table.catid = $news_table.catid)
    							  LEFT JOIN $user_table ON ($user_table.$userid_table_column = $news_table.userid)
    							  WHERE $news_table.newsdate <= '".time()."' 
    							  AND ($news_table.news_enddate >= '".time()."' 
    							  OR ISNULL($news_table.news_enddate)) 
    							  AND $news_table.published = '1' 
    							  $categorie 
    							  GROUP BY $news_table.newsid ORDER BY $news_table.newsdate DESC LIMIT $setting[newsno]");
    while($news = $news_sql->fetch_array($result)) {
        $news = stripslashesNewsArray($news);
        unset($image);
        unset($linkshow);
        unset($count_reads);
        unset($catgraf);
    	unset($image_align_right);
    	unset($image_align_left);
    	unset($image_top);
    	unset($writerlink);
    	
        if ($setting['cat_pics'] == "1") {
    	
    		if($news['img_align']=="right" || $news['img_align']=="") {
    			$image_align = "right";
    			$image_top = false;
    		} elseif($news['img_align']=="left") {
    			$image_align = "left";	
    			$image_top = false;
    		} else {
    			$image_align = "absmiddle";
    			$image_top = true;
    		}	
    	
            if ($news['pic_n'] == 0) {
                if ($news['cat_image'] != "") {
                    $catgraf = pickupNewsImage($news['cat_image']);
                    $image = "<img src=\"$setting[catgrafurl]/$news[cat_image]\" $catgraf[0] $catgraf[1] border=\"0\" align=\"".$image_align."\" />";
                }
            }		   
            if ($news['pic_n'] == 1) {
                $catgraf = pickupNewsImage($news['pic_name']);
                $image = "<img src=\"$setting[catgrafurl]/$news[pic_name]\" $catgraf[0] $catgraf[1]  border=\"0\" align=\"".$image_align."\" />";
            }
            if ($news['pic_n'] == 2) $image = "";
        } else {
            $image = "";
        }
    	
    	if($image_top) {
    		$image_headline = $image;
    		$image_text = "";
    	} else {
    		$image_headline = "";
    		$image_text = $image;
    	}
    		
    	$writerlink = $lang['index_news_poster']." <a href=\"".newsUrl("misc.php?action=formmailer&memberid=".$news['userid'])."\">".trim($news['postname'])."</a>&nbsp;&nbsp;|";
    		
        if ($news['comments_allowed'] == 1) {
            $comments = getNewsCommentNo($news['newsid']);
            $commentlink = "&nbsp;&nbsp;<a href=\"".newsUrl("newscomments.php?newsid=".$news['newsid'])."\">".$lang['php_comments']."</a> (".$comments.")";
        } else {
            $commentlink = "";
        }
    		
        if ($news['news_links'] == 0) {
            $linkshow = "";
        } else {
            if ($news['linkC'] != "0") {
    			$linkshow .= "<br /><br />".$lang['index_more_links'];
                $result4 = $news_sql->sql_query("SELECT * FROM $newslinks_table WHERE newsid='$news[newsid]'");
                while ($links = $news_sql->fetch_array($result4)) {
                    $links = stripslashesNewsArray($links);
                    if($links['link_target'] == 1) {
                        $target = "target=\"_blank\"";
                    } else {
                        $target = "target=\"_self\"";
                    }					
                    $linkshow .= "<br />&nbsp;&nbsp;&nbsp;&nbsp;&raquo; <a href=\"".stripslashes($links['link_url'])."\" $target>".stripslashes($links['link_name'])."</a>";
                }
            } else {
                $linkshow = "";
            }
        }
    
        if ($news['newstext'] != "") {
            $newshead = "<a class=\"cat_headline\" href=\"".newsUrl("news.php?newsid=".$news['newsid'])."\">".stripslashes(trim($news['headline']))."</a>";
            $morenews = "&nbsp;&nbsp;[<a href=\"".newsUrl("news.php?newsid=".$news['newsid'])."\">".$lang['index_read_more']."</a>]";
            $image = "<a href=\"".newsUrl("news.php?newsid=".$news['newsid'])."\">".$image."</a>";
            $count_reads = "&nbsp;&nbsp;".$lang['php_reads1']." ".$news['reads']."x ".$lang['php_reads2'];
        } else {
            $newshead = "<span style=\"font-size: 12px;\"><b>".stripslashes(trim($news['headline']))."</b></span>";
            $morenews = "&nbsp;";
        }
    	
    	if($setting['categorie_before_headline']) $newshead = $setting['start_category_html'].trim($news['titel']).$setting['end_category_html']."&nbsp;".$newshead;

   		
        if ($news['is_html'] == 0) {
            include_once($path2news."admin/enginelib/class.bbcode.php");
            $bbcode = new engineBBCode(1,0,1,0,$config['smilieurl']);         
            $hometext = $bbcode->rebuildText($hometext);		
            $news['hometext'] = $bbcode->rebuildText($news['hometext'],1,1,1);
        }            	
        
        if($setting['activate_recommendation']) {
    		$recommend_link = "&nbsp;&nbsp;<a href=\"".newsUrl("recommend.php?newsid=".$news['newsid'])."\">".$lang['index_send_news']."</a>&nbsp;&nbsp;|";
    	} else {
    		$recommend_link = "";
    	}
    
        $day = date("D", $news['newsdate']);
    		
        switch($day) {
            case Tue;
                $day = $lang['php_tu'];
                break;
            case Wed;
                $day = $lang['php_we'];
                break;
            case Thu;
                $day = $lang['php_th'];
                break;
            case Fri;
                $day = $lang['php_fr'];
                break;
            case Sat;
                $day = $lang['php_sa'];
                break;
            case Sun;
                $day = $lang['php_su'];
                break;
            default;
                $day = $lang['php_mo'];
                break;
        }
        
        if($setting['newsdate'] == 1) {
            // Datum
            $fulldate = aseDateNews($setting['shortdate'],$news['newsdate']);
        } elseif($setting['newsdate'] == 2) {
            // Uhrzeit - Datum
            $fulldate = aseDateNews($setting['shortdate'],$news['newsdate'])." - ".aseDateNews($setting['timeformat'],$news['newsdate']);
        } elseif($setting['newsdate'] == 3) {
            // Tag, Datum
            $fulldate = $day.", ".aseDateNews($setting['shortdate'],$news['newsdate']);
        } else {
            // Tag, Datum - Uhrzeit
            $fulldate = $day.", ".aseDateNews($setting['shortdate'],$news['newsdate'])." - ".aseDateNews($setting['timeformat'],$news['newsdate']);
        }
        
    	$newsno++;
        
    	$news = array('fulldate' => $fulldate,
    						'hometext' => trim($news['hometext']),
    						'newshead' => $newshead,
    						'morenews' => $morenews,
    						'commentlink' => $commentlink,
    						'recommend_link' => $recommend_link,
    						'writerlink' => $writerlink,
    						'newsid' => $news['newsid'],
    						'image_text' => $image_text,
    						'image_headline' => $image_headline,
    						'linkshow' => $linkshow);
                            
        $newsprint .= parseNewsLoop($news);                       
    }    
}
echo $newsprint;
$news_sql->closeSQL();
?>
