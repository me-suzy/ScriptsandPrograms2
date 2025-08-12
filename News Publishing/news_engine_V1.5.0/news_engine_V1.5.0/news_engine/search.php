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
|   > Newssuche
|
|	> Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-
|	> bedingungen (read-me.html) für weitere Informationen.
|	>-----------------------------------------------------------------
|	> This script is NOT freeware! Please read the Copyright Notice
|	> (read-me_eng.html) for further information. 
|
|	> $Id: search.php 2 2005-10-08 09:40:29Z alex $
|
+--------------------------------------------------------------------------
*/

include_once('lib.inc.php');
include_once($_ENGINE['eng_dir']."admin/enginelib/function.".ENG_TYPE.".php");

$tpl->register('title', $lang['title_search']);

if(!$auth->user['canuseenginesearch']) {
    header("Location: ".$sess->url("index.php"));
    exit;
}

if ($_GET['action'] == 'search') {
    $tpl->loadFile('main', 'search.html');
    $tpl->register('breadcrumb', buildBreadCrumb(array($lang['php_overall_home'] => $config['mainurl'], $lang['title_engine'] => $sess->url('index.php'), $lang['search_search'] => '')));
    $tpl->register(array(
                        'search_search' => $lang['search_search'],
                        'search_searchphrase' => $lang['search_searchphrase'],
                        'search_define_a_searchphrase' => $lang['search_define_a_searchphrase'],
                        'search_contains_all_words' => $lang['search_contains_all_words'],
                        'search_contains_one_word' => $lang['search_contains_one_word'],
                        'search_search_option' => $lang['search_search_option'],
                        'search_search_in_headline' => $lang['search_search_in_headline'],
                        'search_search_in_entry' => $lang['search_search_in_entry'],
                        'search_search_in_headline_entry' => $lang['search_search_in_headline_entry'],
                        'search_number_result' => $lang['search_number_result'],
                        'search_10_results' => $lang['search_10_results'],
                        'search_20_results' => $lang['search_20_results'],
                        'search_no_limitation' => $lang['search_no_limitation'],
                        'search_sorting_results' => $lang['search_sorting_results'],
                        'search_date_ascending' => $lang['search_date_ascending'],
                        'search_date_descending' => $lang['search_date_descending'],
                        'search_name_ascending' => $lang['search_name_ascending'],
                        'search_name_descending' => $lang['search_name_descending'],
                        'search_search_btn' => $lang['search_search_btn'],
                        'search_reset_btn' => $lang['search_reset_btn']));
}    
	
