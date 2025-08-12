<?php
//===========================================================================
//* --    ~~                Crafty Syntax Live Help                ~~    -- *
//===========================================================================
//           URL:   http://www.craftysyntax.com/    EMAIL: ericg@craftysyntax.com
//         Copyright (C) 2003-2005 Eric Gerdes   (http://www.craftysyntax.com )
// --------------------------------------------------------------------------
// $              CVS will be released with version 3.1.0                   $
// $    Please check http://www.craftysyntax.com/ or REGISTER your program for updates  $
// --------------------------------------------------------------------------
// NOTICE: Do NOT remove the copyright and/or license information any files. 
//         doing so will automatically terminate your rights to use program.
//         If you change the program you MUST clause your changes and note
//         that the original program is Crafty Syntax Live help or you will 
//         also be terminating your rights to use program and any segment 
//         of it.        
// --------------------------------------------------------------------------
// LICENSE:
//     This program is free software; you can redistribute it and/or
//     modify it under the terms of the GNU General Public License
//     as published by the Free Software Foundation; 
//     This program is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.
//
//     You should have received a copy of the GNU General Public License
//     along with this program in a file named LICENSE.txt .
// --------------------------------------------------------------------------  
// BIG NOTE:
//     At the time of the release of this version of CSLH, Version 3.1.0 
//     which is a more modular, extendable , skinable version of CSLH
//     was being developed.. please visit http://www.craftysyntax.com to see if it was released! 
//===========================================================================
require_once("admin_common.php");

// error number:
if(!(isset($UNTRUSTED['err']))){ $err = 0; } else { $err = intval($UNTRUSTED['err']); }

// proccess login:
if(!(isset($UNTRUSTED['proccess']))){ $UNTRUSTED['proccess'] = "no"; }
if($UNTRUSTED['proccess'] == "yes"){
      if(validate_user($UNTRUSTED['myusername'],$UNTRUSTED['mypassword'],$identity)){
      	// TEMP :----
      	// In version 3.1.0 of CSLH I am going to separate out the sessions database
      	// table from the users. This session swapping is just a temporary thing till
      	// i finish the user database mapping feature in version 3.1.0 
      	$query = "DELETE FROM livehelp_users WHERE password='' AND sessionid='".$identity['SESSIONID']."'";	
      	$mydatabase->query($query);
      	$twentyminutes  = date("YmdHis", mktime(date("H"), date("i")+20,date("s"), date("m")  , date("d"), date("Y")));
        $query = "UPDATE livehelp_users 
                  SET identity='".filter_sql($identity['IDENTITY'])."',
                      ipaddress='".$identity['IP_ADDR']."',
                      hostname='".$identity['HOSTNAME']."',                      
                      expires='$twentyminutes',
                      lastaction='$twentyminutes',
                      authenticated='Y',
                      sessionid='".$identity['SESSIONID']."' 
                  WHERE username='".filter_sql($UNTRUSTED['myusername'])."' 
                    AND password='".filter_sql($UNTRUSTED['mypassword'])."'";	
        $mydatabase->query($query);       	
      	
      	// ----  // TEMP // ---      	
        $query = "SELECT * 
                  FROM livehelp_users 
                  WHERE sessionid='".$identity['SESSIONID']."'";	
        $person_a = $mydatabase->query($query);
        $person = $person_a->fetchRow(DB_FETCHMODE_ASSOC);
        $visits = $person['visits'];
        $isadminsetting = $person['isadmin'];
        $visits++;
        $query = "UPDATE livehelp_users 
                  SET visits=".intval($visits)." 
                  WHERE sessionid='".$identity['SESSIONID']."'";	
        $mydatabase->query($query);      
        if(empty($UNTRUSTED['adminsession']))
          $UNTRUSTED['adminsession'] = "N";
        if(empty($UNTRUSTED['matchip']))
          $UNTRUSTED['matchip'] = "N";
          
        $query = "UPDATE livehelp_config
                  SET matchip='".filter_sql($UNTRUSTED['matchip'])."',adminsession='".filter_sql($UNTRUSTED['adminsession'])."'";
        $mydatabase->query($query);          
                      
        // update history for operator to show login:
        $query = "INSERT INTO livehelp_operator_history (opid,action,dateof,sessionid,totaltime) VALUES (".$person['user_id'].",'login','".date("YmdHis")."','".$identity['SESSIONID']."',0)";
        $mydatabase->query($query);
        
         ?>
      	<SCRIPT>
        function gothere(){
        	<?php if ($isadminsetting == "L" ) { ?>
            window.location.replace("live.php?cslhOPERATOR=<?php echo $identity['SESSIONID']; ?>");
          <?php } else { ?>
            window.location.replace("admin.php?cslhOPERATOR=<?php echo $identity['SESSIONID']; ?>");          	
          <?php } ?>	
        }
        </SCRIPT>
      	<?php
        
        // show updates, news and security warnings once every 15 logins.
        // Do not remove this! There are Security holes discovered
        // every day in Open source programs and not knowing about them
        // could be fatal to your website.    
        if( ($visits % 12) == 11){
         $lines = "";	
         //TODO: replace with XML HTTP Request..
         $url = "http://www.craftysyntax.com/remote/news.php?v=" . $CSLH_Config['version'] . "&m=" . $CSLH_Config['membernum'] . "&h=" . $_SERVER['HTTP_HOST'];
         $file = @fopen ($url, "r");
         if ($file) {
          while (!feof ($file)) {
            $lines .= fgets ($file, 1024);
          }
          fclose($file);
          print $lines;
         } else {
         ?>
       <h2><?php echo $lang['txt92']; ?></h2>          
       <SCRIPT>
        setTimeout("gothere();",4000);
       </SCRIPT> 
       <?php   
        }
      } else {	
       ?>
       <h2><?php echo $lang['txt92']; ?></h2>          
       <SCRIPT>
        setTimeout("gothere();",4000);
       </SCRIPT> 
       <?php       
      }
      exit;
    } else {
    	 // username/password fail:
    	 $err = 2; 
    } 
$mydatabase->close_connect();
}
?>
<SCRIPT>
if (window.self != window.top){ window.top.location = window.self.location; }
</SCRIPT>

