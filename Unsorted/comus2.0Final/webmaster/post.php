<?
include("../includes/config.inc.php");
include("../includes/config.fnc.php");
include("../includes/language/lang-".$deflang.".php");

/*
if($newlang)
{
	include("../includes/language/lang-".$newlang.".php");
}
else
{
	include("../includes/language/lang-english.php");
}
*/


if($storeuser == 'yes')
{
	setcookie('gtgp_nick'	,$nickname	, time()+3600*3600);
	setcookie('gtgp_email'	,$email		, time()+3600*3600);
	setcookie('gtgp_numpic'	,$numpic	, time()+3600*3600);
	setcookie('gtgp_pass'	,$pass		, time()+3600*3600);
	setcookie('gtgp_mailme'	,$mailme	, time()+3600*3600);
	setcookie('gtgp_store'	,$storeuser	, time()+3600*3600);
}
else
{
	setcookie('gtgp_nick'	,'');
	setcookie('gtgp_email'	,'');
	setcookie('gtgp_numpic'	,'');
	setcookie('gtgp_pass'	,'');
	setcookie('gtgp_mailme'	,'');
	setcookie('gtgp_store'	,'');
}
include($DOCUMENT_ROOT . "/includes/header.php");
$file_c  = $DOCUMENT_ROOT . "/templates/email_confirm.txt";
$file_cs = $DOCUMENT_ROOT . "/templates/email_confirm_subject.txt";
$session = SessionID(5);
?>

<TABLE BORDER="0" ALIGN="CENTER" VALIGN="TOP" WIDTH="750" CELLSPACING="0" CELLPADDING="0" BORDER="0">
<TR><TD>&nbsp;</TD></TR>
<TR>
<TD ALIGN="CENTER">

<TABLE ALIGN="CENTER" VALIGN="TOP" WIDTH="100%" CELLPADDING="2" BORDER="1" BORDERCOLOR="#000000" BORDERCOLORLIGHT="#000000" BORDERCOLORDARK="#000000">
<TR>
	<TD align="center">
	<A HREF="javascript:history.back(-1)"><? echo GTGP_POST_BACK; ?></A>
	</TD>
</TR>
<?

if($posting == 'No')
{
	echo "<TR><TD ALIGN=\"CENTER\"><BR>".GTGP_WM_POSTING."<BR><BR></TD></TR></TABLE></TD></TR></TABLE></body></html>";
	die();
}
/* */
$datenow = date("Ymd");
$czyscz = mysql_query("SELECT * from gtgp_settings where ipclear = '$datenow'"); 
$datacz = @mysql_num_rows($czyscz);
if($datacz)
{
}
else
{
	mysql_query("DELETE from gtgp_ip");
	mysql_query("INSERT into gtgp_settings (ipclear) VALUES ('$datenow')");
}


/* Limit post */
if ($HTTP_X_FORWARDED_FOR)
{
	$REMOTE_ADDR = $HTTP_X_FORWARDED_FOR;
}
$host = eregi_replace(",.*", "", $REMOTE_ADDR);
$result = mysql_query("SELECT * from gtgp_ip where ip = '$host'"); 
$ilepost = @mysql_num_rows($result);

if($pass)
{
$result = mysql_query("SELECT * from tblPreferred where pass = '$pass'");
$partnerpost = @mysql_num_rows($result);
	If ($partnerpost)
	{
		If ($ilepost >= $daypartnergal)
		{
			echo "<TR><TD ALIGN=\"CENTER\"><BR>".GTGP_WM_GALLIMIT."&nbsp;-&nbsp;<B>".$daypartnergal."</B>&nbsp;".GTGP_WM_GALLIMIT_P."<BR><BR></TD></TR></TABLE></TD></TR></TABLE></body></html>";
			die();
		}
	}
	else
	{
		echo "<TR><TD ALIGN=\"CENTER\"><BR>".GTGP_WM_PARTNER_BPASS."<BR><BR></TD></TR></TABLE></TD></TR></TABLE></body></html>";
		die();		
	}
}
else
{
	If ($ilepost >= $daynormalgal)
	{
		echo "<TR><TD ALIGN=\"CENTER\"><BR>".GTGP_WM_GALLIMIT."&nbsp;-&nbsp;<B>".$daynormalgal."</B>&nbsp;".GTGP_WM_GALLIMIT_N."<BR><BR></TD></TR></TABLE></TD></TR></TABLE></body></html>";
		die();
	}
}

