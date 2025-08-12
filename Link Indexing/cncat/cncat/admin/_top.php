<HTML>
<HEAD>
<TITLE>CNCat ::: <?=$LANG["administration"];?></TITLE>
<STYLE>
<!--
body {font-family:verdana;font-size:11px;}
th {text-align:left;font-family:verdana;font-size:11px;}
td {font-family:verdana;font-size:11px;}
input,select {font-family:verdana;font-size:11px;background-color:#F0E0F0;}
textarea {background-color:#F0E0F0;}
.checkbox {background-color:white;}
ul {padding:10px;margin:0;}
li {padding:3px;list-style:none;}
h1 {font-size:14px;padding:10px;border-bottom:1px solid #F0E0F0;}
//-->
</STYLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=<?=$LANG["charset"];?>">
<?=$HEAD;?>
</HEAD>
<BODY style="margin:2px;" <?=$ADBODY;?>>
<table width=100% cellspacing=2 cellpadding=0 border=0 style="height:100%;" height=100%><tr><td>
</td><td width="150" valign="top" bgcolor=#F0E8F0>
<table cellspacing="0" cellpadding="6" border="0" width="150"><tr><th background="<?=$ADLINK;?>../cat/tablebg.gif" style="color:white;">CNCat 2.0</th></tr></table>

<UL>
<LI><a href=<?=$ADLINK;?>../><?=$LANG["main"];?></a>
</UL>

<table cellspacing="0" cellpadding="6" border="0" width="150"><tr><th background="<?=$ADLINK;?>../cat/tablebg.gif" style="color:white;"><?=$LANG["links"];?></th></tr></table>
<UL>
<LI><?
function ShowMenu($title,$type) {
	GLOBAL $db,$ADLINK;

	$count=mysql_result(mysql_query("SELECT count(*) FROM ".$db["prefix"]."main WHERE type='".$type."';"),0,0);
	if ($count==0) print $title." (0)";
	else print "<a href=".$ADLINK."index.php?type=".$type."><b>".$title." (".$count.")</B></a>";
	}
ShowMenu($LANG["new"],0);?>
<LI><? ShowMenu($LANG["submited"],1); ?>
<LI><? ShowMenu($LANG["deleted"],2); ?>
<LI><a href="<?=$ADLINK;?>cat.php"><?=$LANG["editcats"];?></a>
<LI><a href="<?=$ADLINK;?>dups.php"><?=$LANG["duplinks"];?></a>
<LI><a href="<?=$ADLINK;?>broken.php"><?=$LANG["brokenlinks"];?></a>
<LI><a href="<?=$ADLINK;?>counters.php"><?=$LANG["sync"];?></a>
</UL>

<table cellspacing="0" cellpadding="6" border="0" width="150"><tr><th background="<?=$ADLINK;?>../cat/tablebg.gif" style="color:white;"><?=$LANG["mail_title"];?></th></tr></table>

<UL>
<LI><a href="<?=$ADLINK;?>mail.php?mid=1"><?=$LANG["mail_menu_add"];?></a>
<LI><a href="<?=$ADLINK;?>mail.php?mid=2"><?=$LANG["mail_menu_submit"];?></a>
<LI><a href="<?=$ADLINK;?>mail.php?mid=3"><?=$LANG["mail_menu_delete"];?></a>
</UL>

<table cellspacing="0" cellpadding="6" border="0" width="150"><tr><th background="<?=$ADLINK;?>../cat/tablebg.gif" style="color:white;"><?=$LANG["other"];?></th></tr></table>

<UL>
<LI><a href="<?=$ADLINK;?>templates.php"><?=$LANG["tmpl"];?></a>
<LI><a href="<?=$ADLINK;?>plugins.php"><?=$LANG["plugins"];?></a>
<LI>
<LI><a href="<?=$ADLINK;?>logout.php"><?=$LANG["logout"];?></a>
</UL>

<img src="../cat/none.gif" width="150" height="1" alt=""><br>

</td><td>&nbsp;&nbsp;</td><td width="100%" valign="top">