<center><br><br>
<body bgcolor=D3DBF1 onLoad="document.login.myusername.focus()">
<SCRIPT type="text/javascript" SRC="javascript/hideshow.js"></SCRIPT>
<SCRIPT type="text/javascript">
function showoptions(){
	makeVisible ('moreoptions');
	makeInvisible ('moreoptionsinfo');
}
function hideoptions(){
	makeInvisible ('moreoptions');
	makeVisible ('moreoptionsinfo');
}
function helpwindow(num){
	if(num == 1){
		makeVisible ('helpone');
	}
	if (num == 2){
		makeVisible ('helptwo');
	}	
	if (num == 3){
		makeInvisible('helptwo');
		makeInvisible('helpone');
	}	
}
</SCRIPT>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
<center>

<table border=0 cellpadding=0 cellspacing=0 width=450>
<tr>
<td width=1 height=1 bgcolor=000000><img src=images/blank.gif width=1 height=1 border=0></td>
<td width=99% height=1 bgcolor=000000><img src=images/blank.gif width=400 height=1 border=0></td>
<td width=1 height=1 bgcolor=000000><img src=images/blank.gif width=1 height=1 border=0></td>
</tr>
<tr>
<td width=1 height=1 bgcolor=000000><img src=images/blank.gif width=1 height=1 border=0></td>
<td width=99% background=images/nav_bg.gif align=right><a href=http://craftysyntax.com/ target=_blank><img src=images/version.gif width=267 height=32 border=0></a></td>
<td width=1 height=1 bgcolor=000000><img src=images/blank.gif width=1 height=1 border=0></td>
</tr>
<tr>
<td width=1 height=1 bgcolor=000000><img src=images/blank.gif width=1 height=1 border=0></td>
<td width=99% height=1 bgcolor=000000><img src=images/blank.gif width=400 height=1 border=0></td>
<td width=1 height=1 bgcolor=000000><img src=images/blank.gif width=1 height=1 border=0></td>
</tr>
</table>

<table bgcolor="#F7FAFF" cellpadding=0 cellspacing=0 border=0 width=450>
<tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1 border=0></td>
<td align=center>

 <table>
 <tr>
  <td><img src=images/keys.jpg width=150 height=121></td>
  <td>