if($changedesc == 'Yes')
{
$description = strtolower($description);
$description = ucwords($description);
}

if($useconfirm == 'Yes') { $confirm = "waiting"; } else { $confirm = "yes"; }

if($useblacklist == 'Yes')
{
	$result = mysql_query("SELECT * from tblBlacklist where email = '$email'");
	$num = @mysql_num_rows($result);
	If ($num)
	{ 
		echo "<TR><TD align=center><h3><font color=red>". GTGP_POST_BL ."</font></h3></TD></TR>";	  
		die();
	}
	$domenka=parse_url($url);
	$domena=eregi_replace("www.","",$domenka["host"]);
	$resultd = mysql_query("SELECT * from tblBlacklist where email = '$domena'");
	$numd = @mysql_num_rows($resultd);
	If ($numd)
	{ 
		echo "<TR><TD align=center><h3><font color=red>". GTGP_POST_BL ."</font></h3></TD></TR>";	  
		die();
	}
}
if($usedupe == 'Yes')
{
	$result = mysql_query("SELECT * from tblTgp where url = '$url'");
	$num = @mysql_num_rows($result);
	If ($num)
	{ 
		echo "<TR><TD align=center><h3><font color=red>". GTGP_POST_DU ."</font><br><br>". GTGP_POST_PO .".</h3></TD></TR>";
		die();
	}
}

/* Check for and report reciprical links -- always on */
$open = @fopen("$url", "r");
if(!$open)
{ 
	echo "<TR><TD align=center><h3><font color=red>". GTGP_POST_NF ."</font></h3></TD></TR>";
	die();
}
else
{
	$read = fread($open, 15000);
	fclose($open);
	$orghtml = $read;
	$read = strtolower($read);
	$recipek = "<a href=\"$recip\"";
	$recipcheck= substr_count($read, "$recipek");
	if(!$recipcheck){ $recreport = "No"; } else { $recreport = "Yes"; }
}

/* BadWord checking */
if($badwordcheck == 'Yes'){
      $ckbad = explode(",", "$badword");
      while(list($v) = each($ckbad)){  
      $ckbad[$v] = trim($ckbad[$v]);
   $badcheck= substr_count($read, "$ckbad[$v]");
      if($badcheck){
      echo "
	  <TR><TD align=center>
	  <h3>". GTGP_POST_BW .": <font color=red>". $ckbad[$v] ."</font></h3>
	  </TD></TR>
	  ";	  
      die();
      }
   }
}

/* PopUp checking */
if($popcheck == 'No'){
      $badpop = substr_count($read, "$popup");
         if($badpop){
         echo "
    	 <TR><TD align=center>
	     <h3>". GTGP_POST_POP ."</h3>
    	 </TD></TR>
	     ";	
	     die();
         }
}

/* JavaScript checking */
if($javacheck == 'No'){
      $javascr = substr_count($read, "$java");
         if($javascr){
         echo "
    	 <TR><TD align=center>
	     <h3>". GTGP_POST_JAVA ."</h3>
    	 </TD></TR>
	     ";	
	     die();
         }
}

/* Flashlinks checking */
if($flcheck == 'No'){
      $flashlink = substr_count($read, "$flcode");
         if($flashlink){
         echo "
    	 <TR><TD align=center>
	     <h3>". GTGP_POST_FL ."</h3>
    	 </TD></TR>
	     ";	
	     die();
         }
}

