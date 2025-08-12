<?

if(isset($_REQUEST["nomenu"])){
	$nomenu=$_REQUEST["nomenu"];
}
$thisfile="/index.php";
//title of the administration page
require ("../../includes/includes.inc.php");
//array configuration
$jetstream_nav = array (
		"2.1"		=>  array($jetstream_url . $thisfile => "Overview"),		//2.1 general overview
		"2.4"		=>  array($jetstream_url ."/index.php?task=editprefs&container_id=".$container_id => "Preferences"),//2.4 preferences

);

// First segment is header of informarmation section and Title of link (help, general instructions)
// Other segments a are seen if section is folded out
// Help under list
$hul=array(	'Help'=>'General information',
						'Editorial overview'=>'This is the startup screen after a succesful login. This screen contains the latest information about items and tasks. It shows all items you\'ve saved or submitted for approval. Editors and administrators have a general overview for aproving or declining items.',
);

function generallist($error='', $blurbtype='notify') {
	global $records, $tablename, $primarykey, $jetstream_url, $jetstream_nav, $thisfile, $generalconfig, $container_id, $status, $userright,  $autologoutpopup, $hul;
	$span=4;
	$ruler = '<tr><td colspan="'.$span.'" class="ruler"></td></tr>';
	$ruler2 = '<tr><td colspan="'.$span.'" class="ruler2"></td></tr>';

	if ($_REQUEST["timeout"]) {
		?>
		<script language='JavaScript'>
		<!--
		parent.sh();
		parent.myCountdown[0].eventDate = <?echo $autologoutpopup;?>;
		parent.myCountdown[0].expired = false;
		parent.setTimeout("doCountdown()", 1000);
		//window.close();
		//alert(window.frameElement);
		
		// -->
		</script>
		<?
	}
	else{
		$tabs[]="2.1";
		jetstream_ShowSections($tabs, $jetstream_nav, "2.1");
		errorbox ($error, $blurbtype);
		echo "\n<table border=0 width=100% cellspacing=\"0\" cellpadding=\"4\">";
		echo $ruler;
		echo "\n<tr bgcolor=\"#F6F6F6\">";
		echo "\n<td class='tab-g'>Status</td>";
		echo "\n<td class='tab-g'>Page</td>";
		echo "\n<td class='tab-g'>Author</td>";
		echo "\n<td class='tab-g' width=\"80%\">Last updated</td>";
		echo "\n</tr>";

		if ($_SESSION["user_type"] == "administrator") {
			$res = mysql_prefix_query("SELECT container.* FROM container WHERE container.generalview='1' ORDER BY container.corder ASC") or die(mysql_error());
		}
		else{
			$res = mysql_prefix_query("SELECT  userrights.*, container.* FROM userrights, container WHERE container.id=userrights.container_id AND userrights.uid=".$_SESSION["uid"]." AND container.generalview='1' ORDER BY container.corder ASC") or die(mysql_error());
			mysql_num_rows($res);
		}
		while($navarray=mysql_fetch_array($res)){
			//echo $navarray["type"];
			if ($navarray["type"]=='administrator' || $_SESSION["user_type"]=='administrator'){
				$q = "SELECT struct.*, user.*, unix_timestamp(struct.stamp) as stampp FROM struct, user WHERE struct.container_id='".$navarray["id"]."' AND struct.u_id=user.uid AND struct.status<>'archive' AND ((struct.status<>'published' AND struct.status<>'saved' AND struct.status<>'declined') OR (user.uid='".$_SESSION["uid"]."' AND struct.status<>'published')) ORDER BY struct.stamp DESC";
			}
			elseif($navarray["type"]=='editor'){
				$q = "SELECT struct.*, user.*, unix_timestamp(struct.stamp) as stampp FROM struct, user WHERE struct.container_id='".$navarray["id"]."' AND struct.u_id=user.uid AND struct.status<>'archive' AND ((struct.status<>'published' AND struct.status<>'saved' AND struct.status<>'declined') OR (user.uid='".$_SESSION["uid"]."' AND struct.status<>'published')) ORDER BY struct.stamp DESC";
			}
			elseif($navarray["type"]=='author'){
				$q = "SELECT struct.*, user.*, unix_timestamp(struct.stamp) as stampp FROM struct, user WHERE struct.container_id='".$navarray["id"]."' AND struct.u_id=user.uid AND struct.status<>'published' AND struct.status<>'waiting' AND struct.status<>'archive' AND user.uid='".$_SESSION["uid"]."' ORDER BY struct.stamp DESC";
			}
			$r = mysql_prefix_query($q);
			$printedcat=0;
			$cat= $ruler2;
			$cat.="\n<tr bgcolor=\"#ffffff\"><td colspan=6  background='images/stab-bg.gif'><img src='images/caret-rs.gif' width='11' height='7'>\n<font color=\"\"><b><a href='" . $jetstream_url . $navarray["cfile"] . "'>".$navarray["cname"]."</a></b></font></td>\n";
			$cat.= "</tr>";
			//$cat.= "\n<tr><td width='20' valign='top'></td><td width='140'></td></tr>";
			while ( $structarray = @mysql_fetch_array($r) ) {
				if ($printedcat==0){
					echo $cat;
					$printedcat=1;
				}
				echo $ruler;
				$rightsokay=0;
				//check de status
				echo "\n<tr>";
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

//				}
	//			if ($rightsokay=='1'){
		//			echo "\n<td valign=\"top\" width=5% title=\"You may edit this item.\" nowrap> [".$structarray["status"]."]</td>";
			//	}
				$rightsokay=='1' ? print("<td valign=\"top\" width=\"1%\" title=\"".strtolower($structarray["status"])." - You may edit this item.\" ".$sorter_display_class." nowrap=\"nowrap\"  style=\"padding:3px 2px 1px 2px\"><img src=\"images/workflow_".strtolower($structarray["status"]).".gif\"/></td>") : print("<td valign=\"top\" width=1% title=\"You do not have the rights to edit this item.\" ".$sorter_display_class." nowrap=\"nowrap\" style=\"padding:3px 2px 1px 2px\"><img src=\"images/workflow_".strtolower($structarray["status"]).".gif\"/></td>");

				//mag je deze dan verwijderen, of aanpassen, of saven for approval etc.
				//interface hierop aanpassen
				if($rightsokay==1){
					echo "\n<td valign=\"top\" nowrap><a class=\"link\" href=\"". $jetstream_url . $navarray["cfile"]."?task=editrecord&structid=".$structarray["id"] . "\">";
					echo ($structarray["systemtitle"]<>'')?  stripslashes($structarray["systemtitle"]) :  'Title';
					echo "</a></td>\n";
				}
				else{
						echo "\n<td valign=\"top\" nowrap><font color=\"aaaaaa\">" . ($structarray["systemtitle"]<>'')? $structarray["systemtitle"] : "Title". "</font></td>\n";
				}
				echo "\n<td valign=\"top\" width=5% nowrap>".$structarray["display_name"]."</td>";
				
				echo "\n<td valign=\"top\" nowrap>" . date("D d-m-Y, H:i", $structarray["stampp"])."</td>";
				echo "\n</tr>";
				
				if($structarray["comment"]<>''){
					echo "<tr><td class=\"\" colspan=\"4\"><font color=\"777777\">".nl2br(htmlspecialchars(substr(stripslashes($structarray["comment"]),0,100)))."<font></td></tr>";
					//echo $struct_c;
				}
			
			}

		}
	
		echo $ruler;
		echo help_section($hul, $ruler, $ruler2, $span);
		echo "\n</table>\n";
	}
}


