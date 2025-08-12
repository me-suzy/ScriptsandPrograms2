<?
//displays the actual login form
function login_form($login, $login_password, $annotation, $task, $error=''){
	global $site_title, $jetstream_url, $absolutepathfull, $absolutepath, $thisfile;
	?>
	<SCRIPT LANGUAGE="JavaScript" type="text/javascript">
	<!--
	function login_focus()
	{
		if (document.login.login.value == '')
			document.login.login.focus();
		else
			document.login.login_password.focus();
	}
	//-->
	</SCRIPT>
	<br>
	<form name='login' method='post' action='<? echo $thisfile; ?>'>
	<TABLE border="0" width="100%" cellpadding="4" cellspacing="0">
		<?
		if($annotation!=''){
			echo "<tr><td colspan=3>". $annotation."</td></tr>";
		}
		?>

	<tr bgcolor="#ffffff"><td background='images/stab-bg.gif' class="infoBody" nowrap><b>Mandatory field</b></td><td style="background-color: #202D4C;" width="1"></td><td background='images/stab-bg.gif'></td></tr>
	<tr bgcolor="#ffffff"><td background='images/stab-bg.gif'></td></tr>
	<tr><td colspan="5" class="ruler"></td></tr>
	<tr><td nowrap>Gebruikersnaam&nbsp;&nbsp;&nbsp;</td><td style="background-color: #202D4C;" width="1"></td><td width="90%"><input class="form_input" type="text" name="login" value="" size="30" maxlength="255"></td></tr>
	<tr><td colspan="3" class="ruler"></td></tr>
	<tr><td nowrap>Wachtwoord</td><td style="background-color: #202D4C;" width="1"></td><td width="90%"><input class="form_input" type="password" name="login_password" value="" size="30" maxlength="255"></td></tr>
	<tr><td colspan="3" class="ruler"></td></tr><tr><td nowrap></td><td bgcolor="#ffffff" width="1"></td><td width="90%"><input type='submit' value='Login'></td></tr>
	<?
	if($_REQUEST["login"]<>''){
		?>
		<tr><td colspan=3><b>Ik ben mijn wachtoord vergeten voor: <?echo $_REQUEST["login"]?></b></span><br><span class='install'><a href="<? echo $absolutepathfull;?>view/webuser/task/sendpw/login/<?echo $_REQUEST["login"]?>">E-mail mijn wachtwoord.</a><br>Deze e-mail wordt naar het bijbehorend  e-mailadres van dit account gestuurd.</span></td></tr>
		<?
	}
	?>

	</table>
	</form>
	<script language='JavaScript' type="text/javascript">
	<!--
		login_focus();
	// -->
	</script>
	<?
}

//displays the login screen, invoced from auth.inc.php
function login_screen($screentype=''){
	global $site_title, $jetstream_url, $task, $annotation;
	if ($screentype=="expire"){
		$annotation="Geef gebruikersnaam of wachtwoord op.";
	}
	elseif($screentype=="ended"){
		$annotation=" ";
	}
	login_form($login, $login_password, $annotation, $task);
}

//loggedin function with workflow support
function loggedin_workflow($uid=''){
	global $userright, $currentstatus, $primarykey, $$primarykey, $status, $generallanguage, $wf, $thisfile, $absolutepath, $edititemoption,$floodstop,$floodstop_time;
	general_date_process();
	if($primarykey=='uid'){
		$_REQUEST[$primarykey]=$_SESSION["uid"];
	}

	if ($_SESSION["uid"]) {
		switch($_REQUEST["task"]) {
			case 'editrecord'					: if($edititemoption<>false){general_form('edit', '', ''); }else{listrecords();}break;
			case 'updateedit'					: if($edititemoption<>false){general_form('edit', '', ''); }else{listrecords();}break;
			case 'updatereedit'				: if($edititemoption<>false){general_form('edit', '', ''); }else{listrecords();}break;
			case 'createrecord'				: general_form('create', '', ''); break;
			case 'updatecreate'				: general_process('create'); break;
			case 'updaterecreate'			: general_process('create'); break;
			default :listrecords();
			break;
		}
	}
	else{
		switch($_REQUEST["task"]) {
			case 'createrecord'				: general_form('create', '', ''); break;
			case 'updatecreate'				: general_process('create'); break;
			case 'updaterecreate'			: general_process('create'); break;
			default		: general_form('create', '', ''); break;
		}
	}
}

