<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
$path_simpnews=dirname(__FILE__);
$do_db_die=false;
require_once($path_simpnews.'/config.php');
require_once($path_simpnews.'/functions.php');
if(!isset($category))
	$category=0;
if(!isset($$langvar) || !$$langvar)
	$act_lang=$default_lang;
else
	$act_lang=$$langvar;
include($path_simpnews.'/language/lang_'.$act_lang.'.php');
if(!$dbinited)
	die($l_temp_unavail);
include($path_simpnews.'/includes/get_settings.inc');
$content="<span class=\"jsscroller\">";
$announceavail=false;
$actdate = date("Y-m-d 23:59:59");
$first=true;
if(bittst($announceoptions,BIT_8))
{
		$acttime=transposetime(time(),$servertimezone,$displaytimezone);
		$sql = "select * from ".$tableprefix."_announce where (expiredate>=$acttime or expiredate=0)  and (firstdate<=$acttime or firstdate=0)";
		if($jsns_maxdays>=0)
			$sql.= "and date >= date_sub('$actdate', INTERVAL $jsns_maxdays DAY) ";
		if($separatebylang==1)
			$sql.="and lang='$act_lang' ";
		if($category>0)
			$sql.= "and (category='$category' or category=0)";
		else if($category==0)
			$sql.= "and category=0";
		$sql.= " order by date desc";
		if(isset($maxannounce))
			$sql.=" limit $maxannounce";
		else if($jsns_maxentries > 0)
			$sql.=" limit $jsns_maxentries";
		if(!$result = mysql_query($sql, $db))
		    die("Unable to connect to database.".mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$announceavail=true;
			while($myrow=mysql_fetch_array($result))
			{
				if($first)
					$first=false;
				else
					$content.="<br><br>";
				$destlink="";
				$closelink=false;
				if($jsns_nolinking==0)
				{
					if($myrow["tickerurl"])
						$linkdest=$myrow["tickerurl"];
					else
						$linkdest="http://".$simpnewssitename.$url_simpnews."/announce.php?announcenr=".$myrow["entrynr"]."&$langvar=$act_lang&layout=$layout&category=".$myrow["category"];
					$destlink="<a class=\"jsns\" href=\"$linkdest\"";
					$destlink.=" target=\"$jsns_linktarget\">";
				}
				if($jsns_displaydate)
				{
					if($destlink && !$myrow["heading"])
					{
						$content.=$destlink;
						$destlink="";
						$closelink=true;
					}
					list($mydate,$mytime)=explode(" ",$myrow["date"]);
					list($year, $month, $day) = explode("-", $mydate);
					list($hour, $min, $sec) = explode(":",$mytime);
					if($month>0)
					{
						$displaytime=mktime($hour,$min,$sec,$month,$day,$year);
						$displaytime=transposetime($displaytime,$servertimezone,$displaytimezone);
						$displaydate=date($jsns_dateformat,$displaytime);
						$content.=$displaydate;
						if($myrow["category"]==0)
							$content.="(".undo_htmlentities($l_global_announcement).")";
						else
							$content.="(".undo_htmlentities($l_announcement).")";
						$content.=":";
					}
					if($closelink)
					{
						$content.="</a>";
						$closelink=false;
					}
					$content.="<br>";
				}
				if($myrow["heading"])
				{
					if($destlink)
					{
						$content.=$destlink;
						$destlink="";
						$closelink=true;
					}
					$content.=undo_htmlentities(stripslashes($myrow["heading"]));
					if($closelink)
					{
						$content.="</a>";
						$closelink=false;
					}
					if($jsns_sepheading)
						$content.="<hr>";
					$content.="<br>";
				}
				if($destlink)
				{
					$content.=$destlink;
					$destlink="";
					$closelink=true;
				}
				$displaytext = undo_htmlspecialchars(stripslashes($myrow["text"]));
				$displaytext = str_replace("\r","",$displaytext);
				$displaytext = undo_htmlentities($displaytext);
				$displaytext = strip_tags($displaytext);
				if(($jsns_maxchars>0) && (strlen($displaytext)>$jsns_maxchars))
				{
					$text = explode(" ", $displaytext);
					$i = 0;
					$length = 0;
					$displaytext="";
					while(($i<count($text)) && ($length<$jsns_maxchars))
					{
						$length+=strlen($text[$i]);
						if($length<=$jsns_maxchars)
						{
							$displaytext.=$text[$i]." ";
							$i++;
						}
					}
					if($i<count($text))
						$displaytext.="...";
				}
				if($jsns_maxchars!=0)
					$content.=$displaytext;
				if($closelink)
				{
					$content.="</a>";
					$closelink=false;
				}
			}
		}
}
$sql = "select * from ".$tableprefix."_data ";
if($category>=0)
	$sql.="where category='$category' ";
else
{
	$sql.="where linknewsnr=0 ";
	$tmpsql="select * from ".$tableprefix."_categories where hideintotallist=1";
	if(!$tmpresult = mysql_query($tmpsql, $db))
	    die("Unable to connect to database.".mysql_error());
	while($tmprow=mysql_fetch_array($tmpresult))
		$sql.="and category!=".$tmprow["catnr"]." ";
}
if($separatebylang==1)
	$sql.="and lang='$act_lang' ";
