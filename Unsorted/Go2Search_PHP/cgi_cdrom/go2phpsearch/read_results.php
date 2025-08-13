<?php
#####################################################
#                   Go2! Search                     #
#####################################################
#                                                   #
#                read_results.php                  #
#                                                   #
#####################################################
#       Copyright © 2001 W. Dustin Alligood         #
#####################################################

require("./text.php");
require("./config.php");
require("./ads.php");

########## Change Nothing Below This Line ###########

mysql_connect($mysql_host,$mysql_user,$mysql_password) or die ("Could not connect");
mysql_select_db($mysql_database);
$header=open("header.txt");
$footer=open("footer.txt");
if($mt<=0){
	$mt=$default_mt;
}
if($mu!="hide"){
	if($mu<=0){
		$mu=$default_mu;
	}
}
if($md!="hide"){
	if($md<=0){
		$md=$default_md;
	}
}
$search_file=$f;
$top_matches=$t;
$total_matches=$v;
if($file=fopen($temps."/search".$search_file.".txt","r")){
	$raw_results=@fread($file,filesize($temps."/search".$search_file.".txt"));
}else{
	print "Could Not Access Temp Search File";
	exit;
}
$final_results=split("\*\*\*end_of_line\*\*\*",$raw_results);
$results_length=count($final_results)-1;
$start_at=$s;
$per_page=$p;
$end_at=$s+$per_page;
if($end_at>$results_length){
	$end_at=$results_length;
}
@$page_number=$v/$p;
if(intval($page_number)<$page_number){
	$page_number=intval($page_number)+1;
}else{
	$page_number=intval($page_number);
}
$p_s=0;
$page_navigation="";
for($i=1;$i<=$page_number;$i++){
	if($p_s==$s){
		$page_navigation.="<strong>";
		$current_page=$i;
		$return_page=$results_script."?f=".$f."&t=".$t."&s=".$p_s."&p=".$p."&v=".$v."&m=".urlencode($m)."&mt=".$mt."&mu=".$mu."&md=".$md;
	}
	$read_results=$results_script."?f=".$f."&t=".$t."&s=".$p_s."&p=".$p."&v=".$v."&m=".urlencode($m)."&mt=".$mt."&mu=".$mu."&md=".$md;
	$page_navigation.=" <a href=\"".$read_results."\">".$i."</a> ";
	if($p_s==$s){
		$page_navigation.="</strong>";
	}
	$p_s=$p_s+$p;
}
if($s-$p>=0){
	$n_s=$s-10;
	$read_results=$results_script."?f=".$f."&t=".$t."&s=".$n_s."&p=".$p."&v=".$v."&m=".urlencode($m)."&mt=".$mt."&mu=".$mu."&md=".$md;
	$top_page_navigation=" <a href=\"".$read_results."\">".$text['last']."</a> ";
	$quick_navigation.=" <a href=\"".$read_results."\">&lt;</a> ";
}else{
	$top_page_navigation=" ".$text['last']." ";
	$quick_navigation.=" &lt; ";
}
$top_page_navigation.="|";
$read_results=$results_script."?f=".$f."&t=".$t."&s=0&p=".$p."&v=".$v."&m=".urlencode($m)."&mt=".$mt."&mu=".$mu."&md=".$md;
$quick_navigation.=" <a href=\"".$read_results."\">&lt;&lt;</a> ";
$quick_navigation.="<b>".intval($current_page).":".intval($page_number)."</b>";
$n_s=$p_s-$p;
$read_results=$results_script."?f=".$f."&t=".$t."&s=$n_s&p=".$p."&v=".$v."&m=".urlencode($m)."&mt=".$mt."&mu=".$mu."&md=".$md;
$quick_navigation.=" <a href=\"".$read_results."\">&gt;&gt;</a> ";
if($s+$p<=$v){
	$n_s=$s+10;
	$read_results=$results_script."?f=".$f."&t=".$t."&s=".$n_s."&p=".$p."&v=".$v."&m=".urlencode($m)."&mt=".$mt."&mu=".$mu."&md=".$md;
	$top_page_navigation.=" <a href=\"".$read_results."\">".$text['next']."</a> ";
	$quick_navigation.=" <a href=\"".$read_results."\">&gt;</a> ";
}else{
	$top_page_navigation.=" ".$text['next']." ";
	$quick_navigation.=" &gt; ";
}
$page_navigation=$top_page_navigation."<br>".$page_navigation;
$print_results="";
for($i=$s;$i<=$end_at;$i++){
	$entry=$final_results[$i];
	chop($entry);
	if($entry!=""){
		list($home_indent,$entry_total,$entry_home,$entry_url,$entry_matches,$entry_title,$entry_desc,$entry_keys,$entry_rating,$entry_hits,$entry_email,$entry_cat,$entry_key,$entry_cache,$entry_id)=split("::",$entry);
		$relevance=0;
		if(($entry_matches>0)&&($top_matches>0)){
			$relevance=intval(100*($entry_matches/$top_matches));
		}else{
			$relevance="0";
		}
		$relevance.="%";
		if($entry_url=="***none***"){
			$template=open("no_entry.txt");
		}elseif($home_indent=="homepage"){
			$template=open("home_entry.txt");
		}elseif($home_indent=="indent"){
			$template=open("page_entry.txt");
		}
		$entry_track=$track_script."?id=".$entry_id."&url=".urlencode($entry_url);
		if(strlen($entry_title)>$mt){
			$entry_title=substr($entry_title,0,$mt)."...";
		}
		if($mu=="hide"){
			$entry_url="";
		}else{
			if(strlen($entry_url)>$mu){
				$entry_url=substr($entry_url,0,$mu)."...";
			}
		}
		if($md=="hide"){
			$entry_desc="";
		}else{
			if(strlen($entry_desc)>$md){
				$entry_desc=substr($entry_desc,0,$md)."...";
			}
		}
		$stop=0;
		if(substr($m,0,strlen($category_command))==$category_command){
			$category=substr($m,strlen($category_command),strlen($m));
			if($category!=$entry_cat){
				$stop=1;
			}
		}
		$entry_cat="<a href=\"".$search_script."?t=".urlencode($category_command).urlencode($entry_cat)."\">".$entry_cat."</a>";
		$graphic_rating="<table border=0 cellpadding=0 cellspacing=0><tr><td align=center><nobr>";
		if($entry_rating>0){
			for($r=0;$r<$entry_rating;$r++){
				$graphic_rating.=$star;
			}
		}else{
			$graphic_rating.=$text['unrated'];
		}
		$graphic_rating.="<br><a href=\"".$rate_script."?id=".$entry_id."\">".$text['rate']."</a>";
		$graphic_rating.="</nobr></td></tr></table>";
		if($stop==0){
			$entry_title=preg_replace("/<(.+?)>/","",$entry_title);
			$entry_desc=preg_replace("/<(.+?)>/","",$entry_desc);
			$template=preg_replace("/<!--#url-->/", $entry_url, $template);
			$template=preg_replace("/<!--#track_url-->/", $entry_track, $template);
			$template=preg_replace("/<!--#title-->/", $entry_title, $template);
			$template=preg_replace("/<!--#rating-->/", $graphic_rating, $template);
			$template=preg_replace("/<!--#description-->/", $entry_desc, $template);
			$template=preg_replace("/<!--#keywords-->/", $entry_keys, $template);
			$template=preg_replace("/<!--#relevance-->/", $relevance, $template);
			$template=preg_replace("/<!--#hitcount-->/", $entry_hits, $template);
			$template=preg_replace("/<!--#category-->/", $entry_cat, $template);
			$template=preg_replace("/<!--#cache-->/", $entry_cache, $template);
			$template=preg_replace("/<!--#id-->/", $entry_id, $template);
			$print_results.=$template;
		}
	}
}
if($print_results==""){
	$template=open("no_results.txt");
	$template=preg_replace("/<!--#start_form-->/", "<form action=\"".$search_script."\" method=post>", $template);
	$template=preg_replace("/<!--#end_form-->/", "</form>", $template);
	$template=preg_replace("/<!--#small_terms_box-->/", "<input type=text name=t size=10>", $template);
	$template=preg_replace("/<!--#terms_box-->/", "<input type=text name=t size=50>", $template);
	$template=preg_replace("/<!--#large_terms_box-->/", "<input type=text name=t size=100>", $template);
	$template=preg_replace("/<!--#search_button-->/", "<input type=submit name=\"search_style\" value=\"".$button['search']."\">", $template);
	$template=preg_replace("/<!--#quick_button-->/", "<input type=submit name=\"search_style\" value=\"".$button['quick']."\">", $template);
	$print_results=$template;
}
$search_link="http://av.com/?q=".urlencode($m);
$engine_list =" <a href=\"".$remote_script."?back=".base64_encode($return_page)."&url=".base64_encode($search_link)."\" target=".$target.">AltaVista</a> ";
$search_link="http://search.excite.com/search.gw?search=".urlencode($m);
$engine_list.=" <a href=\"".$remote_script."?back=".base64_encode($return_page)."&url=".base64_encode($search_link)."\" target=".$target.">Excite</a> ";
$search_link="http://hotbot.com/?MT=".urlencode($m);
$engine_list.=" <a href=\"".$remote_script."?back=".base64_encode($return_page)."&url=".base64_encode($search_link)."\" target=".$target.">HotBot</a> ";
$search_link="http://lycos.com/cgi-bin/pursuit?query=".urlencode($m);
$engine_list.=" <a href=\"".$remote_script."?back=".base64_encode($return_page)."&url=".base64_encode($search_link)."\" target=".$target.">Lycos</a> ";
$search_link="http://search.yahoo.com/search?p=".urlencode($m);
$engine_list.=" <a href=\"".$remote_script."?back=".base64_encode($return_page)."&url=".base64_encode($search_link)."\" target=".$target.">Yahoo!</a> ";
$template=open("results.txt");
$category=substr($m,strlen($category_command),strlen($m));
$category_bits=Array();
$subcategories=Array();
$category_bits=split(">",$category);
if((substr($m,0,strlen($category_command))==$category_command)&&($current_page<=1)){
	$cats=split("\*\*\*",$categories);
	foreach($cats as $subcat){
		$sub_category_bits=split(">",$subcat);
		if(count($sub_category_bits)==count($category_bits)+1){
			if(substr($subcat,0,strlen($category))==$category){
				$subcat_name=$sub_category_bits[count($sub_category_bits)-1];
				@$query="SELECT * from $mysql_table WHERE category='$subcat' ORDER BY id";
				@$result=mysql_query($query);
				@$num=mysql_numrows($result);
				$subcat=urlencode($category_command.$subcat);
				$subcategories[]="<a href=\"".$search_script."?t=".$subcat."\">".$subcat_name."</a>".$text['number_start'].$num.$text['number_end']."<br>";
			}
		}
	}
	$print_subcategories="<table border=0>";
	$col=0;
	foreach($subcategories as $subcategory){
		if($col==0){
			$print_subcategories.="<tr><td>".$subcategory."</td>";
		}elseif(($col>0)&&($col<$subcat_col-1)){
			$print_subcategories.="<td width=5>&nbsp;</td><td>".$subcategory."</td>";
		}elseif($col==$subcat_col-1){
			$print_subcategories.="<td width=5>&nbsp;</td><td>".$subcategory."</td></tr>";
			$col=-1;
		}
		$col++;	
	}
	if(substr($print_subcategories,strlen($print_subcategories)-5,strlen($print_subcategories))!="</tr>"){
		$print_subcategories.="</tr>";
	}
	$print_subcategories.="</table>";
	$print_category="";
	$category_link=urlencode($category_command);
	foreach($category_bits as $category){
		$category_link.=urlencode($category);
		$print_category.="<a href=\"".$search_script."?t=".$category_link."\">".$category."</a> &gt; ";
		$category_link.="%3E";
	}
	$print_category=substr($print_category,0,strlen($print_category)-6);
	$template=preg_replace("/<!--#start_category-->/", "", $template);
	$template=preg_replace("/<!--#end_category-->/", "", $template);
	$template=preg_replace("/<!--#category-->/", $print_category, $template);
	$template=preg_replace("/<!--#sub_categories-->/", $print_subcategories, $template);
}else{
	$template=preg_replace("/<!--#start_category-->(.+?)<!--#end_category-->/", "", $template);
}
if($cw==""){
	$template=preg_replace("/<!--#common_terms_start-->(.+?)<!--#common_terms_end-->/", "", $template);
}else{
	$template=preg_replace("/<!--#common_terms-->/", $cw, $template);
}