if ($_GET["task"] == "logout") {
	session_start();
	logout($_SESSION["uid"]);
	$toptab=array("6");
	$seltoptab="6";
	if ($_GET["timeout"]=='true') {
		$pagetitle="Login to continue";
		jetstream_header("Login to continue", false);
		login_screen("popup");	    
	}
	else{
		jetstream_header("Logged out", false);
		login_screen("ended");
	}
}
elseif ($_GET["task"]=='sendpw'){
	jetstream_header("Send password", false);
	$jetstream_nav = array (
		"2.1"		=>  array($jetstream_url . $thisfile."?login=".$_REQUEST["login"] => "Log in"),		//2.1 general overview
		"2.2"		=>  array($jetstream_url . $thisfile => "Password sent"),		//2.1 general overview
	);
	$tabs[]="2.2";
	$tabs[]="2.1";
	jetstream_ShowSections($tabs, $jetstream_nav, "2.2");
	if ($_REQUEST['login']){
		$mailr = mysql_prefix_query("SELECT display_name, email, user_password  FROM user WHERE login='".$_REQUEST["login"]."'  AND active=1")or die (mysql_error());
		if ($marray=mysql_fetch_array($mailr)){
			$to = $marray["email"];
			$subject= "Jetstream request for password";
			$mailbody = "Jetstream request for password on " . date('F jS, Y') . "\r\n\r\n";
			$mailbody .="login: ". $_REQUEST["login"]."\r\n";
			$mailbody .="password: ". $marray["user_password"]."\r\n";
			$mail_header = "From: \"" . $admin_name ."\" <" . $generic_email . ">\n";
			$mail_status = mail($marray["email"], $subject, $mailbody, $mail_header);
		}

	}
	$annotation="Username or password incorrect.";
	login_form($annotation);
}
else{
	authenticate();
}

function listrecords($error='', $blurbtype='notify'){
	generallist();
};

jetstream_footer();