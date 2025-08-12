<?
//displays the actual login form
function login_form($annotation, $error=''){
	global $site_title, $jetstream_url;
	if (isset($_GET["timeout"]) && $_GET["timeout"]==true) {
		$urladd='?timeout=true';
	}
	?>
	<br /><form name='login' method='post' action='<? echo $jetstream_url; ?>/index.php<?echo $urladd;?>'>
	<table width='100%' cellpadding='0' cellspacing='0' border='0'><tr>
	<td width='80' valign='bottom'><img src="<? echo $jetstream_url;?>/images/login.gif" width="91" height="84" border="0" />&nbsp;&nbsp;</td>
	<td width='100%' valign='bottom'><span class='tab-s'>
	<?
	if ($error==''){
		echo "Welcome to ".$site_title." CMS";
	}
	else{
		echo "<font color=\"#880000\">".$error."</font>";
	}
	?>
	</span><br />
	<?
	if($annotation!=''){
		echo "<span class='install'>". $annotation."</span><br />";
	}
	else{
		echo "<span class='install'>Enter your username and password</span><br />";
	}
		?>
	<img src='<? echo $jetstream_url;?>/images/break-el.gif' width='400' height='1' vspace='8' /></td></tr><tr><td>&nbsp;</td><td>
	<table cellpadding='0' cellspacing='0' border='0'><tr height='24'><td>Username:&nbsp;</td><td><input class="form_input" type="text" name="login" maxlength="30" size="15" value="<?echo $_REQUEST["login"];?>"></td></tr><tr height='24'><td>Password:&nbsp;</td><td><input class="form_input" type="password" name="login_password" maxlength="30" size="15"></td></tr><tr height='24'><td>&nbsp;</td><td><input type='submit' value='Login'></td></tr></table>
	</td></tr>
	<?
	if(isset($_GET["timeout"]) && $_REQUEST["login"]<>''){
		?>
	<tr>
	<td width='80' valign='bottom'></td>
	<td width='100%' valign='bottom'><img src='<? echo $jetstream_url;?>/images/break-el.gif' width='400' height='1' vspace='8' /><br /><span class='tab-s'>I forgot my password:</span><br /><span class='install'><a href="<? echo $jetstream_url;?>/index.php?task=sendpw&login=<?echo $_REQUEST["login"]?>">Please email my password.</a></span><br />
	</td></tr>
	<?
	}
		?>
	</table>
	</form>
	<script language='JavaScript'>
	<!--
		login_focus();
	// -->
	</script>
	<?
}

//displays the login screen, invoked from auth.inc.php
function login_screen($screentype=''){
	global $site_title, $jetstream_url, $annotation, $thisfile;
	$jetstream_nav = array (
		"2.1"		=>  array($jetstream_url . $thisfile => "Logged out")
	);
	if($screentype=="ended"){
		$annotation="You are logged out of ". $site_title. " CMS";
	}
	elseif($screentype=="popup"){
		$annotation="Logged out automatically of ". $site_title. " for security reasons<br />Please login to continue";
		$jetstream_nav = array (
			"2.1"		=>  array($jetstream_url . $thisfile => "Login to continue")
		);
	}
	else{
		$jetstream_nav = array (
			"2.1"		=>  array($jetstream_url . $thisfile => "Log in")
		);
	}
	$tabs[]="2.1";
	jetstream_ShowSections($tabs, $jetstream_nav, "2.1");
	login_form($annotation);
}

//loggedin function with workflow support
function loggedin_workflow(){
	global $userright, $primarykey, $$primarykey, $status, $generallanguage, $wf;
	global $addnewitemoption, $deleteitemoption, $norightmusthaveparent, $jetstream_nav;
	global $allow_duplicate;

	general_date_process();
	//check current userrights
	//echo "userright:	";
	$userright=get_userrights($_SESSION["uid"]);

	if ($wf){
		if ($_REQUEST["structid"]<>''){
			//check input for consistency
			if(is_numeric($_REQUEST["structid"])){
				$presult=mysql_prefix_query("SELECT content_id FROM struct WHERE id=".$_REQUEST["structid"]);
				if (mysql_num_rows($presult)>0){
					$parray=mysql_fetch_array($presult);
					$_REQUEST[$primarykey]=$parray["content_id"];
				}
				else{
					$_REQUEST["task"]='';
				}
			}
			else{
				$_REQUEST["task"]='';
			}
		}
		//check current content status
		if (isset($_REQUEST[$primarykey])){
			//check input for consistency
			$pri_id_control=$_REQUEST[$primarykey];
			$_REQUEST[$primarykey]=$_REQUEST[$primarykey]*1;
			if($pri_id_control==$_REQUEST[$primarykey]){
				$status=get_status();
				$owner=get_itemowner();
			}
			else{
				$_REQUEST["task"]='';
			}
		}
		//check current action
		//$_REQUEST["task"];
		$tasktoaction=array(
			"createrecord"=>			"create",
			"updatecreate"=>			"create",
			"updaterecreate"=>		"create",
			"editrecord"=>				"edit",
			"updateedit"=>				"edit",
			"updatereedit"=>			"edit",
			"deleterecord"=>			"delete",
			"approve"=>						"approve",
			"decline"=>						"decline",
			"duplicate"=>					"duplicate",
		);
		$action=$tasktoaction[$_REQUEST["task"]];
		// -------
		//All the required information is gathered to check if further processing is allowed
		//--------
		//echo $userright;
		//may this action be done
		if ($userright=="author"){
			//okay if:
			// no action: show list
			// create action
			// or any other action if owned item
			if($action=='duplicate'){
			}
			elseif (($action && $owner==$_SESSION["uid"]) || $action=='' || $action=='create' || ($allow_duplicate<>false || $allow_duplicate=='')) {
				if($action=='approve' || $action=='decline'){
					listrecords($generallanguage["norights"]);
					return false;
				}
				elseif(($norightmusthaveparent==true && ($action=='' || ($owner==$_SESSION["uid"] && $status<>'published'))) || $action=='duplicate'){
					//just do the action
				}
				elseif(($allow_duplicate<>false || $allow_duplicate=='') || (($rarray["status"]=='saved' || $rarray["status"]=='declined' || $rarray["status"]=='waiting') && ($rarray["u_id"]==$_SESSION["uid"]))){

				}
				elseif($norightmusthaveparent==true && !isset($_REQUEST["p_id"])) {
					listrecords($generallanguage["norights"]);
					return false;
				}
				elseif($status=='published'){
					listrecords($generallanguage["norights"]);
					return false;
				}
				elseif(($status=='published') && ($action<>'duplicate') && ($action<>'')){
					listrecords($generallanguage["norights"]);
					return false;
				}
			}
			else{
				listrecords($generallanguage["norights"]);
				return false;
			}
		
		}
		elseif ($userright=="editor" || $userright=="administrator"){
			//echo "You are an Editor or an Administrator";
		}
		else{
			$tabs[]="2.1";
			jetstream_ShowSections($tabs, $jetstream_nav, "2.1");
			errorbox ($generallanguage["norights"], 'error');
			return false;
		}
	}// end if wf

	//check current from form
	//echo $_REQUEST["task"];
	//echo "<br />";
	switch($_REQUEST["task"]) {
		case 'createrecord'				: 
			if ($addnewitemoption==true) {
		    general_form('create', '', ''); 
			}
			else{
				listrecords($error, $blurbtype);
			}
			break;
		case 'createstep1'				: general_select_template('create', '', ''); break;
		case 'moverecord'					: move_tree($error, $blurbtype); break;
		case 'updatemoverecord'		: general_process('move'); break;
		case 'editrecord'					: general_form('edit', '', ''); break;
		case 'updateedit'					: general_process('edit'); break;
		case 'updatereedit'				: general_process('edit'); break;
		case 'updatecreate'				: 
			if ($addnewitemoption==true) {
		    general_process('create'); 
			}
			else{
				listrecords($error, $blurbtype);
			}
			break;
		case 'updaterecreate'			:
			if ($addnewitemoption==true) {
		    general_process('create'); 
			}
			else{
				listrecords($error, $blurbtype);
			}
			break;
		case 'deleterecord'				:
			if ($deleteitemoption==true) {
				general_process('edit');
				//general_deleterecord(); 
			}
			else{
				listrecords($error, $blurbtype);
			}
			break;
		default : listrecords($error, $blurbtype);
	}
	return true;
}