/* IFRAME checking */
if($iframecheck == 'No'){
      $iframe = substr_count($read, "$iframecode");
         if($iframe){
         echo "
    	 <TR><TD align=center>
	     <h3>". GTGP_POST_IFRAME ."</h3>
    	 </TD></TR>
	     ";	
	     die();
         }
}
/* OBJECT checking */
if($objectcheck == 'No'){
      $object = substr_count($read, "$objectcode");
         if($object){
         echo "
    	 <TR><TD align=center>
	     <h3>". GTGP_POST_OBJECT ."</h3>
    	 </TD></TR>
	     ";	
	     die();
         }
}
/* Recip checking */
if($reqrecip == 'Yes'){
      if(!$recipcheck){
         echo "
    	 <TR><TD align=center>
	     <h3><font color=red>". GTGP_POST_RU ."</font><BR><BR>
		 ". GTGP_POST_RUU .": ". $recip ."
		 </h3>
    	 </TD></TR>
	     ";
      die();
   }
}
/* ADVANCED GALERY CHECK */
if($advgalcheck == 'Yes')
{
?>
</TABLE>
<BR CLEAR="ALL"><BR>
</TD>
</TR>
</TABLE>
<?
	$parser = new htmlparser_class;
	$parser->LoadHTML($orghtml);
	$parser->Parse();
	$result=$parser->GetElements(&$htmlcode);
	$thw = explode(",",$thumbw);
	$minw = $thw[0];
	$maxw = $thw[1];
	$thh = explode(",",$thumbh);
	$minh = $thh[0];
	$maxh = $thh[1];

	$htmllen = strlen($read);
	$htmlsize = calc($htmllen);
	echo "<FONT SIZE=1>&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp<B>" . GTGP_POST_CHECKING .":</B>&nbsp;&nbsp;<I>". $url . "</I>";
	echo "&nbsp;&nbsp<B>".GTGP_POST_HTML_SIZE."</B> - " . $htmlsize;
	echo "&nbsp;&nbsp<FONT COLOR=green>".GTGP_POST_HTML_OK."</FONT>\n<BR><BR>\n";
	flush();

	if ($result)
	{
		while (list($key, $code) = each ($htmlcode))
		{
			$tag	= substr($code,0,7);
			if($tag == '<a href')
			{
				$raz	= $code;
				$dwa	= current($htmlcode);
				$trzy	= next($htmlcode);
				$picnum = preg_match('/href="?(\S+\.jpe?g)"?/i',$raz, $matches);
				$pic = $matches[1];
				$thbnum = preg_match('/src="?(\S+\.jpe?g)"?/i',$dwa, $matches);
				$thu = $matches[1];
				if (($picnum) AND ($thbnum))
				{
					$numthumbs++;
					$fpic = makefull($pic);
					$fthu = makefull($thu);
					echo "<FONT SIZE=1>&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp<B>" . GTGP_POST_CHECKING .":</B>&nbsp;&nbsp;<I>". $fpic . "</I>";
					flush();
					$open = @fopen($fpic, "r");

					if($open)
					{
						$readp = fread($open, 150000);
						fclose($open);
						$piclen = strlen($readp);
						$rozmiar = @GetImageSize($fpic,&$info);
						$picsize = calc($piclen);
						echo "&nbsp;&nbsp<B>".GTGP_POST_PIC_WIDTH."</B> - "  . $rozmiar[0];
						echo "&nbsp;&nbsp<B>".GTGP_POST_PIC_HEIGHT."</B> - " . $rozmiar[1];
						echo "&nbsp;&nbsp<B>".GTGP_POST_PIC_SIZE."</B> - " . $picsize;
						echo "&nbsp;&nbsp<FONT COLOR=green>".GTGP_POST_PIC_OK."</FONT><br>";
					}
					else
					{
						echo "<BR><BR><FONT COLOR=\"RED\"><B>" . GTGP_POST_PIC_ERROR_READ; "</B></FONt></TD></TR>";
						die();
					}
				
					if($thumbcheck == 'Yes')
					{
						$rozmiar = @GetImageSize($fthu,&$info);
						$typ = $rozmiar[2];
						if($typ)
						{
							echo "&nbsp;&nbsp<B>" . GTGP_POST_THUMB . "</B>&nbsp;-&nbsp";
							echo $rozmiar[0] . "x" .$rozmiar[1];
							echo "</FONT>";
							if (($rozmiar[0] < $minw ) OR ($rozmiar[1] < $minh))
							{
								echo "<BR><BR>&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp<FONT SIZE=\"1\" COLOR=\"RED\">".GTGP_POST_THUMB_M."</FONT>";
								echo "<BR><BR>&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp<FONT SIZE=\"1\" COLOR=\"RED\">".GTGP_POST_THUMB_M_B."&nbsp;&nbsp;".$minw."x".$minh."</FONT>";
								die();
							}
							if (($rozmiar[0] > $maxw ) OR ($rozmiar[1] > $maxh))
							{
								echo "<BR><BR>&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp<FONT SIZE=\"1\" COLOR=\"RED\">".GTGP_POST_THUMB_D."</FONT>";
								echo "<BR><BR>&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp<FONT SIZE=\"1\" COLOR=\"RED\">".GTGP_POST_THUMB_D_B."&nbsp;&nbsp;".$maxw."x".$maxh."</FONT>";
								die();
							}
	
						}
						else
						{
								echo "<BR><BR>&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp<FONT SIZE=\"1\" COLOR=\"RED\">".GTGP_POST_THUMB_ERROR."</FONT>";
								echo "<BR><BR>&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp<B>".$fthu."</B>";
								die();
						}
						echo "&nbsp;&nbsp<FONT size=1 COLOR=green>".GTGP_POST_THUMB_OK."</FONT>\n";
					}
					else
					{
						echo "\n";
					}
					flush();
				}
				else
				{
				$numlinks++;
				}
			}
		}
		if($numthumbs < $galminpic)
		{
			echo "<BR><BR>&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp<FONT COLOR=\"RED\">".GTGP_POST_MINPIC."<BR>&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp".GTGP_POST_MINPIC_T."&nbsp<B>".$galminpic."</B>&nbsp;".GTGP_POST_PIC_P."</FONT>";
			$fin = 1;
		}
		if($numthumbs > $galmaxpic)
		{
			echo "<BR><BR>&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp<FONT COLOR=\"RED\">".GTGP_POST_MAXPIC."<BR>&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp".GTGP_POST_MAXPIC_T."&nbsp<B>".$galmaxpic."</B>&nbsp;".GTGP_POST_PIC_P."</FONT>";
			$fin = 1;
		}
		if($numlinks > $maxlink)
		{
			echo "<BR><BR>&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp<FONT COLOR=\"RED\">".GTGP_POST_MAXLINK."<BR>&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp".GTGP_POST_MAXLINK_T."&nbsp<B>".$maxlink."</B>&nbsp;".GTGP_POST_MAXLINK_P."<BR>&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp".GTGP_POST_MAXLINK_G."&nbsp<B>".$numlinks."</B>&nbsp;"."</FONT>";
			$fin = 1;
		}
		if($numthumbs != $numpic)
		{
			echo "<BR><BR>&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp<FONT COLOR=\"RED\">".GTGP_POST_PIC_GAL_PIC."&nbsp<B>".$numthumbs."</B>&nbsp;".GTGP_POST_PIC_GAL_PIC_P."<BR>&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp".GTGP_POST_PIC_GAL_FORM."&nbsp<B>".$numpic."</B>&nbsp;".GTGP_POST_PIC_GAL_FORM_P."</FONT>";
			$fin = 1;
		}
		if($fin == 1)
		{
			echo "<BR><BR>&nbsp;&nbsp&nbsp;&nbsp&nbsp;&nbsp<B>".GTGP_POST_PIC_GAL_CHANGE."</B>";
			die();
		}
	}
	else
	{
		echo "Error";
	}
?>
<TABLE BORDER="0" ALIGN="CENTER" VALIGN="TOP" WIDTH="750" CELLSPACING="0" CELLPADDING="0" BORDER="0">
<TR><TD>&nbsp;</TD></TR>
<TR>
<TD ALIGN="CENTER">

<TABLE ALIGN="CENTER" VALIGN="TOP" WIDTH="100%" CELLPADDING="2" BORDER="1" BORDERCOLOR="#000000" BORDERCOLORLIGHT="#000000" BORDERCOLORDARK="#000000">
<?
}
/* Partner post */
if($usepreferred == 'Yes')
{
	$result = mysql_query("SELECT * from tblPreferred where pass = '$pass'"); 
	$num = @mysql_num_rows($result); 
	If ($num)
	{
		mysql_query("INSERT into tblTgp (nickname, email, url, category, description, date, newpost, accept, recip, sessionid, numpic, mailme, ppost) VALUES ('$nickname', '$email', '$url', '$category', '$description', '$dnow', 'no', 'yes', '$recreport', '$session', '$numpic', '$mailme','yes')");
		$result	= mysql_query("SELECT * from tblTgp where url = '$url'");
		$r		= mysql_fetch_array($result);
		$postid	= $r["id"];
		/* Add IP to table */
		mysql_query("INSERT into gtgp_ip (ip) VALUES ('$host')");

		echo "
		<TR><TD align=left>
		<h3>". GTGP_POST_TU ." $sitename.</h3>
		<b>". GTGP_POST_DANE .":</b>
		<br>
		<br><b>". GTGP_POST_EMAIL .":</b> $email
		<br>
		<b>". GTGP_POST_URL .":</b> $url
		<br>
		<b>". GTGP_POST_CAT .":</b> $category
		<br>
		<b>". GTGP_POST_DSC .":</b> $description
		<br>
		<b>". GTGP_POST_NPIC .":</b> $numpic  $numlinks
		<br>
		<b>". GTGP_POST_NLINK .":</b> $numlinks
		<br><br>
		". GTGP_WM_POSTID1 ."". $postid ."". GTGP_WM_POSTID2 ."
		<br><br>
		". GTGP_POST_PARTNER ."
		<br><br>
		". GTGP_POST_CONTACT .": <A HREF=\"mailto:$tgpemail\">$siteowner</A>
		<br>
		</td></tr><br>";
		die();
	}
}

