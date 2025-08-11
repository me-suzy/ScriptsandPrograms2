<?PHP
/************************************************************************/
/* BCWB: Business Card Web Builder                                      */
/* ============================================                         */
/*                                                                      */
/* 	The author of this program code:                                    */
/*  Dmitry Sheiko (sheiko@cmsdevelopment.com)	                    	*/
/* 	Copyright by Dmitry Sheiko											*/
/* 	http://bcwb.cmsdevelopment.com     			                        */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

?>
<html>
 <head>
  <title><?=$lang["Template_select"]?>: <?=$this->xslt_filename?></title>
  
	<LINK REL="stylesheet" TYPE="text/css" HREF="<?=$GLOBALS["http_path"]?>system/default.css.php" TITLE="Style" />
	
	<base href="<?=$GLOBALS["http_path"]?>" />
	</head>
 
<body bgcolor="#F6F7F7" leftmargin="0" topmargin="0" marginwidth="0">

<?PHP
$query_uri = preg_replace("/\?(.*?)$/is", "", $GLOBALS["REQUEST_URI"]);
$query_uri = preg_replace("/^\//is", "", $query_uri);
$vline = '<td bgcolor="#707071"><IMG class="x" SRC="'.$GLOBALS["http_path"].'system/x.gif" WIDTH="1" HEIGHT="1" ALT="" /></td>';

if(!$_GET["report"]) $report = date("Y.m.d").".log"; else $report = $_GET["report"];

// Report's menu
	$d = dir($root_path."log");
	while($entry=$d->read()) {
    if(strlen($entry)>2) $dates_menu .= "<a href=\"".$GLOBALS["http_path"].$query_uri."?action=stats&report=".$entry."\">".$entry."</a><br />\n";
	}
	$d->close();
	
	
      
// Get current report data

	$rows = @file($root_path."log/".$report);

	if($rows) {
		foreach($rows as $row) {
			if($row) {
				
				list($date, $ip, $page, $reffer, $tbrowser, $tos, $tsid) = split("\|", preg_replace("/\t/", "", $row));
				$page = preg_replace("/\?PHPSESSID=(.*?)$/", "\/", $page);
				if(!$tsid) $tsid = $ip;
				if($tsid != $oldsid)
				$table.='
				<tr>
					<td colspan="7" bgcolor="#707071"><IMG SRC="'.$GLOBALS["http_path"].'system/x.gif" WIDTH="1" HEIGHT="1" ALT="" /></td>
				</tr>
				<tr>
					<td style="padding: 0px 5px 0px 0px">'.substr($date, 11).'</td>
					'.$vline.'
					<td style="padding: 0px 5px 0px 5px"><a href="'.$page.'">'.$page.'</a></td>
					'.$vline.'
					<td style="padding: 0px 5px 0px 5px"><a class="text" href="'.$reffer.'">'.$reffer.'</a></td>
					'.$vline.'
					<td style="padding: 0px 5px 0px 5px">'.$ip.', '.$tbrowser.', '.$tos.'</td>
				</tr>
				';
				else 	
				$table.='<tr>
				<tr>
					<td>'.substr($date, 11).'</td>
					'.$vline.'								
					<td style="padding: 0px 5px 0px 5px"><a href="'.$page.'">'.$page.'</a></td>
					'.$vline.'
					<td ></td>
					'.$vline.'
					<td></td>
				</tr>

				</tr>
				';
				
				$oldsid = $tsid;
				
			}
		}
	}

print '
<SCRIPT LANGUAGE="JavaScript">
//<![CDATA[

