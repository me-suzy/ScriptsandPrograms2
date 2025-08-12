<?php
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.1.1                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 05/01/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# File name               install.php                      #
# File purpose            Installation script              #
# File created by         AzDG <support@azdg.com>          #
############################################################
/* 
Using Old HTTP_POST for detect variables 
*/
if (isset($HTTP_POST_VARS)) {while(list($name,$value) = each($HTTP_POST_VARS)){$$name = $value;};}; 
if (isset($HTTP_GET_VARS)) {while(list($name,$value) = each($HTTP_GET_VARS)){$$name = $value;};}; 

/* 
Detecting user language. Installation will continue with user
language or default language
*/
if(!isset($l)) $l = $HTTP_SERVER_VARS['HTTP_ACCEPT_LANGUAGE'];
if(!file_exists('languages/'.$l.'/inst.php')) $l='default';
if(!file_exists('languages/'.$l.'/inst.php')) {echo "<h3>Install Error:<br>Please upload inst.php from AzDGDatingLite package into languages/default/ directory</h1>";die;};
include_once 'languages/'.$l.'/inst.php';

/*
We like to work with short tags.
And we chaking that this parameter must be enable
*/
if(!(ini_get("short_open_tag"))) {echo $y[0];die;}

/*
Several variables for design and script name
*/
define('C_SNAME_','AzDGDatingLite v 2.1.1');
define('COLOR1','#A0C0F0'); 
define('COLOR2','#73AED2'); 
define('COLOR3','#84B8D7'); 
define('COLOR4','#92C0DC');
define('COLORH','#73AED2');

$error='0';
if(!isset($s)) $s='0';

