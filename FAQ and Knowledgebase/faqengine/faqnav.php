<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('./config.php');
require_once('./functions.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$default_lang;
else
	$act_lang=$$langvar;
include_once('./includes/get_settings.inc');
require_once('./includes/navbar.inc');
require_once('./includes/block_leacher.inc');
if(!language_avail($act_lang))
	faqe_die_asc("Language <b>$act_lang</b> not configured");
include_once('./language/lang_'.$act_lang.'.php');
if(!isset($navframe))
	$navframe=0;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
if($blockoldbrowser==1)
{
	if(is_ns3() || is_msie3())
	{
		$sql="select * from ".$tableprefix."_texts where textid='oldbrowser' and lang='$act_lang'";
		if(!$result = mysql_query($sql, $db))
		    die("Could not connect to the database.");
		if($myrow = mysql_fetch_array($result))
			echo undo_htmlspecialchars($myrow["text"]);
		else
			echo $l_oldbrowser;
		exit;
	}
}
?>
<html>
<head>
<?php
if(is_ns4() && $ns4style)
	echo"<link rel=stylesheet href=\"$ns4style\" type=\"text/css\">\n";
else if(is_ns6() && $ns6style)
	echo"<link rel=stylesheet href=\"$ns6style\" type=\"text/css\">\n";
else if(is_opera() && $operastyle)
	echo"<link rel=stylesheet href=\"$operastyle\" type=\"text/css\">\n";
else if(is_konqueror() && $konquerorstyle)
	echo"<link rel=stylesheet href=\"$konquerorstyle\" type=\"text/css\">\n";
else if(is_gecko() && $geckostyle)
	echo"<link rel=stylesheet href=\"$geckostyle\" type=\"text/css\">\n";
else if($stylesheet)
	echo"<link rel=stylesheet href=\"$stylesheet\" type=\"text/css\">\n";
include_once('./includes/styles.inc');
if(file_exists("metadata.php"))
	include_once("./metadata.php");
else
{
?>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $contentcharset?>">
<title><?php echo $l_heading?></title>
<?php
}
?>
<meta name="generator" content="FAQEngine v<?php echo $faqeversion?>, <?php echo $copyright_asc?>">
<meta name="fid" content="022a9b32a909bf2b875da24f0c8f1225">
</head>
<body bgcolor="<?php echo $page_bgcolor?>" link="<?php echo $LinkColor?>" vlink="<?php echo $VLinkColor?>" alink="<?php echo $ALinkColor?>" text="<?php echo $FontColor?>" <?php echo $addbodytags?>>
<div align="<?php echo $tblalign?>">
<table class="faqetable" width="100%" border="0" CELLPADDING="1" CELLSPACING="0" ALIGN="<?php echo $tblalign?>">
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $heading_bgcolor?>" ALIGN="CENTER">
<TD class="mainheading" ALIGN="CENTER" VALIGN="MIDDLE" WIDTH="100%"><a name="#top">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize3?>; color: <?php echo $HeadingFontColor?>; font-weight: bold">
<?php echo $l_heading?></span></a>
</td></tr>
<?php
$maxlevel=0;
$cnt=0;
if(!isset($limitprog) || !($limitprog))
	include_once("./includes/navbar_progs.inc");
else
	include_once("./includes/navbar_prog.inc");
for ($i=0; $i<count($navtree); $i++)
{
	$expand[$i]=0;
	$visible[$i]=0;
	$levels[$i]=0;
}
$lastlevel=$maxlevel;
for ($i=count($navtree)-1; $i>=0; $i--)
{
	if($navtree[$i][0] < $lastlevel)
	{
		for ($j=$navtree[$i][0]+1; $j <= $maxlevel; $j++)
		{
			$levels[$j]=0;
		}
	}
	if($levels[$navtree[$i][0]]==0)
	{
		$levels[$navtree[$i][0]]=1;
		$navtree[$i][4]=1;
	}
	else
		$navtree[$i][4]=0;
	$lastlevel=$navtree[$i][0];
}
if(isset($expanded) && ($expanded!=""))
	$explevels = explode("|",$expanded);
else
	$explevels=array();
if(isset($prog) && ($prog))
{
	$found=false;
	$j=0;
	while(($j<count($navtree)) && !$found)
	{
		if($navtree[$j][3]==NAV_PROG)
		{
			if(!in_array($j,$explevels) && ($navtree[$j][2]==$prog))
			{
				array_push($explevels,$j);
				$found=true;
			}
		}
		$j++;
	}
}
if(isset($catnr) && ($catnr))
{
	$found=false;
	$j=0;
	while(($j<count($navtree)) && !$found)
	{
		if($navtree[$j][3]==NAV_CAT)
		{
			list($null,$localcatnr)=explode("|",$navtree[$j][2]);
			if(!in_array($j,$explevels) && ($localcatnr==$catnr))
			{
				array_push($explevels,$j);
				$found=true;
			}
		}
		$j++;
	}
}
if(isset($subcatnr) && ($subcatnr))
{
	$found=false;
	$j=0;
	while(($j<count($navtree)) && !$found)
	{
		if($navtree[$j][3]==NAV_SUBCAT)
		{
			list($null,$null2,$localsubcatnr)=explode("|",$navtree[$j][2]);
			if(!in_array($j,$explevels) && ($localsubcatnr==$subcatnr))
			{
				array_push($explevels,$j);
				$found=true;
			}
		}
		$j++;
	}
}
if(isset($faqnr) && ($faqnr))
{
	$found=false;
	$j=0;
	$lastcat=0;
	$lastsubcat=0;
	while(($j<count($navtree)) && !$found)
	{
		if($navtree[$j][3]==NAV_CAT)
		{
			$lastcat=$j;
			$lastsubcat=0;
		}
		else if($navtree[$j][3]==NAV_SUBCAT)
			$lastsubcat=$j;
		else if($navtree[$j][3]==NAV_FAQ)
		{
			list($null,$null2,$localfaqnr)=explode("|",$navtree[$j][2]);
			if($localfaqnr==$faqnr)
			{
				if(!in_array($lastcat,$explevels))
					array_push($explevels,$lastcat);
				if(($lastsubcat>0) && !in_array($lastsubcat,$explevels))
					array_push($explevels,$lastsubcat);
				$found=true;
			}
		}
		$j++;
	}
}
if(isset($question) && ($question))
{
	$found=false;
	$j=0;
	$lastitem=0;
	while(($j<count($navtree)) && !$found)
	{
		if($navtree[$j][3]==NAV_QUESCAT)
			$lastitem=$j;
		else if($navtree[$j][3]==NAV_QUES)
		{
			list($null,$localquestion)=explode("|",$navtree[$j][2]);
			if($localquestion==$question)
			{
				if(!in_array($lastitem,$explevels))
					array_push($explevels,$lastitem);
				$found=true;
			}
		}
		$j++;
	}
}
if(isset($qlink))
{
	$found=false;
	$j=0;
	$lastprog="";
	while(($j<count($navtree)) && !$found)
	{
		if($navtree[$j][3]==NAV_PROG)
			$lastprog=$navtree[$j][2];
		if($navtree[$j][3]==NAV_QUESCAT)
		{
			if(!in_array($j,$explevels) && ($lastprog==$prog))
			{
				array_push($explevels,$j);
				$found=true;
			}
		}
		$j++;
	}
}
$i=0;
while($i<count($explevels))
{
	$expand[$explevels[$i]]=1;
	$i++;
}
for($i=0; $i<count($navtree); $i++)
{
	if($navtree[$i][0]==1)
		$visible[$i]=1;
}
for($i=0; $i<count($explevels); $i++)
{
	$n=$explevels[$i];
	if(($visible[$n]==1) && ($expand[$n]==1))
	{
		$j=$n+1;
		while(($j<count($navtree)) && ($navtree[$j][0] > $navtree[$n][0] ))
   		{
			 if($navtree[$j][0]==$navtree[$n][0]+1)
			 	$visible[$j]=1;
			$j++;
		}
	}
}
for ($i=0; $i<$maxlevel; $i++)
	$levels[$i]=1;
$maxlevel++;
echo "<tr bgcolor=\"$row_bgcolor\"><td>";
echo "<table class=\"nav_table\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" cols=".($maxlevel+3)." width=100%>\n";
echo "<tr>";
for ($i=0; $i<$maxlevel; $i++)
	echo "<td width=\"16\" height=\"1\">&nbsp;</td>";
echo "<td width=100%>&nbsp;</td></tr>\n";
$cnt=0;
while ($cnt<count($navtree))
{
if ($visible[$cnt])
{
  echo "<tr valign=\"top\">";

  /****************************************/
  /* vertical lines from higher levels    */
  /****************************************/
  $i=0;
  while ($i<$navtree[$cnt][0]-1)
  {
	if ($levels[$i]==1)
		echo "<td><a name='$cnt'></a><img src=\"".$img_line."\"></td>";
	else
		echo "<td><a name='$cnt'></a><img src=\"".$img_spc."\"></td>";
	$i++;
  }

  /****************************************/
  /* corner at end of subtree or t-split  */
  /****************************************/
  if ($navtree[$cnt][4]==1)
  {
	echo "<td><img src=\"".$img_end."\"></td>";
	$levels[$navtree[$cnt][0]-1]=0;
  }
  else
  {
	echo "<td><img src=\"".$img_split."\"></td>";
	$levels[$navtree[$cnt][0]-1]=1;
  }

	$firstparam=true;
  /********************************************/
  /* Node (with subtree) or Leaf (no subtree) */
  /********************************************/
  if(($cnt+1<count($navtree))&&($navtree[$cnt+1][0]>$navtree[$cnt][0]))
  {

	/****************************************/
	/* Create expand/collapse parameters    */
	/****************************************/
	$i=0;
	$params="&expanded=";
	$script_url=$act_script_url."?$langvar=$act_lang";
	if(isset($limitprog))
		$script_url.="&limitprog=$limitprog";
	if(isset($layout))
		$script_url.="&layout=$layout";
	if($navframe==1)
		$script_url.="&navframe=1";
	while($i<count($expand))
	{
	  if ( ($expand[$i]==1) && ($cnt!=$i) || ($expand[$i]==0 && $cnt==$i))
	  {
	  	if($firstparam)
	  		$firstparam=false;
	  	else
			$params=$params."|";
		$params=$params.$i;
	  }
	  $i++;
	}

	if($expand[$cnt]==0)
	{
		echo "<td><a href=\"".$script_url.$params."#$cnt\">";
		switch($navtree[$cnt][3])
		{
			case NAV_PROG:
				echo "<img src=\"".$img_progclosed."\"";
				break;
			case NAV_QUESCAT:
			case NAV_CAT:
				echo "<img src=\"".$img_catclosed."\"";
				break;
			case NAV_SUBCAT:
				echo "<img src=\"".$img_subcatclosed."\"";
				break;
			default:
				echo "<img src=\"".$img_expand."\"";
				break;
		}
		echo " alt=\"$l_click2open\" title=\"$l_click2open\" border=\"0\"></a></td>";
	}
	else
	{
		echo "<td><a href=\"".$script_url.$params."#$cnt\">";
		switch($navtree[$cnt][3])
		{
			case NAV_PROG:
				echo "<img src=\"".$img_progopen."\"";
				break;
			case NAV_QUESCAT:
			case NAV_CAT:
				echo "<img src=\"".$img_catopen."\"";
				break;
			case NAV_SUBCAT:
				echo "<img src=\"".$img_subcatopen."\"";
				break;
			default:
				echo "<img src=\"".$img_collapse."\"";
				break;
		}
		echo " alt=\"$l_click2close\" title=\"$l_click2close\" border=\"0\"></a></td>";
	}
  }
  else
  {
  	echo "<td>";
	/*************************/
	/* Tree Leaf             */
	/*************************/
	switch($navtree[$cnt][3])
	{
		case NAV_FAQ:
			echo "<img src=\"".$img_faq."\">";
			break;
		case NAV_QUES:
			echo "<img src=\"".$img_ques."\">";
			break;
		case NAV_QUESCAT:
		case NAV_CAT:
			echo "<img src=\"".$img_catlocked."\">";
			break;
		case NAV_SUBCAT:
			echo "<img src=\"".$img_subcatlocked."\">";
			break;
		case NAV_PROG:
			echo "<img src=\"".$img_proglocked."\">";
			break;
		default:
			echo "<img src=\"".$img_leaf."\">";
			break;
	}
	echo "</td>";
  }

	if(!bittst($faqnavoptions,BIT_1))
		$linktarget="faqcontent";
	else
		$linktarget="_parent";
	echo "<td class=\"navitem\" colspan=".($maxlevel-$navtree[$cnt][0])." nowrap>";
	$desturl=navbar_mkitemurl($navtree[$cnt]);
	echo "<a class=\"navbar\" href=\"$desturl\" target=\"$linktarget\">";
	echo $navtree[$cnt][1];
	echo "</a></td>";

  /****************************************/
  /* end row                              */
  /****************************************/

  echo "</tr>";
}
$cnt++;
}
echo "<tr><td>&nbsp;</td></tr>";
echo "</table></td></tr></table></td></tr></table></div>";
?>