function helpdesk(pointer)
{
	window.open(\''.$GLOBALS["http_path"].'scripts/helpdesk.xml.php?pointer=\'+pointer, \'displayWindow\',\'width=250,height=200,status=no,toolbar=no,menubar=no, scrollbars=auto, resizable=yes\'); 
	return false;
}

function checkbindfield(el)
{
	if(el.url.value.length==0)  { alert(\''.$lang["NoUrl"].'\'); return false; }
	el.submit();
	return false;
}

function delete_item()
{
	if(confirm(\''.$lang["You_are_sure"].'?\'))
		location.href=\''.$GLOBALS["http_path"].$query_uri.'?action=delete\';

		return false;
}

//]]>
</SCRIPT>

<form action="'.$GLOBALS["http_path"].$query_uri.'" method="post" name="bcwb_form" style="	padding-top : 0px; padding-bottom : 0px; margin-top : 0px; margin-bottom : 0px;">
<input type="hidden" name="OK" value="1" />	


<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#D9D9D9">
	<tr>	
		<td onclick="return helpdesk(\'Info\')" style="CURSOR: hand"><IMG SRC="'.$GLOBALS["http_path"].'system/v2_logo.gif" WIDTH="69" HEIGHT="55" ALT="" /></td>
		<td><IMG SRC="'.$GLOBALS["http_path"].'system/v2_verline.gif" WIDTH="4" HEIGHT="55" ALT="" /></td>
		<td width="100%">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#D9D9D9">
				<tr>
					<td><IMG class="x" SRC="'.$GLOBALS["http_path"].'system/x.gif" WIDTH="1" HEIGHT="30" ALT="" /></td>
					'.btn($GLOBALS["http_path"].$query_uri, $lang["Cancel"]).'
	<td><IMG SRC="'.$GLOBALS["http_path"].'system/v2_bgtpc" WIDTH="4" HEIGHT="30" ALT="" /></td>
	'.btn($GLOBALS["http_path"].$query_uri.'?action=tree', $lang["Structure"]).'	
	'.btn($GLOBALS["http_path"].$query_uri.'?action=stats', $lang["Statistic"]).'
	<td><IMG SRC="'.$GLOBALS["http_path"].'system/v2_bgtpc" WIDTH="4" HEIGHT="30" ALT="" /></td>	
	'.btn($GLOBALS["http_path"].'logout/admin/', $lang["Logout"]).'
					<td width="100%"><IMG class="x" SRC="'.$GLOBALS["http_path"].'system/x.gif" WIDTH="1" HEIGHT="30" ALT="" /></td>
				</tr>
			</table><table width="100%" border="0" cellspacing="0" cellpadding="0" background="'.$GLOBALS["http_path"].'system/v2_bgdw">
				<tr>
					<td><a href="#"  onclick="return helpdesk(\'Template\')"><IMG SRC="'.$GLOBALS["http_path"].'system/v2_help.gif" WIDTH="27" HEIGHT="23" BORDER="0" ALT="Context help: '.$lang["Template_select"].'" /></a></td>
					<td nowrap="nowrap" class="adminarea">'.$lang["Template_select"].': <a class="admin_com">'.$this->xslt_filename.'</a>&#xA0;&#xA0;</td>
					
					<td><IMG SRC="'.$GLOBALS["http_path"].'system/v2_bgdwc" WIDTH="4" HEIGHT="25" ALT="" /></td>


					<td width="100%">&#xA0;</td>
				</tr>
			</table>	
	
		</td>
	</tr>
	<tr>	
		<td colspan="3" bgcolor="#B4B4B4"><IMG class="x" SRC="'.$GLOBALS["http_path"].'system/x.gif" WIDTH="1" HEIGHT="1" ALT="" /></td>
	</tr>
	
	<tr>	
		<td colspan="3" bgcolor="#F6F7F7">

<table width="100%"  border="0" cellspacing="0" cellpadding="10">
	<tr>	
		<td nowrap="nowrap" valign="top">
		<h1>'.$lang["Reports"].'</h1>
		'.$dates_menu.'
		</td>
		<td width="100%" class="adminarea">
		'.$lang["Date"].': <span class="bold">'.preg_replace("/\.log$/is", "",$report).'</a><br /><br />
		<IMG class="x" SRC="'.$GLOBALS["http_path"].'system/x.gif" WIDTH="1" HEIGHT="5" ALT="" /><br />
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="adminarea" width="5%">'.$lang["Time"].'</td>
					'.$vline.'
					<td class="adminarea" style="padding-left: 5px" width="40%">'.$lang["Page"].'</td>
					'.$vline.'
					<td class="adminarea" style="padding-left: 5px"  width="40%">'.$lang["Reffer"].'</td>
					'.$vline.'
					<td class="adminarea" style="padding-left: 5px"  width="15%">'.$lang["Visitor"].'</td>
				</tr>
		'.$table.'
		</table>
		</td>
	</tr>
</table>
		</td>
	</tr>
</table>

</form>

 	</body>
 </html>
';