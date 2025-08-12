<?

/* ************************************************************
Head section of the document
*********************************************************** */

function commonheader($pageid,$title,$keywords,$description,$forcedid)
{
if ($pageid!="-1" && $pageid!="contact")
{
$result=mysql_query("SELECT Name, Keywords, Description FROM pages WHERE PageId='$pageid'");
	$row=mysql_fetch_row($result);
	$name=$row[0];
	$thiskeywords=$row[1];
	$thisdescription=$row[2];
if ($forcedid=="no") {$title="$name :: $title";}
if ($thiskeywords!="") $keywords=$thiskeywords;
if ($thisdescription!="") $description=$thisdescription;
}

echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>$title</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
<meta name=\"keywords\" content=\"$keywords\" />
<meta name=\"description\" content=\"$description\" />
<meta name=\"robots\" content=\"index,follow\" />
<link href='style.css' rel='stylesheet' /> 
</head>
<body>
";
}

/* Formation of the document area */
function bodybegin($width,$bodyposition)
{echo "
<div align=\"$bodyposition\">
<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"width: $width\">
<tr>
<td class=\"document\" align=\"left\" valign=\"top\">
";
}

/* Logobar */
function logobar($logoname,$textlogo)
{echo "
<div id=\"head\">
<table width=\"100%\">
<tr>
<td align=\"left\" valign=\"middle\">
<br />
&nbsp; &nbsp;";
if ($logoname!="")
echo "
<a href=\"index.php\" title=\"Home page\"><img src=\"./images/$logoname\" border=\"0\" alt=\"Home page\" /></a>
";
else echo "<a href=\"index.php\" title=\"Home page\">$textlogo</a>";
echo "
<br /><br />
</td>
</tr>
</table>
</div>
</td>
</tr>
<tr>
<td class=\"indocument\" valign=\"top\" align=\"left\"> 
";
}


/* Main menu - reads out from the DB the main sections of the document */

function mainmenu($pageid,$model)
{if ($model=="hh" || $model=="hv") {$divider=""; $iswidth="";} else {$divider="</tr><tr>"; $iswidth="width=\"95%\"";}


/* Show the menu only when there are any active sections in the DB */
if ($pageid!="-1")
	{/* ******************************************************
	First step: take the RefId of the page with given pageid.
	In case it is a subsection, the superordered item in the main
	menu should be signed as active as well. 
	******************************************************** */
	if ($pageid=="contact") $refid="0";
	else {
	$result=mysql_query("SELECT RefId FROM pages WHERE PageId='$pageid'");
	$row=mysql_fetch_row($result);
	$refid=$row[0];}

	/* Read out the top-level sections from the DB */

	$result=mysql_query("SELECT PageId, Name FROM pages WHERE Active='1' AND RefId='0' order by PageId");


	/* Begin the menu */

	echo "
	<div id=\"mainmenu\" align=\"left\">
	<table $iswidth cellpadding=\"1\" cellspacing=\"1\">
	<tr>
	";

	/* Create the menu dynamically */
		
	$firstitem="yes";
	while ($row=mysql_fetch_row($result))
	{$thispageid=$row[0];
	$name=$row[1];
	if ($thispageid==$pageid || $thispageid==$refid) {$item = "<a class=\"active\" href=\"index.php?pageid=$thispageid\" title=\"$name\">$name</a>";}
	else {$item="<a href=\"index.php?pageid=$thispageid\" title=\"$name\">$name</a>";}
	if ($firstitem!="yes") echo "$divider";
	echo "	
	<td align=\"left\">
	&nbsp;&nbsp; $item &nbsp;&nbsp;
	</td>";
	$firstitem="no";
	}	

	/* And Finally right-close the menu */

if ($result=mysql_query("SELECT adminMail FROM mycmsadmin WHERE AdminId='1'"))
	{$row=mysql_fetch_row($result);
	$adminMail=$row[0];
	if ($adminMail!="")
		{
		if ($firstitem!="yes") echo "$divider";
		if ($pageid=="contact") {$item="<a class=\"active\" href=\"contact.php?pageid=contact\" title=\"Contact us\">Contact us</a>";} else $item="<a href=\"contact.php?pageid=contact\" title=\"Contact us\">Contact us</a>";

		echo "
		<td align=\"left\">
		&nbsp;&nbsp;$item &nbsp;&nbsp;
		</td>";
		}
	}

echo "
	</tr>
	</table>
	</div>
	";
	}
}