function check($var) {
  global $error,$y;
  if($var) {
    echo '<span class="ok">'.$y[1].'<span>';
  } else {
    echo '<span class="false">'.$y[2].'<span>';
  $error='1';
  }
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 TRANSITIONAL//EN">
<html dir=<?=C_HTML_DIR?>>
<head>
<title><?=C_SNAME_?> <?=$y[3]?></title>
<meta http-equiv=Content-Type content="text/html; charset=<?=C_CHARSET?>">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<style>
<!--
a:link{text-decoration:none;font-size:11px;color:blue}
a:visited{text-decoration:none;font-size:11px;color:blue}
a:hover{text-decoration:none;font-size:11px;color:black}
body{font-family:"Verdana";font-size:12px;scrollbar-face-color: #73AED2;scrollbar-highlight-color:#BDD3F4;scrollbar-shadow-color: #73AED2;scrollbar-3dlight-color:#73AED2;scrollbar-arrow-color: #BDD3F4;scrollbar-track-color:#A0D8F8;scrollbar-darkshadow-color: black;}
.pass{font-family:"Verdana";font-size:12px;color: red;}
.mes{font-family:Verdana,tahoma;font-size:11px;color:black;font-weight:bold;test-align:justify;}
sup{font-family:Verdana,tahoma;font-size:12px;color:white;font-weight:normal;}
.desc{font-family:Verdana,tahoma;font-size:10px;color:black;font-weight:bold;}
.dat{font-family:Verdana,tahoma;font-size:12px;color:red;font-weight:bold;}
.ok{font-family:Verdana,tahoma;font-size:12px;color:lime;font-weight:bold;}
.false{font-family:Verdana,tahoma;font-size:12px;color:red;font-weight:bold;}
.head{font-family:Arial Black, Arial;font-size:20px;color:black;}
.error{font-family:Verdana,Helvetica;font-size:16px;color:red;font-weight:bold;}
.button{font-family:Verdana;font-weight:bold;font-size:11px;color:black;background:#A0C0F0;border: black 1px solid; width:140px;cursor:hand;}
.input,.textarea,.select{font-family:Verdana;font-size:12px;color:black;background:#C2FBFE;border: black;border-style: solid;border-top-width: 1px;border-right-width: 1px;border-bottom-width: 1px;border-left-width: 1px;width:250px}
.binput{font-family:Verdana;font-size:12px;color:black;background:#C2FBFE;border: black 1px solid;width:500px}
.sinput{font-family:Verdana;font-size:12px;color:black;background:#C2FBFE;border: black 1px solid;width:60px}
.msinput{font-family:Verdana;font-size:12px;color:black;background:#C2FBFE;border: black 1px solid;width:90px}
.minput{font-family:Verdana;font-size:12px;color:black;background:#A0D8F8;border: black 1px solid;width:120px}
-->
</style>

<script language="JavaScript">
<!--
function bc(b,bg){b.style.backgroundColor = bg;}
function open_win(win_file, win_title) {
window.open(win_file, win_title, 'resizable=yes,width=400,height=300,toolbar=no,scrollbars=yes,location=no,menubar=no,status=no');
}
//-->
</script>
</head>
<body bgcolor="#C2FBFE">
<center><table width="80%"><tr><td>
<basefont size="4" color="navy" face="Verdana,Tahoma">
<center><span class=head><?=C_SNAME_?> <?=$y[3]?></span>
<hr color=navy size=2>
<?php 
switch($s) {

######################################
#   S T E P - 0 [Accepting License]
######################################
case '0':
?>
<Table CellSpacing="1" CellPadding="0" width="600" bgcolor="black"><Tr><Td>
<Table Border=0 CellSpacing="1" CellPadding="4" width="600" class=mes>
<Tr bgcolor="<?=COLOR3?>" align=center><Td>
:: <?=$y[7]?> ::
</td></tr>
<Tr bgcolor="<?=COLOR4?>" align=center><td>
	<textarea cols="100" rows="20" class="binput">
	<?php if(!(@readfile("./gpl.txt"))) {echo $y[8];$error=1;}?>
	</textarea>
</td></tr>
</table></td></tr></table>
<br>
<?= ($error) ? '<span class=error>'.$y[9].'</span><br><br><input type="button" class=button id=but1 onmouseover=bc(this,"'.COLOR2.'") onmouseout=bc(this,"'.COLOR1.'") href="javascript:;" OnClick="location.reload()" value="'.$y[10].'">' : '<input type="button" class=button id=but1 onmouseover=bc(this,"'.COLOR2.'") onmouseout=bc(this,"'.COLOR1.'") href="javascript:;" OnClick="location.href=\'http://www.azdg.com/\'" value="'.$y[11].'"> &nbsp; &nbsp; <input type="button" class=button id=but2 onmouseover=bc(this,"'.COLOR2.'") onmouseout=bc(this,"'.COLOR1.'") href="javascript:;" OnClick="location.href=\'install.php?s=1\'" value="'.$y[12].'">';
break;

######################################
#   S T E P - 1 [Checking for requirement values]
######################################
case '1':
?>
<Table CellSpacing="1" CellPadding="0" width="600" bgcolor="black"><Tr><Td>
<Table Border=0 CellSpacing="1" CellPadding="4" width="600" class=mes>
<Tr bgcolor="<?=COLOR3?>"><Td colspan=4>
:: <?=$y[13]?> 1 :: <?=$y[14]?>
</td></tr>
<Tr bgcolor="<?=COLOR4?>" align=center><td>
<?=$y[15]?></td><td><?=$y[16]?></td><td><?=$y[17]?></td><td><?=$y[18]?></td></tr>
<Tr bgcolor="<?=COLOR1?>" align=center><td>
<?=$y[19]?></td><td>v 4.1.0</td><td>v <?=phpversion();?></td><td><?php check(phpversion() >= '4.1.0');?></td></tr>
<Tr bgcolor="<?=COLOR4?>" align=center><td>
<?=$y[20]?></td><td>v 3.23.15</td><td>v <?=mysql_get_client_info();?></td><td><?php check(mysql_get_client_info() >= '3.23.15');?></td></tr>
</table></td></tr></table>
<br>

<Table CellSpacing="1" CellPadding="0" width="600" bgcolor="black"><Tr><Td>
<Table Border=0 CellSpacing="1" CellPadding="4" width="600" class=mes>
<Tr bgcolor="<?=COLOR3?>"><Td colspan=3>
<?=$y[21]?>
</td></tr>
<?php
umask(0);
if(file_exists("include/config.inc.php")) @chmod("include/config.inc.php",0666);
if(file_exists("members/uploads")) @chmod("members/uploads",0777);
if(file_exists("include/options.inc.php")) @chmod("include/options.inc.php",0666);
?>
<Tr bgcolor="<?=COLOR4?>" align=center><td>members/uploads/</td><td><?=$y[22]?></td><td><?php check((file_exists("members/uploads") && @is_writable("members/uploads")));?></td></tr>
<Tr bgcolor="<?=COLOR1?>" align=center><td>
include/config.inc.php</td><td><?=$y[22]?></td><td><?php check((file_exists("include/config.inc.php") && @is_writable("include/config.inc.php")));?></td></tr>
<Tr bgcolor="<?=COLOR4?>" align=center><td>
include/options.inc.php</td><td><?=$y[22]?></td><td><?php check((file_exists("include/options.inc.php") && @is_writable("include/options.inc.php")));?></td></tr>
</table></td></tr></table>
<br><br>
<?= ($error) ? '<span class=error>'.$y[9].'</span><br><br><input type="button" class=button id=but1 onmouseover=bc(this,"'.COLOR2.'") onmouseout=bc(this,"'.COLOR1.'") href="javascript:;" OnClick="location.reload()" value="'.$y[10].'">' : '<input type="button" class=button id=but1 onmouseover=bc(this,"'.COLOR2.'") onmouseout=bc(this,"'.COLOR1.'") href="javascript:;" OnClick="location.href=\'install.php?s=3&no_check=1\'" value="'.$y[72].'"> &nbsp; &nbsp; &nbsp; <input type="button" class=button id=but2 onmouseover=bc(this,"'.COLOR2.'") onmouseout=bc(this,"'.COLOR1.'") href="javascript:;" OnClick="location.href=\'install.php?s=2\'" value="'.$y[24].' 2">';?>

<? 
break;
######################################
#   S T E P - 2 [Creating configuration]
######################################

case '2':

include_once 'include/config.inc.php';
/* 
Detecting script path
*/ 
$int_path = realpath(".");
$int_path=str_replace("\\","/",$int_path);
$ext_path='http://'.getenv('SERVER_NAME').dirname(getenv('REQUEST_URI'));
$ext_path=str_replace("\\","/",$ext_path);

// Default values:
$c_url = (C_URL != 'http://www.test.net/AzDGDatingLite') ? C_URL : $ext_path;  
$c_path = (C_PATH != 'Z:/home/www.test.net/www/AzDGDatingLite') ? C_PATH : $int_path;  
$c_sname = (substr(C_SNAME,0,10) != 'AzDGDating') ? C_SNAME : C_SNAME_;
$c_host = (C_HOST != 'localhost') ? C_HOST : 'localhost';  
$c_user = (C_USER != 'user') ? C_USER : '';  
$c_pass = ''; // :) 
$c_base = (C_BASE != 'database') ? C_BASE : '';
$c_adminl = (C_ADMINL != '') ? C_ADMINL : '';  
$c_adminp = ''; // :)  
$c_adminm = (C_ADMINM != 'admin@yoursite.com') ? C_ADMINM : 'admin@yoursite.com';
$c_adminlang = (C_ADMINLANG != 'default') ? C_ADMINLANG : 'default';

?>
<Table CellSpacing="1" CellPadding="0" width="600" bgcolor="black"><Tr><Td>
<Table Border=0 CellSpacing="1" CellPadding="4" width="600" class=mes>
<Tr bgcolor="<?=COLOR3?>"><Td colspan=2>
:: <?=$y[13]?> 2 :: <?=$y[25]?>
</td></tr>
<script language="JavaScript">
<!--
function formCheck(form) {

if (form.c_url.value == "")
{alert("<?=$y[26]?>");return false;}
if (form.c_path.value == "")
{alert("<?=$y[27]?>");return false;}
if (form.c_sname.value == "")
{alert("<?=$y[28]?>");return false;}
if (form.c_host.value == "")
{alert("<?=$y[29]?>");return false;}
if (form.c_user.value == "")
{alert("<?=$y[30]?>");return false;}
if (form.c_pass.value == "")
{alert("<?=$y[31]?>");return false;}
if (form.c_base.value == "")
{alert("<?=$y[32]?>");return false;}

if (form.c_adminl.value == "")
{alert("<?=$y[33]?>");return false;}
if (form.c_adminp.value == "")
{alert("<?=$y[34]?>");return false;}
if (form.c_adminm.value == "")
{alert("<?=$y[35]?>");return false;}



if (document.form.submit.action != "") {
document.form.submit.disabled=1;}
}
// -->
</script>
<form method="Post" action="install.php?s=3" OnSubmit="return formCheck(this)" name="form">

<Tr bgcolor="<?=COLORH?>" align=center><Td colspan=2>
<?=$y[36]?>
</td></tr>
<Tr bgcolor="<?=COLOR4?>"><td>
<?=$y[37]?></td><td><input type="text" name="c_url" value="<?=$c_url?>" class=input></td></tr>
<Tr bgcolor="<?=COLOR4?>"><Td colspan=2>
<sup><?=$y[38]?></sup>
</td></tr>
<Tr bgcolor="<?=COLOR1?>"><td>
<?=$y[39]?></td><td><input type="text" name="c_path" value="<?=$c_path?>" class=input></td></tr>
<Tr bgcolor="<?=COLOR1?>"><Td colspan=2>
<sup><?=$y[40]?></sup>
</td></tr>
<Tr bgcolor="<?=COLOR4?>"><td>
<?=$y[41]?></td><td><input type="text" name="c_sname" value="<?=$c_sname?>" class=input></td></tr>
</table></td></tr></table>
<br>

<Table CellSpacing="1" CellPadding="0" width="600" bgcolor="black"><Tr><Td>
<Table Border=0 CellSpacing="1" CellPadding="4" width="600" class=mes>
<Tr bgcolor="<?=COLORH?>" align=center><Td colspan=2>
<?=$y[42]?>
</td></tr>
<Tr bgcolor="<?=COLOR4?>"><td>
<?=$y[43]?></td><td><input type="text" name="c_host" value="<?=$c_host?>" class=input></td></tr>
<Tr bgcolor="<?=COLOR4?>"><Td colspan=2>
<sup><?=$y[44]?>:localhost</sup>
</td></tr>
<Tr bgcolor="<?=COLOR1?>"><td>
<?=$y[45]?></td><td><input type="text" name="c_base" value="<?=$c_base?>" class=input></td></tr>
<Tr bgcolor="<?=COLOR1?>"><Td colspan=2>
<sup><?=$y[46]?></sup>
</td></tr>
<Tr bgcolor="<?=COLOR4?>"><td>
<?=$y[47]?></td><td><input type="text" name="c_user" value="<?=$c_user?>" class=input></td></tr>
<Tr bgcolor="<?=COLOR1?>"><td>
<?=$y[48]?></td><td><input type="password" name="c_pass" value="<?=$c_pass?>" class=input></td></tr>
<Tr bgcolor="<?=COLOR1?>"><td>
<?=$y[49]?></td><td><input type="text" name="c_pref" value="pro" class=input></td></tr>
</table></td></tr></table>
<br>

<Table CellSpacing="1" CellPadding="0" width="600" bgcolor="black"><Tr><Td>
<Table Border=0 CellSpacing="1" CellPadding="4" width="600" class=mes>
<Tr bgcolor="<?=COLORH?>" align=center><Td colspan=2>
<?=$y[50]?>
</td></tr>
<Tr bgcolor="<?=COLOR4?>"><td>
<?=$y[51]?></td><td><input type="text" name="c_adminl" value="<?=$c_adminl?>" class=input></td></tr>
<Tr bgcolor="<?=COLOR1?>"><td>
<?=$y[52]?></td><td><input type="password" name="c_adminp" value="<?=$c_adminp?>" class=input></td></tr>
<Tr bgcolor="<?=COLOR4?>"><td>
<?=$y[53]?></td><td><input type="text" name="c_adminm" value="<?=$c_adminm?>" class=input></td></tr>
<Tr bgcolor="<?=COLOR1?>"><td>
<?=$y[54]?></td><td>
<?php
$handle=opendir('languages/');
   $fnm = 0;
   while (false!==($file = readdir($handle))) { 
      if ($file != "." && $file != "..") {
	  $langfile[$fnm] = $file;
      $fnm++;
      } 
   }
   closedir($handle); 
if ($fnm == 0) echo $y[6];
elseif ($fnm > 1) {
?>
<select name="c_adminlang" class="input">
<?
for ($i = 0; $i < $fnm; $i++) {
if(isset($langfile[$i]) && ($langfile[$i] == $c_adminlang)) echo '<option value="'.$langfile[$i].'" selected>'.$langfile[$i];
else echo '<option value="'.$langfile[$i].'">'.$langfile[$i];
}
}
?>
</select>
</td></tr>
<Tr bgcolor="<?=COLOR1?>"><Td colspan=2>
<sup><?=$y[55]?></sup>
</td></tr>
</table></td></tr></table>
<br>
<?='<input type="submit" class=button id=but2 onmouseover=bc(this,"'.COLOR2.'") onmouseout=bc(this,"'.COLOR1.'") href="javascript:;" value="'.$y[24].' 3" name="submit">';?>
</form>
<? 
break;

######################################
#   S T E P - 3 [Checking for Step 2]
######################################

case '3':

function check_last_slash($file) {
  if (ereg("./$", $file)) return false;
  else return true;
}

function cb($ss) 
{
//$ss = htmlspecialchars(stripslashes($ss));
//$ss = str_replace("\r\n"," <br>","$ss");
//$ss = str_replace("\\","","$ss");
$ss = str_replace("'","&rsquo;","$ss");
$ss = str_replace('"',"&quot;","$ss");
$ss = trim($ss);
return $ss;
}

function error($er) {
?>
<br>
<center><Table CellSpacing="1" CellPadding="0" width="600" bgcolor="black"><Tr><Td><Table Border=0 CellSpacing="1" CellPadding="4" width="600" class=mes><Tr bgcolor="<?=COLOR3?>"><Td align=center class=error><?=$er?></td></tr></table></td></tr></table>
</center><br>
<?php
exit();
}

if (!((isset($no_check)) && ($no_check == '1'))) {

if(!check_last_slash($c_url)) error($y[56]);
if(!check_last_slash($c_path)) error($y[57]);
if(!file_exists($c_path.'/install.php')) error($y[58]);
$c_url=cb($c_url);
$c_path=cb($c_path); 
$c_sname=cb($c_sname);
$c_host=cb($c_host); 
$c_base=cb($c_base); 
$c_pass=cb($c_pass); 
$c_user=cb($c_user); 
$c_pref=cb($c_pref); 
$c_adminl=cb($c_adminl); 
$c_adminp=cb($c_adminp); 
$c_adminm=cb($c_adminm); 
$c_adminlang=cb($c_adminlang); 

function vars_write($file,$data,$real) {
$cnt = file($file);$fp = fopen($file,"w");flock($fp, LOCK_EX);
for ($i=0;$i<count($cnt);$i++) {
  if (strpos($cnt[$i],"','")) {
    list($param,$value) = split("','",$cnt[$i]);
    if (trim($param) == "define('".$data) $cnt[$i] = "define('".$data."','".$real."');\n";
	}
}    
fwrite($fp, implode("",$cnt));fflush($fp);flock($fp, LOCK_UN);
fclose($fp);
}

function file_write($file,$data) {
$fp = fopen($file,"w");flock($fp, LOCK_EX);
fwrite($fp, $data);fflush($fp);flock($fp, LOCK_UN);fclose($fp);
}

$str="<?php\n";
$str.="session_start();unset(\$s);unset(\$m);\n";
$str.="############################################################\n";
$str.="# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #\n";
$str.="############################################################\n";
$str.="# AzDGDatingLite          Version 2.0.5                    #\n";
$str.="# Writed by               AzDG (support@azdg.com)          #\n";
$str.="# Created 03/01/03        Last Modified 20/03/03           #\n";
$str.="# Scripts Home:           http://www.azdg.com              #\n";
$str.="############################################################\n";
$str.="# File name               config.inc.php                   #\n";
$str.="# File purpose            Main configuration file          #\n";
$str.="# File created by         AzDG <support@azdg.com>          #\n";
$str.="############################################################\n";
$str.="### Url were AzDGDatingLite has been installed, not '/' in end!\n";
$str.="define('C_URL','".$c_url."');\n";
$str.="\n";
$str.="### Internal path to AzDGDatingLite directory\n";
$str.="define('C_PATH','".$c_path."');\n";
$str.="\n";
$str.="### Site Name\n";
$str.="define('C_SNAME','".$c_sname."');\n";
$str.="\n";
$str.="### Admin Data\n"; 
$str.="define('C_ADMINL','".$c_adminl."');// Admin login\n";
$str.="define('C_ADMINP','".$c_adminp."');// Admin password\n";
$str.="define('C_ADMINM','".$c_adminm."');//Admin email\n";
$str.="define('C_ADMINLANG','".$c_adminlang."');//Admin language (By lang dir example: en)\n";
$str.="\n";
$str.="### MySQL data\n";
$str.="define('C_HOST','".$c_host."');// MySQL host name (usually:localhost)\n";
$str.="define('C_USER','".$c_user."');// MySQL username\n";
$str.="define('C_PASS','".$c_pass."');// MySQL password\n";
$str.="define('C_BASE','".$c_base."');// MySQL database\n";
$str.="\n";
$str.="define('C_MYSQL_MEMBERS','".$c_pref."_membersu');// Table for members info\n";
$str.="define('C_MYSQL_ONLINE_USERS','".$c_pref."_onlineu');// Table for online users info\n";
$str.="define('C_MYSQL_ONLINE_QUESTS','".$c_pref."_onlineq');// Table for online quests\n";
$str.="define('C_MYSQL_TEMP','".$c_pref."_temp');// Table for temporary info\n";
$str.="?>\n";

file_write("include/config.inc.php",$str);
if(ini_get('safe_mode')) vars_write("include/options.inc.php","C_IMG_ERR","1");
sleep(1);
}
include_once 'include/config.inc.php';
include_once 'include/options.inc.php';
@mysql_connect(C_HOST, C_USER, C_PASS) or error($y[59]); 
@mysql_select_db(C_BASE) or error($y[60]);
?>

<Table CellSpacing="1" CellPadding="0" width="600" bgcolor="black"><Tr><Td>
<Table Border=0 CellSpacing="1" CellPadding="4" width="600" class=mes>
<Tr bgcolor="<?=COLOR3?>"><Td colspan=2>
:: <?=$y[13]?> 3 :: <?=$y[61]?>
</td></tr>
<Tr bgcolor="<?=COLOR4?>" align=center><td>
<?=$y[62]?></td><td><?=$y[63]?></td></tr>
<Tr bgcolor="<?=COLOR1?>" align=center>
<td><?=C_MYSQL_TEMP?></td>
<td><?php
$sql = "CREATE TABLE IF NOT EXISTS ".C_MYSQL_TEMP." (
  id smallint unsigned NOT NULL,
  date DATE NOT NULL default '0',
  code char(32) NOT NULL default ''
)";
if(mysql_query($sql)) echo "<span class=ok>".$y[64]."</span>";
else echo "<span class=false>".$y[65]."</span>";
?></td></tr>
<Tr bgcolor="<?=COLOR4?>" align=center>
<td><?=C_MYSQL_MEMBERS?></td>
<td><?php
$sql = "CREATE TABLE IF NOT EXISTS ".C_MYSQL_MEMBERS." (
  id smallint unsigned ZEROFILL NOT NULL auto_increment,
  fname varchar(16) NOT NULL default '',
  lname varchar(30) NOT NULL default '',
  password char(16) NOT NULL default '',
  birthday DATE NOT NULL default '0',
  gender tinyint unsigned NOT NULL default '0',
  purposes tinyint unsigned NOT NULL default '0',
  country tinyint unsigned NOT NULL default '0',
  email varchar(64) NOT NULL default '',
  url varchar(64) NOT NULL default '',
  icq int unsigned NOT NULL default '0',
  aim varchar(16) NOT NULL default '',
  phone varchar(20) NOT NULL default '',
  city varchar(32) NOT NULL default '',
  marstat tinyint unsigned NOT NULL default '0',
  child tinyint unsigned NOT NULL default '0',
  height tinyint unsigned NOT NULL default '0',
  weight tinyint unsigned NOT NULL default '0',
  hcolor tinyint unsigned NOT NULL default '0',
  ecolor tinyint unsigned NOT NULL default '0',
  etnicity tinyint unsigned NOT NULL default '0',
  religion tinyint unsigned NOT NULL default '0',
  smoke tinyint unsigned NOT NULL default '0',
  drink tinyint unsigned NOT NULL default '0',
  education tinyint unsigned NOT NULL default '0',
  job varchar(30) NOT NULL default '',
  hobby tinytext NOT NULL default '',
  descr text NOT NULL default '',
  sgender tinyint unsigned NOT NULL default '0',
  setnicity tinyint unsigned NOT NULL default '0',
  sreligion tinyint unsigned NOT NULL default '0',
  agef tinyint unsigned NOT NULL default '0',
  aget tinyint unsigned NOT NULL default '0',
  heightf tinyint unsigned NOT NULL default '0',
  heightt tinyint unsigned NOT NULL default '0',
  weightf tinyint unsigned NOT NULL default '0',
  weightt tinyint unsigned NOT NULL default '0',
  hdyfu tinyint unsigned NOT NULL default '0',
  pic1 varchar(24) NOT NULL default '',
  pic2 varchar(24) NOT NULL default '',
  pic3 varchar(24) NOT NULL default '',
  horo tinyint unsigned NOT NULL default '0',
  regdate DATETIME NOT NULL default '0',
  editdate DATETIME NOT NULL default '0',
  ip int unsigned NOT NULL default '0',
  status tinyint unsigned NOT NULL default '0',
  req tinyint unsigned NOT NULL default '0',
  UNIQUE KEY id (id)
)";
if(mysql_query($sql)) echo "<span class=ok>".$y[64]."</span>";
else echo "<span class=false>".$y[65]."</span>";
?></td></tr>
<Tr bgcolor="<?=COLOR1?>" align=center>
<td><?=C_MYSQL_ONLINE_USERS?></td>
<td><?php
$sql = "CREATE TABLE IF NOT EXISTS ".C_MYSQL_ONLINE_USERS." (
  time time NOT NULL,   
  user smallint unsigned NOT NULL default '0'
)";
if(mysql_query($sql)) echo "<span class=ok>".$y[64]."</span>";
else echo "<span class=false>".$y[65]."</span>";
?></td></tr>
<Tr bgcolor="<?=COLOR4?>" align=center>
<td><?=C_MYSQL_ONLINE_QUESTS?></td>
<td><?php
$sql = "CREATE TABLE IF NOT EXISTS ".C_MYSQL_ONLINE_QUESTS." (
  time time NOT NULL,   
  ip int(8) unsigned NOT NULL default '0'
)";
if(mysql_query($sql)) echo "<span class=ok>".$y[64]."</span>";
else echo "<span class=false>".$y[65]."</span>";
?></td></tr>
</table></td></tr></table>
<br>
<br>
<Table CellSpacing="1" CellPadding="0" width="600" bgcolor="black"><Tr><Td>
<Table Border=0 CellSpacing="1" CellPadding="4" width="600" class=mes>
<Tr bgcolor="<?=COLOR3?>" align=center><Td colspan=2>
<?=$y[66]?>
</td></tr>
<Tr bgcolor="<?=COLOR4?>" align=center><td>
<?=$y[66]?></td><td><span class=ok><?=$y[67]?></span></td></tr>

<Tr bgcolor="<?=COLOR1?>" align=center><td>
<?=$y[68]?></td><td><a href="<?php echo C_URL.'/admin.php';?>" target="_blank"><?php echo C_URL.'/admin.php';?></a></td></tr>
<Tr bgcolor="<?=COLOR4?>" align=center><td>
<?=$y[69]?></td><td><a href="<?php echo C_URL.'/index.php';?>" target="_blank"><?php echo C_URL.'/index.php';?></a></td></tr>
<Tr bgcolor="<?=COLOR1?>" align=center>
<td>
<?=$y[70]?>
</td>
<td><span class=false><?=$y[71]?></span></td></tr></Table>
</td></tr></Table>


<?break;}?>
</td></tr></Table>
<?die;?>