//deletes record with wordkflow support
//deletes records from struct table
function general_deleterecord() {
	global $tablename, $primarykey, $container_id, $wf ;
	global $basedir, $fileupload, $filefield, $filenamefield, $storemethod, $BASE_URL, $BASE_ROOT;
	if ($fileupload==true && $storemethod=='hd') {
		$r = mysql_prefix_query("SELECT $filenamefield from $tablename WHERE $primarykey ='" . $_REQUEST[$primarykey] ."'");
		if ($array=mysql_fetch_array($r)) {
			$todeletefilename=$array[$filenamefield];
		}
	}
	$q = "DELETE from $tablename WHERE $primarykey ='" . $_REQUEST[$primarykey] ."'";
	$r = mysql_prefix_query($q);
	$error1 = mysql_error();
	if ($wf){
		$q = "DELETE from struct WHERE content_id ='" . $_REQUEST[$primarykey] ."' AND container_id='".$container_id."'";
		$r = mysql_prefix_query($q);
		$error2 = mysql_error();
	}
	if ($error) {
		listrecords("\nError deleting: $error1 <br> $error2", "error");
	}
	else {
		if ($fileupload==true) {
			if ($BASE_ROOT<>'') {
		    $basedir=$_SERVER['DOCUMENT_ROOT']."/".$BASE_ROOT;
			}
			$full_localfilename = "$basedir/$todeletefilename";
			if (file_exists($full_localfilename)) {
				unlink($full_localfilename);			    
			}
		}
		listrecords("\nDeletion completed.", "notify");
	}
}