/* *************************************************************
This is a sub-menu. Pops up in case the active section has any 
subsections.
************************************************************** */


function submenu($pageid,$model)
{if ($model=="hh") {$divider=""; $iswidth="";} else {$divider="</tr><tr>"; $iswidth="width=\"95%\"";}


if ($pageid!="-1" && $pageid!="contact")
{

/* Checks whether the page a top-level one */
$result=mysql_query("SELECT RefId FROM pages WHERE PageId='$pageid'");
$row=mysql_fetch_row($result);
$refid=$row[0];

/* If it is the top-level page */
if ($refid=="0")
{$result=mysql_query("SELECT PageId, Name FROM pages WHERE RefId='$pageid' and Active='1' order by PageId");
$num_rows=mysql_num_rows($result);

/* ... and there are any subsections */
if ($num_rows!=0)
	{/* start the menu */
	echo "<div id=\"sidemenu\" align=\"left\"><table $iswidth border=\"1\"><tr>";
	/* dynamically create the cells */
	$firstitem="yes";
	while ($row=mysql_fetch_row($result))
		{$thispageid=$row[0];
		$name=$row[1];
		if ($firstitem=="no") echo "$divider";
		echo "<td> &nbsp; <a href=\"index.php?pageid=$thispageid\" title=\"$name\">$name</a> &nbsp; </td>";
		$firstitem="no";
		}
	/* ...and close the table */
	echo "</tr></table></div>";
	}

}
/* if the page is already from the second-level */
else {$result=mysql_query("SELECT PageId, Name FROM pages WHERE RefId='$refid' AND Active='1' order by PageId");
$num_rows=mysql_num_rows($result);
 	echo "<div id=\"sidemenu\" align=\"left\"><table $iswidth border=\"1\"><tr>";
	$firstitem="yes";
	while ($row=mysql_fetch_row($result))
		{$thispageid=$row[0];
		$name=$row[1];
		if ($thispageid==$pageid) {$item="<td>&nbsp; <a class=\"active\" href=\"index.php?pageid=$thispageid\" title=\"$name\">$name</a> &nbsp; </td>";}	else {$item="<td>&nbsp; <a href=\"index.php?pageid=$thispageid\" title=\"$name\">$name</a> &nbsp; </td>";}
		if ($firstitem=="no") echo "$divider";
		echo "$item";
		$firstitem="no";
		}	
		echo "</tr></table></div>";
}
}
}