/* Confirmation */
if($useconfirm == 'Yes')
{
	$recipient = "$email";
	/* Subject */
	$f_confirm_s = fopen($file_cs, "r");
	$subject   = fgets($f_confirm_s, 200);
	$subject  = ereg_replace("%sitename%",$sitename,$subject);
	$subject = chop($subject);
	fclose($f_confirm_s);

	/* Message  */
	$f_confirm = fopen($file_c, "r");
	$c_dlug = filesize($file_c);
	$message = fread($f_confirm,$c_dlug);
	fclose($f_confirm);
	$message  = ereg_replace("%sitename%",$sitename,$message);
	$message  = ereg_replace("%email%",$email,$message);
	$message  = ereg_replace("%url%",$url,$message);
	$message  = ereg_replace("%category%",$category,$message);
	$message  = ereg_replace("%description%",$description,$message);
	$message  = ereg_replace("%sitename%",$sitename,$message);
	$message  = ereg_replace("%tgpemail%",$tgpemail,$message);
	$message  = ereg_replace("%siteowner%",$siteowner,$message);
	$message  = ereg_replace("%session%",$session,$message);
	if($hmail = 'Yes')
	{
		$extra = "From: $tgpemail\r\nReply-To: $tgpemail\r\nContent-type:text/html; charset=iso-8859-2\r\n";
	}
	else
	{
		$extra = "From: $tgpemail\r\nReply-To: $tgpemail\r\n";
	}
	mail ($recipient, $subject, $message, $extra);
}

