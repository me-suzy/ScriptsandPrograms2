<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./auth.php');
include_once('./newsfunctions.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
$page_title=$l_programm_title;
require_once('./heading.php');
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($admin_rights < 2)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
$modsql="select * from ".$tableprefix."_programm_admins where prognr=$input_prognr and usernr=$act_usernr";
if(!$modresult = faqe_db_query($modsql, $db)) {
    die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.");
}
if($modrow=faqe_db_fetch_array($modresult))
	$ismod=1;
else
	$ismod=0;
if(($admin_rights<3) && ($ismod==0))
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(($userdata["hideemail"]==0) && (strlen($userdata["email"])<1))
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	echo "$l_nonewssend<br>";
	echo "$l_reason: $l_noadminmail";
	echo "</td></tr></table></td></tr></table>";
	include('./trailer.php');
	exit;
}
$sql = "select * from ".$tableprefix."_misc";
if(!$result = faqe_db_query($sql, $db))
    die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.");
$sql = "select * from ".$tableprefix."_programm where (prognr=$input_prognr)";
if(!$result = faqe_db_query($sql, $db))
    die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.");
if($myrow=faqe_db_fetch_array($result))
{
	$newsgroup=$myrow["newsgroup"];
	$subject=$myrow["newssubject"];
	$nntpserver=$myrow["nntpserver"];
	$newsdomain=$myrow["newsdomain"];
}
else
{
	$subject="";
	$newsgroup="";
	$nntpserver="";
	$newsdomain="";
}
if(strlen($nntpserver)<1)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	echo "$l_nonewssend<br>";
	echo "$l_reason: $l_nonntpserver";
	echo "</td></tr></table></td></tr></table>";
	include('./trailer.php');
	exit;
}
if(strlen($newsdomain)<1)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	echo "$l_nonewssend<br>";
	echo "$l_reason: $l_nodomain";
	echo "</td></tr></table></td></tr></table>";
	include('./trailer.php');
	exit;
}
if(strlen($newsgroup)<1)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	echo "$l_nonewssend<br>";
	echo "$l_reason: $l_nonewsgroup";
	echo "</td></tr></table></td></tr></table>";
	include('./trailer.php');
	exit;
}
if(strlen($subject)<1)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	echo "$l_nonewssend<br>";
	echo "$l_reason: $l_nonewssubject";
	echo "</td></tr></table></td></tr></table>";
	include('./trailer.php');
	exit;
}
$body="";
$crlf="\n";
$sql = "select * from ".$tableprefix."_programm where (prognr=$input_prognr)";
if(!$result = faqe_db_query($sql, $db))
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
   	die("Could not connect to the database.");
}
if (!$myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
   	die($l_nosuchprog);
}
$body.="$l_listheading$crlf";
$body.="$l_progname: ".$myrow["programmname"]."$crlf$crlf";
$prognr=$myrow["prognr"];
$sql = "select * from ".$tableprefix."_category where (programm=$prognr) order by catnr";
if(!$result = faqe_db_query($sql, $db))
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
   	die("Could not connect to the database.");
}
if (!$myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
   	die($l_noentries);
}
$faqcount=1;
do{
$body.=$myrow["categoryname"]."$crlf";
$sql = "select * from ".$tableprefix."_data where (category=".$myrow["catnr"].") order by faqnr";
if(!$result2 = faqe_db_query($sql, $db))
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
   	die("Could not connect to the database.");
}
if (!$myrow2 = faqe_db_fetch_array($result2))
	$body.=$l_noentries;
else
{
	do{
	$body.="$faqcount. ".$myrow2["heading"]."$crlf";
	$faqcount+=1;
	}while($myrow2 = faqe_db_fetch_array($result2));
}
} while($myrow = faqe_db_fetch_array($result));
$body.="$crlf$crlf";
$sql = "select * from ".$tableprefix."_category where (programm=$prognr) order by catnr";
if(!$result = faqe_db_query($sql, $db))
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
   	die("Could not connect to the database.");
}
if (!$myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
   	die($l_noentries);
}
$faqcount=1;
do{
$body.=$myrow["categoryname"]."$crlf";
$sql = "select * from ".$tableprefix."_data where (category=".$myrow["catnr"].") order by faqnr";
if(!$result2 = faqe_db_query($sql, $db))
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
   	die("Could not connect to the database.");
}
if (!$myrow2 = faqe_db_fetch_array($result2))
	$body.=$l_noentries;
else
{
	do{
	$body.="$faqcount. ".$myrow2["heading"]."$crlf";
	$body.="$l_question:$crlf";
	$body.=stripslashes($myrow2["questiontext"])."$crlf";
	$body.="$l_answer:$crlf";
	$body.=stripslashes($myrow2["answertext"])."$crlf$crlf";
	$faqcount+=1;
	}while($myrow2 = faqe_db_fetch_array($result2));
}
} while($myrow = faqe_db_fetch_array($result));
$body.="Generated by FAQEngine v$faqeversion$crlf(c)2001-2005 Boesch IT-Consulting$crlf";
$errortext="";
if($userdata["hideemail"]==0)
	$sendermail=$userdata["email"];
else
	$sendermail=$faqemail;
if(!postnews($nntpserver, $newsgroup, $sendermail, $subject, $body, $newsdomain, &$errortext))
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	echo "$l_nonewssend<br>";
	echo "$l_reason: $errortext</td>";
}
else
{
	echo "<tr class=\"displayrow\"><td align=\"center\">";
	echo "$l_newsposted</td>";
}
echo "</tr></table></td></tr></table>";
echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("program.php?$langvar=$act_lang")."\">$l_proglist</a></div>";
include('./trailer.php');
?>