//loggedin function with workflow support
function login_do_sort(){
	global $primarykey, $generallanguage, $order_column, $order_column_order, $tablename;

	//check current userrights
	//echo "userright:	";
	$userright=get_userrights($_SESSION["uid"]);

	//input control, only continue if all inputs are okay
	$_REQUEST["param"]=$_REQUEST["param"]*1;
	$_REQUEST["way"]=$_REQUEST["way"]*1;
	$_REQUEST["do_sort"]=$_REQUEST["do_sort"]*1;
	if (is_int($_REQUEST["param"]) && is_int($_REQUEST["way"]) && is_int($_REQUEST["do_sort"])) {
	  $order_column_order=strtoupper($order_column_order);
		if($order_column_order!=="ASC" && $order_column_order!=="DESC"){
			echo "alert(\"Please set a default order for this container to prevent unexpected behaviour.\");\n";
			$order_column_order="ASC";
		}
		if ($_REQUEST["way"]!==1 && $_REQUEST["way"]!==-1) {
			echo "alert(\"No order direction specified.\");\n";
			exit;
		}
		if ($userright=="author" || $userright=="editor" || $userright=="administrator"){
			// move id upwards/ move down
			if ($_REQUEST["way"]==1) {
				$order_column_order=="ASC"?$selector="<" :$selector=">";
				$order_column_order=="ASC"?$q_order="DESC" :$q_order="ASC";
			}
			elseif($_REQUEST["way"]==-1){
				$order_column_order=="ASC"?$selector=">" :$selector="<";
				$order_column_order=="ASC"?$q_order="ASC" :$q_order="DESC";
			}
			//Select records
			
			$sql1="SELECT ".$primarykey.", ".$order_column." FROM ".$tablename." WHERE ".$primarykey."=".$_REQUEST["param"];
			$r = mysql_prefix_query($sql1);
			if($r){
				$array=mysql_fetch_array($r);
				$rows[0]=array($array[$primarykey], $array[$order_column]);
				$sql2="SELECT ".$primarykey.", ".$order_column." FROM ".$tablename." WHERE ".$order_column.$selector.$array[$order_column]." AND ".$primarykey."<>".$_REQUEST["param"]." ORDER BY ".$order_column." ".$q_order." LIMIT 1";
				$r = mysql_prefix_query($sql2);

			}
			//Get results
			if ($r) {
				if (mysql_num_rows($r)>0) {
					$array=mysql_fetch_array($r);
					$rows[1]=array($array[$primarykey], $array[$order_column]);
					$sql="UPDATE $tablename SET ".$order_column."=".$rows[1][1]." WHERE ".$primarykey."=".$rows[0][0];
					$r = mysql_prefix_query($sql);
					$error = mysql_error();
					$sql="UPDATE $tablename SET ".$order_column."=".$rows[0][1]." WHERE ".$primarykey."=".$rows[1][0];
					$r = mysql_prefix_query($sql);
					echo "swap2(a2, way2);\n";
				}
			}
			else{
				echo "alert(\"".$error."\");\n";
			}
		}
	}
	else{
		echo "alert(\"".$generallanguage["norights"]."\");\n";
	}
	exit();
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
	global $order_column, $order_column_order, $status;
	if (isset($_POST["statusactionduplicate"])){
		$_REQUEST["status"]='';
		$_POST["status"]='';
		$status='';
		$_REQUEST[$primarykey]='';
		$_POST[$primarykey]='';
		$action="create";
		general_form("recreate");
		return false;
	}

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
			$_POST[$primarykey] = mysql_insert_id();
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

/*
 * general list display
 * no standard support for workflow yet
 */
function general_listrecords($error='', $blurbtype='notify') {
	global $records, $tablename, $primarykey, $jetstream_url, $jetstream_nav, $thisfile, $generalconfig, $container_id, $status, $userright, $uid, $listformat, $wf, $listgroup, $grouplistformat, $groupsql;
	global $overviewitemoption, $addnewitemoption, $deleteitemoption, $default_sort_colomn, $defaul_sort_order;
	global $userowneditemsonly;
	global $hul;
	global $sorted_by, $sorted_by_order;
	global $order_column, $order_column_order;
	global $allow_duplicate;
	if (function_exists("on_before_list")) {
		$error2=on_before_list();
	}

	//global $showarchive;
	//which list should be shown
	//When new item is made always show nonarchived list
	if ($_REQUEST["task"]=="updatecreate" ||$_REQUEST["task"]=="updaterecreate") {
		$_SESSION["showarchive"]=false;
	}
	//When task don't change list
	elseif ($_REQUEST["task"]) {
	    
	}
	//set list type
	elseif($_REQUEST["showarchive"]==true){
		$_SESSION["showarchive"]=true;
	}
	else{
		$_SESSION["showarchive"]=false;
	}
	if ($overviewitemoption==true) {
		$tabs[]="2.1";
		$seltab="2.1";
	}
	if ($addnewitemoption==true) {
		$tabs[]="2.2";	    
	}
	if ($wf==true) {
		$tabs[].="2.4";	    
		if ($_SESSION["showarchive"]==true) {
			$seltab="2.4";
		}
	}
	jetstream_ShowSections($tabs, $jetstream_nav, $seltab);
	errorbox ($error, $blurbtype);

	$span = count($listformat)+2;
	if ($_SESSION["showarchive"]==true) {
		$qad="struct.status='archive'";
	}
	else{
		$qad="struct.status<>'archive'";
	}
	if ($wf){
		$span=$span+2;
		$wflist=array("1"=>array("status", "wfstatus"));
		$q="SELECT *, struct.id AS struct_id  FROM user, struct, $tablename WHERE ".$qad." AND struct.container_id='".$container_id."' AND struct.content_id=".$tablename.".".$primarykey." AND struct.u_id=user.uid";
		if ($userright=='author' && ($allow_duplicate<>false || $allow_duplicate=='')){
			$q.=" AND (user.uid=".$_SESSION["uid"]." OR (user.uid<>".$_SESSION["uid"]." AND struct.status IN ('published','deleted','archive')))";
		}
		elseif($userright=='author'){
			$q.=" AND user.uid=".$_SESSION["uid"];
		}
	}
	else{
		$q = "SELECT * FROM $tablename";
	}
	if ($userowneditemsonly==true && get_userrights($uid)<>'administrator') {
		$q.= " WHERE uid='".$_SESSION["uid"]."'";
	}
	
	if($_SESSION["container_".$container_id]["sorted_by"]==$_REQUEST["orderby"]){
		$_REQUEST["logic"] == "asc" ? $_REQUEST["logic"] = "desc" : $_REQUEST["logic"] = "asc";
	}

	if ($_REQUEST["orderby"]) {
		//while ($listitem = each($listformat)) {
		
		//}
		$q .= " ORDER by ".$_REQUEST["orderby"] . " " .$_REQUEST["logic"];
		$sorted_by=$_REQUEST["orderby"];
		$sorted_by_order=$_REQUEST["logic"];
	}
	else{
		if($default_sort_colomn<>'' && $defaul_sort_order<>'' && $_SESSION["container_".$container_id]["sorted_by"]==''){
			$sorted_by=$default_sort_colomn;
			$sorted_by_order=$defaul_sort_order;
			$q .= " ORDER by ".$sorted_by . " " .$sorted_by_order;
		}
		elseif($_SESSION["container_".$container_id]["sorted_by"]<>''){
			$sorted_by=$_SESSION["container_".$container_id]["sorted_by"];
			$sorted_by_order=$_SESSION["container_".$container_id]["sorted_by_order"];
			$q .= " ORDER by ".$sorted_by . " " .$sorted_by_order;
		}
		else{
			$sorted_by_order="asc";
		}
		$_REQUEST["logic"]=strtolower($sorted_by_order);
	}

	$_SESSION["container_".$container_id]["sorted_by"]=$sorted_by;
	$_SESSION["container_".$container_id]["sorted_by_order"]=$sorted_by_order;
	
	$ruler = '<tr><td colspan="'.$span.'" class="ruler"></td></tr>';
	$ruler2 = '<tr><td colspan="'.$span.'" class="ruler2"></td></tr>';
	echo "<table border=\"0\" width=100% cellspacing=\"0\" cellpadding=\"5\" id=\"table_overview_list\">";
	echo $ruler;
	reset($listformat);
	echo "<tr bgcolor=\"#F6F6F6\" id=\"sorting_row\">";
	if ($wf){
		if ($listgroup){
			echo "<td class='tab-g'>Status</td><td class='tab-g'>Author</td>";	
		}
		else{
			$sorter_display_class="class=\"tab-g-sort\"";
			$sort_img="&nbsp;<img src=\"images/clearpixel.gif\" border=\"0\" />";
			if ($sorted_by=="struct.status") {
				$sorter_display_class="class=\"tab-g-sort-sel\"";
				$sort_img="&nbsp;<img src=\"images/caret-".strtolower($sorted_by_order).".gif\" border=\"0\" />";
			}

			echo "<td ".$sorter_display_class." title=\"Sort by status\"><a style=\"DISPLAY: block;\" href=\"". $jetstream_url . $thisfile. "?task=sort&orderby=struct.status&logic=".$_REQUEST["logic"] . "\">Status".$sort_img."</a></td>";	
			$sorter_display_class="class=\"tab-g-sort\"";
			$sort_img="&nbsp;<img src=\"images/clearpixel.gif\" border=\"0\" />";
			if ($sorted_by=="struct.u_id") {
				$sorter_display_class="class=\"tab-g-sort-sel\"";
				$sort_img="&nbsp;<img src=\"images/caret-".strtolower($sorted_by_order).".gif\" border=\"0\" />";
			}
			echo "<td ".$sorter_display_class." title=\"Sort by author\"><a style=\"DISPLAY: block;\" href=\"". $jetstream_url . $thisfile. "?task=sort&orderby=struct.u_id&logic=".$_REQUEST["logic"] . "\">Author".$sort_img."</a></td>";	
		}
	}
	//echo $sorted_by;
	while ($listitem = each($listformat)) {
		if ($listgroup){
			echo "n<td class='tab-g-sort'>". $listitem[1][2]."</td>";	
		}
		else{
			if ($sorted_by==$listitem[1][0]) {
				echo "<td class='tab-g-sort-sel' title=\"Sort by ".strtolower($listitem[1][2])."\"><a style=\"DISPLAY: block;\" href=\"". $jetstream_url . $thisfile. "?task=sort&orderby=".$listitem[1][0]."&logic=".$_REQUEST["logic"] . 	"\">".$listitem[1][2]."&nbsp;<img src=\"images/caret-".strtolower($sorted_by_order).".gif\" border=\"0\" /></a></td>";
			    
			}
			else{
				echo "<td class='tab-g-sort' title=\"Sort by ".strtolower($listitem[1][2])."\"><a style=\"DISPLAY: block;\" href=\"". $jetstream_url . $thisfile. "?task=sort&orderby=".$listitem[1][0]."&logic=".$_REQUEST["logic"] . "\">".$listitem[1][2]."<img src=\"images/clearpixel.gif\" border=\"0\" /></a></td>";	
			}
		}
	}
	if ($order_column<>'') {
		if ($sorted_by==$order_column) {
			echo "<td class=\"tab-g-sort-sel\" colspan=\"2\" title=\"By clicking the arrows the items order is changed.\" align=\"center\"><a style=\"DISPLAY: block;\" href=\"". $jetstream_url . $thisfile. "?task=sort&orderby=".$order_column."&logic=".$order_column_order . 	"\">Order&nbsp;<img src=\"images/clearpixel.gif\" border=\"0\" /></a></td>";	
		}
		else{
			echo "<td class=\"tab-g\" colspan=\"2\" title=\"Change items order\" align=\"center\"><a style=\"DISPLAY: block;\" href=\"". $jetstream_url . $thisfile. "?task=sort&orderby=".$order_column."&logic=".$order_column_order. "\">Order<img src=\"images/clearpixel.gif\" border=\"0\" /></a></td>";	
		}	    
	}
	echo "</tr>";

	if($listgroup){
		$groupq = "SELECT * FROM ".$groupsql[0]. " " .$groupsql[1];
		$groupr = mysql_prefix_query($groupq) or die (mysql_error());
		while($grouparray = mysql_fetch_array($groupr)){
			echo $ruler2;
			eval ("\$groupadd=\"".$groupsql[2]."\";");
			$q = "SELECT * FROM user, struct, $tablename, struct.id AS struct_id WHERE struct.container_id='".$container_id."' AND struct.content_id=".$tablename.".".$primarykey." AND struct.u_id=user.uid ".$groupadd;
			$r = mysql_prefix_query($q) or die (mysql_error());
			reset($grouplistformat);
			while ($listitem = each($grouplistformat)) {
				general_listelement($listitem, $grouparray);
			}
			while ($rarray = mysql_fetch_array($r)) {
				general_listrow($rarray, $wflist, $ruler);
			}
		}
	}
	else{
		//echo $q;
		$r = mysql_prefix_query($q) or die (mysql_error());
		//echo mysql_num_rows($r);
		while ($rarray=mysql_fetch_array($r)) {
			general_listrow($rarray, $wflist, $ruler);
		}
		echo $ruler;
	}
	echo "</table>";
	echo "<table border=\"0\" width=100% cellspacing=\"0\" cellpadding=\"5\" id=\"table_overview_list\">";
	echo help_section($hul, $ruler, $ruler2, $span);
	echo "</table>";
	if ($sorted_by==$order_column && $order_column<>'') {
		echo "<SCRIPT LANGUAGE=\"JavaScript\" type=\"text/javascript\">
				<!--
				overview_list_init();
				-->
				</SCRIPT>";
	}
}

//display actual record
function general_listrow($rarray, $wflist, $ruler){
	global $records, $tablename, $primarykey, $jetstream_url, $jetstream_nav, $thisfile, $generalconfig, $container_id, $status, $userright, $uid, $listformat, $wf;
	global $addnewitemoption, $deleteitemoption;
	global $order_column, $order_column_order, $sorted_by;
	global $allow_duplicate; 
	//echo $order_column;
	
	if ($order_column<>"" && $sorted_by==$order_column) {
		$sorting_add= "<td class=\"sort_column\" width=\"1%\" align=\"center\"><a style=\"display: block;\" href=\"#\" title=\"Move up\" onclick=\"swap(this, 1)\"><img src=\"images/caret-asc.gif\" border=\"0\" /></a></td><td class=\"sort_column\" width=\"1%\" align=\"center\"><a   href=\"#\"  title=\"Move down\" style=\"display: block;\" onclick=\"swap(this, -1)\"><img src=\"images/caret-desc.gif\" border=\"0\" /></a></td>";
	}
	elseif($order_column<>""){
		$sorting_add="<td width=\"1%\" align=\"center\"><img class=\"off\" src=\"images/caret-asc.gif\" border=\"0\" /></td><td width=\"1%\" align=\"center\"><img class=\"off\" src=\"images/caret-desc.gif\" border=\"0\" /></td>";
	}
	if($wf){
		$noprint=0;
		if ($userright=='author' && $rarray["status"]<>'published' && !($allow_duplicate<>false || $allow_duplicate=='')){
			if (!(($rarray["status"]=='saved' || $rarray["status"]=='declined' || $rarray["status"]=='waiting') && ($rarray["u_id"]==$_SESSION["uid"]))){
				$noprint=1;
			}
		}
		if (!$noprint){
			echo $ruler;
			echo "<tr item=\"".$rarray[$primarykey]."\">";
			general_listelement($wflist, $rarray);
			reset($listformat);
			while ($listitem = each($listformat)) {
				general_listelement($listitem, $rarray);
			}
			echo $sorting_add;
			echo "</tr>";
			return true;
		}
		else{
			return false;
		}
	}
	else{
		echo $ruler;
		echo "<tr item=\"".$rarray[$primarykey]."\">";
		reset($listformat);
		while ($listitem = each($listformat)) {
			general_listelement($listitem, $rarray);
		}
		echo $sorting_add;
		echo "</tr>";
		return true;
	}
}

//displays general form

function general_select_template($action, $error='', $blurbtype='error') {
	global $records, $tablename, $primarykey, $dropdown, $jetstream_url, $jetstream_nav, $thisfile, $sessid, $sesscode, $userright, $status, $wf;
	global $container_id, $generalconfig;
	$tabs[]="2.1";
	$selectedtab="2.2";
	if ($action=="edit"){
		$tabs[].="2.6";
		$selectedtab="2.6";
	}
	else{
		$tabs[].="2.2";
	}
	
	jetstream_ShowSections($tabs, $jetstream_nav, $selectedtab);
	errorbox ($error, $blurbtype);
	echo "\n<FORM enctype=\"multipart/form-data\" ACTION=\"". $jetstream_url . $thisfile. "\" METHOD=\"POST\" name=\"mainform\">";
	echo "\n<TABLE BORDER=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n<TR>";
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
	$span=5;
	$ruler = '<tr><td colspan="'.$span.'" class="ruler"></td></tr>';
	$ruler2 = '<tr><td colspan="'.$span.'" class="ruler2"></td></tr>';
	echo $ruler2;
	echo "\n<tr bgcolor=\"#ffffff\">\n<td colspan=3  background='images/stab-bg.gif'><font color=\"\"><b>".$status."</b></font></td></tr>";
	echo $ruler;
	if ($action=="edit"){
		$localrec=array(
			array("t_id","valuefromurlshowlinkednovalue","Current template",""),
			array("t_id","dropdown","New template",""),
			array("p_id","valuefromurl","Parent",""),
		);
	}
	else{
			$localrec=array(
			array("t_id","dropdown","Template",""),
			array("p_id","valuefromurl","Parent",""),
		);
	}
	while (list($var, $val) = each($localrec)) {
		general_formelement($val,$r, 'create');
	}
	echo "\n<tr><td><br></td></tr>";
	echo "\n<tr>\n<td width=10%>&nbsp;</td>\n<td colspan=2><INPUT class=\"form_button\" TYPE=\"submit\" VALUE=\"Next step\">";
	echo "\n</td></tr>";

	if ($action=="edit"){
		echo "\n<INPUT TYPE=\"Hidden\" NAME=\"".$primarykey."\" VALUE=\"".$_REQUEST[$primarykey]."\">";
		echo "\n<INPUT TYPE=\"Hidden\" NAME=\"task\" VALUE=\"updatechangetemplate\">";
	}
	else{
		echo "\n<INPUT TYPE=\"Hidden\" NAME=\"task\" VALUE=\"createrecord\">";
	}
	echo "</TABLE>\n</FORM>";
}

//displays general form
function general_form($action, $error='', $blurbtype='error') {
	global $records, $tablename, $primarykey, $dropdown, $jetstream_url, $jetstream_nav, $thisfile, $sessid, $sesscode, $userright, $status, $wf;
	global $container_id, $generalconfig, $fileupload, $fileuploaduserrestricted, $noruler;
	global $overviewitemoption, $addnewitemoption, $deleteitemoption, $generallanguage, $templateenabled, $archiveenabled;
	global $userowneditemsonly;
	global $help_under_form;
	global $changeparentenabled;
	global $huf;
	global $_SETTINGS;
	global $allow_duplicate;
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
	jetstream_ShowSections($tabs, $jetstream_nav, $selectedtab);
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

	?>
	<SCRIPT LANGUAGE="JavaScript" type="text/javascript">
	<!--
	hWnd=""; // Global variable
	function JetstreamRTE(rtevar){
		<?
		if ($richtext==true) {
			?>
			var url = "<? echo $rte_path;?>edit.php?val=";
			url += rtevar;
			hWnd = window.open(url,"HelpWindow","width=780,height=425,resizable=yes,scrollbars=yes,location=no,status=no,adressbar=no");
			if ((document.window != null) && (!hWnd.opener))
				hWnd.opener = document.window;
			<?
		}
		else{
			?>
			alert("The Rich Text editor feature is only available with\nInternet Explorer 5.5 or better.\nIt is recommended to update your browser. If you\ncan't do this yourself, please contact your system\nadministrator for assistants");	
			<?
		}
		?>
	}
	
	function SelImage(rtevar){
		<?
		if ($insimage==true) {
			?>
				a = showModalDialog("<?echo $_SETTINGS["img_dialog"];?>",
					                        rtevar,      // str or obj specified here can be read from dialog as "window.dialogArguments"
                                 "resizable: yes; help: no; status: no; scroll: no; ");
			 if (a) {
				 eval("document.mainform."+rtevar+".value='"+a+"'");			     
			 }
			 <?
		}
		else{
			?>
			alert("The insert iamge feature is only available with\nInternet Explorer 5.0 or better.\nIt is recommended to update your browser. If you\ncan't do this yourself, please contact your system\nadministrator for assistants");	
			<?
		}
		?>
	}

	function isOpen() {
		return (hWnd && !hWnd.closed);
	}
	//-->
	</script>
	<?
	echo "<FORM enctype=\"multipart/form-data\" ACTION=\"". $jetstream_url . $thisfile. "\" METHOD=\"POST\" name=\"mainform\" onsubmit=\"".$formdisable. $multi_select_action."\">";
	echo "<TABLE border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\"><tr>";
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
	echo $ruler2;
	echo "<tr bgcolor=\"#ffffff\"><td colspan=3 background='images/stab-bg.gif' style=\"padding:3px 2px 1px 2px\"><img src=\"images/workflow_".strtolower($status).".gif\"/ title=\"Status: ".$status."\"></td></tr>";
	$rightsokay=='1' ? $printrecord.="<td valign=\"top\" width=\"1%\" title=\"".strtolower($rarray["status"])." - You may edit this item.\" ".$sorter_display_class." nowrap=\"nowrap\"  style=\"padding:3px 2px 1px 2px\"><img src=\"images/workflow_".strtolower($rarray["status"]).".gif\"/></td>" : $printrecord.="<td valign=\"top\" width=1% title=\"You do not have the rights to edit this item.\" ".$sorter_display_class." nowrap=\"nowrap\" style=\"padding:3px 2px 1px 2px\"><img src=\"images/workflow_".strtolower($rarray["status"]).".gif\"/></td>";
	echo $ruler;
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
		echo "<tr><td width=10% colspan=2><b>Status: ".$status."</b></td><td>";	    
	}
	else{
		echo "<tr><td width=10% colspan=2>&nbsp;</td><td>";	    
	}
	
	if ($wf && $status<>'new'){
		if(get_userrights($_SESSION["uid"])=='author' && $_SESSION["uid"]<>get_itemowner()){
			echo "<INPUT class=\"form_button\" TYPE=\"SUBMIT\" VALUE=\"Duplicate\" name=\"statusactionduplicate\">";
			$only_duplicate=true;
		}
	}
	
	if ($fileupload==true && $only_duplicate<>true) {
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
	elseif($only_duplicate<>true) {
		if ($status=='new') {
			if ($wf) {
				$buttonvalue='Store draft';			    
			}
			else{
				$buttonvalue='Save';			    
			}
		}
		else{
			$buttonvalue='Save changes';
		}
		echo "<INPUT class=\"form_button\" TYPE=\"submit\" VALUE=\"".$buttonvalue."\" name=\"statusactionsave\">";
		if ($status<>'new') {
			echo "<INPUT class=\"form_button\" TYPE=\"SUBMIT\" VALUE=\"Duplicate\" name=\"statusactionduplicate\">";
		}
	}
	if($only_duplicate<>true){
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
	}
	echo "</td></tr>";
	if ($wf){
		generalform_workflow($action);
	}
	echo "<INPUT TYPE=\"Hidden\" NAME=\"task\" VALUE=\"update".$action."\">";
	echo help_section($huf, $ruler, $ruler2, $span);
	echo "</TABLE></FORM>";
}

//display the form elements
function general_formelement($val, $formrarray, $action){
	global $records, $tablename, $primarykey, $dropdown, $jetstream_url, $jetstream_nav, $thisfile, $sessid, $sesscode, $userright, $status;
	global $container_id, $generalconfig, $wf, $includes_path, $noruler;
	$value=$val[0];
	$formelement=$val[1];
	$display=$val[2];
	$val[3]=='required' ? $requiredcolor="8C8A8C" : $requiredcolor="ffffff";
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
	include ($includes_path."/general_formelements.inc.php");
	if ($noruler==false){
		echo $ruler;
	}
}

//display the form elements
function general_listelement($listformat, $rarray){
	global $tablename, $primarykey, $dropdown, $jetstream_url, $jetstream_nav, $thisfile, $sessid, $sesscode, $userright, $status, $uid;
	global $container_id, $generalconfig, $wf, $BASE_URL, $BASE_ROOT, $front_end_url, $filenamefield, $login;
	global $absolutepath, $absolutepathfull;
	global $sorted_by, $sorted_by_order;
	global $allow_duplicate;
	// added to fix 'Show' action in 'Preview' column.
	$result = mysql_prefix_query("SELECT * FROM user WHERE uid='" . $_SESSION["uid"] ."'");
	$login= @mysql_result($result,0,'login');
	$cellvalue=$listformat[1][0];

	if ($cellvalue==$sorted_by) {
		$sorter_display_class="class=\"sort_column\"";
	}
	else{
		$sorter_display_class="";
	}
	$formelement=$listformat[1][1];
	switch($formelement) {
		case 'editlink' :
			if (strlen(stripslashes($rarray[$cellvalue]))==0){
				$rarray[$cellvalue]="- Not set -";
			}
			if ($wf){	
				$rightsokay=0;
				if (($userright=='author' && ($allow_duplicate<>false || $allow_duplicate=='')) || $userright<>'author' || (($rarray["status"]=='saved' || $rarray["status"]=='declined' || $rarray["status"]=='waiting') && ($rarray["u_id"]==$_SESSION["uid"]))){
					$rightsokay=1;
				}
				if ($rightsokay==1){
					echo "<td width=\"20%\" ".$sorter_display_class." nowrap=\"nowrap\"><a style=\"display: block;\" title=\"Edit " . stripslashes($rarray[$cellvalue]) . "\" class=\"link\" href=\"". $jetstream_url . $thisfile ."?task=editrecord&$primarykey=".$rarray[$primarykey] . "\">" . stripslashes($rarray[$cellvalue]) . "<img src=\"images/clearpixel.gif\" border=\"0\" /></a></td>";
				}
				else{
					echo "<td ".$sorter_display_class.">" . stripslashes($rarray[$cellvalue]) . "</td>";
				}
			}
			else{
				echo "<td width=\"20%\" ".$sorter_display_class." nowrap=\"nowrap\"><a class=\"link\" href=\"". $jetstream_url . $thisfile ."?task=editrecord&$primarykey=".$rarray[$primarykey] . "\">" . stripslashes($rarray[$cellvalue]) . "</a></td>";
			}
		break;
		case 'refeditlink' :
			$fsql="SELECT ".$listformat[1][5]." FROM ".$listformat[1][3]." WHERE ".$listformat[1][4]."='". stripslashes($rarray[$cellvalue]) ."'";
			$fresult = mysql_prefix_query($fsql);
			if ($farray= mysql_fetch_array($fresult)){
				$avalue=$farray[$listformat[1][5]];
			}
			echo "<td width=\"20%\" ".$sorter_display_class." nowrap=\"nowrap\"><a class=\"link\" href=\"". $jetstream_url . $thisfile ."?task=editrecord&$primarykey=".$rarray[$primarykey] . "\">" . stripslashes($avalue) . "</a></td>";
		break;
		case 'reflink' :
			if ($listformat[1][3]=='' || $listformat[1][4]=='' || $listformat[1][5]==''){
				echo "<td width=\"20%\" ".$sorter_display_class." nowrap=\"nowrap\">".stripslashes($rarray[$cellvalue]) . " -Configure the referred show -</td>";
				break;
			}
			$fsql="SELECT ".$listformat[1][5]." FROM ".$listformat[1][3]." WHERE ".$listformat[1][4]."='". stripslashes($rarray[$cellvalue]) ."'";
			$fresult = mysql_prefix_query($fsql);
			if ($farray= mysql_fetch_array($fresult)){
				$avalue=$farray[$listformat[1][5]];
			}
			echo "<td width=\"20%\" ".$sorter_display_class." nowrap>".stripslashes($avalue) . "</td>";
		break;
		case 'showfilelink' :
			echo "<td width=\"20%\" ".$sorter_display_class." nowrap=\"nowrap\"><a class=\"link\" target=\"_new\" href=\"".$BASE_URL."/" .stripslashes($rarray[$cellvalue])."\">". $BASE_URL."/".stripslashes($rarray[$cellvalue]) ."</a></td>";
		break;
		case 'radioja' :
			if ($rarray[$cellvalue]==1 || $rarray[$cellvalue]==true ){
			$printval="Ja";
			}
			echo "<td>". $printval . "</td>";
			$printval='';
		break;
		case 'radionee' :
			if ($rarray[$cellvalue]==0 || $rarray[$cellvalue]==false || $rarray[$cellvalue]==''){
			$printval="Nee";
			}
			echo "<td>". $printval . "</td>";
			$printval='';
		break;
		case 'radioyesno' :
			if ($rarray[$cellvalue]==0 || $rarray[$cellvalue]==false || $rarray[$cellvalue]==''){
				$printval="No";
			}
			else{
				$printval="Yes";
			}
			echo "<td ".$sorter_display_class.">". $printval . "</td>";
			$printval='';
		break;

		case 'string' :
			echo "<td ".$sorter_display_class.">". stripslashes($rarray[$cellvalue]) . "</td>";
		break;
		case 'date' :
		$splitdate = split('-',$rarray[$cellvalue],3);
			echo "<td ".$sorter_display_class.">". $splitdate[2] ."-". $splitdate[1] ."-". $splitdate[0]."</td>";
		break;
		case 'date2' :
		$splitdate = split('-',$rarray[$cellvalue],3);
			echo "<td ".$sorter_display_class.">". substr($splitdate[2],"0", "2") ."-". $splitdate[1] ."-". $splitdate[0]."</td>";
		break;
		case 'wfstatusoud' :
			$rightsokay=0;
			if (($userright=='author' && ($allow_duplicate<>false || $allow_duplicate=='')) || $userright<>'author' || (($rarray["status"]=='saved' || $rarray["status"]=='declined' || $rarray["status"]=='waiting') && ($rarray["u_id"]==$_SESSION["uid"]))){
				$rightsokay=1;
			}
			$rightsokay=='1' ? $printrecord.="<td valign=\"top\" width=5% title=\"You may edit this item.\" nowrap=\"nowrap\"><img align='absmiddle' src='images/edit.png' width='16' height='16' border=\"0\" /> [".$rarray["status"]."]</td>" : $printrecord.="<td valign=\"top\" width=5% title=\"You do not have the rights to edit this item.\" nowrap=\"nowrap\"><img align='absmiddle' src='images/lock.png' width='16' height='16' border=\"0\" /> <font color=\"aaaaaa\">[".$rarray["status"]."]</font></td>";
			echo $printrecord.="<td valign=\"top\" width=5% nowrap=\"nowrap\"><font color=\"aaaaaa\">[".$rarray["display_name"]."]</font></td>";
		break;
		case 'wfstatus' :
			$rightsokay=0;
			if (($userright=='author' && ($allow_duplicate<>false || $allow_duplicate=='')) || $userright<>'author' || (($rarray["status"]=='saved' || $rarray["status"]=='declined' || $rarray["status"]=='waiting') && ($rarray["u_id"]==$_SESSION["uid"]))){
				$rightsokay=1;
			}
			$sorter_display_class="";
			if ($sorted_by=="struct.status") {
				$sorter_display_class="class=\"sort_column\"";
			}
			$rightsokay=='1' ? $printrecord.="<td valign=\"top\" width=\"1%\" title=\"".strtolower($rarray["status"])." - You may edit this item.\" ".$sorter_display_class." nowrap=\"nowrap\"  style=\"padding:3px 2px 1px 2px\"><img src=\"images/workflow_".strtolower($rarray["status"]).".gif\"/></td>" : $printrecord.="<td valign=\"top\" width=1% title=\"You do not have the rights to edit this item.\" ".$sorter_display_class." nowrap=\"nowrap\" style=\"padding:3px 2px 1px 2px\"><img src=\"images/workflow_".strtolower($rarray["status"]).".gif\"/></td>";
			$sorter_display_class="";
			if ($sorted_by=="struct.u_id") {
				$sorter_display_class="class=\"sort_column\"";
			}

			$printrecord.="<td valign=\"top\" ".$sorter_display_class." width=5% nowrap=\"nowrap\">".$rarray["display_name"]."</td>";
			echo $printrecord; 
		break;

		case 'inforum' :
			$rarray["inforum"]=="yes" ? $ainforum="open" : ($rarray["discus"]=="yes" ? $ainforum="Niet toegevoegd" : $ainforum="Geen discussie");
			echo "<td ".$sorter_display_class.">".$ainforum."</td>";
		break;
		case 'grouprow' :
			echo "<tr><td colspan=3  background='images/stab-bg.gif'><b>".$rarray["shorttitle"]."</b></td></tr>";
		break;
		case 'containerlevel' :
			$rarray["level"]>10 ? ($rarray["level"]>100 ? $level="system administrator only" : $level="container administrator and higher") :  $level="author and higher";
			echo "<td valign=\"top\" ".$sorter_display_class." nowrap=\"nowrap\" width=10%>".$level."</td>";
		break;
		case 'imagelink' :
			echo "<td ".$sorter_display_class."><a target=\"_new\"href=\"".$BASE_URL."/".$rarray[$filenamefield]."\">Show</a></td>";
		break;
		case 'url' :
			//$fsql="SELECT ".$listformat[1][5]." FROM ".$listformat[1][3]." WHERE ".$listformat[1][4]."='". stripslashes($rarray[$cellvalue]) ."'";
			//$fresult = mysql_prefix_query($fsql);
			//if ($farray= mysql_fetch_array($fresult)){
				//$avalue=$farray[$listformat[1][5]];
			//}
			echo "<td ".$sorter_display_class." width=\"20%\" nowrap=\"nowrap\">".$absolutepathfull.$listformat[1][3].$rarray["struct_id"] . "</td>";
		break;
		default :
			echo "<td ".$sorter_display_class.">". stripslashes($rarray[$cellvalue]) . "</td>";
		break;
	}
}

//displays the general workflow form items
function generalform_workflow($action){
	global $primarykey, $container_id, $generalconfig, $status;
	//echo "status ".$status;
	//echo " userright ".$userright;
	//echo " task ".$_REQUEST["task"];
	$bgcolor="#F6F6F6";
	$span=5;
	$ruler = '<tr><td colspan="'.$span.'" class="ruler"></td></tr>';
	$ruler2 = '<tr><td colspan="'.$span.'" class="ruler2"></td></tr>';

	echo "<tr><td><br /></td></tr>";
	echo $ruler2;
	//echo "<tr bgcolor=\"#ffffff\"><td colspan=3 background='images/stab-bg.gif'><font color=\"\"><b>Meta information</b></font></td></tr>";
	if ($action<>'create' && $action<>"recreate" && $action<>"reedit"){
		$q = "SELECT * FROM struct WHERE container_id='".$container_id."' and content_id='".$_REQUEST[$primarykey]."'";
		$r = mysql_prefix_query($q);
		$resultarray= mysql_fetch_array($r);
	}
	elseif($action=="recreate" || $action=="reedit"){
		$resultarray["keywords"]=$_POST["keywords"];
		$resultarray["description"]=$_POST["description"];
		$resultarray["robot"]=$_POST["robot"];
		$resultarray["ondate"]=$_POST["ondate"];
		$resultarray["offdate"]=$_POST["offdate"];
		$resultarray["comment"]=$_POST["comment"];
	}
	else{
		$resultarray["keywords"]=$generalconfig["keywords"];
		$resultarray["description"]=$generalconfig["description"];
		$resultarray["robot"]=$generalconfig["robot"];
		$resultarray["comment"]=$generalconfig["comment"];
	}
	$localrec=array(
		array("ondate","wf_online","Online date","","",$generalconfig["ondate"]),
		array("offdate","wf_online","Offline date","","",$generalconfig["offdate"]),

	//array("p_id","valuefromurl","Parent",""),
	);
	//echo $ruler;
	$counter++;
	echo "<tr bgcolor=\"#F6F6F6\"><td class='tab-g' colspan=8><table cellpadding=0 cellspacing=0 border=0><tr><td><div id=open1".$counter." style=\"DISPLAY: block\"><a title=Expand  style=\"CURSOR: hand\" onClick=\"javascript:showLayer('rest1".$counter."'); hideLayer('rest2".$counter."'); hideLayer('open1".$counter."'); showLayer('close1".$counter."')\"><img src=\"dtreeimg/nolines_plus.gif\"></a></div><div id=close1".$counter." style=\"DISPLAY: none\"><a title=Collapse style=\"CURSOR: hand\" onClick=\"javascript:hideLayer('rest1".$counter."'); showLayer('rest2".$counter."'); showLayer('open1".$counter."'); hideLayer('close1".$counter."')\"><img src=\"dtreeimg/nolines_minus.gif\"></a></div></td><td class='tab-g'>Meta information</td></tr></table></td></tr>";
	echo $ruler;
	echo "<tr><td colspan=5 style=\"padding: 0;margin: 0\">";
	echo "<div id=rest1".$counter." style=\"DISPLAY: none\">";
	echo "<TABLE border=0 width=100% cellpadding=\"4\" cellspacing=\"0\">";
	echo "<tr bgcolor=\"".$bgcolor."\"><td width=15%>Keywords</td><td></td><td><INPUT class=\"form_input\" TYPE=\"TEXT\" NAME=\"keywords\" VALUE=\"".$resultarray["keywords"]."\" SIZE=\"79\" MAXLENGTH=\"255\"></td></tr>";
	echo $ruler."\n<tr bgcolor=\"".$bgcolor."\"><td width=15%>Description</td><td></td><td><INPUT class=\"form_input\" TYPE=\"TEXT\" NAME=\"description\" VALUE=\"".$resultarray["description"]."\" SIZE=\"79\" MAXLENGTH=\"255\"></td></tr>";		
	echo $ruler."\n<tr bgcolor=\"".$bgcolor."\"><td width=15%>Robot</td><td></td><td><INPUT class=\"form_input\" TYPE=\"TEXT\" NAME=\"robot\" VALUE=\"".$resultarray["robot"]."\" SIZE=\"79\" MAXLENGTH=\"255\"></td></tr>".$ruler."<tr><td></td></tr>";		
	echo "</table>";
	echo "</div>";
	echo "<div id=rest2".$counter." style=\"DISPLAY: block\"></div>";
	echo "</td></tr>";
	
	$counter++;
	echo $ruler2;
	echo "<tr bgcolor=\"#F6F6F6\"><td class='tab-g' colspan=8><table cellpadding=0 cellspacing=0><tr><td><div id=open1".$counter." style=\"DISPLAY: block\"><a title=Expand  style=\"CURSOR: hand\" onClick=\"javascript:showLayer('rest1".$counter."'); hideLayer('rest2".$counter."'); hideLayer('open1".$counter."'); showLayer('close1".$counter."')\"><img src=\"dtreeimg/nolines_plus.gif\"></a></div><div id=close1".$counter." style=\"DISPLAY: none\"><a title=Collapse style=\"CURSOR: hand\" onClick=\"javascript:hideLayer('rest1".$counter."'); showLayer('rest2".$counter."'); showLayer('open1".$counter."'); hideLayer('close1".$counter."')\"><img src=\"dtreeimg/nolines_minus.gif\"></a></div></td><td class='tab-g'>Workflow</td></tr></table></td></tr>";
	echo $ruler;
	echo "<tr><td colspan=5 style=\"padding: 0\">";
	echo "<div id=rest1".$counter." style=\"DISPLAY: none\">";
	echo "<TABLE border=0 width=100% cellpadding=\"4\" cellspacing=\"0\">";
	while (list($var, $val) = each($localrec)) {
		general_formelement($val, $resultarray, $action);
	}
	echo "<tr bgcolor=\"".$bgcolor."\"><td valign=\"top\">Comment</td><td></td><td><TEXTAREA class=\"form_input_textarea\" NAME=\"comment\" ROWS=\"5\" COLS=\"78\" wrap=\"VIRTUAL\">".$resultarray["comment"]."</TEXTAREA></td></tr>";
	echo $ruler;
	echo "</table>";
	echo "</div>";
	echo "<div id=rest2".$counter." style=\"DISPLAY: block\"></div>";
	echo "</td></tr>";

	//echo $ruler2;
}

//deletes record with wordkflow support
//deletes records from struct table
function general_deleterecord() {
	global $tablename, $primarykey, $container_id, $wf ;
	global $basedir, $fileupload, $filefield, $filenamefield, $storemethod, $BASE_URL, $BASE_ROOT, $fileuploaduserrestricted;
	global $userowneditemsonly;

	if ($fileuploaduserrestricted==true) {
    $result = mysql_prefix_query("SELECT * FROM user WHERE uid='" . $_SESSION["uid"] ."'");
		$login= @mysql_result($result,0,'login');
		//$BASE_ROOT=$BASE_ROOT.'/'.$login;
		$path=$_SERVER['DOCUMENT_ROOT']."/".$BASE_ROOT.'/'.$login;
	}
	
	if ($fileupload==true && $storemethod=='hd') {
		$r = mysql_prefix_query("SELECT $filenamefield from $tablename WHERE $primarykey ='" . $_REQUEST[$primarykey] ."'");
		if ($array=mysql_fetch_array($r)) {
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

//displays the default error box at the top of the list or form
function errorbox ($error, $blurbtype){
	echo "<br />";
	$blurbtype=='notify' ? $errorcolor='color="000000"' : $errorcolor='color="cc0000"';
	if($error<>'') {
		if ($blurbtype=='notify'){
			echo '<div style="border: #588ab0 solid; border-width:5px 0 0 0;"></div>';
		}
		else{
			echo '<div style="border: #cc0000 solid; border-width:5px 0 0 0;"></div>';
		}
		echo '<div style="background-color:#efefef; border: #ccc solid; border-width:1px; padding:10px 10px 10px 10px">';
		echo "<font ".$errorcolor."><b> ".$error."</b></font>";
		echo '</div><br />';
	}
}

//upper tabs
//this information should be configured in settings.inc.php, sorry for now
function jetstream_tabs ($sections, $navid, $nomenu=false){
	global $jetstream_url, $site_title, $uid, $pagetitle, $PHP_SELF, $no_auth_tabs, $auth_tabs;
	$tabs='';
	if(isset($_SESSION["uid"]) && $_SESSION["uid"]<>''){
		$pages= $auth_tabs;
	}
	else{
		$pages= $no_auth_tabs;
	}

	if ($nomenu==true) {
		if($sections==''){
			$sections[].="4";
			$navid="4";
		}
	}
	else{
		if($sections==''){
			$sections[].="3";
			$navid="3";
			if($pagetitle && $_SESSION["uid"]){
				$sections=array("4", "3");
				$navid="4";
			}
			if (isset($_SESSION["user_type"]) && $_SESSION["user_type"]=="administrator" ) {
				$sections[].="11";				    
			}
		}
		$sections[].="2";
		if(isset($_SESSION["uid"])){
			$sections[].="9";
		}
	}
	$i = 0;
	$lastselected = false;
	$tabs .= "<table border='0' cellspacing='0' cellpadding='0'><tr>";
	
	for ($i=0; $i<count($sections);$i++){
		list($sectionUrl, $sectionStr) = each($pages["$sections[$i]"]);
		$selected = ($navid == $sections[$i]);
		$tabs.= "<td>";
		if ($selected){
			$i > 0 ? $tabs.= "<img src='".$jetstream_url."/images/tab-mus.gif'>" : $tabs.= "<img src='".$jetstream_url."/images/tab-bs.gif' />";
			$tabs.= "</td><td class='tab-s' background='".$jetstream_url."/images/tab-sb.gif' valign='middle' nowrap=\"nowrap\">&nbsp;&nbsp;".$sectionStr."</td>";
		}
		else{
			$i > 0 ? ( $previousselected ? $tabs.= "<img src='".$jetstream_url."/images/tab-msu.gif' />" : $tabs.= "<img src='".$jetstream_url."/images/tab-muu.gif' />") : $tabs.= "<img src='".$jetstream_url."/images/tab-bu.gif' />";
			$tabs.= "</td><td background='".$jetstream_url."/images/tab-ub.gif' valign='middle' nowrap=\"nowrap\">&nbsp;&nbsp;<a class='tab-g' href='".$sectionUrl."'>".$sectionStr."</a></td>";
		}
		$previousselected = $selected;
	}
	$previousselected ? $tabs.= "<td><img src='".$jetstream_url."/images/tab-es.gif' /></td>" : $tabs.= "<td><img src='".$jetstream_url."/images/tab-eu.gif' /></td><td>&nbsp;</td>";
	$tabs.= "</tr></table>";
	return $tabs;
}

//displays the tabs for navigation and actions in the content area
function jetstream_ShowSections($sections, $pages, $navid){
	global $primarykey, $$primarykey, $jetstream_url;
	$tabs='';
	$tabs.= "<table border='0' cellpadding='0' cellspacing='0' width='100%' background='".$jetstream_url."/images/stab-bgdark.gif'  class=\"printhide\"><tr height='24'>";
	$tabs.= "<td width='5'><img src='".$jetstream_url."/images/stab-bgdark2.gif' width='5' height='24' /></td><td>";
	$tabs.= "<table border='0' cellpadding='0' cellspacing='0'><tr height='24'><td>&nbsp;</td>";
	for ($i=0; $i<count($sections);$i++){
		list($sectionUrl, $sectionStr) = each($pages["$sections[$i]"]);
		$selected = ($navid == $sections[$i]);
		$tabs.= "<td>";
		if ($selected){
			$i > 0 ? $tabs.= "<img src='".$jetstream_url."/images/stab-mus.gif' />" : $tabs.= "<img src='".$jetstream_url."/images/stab-bs.gif' />";
			$tabs.= "</td><td class='tab-s' background='".$jetstream_url."/images/stab-sb.gif' valign='middle' nowrap=\"nowrap\">&nbsp;&nbsp;".$sectionStr."</td>";
		}
		else{
			$i > 0 ? ( $previousselected ? $tabs.= "<img src='".$jetstream_url."/images/stab-msu.gif' />" : $tabs.= "<img src='".$jetstream_url."/images/stab-muu.gif' />") : $tabs.= "<img src='".$jetstream_url."/images/stab-bu.gif' />";
			$tabs.= "</td><td background='".$jetstream_url."/images/stab-ub.gif' valign='middle' nowrap=\"nowrap\">&nbsp;&nbsp;<a class='tab-g' href='".$sectionUrl."'>".$sectionStr."</a></td>";
		}
		$previousselected = $selected;
	}
	$previousselected ? $tabs.= "<td><img src='".$jetstream_url."/images/stab-es.gif' /></td>" : $tabs.= "<td><img src='".$jetstream_url."/images/stab-eu.gif' /></td>";
	$tabs.= "</tr></table></td><td width=\"1\"><img src='".$jetstream_url."/images/stab-bgdark2.gif' width='5' height='24' /></td></tr></table>";

	$tabs.= "<table width='100%' border='0' cellspacing='0' cellpadding='0' >";
	$tabs.= "<tr><td style=\"padding: 0px 5px 10px 5px;\">";
	$tabs.= "<table width='100%' border='0' cellspacing='0' cellpadding='0' bgcolor=\"#FFFFFF\" style=\"border: #8C8A8C solid; border-width: 0px 1px 1px 1px;\">";
	$tabs.= "<tr><td style=\"padding: 0px 3px 30px 7px\">";
	echo $tabs;
}

//displays a pulldown form item
function general_displaydropdown($pos, $selected, $dropdown, $argument='') {
	$q = "SELECT * FROM ".$dropdown[$pos][0]." ". $dropdown[$pos][3];
	$r = mysql_prefix_query($q) or die (mysql_error());
	$n = mysql_num_rows($r);
	echo "<select name=\"".$pos."\">";
	for ($i=0; $i < $n; $i++) {
		$primkey = mysql_result ($r,$i,$dropdown[$pos][1]);
		$displaytext = mysql_result ($r,$i,$dropdown[$pos][2]);
		if ($selected == $primkey) {
			echo "<option class=\"form_select\" selected value=\"". $primkey . "\">" . $displaytext;
		}
		else {
			echo "<option class=\"form_select\" value=\"". $primkey . "\">" . $displaytext ;
		}
	}
	echo "</select>";
}

//displays a pulldown form item
function general_linkedvalue($pos, $selected, $dropdown, $argument='') {
	$q = "SELECT * FROM ".$dropdown[$pos][0]." ". $dropdown[$pos][3] ." WHERE ". $dropdown[$pos][1]."='".$selected."'";
	$r = mysql_prefix_query($q) or die (mysql_error());
	$n = mysql_num_rows($r);
	if ($n) {
		return  $displaytext = mysql_result ($r,$i,$dropdown[$pos][2]);
	}
	else{
		return("none");
	}
}


//process date information
function general_date_process(){
	if ($_REQUEST["dbadmindatefield"]) {
		while (list($var, $value) = each($_REQUEST["dbadmindatefield"])) {
			$date_field=$value;
			$day = $date_field . "_day";
			$month = $date_field . "_month";
			$year = $date_field . "_year";
			$hour = $date_field . "_hour";
			$minute = $date_field . "_minute";

			$_REQUEST[$date_field]= $_REQUEST[$year] ."-". $_REQUEST[$month] ."-". $_REQUEST[$day]." ". $_REQUEST[$hour].":". $_REQUEST[$minute];
			$_POST[$date_field]= $_POST[$year] ."-". $_POST[$month] ."-". $_POST[$day]." ". $_POST[$hour].":". $_POST[$minute];
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
	$month[1]="January";
	$month[2]="February";
	$month[3]="March";
	$month[4]="April";
	$month[5]="May";
	$month[6]="June";
	$month[7]="July";
	$month[8]="August";
	$month[9]="September";
	$month[10]="October";
	$month[11]="November";
	$month[12]="December";
	$return ="<select name=\"".$inpone."\">";
	$return .="<option class=\"form_select\" value=\"0\">Day";
	for ($ii=1;$ii<=31;$ii++) {
		if ( $ii==$valone ) { $sel=" selected" ; } else { $sel="" ; }
		$return .="<option class=\"form_select\" value=\"".$ii."\"".$sel.">".$ii."";
	}
	$return .="</select>";
	$return .="<select name=\"".$inptwo."\">";
	$return .="<option class=\"form_select\" value=\"0\">Maand";
	for ($ii=1;$ii<=12;$ii++) {
		if ( $ii==$valtwo ) { $sel=" selected" ; } else { $sel="" ; }
		$return .="<option class=\"form_select\" value=\"".$ii."\"".$sel.">".$month[$ii]."";
	}
	$return .="</select>";
	$return .="<input class=\"form_input\" type=\"text\" name=\"$inpthree\" size=4 value=\"$valthree\">";
	return $return;
}

//display a "date" form item
//combination of three pulldown menus
function general_datetimeinput($inpone, $valone, $inptwo, $valtwo, $inpthree, $valthree, $inpfor, $valfor, $inpfive, $valfive) {
	if (!$valone && !$valtwo && !$valthree) {
		$valone = date("d");
		$valtwo = date("m");
		$valthree = date("Y");
		//$valfor = '00';
		//$valfive = '00';
	}
	$month[1]="January";
	$month[2]="February";
	$month[3]="March";
	$month[4]="April";
	$month[5]="May";
	$month[6]="June";
	$month[7]="July";
	$month[8]="August";
	$month[9]="September";
	$month[10]="October";
	$month[11]="November";
	$month[12]="December";
	$return ="\n<select name=\"".$inpone."\">";
	$return .="<option class=\"form_select\" value=\"0\">Day";
	for ($ii=1;$ii<=31;$ii++) {
		if ( $ii==$valone ) { $sel=" selected" ; } else { $sel="" ; }
		$return .="<option class=\"form_select\" value=\"".$ii."\"".$sel.">".$ii."";
	}
	$return .="</select>";
	$return .="\n<select name=\"".$inptwo."\">";
	$return .="<option class=\"form_select\" value=\"0\">Month";
	for ($ii=1;$ii<=12;$ii++) {
		if ( $ii==$valtwo ) { $sel=" selected" ; } else { $sel="" ; }
		$return .="<option class=\"form_select\" value=\"".$ii."\"".$sel.">".$month[$ii]."";
	}
	$return .="</select>";
	$return .=" <input class=\"form_input\" type=\"text\" name=\"$inpthree\" size=4 value=\"$valthree\">";
	$return .=" <select name=\"".$inpfor."\">";
	for ($ii=00;$ii<=23;$ii++) {
		if ( $ii==$valfor ) { $sel=" selected" ; } else { $sel="" ; }
		$return .="<option class=\"form_select\" value=\"".$ii."\"".$sel.">".sprintf("%02d", $ii)."";
	}
	$return .="</select>";
	$return .= ":\n<select name=\"".$inpfive."\">";
	for ($ii=00;$ii<=50;$ii+=10) {
		if ( $ii==$valfive ) { $sel=" selected" ; } else { $sel="" ; }
		$return .="<option class=\"form_select\" value=\"".$ii."\"".$sel.">".sprintf("%02d", $ii)."";
	}
	$return .="</select>";

	return $return;
}

function get_userrights($uid){
	global $container_id;
	if ($_SESSION["user_type"]<>'administrator'){
		$sql="SELECT * FROM userrights WHERE uid='".$_SESSION["uid"]."' AND container_id='$container_id'";
		$res = mysql_prefix_query($sql);
		if (mysql_num_rows($res)>0){
			$userright=mysql_result($res,0,'type');
		}
		else{
			$userright=false;
		}
	}
	else{
		$userright='administrator';
	}
	return $userright;
}

function get_status(){
	global $container_id, $primarykey;
	$sql = "SELECT * FROM struct WHERE container_id='".$container_id."' and content_id='".$_REQUEST[$primarykey]."'";
	if ($res = mysql_prefix_query($sql) or die (mysql_error())){
		return @mysql_result($res,0,'status');
	}
	else{
		return false;	
	}
}

function get_itemowner(){
	global $container_id, $primarykey;
	$sql = "SELECT * FROM struct WHERE container_id='".$container_id."' and content_id='".$_REQUEST[$primarykey]."'";
	if ($res = mysql_prefix_query($sql) or die (mysql_error())){
		return @mysql_result($res,0,'u_id');
	}
	else{
		return false;	
	}
}

// Generate unfoldable help section
function help_section($info_array, $ruler, $ruler2, $span, $showtop=true){
	global $counter;
	if (count($info_array)>0) {
		while(list($key, $val)= each($info_array)){
			if($h_sec<>''){
				$h_sec.="<tr><td class='tab-g' colspan=".$span.">".$key."</td></tr>";
				$h_sec.="<tr><td>".$val."</td></tr>";
				$h_sec.=$ruler;				    
			}
			else{
				if ($showtop) {
					$h_sec.="<tr><td>&nbsp;</tr></tr>";
					$h_sec.=$ruler2;
					$h_sec.="<tr bgcolor=\"#ffffff\"><td colspan=".$span." background='images/stab-bg.gif'><b>".$key."</b></td></tr>";
					$h_sec.=$ruler;				    
				}
				$counter++;
				$counter++;
				$h_sec.="<tr bgcolor=\"#F6F6F6\"><td class='tab-g' colspan=".$span."><table cellpadding=0 cellspacing=0><tr><td><div id=h_open".$counter." style=\"DISPLAY: block\"><a title=Expand  style=\"CURSOR: hand\" onClick=\"javascript:showLayer('h_sec_open".$counter."'); hideLayer('h_sec_close".$counter."'); hideLayer('h_open".$counter."'); showLayer('h_close".$counter."')\"><img src=\"dtreeimg/nolines_plus.gif\"></a></div><div id=h_close".$counter." style=\"DISPLAY: none\"><a title=Collapse style=\"CURSOR: hand\" onClick=\"javascript:hideLayer('h_sec_open".$counter."'); showLayer('h_sec_close".$counter."'); showLayer('h_open".$counter."'); hideLayer('h_close".$counter."')\"><img src=\"dtreeimg/nolines_minus.gif\"></a></div></td><td class='tab-g'>".$val."</td></tr></table></td></tr>";
				$h_sec.=$ruler;
				$h_sec.="<tr><td colspan=".$span." style=\"padding: 0\">";
				$h_sec.="<div id=h_sec_open".$counter." style=\"DISPLAY: none\">";
				$h_sec.="<TABLE border=0 width=100% cellpadding=\"4\" cellspacing=\"0\">";
			}
		}
		$h_sec.="</table>";
		$h_sec.="</div>";
		$h_sec.="<div id=h_sec_close".$counter." style=\"DISPLAY: block\"></div>";
		$h_sec.="</td></tr>";
		return $h_sec;
	}
} // end func

function show_format_javascript(){

}

# Display MySQL's last error message an die
//This function probably won't be available in later versions.
//You should not use this function
function mysql_die() {
	global $strMySQLError;
	echo "$strMySQLError: <br />";
	echo mysql_error();
	html_footer();
	exit;
}

/**
 * Generates a random string from the caracter set you pass in
 *
 * Flags:
 *   1 = add lowercase a-z to $chars
 *   2 = add uppercase A-Z to $chars
 *   4 = add numbers 0-9 to $chars
 */
function GenerateRandomString($size, $chars, $flags = 0) {
	if ($flags & 0x1) {
		$chars .= 'bcdfghjklmnpqrstvwxz';
	}
	if ($flags & 0x2) {
		$chars .= 'BCDFGHJKLMNPQRSTVWXZ';
	}
	if ($flags & 0x4) {
		$chars .= '0123456789';
	}

	if (($size < 1) || (strlen($chars) < 1)) {
		return '';
	}

	sq_mt_randomize(); /* Initialize the random number generator */

	$String = '';
	$j = strlen( $chars ) - 1;
	while (strlen($String) < $size) {
		$String .= $chars{mt_rand(0, $j)};
	}

	return $String;
}

/**
 * Randomize the mt_rand() function.  Toss this in strings or integers
 * and it will seed the generator appropriately. With strings, it is
 * better to get them long. Use md5() to lengthen smaller strings.
 */
function sq_mt_seed($Val) {
	/* if mt_getrandmax() does not return a 2^n - 1 number,
	 this might not work well.  This uses $Max as a bitmask. */
	$Max = mt_getrandmax();

	if (! is_int($Val)) {
		if (function_exists('crc32')) {
				$Val = crc32($Val);
		}
		else {
			$Str = $Val;
			$Pos = 0;
			$Val = 0;
			$Mask = $Max / 2;
			$HighBit = $Max ^ $Mask;
			while ($Pos < strlen($Str)) {
				if ($Val & $HighBit) {
					$Val = (($Val & $Mask) << 1) + 1;
				} else {
					$Val = ($Val & $Mask) << 1;
				}
				$Val ^= $Str[$Pos];
				$Pos ++;
			}
		}
	}
	if ($Val < 0) {
		$Val *= -1;
	}
	if ($Val = 0) {
		return;
	}
	mt_srand(($Val ^ mt_rand(0, $Max)) & $Max);
}

/**
 * This function initializes the random number generator fairly well.
 * It also only initializes it once, so you don't accidentally get
 * the same 'random' numbers twice in one session.
 */
function sq_mt_randomize() {
	global $_SERVER;
	static $randomized;
	if ($randomized) {
		return;
	}
	/* Global. */
	sq_mt_seed((int)((double) microtime() * 1000000));
	if (isset($_SERVER['REMOTE_PORT']) && isset($_SERVER['REMOTE_ADDR'])) {
		sq_mt_seed(md5($_SERVER['REMOTE_PORT'] . $_SERVER['REMOTE_ADDR'] . getmypid()));
	}
	/* getrusage */
	if (function_exists('getrusage')) {
		/* Avoid warnings with Win32 */
		$dat = @getrusage();
		if (isset($dat) && is_array($dat)) {
			$Str = '';
			foreach ($dat as $k => $v){
				$Str .= $k . $v;
			}
			sq_mt_seed(md5($Str));
		}
	}
	/* Apache-specific */
	if(isset($_SERVER['UNIQUE_ID'])) {
		sq_mt_seed(md5($_SERVER['UNIQUE_ID']));
	}
	$randomized = 1;
}

function validate_email($email) { 
	if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) { 
		return false;
	}
	else{
		return true;
	}
}

class SelectCategory {
	var $Name;
	var $SelectedId;
	var $IsMultiple;
	var $MultipleSize;
	var $ClassName;
	var $Id;
	var $Style;
	var $ActiveFlag;
	var $RootId;
	
	var $output;
	var $srcTable;
	var $firstOption;
	var $attributes;
	
	var $ExcludeId;

	function SelectCategory($name, $table) {
		$this->Name = $name;
		$this->SelectedId = '';
		$this->IsMultiple = false;
		$this->MultipleSize = 5;
		$this->FirstOption = array('value' => '', 'text' => '');
		$this->ClassName = '' ;
		$this->Id = '';
		$this->Style = '';
		$this->ActiveFlag = '';
		$this->RootId = 0;
		$this->output = '';
		$this->srcTable = $table;
	}
	
	function Output() {
		$options = '';
		$this->_getCategory($options, $this->RootId);
		$attributes = $this->_getAttributes();
		$output = '<select '.$attributes.'>'.$options.'</select>';
		return $output;
	}

	function SetFirstOption($text, $value) {
		$this->firstOption = array('value' => '', 'text' => '');
		$this->firstOption['value']	= $value;
		$this->firstOption['text'] = $text;
	}

	function _getAttributes() {
		$tmpAtt = array();
		$tmpAtt[] = 'name="'.$this->Name.'"';
		if($this->IsMultiple){
			$tmpAtt[] = 'multiple size="'.$this->MultipleSize.'"';
		}
		if(trim($this->ClassName) != ''){
			$tmpAtt[] = 'class="'.$this->ClassName.'"';
		}
		if(trim($this->Style) != ''){
			$tmpAtt[] = 'style="'.$this->Style.'"';
		}
		if(count($tmpAtt) > 0){
			$return = implode(' ', $tmpAtt);
		}
		else{
			$return = '';
		}
		return $return;
	}
	
	function _getCategory(&$options, $rootId, $level = 0) {
		if(is_array($this->firstOption) && trim($options) == '') {
			$options .= '<option value="'.$this->firstOption['value'].'">'.$this->firstOption['text'].'</option>';
		}
		
		$qry = 'SELECT id, name FROM '.$this->srcTable.' WHERE parent_id='.$rootId;
		
		if($this->ActiveFlag == 'Y' or $this->ActiveFlag == 'N'){
			$qry .= ' AND active="'.$this->ActiveFlag.'"';
		}
		$qry .= ' AND id<>"'.$this->ExcludeId.'" ORDER BY rank, name ASC';
		//echo $qry."<br />";
		$rsl = mysql_prefix_query($qry);
		if(mysql_num_rows($rsl) > 0) {
			while($row = mysql_fetch_assoc($rsl)) {
				if($row['id'] == $this->SelectedId){
					$selected = ' selected';
				}
				else{
					$selected = ''	;
				}
				$options .= '<option value="'.$row['id'].'" '.$selected.'>'.str_repeat('&nbsp;', $level*4).'- '.$row['name']."</option>\n";
				if ($row['id']<>$this->ExcludeId) {
					$this->_getCategory($options, $row['id'], $level+1);
				}
			}
		}		
	}
}

?>