//display the form elements
function general_form($action, $error='', $blurbtype='error') {
	global $records, $tablename, $primarykey, $dropdown, $jetstream_url, $jetstream_nav, $thisfile, $sessid, $sesscode, $userright, $status, $wf;
	global $container_id, $generalconfig, $fileupload, $fileuploaduserrestricted, $noruler;
	global $overviewitemoption, $addnewitemoption, $deleteitemoption, $generallanguage, $templateenabled, $archiveenabled;
	global $userowneditemsonly;
	global $help_under_form;
	global $changeparentenabled;
	global $huf;
	global $_SETTINGS;
	if (function_exists("on_before_form")) {
		$error2=on_before_form();
		if ($error2<>'') {
			listrecords($error2, 'error');
			return false;
		}
	}

	$bgcolor="#F6F6F6";
	$span=5;
	$ruler = '<tr><td colspan="'.$span.'" class="ruler"></td></tr>';
	$ruler2 = '<tr><td colspan="'.$span.'" class="ruler2"></td></tr>';

	if ($action == 'edit') {
		$q="SELECT * FROM $tablename WHERE $primarykey='" . $_REQUEST[$primarykey] ."'";
		if ($userowneditemsonly==true) {
	    $actionisokay='no';
			$q.=" AND uid='".$_SESSION["uid"]."'";
		}
		$result=mysql_prefix_query($q);
		if (mysql_num_rows($result)>'0') {
			$formrarray= mysql_fetch_array($result);		    
			if ($templateenabled==true) {
				$_REQUEST["t_id"]=$formrarray["t_id"];
				$formrarray = array_merge($formrarray,unserialize($formrarray["content"]));
			}
		}
		else{
			listrecords($generallanguage["norights"]);
			return false;
		}
	}
	elseif ($action == 'changetemplate') {
		$changetemplate=true;
		$action="edit";
		$q="SELECT * FROM $tablename WHERE $primarykey='" . $_REQUEST[$primarykey] ."'";
		if ($userowneditemsonly==true) {
	    $actionisokay='no';
			$q.=" AND uid='".$_SESSION["uid"]."'";
		}
		$result=mysql_prefix_query($q);
		if (mysql_num_rows($result)>'0') {
			$formrarray= mysql_fetch_array($result);		    
			$old_template=$formrarray["t_id"];
			$formrarray = array_merge($formrarray,unserialize($formrarray["content"]));
		}
		else{
			listrecords($generallanguage["norights"]);
			return false;
		}
	}

	if ($templateenabled==true) {
		reset($records);
		$qq="SELECT * FROM opentempl WHERE id='".$_REQUEST["t_id"]."'";
		$tresult = mysql_prefix_query($qq);
		$tarray = mysql_fetch_array($tresult);
		$newtemplaterecords=unserialize(stripslashes($tarray["t_data"]));
		$records = array_merge($records,$newtemplaterecords);
		// hier uit de array content unserializen
	}
	if ($overviewitemoption==true) {
		$tabs[]="2.1";
	}
	if ($addnewitemoption==true) {
		$selectedtab="2.2";
	}
	if ($action=="edit" || $action=="reedit"){
		$tabs[].="2.3";
		$selectedtab="2.3";
	}
	if ($addnewitemoption==true  && $action<>"reedit"  && $changetemplate<>true) {
		$tabs[].="2.2";
	}

	if ($wf==true && $archiveenabled<>false) {
		$tabs[].="2.4";	    
	}
	if ($changeparentenabled==true &&
		$_REQUEST["task"]<>'createrecord' &&
		$_REQUEST["task"]<>'updatecreate' &&
		$_REQUEST["task"]<>'updaterecreate' && $action<>"reedit"  && $changetemplate<>true) {
		$tabs[].="2.5";	    		    
	}
	//jetstream_ShowSections($tabs, $jetstream_nav, $selectedtab);
	errorbox ($error, $blurbtype);
	//echo $_SETTINGS["current_process"]["before_form"];
	$formdisable= "if (isOpen()){ hWnd.close();}";
	if ($fileupload==true) {
		$formdisable.= "this.elements['statusactionsave'].disabled='disabled';";
	}
	//$formdisable.="";
	//echo $_SESSION["browser"];
	//echo $_SESSION["maj_ver"];
	
	if ($_SESSION["browser"]=="ie") {
		$rte_path="htmlarea/";
		if ($_SESSION["maj_ver"]>"5") {
			$richtext=true;
			$insimage=true;
		}
		elseif ($_SESSION["maj_ver"]=="5") {
			if ($_SESSION["min_ver"]=='.0') {
				$richtext=false;
			}
			else{
				$richtext=true;
				$insimage=true;
			}
		}
	}
	elseif($_SESSION["browser"]=="mz"){
		$richtext=true;
	}

	if ($_SETTINGS["set_multiselect"]==true){
		$multi_select_action="selectAllOptions(MM_findObj('list2[]'));";
	}
	echo "<FORM ACTION=\"". $thisfile. "\" METHOD=\"POST\" name=\"mainform\" onsubmit=\"".$multi_select_action."\">";
	echo "<TABLE border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">";
	if ($wf){
		if($status==''){
			$status='new';
		}
	}
	elseif ($action<>'create' && $action<>'recreate') {
		$status='edit';
	}
	else{
		$status='new';
	}
	$requiredcolor="202D4C";
	//echo $ruler2;

	echo "<tr bgcolor=\"#ffffff\"><td background='images/stab-bg.gif' class=\"infoBody\" nowrap><b>Mandatory field</b></td><td style=\"background-color:#".$requiredcolor."\" width=\"1\"></td><td background='images/stab-bg.gif'></td></tr>";
	//echo $ruler2;
	echo "<tr bgcolor=\"#ffffff\"><td background='images/stab-bg.gif'></td></tr>";
	echo $ruler;

	//echo $ruler;
	reset($records);
	while (list($var, $val) = each($records)) {
		general_formelement($val, $formrarray, $action);
	}
	echo "<tr><td><br /></td></tr>";
	if ($changetemplate==true) {
		$tarray = mysql_fetch_array(mysql_prefix_query("SELECT * FROM opentempl WHERE id='".$old_template."'"));
		$oldtemplaterecords=unserialize(stripslashes($tarray["t_data"]));
		while (list($var, $val) = each($newtemplaterecords)) {
			$comm[].=$val[0];
		}
		while (list($var, $val) = each($oldtemplaterecords)) {
			if (!in_array($val[0], $comm)) {
				if ($warningprinted<>true) {
					$warningprinted=true;				    
					echo "\n<tr><td><br></td></tr>";
					echo $ruler2;
					echo "\n<tr bgcolor=\"#ffffff\">\n<td colspan=3  background='images/stab-bg.gif'><b>The fields below are not used in the new template.</b></td></tr>";
					echo $ruler;
					echo "\n<tr><td colspan=3>All text or data in these fields will be lost.</td></tr>";
					echo $ruler;
				}
				//echo $val[0]."<br>";
				general_formelement($val, $formrarray, $action);
			}
		}
	}
	if ($wf) {
		echo "<tr><td width=10%><b>Status: ".$status."</b></td><td colspan=2>";	    
	}
	else{
		echo "<tr><td width=10%>&nbsp;</td><td></td><td>";	    
	}
	if ($fileupload==true) {
		?>
   <script language="JavaScript" type="text/JavaScript">
		<!--
		function changeLoadingStatus(state){
			var statusText = null;
			if(state == 'load') {
				statusText = 'Loading Images';	
			}
			else if(state == 'upload') {
				statusText = 'Uploading Files';
			}
			if(statusText != null) {
				var obj = MM_findObj('loadingStatus');
				obj.innerHTML = statusText;
				MM_showHideLayers('loading','','show')		
			}
		}

		//-->
		</script>
		<style type="text/css">
			<!--
			.statusLayer{
				background:#FFFFFF;
				border: 1px solid #CCCCCC;
			}
			.statusText {
				font-family: Verdana, Arial, Helvetica, sans-serif;
				font-size: 15px;
				font-weight: bold;
				color: #6699CC;
				text-decoration: none;
			}
			-->
		</style>
		<div id="loading" style="position:absolute; left:230px; top:200px; width:184px; height:48px; z-index:1; visibility: hidden" class="statusLayer">
			<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td>
				<div align="center"><span id="loadingStatus" class="statusText">__Uploadstatus__</span></div>
				 </td>
				</tr>
			</table>
		</div>
	<?
		if ($status=='new') {
			$buttonvalue='Upload file';
		}
		else{
			$buttonvalue='Save changes';
		}
		echo "<INPUT class=\"form_button\" TYPE=\"SUBMIT\" VALUE=\"".$buttonvalue."\" name=\"statusactionsave\" onClick=\"javascript:changeLoadingStatus('upload');\">";

	}
	else {
		if ($status=='new') {
			if ($wf) {
				$buttonvalue='Store draft';			    
			}
			else{
				$buttonvalue='Submit';			    
			}
		}
		else{
			$buttonvalue='Save changes';
		}
		echo "<INPUT class=\"form_button\" TYPE=\"submit\" VALUE=\"".$buttonvalue."\" name=\"statusactionsave\">";	    
	}
	if ($wf){
		if ($status<>'published' && $status<>'waiting') {
			echo "<INPUT class=\"form_button\" TYPE=\"submit\" VALUE=\"Submit for publishing\" name=\"statusactionsaveforapproval\">";
		}
		if ($userright=='administrator' || $userright=='editor'){
			if ($status<>'published') {
   			echo "<INPUT class=\"form_button\" TYPE=\"submit\" VALUE=\"Publish\" name=\"statusactionapprove\">";
			}
			if ($status=='waiting'){
				echo "<INPUT class=\"form_button\" TYPE=\"submit\" VALUE=\"Decline\" name=\"statusactiondecline\">";
			}
			if ($status<>'archive' && $status<>'new'){
				echo "<INPUT class=\"form_button\" TYPE=\"submit\" VALUE=\"Archive\" name=\"statusactionarchive\">";
			}
		}
	}
	if ($action <> 'create' && $action <> 'recreate' && $deleteitemoption==true) {
		echo "<INPUT class=\"form_button\" TYPE=\"submit\" VALUE=\"Remove\" name=\"deleterecord\" onclick=\"return confirm('Are you sure you want te remove this item?');\">";
	}
	if ($templateenabled==true && $changetemplate<>true && $action <> 'create' && $action <> 'recreate') {
 		echo "\n<INPUT class=\"form_button\" TYPE=\"submit\" VALUE=\"Change template\" name=\"changetemplate\" onclick=\"return confirm('Are you sure you want te change the template?\\nAll changes will be lost.');\">";
	}

	echo "</td></tr>";
	//if ($wf){
	//	generalform_workflow($action);
	//}
	echo "<INPUT TYPE=\"Hidden\" NAME=\"task\" VALUE=\"update".$action."\">";
	//echo help_section($huf, $ruler, $ruler2, $span);
	echo "</TABLE></FORM>";
}