if(substr($m,0,strlen($category_command))==$category_command){
	$amazon_terms=$category_bits[count($category_bits)-1];
}elseif(substr($m,0,strlen($keyword_command))==$keyword_command){
	$amazon_terms=substr($m,strlen($keyword_command),strlen($m));
}else{
	$amazon_terms=$m;
}
$amazon_terms=ereg_replace("\+","",$amazon_terms);
$amazon_terms=ereg_replace("\-","",$amazon_terms);
$amazon_terms=ereg_replace("\|"," ",$amazon_terms);
$amazon_terms=ereg_replace("\("," ",$amazon_terms);
$amazon_terms=ereg_replace("\)"," ",$amazon_terms);
if(substr($amazon_terms,0,1)==" "){
	$amazon_terms=substr($amazon_terms,1,strlen($amazon_terms));
}
$ad_term_bits=split(" ",$amazon_terms);
$ad_term="";
if(count($ad_term_bits)>0){
	foreach($ad_term_bits as $ad_term_bit){
		if(strlen($ad_term_bit)>strlen($ad_term)){
			$ad_term=strtolower($ad_term_bit);
		}
	}
}
if($ad_term==""){
	$ad_term=strtolower($amazon_terms);
}
if($target_ad[$ad_term]==""){
	$show_ad=$target_ad['default'];
}else{
	$show_ad=$target_ad[$ad_term];
}
$template=preg_replace("/<!--#results-->/", $print_results, $template);
$template=preg_replace("/<!--#ad-->/", $show_ad, $template);
$template=preg_replace("/<!--#amazon_search-->/", $amazon_terms, $template);
$template=preg_replace("/<!--#amazon_terms-->/", rawurlencode($amazon_terms), $template);
$template=preg_replace("/<!--#page_navigation-->/", $page_navigation, $template);
$template=preg_replace("/<!--#quick_navigation-->/", $quick_navigation, $template);
$template=preg_replace("/<!--#searched_terms-->/", $m, $template);
$template=preg_replace("/<!--#total_matches-->/", $total_matches, $template);
$template=preg_replace("/<!--#start_form-->/", "<form action=\"".$search_script."\" method=post>", $template);
$template=preg_replace("/<!--#end_form-->/", "</form>", $template);
$template=preg_replace("/<!--#small_terms_box-->/", "<input type=text name=t size=10>", $template);
$template=preg_replace("/<!--#terms_box-->/", "<input type=text name=t size=50>", $template);
$template=preg_replace("/<!--#large_terms_box-->/", "<input type=text name=t size=100>", $template);
$template=preg_replace("/<!--#search_button-->/", "<input type=submit name=\"search_style\" value=\"".$button['search']."\">", $template);
$template=preg_replace("/<!--#quick_button-->/", "<input type=submit name=\"search_style\" value=\"".$button['quick']."\">", $template);
if(substr($m,0,strlen($category_command))==$category_command){
	$template=preg_replace("/<!--#search_within-->/", "<img src=".$shaded_radio." width=11 height=11>&nbsp;",$template);
}else{
	$template=preg_replace("/<!--#search_within-->/", "<input type=radio name=\"within\" value=\"".urldecode($m)."\" checked>",$template);
}
if(substr($m,0,strlen($category_command))==$category_command){
	$template=preg_replace("/<!--#new_search-->/", "<input type=radio name=\"within\" value=\"\" checked>",$template);
}else{
	$template=preg_replace("/<!--#new_search-->/", "<input type=radio name=\"within\" value=\"\">",$template);

}
$template=preg_replace("/<!--#engine_list-->/", $engine_list, $template);
$template=preg_replace("/<!--#meta_search_link-->/", $meta_search."?terms=".rawurlencode($amazon_terms), $template);
$template=preg_replace("/<!--#header-->/", $header, $template);
$template=preg_replace("/<!--#footer-->/", $footer, $template);
$template=preg_replace("/<!--#title-->/", $page['title'].$page['separator'].$page['search_results'], $template);
print $template;

function open($filename){
	global $templates;
	$fd=@fopen($templates."/".$filename, "r");
	$template=@fread($fd, filesize ($templates."/".$filename));
	@fclose($fd);
	return $template;
}

?>