<?
$auth=true;
$toptab=array("3","11");
$seltoptab="11";
require("../../includes/includes.inc.php");
$pagetitle="Changes overview";
$jetstream_nav = array (
		"2.1"		=>  array($jetstream_url . $thisfile => "Overview"),//2.1 general overview
);
$tabs[]="2.1";
authenticate($uid);
function listrecords($error='', $blurbtype='notify'){
	generallist($error, $blurbtype);
};


// First segment is header of informarmation section and Title of link (help, general instructions)
// Other segments a are seen if section is folded out
// Help under list
$hul=array(	'Help'=>'General information',
						'Editorial overview'=>'This is the startup screen after a succesful login. This screen contains the latest information about items and tasks. It shows all items you\'ve saved or submitted for approval. Editors and administrators have a general overview for aproving or declining items.',
);

function generallist($error='', $blurbtype='notify') {
	global $records, $tablename, $primarykey, $jetstream_url, $jetstream_nav, $thisfile, $generalconfig, $container_id, $status, $userright,  $autologoutpopup, $hul;
	$span=5;
	$ruler = '<tr><td colspan="'.$span.'" class="ruler"></td></tr>';
	$ruler2 = '<tr><td colspan="'.$span.'" class="ruler2"></td></tr>';

	$tabs[]="2.1";
	jetstream_ShowSections($tabs, $jetstream_nav, "2.1");
	errorbox ($error, $blurbtype);
	
	echo "\n<table border=0 width=100% cellspacing=\"0\" cellpadding=\"4\">";
	echo $ruler;
	echo "\n<tr bgcolor=\"#F6F6F6\">";
	echo "\n<td class='tab-g'>Status</td>";
	echo "\n<td class='tab-g'>Type</td>";
	echo "\n<td class='tab-g'>Page</td>";
	echo "\n<td class='tab-g'>Author</td>";
	echo "\n<td class='tab-g' width=80%>Created/ Updated</td>";
	echo "\n</tr>";
	echo "\n</tr>";

	$monthago=date("Y-m-d",time()-(60*60*24*30*3));
	$q = "SELECT struct.*, user.*, unix_timestamp(struct.stamp) AS stampp, container.*, struct.id AS struct_id FROM struct, user, container
	WHERE struct.container_id=container.id
	AND	struct.u_id=user.uid
	AND struct.status<>'waiting'
	AND struct.status<>'saved'
	AND container.generalview='1'
	AND struct.stamp>'".$monthago."'
	ORDER BY struct.stamp DESC limit 0, 20";

	$r = mysql_prefix_query($q) or die (mysql_error());
	$n = mysql_numrows($r);
	$printedcat=0;
	$lastcat="0";
	while ($structarray = @mysql_fetch_array($r) ) {
		if($lastcat<>$structarray["cfile"]){
			$cat= '<tr><td colspan=5 style="PADDING-RIGHT: 0px; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; PADDING-TOP: 0px" bgcolor="#8C8A8C" height=1></td></tr>';
			$cat.="\n<tr bgcolor=\"#ffffff\"><td colspan=6  background='images/stab-bg.gif'><img src='images/caret-rs.gif' width='11' height='7'>\n<font color=\"\"><b><a href='" . $jetstream_url . $structarray["cfile"] . "'>".$structarray["cname"]."</a></b></font></td>\n";
			$cat.= "</tr>";
			$cat.= "\n<tr><td width='20' valign='top'></td><td width='140'></td></tr>";
			$lastcat=$structarray["cfile"];
			//echo $cat;
		}
		echo $ruler;
		$rightsokay=0;
		//check de status
		echo "\n<tr bgcolor=\"#ffffff\">";
		if ($structarray["status"]<>'beingedited' && $structarray["status"]<>'beingedited'){
			//check content owner;
			if (($structarray["status"]<>'published' && $structarray["status"]<>'waiting')){
				if($userright<>'author' || ($structarray["u_id"]==$_SESSION["uid"])){
					$rightsokay=1;
				}
			}
			elseif($userright<>'author'){
				$rightsokay=1;
			}
		}
		//else{
		//	echo "\n<td valign=\"top\" width=5% title=\"You do not have the rights to edit this item.\">[".$structarray["status"]."]</td>";

		//}
		//if ($rightsokay=='1'){
		//	echo "\n<td valign=\"top\" width=5% title=\"You may edit this item.\" nowrap> [".$structarray["status"]."]</td>";
		//}

		$rightsokay=='1' ? print("<td valign=\"top\" width=\"1%\" title=\"".strtolower($structarray["status"])." - You may edit this item.\" ".$sorter_display_class." nowrap=\"nowrap\"  style=\"padding:3px 2px 1px 2px\"><img src=\"images/workflow_".strtolower($structarray["status"]).".gif\"/></td>") : print("<td valign=\"top\" width=1% title=\"You do not have the rights to edit this item.\" ".$sorter_display_class." nowrap=\"nowrap\" style=\"padding:3px 2px 1px 2px\"><img src=\"images/workflow_".strtolower($structarray["status"]).".gif\"/></td>");
		
		echo "\n<td valign=\"top\" width=5% nowrap>".$structarray["cname"]."</td>";
		//mag je deze dan verwijderen, of aanpassen, of saven for approval etc.
		//interface hierop aanpassen
		if($rightsokay==1){
			echo "\n<td valign=\"top\" nowrap><a class=\"link\" href=\"". $jetstream_url . $structarray["cfile"]."?task=editrecord&structid=".$structarray["struct_id"] . "\">";
			echo ($structarray["systemtitle"]<>'')?  stripslashes($structarray["systemtitle"]) :  'Title';
			echo "</a></td>\n";
		}
		else{
				echo "\n<td valign=\"top\"><font color=\"aaaaaa\">" . ($structarray["systemtitle"]<>'')? $structarray["systemtitle"] : "Title". "</font></td>\n";
		}
		echo "\n<td valign=\"top\" width=5% nowrap>".$structarray["display_name"]."</td>";
		
		echo "\n<td valign=\"top\">" . date("d-m-Y, H:i", $structarray["stampp"])."</td>";
		echo "\n</tr>";
	}
	echo $ruler;
	echo help_section($hul, $ruler, $ruler2, $span);
	echo "\n</table>\n";
}
jetstream_footer();