//display the form elements
function general_formelement($val, $formrarray, $action){
	global $records, $tablename, $primarykey, $dropdown, $jetstream_url, $jetstream_nav, $thisfile, $sessid, $sesscode, $userright, $status;
	global $container_id, $generalconfig, $wf, $includes_path, $noruler;
	$value=$val[0];
	$formelement=$val[1];
	$display=$val[2];
	$val[3]=='required' ? $requiredcolor="202D4C" : $requiredcolor="ffffff";
	$span=3;
	$ruler = '<tr><td colspan="'.$span.'" class="ruler"></td></tr>';
	$ruler2 = '<tr><td colspan="'.$span.'" class="ruler2"></td></tr>';

	if ($action == 'reedit' || $action == 'recreate') {
		$cellvalue = $_REQUEST[$value];
		$formrarray[$value] = $_REQUEST[$value];
		//$cellvalue = eregi_replace("<br />","",$cellvalue);
	}
	elseif ($action == 'edit') {
		$cellvalue = $formrarray[$value];
	}
	elseif($action == 'create'){
		$cellvalue = $val[5];
	}
	$cellvalue=htmlspecialchars(stripslashes($cellvalue));
	$noruler=false;
	include ($includes_path."/f_general_formelements.inc.php");
	if ($noruler==false){
		echo $ruler;
	}
}






//displays the default error box at the top of the list or form
function errorbox ($error, $blurbtype,$r='echo'){
	global $absolutepath;
	$msg="<br>";
	$blurbtype=='notify' ? $errorcolor='color="000000"' : $errorcolor='color="ff0000"';
	if($error<>'') {
		$msg.='

		<table cellspacing=2 cellpadding=0 width="100%" border="0">
			<tr> 
				<td><img height=22 alt="" hspace=3 src="'.$absolutepath.'images/dr_pijl.gif" width=22 vspace=3></td>
				<td width="100%"><font color="#A61F1F"><b>Error:</b></font></td>
			</tr>
			<tr> 
				<td colspan=2><img height=10 src="'.$absolutepath.'images/clearpixel.gif" alt="" width=2></td>
			</tr>
			<tr> 
				<td valign=top></td>
				<td valign=top width="100%"> 
					<table width="100%" border="0" cellspacing="2" cellpadding="0">
						<tr>
							<td>'. $error.'</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr> 
				<td valign=top></td>
				<td valign=top width="100%"><br />	</td>
			</tr>
		</table>
		';
	}
	if($r=='echo'){
		echo $msg;
	}
	else{
		return $msg;
	}
}


