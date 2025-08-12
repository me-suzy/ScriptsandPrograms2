<?php
/*
--------------------------------------------------------------------------------
PhpDig 1.6.x
This program is provided under the GNU/GPL license.
See LICENSE file for more informations
All contributors are listed in the CREDITS file provided with this package

PhpDig Website : http://phpdig.toiletoine.net/
Contact email : phpdig@toiletoine.net
Author and main maintainer : Antoine Bajolet (fr) bajolet@toiletoine.net
--------------------------------------------------------------------------------
*/

//===============================================
// do the search and display the results
// can be called in any page
function phpdigSearch($id_connect, $query_string, $option='start', $refine=0,
                       $refine_url='', $lim_start=0, $limite=10, $browse=0,
                       $site=0, $path='', $relative_script_path = '.', $template='',
                       $templates_links='')
{

$timer = new phpdigTimer('html');
$timer->start('All');

// init variables
settype($maxweight,'integer');
$ignore = '';
$ignore_common = '';
$wheresite = '';
$wherepath = '';
$table_results = '';
$final_result = '';
$search_time = 0;
$strings = array();

$mtime = explode(' ',microtime());
$start_time = $mtime[0]+$mtime[1];

$timer->start('All backend');
$timer->start('parsing strings');

if (!$option) {
     $option = SEARCH_DEFAULT_MODE;
}
if (!in_array($option,array('start','any','exact'))) {
     return 0;
}
// the query was filled
if ($query_string) {
$common_words = phpdigComWords("$relative_script_path/includes/common_words.txt");

$like_start = array( "start" => "",
                     "any" => "%",
                     "exact" => ""
                     );
$like_end = array( "start" => "%",
                     "any" => "%",
                     "exact" => ""
                     );
$like_operator = array( "start" => "like",
                     "any" => "like",
                     "exact" => "="
                     );

if ($refine) {
     $query_string = urldecode($query_string);
     $wheresite = "AND spider.site_id = $site ";
     if ($path) {
          $wherepath = "AND spider.path like '$path' ";
     }
     $refine_url = "&refine=1&site=$site&path=$path";
}

// query string was passed by url
if ($browse) {
     $query_string = urldecode($query_string);
}

if ($limite) {
     settype ($limite,"integer");
}
else {
    $limite = SEARCH_DEFAULT_LIMIT;
}

settype ($lim_start,"integer");
if ($lim_start < 0) {
     $lim_start = 0;
}

$n_words = count(explode(" ",$query_string));
$ncrit = 0;
$tin = "0";

$query_to_parse = $query_string;
$query_to_parse = str_replace('"','',$query_to_parse); // avoid '%' in the query
$query_to_parse = str_replace('%','\%',$query_to_parse); // avoid '%' in the query
$query_to_parse = phpdigStripAccents(strtolower(ereg_replace("[\"']+"," ",$query_to_parse))); //made all lowercase
//$query_to_parse = ereg_replace("([^ ])-([^ ])","\\1 \\2",$query_to_parse); // no '-'
$query_to_parse = str_replace('_','\_',$query_to_parse); // avoid _ in the query
$query_to_parse = trim(ereg_replace(" +"," ",$query_to_parse)); // no more than 1 blank

$test_short = $query_to_parse;

while (ereg(' ([^ ]{1,'.SMALL_WORDS_SIZE.'}) | ([^ ]{1,'.SMALL_WORDS_SIZE.'})$|^([^ ]{1,'.SMALL_WORDS_SIZE.'}) ',$test_short,$regs)) {
     for ($n=1; $n <=3; $n++) {
        if ($regs[$n]) {
            $ignore .= "\"".$regs[$n]."\", ";
            $test_short = trim(str_replace($regs[$n],"",$test_short));
        }
     }
}
if ($ignore) {
    $ignore_message = $ignore.' '.phpdigMsg('w_short');
}

while (ereg("(-)?([^ ]{".(SMALL_WORDS_SIZE+1).",}).*",$query_to_parse,$regs)) {

        $query_to_parse = trim(str_replace($regs[2],"",$query_to_parse));
        if (!isset($common_words[$regs[2]])) {
             $spider_in = "";
             if ($regs[1] == '-') {
                 $exclude[$ncrit] = $regs[2];
             }
             else {
                 $strings[$ncrit] = $regs[2];
             }

             $kconds[$ncrit] = '';
             if ($option != 'any') {
                 $kconds[$ncrit] .= " AND k.twoletters = '".substr(str_replace('\\','',$regs[2]),0,2)."' ";
             }
             $kconds[$ncrit] .= " AND k.keyword ".$like_operator[$option]." '".$like_start[$option].$regs[2].$like_end[$option]."' ";
             $ncrit++;
        }
        else {
            $ignore_common .= "\"".$regs[2]."\", ";
        }
}

if ($ignore_common) {
    $ignore_commess = $ignore_common.' '.phpdigMsg('w_common');
}

$timer->stop('parsing strings');

if ($ncrit && is_array($strings)) {
     $query = "SET OPTION SQL_BIG_SELECTS = 1";
     mysql_query($query,$id_connect);

     for ($n = 0; $n < $ncrit; $n++) {
           $timer->start('spider queries');

           $query = "SELECT spider.spider_id,sum(weight) as weight, spider.site_id
           FROM ".PHPDIG_DB_PREFIX."keywords as k,".PHPDIG_DB_PREFIX."engine as engine, ".PHPDIG_DB_PREFIX."spider as spider
           WHERE engine.key_id = k.key_id
           ".$kconds[$n]."
           AND engine.spider_id = spider.spider_id $wheresite $wherepath
           GROUP BY spider.spider_id,spider.site_id ";

           $result = mysql_query($query,$id_connect);
           $num_res_temp = mysql_num_rows($result);

           $timer->stop('spider queries');
           $timer->start('spider fills');

           if ($num_res_temp > 0) {
               if (!isset($exclude[$n])) {
               $num_res[$n] = $num_res_temp;
                    while (list($spider_id,$weight) = mysql_fetch_array($result)) {
                         $s_weight[$n][$spider_id] = $weight;
                    }
               }
               else {
               $num_exclude[$n] = $num_res_temp;
                     while (list($spider_id,$weight) = mysql_fetch_array($result)) {
                            $s_exclude[$n][$spider_id] = 1;
                     }
               mysql_free_result($result);
               }
           }
           elseif (!isset($exclude[$n])) {
                   $num_res[$n] = 0;
                   $s_weight[$n][0] = 0;
          }
          $timer->stop('spider fills');
     }

     $timer->start('reorder results');

     if (is_array($num_res)) {
           asort ($num_res);
           list($id_most) = each($num_res);
           reset ($s_weight[$id_most]);
           while (list($spider_id,$weight) = each($s_weight[$id_most]))  {
                  $weight_tot = 1;
                  reset ($num_res);
                  while(list($n) = each($num_res)) {
                        settype($s_weight[$n][$spider_id],'integer');
                        $weight_tot *= sqrt($s_weight[$n][$spider_id]);
                  }
                  if ($weight_tot > 0) {
                       $final_result[$spider_id]=$weight_tot;
                  }
           }
     }

    if (isset($num_exclude) && is_array($num_exclude)) {
           while (list($id) = each($num_exclude)) {
                  while(list($spider_id) = each($s_exclude[$id])) {
                        unset($final_result[$spider_id]);
                  }
           }
    }

    $timer->stop('reorder results');

}

$timer->stop('All backend');
$timer->start('All display');

if (is_array($final_result)) {
    arsort($final_result);
    $n_start = $lim_start+1;
    $num_tot = count($final_result);
    if ($n_start+$limite-1 < $num_tot) {
           $n_end = ($lim_start+$limite);
           $more_results = 1;
    }
    else {
          $n_end = $num_tot;
          $more_results = 0;
    }

    // ereg for text snippets and highlighting
    $reg_strings = str_replace('@#@','|',phpdigPregQuotes(str_replace('\\','',implode('@#@',$strings))));
    $stop_regs = "[][(){}[:blank:]=&?!&#%\$£*@+%:;,'\"]";
    switch($option) {
        case 'any':
        $reg_strings = "()($reg_strings)()";
        break;
        case 'exact':
        $reg_strings = "($stop_regs{1}|^)($reg_strings)($stop_regs{1}|\$)";
        break;
        default:
        $reg_strings = "($stop_regs{1}|^)($reg_strings)()";
    }

    $timer->start('Result table');

    //fill the results table
    reset($final_result);
    for ($n = 1; $n <= $n_end; $n++) {
        list($spider_id,$s_weight) = each($final_result);
        if (!$maxweight) {
              $maxweight = $s_weight;
        }
        if ($n >= $n_start) {
             $timer->start('Display queries');

             $query = "SELECT sites.site_url, sites.port, spider.path,spider.file,spider.first_words,sites.site_id,spider.spider_id,spider.last_modified,spider.md5 "
                      ."FROM ".PHPDIG_DB_PREFIX."spider AS spider, ".PHPDIG_DB_PREFIX."sites AS sites "
                      ."WHERE spider.spider_id=$spider_id AND sites.site_id = spider.site_id";
             $result = mysql_query($query,$id_connect);
             $content = mysql_fetch_array($result,MYSQL_ASSOC);
             mysql_free_result($result);
             if ($content['port']) {
                 $content['site_url'] = ereg_replace('/$',':'.$content['port'].'/',$content['site_url']);
             }
             $weight = sprintf ("%01.2f", (100*$s_weight)/$maxweight);
             $url = eregi_replace("([a-z0-9])[/]+","\\1/",$content['site_url'].$content['path'].$content['file']);
             $l_site = "<a class='phpdig' href='".SEARCH_PAGE."?refine=1&amp;query_string=".urlencode($query_string)."&amp;site=".$content['site_id']."&amp;limite=$limite&amp;option=$option'>".$content['site_url']."</a>";
             if ($content['path']) {
                  $l_path = ", ".phpdigMsg('this_path')." : <a class='phpdig' href='".SEARCH_PAGE."?refine=1&amp;query_string=".urlencode($query_string)."&amp;site=".$content['site_id']."&amp;path=".$content['path']."&amp;limite=$limite&amp;option=$option' >".$content['path']."</a>";
             }
             else {
                  $l_path="";
             }

             $first_words = str_replace('<','&lt;',str_replace('>','&gt;',$content['first_words']));

             $timer->stop('Display queries');
             $timer->start('Extracts');

             $extract = "";
             //Try to retrieve matching lines if the content-text is set to 1
             if (CONTENT_TEXT == 1 && DISPLAY_SNIPPETS) {
                 $content_file = $relative_script_path.'/'.TEXT_CONTENT_PATH.$content['spider_id'].'.txt';
                 if (is_file($content_file)) {
                     $num_extracts = 0;
                     $f_handler = fopen($content_file,'r');
                     while($num_extracts < DISPLAY_SNIPPETS_NUM && $extract_content = fgets($f_handler,1024)) {
                           if(eregi($reg_strings,$extract_content)) {
                              $extract .= ' ...'.phpdigHighlight($reg_strings,str_replace('<','&lt;',str_replace('>','&gt;',trim($extract_content))),2).'... ';
                              $num_extracts++;
                           }
                     }
                     fclose($f_handler);
                 }
             }

             list($title,$text) = explode("\n",$first_words);

             $title = phpdigHighlight($reg_strings,$title,2);

             $timer->stop('Extracts');

             $table_results[$n] = array (
                    'weight' => $weight,
                    'img_tag' => '<img border="0" src="'.WEIGHT_IMGSRC.'" width="'.ceil(WEIGHT_WIDTH*$weight/100).'" height="'.WEIGHT_HEIGHT.'" alt="" />',
                    'page_link' => "<a class=\"phpdig\" href=\"".$url."\" target=\"".LINK_TARGET."\" >$title</a>",
                    'limit_links' => phpdigMsg('limit_to')." ".$l_site.$l_path,
                    'filesize' => sprintf('%.1f',(ereg_replace('.*_([0-9]+)$','\1',$content['md5']))/1024),
                    'update_date' => ereg_replace('^([0-9]{4})([0-9]{2})([0-9]{2}).*',PHPDIG_DATE_FORMAT,$content['last_modified']),
                    'complete_path' => $url,
                    'link_title' => $title
                    );

             $table_results[$n]['text'] = '';
             if (DISPLAY_SUMMARY) {
                 $table_results[$n]['text'] = phpdigHighlight($reg_strings,ereg_replace('(@@@.*)','',wordwrap($text, SUMMARY_DISPLAY_LENGTH, '@@@')));
             }
             if (DISPLAY_SUMMARY && DISPLAY_SNIPPETS) {
                 $table_results[$n]['text'] .= "\n<br/><br/>\n";
             }
             if (DISPLAY_SNIPPETS) {
                 if ($extract) {
                     $table_results[$n]['text'] .= $extract;
                 }
                 else if (!$table_results[$n]['text']){
                     $table_results[$n]['text'] = phpdigHighlight($reg_strings,ereg_replace('(@@@.*)','',wordwrap($text, SUMMARY_DISPLAY_LENGTH, '@@@')));
                 }
             }
        }
    }

    $timer->stop('Result table');
    $timer->start('Final strings');

    $nav_bar = '';
    $pages_bar = '';
    $url_bar = SEARCH_PAGE."?browse=1&amp;query_string=".urlencode($query_string)."$refine_url&amp;limite=$limite&amp;option=$option&amp;lim_start=";
    if ($lim_start > 0) {
        $previous_link = $url_bar.($lim_start-$limite);
        $nav_bar .= "<a class=\"phpdig\" href=\"$previous_link\" >&lt;&lt;".phpdigMsg('previous')."</a>&nbsp;&nbsp;&nbsp; \n";
    }
    $tot_pages = ceil($num_tot/$limite);
    $actual_page = $lim_start/$limite + 1;
    $page_inf = max(1,$actual_page - 5);
    $page_sup = min($tot_pages,max($actual_page+5,10));
    for ($page = $page_inf; $page <= $page_sup; $page++) {
      if ($page == $actual_page) {
           $nav_bar .= " <span class=\"phpdigHighlight\">$page</span> \n";
           $pages_bar .= " <span class=\"phpdigHighlight\">$page</span> \n";
           $link_actual =  $url_bar.(($page-1)*$limite);
      }
      else {
          $nav_bar .= " <a class=\"phpdig\" href=\"".$url_bar.(($page-1)*$limite)."\" >$page</a> \n";
          $pages_bar .= " <a class=\"phpdig\" href=\"".$url_bar.(($page-1)*$limite)."\" >$page</a> \n";
      }
    }

    if ($more_results == 1) {
        $next_link = $url_bar.($lim_start+$limite);
        $nav_bar .= " &nbsp;&nbsp;&nbsp;<a class=\"phpdig\" href=\"$next_link\" >".phpdigMsg('next')."&gt;&gt;</a>\n";
    }

    $mtime = explode(' ',microtime());
    $search_time = sprintf('%01.2f',$mtime[0]+$mtime[1]-$start_time);
    $result_message = stripslashes(ucfirst(phpdigMsg('results'))." $n_start-$n_end, $num_tot ".phpdigMsg('total').", ".phpdigMsg('on')." \"$query_string\" ($search_time ".phpdigMsg('seconds').")");

    $timer->stop('Final strings');
}
else {
    $num_tot = 0;
    $result_message = phpdigMsg('noresults');
}

if (isset($tempresult)) {
    mysql_free_result($tempresult);
}

$title_message = phpdigMsg('s_results');
}
else {
   $title_message = 'PhpDig '.PHPDIG_VERSION;
   $result_message = phpdigMsg('no_query');
}

$timer->start('Logs');
if (PHPDIG_LOGS == true && !$browse && !$refine) {
   if (!isset($exclude)) {
        $exclude = array();
   }
   if (is_array($final_result)) {
       phpdigAddLog ($id_connect,$option,$strings,$exclude,count($final_result),$search_time);
   }
   else {
       phpdigAddLog ($id_connect,$option,$strings,$exclude,0,$search_time);
   }
}
$timer->stop('Logs');

$timer->start('Template parsing');

if ($template == 'array' || is_file($template)) {
    $phpdig_version = PHPDIG_VERSION;
    $t_mstrings = compact('title_message','phpdig_version','result_message','nav_bar','ignore_message','ignore_commess','pages_bar','previous_link','next_link','templates_links');
    $t_fstrings = phpdigMakeForm($query_string,$option,$limite,SEARCH_PAGE,$site,$path,'template',$template);
    if ($template == 'array') {
        return array_merge($t_mstrings,$t_fstrings,array('results'=>$table_results));
    }
    else {
        $t_strings = array_merge($t_mstrings,$t_fstrings);
        phpdigParseTemplate($template,$t_strings,$table_results);
    }
}
else {
?>
<?php include $relative_script_path.'/libs/htmlheader.php' ?>
<head>
<title><?php print $title_message ?></title>
<?php include $relative_script_path.'/libs/htmlmetas.php' ?>
<style>
.phpdigHighlight {color:<?php print HIGHLIGHT_COLOR ?>;
                 background-color:<?php print HIGHLIGHT_BACKGROUND ?>;
                 font-weight:bold;
                 }
.phpdigMessage {padding:1px;background-color:#002288;color:white;}
</style>
</head>
<body bgcolor="white">
<div align="center">
<img src="phpdig_logo_2.png" width="200" height="114" alt="phpdig <?php print PHPDIG_VERSION ?>" border="0" />
<br />
<?php
phpdigMakeForm($query_string,$option,$limite,SEARCH_PAGE,$site,$path);
?>
<h3><span class="phpdigMsg"><?php print $result_message ?></span>
<br /><span class="phpdigAlert"><?php print $ignore_message ?></span>
<br /><span class="phpdigAlert"><?php print $ignore_commess ?></span>
</h3>
</div>
<?php
if (is_array($table_results)) {
       while (list($n,$t_result) = each($table_results)) {
             print "<p style='background-color:#CCDDFF;'>\n";
             print "<b>$n. <font style='font-size:10;'>[".$t_result['weight']." %]</font>&nbsp;&nbsp;".$t_result['page_link']."</b>\n<br />\n";
             print "<font style='font-size:10;background-color:#BBCCEE;'>".$t_result['limit_links']."</font>\n<br />\n";
             print "</p>\n";
             print "<blockquote style='background-color:#EEEEEE;font-size:10;'>\n";
             print $t_result['text'];
             print "</blockquote>\n";
       }
}
print "<p style='text-align:center;background-color:#CCDDFF;font-weight:bold'>\n";
print $nav_bar;
print "</p>\n";
?>
<hr />
<div align="center">
<?php
if ($query_string) {
    phpdigMakeForm($query_string,$option,$limite,SEARCH_PAGE,$site,$path);
}
?>
</div>
<div align='center'>
<a href='http://phpdig.toiletoine.net/' target='_blank'><img src='phpdig_powered_2.png' width='88' height='28' border='0' alt='PhpDig powered' /></a> &nbsp;
</div>
</body>
</html>
<?php
}
$timer->stop('Template parsing');
$timer->stop('All display');
$timer->stop('All');
//$timer->display();
}
?>