if ($_POST['action'] == "result" ) {
	if($_POST['searchstring'] != "" && strlen($_POST['searchstring'])>=4) {		
        $search_loop = array();
        $tpl->loadFile('main', 'search_result.html');	
        $tpl->register('breadcrumb', buildBreadCrumb(array($lang['php_overall_home'] => $config['mainurl'], $lang['title_engine'] => $sess->url('index.php'), $lang['search_search'] => $sess->url("search.php?action=search"), $lang['search_result_search_result'] => '')));                
	
        include_once($_ENGINE['eng_dir']."admin/enginelib/class.bbcode.php");
        $bbcode = new engineBBCode(1,0,1,0,$config['smilieurl']);
	
		$searchstring = str_replace("*","%",$_POST['searchstring']);
		$searchstring = str_replace("_", "\\_", $searchstring);
        $searchstring=preg_replace("/[\/:;'\"\(\)\[\]?!#{}%\-+\\\\]/s","",$searchstring);
        $searchstring=preg_replace("/\s{2,}/"," ",$searchstring);
		$searchstring = preg_replace("/(%){2,}/s", '%', $searchstring);		
		$searchwords = explode(" ",strtolower(addslashes($searchstring)));
		switch($_GET['destination']) {
		case "head":
            $search_col1 = "headline";
            break;
		case "desc":
            $search_col1 = "hometext";
            $search_col3 = "newstext";
            break;
		default:
            $search_col1 = "headline";
            $search_col2 = "hometext";
            $search_col3 = "newstext";
		}
		
		if($search_col1) {
			foreach ($searchwords as $key => $val) {
				$words1[$key] .= " ".$search_col1." LIKE '%".$val."%'";
			}
		}
		
		if($search_col2) {
			foreach ($searchwords as $key => $val) {
				$words2[$key] .= " ".$search_col2." LIKE '%".$val."%'";
			}
		}
		
		if($search_col3) {
			foreach ($searchwords as $key => $val) {
				$words3[$key] .= " ".$search_col3." LIKE '%".$val."%'";
			}
		}					
		
		$condition1 = implode(" ".strtoupper($_POST['connector'])." ", $words1);
		if($words2 != "") $condition2 = " OR ".implode(" ".strtoupper($_POST['connector'])." ", $words2);
		if($words3 != "") $condition3 = " OR ".implode(" ".strtoupper($_POST['connector'])." ", $words3);
		$where_def .= $condition1.$condition2.$condition3;
		
		$bord = "LIMIT ".$_POST['limit'];
		if($_POST['limit'] == '0') $bord = '';
		if(!$_POST['orderby']) $_POST['orderby'] = "dateD";
		$res = 0;
		$result = $db_sql->sql_query("SELECT $news_table.*,$newscat_table.titel FROM $news_table 
										LEFT JOIN $newscat_table ON ( $news_table.catid = $newscat_table.catid )
										WHERE $where_def AND published='1' ORDER BY ".convertOrderBy($news_table, $_POST['orderby'])." $bord");
		while($sresult = $db_sql->fetch_array($result)) {
			$sresult = stripslashes_array($sresult);
			if ($sresult['print_ver'] != "1") {
	   	   		$print_ver = $lang['php_print_ver_no'];
	    	} else {
	   	   		$print_ver = $lang['php_print_ver_yes'];
	    	}
			if ($sresult['comments_allowed'] == 0) $kom = $lang['search_result_comments_not_allowed'];
			if ($sresult['comments_allowed'] != 0) {
				$c = $db_sql->query_array("SELECT COUNT(comid) AS kom_count FROM $newscomment_table WHERE newsid='$sresult[newsid]'");
				$kom = sprintf($lang['search_result_comments'], $c['kom_count']);
            }
			
			if(strlen($sresult['hometext'])>250) $sresult['hometext'] = substr($sresult['hometext'],0,250)."...";
			$search_loop[] = array('comments_info' => $kom,
									'hometext' => $bbcode->rebuildText($sresult['hometext']),
									'headline' => "<a href=\"".$sess->url("news.php?newsid=".$sresult['newsid'])."\">".trim($sresult['headline'])."</a>",
									'date' => aseDate($config['shortdate'],$sresult['newsdate'],1)." ".$lang['php_last_visit2']." ".aseDate($config['timeformat'],$sresult['newsdate']),
									'postcolor' => postCss($res),
									'category_titel' => sprintf($lang['search_result_category'], $sresult['titel']));			
    		$res++;	
		}
		if($res == 0) {
	        rideSite($sess->url('search.php?action=search'), $lang['rec_error44']);
	        exit();		
		} else {		
			$tpl->parseLoop('main', 'search_loop');
		}
		
        $tpl->register(array(
                            'search_result_search_result' => $lang['search_result_search_result'],
                            'search_result_headline_entry' => $lang['search_result_headline_entry'],
                            'search_result_author' => $lang['search_result_author'],
                            'search_result_from' => $lang['search_result_from']));		
	} else {
        rideSite($sess->url('search.php?action=search'), $lang['rec_error43']);
        exit();  
	}
}	

$tpl->register('query', showQueries($develope));
$tpl->register('header', $tpl->pget('header'));

$tpl->register('footer', $tpl->pget('footer'));
$tpl->pprint('main'); 	 	 
?>