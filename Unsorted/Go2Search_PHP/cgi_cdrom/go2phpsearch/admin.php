<?php
#####################################################
#                   Go2! Search                     #
#####################################################
#                                                   #
#                    admin.php3                     #
#                                                   #
#####################################################
#       Copyright © 2001 W. Dustin Alligood         #
#####################################################

require("./text.php");
require("./config.php");

########## Change Nothing Below This Line ###########

mysql_connect($mysql_host,$mysql_user,$mysql_password) or die ("Could not connect");
mysql_select_db($mysql_database);

if($entered_password!=$admin_password){
	print "<html><head><title>Go2! Search: Login</title></head><body><p><center><font face=Verdana size=+2 color=red>Go2!</font> <font face=Verdana size=+2 color=blue>Search</font><font face=Verdana size=+2 color=green>: The PHP Edition</font></center></p>";
	if($entered_password!=""){
		print "<p><center><b>Error:</b> Incorrect password</center></p>";
	}
	print "<form action=\"".$admin_script."\" method=post><input type=hidden name=action value=\"\">";
	print "<p><center><table border=0><tr><td colspan=4 align=center><b>Admin Login</b></td></tr><tr><td>Password:</td><td colspan=2><input type=password name=entered_password></td></tr><tr><td>PIN:</td><td><input type=text name=pin_code></td><td><input type=submit value=\"Login\"></td></tr></table></center></p>";
	print "</form></body></html>";
	exit;
}else{
	if($action==""){
		print "<html><head><title>Go2! Search: Admin Main Page</title></head><body><p><center><font face=Verdana size=+2 color=red>Go2!</font> <font face=Verdana size=+2 color=blue>Search</font><font face=Verdana size=+2 color=green>: The PHP Edition</font></center></p>";
		print "<p><center><table border=0><tr><td>";
		$query="SELECT id FROM $mysql_table";
		$result=mysql_query($query);
		$total_entries=mysql_numrows($result);
		print "<p><center><table border=1 cellspacing=0 cellpadding=10><tr><td>";
		print "<p><center>Entries in database: <b>".$total_entries."</b></center></p>";
		print "</td></tr></table></center></p>";
		print "<form action=\"".$admin_script."\" method=post target=edit onSubmit=\"window.open('','edit','width=440,height=550,menubar')\">";
		print "<input type=hidden name=\"action\" value=\"show_entry\">";
		print "<input type=hidden name=\"entered_password\" value=\"".$entered_password."\">";
		print "<input type=hidden name=\"pin_code\" value=\"".$pin_code."\">";
		print "<p><center><table border=1 cellspacing=0 cellpadding=10><tr><td>";
		print "<p><center><b>Edit Entry</b></center></p>";
		print "<p><center><table border=0><tr><td><input type=radio name=sb value=id checked>Edit entry ID #</td><td><input type=text name=id></td></tr>";
		print "<tr><td><input type=radio name=sb value=url>Edit entry URL</td><td><input type=text value=\"http://\" name=url></td></tr></table><br><input type=submit value=\"Edit Entry\"></center></p>";
		print "</td></tr></table></center></p>";
		print "</form>";
		print "</td><td width=25>&nbsp;</td><td>";
		print "<p><center><table border=1 cellspacing=0 cellpadding=10 width=250 height=250><tr><td width=100% valign=top>";
		print "<p><center><b>Upgrades Available</b></center></p>";
		include("http://www.zeed.co.uk/search/upgrades.php?pin=".$pin_code."&url=".getenv("HTTP_HOST"));
		print "</td></tr></table></center></p>";
		print "</td></tr></table></center></p>";
		$files=opendir($temps);
		while($file=readdir($files)){
			if((substr($file,0,6)=="addurl")&&($file!="addurl_count.txt")){
				$add=$file;
				$add=preg_replace("/[^0-9]/","",$add);
				if(preg_match("/[0-9]/",$add)){
					$add_url_files[]=$add;
				}
			}elseif(substr($file,0,6)=="search"){
				$add=$file;
				$add=preg_replace("/[^0-9]/","",$add);
				if(preg_match("/[0-9]/",$add)){
					$search_files[]=$add;
				}
			}
		}
		sort($add_url_files,SORT_NUMERIC);
		sort($search_files,SORT_NUMERIC);
		print "<form action=\"".$admin_script."\" method=post target=view onSubmit=\"window.open('','view','width=300,height=100')\">";
		print "<input type=hidden name=\"action\" value=\"temps\">";
		print "<input type=hidden name=\"entered_password\" value=\"".$entered_password."\">";
		print "<input type=hidden name=\"pin_code\" value=\"".$pin_code."\">";
		$file=@fopen($temps."/addurl_count.txt","r");
		$active_add=@fread($file,filesize($temps."/addurl_count.txt"));
		@fclose($file);
		$file=@fopen($temps."/count.txt","r");
		$active_search=@fread($file,filesize($temps."/count.txt"));
		@fclose($file);
		print "<p><center><table border=1 cellspacing=0 cellpadding=10><tr><td>";
		print "<p><center><b>Temp File Management</b></center></p>";
		print "<p><center><table border=0><tr><td align=center>Add URL Temps<br><select name=add_file size=5>";
		foreach($add_url_files as $add_url_file){
			print "<option value=\"".$add_url_file."\">".$add_url_file."</option>";
		}
		print "</select><br><input type=submit name=which value=\"View Add URL\"><br><input type=submit name=which value=\"Delete Add URL\"></td><td width=10></td><td align=center>Recent Search Temps<br><select name=search_file size=5>";
		foreach($search_files as $search_file){
			print "<option value=\"".$search_file."\">".$search_file."</option>";
		}
		print "</select><br><input type=submit name=which value=\"View Search\"><br><input type=submit name=which value=\"Delete Search\"></td><td width=10></td><td><p>Active Add URL File: ".$active_add."</p><p>Active Search File: ".$active_search."</p></td></tr></table></center></p>";
		print "</td></tr></table></center></p>";
		print "</form>";
		list($home_indent,$entry_total,$entry_home,$entry_url,$entry_matches,$entry_title,$entry_desc,$entry_keys,$entry_rating,$entry_hits,$entry_email,$entry_cat,$entry_key,$entry_cache,$entry_id)=split("::",$entry);
		print "<p><center><table border=1 cellspacing=0 cellpadding=10><tr><td>";
		print "<p><center><b>Manually Add New Entry</b></b></center></p>";
		print "<form action=\"".$admin_script."\" method=post target=new onSubmit=\"window.open('','new','width=300,height=100')\">";
		print "<input type=hidden name=\"action\" value=\"new_entry\">";
		print "<input type=hidden name=\"entered_password\" value=\"".$entered_password."\">";
		print "<input type=hidden name=\"pin_code\" value=\"".$pin_code."\">";
		print "<input type=hidden name=\"id\" value=\"".$entry_id."\">";
		print "<p><center><table border=0 width=100%>";
		print "<tr><td valign=top>Title:</td><td align=right><input type=text size=40 name=title></td></tr>";
		print "<tr><td valign=top>URL:</td><td align=right><input type=text size=40 name=url value=\"http://\"></td></tr>";
		print "<tr><td valign=top>Homepage:</td><td align=right><input type=text size=40 name=homepage value=\"http://\"></td></tr>";
		print "<tr><td valign=top>SearchKey:</td><td align=right><input type=text size=40 name=searchkey></td></tr>";
		print "<tr><td valign=top>Category:</td><td align=right><input type=text size=40 name=category></td></tr>";
		print "<tr><td valign=top>Email:</td><td align=right><input type=text size=40 name=email></td></tr>";
		print "<tr><td valign=top>Hits:</td><td align=right><input type=text size=40 name=hits value=0></td></tr>";
		print "<tr><td valign=top>Priority:</td><td align=right><input type=text size=40 name=priority value=0></td></tr>";
		print "<tr><td valign=top>Rating:</td><td align=right><input type=text size=40 name=rating value=0></td></tr>";
		print "<tr><td valign=top>Votes:</td><td align=right><input type=text size=40 name=votes value=0></td></tr>";
		print "<tr><td valign=top>Description:</td><td align=right><textarea wrap rows=4 cols=38 name=description></textarea></td></tr>";
		print "<tr><td valign=top>Keywords:</td><td align=right><textarea wrap rows=4 cols=38 name=keywords></textarea></td></tr>";
		print "<tr><td valign=top>Other:</td><td align=right><input type=text size=40 name=other></td></tr>";
		print "<tr><td colspan=2 align=center><input type=submit value=\"Add Entry\"></td></tr></table></center></p>";
		print "</form>";
		print "</td></tr></table></center></p>";
		print "<p><center><table border=1 cellspacing=0 cellpadding=10><tr><td>";
		print "<p><center><b>Add/Delete Categories</b></b></center></p><p><center>";
		print "<form action=\"".$admin_script."\" method=post name=catform target=new onSubmit=\"window.open('','new','width=300,height=100')\">";
		print "<input type=hidden name=\"action\" value=\"edit_cat\">";
		print "<input type=hidden name=\"entered_password\" value=\"".$entered_password."\">";
		print "<input type=hidden name=\"pin_code\" value=\"".$pin_code."\">";
		print "<input type=hidden name=\"id\" value=\"".$entry_id."\">";
		print "<select name=old_cat size=5 onChange=\"document.catform.new_cat.value=document.catform.old_cat.options[document.catform.old_cat.selectedIndex].text\">";
		$cat_array=split("\*\*\*",$categories);
		sort($cat_array);
		foreach($cat_array as $cat){
			print "<option value=\"".urlencode($cat)."\">".$cat."</option>";
		}
		print "</select><br><input type=text name=new_cat size=50><br><input type=submit name=add_delete value=\"Add Category\"> &nbsp; <input type=submit name=add_delete value=\"Delete Category\">";
		print "</center></p></td></form></tr></table></center></p>";
		print "<p><center><table border=1 cellspacing=0 cellpadding=10><tr><td>";
		print "<p><center><b>Search Filter Management</b></b></center></p><p><center>";
		print "<form action=\"".$admin_script."\" method=post target=filter onSubmit=\"window.open('','filter','width=300,height=100')\">";
		print "<input type=hidden name=\"action\" value=\"edit_filter\">";
		print "<input type=hidden name=\"entered_password\" value=\"".$entered_password."\">";
		print "<input type=hidden name=\"pin_code\" value=\"".$pin_code."\">";
		print "<input type=hidden name=\"id\" value=\"".$entry_id."\">";
		print "<table border=0><tr><td>Common Words Filter:<br><input type=text name=common value=\"".$common_words."\" size=40></td></tr><tr><td>Naughty Words Filter:<br><input type=text name=naughty value=\"".$naughty_words."\" size=40></td></tr><tr><td align=center>Separate words by commas</td></tr></table>";
		print "<input type=submit value=\"Edit Filters\">";
		print "</center></p></td></form></tr></table></center></p>";
		print "</body></html>";
		exit;
	}elseif($action=="edit_filter"){
		$file=@fopen($config_file,"r");
		$raw_old_config=@fread($file,filesize($config_file));
		@fclose($file);
		$old_config=split("\n",$raw_old_config);
		$new_config="";
		foreach($old_config as $line){
			chop($line);
			if(substr($line,0,14)=="\$naughty_words"){
				$new_config.="\$naughty_words=\"".$naughty."\";\n";
			}elseif(substr($line,0,13)=="\$common_words"){
				$new_config.="\$common_words=\"".$common."\";\n";
			}else{
				$new_config.=$line."\n";
			}
		}
		$file=fopen($config_file,"w");
		rewind($file);
		fputs($file,$new_config);
		@fclose($file);
		print "<html><head><title>Go2! Search: Filters Edited</title></head><body bgcolor=#C0C0C0>";
		print "<form><p><center>Search Filters Edited</center></p><p><center><input type=button value=Close onClick=self.close()></center></p></form></body></html>";
		exit;
	}elseif($action=="edit_cat"){
		$old_cat=urldecode($old_cat);
		if($add_delete=="Delete Category"){
			if($old_cat==""){
				$old_cat=$new_cat;
			}
			$a=1;
			$c=$old_cat;
		}else{
			$a=0;
			$c=$new_cat;
		}
		$file=@fopen($config_file,"r");
		$raw_old_config=@fread($file,filesize($config_file));
		@fclose($file);
		$old_config=split("\n",$raw_old_config);
		$new_config="";
		$ac=2;
		foreach($old_config as $line){
			chop($line);
			if(substr($line,0,11)=="\$categories"){
				if($ac==3){
					if($line!="\$categories.=\"***\";"){
						$new_config.=$line."\n";
					}
					$ac=1;
				}else{
					$ac=1;
					if($a==1){
						if($line!="\$categories.=\"".$c."\";"){
							$new_config.=$line."\n";
						}else{
							$ac=3;
						}
					}else{
						$new_config.=$line."\n";
					}
				}
			}elseif(($ac==1)&&($a==0)){
				if(chop(substr($new_config,strlen($new_config)-20,strlen($new_config)))=='$categories.="***";'){
					$new_config=substr($new_config,0,strlen($new_config)-20);
				}
				$new_config.="\$categories.=\"***\";\n";
				$new_config.="\$categories.=\"".$c."\";\n\n";
				$ac=0;
			}else{
				if(chop(substr($new_config,strlen($new_config)-20,strlen($new_config)))=='$categories.="***";'){
					$new_config=substr($new_config,0,strlen($new_config)-20);
				}
				$new_config.=$line."\n";
			}
		}
		$file=fopen($config_file,"w");
		rewind($file);
		fputs($file,$new_config);
		@fclose($file);
		print "<html><head><title>Go2! Search: Categories Edited</title></head><body bgcolor=#C0C0C0>";
		print "<form><p><center>Categories Edited</center></p><p><center><input type=button value=Close onClick=self.close()></center></p></form></body></html>";
		exit;
	}elseif($action=="new_entry"){
		$query="insert into $mysql_table set id='',title='$title',url='$url',homepage='$homepage',searchkey='$searchkey',category='$category',email='$email',hits='$hits',priority='$priority',rating='$rating',votes='$votes',description='$description',keywords='$keywords',other='$other'";
		$result=mysql_query($query);
		print "<html><head><title>Go2! Search: New Entry Added</title></head><body bgcolor=#C0C0C0>";
		print "<form><p><center>New Entry Added</center></p><p><center><input type=button value=Close onClick=self.close()></center></p></form></body></html>";
		exit;
	}elseif($action=="show_entry"){
		if($sb=="id"){
			$query="SELECT * FROM $mysql_table WHERE id=$id";
			$result=mysql_query($query);
			if(mysql_numrows($result)<1){
				print "<html><head><title>Go2! Search: Error</title></head><body bgcolor=#C0C0C0>";
				print "<p><center><table border=1 cellspacing=0 cellpadding=10 bgcolor=#ffffff><tr><td>";
				print "<p><b>Error:</b><br>No entry was found matching the ID # ".$id."</p>";
				print "</td></tr></table></center></p>";
				print "<form><p><center><input type=button onClick=self.close() value=Close></center></p></form>";
				print "</body></html>";
				exit;
			}
		}else{
			$query="SELECT * FROM $mysql_table WHERE url='$url'";
			$result=mysql_query($query);
			if(mysql_numrows($result)<1){
				print "<html><head><title>Go2! Search: Error</title></head><body bgcolor=#C0C0C0>";
				print "<p><center><table border=1 cellspacing=0 cellpadding=10 bgcolor=#ffffff><tr><td>";
				print "<p><b>Error:</b><br>No entry was found matching the URL ".$url."</p>";
				print "</td></tr></table></center></p>";
				print "<form><p><center><input type=button onClick=self.close() value=Close></center></p></form>";
				print "</body></html>";
				exit;
			}
		}
		extract(mysql_fetch_array($result),EXTR_PREFIX_ALL,"entry");
		print "<html><head><title>Go2! Search: Editing Entry ID # ".$entry_id."</title></head><body bgcolor=#C0C0C0>";
		print "<form action=\"".$admin_script."\" method=post>";
		print "<input type=hidden name=\"action\" value=\"edit_entry\">";
		print "<input type=hidden name=\"entered_password\" value=\"".$entered_password."\">";
		print "<input type=hidden name=\"pin_code\" value=\"".$pin_code."\">";
		print "<input type=hidden name=\"id\" value=\"".$entry_id."\">";
		print "<p><center><table border=0 width=100%>";
		print "<tr><td valign=top>Title:</td><td align=right><input type=text size=40 name=title value=\"".$entry_title."\"></td></tr>";
		print "<tr><td valign=top>URL:</td><td align=right><input type=text size=40 name=url value=\"".$entry_url."\"></td></tr>";
		print "<tr><td valign=top>Homepage:</td><td align=right><input type=text size=40 name=homepage value=\"".$entry_homepage."\"></td></tr>";
		print "<tr><td valign=top>SearchKey:</td><td align=right><input type=text size=40 name=searchkey value=\"".$entry_searchkey."\"></td></tr>";
		print "<tr><td valign=top>Category:</td><td align=right><input type=text size=40 name=category value=\"".$entry_category."\"></td></tr>";
		print "<tr><td valign=top>Email:</td><td align=right><input type=text size=40 name=email value=\"".$entry_email."\"></td></tr>";
		print "<tr><td valign=top>Hits:</td><td align=right><input type=text size=40 name=hits value=\"".$entry_hits."\"></td></tr>";
		print "<tr><td valign=top>Priority:</td><td align=right><input type=text size=40 name=priority value=\"".$entry_priority."\"></td></tr>";
		print "<tr><td valign=top>Rating:</td><td align=right><input type=text size=40 name=rating value=\"".$entry_rating."\"></td></tr>";
		print "<tr><td valign=top>Votes:</td><td align=right><input type=text size=40 name=votes value=\"".$entry_votes."\"></td></tr>";
		print "<tr><td valign=top>Description:</td><td align=right><textarea wrap rows=4 cols=38 name=description>".$entry_description."</textarea></td></tr>";
		print "<tr><td valign=top>Keywords:</td><td align=right><textarea wrap rows=4 cols=38 name=keywords>".$entry_keywords."</textarea></td></tr>";
		print "<tr><td valign=top>Other:</td><td align=right><input type=text size=40 name=other value=\"".$entry_other."\"></td></tr>";
		print "<tr><td colspan=2 align=right><nobr><input type=button onClick=self.close() value=Cancel> &nbsp; <input type=submit name=sub_action value=\"Delete Entry\"> &nbsp; <input type=submit name=sub_action value=\"Edit Entry\"></nobr></td></tr></table></center></p>";
		print "</form></body></html>";
		exit;
	}elseif($action=="edit_entry"){
		if($sub_action=="Delete Entry"){
			$query="delete from $mysql_table where id='$id'";
			$result=mysql_query($query);
		}else{
			$query="update $mysql_table set title='$title',url='$url',homepage='$homepage',searchkey='$searchkey',category='$category',email='$email',hits='$hits',priority='$priority',rating='$rating',votes='$votes',description='$description',keywords='$keywords',other='$other' where id='$id'";
			$result=mysql_query($query);
		}
		print "<script language=javascript><!--\n self.close();\n //--></script>";
		exit;
	}elseif($action=="temps"){
		if($which=="View Add URL"){
			$file=@fopen($temps."/addurl".$add_file.".txt","r");
			$raw_entries=@fread($file,filesize($temps."/addurl".$add_file.".txt"));
			@fclose($file);
			$entries=split("\n",$raw_entries);
			list($email,$category,$searchkey)=split("\|\|",$entries[0]);
			print "<html><head><title>Go2! Search: Add URL Temp</title></head><body bgcolor=#C0C0C0>";
			print "<p><center><table border=0><tr><td>Submitted by:</td><td><a href=\"mailto:".$email."\">".$email."</a></td></tr>";
			print "<tr><td>Category:</td><td>".$category."</td></tr>";
			print "<tr><td>SearchKey:</td><td>".$searchkey."</td></tr></table></center></p><ol>";
			for($i=1;$i<count($entries)-1;$i++){
				print "<li><a href=".$entries[$i]." target=_blank>".$entries[$i]."</a></li>";
			}
			print "</ol><form><p><center><input type=button onClick=self.close() value=Close></center></p></form></body></html>";
			exit;
		}elseif($which=="Delete Add URL"){
			unlink($temps."/addurl".$add_file.".txt");
			print "<html><head><title>Go2! Search: Search Temp</title></head><body bgcolor=#C0C0C0>";
			print "<p><center>Add URL temp file deleted</center></p>";
			print "<form><p><center><input type=button onClick=self.close() value=Close></center></p></form></body></html>";
			exit;
		}elseif($which=="View Search"){
			$file=@fopen($temps."/search".$search_file.".txt","r");
			$raw_entries=@fread($file,filesize($temps."/search".$search_file.".txt"));
			@fclose($file);
			$entries=split("\*\*\*end_of_line\*\*\*",$raw_entries);
			print "<html><head><title>Go2! Search: Search Temp</title></head><body bgcolor=#C0C0C0>";
			foreach($entries as $entry){
				list($home_indent,$entry_total,$entry_home,$entry_url,$entry_matches,$entry_title,$entry_desc,$entry_keys,$entry_rating,$entry_hits,$entry_email,$entry_cat,$entry_key,$entry_cache,$entry_id)=split("::",$entry);
				if(($entry_id!=0)&&($entry_id!="")){
					print "<p><table border=1 cellspacing=0 width=100%><tr><td><table border=0 width=100%><tr><td valign=top width=100%><tr><td align=center>ID: ".$entry_id."</td></tr><tr><td valign=top width=100%><b><a href=\"".$entry_url."\">".$entry_title."</a></b><br>".$entry_desc."<br><i>".$entry_url."</i></td></tr></table></td></tr></table></p>";
					$number++;
				}
			}
			if($number<1){
				print "<p><center>Search returned no matching entries</center></p>";
			}
			print "<form><p><center><input type=button onClick=self.close() value=Close></center></p></form></body></html>";
			exit;
		}elseif($which=="Delete Search"){
			unlink($temps."/search".$search_file.".txt");
			print "<html><head><title>Go2! Search: Search Temp</title></head><body bgcolor=#C0C0C0>";
			print "<p><center>Search temp file deleted</center></p>";
			print "<form><p><center><input type=button onClick=self.close() value=Close></center></p></form></body></html>";
			exit;
		}
		exit;
	}
}

function open($filename){
	global $templates;
	$fd=@fopen($templates."/".$filename, "r");
	$template=@fread($fd, filesize ($templates."/".$filename));
	@fclose($fd);
	return $template;
}

?>