//this function inserts and updates the records
//inserts and updates struct with workflow support
function general_process($action) {
	global $records, $tablename, $primarykey, $uid, $status, $userright, $display_name, $u_mail, $pagetitle, $jetstream_url, $wf;
	global $container_id, $generalconfig, $mailuid, $front_end_url, $install_dir;
	global $basedir, $fileupload, $filefield, $filenamefield, $storemethod, $BASE_URL, $BASE_ROOT, $fileuploaduserrestricted;
	global $userowneditemsonly, $templateenabled, $parent_field;
	global $replace_from, $replace_to;
	global $includes_path, $upload_base;
	global $order_column, $order_column_order;
	global $connect;

	if (function_exists("on_before_process")) {
		$error2=on_before_process();
		if ($error2<>'') {
			general_form("re".$action, $error2);
			return false;
		}
	}
	//strip all absolute paths from the rich text editor
	//http://host/install_dir/admin/cms/htmlarea/
	while (list($var, $val) = each($_POST)) {
		$_POST[$var]=str_replace($replace_from, $replace_to, $_POST[$var]);
	}

	//echo $action;
	if ($fileuploaduserrestricted==true) {
    $result = mysql_prefix_query("SELECT * FROM user WHERE uid='" . $_SESSION["uid"] ."'");
		$login= @mysql_result($result,0,'login');
		// Change base root to personal directory
		$BASE_ROOT=$BASE_ROOT.'/'.$login;
		//check if path exist and create
		$path=$upload_base.$BASE_ROOT;
		if (!file_exists($path)) {
			mkdir ($path, 0755);
		}
	}
	
	//echo $_POST["deleterecord"];
	if ($action=='move') {
		$q="SELECT * FROM $tablename WHERE ".$primarykey."=".$_REQUEST[$parent_field]." ORDER BY $tablename.position ASC";
		$qr = mysql_prefix_query($q) or die (mysql_error());
		$c = mysql_num_rows($qr);
		if ($c<'1') {
			$_REQUEST[$parent_field]=0;
		}// get all on this level
		$update = "UPDATE $tablename SET ".$parent_field."='".$_REQUEST[$parent_field]."' WHERE ".$primarykey."=".$_REQUEST[$primarykey];
		$r = mysql_prefix_query($update);
		$error = mysql_error();
		if ($error) {
			$errormsg="<P><B>Error moving record: $error</B></P>";
			listrecords($errormsg, 'error');
			return false;
		}
		else{
			listrecords("\nMove completed.", "notify");
		}
	}
	elseif(isset($_POST["changetemplate"])){
		general_select_template("edit");
		return false;
	}
	elseif(isset($_POST["deleterecord"])){
		if ($fileupload==true && $storemethod=='hd') {
			$r = mysql_prefix_query("SELECT $filenamefield from $tablename WHERE $primarykey ='" . $_REQUEST[$primarykey] ."'");
			if (mysql_num_rows($r)>0) {
				$array=mysql_fetch_array($r);
				$todeletefilename=$array[$filenamefield];
			}
		}
		if ($userowneditemsonly==true) {
			$q = "SELECT * FROM $tablename WHERE $primarykey ='" . $_REQUEST[$primarykey] ."' AND uid='".$_SESSION["uid"]."'";
			$r = mysql_prefix_query($q);
			if (mysql_num_rows($r)>'0') {
				$deletionallowed=true;
			}
		}
		if(($userowneditemsonly==true && $deletionallowed==true) || $userowneditemsonly<>true){
			$q = "DELETE from $tablename WHERE $primarykey ='" . $_REQUEST[$primarykey] ."'";
			$r = mysql_prefix_query($q);
			$error1 = mysql_error();
			if ($wf){
				$q = "DELETE from struct WHERE content_id ='" . $_REQUEST[$primarykey] ."' AND container_id='".$container_id."'";
				$r = mysql_prefix_query($q);
				$error2 = mysql_error();
			}
			if ($error) {
				listrecords("\nError deleting: $error1 <br /> $error2", "error");
			}
			else {
				if ($fileupload==true) {
					if ($BASE_ROOT<>'') {
						$basedir=$_SERVER['DOCUMENT_ROOT']."/".$BASE_ROOT;
					}
					$full_localfilename = $basedir."/".$todeletefilename;
					if (file_exists($full_localfilename)) {
						unlink($full_localfilename);			    
					}
				}
				listrecords("\nDeletion completed.", "notify");
			}
		}
	}
	elseif (!isset($_POST["deleterecord"])) {
		if ($templateenabled==true) {
			$qq="SELECT * FROM opentempl WHERE id='".$_POST["t_id"]."'";
			$tresult = mysql_prefix_query($qq);
			$tarray = mysql_fetch_array($tresult);
			$tempopentreearray = unserialize(stripslashes($tarray["t_data"]));
			$serdata= array();
			while (list($var, $val) = each($tempopentreearray)) {
				$serdata[$val[0]]=$_POST[$val[0]];
				if ($val[3] && !$_POST[$val[0]]) {
					$errors_templ .="<li>".$val[2]."</li>";
				}
			}
			$_POST["content"] = serialize($serdata);
		}

		//echo " status: ".$status;
		//echo " wf: ".$wf;
		//echo " task: ".$_REQUEST["task"];
		if ($wf){
			//check the action and set the status for this item
			if ($status<>'saved' && $status<>'' && isset($_POST["statusactionsave"])){
				$_POST["status"]=$status;
			}
			elseif (isset($_POST["statusactionsaveforapproval"])){
				$_POST["status"]="waiting";
			}
			elseif (isset($_POST["statusactionapprove"])){
				$_POST["status"]="published";
			}
			elseif (isset($_POST["statusactiondecline"])){
				$_POST["status"]="declined";
			}
			elseif (isset($_POST["statusactionarchive"])){
				$_POST["status"]="archive";
			}
			else{
				$_POST["status"]="saved";	
			}
		}
		//this is specific for file upload
		if($fileupload==true){
			if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
				//file is image
				$size = GetImageSize($_FILES['userfile']['tmp_name']);
				if ($size) {
					$_POST["width"] = $size[0];
					$_POST["height"] = $size[1];   
				}
				$_POST["format"] = strtolower($_FILES['userfile']['type']);
				// If personal restriction is enabled the path is already set to the personal directory
				if ($BASE_ROOT<>'') {
					$basedir=$upload_base.$BASE_ROOT;
				}
				//generate a file name that doesn't exsist
				$localfilename=$_FILES['userfile']['name'];
				$full_localfilename = $basedir."/".$_FILES['userfile']['name'];
				while (file_exists($full_localfilename)) {
					$localfilename = GenerateRandomString(4, '', 7)."_".$_FILES['userfile']['name'];
					$full_localfilename = $basedir."/".$localfilename;
				}
				if (!@move_uploaded_file($_FILES['userfile']['tmp_name'], $full_localfilename)) {
					return true;
				}
				if ($fileuploaduserrestricted==true) {
					$_POST[$filenamefield]= $login."/".$localfilename;
				}
				else{
					$_POST[$filenamefield]= $localfilename;
				}
				//file is moved to new/ permanent location
				if ($storemethod=='db') {
					$fd = fopen ($full_localfilename, "rb");
					while(!feof($fd)){
						$_POST[$filefield].= fread($fd,$_FILES['userfile']['size']);
					}
					fclose ($fd);
					unlink($full_localfilename);
				}
			}
		}
		//check if all required items are filled out
		while (list($var, $val) = each($records)) {
			$field=$val[0];
			$nicefield=$val[2];
			$req=$val[3];
			if ($req && !$_POST[$field]) {
				$errors .="<li>".$nicefield."</li>";
			}
		}
		$errors.=$errors_templ;
		//if errors some of the required fields are empty
		if ($errors) {
			$_POST[$primarykey] = $primarykey;
			if ($fileupload==true) {
				if (file_exists($full_localfilename)) {
					unlink($full_localfilename);			    
				}
			}
			general_form("re".$action, "\nPlease fill out these mandatory fields:\n<ul>" . $errors . "</ul>");
			return false;
		}
		if ($action == 'create') {
			//a new record is created for the actual container data
			$sql="INSERT INTO $tablename ( $primarykey ) VALUES ('')";
			$r = mysql_prefix_query($sql);
			$error = mysql_error();
			if ($error) {
				if ($fileupload==true) {
					if (file_exists($full_localfilename)) {
						unlink($full_localfilename);			    
					}
				}
				listrecords("\nError creating record: $error","error");
				return false;
			}
			//get the primarykey

			$_POST[$primarykey] = mysql_insert_id($connect);
			// Get order column
			$sql2="SELECT ".$order_column." FROM ".$tablename." ORDER BY ".$order_column." DESC LIMIT 1";
			$r = mysql_prefix_query($sql2);
			if ($r) {
				if (mysql_num_rows($r)>0) {
					$array=mysql_fetch_array($r);
					$_REQUEST[$order_column]=$array[$order_column];
				}
				else{
					$_REQUEST[$order_column]=1;
				}
			}
			if ($wf){
				//initiate the value's for the generalinformation/struct table
				if ($generalconfig["robot"]==''){
					$generalconfig["robot"]='index,folow';
				}
				if ($generalconfig["ondate"]==''){
					//today
					$generalconfig["ondate"]=date("Y-m-d  H:i:s",(time()));
				}
				if ($generalconfig["offdate"]==''){
					//today plus ten years 
					$generalconfig["offdate"]=date("Y-m-d  H:i:s",(time()+315360000));
				}
				$sql= "INSERT INTO struct (id, container_id, content_id, u_id, keywords, description, robot, ondate, offdate, systemtitle) VALUES ('', $container_id, $_POST[$primarykey], ".$_SESSION["uid"].", '".$generalconfig["keywords"]."', '".$generalconfig["description"]."', '".$generalconfig["robot"]."', '".$generalconfig["ondate"]."', '".$generalconfig["offdate"]."', '".$generalconfig["systemtitle"]."')";
				$r = mysql_prefix_query($sql);
				$error = mysql_error();
				if ($error) {
					if ($fileupload==true) {
						if (file_exists($full_localfilename)) {
							unlink($full_localfilename);			    
						}
					}
					listrecords("\nError creating record: $error","error");
					return false;
				}
			}
		}
		$update = "UPDATE $tablename ";
		$delimiter= "SET ";
		$index=0;
		reset($records);
		while (list($var, $val) = each($records)) {
			$field=$val[0];
			if ($primarykey<>$field) {
				$addfield=true;
				if ($fileupload==true && ($action=='edit' || $action=='reedit') && $field==$filenamefield && $_POST[$field]=='') {
					$addfield=false;
				}
				if($val[4]==true){
					$addfield=false;
				}
			}
			if ($addfield==true) {
					$update .= $delimiter . $field  . "='" .  addslashes($_POST[$field]) . "'";
					$delimiter= ", ";			    
					$addfield=false;
			}
			$index++;
		}
		$update = $update . " WHERE $primarykey = '" . $_POST[$primarykey] ."'";
		$r = mysql_prefix_query($update);
		$error = mysql_error();
		if ($wf){
			$update  = "UPDATE struct ";
			$update .= "SET keywords='" .  addslashes($_POST["keywords"]) . "' ";
			$update .= ", description='" .  addslashes($_POST["description"]) . "' ";
			$update .= ", robot='" .  addslashes($_POST["robot"]) . "' ";
			$update .= ", ondate='" .  addslashes($_POST["ondate"]) . "' ";
			$update .= ", offdate='" .  addslashes($_POST["offdate"]) . "' ";
			$update .= ", status='" .  addslashes($_POST["status"]) . "' ";
			$update .= ", comment='" .  addslashes($_POST["comment"]) . "' ";
			$update .= ", systemtitle='" .  addslashes($generalconfig["systemtitle"]) . "' ";
			$update .= " WHERE container_id = '" . $container_id ."' AND content_id = '".$_POST[$primarykey]."'";
			$r = mysql_prefix_query($update);
			$error = mysql_error();
		}
		
		if ($error) {
			if ($fileupload==true) {
				if (file_exists($full_localfilename)) {
					unlink($full_localfilename);			    
				}
			}
			if ($action == 'create') {
				$errormsg="<P><B>Error creating record: $error</B></P>";
			}
			else{
				$errormsg="<P><B>Error updating record: $error</B></P>";
			}
			general_form('re'.$action,$errormsg);
			return false;
		} 
		else{
			if(isset($_POST["statusactionsaveforapproval"]) && $wf){
				if ($mailuid && $mailuid<>$_SESSION["uid"]){
					$mailr = mysql_prefix_query("SELECT display_name, email FROM user WHERE uid='".$mailuid."'")or die (mysql_error());
					if (mysql_num_rows($mailr)){
						$marray=mysql_fetch_array($mailr);
						//validate e-mail address
						$mail = new PHPMailer();
						$mail->SetLanguage('en', $includes_path.'/');
						$mail->From = $u_mail;
						$mail->FromName = $display_name;
						
						$mail->AddAddress($marray["email"], $marray["display_name"]);
						$mail->AddReplyTo($u_mail, $display_name);
						$mail->WordWrap = 50;                                 // set word wrap to 50 characters
						$mail->IsHTML(true);                                  // set email format to HTML
						$mail->Subject = "Jetstream request for approval - $pagetitle";
						$mail->Body    = "Jetstream request for approval on " . date('F jS, Y') . "<br /><br />". $pagetitle ."<br />". $generalconfig["systemtitle"] ."<br /><a href=\"http://".$_SERVER["HTTP_HOST"].$install_dir."/admin/cms/\">http://".$_SERVER["HTTP_HOST"].$install_dir."/admin/cms</a><br />";
						$mail->AltBody = "Jetstream request for approval on " . date('F jS, Y') . "\n\n".
						$pagetitle."\n".
						$generalconfig["systemtitle"]."\n".
						$_SERVER["HTTP_HOST"].$install_dir."/admin/cms";
						if(!$mail->Send()){
							//$mailerror=$mail->ErrorInfo;
						}
					}
				}
			}
			elseif(	isset($_POST["statusactiondecline"]) && $wf){
				$r2 = mysql_prefix_query("SELECT user.display_name as dn, user.email, user.uid FROM user, struct WHERE user.uid=struct.u_id AND container_id = '" . $container_id ."' AND content_id = '".$_POST[$primarykey]."'");
				$rarray2=mysql_fetch_array($r2);
				if ($rarray2["uid"] && $rarray2["uid"]<>$_SESSION["uid"]){
					$mail = new PHPMailer();
					$mail->SetLanguage('en', $includes_path.'/');
					$mail->From = $u_mail;
					$mail->FromName = $display_name;
					
					$mail->AddAddress($marray["email"], $marray["dn"]);
					$mail->AddReplyTo($u_mail, $display_name);
					$mail->WordWrap = 50;                                 // set word wrap to 50 characters
					$mail->IsHTML(true);                                  // set email format to HTML
					$mail->Subject = "Jetstream decline for - $pagetitle";
					$mail->Body    = "Declined on " . date('F jS, Y') . "<br /><br />". $pagetitle ."<br />". $generalconfig["systemtitle"] ."<br /><a href=\"http://".$_SERVER["HTTP_HOST"].$install_dir."/admin/cms/\">http://".$_SERVER["HTTP_HOST"].$install_dir."/admin/cms</a><br />";
					$mail->AltBody = "Declined on " . date('F jS, Y') . "\n\n".
					$pagetitle."\n".
					$generalconfig["systemtitle"]."\n".
					$_SERVER["HTTP_HOST"].$install_dir."/admin/cms";
					if(!$mail->Send()){
						//$mailerror=$mail->ErrorInfo;
					}
				}
			}
			if (function_exists("on_after_process")) {
				$error2=on_after_process();
			}
			listrecords();
		}
	}
}

