<?php
#####################################################
#                   Go2! Search                     #
#####################################################
#                                                   #
#                simple_search.php                 #
#                                                   #
#####################################################
#       Copyright © 2001 W. Dustin Alligood         #
#####################################################

require("./text.php");
require("./config.php");

########## Change Nothing Below This Line ###########

mysql_connect($mysql_host,$mysql_user,$mysql_password) or die ("Could not connect");
mysql_select_db($mysql_database);
$header=open("header.txt");
$footer=open("footer.txt");
$cats=split("\*\*\*",$categories);
sort($cats);
if($within!=""){
	$t.=$within;
}
if($t!=""){
	$ids=Array();
	$searchkeys=Array();
	$mysql_categories=Array();
	$emails=Array();
	$hits=Array();
	$prioritys=Array();
	$ratings=Array();
	$votes=Array();
	$homepages=Array();
	$urls=Array();
	$titles=Array();
	$descriptions=Array();
	$keywords=Array();
	if(($search_style==$button['quick'])||(substr($t,0,strlen($keyword_command))==$keyword_command)){
		if(substr($t,0,strlen($keyword_command))!=$keyword_command){
			$t=$keyword_command.$t;
		}
		$searched_terms=$t;
		$keyword=substr($t,strlen($keyword_command),strlen($t));
		$query="SELECT * FROM $mysql_table WHERE searchkey='$keyword' ORDER BY id";
		$result=mysql_query($query);
		$num_rows=mysql_numrows($result);
		$i=0;
		$valid_number=0;
		$raw_mixed_results=Array();
		while ($i<$num_rows){
			if(mysql_result($result,$i,"searchkey")==$keyword){
				$id=mysql_result($result,$i,"id");
				$category=mysql_result($result,$i,"category");
				$searchkey=mysql_result($result,$i,"searchkey");
				$email=mysql_result($result,$i,"email");
				$hits=mysql_result($result,$i,"hits");
				$priority=mysql_result($result,$i,"priority");
				$rating=mysql_result($result,$i,"rating");
				$votes=mysql_result($result,$i,"votes");
				$homepage=mysql_result($result,$i,"homepage");
				$url=mysql_result($result,$i,"url");
				$title=mysql_result($result,$i,"title");
				$description=mysql_result($result,$i,"description");
				$keywords=mysql_result($result,$i,"keywords");
				@$rating=intval($rating/$votes);
				$cache="<a href=\"".$cache_script."?c=".$id.".html&b=".urlencode($url)."\" target=cache>".$text['view_cache']."</a>";
				preg_match("/^(http:\/\/)?([^\/]+)/i",$homepage,$raw_homepage_host);
				$homepage_host=$raw_homepage_host[2];
				preg_match("/^(http:\/\/)?([^\/]+)/i",$url,$raw_url_host);
				$url_host=$raw_url_host[2];
				$test_homepage=$homepage;
				if(substr($test_homepage,strlen($test_homepage)-1,strlen($test_homepage))=="/"){
					$test_homepage=substr($test_homepage,0,strlen($test_homepage)-1);
				}
				$test_homepage.="/";
				$test_url=$url;
				if(substr($test_url,strlen($test_url)-1,strlen($test_url))=="/"){
					$test_url=substr($test_url,0,strlen($test_url)-1);
				}
				$test_url.="/";
				if($test_homepage==$test_url){
					$raw_mixed_results[]=$homepage."::".$url."::0::".$title."::".$description."::".$keywords."::".$rating."::".$hits."::".$email."::".$category."::".$searchkey."::".$cache."::".$id;
					$valid_number++;
				}
				if($homepage_host==$url_host){
					$bk_entry=$homepage."::".$url."::0::".$title."::".$description."::".$keywords."::".$rating."::".$hits."::".$email."::".$category."::".$searchkey."::".$cache."::".$id;
				}
			}
			++$i;
		}
		if(count($raw_mixed_results)==0){
			$raw_mixed_results[]=$bk_entry;
			$valid_number++;		
		}
		$raw_mixed_results=array_reverse($raw_mixed_results);
	}elseif(substr($t,0,strlen($category_command))==$category_command){
		$searched_terms=$t;
		$category=substr($t,strlen($category_command),strlen($t));
		$query="SELECT * FROM $mysql_table WHERE category='$category' ORDER BY id";
		$result=mysql_query($query);
		$num_rows=mysql_numrows($result);
		$i=0;
		$valid_number=0;
		$raw_mixed_results=Array();
		while ($i<$num_rows){
			if(mysql_result($result,$i,"category")==$category){
				$id=mysql_result($result,$i,"id");
				$searchkey=mysql_result($result,$i,"searchkey");
				$email=mysql_result($result,$i,"email");
				$hits=mysql_result($result,$i,"hits");
				$priority=mysql_result($result,$i,"priority");
				$rating=mysql_result($result,$i,"rating");
				$votes=mysql_result($result,$i,"votes");
				$homepage=mysql_result($result,$i,"homepage");
				$url=mysql_result($result,$i,"url");
				$title=mysql_result($result,$i,"title");
				$description=mysql_result($result,$i,"description");
				$keyword=mysql_result($result,$i,"keywords");
				@$rating=intval($rating/$votes);
				$cache="<a href=\"".$cache_script."?c=".$id.".html&b=".urlencode($url)."\" target=cache>".$text['view_cache']."</a>";
				$raw_mixed_results[]=$homepage."::".$url."::0::".$title."::".$description."::".$keywords."::".$rating."::".$hits."::".$email."::".$category."::".$searchkey."::".$cache."::".$id;
				$valid_number++;
			}
			++$i;
		}
		$raw_mixed_results=array_reverse($raw_mixed_results);
	}else{
		if(strlen($t)<3){
			$template=open("too_short.txt");
			$template=preg_replace("/<!--#start_form-->/", "<form action=\"".$search_script."\" method=post>", $template);
			$template=preg_replace("/<!--#end_form-->/", "</form>", $template);
			$template=preg_replace("/<!--#small_terms_box-->/", "<input type=text name=t size=10>", $template);
			$template=preg_replace("/<!--#terms_box-->/", "<input type=text name=t size=50>", $template);
			$template=preg_replace("/<!--#large_terms_box-->/", "<input type=text name=t size=100>", $template);
			$template=preg_replace("/<!--#search_button-->/", "<input type=submit name=\"search_style\" value=\"".$button['search']."\">", $template);
			$template=preg_replace("/<!--#quick_button-->/", "<input type=submit name=\"search_style\" value=\"".$button['quick']."\">", $template);
			$template=preg_replace("/<!--#header-->/", $header, $template);
			$template=preg_replace("/<!--#footer-->/", $footer, $template);
			$template=preg_replace("/<!--#title-->/", $page['title'].$page['separator'].$page['search_error'], $template);
			print $template;
			exit;
		}
		$terms=" ".stripslashes($t)." ";
		preg_match_all("/\((.+?)\)/",$terms,$outs);
		foreach($outs[count($outs)-1] as $out){
			$orginal=$out;
			$out=preg_replace("/\s/","_",$out);
			$terms=preg_replace("/".$orginal."/",$out,$terms);
		}
		$terms=preg_replace("/ AND /i"," +",$terms);
		$terms=preg_replace("/ NOT /i"," -",$terms);
		$terms=preg_replace("/ OR /i","|",$terms);
		$terms=preg_replace("/ NOR /i","|",$terms);
		$terms=split(" ",$terms);
		$and=Array();
		$not=Array();
		$searched_terms="";
		$mysql_search="";
		$common_word_bits=Array();
		$common_word_bits=split(", ",$common_words);
		foreach($terms as $term){
			$term=stripslashes($term);
			if((substr($term,0,1)=="\"")&&(substr($term,strlen($term)-1,strlen($term))=="\"")){
				$term="(".substr($term,1,strlen($term)-2).")";
			}
			if(($term!="")&&($term!=" ")){
				$stop=0;
				foreach($common_word_bits as $common_word){
					if(strtolower($common_word)==strtolower($term)){
						$stop=1;
					}
				}
				if($stop==1){
					if($common_words_used!=""){
						$common_words_used.=", ";
					}
					$common_words_used.=$term;
				}else{
					if(preg_match("/\((.+?)\)/",$term)){
						$term=preg_replace("/_/"," ",$term);
					}
					if(substr($term,0,1)=="+"){
						$term=substr($term,1,strlen($term));
						$and[]=$term;
						$searched_terms.=" +".$term;
						$term=addslashes($term);
						$mysql_title_search.="title REGEXP \"".$term."\" AND ";
						$mysql_keywords_search.="keywords REGEXP \"".$term."\" AND ";
						$mysql_description_search.="description REGEXP \"".$term."\" AND ";
						$mysql_url_search.="url REGEXP \"".$term."\" AND ";
						$last_type="+";
						$n++;
					}elseif(substr($term,0,1)=="-"){
						$term=substr($term,1,strlen($term));
						$not[]=$term;
						$searched_terms.=" -".$term;
						$term=addslashes($term);
						$mysql_title_search.="title REGEXP \"[^".$term."]\" AND ";
						$mysql_keywords_search.="keywords REGEXP \"[^".$term."]\" AND ";
						$mysql_description_search.="description REGEXP \"[^".$term."]\" AND ";
						$mysql_url_search.="url REGEXP \"[^".$term."]\" AND ";
						$last_type="-";
						$n++;
					}else{
						$and[]=$term;
						$searched_terms.=" +".$term;
						$term=addslashes($term);
						$mysql_title_search.="title REGEXP \"".$term."\" AND ";
						$mysql_keywords_search.="keywords REGEXP \"".$term."\" AND ";
						$mysql_description_search.="description REGEXP \"".$term."\" AND ";
						$mysql_url_search.="url REGEXP \"".$term."\" AND ";
						$last_type="+";
						$n++;
					}
				}
			}
		}
		if($and[0]==""){
			$template=open("no_ands.txt");
			$template=preg_replace("/<!--#start_form-->/", "<form action=\"".$search_script."\" method=post>", $template);
			$template=preg_replace("/<!--#end_form-->/", "</form>", $template);
			$template=preg_replace("/<!--#small_terms_box-->/", "<input type=text name=t size=10>", $template);
			$template=preg_replace("/<!--#terms_box-->/", "<input type=text name=t size=50>", $template);
			$template=preg_replace("/<!--#large_terms_box-->/", "<input type=text name=t size=100>", $template);
			$template=preg_replace("/<!--#search_button-->/", "<input type=submit name=\"search_style\" value=\"".$button['search']."\">", $template);
			$template=preg_replace("/<!--#quick_button-->/", "<input type=submit name=\"search_style\" value=\"".$button['quick']."\">", $template);
			$template=preg_replace("/<!--#header-->/", $header, $template);
			$template=preg_replace("/<!--#footer-->/", $footer, $template);
			$template=preg_replace("/<!--#title-->/", $page['title'].$page['separator'].$page['search_error'], $template);
			print $template;
			exit;
		}
		$mysql_title_search=substr($mysql_title_search,0,strlen($mysql_title_search)-5);
		$mysql_keywords_search=substr($mysql_keywords_search,0,strlen($mysql_keywords_search)-5);
		$mysql_description_search=substr($mysql_description_search,0,strlen($mysql_description_search)-5);
		$mysql_url_search=substr($mysql_url_search,0,strlen($mysql_url_search)-5);
		$query="SELECT * FROM $mysql_table WHERE $mysql_title_search OR $mysql_keywords_search OR $mysql_description_search OR $mysql_url_search ORDER BY id";
		$result=mysql_query($query);
		$num_rows=mysql_numrows($result);
		$i=0;
		if($num_rows>$max_entries){
			$num_rows=$max_entries;
		}
		while($i<$num_rows){
			$ids[$i+3]=mysql_result($result,$i,"id");
			$searchkeys[$i+3]=mysql_result($result,$i,"searchkey");
			$mysql_categories[$i+3]=mysql_result($result,$i,"category");
			$emails[$i+3]=mysql_result($result,$i,"email");
			$hits[$i+3]=mysql_result($result,$i,"hits");
			$prioritys[$i+3]=mysql_result($result,$i,"priority");
			$ratings[$i+3]=mysql_result($result,$i,"rating");
			$votes[$i+3]=mysql_result($result,$i,"votes");
			$homepages[$i+3]=mysql_result($result,$i,"homepage");
			$urls[$i+3]=mysql_result($result,$i,"url");
			$titles[$i+3]=mysql_result($result,$i,"title");
			$descriptions[$i+3]=mysql_result($result,$i,"description");
			$keywords[$i+3]=mysql_result($result,$i,"keywords");
			++$i;
		}
		$number=0;
		$top_matches=0;
		$raw_mixed_results=Array();
		$valid_number=0;
		$id=0;
		while($id<count($ids)){
			$show=1;
			$search=$titles[$id].$urls[$id].$keywords[$id].$descriptions[$id];
			$total_term_count=0;
			foreach($and as $a){
				if(preg_match("/".$a."/".$case,$search)){
					$term_count=split($a,$search);
					$total_term_count=count($term_count)+$total_term_count;
					$number=count($term_count)-1+$prioritys[$id]+$number;
				}else{
					$show=0;
				}
			}
			foreach($not as $n){
				if(preg_match("/".$n."/".$case,$search)){
					$show=0;
				}
			}
			if($show==1){
				$total_term_count=$number;
				if($total_term_count>$top_matches){
					$top_matches=$total_term_count;
				}
				$total_term_count=str_pd($total_term_count,10);
				$entry_url=$urls[$id];
				$entry_title=$titles[$id];
				$entry_desc=$descriptions[$id];
				$entry_keys=$keywords[$id];
				@$entry_rating=intval($ratings[$id]/$votes[$id]);
				$entry_hits=$hits[$id];
				$entry_email=$emails[$id];
				$entry_cat=$mysql_categories[$id];
				$entry_key=$searchkeys[$id];
				$entry_home=$homepages[$id];
				$entry_cache="<a href=\"".$cache_script."?c=".$id.".html&b=".urlencode($entry_url)."\" target=cache>".$text['view_cache']."</a>";
				$stop=0;
				if(($fstr!="")&&($fstr!=0)&&($entry_rating<5)){
					$stop=1;
				}
				if(($fltr!="")&&($fltr!=0)&&($naughty!="")){
					$naughty_bits=split(", ",$naughty_words);
					foreach($naughty_bits as $naughty_word){
						if(preg_match("/(".strtolower($naughty_word).")/",strtolower($term))){
							$stop=1;
						}
					}
				}
				preg_match("/^(http:\/\/)?([^\/]+)/i",$entry_url,$raw_url_host);
				$url_host=$raw_url_host[2];
				if($tld!=""){
					$test_tld=".".strtolower($tld);
					if(substr($url_host,strlen($url_host)-strlen($test_tld),strlen($url_host))!=$test_tld){
						$stop=1;
					}
				}
				if($stop==0){
					$raw_mixed_results[]=$entry_home."::".$entry_url."::".$total_term_count."::".$entry_title."::".$entry_desc."::".$entry_keys."::".$entry_rating."::".$entry_hits."::".$entry_email."::".$entry_cat."::".$entry_key."::".$entry_cache."::".$id;
					$valid_number++;
				}
			}
			$id++;
		}
	}
	sort($raw_mixed_results);
	reset($raw_mixed_results);
	$indent=0;
	$misc=0;
	$raw_sorted_results=Array();
	foreach($raw_mixed_results as $raw_mixed_result){
		if($indent=1){
			$indent=2;
		}
		list($raw_homepage,$raw_url,$raw_matches,$raw_title,$raw_desc,$raw_keys,$raw_rating,$raw_hits,$raw_email,$raw_cat,$raw_key,$raw_cache,$raw_id)=split("::",$raw_mixed_result);
		$raw_matches=str_pd($raw_matches,500);
		preg_match("/^(http:\/\/)?([^\/]+)/i",$raw_homepage,$raw_homepage_host);
		$homepage_host=$raw_homepage_host[2];
		preg_match("/^(http:\/\/)?([^\/]+)/i",$raw_url,$raw_url_host);
		$url_host=$raw_url_host[2];
		if(substr($raw_homepage,strlen($raw_homepage)-1,strlen($raw_homepage))=="/"){
			$test_homepage=substr($raw_homepage,0,strlen($raw_homepage)-1);
		}else{
			$test_homepage=$raw_homepage;	
		}
		$test_homepage=preg_replace("/www\./i","",$test_homepage);
		if(substr($raw_url,strlen($raw_url)-1,strlen($raw_url))=="/"){
			$test_url=substr($raw_url,0,strlen($raw_url)-1);
		}else{
			$test_url=$raw_url;
		}
		$test_url=preg_replace("/www\./i","",$test_url);
		if($test_homepage==$test_url){
			$indent=1;
			$total_matches=str_pd($raw_matches,500);
			$raw_sorted_results[$homepage_host]=$raw_matches."::".$raw_homepage."::".$raw_url."::".$raw_matches."::".$raw_title."::".$raw_desc."::".$raw_keys."::".$raw_rating."::".$raw_hits."::".$raw_email."::".$raw_cat."::".$raw_key."::".$raw_cache."::".$raw_id;
		}elseif($homepage_host!=$url_host){
			$indent=0;
		}
		if($indent==2){
			list($total_matches,$home_home,$home_url,$home_matches,$home_title,$home_desc,$home_keys,$home_rating,$home_hits,$home_email,$home_cat,$home_key,$home_cache,$home_id)=split("::",$raw_sorted_results[$homepage_host]);
			$total_matches=$total_matches+$raw_matches;
			$total_matches=str_pd($total_matches,500);
			$home_matches=str_pd($home_matches,500);
			if($home_url!=""){
				$raw_sorted_results[$homepage_host]=$total_matches."::".$home_home."::".$home_url."::".$home_matches."::".$home_title."::".$home_desc."::".$home_keys."::".$home_rating."::".$home_hits."::".$home_email."::".$home_cat."::".$home_key."::".$home_cache."::".$home_id;
			}else{
				$raw_sorted_results[$homepage_host]=$total_matches."::".$raw_homepage."::***none***::".$total_matches."::0::0::0::0::0::0::0::0::0::0";
			}
			$raw_sorted_results_thred[$homepage_host][]="||".$raw_matches."::".$raw_homepage."::".$raw_url."::".$raw_matches."::".$raw_title."::".$raw_desc."::".$raw_keys."::".$raw_rating."::".$raw_hits."::".$raw_email."::".$raw_cat."::".$raw_key."::".$raw_cache."::".$raw_id;
		}elseif($indent==0){
			$misc++;
			$raw_sorted_results['misc'.$misc]=$raw_matches."::misc".$misc."::".$raw_url."::".$raw_matches."::".$raw_title."::".$raw_desc."::".$raw_keys."::".$raw_rating."::".$raw_hits."::".$raw_email."::".$raw_cat."::".$raw_key."::".$raw_cache."::".$raw_id;
		}
	}
	$final_results=Array();
	arsort($raw_sorted_results);
	if($category!=""){
		$raw_sorted_results=array_reverse($raw_sorted_results);
	}
	reset($raw_sorted_results);
	foreach($raw_sorted_results as $raw_sorted_result){
		list($total_matches,$raw_homepage,$raw_url,$raw_matches,$raw_title,$raw_desc,$raw_keys,$raw_rating,$raw_hits,$raw_email,$raw_cat,$raw_key,$raw_cache,$raw_id)=split("::",$raw_sorted_result);
		if($raw_url!=""){
			$final_results[]="homepage::".intval($total_matches)."::".$raw_homepage."::".$raw_url."::".intval($raw_matches)."::".$raw_title."::".$raw_desc."::".$raw_keys."::".$raw_rating."::".$raw_hits."::".$raw_email."::".$raw_cat."::".$raw_key."::".$raw_cache."::".$raw_id;
		}
		preg_match("/^(http:\/\/)?([^\/]+)/i",$raw_homepage,$raw_homepage_host);
		$homepage_host=$raw_homepage_host[2];
		if($raw_sorted_results_thred[$homepage_host][0]!=""){
			arsort($raw_sorted_results_thred[$homepage_host]);
			reset($raw_sorted_results_thred[$homepage_host]);
			foreach($raw_sorted_results_thred[$homepage_host] as $raw_thred){
				list($total_matches,$raw_homepage,$raw_url,$raw_matches,$raw_title,$raw_desc,$raw_keys,$raw_rating,$raw_hits,$raw_email,$raw_cat,$raw_key,$raw_cache,$raw_id)=split("::",$raw_thred);
				if($raw_url!=""){
					$final_results[]="indent::".intval($total_matches)."::".$raw_homepage."::".$raw_url."::".intval($raw_matches)."::".$raw_title."::".$raw_desc."::".$raw_keys."::".$raw_rating."::".$raw_hits."::".$raw_email."::".$raw_cat."::".$raw_key."::".$raw_cache."::".$raw_id;
				}
			}
		}
	}
	$file=@fopen($temps."/count.txt","r");
	$temp_total=@fread($file,filesize($temps."/count.txt"));
	@fclose($file);
	$temp_total++;
	if($temp_total>$max_temps){
		$temp_total=0;
	}
	if($file=fopen($temps."/count.txt","w")){
		rewind($file);
		fputs($file,$temp_total);
	}
	$file=fopen($temps."/search".$temp_total.".txt","w");
	foreach($final_results as $entry){
		chop($entry);
		$entry=ereg_replace("(\r\n|\n|\r)"," ",$entry);
		fputs($file,$entry."***end_of_line***");
	}
	@fclose($file);
	$searched_terms=urlencode($searched_terms);
	if($p<3){
		$read_results=$results_script."?f=".$temp_total."&t=".$top_matches."&s=0&p=".$default_per_page."&v=".$valid_number."&m=".$searched_terms."&mt=".$mt."&md=".$md."&mu=".$mu."&cw=".urlencode($common_words_used);
	}else{
		$read_results=$results_script."?f=".$temp_total."&t=".$top_matches."&s=0&p=".$p."&v=".$valid_number."&m=".$searched_terms."&mt=".$mt."&md=".$md."&mu=".$mu."&cw=".urlencode($common_words_used);
	}
	header("Location:".$read_results);
	exit;
}else{
	$category_links=Array();
	foreach($cats as $cat){
		$category_parts=split(">",$cat);
		if(count($category_parts)==1){
			if($sc>0){
				$category_links[]=$new_link_set;
			}
			$new_link_set="<font ".$category_font."><b><a href=\"".$search_script."?t=".urlencode($category_command.$category_parts[0])."\">".$category_parts[0]."</a></b></font><br>";
			$tc=$category_parts[0];
			$sc=0;
		}elseif((count($category_parts)==2)&&($category_parts[0]==$tc)){
			$sc++;
			if($sc==1){
				$new_link_set.="<font ".$subcategory_font."><a href=\"".$search_script."?t=".urlencode($category_command.$category_parts[0].">".$category_parts[1])."\">".$category_parts[1]."</a></font>";
			}elseif(($sc!=1)&&($sc<=$subcat_number)){
				$new_link_set.="<font ".$subcategory_font.">, <a href=\"".$search_script."?t=".urlencode($category_command.$category_parts[0].">".$category_parts[1])."\">".$category_parts[1]."</a></font>";
			}elseif($sc==$subcat_number+1){
				$new_link_set.="<font ".$subcategory_font.">...</font>";
			}
		}
	}
	$category_links[]=$new_link_set;
	$print_categories="<table border=0 cellspacing=5>";
	$col=0;
	foreach($category_links as $category_link){
		if($col==0){
			$print_categories.="<tr><td>".$category_link."</td>";
		}elseif(($col>0)&&($col<$cat_col-1)){
			$print_categories.="<td width=5>&nbsp;</td><td>".$category_link."</td>";
		}elseif($col==$cat_col-1){
			$print_categories.="<td width=5>&nbsp;</td><td>".$category_link."</td></tr>";
			$col=-1;
		}
		$col++;	
	}
	if(substr($print_categories,strlen($print_categories)-5,strlen($print_categories))!="</tr>"){
		$print_categories.="</tr>";
	}
	$print_categories.="</table>";
	$query="SELECT id FROM $mysql_table ORDER BY id";
	$result=mysql_query($query);
	$total_entries=mysql_numrows($result);
	$template=open("simple_search.txt");
	$world_news="<p><center><font ".$news_font."><b>World News</b><br>From <a href=\"http://www.abcnews.com/\">ABCNEWS.com</a></font></center></p>";
	$fd=@fopen("http://abcnews.go.com/sections/world/", "r");
	$raw_news=@fread($fd,1048576);
	@fclose($fd);
	$news_page=split("\n",$raw_news);
	$world_news.="<table border=0>";
	$ln=0;
	$cn=0;
	while($cn<5){
		$line=$news_page[$ln];
		if(preg_match("/\/wire\/World\//i",$line)){
			$line=preg_replace("/&nbsp;/i"," ",$line);
			preg_match("/<a href=\"(.+?)\"(.+?)>(.+?)<\/a>/i",$line,$out);
			$href=$out[1];
			$headline=$out[3];
			if(($href!='')&&(strlen($headline)>2)&&(!eregi("http:|javascript:",$href))&&(!eregi("<|>",$headline))){
				$world_news.="<tr><td><font ".$news_font."></font></td><td><font ".$news_font."><a href=\"http://abcnews.com".$href."\" target=news_window>";
				if(strlen($headline)>50){
					$headline=substr($headline,0,50)."...";
				}
				$world_news.=$headline."</a></font></td></tr>";
				$cn++;
			}
		}
		$ln++;
	}
	$world_news.="</table>";
	$template=preg_replace("/<!--#world_news-->/", $world_news, $template);
	$template=preg_replace("/<!--#start_form-->/", "<form action=\"".$search_script."\" method=post>", $template);
	$template=preg_replace("/<!--#end_form-->/", "</form>", $template);
	$template=preg_replace("/<!--#small_terms_box-->/", "<input type=text name=t size=10>", $template);
	$template=preg_replace("/<!--#terms_box-->/", "<input type=text name=t size=50>", $template);
	$template=preg_replace("/<!--#large_terms_box-->/", "<input type=text name=t size=100>", $template);
	$template=preg_replace("/<!--#search_button-->/", "<input type=submit name=\"search_style\" value=\"".$button['search']."\">", $template);
	$template=preg_replace("/<!--#quick_button-->/", "<input type=submit name=\"search_style\" value=\"".$button['quick']."\">", $template);
	$template=preg_replace("/<!--#categories-->/", $print_categories, $template);
	$template=preg_replace("/<!--#power_search_link-->/", "<a href=\"".$power_script."\">".$text['power_search']."</a>", $template);
	$template=preg_replace("/<!--#total_entries-->/", $total_entries."</a>", $template);
	$template=preg_replace("/<!--#header-->/", $header, $template);
	$template=preg_replace("/<!--#footer-->/", $footer, $template);
	$template=preg_replace("/<!--#title-->/", $page['title'].$page['separator'].$page['simple_search'], $template);
	print $template;
}
function open($filename){
	global $templates;
	$fd=@fopen($templates."/".$filename, "r");
	$template=@fread($fd, filesize ($templates."/".$filename));
	@fclose($fd);
	return $template;
}
function str_pd($string,$length){
	while(strlen($string)<$length){
		$string="0".$string;
	}
	return $string; 
}

?>