$rex = mysql_query("INSERT into tblTgp (nickname, email, url, category, description, date, newpost, recip, sessionid, numpic, mailme,ppost) VALUES ('$nickname', '$email', '$url', '$category', '$description', '$dnow', '$confirm', '$recreport', '$session' , '$numpic', '$mailme','no')");
if($rex)
{
	$result	= mysql_query("SELECT * from tblTgp where url = '$url'");
	$r		= mysql_fetch_array($result);
	$postid	= $r["id"];

	/* Add IP to table */
	mysql_query("INSERT into gtgp_ip (ip) VALUES ('$host')");

	echo "
	<TR><TD align=left>
	<h3>". GTGP_POST_TU ." $sitename.</h3>
	<b>". GTGP_POST_DANE .":</b>
	<br>
	<br><b>". GTGP_POST_EMAIL .":</b> $email
	<br>
	<b>". GTGP_POST_URL .":</b> $url
	<br>
	<b>". GTGP_POST_CAT .":</b> $category
	<br>
	<b>". GTGP_POST_DSC .":</b> $description
	<br>
	<b>". GTGP_POST_NPIC .":</b> $numpic
	<br>
	<b>". GTGP_POST_NLINK .":</b> $numlinks
	<br><br>
	". GTGP_WM_POSTID1 ."". $postid ."". GTGP_WM_POSTID2 ."
	<!-- $rex -->
	<BR>
	</td></tr>";
}
else
{
	echo "<TR><TD align=left>";
	echo mysql_error();
	echo "</td></tr>";
}
?>
<TR><TD ALIGN="CENTER">
		<BR><BR>
		<? echo GTGP_WM_POWERED . " <A HREF=\"http://www.nibbi.net/scripts/comus/\" target=\"_blank\"><B>" . GTGP_NAME . "</B></A>&nbsp;" . GTGP_VERS; ?>
