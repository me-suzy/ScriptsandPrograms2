<?php
//Admin Pending Functions

// get values for number of pending
function editor_pending_cats_in_list()
{global $conn, $ses;

	$rs = &$conn->Execute("SELECT cat_id FROM inl_cats WHERE cat_user=".$ses["user_id"]); 
	if ($rs)
		$row = $rs->fields;
	if ($row)
	{
		foreach ($row as $ce)
		{
			$sub_cats = ereg_replace($ce.", ", "", $sub_cats);
			$sub_cats.= $ce.", ";
		}
		$sub_cats = ereg_replace(", $", "", $sub_cats);
	}
	return $sub_cats;

}

function pendinginfo() 
{	global $conn, $plinks, $pcats, $pusers, $previews, $ses,$sql_type;
	
	if($ses["user_perm"]==5) //editor
	{
		$rs = &$conn->Execute("SELECT link_id FROM inl_lc LEFT JOIN inl_cats ON inl_lc.cat_id=inl_cats.cat_id WHERE link_pend!=0 AND cat_user=".$ses["user_id"]." GROUP BY link_id");
	}
	else
		$rs = &$conn->Execute("SELECT link_id FROM inl_lc WHERE link_pend!=0 GROUP BY link_id");
	
	if ($rs && !$rs->EOF)
		$plinks = $rs->RecordCount();
	else
		$plinks=0;
	
	if($ses["user_perm"]==5) //editor
	{
		$sub_cats = editor_pending_cats_in_list();
		$rs = &$conn->Execute("SELECT cat_id FROM inl_cats WHERE cat_pend!=0 and cat_sub IN ($sub_cats)");
		
	}
	else
		$rs = &$conn->Execute("select * from inl_cats where cat_pend=1");
	if ($rs && !$rs->EOF)
		$pcats = $rs->RecordCount();
	else
		$pcats=0;
	

	$rs = &$conn->Execute("select * from inl_users where user_pend=1");
	if ($rs && !$rs->EOF)
		$pusers = $rs->RecordCount();
	else
		$pusers=0;
	
	if($ses_perm==5) //editor
		$rs = &$conn->Execute("SELECT * FROM inl_reviews LEFT JOIN inl_lc ON inl_reviews.rev_link=inl_lc.link_id LEFT JOIN inl_cats ON inl_lc.cat_id=inl_cats.cat_id WHERE rev_pend=1 AND cat_user=".$ses["user_id"]);
	else
		$rs = &$conn->Execute("select * from inl_reviews where rev_pend=1");
	if ($rs && !$rs->EOF)
		$previews = $rs->RecordCount();
	else
		$previews=0;
}

function duplicate_links()
{	global $sql_type, $ses;
	if($sql_type=="mssql")
	{	if($ses["user_perm"]==5)
			return "SELECT link_url, count(link_url) AS 'count' FROM inl_links LEFT JOIN inl_lc ON inl_links.link_id=inl_lc.link_id LEFT JOIN inl_cats ON inl_lc.cat_id=inl_cats.cat_id WHERE link_url<>'http://' and link_url<>'' and inl_cats.cat_user=".$ses["user_id"]." GROUP BY link_url HAVING count(link_url)>1";
		else
			return "SELECT link_url, count(link_url) AS 'count' FROM inl_links WHERE link_url<>'http://' and link_url<>'' GROUP BY link_url HAVING count(link_url)>1";
	}
	else
	{	if($ses["user_perm"]==5)
			return "SELECT link_url, count(link_url) AS count FROM inl_links LEFT JOIN inl_lc ON inl_links.link_id=inl_lc.link_id LEFT JOIN inl_cats ON inl_lc.cat_id=inl_cats.cat_id WHERE link_url<>'http://' and link_url<>'' and inl_cats.cat_user=".$ses["user_id"]." GROUP BY link_url HAVING count>1";
		else
			return "SELECT link_url, count(link_url) AS count FROM inl_links WHERE link_url<>'http://' and link_url<>'' GROUP BY link_url HAVING count>1";
	}
}