/* The structure of the document */
function thedocument($pageid, $model, $start)
{


if ($model!="vv") {mainmenu($pageid,$model);}
if ($model=="hh") {submenu($pageid,$model);}

if ($pageid!="-1")
	{$result=mysql_query("SELECT Name, Content, RefId FROM pages WHERE PageId='$pageid'");
	$row=mysql_fetch_row($result);
	$name=$row[0];
	$content=$row[1];
	$refid=$row[2];
	}
else 	{$content="<h1>The service is empty! Go to your CMS and add some content!</h1>";
	}


/* extra: news */

if ($result=mysql_query("SELECT Type FROM pages WHERE PageId='$pageid'"))
	{$row=mysql_fetch_row($result);
	$type=$row[0];
	} else $type="";

if ($type=="news")
	{$onpage=5;
	if (!isset($start) || $start==""){$start=0;}

$content.="<br /><hr />";

	$result=mysql_query("SELECT * FROM news WHERE RefId='$pageid' AND active='1'");
	$num_rows=mysql_num_rows($result);

	$result=mysql_query("SELECT Author, Title, Summary, Date, NewsId FROM news WHERE RefId='$pageid' AND active='1' order by NewsId DESC limit $start,$onpage");
	while ($row=mysql_fetch_row($result))
		{$author=$row[0];
		$ntitle=$row[1];
		$summary=$row[2];
		$date=$row[3];
		$newsid=$row[4];
	
		$content.="<a href=\"news.php?refid=$pageid&amp;newsid=$newsid&amp;start=$start\" title=\"$ntitle\">$ntitle</a> <br /> $summary <br />"; 
		if ($author!="") $content.="by $author &nbsp;&nbsp;";
$content.="($date) &nbsp; <a href=\"news.php?refid=$pageid&amp;newsid=$newsid&amp;start=$start\" title=\"$ntitle\">read >></a><br /><br /> ";

		}
	if ($start>0)
		{
		$start2=$start-$onpage;
		$content.= "<a href=\"index.php?start=0&amp;pageid=$pageid\">&laquo; first</a> &nbsp; <a href=\"index.php?start=$start2&amp;pageid=$pageid\">&laquo; previous</a> ";
		}
	if ($num_rows>$onpage)
	{$minval=intval($start/$onpage)-4;
	if($minval<0) $minval=0;
	$maxval=$minval+8;

	for ($i=$minval;$i<=$maxval;$i++)
		{if ($num_rows>$i*$onpage)
			{$start3=$onpage*$i;
			 $j=$i+1;
			  if ($start3!=$start)
				{$content.= "<a href=\"index.php?start=$start3&amp;pageid=$pageid\">[$j]</a> ";} else{$content.= "<b>[$j]</b> ";}
			}
		}
	}

if ($start+$onpage<$num_rows){
$start=$start+$onpage;
$supr_range=intval($num_rows/$onpage);
$supr=$supr_range*$onpage;
if (is_int($num_rows/$onpage)) $supr-=$onpage;

$content.= "<a href=\"index.php?start=$start&amp;pageid=$pageid\">next &raquo;</a> &nbsp;<a href=\"index.php?start=$supr&amp;pageid=$pageid\">last &raquo;</a> ";
}

	}


if ($model!="hh")
{
$contwidth="80%";
$side="yes";
} else {$contwidth="100%"; 
	$side="no";}


if ($refid=="0")
{
$result_test=mysql_query("SELECT PageId, Name FROM pages WHERE RefId='$pageid' AND Active='1' order by PageId");
$num_rows_test=mysql_num_rows($result_test);
if ($num_rows_test=="0" && $model!="vv")
	$side="none";
	$contwidth="100%";
}

echo "
<table width=\"100%\">
	<tr>";
	
if ($side=="yes")
	{echo "
		<td width=\"20%\" align=\"center\" valign=\"top\">";
		if ($model=="vv") mainmenu($pageid,$model);
		echo "<br />";
		submenu ($pageid,$model);
		echo"
		</td>";
	}
	

echo " 
		<td width=\"$contwidth\" align=\"center\" valign=\"top\">
		<table width=\"90%\">
			<tr>
				<td valign=\"top\" align=\"left\">
				<br/>
				$content
				<br /><br />
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
";
}



function bodyend($thisurl)
{if ($result=mysql_query("SELECT Name, title FROM rsschannel order by RssId DESC")){$rssbar ="";
	$display="no";
	while ($row=mysql_fetch_row($result))
	{$name=$row[0];
	$rsstitle=$row[1];
	if (file_exists("./rss/$name/rss.xml"))
		{$rssbar.= "<td style=\"border: solid 1px\"> &nbsp; <a href=\"$thisurl/rss/$name/rss.xml\" title=\"$rsstitle\">$rsstitle</a> &nbsp; </td>"; $display="yes";}
	}
if ($display=="yes") {echo "
	<table cellpadding=\"0\" cellspacing=\"0\" border=\"1\"><tr><td style=\"font-weight:bold\"> &nbsp; RSS: &nbsp;</td> $rssbar </tr></table>
";}
}


echo "
</td>
</tr>
<tr>
<td class=\"document\" align=\"left\">
&nbsp; &nbsp; Powered by <a href=\"http://www.netious.com\" title=\"Netious.com - free scripts, CMS-based services, RSS editors\">netious.com</a>
</td>
</tr>
</table>	
</div>
";
}


function commonfooter()
{
echo "

</body>
</html>
";
}



?>