if($category>=0)
	$sql.= "and category='$category' ";
if($jsns_maxdays>=0)
	$sql.= "and date >= date_sub('$actdate', INTERVAL $jsns_maxdays DAY) ";
if($showfuturenews==0)
	$sql.="and date<='$actdate' ";
$sql.="order by date desc";
if($jsns_maxentries > 0)
	$sql.=" limit $jsns_maxentries";
if(!$result = mysql_query($sql, $db))
	die();
if(mysql_num_rows($result)>0)
{
	while($myrow=mysql_fetch_array($result))
	{
		if($myrow["linknewsnr"]==0)
			$entrydata=$myrow;
		else
		{
			$tmpsql="select * from ".$tableprefix."_data where newsnr=".$myrow["linknewsnr"];
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
			if(!$tmprow=mysql_fetch_array($tmpresult))
				die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
			$entrydata=$tmprow;
		}
		if($first)
			$first=false;
		else
			$content.="<br><br>";
		$destlink="";
		$closelink=false;
		if($jsns_nolinking==0)
		{
			if($entrydata["tickerurl"])
				$destlink="<a class=\"jsns\" href=\"".$entrydata["tickerurl"]."\"";
			else
			{
				$srcscript=$url_simpnews."/news.php";
				if($usejslinkdest==1)
					$linkdest="$jslinkdest?newsnr=".$entrydata["newsnr"]."&$langvar=$act_lang&layout=$layout&category=".$entrydata["category"]."&srcscript=$srcscript";
				else
					$linkdest="http://".$simpnewssitename.$url_simpnews."/singlenews.php?newsnr=".$entrydata["newsnr"]."&$langvar=$act_lang&layout=$layout&category=".$entrydata["category"]."&srcscript=$srcscript";
				$destlink="<a class=\"jsns\" href=\"$linkdest\"";
			}
			$destlink.=" target=\"$jsns_linktarget\">";
		}
		if($jsns_displaydate)
		{
			if($destlink && !$entrydata["heading"])
			{
				$content.=$destlink;
				$destlink="";
				$closelink=true;
			}
			list($mydate,$mytime)=explode(" ",$myrow["date"]);
			list($year, $month, $day) = explode("-", $mydate);
			list($hour, $min, $sec) = explode(":",$mytime);
			if($month>0)
			{
				$displaytime=mktime($hour,$min,$sec,$month,$day,$year);
				$displaytime=transposetime($displaytime,$servertimezone,$displaytimezone);
				$displaydate=date($jsns_dateformat,$displaytime);
				$content.=$displaydate.":";
			}
			if($closelink)
			{
				$content.="</a>";
				$closelink=false;
			}
			$content.="<br>";
		}
		if($entrydata["heading"])
		{
			if($destlink)
			{
				$content.=$destlink;
				$destlink="";
				$closelink=true;
			}
			$content.=undo_htmlentities(stripslashes($entrydata["heading"]));
			if($closelink)
			{
				$content.="</a>";
				$closelink=false;
			}
			if($jsns_sepheading)
				$content.="<hr>";
			$content.="<br>";
		}
		if($destlink)
		{
			$content.=$destlink;
			$destlink="";
			$closelink=true;
		}
		$displaytext = undo_htmlspecialchars(stripslashes($entrydata["text"]));
		$displaytext = str_replace("\r","",$displaytext);
		$displaytext = undo_htmlentities($displaytext);
		$displaytext = strip_tags($displaytext);
		if(($jsns_maxchars>0) && (strlen($displaytext)>$jsns_maxchars))
		{
			$text = explode(" ", $displaytext);
			$i = 0;
			$length = 0;
			$displaytext="";
			while(($i<count($text)) && ($length<$jsns_maxchars))
			{
				$length+=strlen($text[$i]);
				if($length<=$jsns_maxchars)
				{
					$displaytext.=$text[$i]." ";
					$i++;
				}
			}
			if($i<count($text))
				$displaytext.="...";
		}
		if($jsns_maxchars!=0)
			$content.=$displaytext;
		if($closelink)
		{
			$content.="</a>";
			$closelink=false;
		}
	}
}
$content.="</span>";
$content = str_replace("\"","\\\"",$content);
$content = str_replace("'","\\'",$content);
if($jsns_direction==0)
	$scrolldir="false";
else
	$scrolldir="true";
?>
<style>
.jsscroller{
	font-size : <?php echo $jsns_fontsize?>pt;
	font-family : <?php echo $jsns_font?>;
	color : <?php echo $jsns_fontcolor?>;
}
</style>
<script language="javascript">
/*
Output generated by SimpNews V<?php echo $version?> (c)2002-2004 Boesch IT-Consulting
*/

//Vertical Scroller v1.2- by Brian of www.ScriptAsylum.com
//Updated for bug fixes
//Visit JavaScript Kit (http://javascriptkit.com) for script

//ENTER CONTENT TO SCROLL BELOW.
var content='<?php echo $content?>';