<form action=login.php METHOD=post name=login>
<input type=hidden name=proccess value=yes>
<br><br>
<table>
<tr><td colspan=2 align=center>  <font face="Arial, Helvetica, sans-serif" size="2" color=000077><b>Crafty Syntax Live Help        Login:</b></font><br>
<font size=2 color=770077><b>Version <?php echo $CSLH_Config['version']; ?></b></font><br>
<br>
<?php
// username/password incorrect
if($err == 2){ 
print $lang['txt94'];
}
// logged out.
if($err == 3){ 
print $lang['txt95']; 
}
?>
<br> </td></tr>
<tr><td><b>Username:</b></td><td><input type=text name=myusername value=""></td></tr>
<tr><td><b>Password:</b></td><td><input type=password name=mypassword></td></tr>
<tr><td colspan=2 align=center><a href=lostsheep.php><?php echo $lang['txt96']; ?></a></td></tr>
</table>
<DIV ID="moreoptionsinfo">
<center><a href="javascript:showoptions()">More options</a></center>
</DIV>
<br>
<?php
if($CSLH_Config['adminsession'] == "N"){
 $adminsession_n = " CHECKED ";	
 $adminsession_y = "  ";
} else {
 $adminsession_y = " CHECKED ";	
 $adminsession_n = "  ";
}
if($CSLH_Config['matchip'] == "N"){
 $matchip_n = " CHECKED ";	
 $matchip_y = "  ";
} else {
 $matchip_y = " CHECKED ";	
 $matchip_n = "  ";
}
?>

<DIV ID="moreoptions" STYLE="display:none">
<table border=1 STYLE="border-style: dashed">
<tr><td><b><a href="#" onmouseover="helpwindow(1)" onmouseout="helpwindow(3)">Match Ip to Session:</a></b></td><td> YES<input type=radio name=matchip value=Y <?php echo $matchip_y; ?>> NO<input type=radio name=matchip value=N <?php echo $matchip_n; ?>></td></tr>
<tr><td><b><a href="#" onmouseover="helpwindow(2)" onmouseout="helpwindow(3)">Use PHP Sessions:</a></b></td><td> YES<input type=radio name=adminsession <?php echo $adminsession_y; ?> value=Y> NO<input type=radio name=adminsession <?php echo $adminsession_n; ?> value=N></td></tr>
</table>
<center><a href="javascript:hideoptions()">Hide Options</a></center>
</DIV>
<br>
<center><input type=submit value=Login></center>
</form>
</td></tr></table>
<br>
powered by <a href=http://www.craftysyntax.com/ target=_blank>Crafty Syntax Live Help <?php echo $CSLH_Config['version']; ?></a> 
<br><br>
<font size=-2>
<!-- Note if you remove this line you will be violating the license even if you have modified the program -->
<a href=http://www.craftysyntax.com/ target=_blank>Crafty Syntax Live Help</a> &copy; 2003 - 2005 by <a href=http://www.craftysyntax.com/EricGerdes/ target=_blank>Eric Gerdes</a>  
<br>
CSLH is  Software released 
under the <a href=http://www.gnu.org/copyleft/gpl.html target=_blank>GNU/GPL license</a>
</font>
</td>
<td bgcolor=000000><img src=images/blank.gif width=2 height=1 border=0></td>
</tr>

<tr>
<td bgcolor=000000 colspan=4 height=2><img src=images/blank.gif width=300 height=2 border=0></td>
</tr>
</table>
<DIV ID="helpone" STYLE="z-index:4; position:absolute; left:215; top:96; width:346; height:94; visibility:hidden;">
  <table width=300>
  <tr><td bgcolor=FFFFEE>
    "Match Ip to Session" is a security setting which, when enabled, 
    requires that the session ID used to access
    the administration matches the operators IP address
    used when logging into the admin. This is to prevent
    users who follow referer links clicked on in the "data" tab that have the session ID in
    the query string from gaining access to the admin. However,
    the downside in enabling this is that operators with dynamic 
    ip addresses will not be able to log in.
  </td></tr></table>
</DIV>

<DIV ID="helptwo" STYLE="z-index:4; position:absolute; left:215; top:226; width:346; height:94; visibility:hidden;">
  <table width=300>
  <tr><td bgcolor=FFFFEE>
  "Use PHP Sessions" when enabled has Crafty Syntax use PHP sessions for the operator logging 
  in rather then just using cookies.  
    </td></tr></table>
</DIV>
</center>