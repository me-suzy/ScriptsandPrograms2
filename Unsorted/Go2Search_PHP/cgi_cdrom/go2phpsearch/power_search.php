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
if($action==$button['begin_search']){
	if($t==""){
		if(!preg_match("/\s/",$terms[0])){
			$t="+".$terms[0];
		}else{
			$t="+(".$terms[0].")";
		}	
	}else{
		if($terms[count($terms)-1]!=""){
			if(!preg_match("/\s/",$terms[count($terms)-1])){
				if($boolean[count($terms)-1]=="|"){
					$t.=$boolean[count($terms)-1].$terms[count($terms)-1];
				}else{
					$t.=" ".$boolean[count($terms)-1].$terms[count($terms)-1];
				}
			}else{
				if($boolean[count($terms)-1]=="|"){
					$t.=$boolean[count($terms)-1]."(".$terms[count($terms)-1].")";
				}else{
					$t.=" ".$boolean[count($terms)-1]."(".$terms[count($terms)-1].")";
				}
			}
		}
	}
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
	if($c==1){
		$case="s";
	}else{
		$case="i";
	}
	header("Location:".$search_script."?t=".urlencode($t)."&case=".$case."&fstr=".$fivestar."&fltr=".$filter."&tld=".$tld."&p=".$p."&mt=".$mt."&mu=".$mu."&md=".$md);
	exit;
}
$case ="<input type=checkbox name=c value=1";
if($c==1){
	$case.=" checked";
}
$case.=">";
$five_star ="<input type=checkbox name=fivestar value=1";
if($fivestar==1){
	$five_star.=" checked";
}
$five_star.=">";
$family_safe="<input type=checkbox name=filter value=1";
if($filter==1){
	$family_safe.=" checked";
}
$family_safe.=">";
$country ="<select name=tld>";
$country.="<option value=\"\"";
if($tld==""){
	$country.=" selected";
}
$country.=">".$tld_c['int']."</option>";
$country.="<option value=\"\">---</option>";
$country.="<option value=\"US\"";
if($tld=="US"){
	$country.=" selected";
}
$country.=">.us - ".$tld_c['US']."</option>";
$country.="<option value=\"CA\"";
if($tld=="CA"){
	$country.=" selected";
}
$country.=">.ca - ".$tld_c['Canada']."</option>";
$country.="<option value=\"UK\"";
if($tld=="UK"){
	$country.=" selected";
}
$country.=">.uk - ".$tld_c['UK']."</option>";
$country.="<option value=\"\">---</option>";
$country.="<option value=\"AF\"";
if($tld=="AF"){
	$country.=" selected";
}
$country.=">.af - ".$tld_c['Afghanistan']."</option>";
$country.="<option value=\"AU\"";
if($tld=="AU"){
	$country.=" selected";
}
$country.=">.au - ".$tld_c['Australia']."</option>";
$country.="<option value=\"AT\"";
if($tld=="AT"){
	$country.=" selected";
}
$country.=">.at - ".$tld_c['Austria']."</option>";
$country.="<option value=\"BE\"";
if($tld=="BE"){
	$country.=" selected";
}
$country.=">.be - ".$tld_c['Belgium']."</option>";
$country.="<option value=\"BR\"";
if($tld=="BR"){
	$country.=" selected";
}
$country.=">.br - ".$tld_c['Brazil']."</option>";
$country.="<option value=\"CA\"";
if($tld=="CA"){
	$country.=" selected";
}
$country.=">.ca - ".$tld_c['Canada']."</option>";
$country.="<option value=\"DK\"";
if($tld=="DK"){
	$country.=" selected";
}
$country.=">.dk - ".$tld_c['Denmark']."</option>";
$country.="<option value=\"FR\"";
if($tld=="FR"){
	$country.=" selected";
}
$country.=">.fr - ".$tld_c['France']."</option>";
$country.="<option value=\"DE\"";
if($tld=="DE"){
	$country.=" selected";
}
$country.=">.de - ".$tld_c['Germany']."</option>";
$country.="<option value=\"GR\"";
if($tld=="GR"){
	$country.=" selected";
}
$country.=">.gr - ".$tld_c['Greece']."</option>";
$country.="<option value=\"HK\"";
if($tld=="HK"){
	$country.=" selected";
}
$country.=">.hk - ".$tld_c['HongKong']."</option>";
$country.="<option value=\"IN\"";
if($tld=="IN"){
	$country.=" selected";
}
$country.=">.in - ".$tld_c['India']."</option>";
$country.="<option value=\"ID\"";
if($tld=="ID"){
	$country.=" selected";
}
$country.=">.id - ".$tld_c['Indonesia']."</option>";
$country.="<option value=\"IE\"";
if($tld=="IE"){
	$country.=" selected";
}
$country.=">.ie - ".$tld_c['Ireland']."</option>";
$country.="<option value=\"IT\"";
if($tld=="IT"){
	$country.=" selected";
}
$country.=">.it - ".$tld_c['Italy']."</option>";
$country.="<option value=\"JP\"";
if($tld=="JP"){
	$country.=" selected";
}
$country.=">.jp - ".$tld_c['Japan']."</option>";
$country.="<option value=\"KR\"";
if($tld=="KR"){
	$country.=" selected";
}
$country.=">.kr - ".$tld_c['Korea']."</option>";
$country.="<option value=\"MX\"";
if($tld=="MX"){
	$country.=" selected";
}
$country.=">.mx - ".$tld_c['Mexico']."</option>";
$country.="<option value=\"NL\"";
if($tld=="NL"){
	$country.=" selected";
}
$country.=">.nl - ".$tld_c['Netherlands']."</option>";
$country.="<option value=\"NZ\"";
if($tld=="NZ"){
	$country.=" selected";
}
$country.=">.nz - ".$tld_c['Zealand']."</option>";
$country.="<option value=\"NO\"";
if($tld=="NO"){
	$country.=" selected";
}
$country.=">.no - ".$tld_c['Norway']."</option>";
$country.="<option value=\"PT\"";
if($tld=="PT"){
	$country.=" selected";
}
$country.=">.pt - ".$tld_c['Portugal']."</option>";
$country.="<option value=\"PR\"";
if($tld=="PR"){
	$country.=" selected";
}
$country.=">.pr - ".$tld_c['PuertoRico']."</option>";
$country.="<option value=\"SA\"";
if($tld=="SA"){
	$country.=" selected";
}
$country.=">.sa - ".$tld_c['Arabia']."</option>";
$country.="<option value=\"SG\"";
if($tld=="SG"){
	$country.=" selected";
}
$country.=">.sg - ".$tld_c['Singapore']."</option>";
$country.="<option value=\"ZA\"";
if($tld=="ZA"){
	$country.=" selected";
}
$country.=">.za - ".$tld_c['SouthAfrica']."</option>";
$country.="<option value=\"ES\"";
if($tld=="ES"){
	$country.=" selected";
}
$country.=">.es - ".$tld_c['Spain']."</option>";
$country.="<option value=\"SE\"";
if($tld=="SE"){
	$country.=" selected";
}
$country.=">.se - ".$tld_c['Sweden']."</option>";
$country.="<option value=\"CH\"";
if($tld=="CH"){
	$country.=" selected";
}
$country.=">.ch - ".$tld_c['Switzerland']."</option>";
$country.="<option value=\"TW\"";
if($tld=="TW"){
	$country.=" selected";
}
$country.=">.tw - ".$tld_c['Taiwan']."</option>";
$country.="<option value=\"TH\"";
if($tld=="TH"){
	$country.=" selected";
}
$country.=">.th - ".$tld_c['Thailand']."</option>";
$country.="<option value=\"TR\"";
if($tld=="TR"){
	$country.=" selected";
}
$country.=">.tr - ".$tld_c['Turkey']."</option>";
$country.="<option value=\"AE\"";
if($tld=="AE"){
	$country.=" selected";
}
$country.=">.ae - ".$tld_c['Emirates']."</option>";
$country.="<option value=\"UK\"";
if($tld=="UK"){
	$country.=" selected";
}
$country.=">.uk - ".$tld_c['UK']."</option>";
$country.="<option value=\"US\"";
if($tld=="US"){
	$country.=" selected";
}
$country.=">.us - ".$tld_c['US']."</option>";
$country.="<option value=\"VE\"";
if($tld=="VE"){
	$country.=" selected";
}
$country.=">.ve - ".$tld_c['Venezuela']."</option>";
$country.="</select>";
if($p<10){
	$p=10;
}
if(intval($mt)==0){
	$mt=80;
}
if(intval($mu)==0){
	$mu=80;
}
if(intval($md)==0){
	$md=320;
}
$template=open("power_search.txt");
$template=preg_replace("/<!--#start_power_form-->/", "<form action=\"".$power_script."\" method=post>", $template);
$template=preg_replace("/<!--#end_power_form-->/", "</form>", $template);
$template=preg_replace("/<!--#simple_search_link-->/", "<a href=\"".$search_script."\">".$text['simple_search']."</a>", $template);
$template=preg_replace("/<!--#begin_search-->/", "<input type=submit name=action value=\"".$button['begin_search']."\" style=\"font-size:10\">", $template);
$template=preg_replace("/<!--#case-->/", $case, $template);
$template=preg_replace("/<!--#five_star-->/", $five_star, $template);
$template=preg_replace("/<!--#family_safe-->/", $family_safe, $template);
$template=preg_replace("/<!--#country-->/", $country, $template);
$template=preg_replace("/<!--#per_page-->/", "<input type=text value=\"".$p."\" name=p size=3>", $template);
$template=preg_replace("/<!--#t_length-->/", "<input type=text value=\"".$mt."\" name=mt size=4 style=\"font-size:10\">", $template);
$template=preg_replace("/<!--#mu_length-->/", "<input type=text value=\"".$mu."\" name=mu size=4 style=\"font-size:10\">", $template);
$template=preg_replace("/<!--#md_length-->/", "<input type=text value=\"".$md."\" name=md size=4 style=\"font-size:10\">", $template);
$template=preg_replace("/<!--#header-->/", $header, $template);
$template=preg_replace("/<!--#footer-->/", $footer, $template);
$template=preg_replace("/<!--#title-->/", $page['title'].$page['separator'].$page['power_search'], $template);
$search_terms="<table border=0><tr><td>";
if($terms[0]==""){
	$search_terms.="<table border=0 width=100%><tr><td align=left><select name=boolean[]><option value=\"+\" selected>".$text['must']."</option></select></td><td width=5>&nbsp;</td><td align=right><input type=text size=40 name=terms[]></td></tr><tr><td colspan=3 align=right><input type=submit name=action value=\"".$button['add_terms']."\"</td></tr></table>";
	$search_terms.="</td></tr></table>";
	$template=preg_replace("/<!--#search_terms-->/", $search_terms, $template);
	$template=preg_replace("/<!--#start_term_box-->(.+?)<!--#end_term_box-->/", "", $template);
	print $template;
}else{
	$n=0;
	$t="";
	foreach($terms as $term){
		if($n==0){
			$search_terms.="<table border=0 width=100%><tr><td align=left><select name=boolean[]><option value=\"+\" selected>".$text['must']."</option>";
			$t.=" +";
		}else{
			$search_terms.="<table border=0 width=100%><tr><td align=left><select name=boolean[]><option value=\"+\"";
			if($boolean[$n]=="+"){
				$search_terms.=" selected";
				$t.=" +";
			}
			$search_terms.=">".$text['must']."</option><option value=\"-\"";
			if($boolean[$n]=="-"){
				$search_terms.=" selected";
				$t.=" -";
			}
			$search_terms.=">".$text['cannot']."</option><option value=\"|\"";
			if($boolean[$n]=="|"){
				$search_terms.=" selected";
				$t.="|";
			}
			$search_terms.=">".$text['or']."</option>";
		}
		$search_terms.="</select></td><td width=5>&nbsp;</td><td align=right><input type=text size=40 name=terms[] value=\"".$term."\"></td></tr></table>";
		if(!preg_match("/\s/",$term)){
			$t.=$term;
		}else{
			$t.="(".$term.")";
		}
		$n++;
	}
	$search_terms.="<table border=0 width=100%><tr><td align=left><select name=boolean[]><option value=\"+\" selected>".$text['must']."</option><option value=\"-\">".$text['cannot']."</option><option value=\"|\">".$text['or']."</option></select></td><td width=5>&nbsp;</td><td align=right><input type=text size=40 name=terms[]></td></tr><tr><td colspan=3 align=right><input type=submit name=action value=\"".$button['add_terms']."\"</td></tr></table>";
	$search_terms.="</td></tr></table><input type=hidden name=t value=\"".$t."\">";
	$template=preg_replace("/<!--#search_terms-->/", $search_terms, $template);
	$template=preg_replace("/<!--#terms-->/", $t, $template);
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