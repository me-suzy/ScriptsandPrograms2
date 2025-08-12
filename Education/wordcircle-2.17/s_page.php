<?php

/*

CLASS
-----
PAGE


PROPERTIES
----------
header
footer
javascript


METHODS
-------
tab()
pleaseWait()
overview()


*/

class page {


function head($title,$javaScript="",$boxtip="",$showMenu=1){
	
	if(isset($_GET['a'])){$action=$_GET['a'];}else{$action='';}

  echo('
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<xhtml xmlns="http://www.w3.org/1999/xhtml">
<head>
');

include ("e_style.html");

echo('

<title>'.$title.'</title>
</head>

<BODY>');

include("e_header.html");

echo('<TABLE cellspacing="0" cellpadding="0" width="100%" border="0" align="center" class="underheader">
        <TR>
         <TD  width="100%" colspan="2"><IMG src="icon_singlepx.gif" width="1" height="3" border="0"></TD>
        </TR>
        <TR>
         <TD width="60%" align="left">');
		 if ($this->checkSecurity() == false) {


	echo('&nbsp;&nbsp;<a href="index.php" >Home</a> &middot;
		<a href="index.php?a=login"  >Login</a> &middot;
		<a href="index.php?a=register"  >Register</a> &middot;
		<a href="index.php?a=help">Help</a> &middot; You are not logged in');

    }else{
		if($showMenu==1){
		echo('&nbsp;&nbsp;<a href="index.php" >My Courses</a> &middot;
		
		<a href="index.php?a=members&gid='.$_GET['gid'].'"  >User Info</a> &middot;
		
		<a href="index.php?a=logout"  >Logout</a> &middot;
		
		<a href="index.php?a=help"  >Help</a> &middot;');

	$user =  new user();
	

	echo(" Logged in as ".$GLOBALS['user']->first_name." ".$GLOBALS['user']->last_name."<br />");
}else{

	echo('&nbsp;&nbsp;<a href="index.php" >My Courses</a> &middot;
		
		<a href="index.php?a=members"  >User Info</a> &middot;
		<a href="index.php?a=logout"  >Logout</a> &middot;
		
		<a href="index.php?a=help"  >Help</a> &middot;');

	$user =  new user();
	

	echo(" Logged in as ".$GLOBALS['user']->first_name." ".$GLOBALS['user']->last_name."<br />");
	
}
}

		 echo('
		 
         </TD>
         <TD width="40%" align="right"> ' .  (date("l F jS")) . '&nbsp;&nbsp;&nbsp;</TD>
        </TR>
        <TR>
         <TD  width="100%" colspan="2"><IMG src="icon_singlepx.gif" width="1" height="3" border="0"></TD>
        </TR>
      </TABLE>
      
      <TABLE cellspacing="0" cellpadding="0" width="100%" border="0" align="center">
        <TR>
              <TD class="textarea" valign="top" width="100%">');
			  if(isset($_GET['gid'])){
			 $result = $GLOBALS['db']->execQuery("select group_name from groups where group_id = ".$_GET['gid']);
	while ($row = mysql_fetch_assoc($result)) { 
	   echo("<div class='coursedismess' align='center'><strong>Course: </strong>".$row['group_name']."<br><br />
	   <a href='index.php?a=view&gid=".$_GET['gid']."&inc=".$GLOBALS['short_increment']."' class='menubar'><img src='icon_home.gif' width='15' height='15' border=0 align='top' hspace=1 />Course Overview</a> &middot; <a href='index.php?a=thoughts&gid=".$_GET['gid']."' class='menubar'><img src='icon_news.gif' width='10' height='11' border=0 align='top' hspace=1 />News</a> &middot; <a href='index.php?a=calendar&gid=".$_GET['gid']."' class='menubar'><img src='icon_cal.gif' width='15' height='16' border='0' align='top' hspace=1 />Calendar</a> &middot; 
<a href='index.php?a=discuss&gid=".$_GET['gid']."' class='menubar'><img src='icon_group.gif' width='14' height='17' border='0' align='top' hspace=1 />Discussions</a> &middot; <a href='index.php?a=documents&gid=".$_GET['gid']."' class='menubar'><img src='icon_materials.gif' width='14' height='16' hspace=1 border=0 align='top' />Course Materials</a> &middot; <a href='index.php?a=projects&gid=".$_GET['gid']."' class='menubar'><img src='icon_proj.gif' width='14' height='14' border=0 hspace=1 align='top' />Projects</a><br><br></div>");
   }
			  }
}

function code($length=7, $list="23456789ABCEFGHJKLMNPQRSTUVWXYZ"){
mt_srand((double)microtime()*1000000);
$thoughtstring="";
if($length>0){
while(strlen($thoughtstring)<$length){
$thoughtstring.=$list[mt_rand(0, strlen($list)-1)];
}
}
	
	$result = $GLOBALS['db']->execQuery("select group_id from groups where code = '".$thoughtstring."'");
	if (mysql_num_rows($result)){$thoughtstring=$this->code();}
return $thoughtstring;
}

function pleaseWait($message,$url){
	echo('<script language="JavaScript" type="text/javascript">
					window.setTimeout("window.location.href=\''.$url.'\'",2000);
					</script>
					
					');
					$GLOBALS['page']->tableStart("","50%","TAB","Working...");
					echo('<table align="center">
					<tr><td align="center"><br>
					<br>
					<strong>'.$message.'</strong><br>
					<br>
					<br>
					
					<img src="icon_circle.gif" width="200" height="40" alt=""><br>
					<br>
					<br>
					<br></td></tr>
					</table><br>
					<br>');
					$GLOBALS['page']->tableEnd("TAB");
	
}

 
  function displayError($errorNum){ 
  echo('<script language="JavaScript">window.alert("' . implode("\\n", $errorNum) . '")</script>');
  }
 
function tableStart($class,$width,$type,$image="",$linkArray=array()){
  //type: GRID, FORM, TAB, NAVIGATION
  //queryArray is for a grid only
  //secure if false show message and exit
  //link array is for navigation
  
  switch(strtoupper($type)) {
  
  case "GRID":
  echo("<table width='".$width."' class='".$class."' cellpadding='3' cellspacing='0'>");
  break;
  
  case "FORM":
  echo("
		<script language='JavaScript'>
		<!-- Copyright 2000 by William and Will Bontrager.
		counter=-1;
		function count() {
		counter++;
		if(counter > 1) {
		if(counter > 2) { return false; }
		alert('This operation will take some time\\n\\nIf you keep re-submitting the page it will just take longer!');
		return false;
		}
		return true;
		} // -->
		</script>


  <form action='".$_SERVER['PHP_SELF'].(strlen($_SERVER['QUERY_STRING']) > 0 ?"?".$_SERVER['QUERY_STRING'] : "")."' method='post' onSubmit='count()'><table class='".$class."' width='".$width."' cellpadding='0' cellspacing='0'>");
  break;
  
  case "TEXT":
  echo('<table width="'.$width.'" cellpadding="5" cellspacing="0"  align="center">
   <tr>
   <td>');
   break;

  case "TAB" : //$image is now just text - got rid of tab images in v. 0.04
  echo('<table width="100%" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td class="tab" align="left"  nowrap ><img src="icon_singlepx.gif" width="5" height="1" alt="" /><img src="icon_arrow.gif" width="8" height="8" alt="" />'.$image.'</td>
	
		<td class="tablink" align="center">
		
		'.(($GLOBALS['owner'] == true and count($linkArray) > 0)?'<a href="'.$linkArray[0].'" style="text-decoration:none"><img src="icon_tools.gif" width="16" height="16" alt="" border="0" align="top" /> Modify</a>':'<img src="icon_singlepx.gif" width="1" height="1" alt="" />').'

		</td>
	</tr>
	<tr>
		<td width="'.$width.'" colspan="2" class="tabcontents">');
  break;
  
  case "NAVIGATION" :
    $zz=0;
    for ($ir=0;$ir < count($linkArray);$ir++){
    foreach( $linkArray[$ir] as $key => $val ){
    echo ("&nbsp;<a href='".$val."' class='".$class."'>".$key."</a>");
    if(count($linkArray)>$zz)
    {echo("<span class=". $class. "&nbsp;&nbsp;|&nbsp;&nbsp;</span> ");}
    $zz++;
    }
    
   }
   
  
  
  }
 
 }
 
function tableEnd($type){

 switch(strtoupper($type)){
 
  case "NAVIGATION":
  break;
  
  case "TAB":
  echo('</td>
	</tr>
</table>');
  break;
  
  case "TEXT":
  echo("</td></tr></table>");
  break;
  
  case "FORM":
  echo("</table></form>");
  break;
  
  case "GRID":
  echo("</table>");
  break;
  
 }
 
 }
 
function pageEnd(){

  echo('<br />
                </TD>
             </TR>
            </TBODY>
          ');
include("e_footer.html");
	  
echo('		  
</BODY>
</HTML>
');
  if (count($GLOBALS['error'])>0){$this->displayError($GLOBALS['error']);}
 }

function welcomeMessage(){
	
	include("e_welcome.html");
	
} 

function checkSecurity(){
   
    //see if a security cookie exists and check the code combo
    if(isset($_COOKIE['loggedin'])){
     $user = new user();
     
     
     $link = mysql_connect($GLOBALS["dbServer"], $GLOBALS["dbUser"], $GLOBALS["dbPass"]) or  $errorCode = mysql_error();
        mysql_select_db($GLOBALS["dbName"]) or $errorCode = mysql_error();
    
	 //the next chunk of code figures out if the user is who they say they are (if they
	 //messed with their cookies or not). If the url contains 'gid' then we check
	 //if they belong to that group - prevents url manipulation.
	 //if they are trying to edit something (a=editx) then we make sure they are a teacher
	  
	 //if there is a 'gid' and 'edit' then do a query for admin and group belongs to them
	 if(isset($_GET['gid']) and isset($_GET['a']) and (substr_count($_GET['a'],"edit")>0)){
     $result = mysql_query("select users.user_id from users,user_groups,groups where users.user_id = " . trim($GLOBALS['user']->user_id) . " and security_code = '" . trim($GLOBALS['user']->security_code) . "' and groups.group_id = user_groups.group_id and users.user_id = user_groups.user_id and groups.group_id = ".$_GET['gid']." and groups.owner_id = " . trim($GLOBALS['user']->user_id)) or die('<br><br>Your browser cookies have been corrupted. Please delete your cookies by
looking in the options or preferences menu of your browser.  There should be a button in one of these menus to delete or remove your cookies.  Once the cookies have been deleted, close your browser and then re-open it.  Then return to the website and login. This should solve the problem.');

		 
	 $result2 = mysql_query("select groups.group_id from groups where group_id = ".$_GET['gid']." and (owner_id = " . trim($GLOBALS['user']->user_id) ."  or ".(( trim($GLOBALS['admin_email']) == trim($GLOBALS['user']->email)   )?"1=1":"1=0").")"  );
	
	if(mysql_num_rows($result2) > 0){$GLOBALS['owner'] = true;}
	
	}
	elseif(isset($_GET['gid'])){
	//if there is a 'gid' then make sure this person owns this group
	 $result = mysql_query("select users.user_id from users,user_groups where users.user_id = " . trim($GLOBALS['user']->user_id) . " and security_code = '" . trim($GLOBALS['user']->security_code) . "' and users.user_id = user_groups.user_id and (group_id = ".$_GET['gid'] ." or ".(( trim($GLOBALS['admin_email']) == trim($GLOBALS['user']->email)   )?"1=1":"1=0").")") or die('<br><br>Your browser cookies have been corrupted. Please delete your cookies by
looking in the options or preferences menu of your browser.  There should be a button in one of these menus to delete or remove your cookies.  Once the cookies have been deleted, close your browser and then re-open it.  Then return to the website and login. This should solve the problem.');
	 
	 $result2 = mysql_query("select groups.group_id from groups where group_id = ".$_GET['gid']." and (owner_id = " . trim($GLOBALS['user']->user_id)." or ".(( trim($GLOBALS['admin_email']) == trim($GLOBALS['user']->email)   )?"1=1":"1=0").")");

	if(mysql_num_rows($result2) > 0){$GLOBALS['owner'] = true;}

	}else{
	//no 'gid' - just see if they match their cookie
	     $result = mysql_query("select user_id from users where user_id = " . trim($GLOBALS['user']->user_id) . " and security_code = '" . trim($GLOBALS['user']->security_code) . "'") or die('<br><br>Your browser cookies have been corrupted. Please delete your cookies by
looking in the options or preferences menu of your browser.  There should be a button in one of these menus to delete or remove your cookies.  Once the cookies have been deleted, close your browser and then re-open it.  Then return to the website and login. This should solve the problem.');
	
	}
	 
     if(mysql_num_rows($result) > 0 or $GLOBALS['owner']==true){
	 	if(trim($GLOBALS['admin_email']) == trim($GLOBALS['user']->email)){$GLOBALS['owner']=true;};
     return true;
     } else {
     //kill thy cookie
     setcookie ("loggedin", " ", time() - 3600);
	  $GLOBALS['error'][0] = 'You have been logged out due to technical problems\nPlease login again';  
     return false;
	   
     }
    } else {
    //no cookie, not logged in
    return false;
    }
   
  } 
  
  function rows($columnArray=array(),$oddClass, $evenClass, $width="10%",$totalRows=0,$action="dummy"){
  $nextpage="index.php";
  if(!isset($_GET['inc'])){
  $increment = $GLOBALS['increment'];
  }else{
  $increment = $_GET['inc'];
  }
  //columnArray are columns to show in order arr[0]['name']='value'
  $endo="";
  if ($totalRows > 0 and $totalRows>$increment){
		$numpages = ceil(($totalRows-1) / $increment);
		$endo .= "<tr><td colspan='".count($columnArray[0])."' class='menubar'> ";
		$qs = $_SERVER['QUERY_STRING'];
		if (isset($_GET['a']) and $_GET['a'] == "view"){
		$endo .= "<a href='index.php?a=".$action."&gid=".$_GET['gid']."' class='more'>click here for complete list</a>";
		}else{
		$endo .= "Page:";
		for($ug=0;$ug < $numpages;$ug++){
		$endo .= "<a href='".$nextpage.(strlen($qs) > 0 ?"?".eregi_replace("((&)pagenum=?[0-9]*)","",$qs) : "")."&pagenum=".$ug."' class='menubar'>".
( (isset($_GET['pagenum']))?(($ug == $_GET['pagenum'])?"<strong>[".($ug+1)."]</strong>":($ug+1)):(($ug==0)?"<strong>[".($ug+1)."]</strong>":($ug+1)))."</a> ";	
	 	 }
		 }
    if(strlen($qs) > 0 ){$qs=eregi_replace("((&)pagenum=?[0-9]*)","",$qs);}
	if(strlen($qs) > 0 ){$qs=eregi_replace("((&)inc=?[0-9]*)","",$qs);}
	if($_GET['a']<>"view"){$endo .= "<a href='".$nextpage."?".$qs."&inc=100000' class='menubar'>view all</a>";}
	$endo .= "</td></tr>";
	}
   
   if(array_key_exists(0,$columnArray)){
   echo($endo);
   echo('<tr>');
     foreach($columnArray[0] as $key => $val){
       echo ("<td nowrap><strong>".$key."</strong></td>");
     }
	 echo("</tr>");
   }
   
     for($id=0;$id < count($columnArray);$id++) {
	 echo("<tr class='".(($id % 2 == 0)?$evenClass:$oddClass)."'>");
	 $zz=0;
     foreach($columnArray[$id] as $key => $val){
      echo ((($zz != 0) ? "<td valign='top'>".$val."</td>" : "<td width='".$width."' valign='top'>".$val."</td>"));
	$zz++;
     }
	    if(array_key_exists(0,$columnArray)){
    	echo("</tr>");
   }
  
  }
   echo($endo);
 } 

 function checkbox($checkSuperArray,$name,$desc,$class,$chngeOnPost){
  //see above for radio - same idea except more than one default is possible w/ chkbx
  
  echo("<tr class='".$class."'><td align='right'>".$desc."</td><td>");
  
  for($ib=0;$ib < count($checkSuperArray);$ib++){
  $checked = 0;
   foreach($checkSuperArray[$ib] as $key => $val){
   
   if ((isset($_POST[$name])) and ($chngeOnPost==1) and (in_array($val,$_POST[$name])) ){$checked=1;}
   
   echo("<input type='checkbox' name='".$name."[]' value='".$val."' class='".$class."' ".(($checked==1)?" checked ":"").">".$key."<br>");
   }
  }
  echo("</td></tr>");
 }
 
 function text($value,$name,$class,$desc,$size,$chngeOnPost=0){
  //see above
  if (isset($_POST[$name]) and ($chngeOnPost==1) ){$val = $_POST[$name];}else{$val = $value;}
  echo("<tr><td align='right'>".$desc."</td><td><input type='text' name='".$name."' value='".$val."' class='".$class."' size='".$size."'></td></tr>");
 }
 
 function password($value,$name,$class,$desc,$size,$chngeOnPost=0){
  //see above
  if (isset($_POST[$name]) and ($chngeOnPost==1) ){$val = $_POST[$name];}else{$val = $value;}
  echo("<tr><td align='right'>".$desc."</td><td><input type='password' name='".$name."' value='".$val."' class='".$class."' size='".$size."'></td></tr>");
 }
 
function textarea($value,$name,$class,$rows,$cols,$desc,$chngeOnPost){
  //see above
  if (isset($_POST[$name]) and ($chngeOnPost==1) ){$val = $_POST[$name];}else{$val = $value;}
  echo("<tr><td align='right' valign='top'>".$desc."</td><td><textarea name='".$name."' class='".$class."' rows='".$rows."' cols='".$cols."'>".$val."</textarea></td>");
  
 }

function select($superValueArray,$dflt,$name,$class,$desc,$chngeOnPost){
  //see above
  
  echo("<tr><td align='right'>".$desc."</td><td><select name='".$name."' class='".$class."'>");
  
  for($ib=0;$ib < count($superValueArray);$ib++){
  $checked = 0;
   foreach($superValueArray[$ib] as $key => $val){
   
   if ((isset($_POST[$name])) and ($chngeOnPost==1) and ($_POST[$name]==$val) ){$checked=1;}
   if (!isset($_POST[$name]) and ($val==$dflt)){$checked=1;}
   if ((isset($_POST[$name])) and ($chngeOnPost==0) and ($val==$dflt)){$checked=1;}
   
   echo("<option value='".$val."' ".(($checked==1)?" selected ":"").">".$key."</option>");
   }
  }
  echo("</select></td></tr>");
 }
 
function multiselect($superValueArray,$name,$class,$desc,$chngeOnPost){
  //see above  
  echo("<tr><td align='right' valign='top'>".$desc."</td><td><select name='".$name."' class='".$class."' multiple>");
  
  for($ib=0;$ib < count($superValueArray);$ib++){
  $checked = 0;
   foreach($superValueArray[$ib] as $key => $val){
   
   if ((isset($_POST[$name])) and ($chngeOnPost==1) and (in_array($val,$_POST[$name])) ){$checked=1;}
   
   echo("<option value='".$val."' ".(($checked==1)?" selected ":"").">".$key."</option>");
   }
  }
  echo("</select></td></tr>");
  
 }
 
function datebox(     $sel_d = 0       // selected day
                         , $sel_m = 0       // selected month
                         , $sel_y = 0       // selected year
                         , $var_d = 'd'     // name for day variable
                         , $var_m = 'm'     // name for month variable
                         , $var_y = 'y'     // name for year variable
                         , $min_y = 0       // minimum year
                         , $max_y = 0       // maximum year
          , $chngeOnPost = 0
          , $desc
          , $class
		  , $includeTime = false
		  , $time = "00:00:00"
    )
 {
 // --------------------------------------------------------------------------
     // First of all, set up some sensible defaults
   
     if(isset($_POST[$var_d]) and $chngeOnPost == 1){
      $sel_d = $_POST[$var_d];
    $sel_m = $_POST[$var_m];
    $sel_y = $_POST[$var_y];
     }else{
    
     // Default day is today
     if ($sel_d == 0) 
       $sel_d = date('j');
   
     // Default month is this month
     if ($sel_m == 0) 
       $sel_m = date('n');
   
     // Default year is this year
     if ($sel_y == 0) 
       $sel_y = date('Y');
      
      } 
  
     // Default minimum year is this year
     if ($min_y == 0) 
       $min_y = date('Y');
   
     // Default maximum year is two years ahead
     if ($max_y == 0) 
       $max_y = ($min_y + 2);
   
   
     // --------------------------------------------------------------------------
     // Start off with the drop-down for Days
     
     // Start opening the select element
     echo ('<tr><td align="right">'.$desc.'</td><td><select class="'.$class.'" name="'. $var_d. '"');
   
     // Add disabled attribute if necessary
   
     // Finish opening the select element
     echo ('>');
   
     // Loop round and create an option element for each day (1 - 31)
     for ($i = 1; $i <= 31; $i++) {
   
       // Start the option element
       echo('<option value="'. $i. '"');
   
       // If this is the selected day, add the selected attribute
       if ($i == $sel_d) 
         echo(' selected ');
   
       // Display the value and close the option element
       echo('>'. $i. '</option>');
   
     }
   
     // Close the select element
     echo('</select>');
   
   
     // --------------------------------------------------------------------------
     // Now do the drop-down for Months
   
     // Start opening the select element
     echo('<select class="'.$class.'" name="'. $var_m. '"');
   
     // Add disabled attribute if necessary
     
     // Finish opening the select element
     echo('>');
   
     // Loop round and create an option element for each month (Jan - Dec)
     for ($i = 1; $i <= 12; $i++) {
   
       // Start the option element
       echo('<option value="'. $i. '"');
   
       // If this is the selected month, add the selected attribute
       if ($i == $sel_m) 
         echo(' selected="selected"');
   
       // Display the value and close the option element
       echo('>'. date('F', mktime(3, 0, 0, $i)). '</option>');
   
     }
   
     // Close the select element
     echo('</select>');
   
   
     // --------------------------------------------------------------------------
     // Finally, the drop-down for Years
   
     // Start opening the select element
     echo('<select class="'.$class.'" name="'. $var_y. '"');
   
   
     // Finish opening the select element
     echo('>');
   
     // Loop round and create an option element for each year ($min_y - $max_y)
     for ($i = $min_y; $i <= $max_y; $i++) {
   
       // Start the option element
       echo('<option value="'. $i. '"');
   
       // If this is the selected year, add the selected attribute
       if ($i == $sel_y) 
         echo(' selected ');
   
       // Display the value and close the option element
       echo('>'. $i. '</option>');
   
     }
   
     // Close the select element
     echo('</select>');
	 
	 if($includeTime == true){

	 echo(" Time: ".$this->create_time_pulldowns($time));
	 
	 }	 
	 
	 echo('</td></tr>');

 }
 
 function create_time_pulldowns($time="00:00:00"){                                                             
    global $hour_pulldown, $minute_pulldown, $ampm_pulldown;                  
    $this->create_hour_pulldown(substr($time,0,2));                                                     
    $this->create_minute_pulldown(substr($time,3,2));                                                             
    $this->create_ampm_pulldown(substr($time,0,2));                                                         
    $output = $hour_pulldown.' '.$minute_pulldown.' '.$ampm_pulldown;     
    return $output;                                                                                                             
    } 
     
function create_hour_pulldown($selected_hour){ 
    global $hour_pulldown; 
    if ($selected_hour > 12) $selected_hour = $selected_hour-12; 
    if ($selected_hour == 00) $selected_hour = $selected_hour +12; 
    $hour_pulldown = '<SELECT NAME="hour" class="inputs">'; 
	$stopit = 0;
    for ($i=0; $i<12; $i++) { 
            $hour_pulldown .= '<OPTION VALUE="'; 
            if ($i<9) $hour_pulldown .= '0'; 
            if ($i ==11) { 
                $hour_pulldown .= '0"'; 
                } else $hour_pulldown .= ($i+1).'"'; 
            if 	(isset($_POST['hour']) and (intval($_POST['hour']) == $i+1)){ 
                $hour_pulldown .= ' SELECTED'; 
				$stopit = 1;
                } elseif
				($selected_hour==$i+1 and $stopit == 0){ 
                $hour_pulldown .= ' SELECTED'; 
                } 
            $hour_pulldown .= '>'; 
            if ($i<9) $hour_pulldown .= '0'; 
            $hour_pulldown .= ($i+1).'</OPTION>'; 
            } 
    $hour_pulldown .= '</SELECT>:'; 
    } 

function create_minute_pulldown($selected_minute){ 
    global $minute_pulldown; 
    $minute_pulldown .= '<SELECT NAME="minute" class="inputs">'; 
    $i = 0; 
	$stopit = 0;
    while ($i<46) { 
            $minute_pulldown .= '<OPTION VALUE="'; 
            if ($i==0) { $minute_pulldown .= '0'; } 
            $minute_pulldown .= $i.'"'; 
			if (isset($_POST['minute']) and (intval($_POST['minute']) == $i)){ $minute_pulldown .= ' SELECTED';$stopit = 1; }elseif ($i == $selected_minute and $stopit == 0) { $minute_pulldown .= ' SELECTED'; } 
            $minute_pulldown .= '>'; 
            if ($i==0) { $minute_pulldown .= '0'; } 
            $minute_pulldown .= $i; 
            $minute_pulldown .= '</OPTION>'; 
            $i= $i + 15; 
            } 
    $minute_pulldown .= '</SELECT>'; 
    } 

function create_ampm_pulldown($selected_hour){ 
    global $ampm_pulldown; 
	$stopit = 0;
    $ampm_pulldown = '<SELECT NAME="ampm" class="inputs">'; 
    $ampm_pulldown .= '<OPTION VALUE="am"';
	if (isset($_POST['ampm']) and (($_POST['ampm']) == 'am')){$ampm_pulldown .= ' SELECTED';$stopit = 1;}
    if ($selected_hour < 12  and $stopit == 0) $ampm_pulldown .= ' SELECTED'; 
    $ampm_pulldown .= '>am</OPTION>'; 
    $ampm_pulldown .= '<OPTION VALUE="pm"'; 
	if (isset($_POST['ampm']) and (($_POST['ampm']) == 'pm')){$ampm_pulldown .= ' SELECTED';$stopit = 1;}
    if ($selected_hour >= 12 and $stopit == 0) $ampm_pulldown .= ' SELECTED'; 
    $ampm_pulldown .= '>pm</OPTION></SELECT>'; 
} 

 
function submit($value,$class){

  echo("<tr class='".$class."'><td>&nbsp;</td><td><input type='submit' name='submit' value='".$value."' class='".$class."' onClick='return count()'></td></tr>");
 
 }

 
 
  
 }



?>