<?
session_start();

// This forum was developed by Adam M. B. from aWeb Labs
// Visit us at http://www.labs.aweb.com.au
// for forum problems, bugs, or ideas email yougotmail@gmail.com
// thanks for trying out or using this forum
// aWebBB version 1.2 released under the GNU GPL
include "config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query5 = "SELECT sig, avatar FROM users WHERE username = '$_SESSION[Username]'"; 
$result5 = mysql_query($query5); 
while($r5=mysql_fetch_array($result5)) 
{ 
$sig5=$r5["sig"]; 
$avatar5=$r5["avatar"]; 
$randomnum = rand(100, 999);
?>
<b>Logged in as <?=$_SESSION[Username];?></b>
<form name="reply<?=$randomnum;?>" id="reply<?=$randomnum;?>" method="post" action="reply.php?c=<?=$_GET['c'];?>&tid=<?=$_GET['tid'];?>&t=<?=$_GET['t'];?>&a=post"> 
<script type="text/javascript">
	function insertext<?=$randomnum;?>(text){
	document.reply<?=$randomnum;?>.fpost<?=$randomnum;?>.value+=" "+ text;
        document.reply<?=$randomnum;?>.fpost<?=$randomnum;?>.focus();
	}
	</script>
<table cellpadding="0" cellspacing="0" border="0"><tr><td rowspan="2"><input type="hidden" name="sig" value="<?=$sig5;?>"> 
<input type="hidden" name="avatar" value="<?=$avatar5;?>"> 
<input type="text" name="tname" value="Re:<?=$tname;?>" size="56"> <input type="hidden" name="tid" value="<?=$_GET['tid'];?>"> 
<input type="hidden" name="categ" value="<?=$_GET['c'];?>"> 
<input type="hidden" name="randomnum" value="<?=$randomnum;?>"> 
      <textarea name="fpost<?=$randomnum;?>" cols="55" rows="4"></textarea></td><td width="21%"><a href="javascript:insertext<?=$randomnum;?>(':)')"><img alt="smile" src="smilies/smile.gif" border="0"></a>&nbsp;<a href="javascript:insertext<?=$randomnum;?>(';)')"><img alt="wink" src="smilies/wink.gif" border="0"></a><br><a href="javascript:insertext<?=$randomnum;?>(':-p')"><img alt="tongue" src="smilies/tongue.gif" border="0"></a>&nbsp;<a href="javascript:insertext<?=$randomnum;?>('>:o')"><img alt="angry" src="smilies/angry.gif" border="0"></a><br><a href="javascript:insertext<?=$randomnum;?>(':(')"><img alt="sad" src="smilies/sad.gif" border="0"></a>&nbsp;<a href="javascript:insertext<?=$randomnum;?>(':-D')"><img alt="laughing" src="smilies/laughing.gif" border="0"></a></td></tr><tr><td>
<input type="submit" name="Submit" value="Reply"></td></tr></table>
</form>
<? 
} 
mysql_close($db); 
?>