function printduplink($query) {
	global $conn, $dups, $links, $la_no_links,$la_no_duplicate, $la_all_links_valid, $la_duplicate_links, $sid, $session_get;

	if($sid && $session_get)
		$att_sid="sid=$sid&";

	$rs = &$conn->Execute($query);
	if ($rs && !$rs->EOF)
		$dups = $rs->RecordCount();
	if(!$dups){$dups=0;}
	$links="";
	if($rs && !$rs->EOF) {
		do {
			$row = $rs->fields;
			$links=$links."<tr bgcolor='#999999' valign='middle'><td class='textTitle'> ( ".$row[1]." ) $la_duplicate_links</td></tr>";
			$link_url = $row[0];
			
		
			//$links .= "<tr bgcolor='#F6F6F6' valign='middle'><td class='text'><ul><li><b><a href='duplicatelinks.php?$att_sid"."dislink=1&url=$link_url'>$link_url</a></b></li><ul></td></tr>";
			
			$links .= "<tr bgcolor='#F6F6F6' valign='middle'><td class='text'><ul><li><input type=\"checkbox\" name=\"duplinks[$link_url]\"><b><a href='duplicatelinks.php?$att_sid"."dislink=1&url=$link_url'>$link_url</a></b></li><ul></td></tr>";
			$rs->MoveNext();
    		} while ($rs && !$rs->EOF);
  	} else {
  			$links = "<tr bgcolor='#999999' valign='middle'><td class='textTitle'><center>$la_all_links_valid</center></td></tr>";
	}
}

function checkdead(){
	global $conn, $start, $query_ids, $check_redirect, $la_button_done , $la_button_cancel, $la_button_view, $urls, $sid, $session_get;

	if($sid && $session_get)
		$att_sid="sid=$sid&";

	$query="Select max(link_id) as count from inl_links";
	$rs = &$conn->Execute($query);
	
	if($rs && !$rs->EOF)
		$total=$rs->fields[0];
	else
		$total=0;

	settype($start,"integer");

	$query="Select link_id, link_url from inl_links order by link_id";
	$rs = &$conn->SelectLimit($query,5,$start);

	if($start)
		$start+=5;
	else
		$start=5;

	while($rs && !$rs->EOF) 
	{	$link_url = $rs->fields[1];
		if($rs->fields[1] =="http://" || $rs->fields[1]=="")
		{
			if($urls=="urls")
				$fail=1;
			else
				$fail=0;
		}
		else
		{	
			if (url_valid($rs->fields[1]))
				$fail=0;
			else
				$fail=1;
		}
		if($fail==1){
			$query_ids.=",".$rs->fields[0];
		}
		$cur=$rs->fields[0];
		$rs->MoveNext();
	}
	if($total==$cur || $total==0){$check_done="<font color='#A0A0E0'><b><center>100%</center></b></font>
			  <table border='1' cellspacing='0' width='100%' bordercolor='#C0C0C0'>
       		 <tr>
         			 <td width='100%' bgcolor='#A0A0E0'>&nbsp;</td>
        		</tr>
      	</table><center><input type='button' value='$la_button_done' name='done' class='button'></center>";
		$check_redirect="
			<script language=\"javascript\">
			<!-- 
		
			location.href=\"linksvalidate.php?$att_sid"."display=Display&query_ids=$query_ids\"
		
			//-->
			</script>";	
		return $check_done;
	}
	else{
		$PERCENT=100*$cur/$total;
		$PERCENT=number_format($PERCENT,1);
		$t=100-$PERCENT;
		$p=$PERCENT;
		$t=number_format($t,0);
		$p=number_format($p,0);
		$check_processing= "<div align=\"center\"><font color='#A0A0E0'><b>".$PERCENT."%</b></font></div>
  			<table border='1' cellspacing='0' width='100%' bordercolor='#C0C0C0'>
      	  <tr>
      	    <td width='".$PERCENT."%' bgcolor='#A0A0E0'>&nbsp;</td>
      	    <td width='".$t."%'>&nbsp;</td>
      	  </tr>
      	</table><center><input type='button' value='$la_button_cancel' name='cancel' class='button' onClick=location.href=\"pending.php\"> <input type='button' value='$la_button_view' name='view' class='button' onClick=location.href=\"linksvalidate.php?$att_sid"."display=Display&query_ids=$query_ids\"></center>";
		global $validate_redirect;
		$check_redirect="
			<script language=\"javascript\">
			<!-- 
		
			location.href=\"linksvalidate.php?$att_sid"."start=$start&query_ids=$query_ids&urls=$urls\"
		
			//-->
			</script>";
		return $check_processing;
	}

	
}