//displays a pulldown form item
function general_displaydropdown($pos, $selected, $dropdown, $argument='') {
	$q = "SELECT * FROM ".$dropdown[$pos][0]." ". $dropdown[$pos][3];
	$r = mysql_prefix_query($q) or die (mysql_error());
	$n = mysql_numrows($r);
	echo "\n<select name=\"".$pos."\">";
	for ($i=0; $i < $n; $i++) {
		$primkey = mysql_result ($r,$i,$dropdown[$pos][1]);
		$displaytext = mysql_result ($r,$i,$dropdown[$pos][2]);
		if ($selected == $primkey) {
			echo "\n<option class=\"form_select\" selected value=\"". $primkey . "\">" . $displaytext;
		}
		else {
			echo "\n<option class=\"form_select\" value=\"". $primkey . "\">" . $displaytext ;
		}
	}
	echo "\n</select>";
}

//displays a pulldown form item
function general_linkedvalue($pos, $selected, $dropdown, $argument='') {
	$q = "SELECT * FROM ".$dropdown[$pos][0]." ". $dropdown[$pos][3] ." WHERE ". $dropdown[$pos][1]."='".$selected."'";
	$r = mysql_prefix_query($q) or die (mysql_error());
	$n = mysql_numrows($r);
	if ($n) {
		return  $displaytext = mysql_result ($r,$i,$dropdown[$pos][2]);
	}
	else{
		return("none");
	}
}