</TD></TR>
</TABLE>
<BR CLEAR="ALL"><BR>
</TD>
</TR>
</TABLE>
</body>
</html>

<?

class htmlparser_class
{
      var $html="";
      var $ontagfound="";
      var $ontextfound="";
      var $elements=array();

      function InsertHTML($htmlcode)
      {
               $this->html = "";
               $this->html=$htmlcode;
               return true;
      }

      function LoadHTML($buffer)
      {
               $this->html = "";
               if ($buffer!="")
               {
                  $this->html.=trim($buffer);
               }
      }

      function GetElements(&$result)
      {
               if (count($this->elements)==0) { return false; $result=array();  }
               $result=$this->elements;
               return true;
      }

      function Parse()
      {
               $ignorechar = true;
               $intag = true;
               $tagdepth = 0;
               $line="";
               $text="";
               $tag="";
               if ($this->html=="")
               { return false;}

               $raw = split ("\r\n", $this->html);

               while (list($key, $line) = each ($raw))
               {
                     $htmlline = htmlentities($line);

                     if ($line=="") { continue; }

                     $line = trim($line);
                     for ($charsindex=0;$charsindex<=strlen($line);$charsindex++)
                     {
                         if ($ignorechar==true) { $ignorechar=false;}

                         if (($line[$charsindex]=="<") && (!$intag))
                         {
                            if ($text!="")
                            {
                               /* Found Text */
                               $this->elements[]=$text;
                               $text="";
                            }
                            $intag = true;
                         } else
                         
                         if (($line[$charsindex]==">") && ($intag))
                         {
                            $tag .=">";
                            /* Tag Found */
                            $this->elements[]=$tag;
                            $ignorechar = true;
                            $intag=false;
                            $tag="";
                         }
                         
                         if ((!$ignorechar) && (!$intag))
                         {
                             $text .= $line[$charsindex];
                         } else
                         if ((!$ignorechar) && ($intag))
                         {

                             $tag .= $line[$charsindex];
                         }

                     }
               }
               return true;
      }



}

function calc($ile)
{
    global $host;
    $jednostka = 0;

    while($ile > 1024)
    {
        $ile /= 1024;
        $jednostka++;
    }
    $ile = round($ile,2);
    switch ($jednostka)
    {
    case "0":
        $jedn = "b";
    break;
    case "1":
        $jedn = "kb";
    break;
    case "2":
        $jedn = "Mb";
    break;
    case "3":
        $jedn = "Gb";
    break;
    }
    $rezultat = "$ile $jedn";
    return $rezultat;
}

function makefull($pic)
{
	global $url;
	$domenka=parse_url($url);

	$rest = substr($pic,0,1);
	if($rest == '/')
	{
		$strona = $domenka["scheme"] ."://". $domenka["host"] . $pic;
		return $strona;
	}
	$rest = substr($pic,0,7);
	if($rest == 'http://')
	{
		return $pic;
	}
	preg_match('/^(\S+)\//',$domenka["path"], $pathh);
	$strona = $domenka["scheme"] ."://". $domenka["host"] . $pathh[0] . $pic;
	return $strona;
}

?>