function url_valid($url)
{
	if(!ereg("^http://",$url))
		$url="http://".$url;

	$urlArray = parse_url($url);
   
   if (!$urlArray[port]) { 
		if ($urlArray[scheme] == 'http') { $urlArray[port] = 80; } 
		elseif ($urlArray[scheme] == 'https') { $urlArray[port] = 443; } 
		elseif ($urlArray[scheme] == 'ftp') { $urlArray[port] = 21; } 
	} 

	if (!$urlArray[path]) { $urlArray[path] = '/'; }

	$errno="";
	$errstr="";
	$fp = @fsockopen ($urlArray[host].'.', $urlArray[port], &$errno, &$errstr , 10);
	
	$sstatus = "OK";  

	if (!$fp)
		return false;

	$req=sprintf( "HEAD %s HTTP/1.0\r\nHost: %s\r\n\r\n", $urlArray[path], $urlArray[host]);
	fputs( $fp, $req ); 
		
	while (!feof($fp)) 
	{ 
		$line = fgets($fp,1000); 

		if( eregi( "HTTP/1.(.) ([0-9]*) (.*)", $line, $parts ) ) 
		{ 
			if( $parts[2] < "400" ) 
			{
				fclose($fp);
				return true;
			}
			else
			{
				fclose($fp);
				return false;
			}
		} 				
	}

	fclose($fp);
	return false;
}

function pagenavdead($cat, $query, $file, $start, $ar, $total) {
	global $conn, $pagenav, $lim, $la_go_to_page, $sid, $session_get;

	if($sid && $session_get)
		$att_sid="sid=$sid&";
	$pagenav = "";	
	if (!$start) {
		$start = "0";
  	}
	if ($ar) {
		$more ="";
		while (list ($varname, $varval) = each ($ar)) {
			$more .= "&$varname=$varval";
		}

		if (($id)&&($type)) {$more = "&id=$id&type=$type";}
	}

	$result = &$conn->Execute($query);
	if ($total > $lim) {
	  	$pagenav = $la_go_to_page;
		if ($start >=(10 * $lim)) {
			$startpage = floor($start/$lim);
			if($startpage){$st2 = $startpage * $lim-9*$lim;$pagenav .= "<a href=\"$file.php?$att_sid"."cat=$cat&start=$st2$more\"><<</a>";}
		}
		if ($startpage) {
			$num = $startpage * $lim;
			$pagenum = $startpage;
		} else {
			$num = "0";
  			$pagenum = "1";
		}
		$pagelinknum = 1;
		if ($total > $lim) {
    			while (($num < $total) && ($pagelinknum <= 10)) {
      			$endnum = $num + $lim;
      			if ($num == $start) {
      				$pagenav .= "$pagenum  ";
      			} else {
      			$pagenav .= "<a href=\"$file.php?$att_sid"."cat=$cat&start=$num$more\">$pagenum</a>  ";
      			}
      			$num = $num + $lim;
      			$pagenum++;
				$pagelinknum++;
      		}
			if (($pagelinknum > 10) && ($num < $total)) {
				#$startpage = floor($start/$lim);
				#$st2 = ($startpage + 10) * $lim;
				$pagenav .= "<a href=\"$file.php?$att_sid"."cat=$cat&start=$num$more\">>></a>";
			}
		}
	}
}

?>