var boxheight=<?php echo $jsns_height?>;        // BACKGROUND BOX HEIGHT IN PIXELS.
var boxwidth=<?php echo $jsns_width?>;         // BACKGROUND BOX WIDTH IN PIXELS.
var boxcolor="<?php echo $jsns_bgcolor?>";   // BACKGROUND BOX COLOR.
var speed=<?php echo $jsns_speed?>;             // SPEED OF SCROLL IN MILLISECONDS (1 SECOND=1000 MILLISECONDS)..
var pixelstep=<?php echo $jsns_step?>;          // PIXELS "STEPS" PER REPITITION.
var godown=<?php echo $scrolldir?>;         // TOP TO BOTTOM=TRUE , BOTTOM TO TOP=FALSE

// DO NOT EDIT BEYOND THIS POINT

var outer,inner,elementheight,ref,refX,refY;
var w3c=(document.getElementById)?true:false;
var ns4=(document.layers)?true:false;
var ie4=(document.all && !w3c)?true:false;
var ie5=(document.all && w3c)?true:false;
var ns6=(w3c && navigator.appName.indexOf("Netscape")>=0)?true:false;
var txt='';
if(ns4){
txt+='<table cellpadding=0 cellspacing=0 border=0 height='+boxheight+' width='+boxwidth+'><tr><td>';
txt+='<ilayer name="ref" bgcolor="'+boxcolor+'" width='+boxwidth+' height='+boxheight+'></ilayer>';
txt+='</td></tr></table>'
txt+='<layer name="outer" bgcolor="'+boxcolor+'" visibility="hidden" width='+boxwidth+' height='+boxheight+'>';
txt+='<layer  name="inner"  width='+(boxwidth-4)+' height='+(boxheight-4)+' visibility="hidden" left="2" top="2" >'+content+'</layer>';
txt+='</layer>';
}else{
txt+='<div id="ref" style="position:relative; width:'+boxwidth+'; height:'+boxheight+'; background-color:'+boxcolor+';" ></div>';
txt+='<div id="outer" style="position:absolute; width:'+boxwidth+'; height:'+boxheight+'; visibility:hidden; background-color:'+boxcolor+'; overflow:hidden" >';
txt+='<div id="inner"  style="position:absolute; visibility:visible; left:2px; top:2px; width:'+(boxwidth-4)+'; overflow:hidden; cursor:default;">'+content+'</div>';
txt+='</div>';
}
document.write(txt);

function getElHeight(el){
if(ns4)return (el.document.height)? el.document.height : el.clip.bottom-el.clip.top;
else if(ie4||ie5)return (el.style.height)? el.style.height : el.clientHeight;
else return (el.style.height)? parseInt(el.style.height):parseInt(el.offsetHeight);
}

function getPageLeft(el){
var x;
if(ns4)return el.pageX;
if(ie4||w3c){
x = 0;
while(el.offsetParent!=null){
x+=el.offsetLeft;
el=el.offsetParent;
}
x+=el.offsetLeft;
return x;
}}

function getPageTop(el){
var y;
if(ns4)return el.pageY;
if(ie4||w3c){
y=0;
while(el.offsetParent!=null){
y+=el.offsetTop;
el=el.offsetParent;
}
y+=el.offsetTop;
return y;
}}

function scrollbox(){
if(ns4){
inner.top+=(godown)? pixelstep: -pixelstep;
if(godown){
if(inner.top>boxheight)inner.top=-elementheight;
}else{
if(inner.top<2-elementheight)inner.top=boxheight+2;
}}else{
inner.style.top=parseInt(inner.style.top)+((godown)? pixelstep: -pixelstep)+'px';
if(godown){
if(parseInt(inner.style.top)>boxheight)inner.style.top=-elementheight+'px';
}else{
if(parseInt(inner.style.top)<2-elementheight)inner.style.top=boxheight+2+'px';
}}}

window.onresize=function(){
if(ns4)setTimeout('history.go(0)', 400);
else{
outer.style.left=getPageLeft(ref)+'px';
outer.style.top=getPageTop(ref)+'px';
}}

window.onload=function(){
outer=(ns4)?document.layers['outer']:(ie4)?document.all['outer']:document.getElementById('outer');
inner=(ns4)?outer.document.layers['inner']:(ie4)?document.all['inner']:document.getElementById('inner');
ref=(ns4)?document.layers['ref']:(ie4)?document.all['ref']:document.getElementById('ref');
elementheight=getElHeight(inner);
if(ns4){
outer.moveTo(getPageLeft(ref),getPageTop(ref));
outer.clip.width=boxwidth;
outer.clip.height=boxheight;
inner.top=(godown)? -elementheight : boxheight-2;
inner.clip.width=boxwidth-4;
inner.clip.height=elementheight;
outer.visibility="show";
inner.visibility="show";
}else{
outer.style.left=getPageLeft(ref)+'px';
outer.style.top=getPageTop(ref)+'px';
inner.style.top=((godown)? -elementheight : boxheight)+'px';
inner.style.clip='rect(0px, '+(boxwidth-4)+'px, '+(elementheight)+'px, 0px)';
outer.style.visibility="visible";
}
setInterval('scrollbox()',speed);
}
</script>