//process date information
function general_date_process(){
	global $dbadmindatefield;
	if ($dbadmindatefield) {
		while (list($var, $value) = each($dbadmindatefield)) {
			$date_field=$value;
			$day = $date_field . "_day";
			$month = $date_field . "_month";
			$year = $date_field . "_year";
			$_REQUEST[$date_field]= $_REQUEST[$year] ."-". $_REQUEST[$month] ."-". $_REQUEST[$day];
			$_POST[$date_field]= $_POST[$year] ."-". $_POST[$month] ."-". $_POST[$day];
		}
	}
}

//display a "date" form item
//combination of three pulldown menus
function general_dateinput($inpone, $valone, $inptwo, $valtwo, $inpthree, $valthree) {
	if (!$valone && !$valtwo && !$valthree) {
		$valone = date("d");
		$valtwo = date("m");
		$valthree = date("Y");
	}
	$month[1]="Januari";
	$month[2]="Februari";
	$month[3]="Maart";
	$month[4]="April";
	$month[5]="Mei";
	$month[6]="Juni";
	$month[7]="Juli";
	$month[8]="Augustus";
	$month[9]="September";
	$month[10]="Oktober";
	$month[11]="November";
	$month[12]="December";
	$return = "\n<select name=\"".$inpone."\">\n" ;
	$return .= "	\n<option class=\"form_select\" value=\"0\">Day\n" ;
	for ($ii=1;$ii<=31;$ii++) {
		if ( $ii==$valone ) { $sel=" selected" ; } else { $sel="" ; }
		$return .= "	\n<option class=\"form_select\" value=\"".$ii."\"".$sel.">".$ii."\n" ;
	}
	$return .= "\n</select>\n";
	$return .= "\n<select name=\"".$inptwo."\">\n" ;
	$return .= "	\n<option class=\"form_select\" value=\"0\">Maand\n" ;
	for ($ii=1;$ii<=12;$ii++) {
		if ( $ii==$valtwo ) { $sel=" selected" ; } else { $sel="" ; }
		$return .= "	\n<option class=\"form_select\" value=\"".$ii."\"".$sel.">".$month[$ii]."\n" ;
	}
	$return .= "\n</select>\n";
	$return .= "\n<input class=\"form_input\" type=\"text\" name=\"$inpthree\" size=4 value=\"$valthree\">";
	return $return;
}
?>