<?
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.1.1                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 24/10/02        Last Modified 24/10/02           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# File name               login.php                        #
# File purpose            Login users to members area      #
# File created by         AzDG <support@azdg.com>          #
############################################################
include_once 'include/config.inc.php';
include_once 'include/options.inc.php';
include_once 'include/security.inc.php';
include_once 'include/functions.inc.php';
include_once 'templates/'.C_TEMP.'/config.php';

if(!isset($p)) $p=''; 
if ($p == "s") {
$inquiry='';
switch (C_ID) {
  case '2':
    if (c_email($id) == 0) {
      include_once 'templates/'.C_TEMP.'/header.php';
      printm($w[11]);
    }
    $inquiry=" email='".cbmail($id)."'";
  break;
  default: // default by ID
    if (!is_numeric($id) || empty($id)) {
      include_once 'templates/'.C_TEMP.'/header.php';
      printm($w[185]);
    }
    $inquiry=" id='".$id."'";
  break;
  }
$tmp=mysql_query("SELECT id, password FROM ".C_MYSQL_MEMBERS." WHERE".$inquiry." AND status >= '7' LIMIT 1");
$count=mysql_num_rows($tmp);
if($count == '0') {
  include_once 'templates/'.C_TEMP.'/header.php';
  printm($w[186]);
  }
while($i=mysql_fetch_array($tmp)) {
  if($password == $i['password']) {
    session_destroy();session_start();
    
    $_SESSION['m']=$i['id'];
    $_SESSION['o']=md5(agent());
    $_SESSION['s']=md5(ip());
    $_SESSION['g']=$i['gender'];
    
    unset($s);unset($m);unset($g);unset($o); 

    // Detecting access
    mysql_query("UPDATE ".C_MYSQL_MEMBERS." SET editdate = NOW() WHERE id='".$i['id']."'") or die(mysql_error());

  } 
  else {
      unset($s);unset($m);unset($g);unset($o); 
      include_once 'templates/'.C_TEMP.'/header.php';
      printm($w[186]); 
}
}
Header("Location:".C_URL."/members/index.php?l=".$l."&".s()."&a=v");
} 
else { include_once 'templates/'.C_TEMP.'/header.php';
?>
<form action="login.php" method="post">
<input type="hidden" name="l" value="<?=$l?>">
<input type="hidden" name="p" value="s">
<center><span class=head><?=$w[90]?></span></center>
<br>
<Table CellSpacing="<?=C_BORDER?>" CellPadding="0" width="<?=C_SWIDTH?>" bgcolor="<?=C_TBCOLOR?>"><Tr><Td>
<Table Border=0 CellSpacing="<?=C_IBORDER?>" CellPadding="<?=C_CELLP?>" width="<?=C_SWIDTH?>" class=mes>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td>
<?=login(C_ID);?></td><td><input class=minput type=text name=id></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td><?=$w[54]?></td><td><input class=minput type=password name=password></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td colspan=2 align=right><input class=minput type=submit value="<?=$w[263]?>"></td></tr>
<Tr align="<?=C_ALIGN?>" bgcolor="<?=COLOR1?>"><Td colspan=2 align=center><a href="remind.php?l=<?=$l?>"><?=$w[173]?></a></td></tr></table></td></tr></table></form>
<?}include_once 'templates/'.C_TEMP.'/footer.php';?>