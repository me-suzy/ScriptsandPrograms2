<?
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	PHP FXP 3.0
	by Harald Meyer (webmaster@harrym.nu) 
	Feel free to use it, but don't delete these lines. 
	PHP FXP is Freeware but all links to the PHP FXP homepage must not be deleted! 
	If you want to use PHP FXP COMMERCIAL please contact me.
	Please send me modified versions! 
	If you use it on your page, a link back to its homepage (to PHP FXP's homepage) 
	would be highly appreciated.
	Homepage: http://fxp.harrym.nu
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

 /* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	File: upftp.php
	Description: -
	Last update: 27-09-2002
	Created by: Harald Meyer
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
 ?>
 <html>
<head>
<title>PHP FXP 3 FTP Client</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript">
<!--
function MM_callJS(jsStr) { //v2.0
  return eval(jsStr)
}
//-->
</script>
</head>

<body bgcolor="#FFFFFF" text="#000000">
<?

if ($action==NULL) {
    ?>
<p><font size="4" face="Arial, Helvetica, sans-serif"><b><font color="#000066">PHP 
  FXP FTP Client </font></b></font></p>
<ul>
  <li><a href="<? echo("$PHP_SELF?action=clientlogin"); ?>"><font size="2" face="Arial, Helvetica, sans-serif"><b>FTP 
    Client</b></font></a></li>
  <li><b><font size="2" face="Arial, Helvetica, sans-serif"><a href="<? echo("$PHP_SELF?action=startrecursiv"); ?>">Recursive 
    file fetcher</a></font></b></li>
  <li><b><font size="2" face="Arial, Helvetica, sans-serif"><a href="<? echo("$PHP_SELF?action=direct"); ?>">Direct 
    input</a></font></b></li>
</ul>
<p> 
  <?
}//NULL

if ($action=="direct") {
    ?>
</p>
<form name="form2" method="post" action="<? echo("$PHP_SELF?action=direct"); ?>">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="2"><font size="3"><b><font face="Arial, Helvetica, sans-serif">Direct 
        url adder</font></b></font></td>
    </tr>
    <tr valign="top" bgcolor="#999900"> 
      <td width="150"><font size="2" face="Arial, Helvetica, sans-serif"><b>Url:</b></font></td>
      <td> <font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="tserver" size="40">
        <input type="checkbox" name="tpasive" value="true" checked>
        pasive mode <br>
        <select name="ttransfermode">
          <option value="ftp">FTP</option>
          <option value="file">File</option>
        </select>
        transfer mode </font></td>
    </tr>
    <tr valign="top" bgcolor="#999900">
      <td width="150"><font size="2" face="Arial, Helvetica, sans-serif"><b>Buffer:</b></font></td>
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="tbuffer">
        <input type="checkbox" name="tautobuffer" value="true" checked>
        Auto</font></td>
    </tr>
    <tr valign="top" bgcolor="#999900"> 
      <td width="150">&nbsp;</td>
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <select name="tmode">
          <option value="FTP_BINARY">Binary</option>
          <option value="FTP_ASCII">Text</option>
        </select>
        mode </font></td>
    </tr>
    <tr valign="top"> 
      <td width="150">&nbsp;</td>
      <td> 
        <input type="button" name="Submit22" value="Add" onClick="MM_callJS('addserver()')">
        <script language="JavaScript">
		<!--
		 function addserver()
		 {
			var tval;
			tval='&go='+escape(document.form2.tserver.value);
			if (document.form2.tautobuffer.checked==true) {
			    tval=tval+'&buffer=AUTO';
			}
			else{
				tval=tval+'&buffer='+document.form2.tbuffer.value;
			}

			tval=tval+'&pasive='+document.form2.tpasive.value;
			tval=tval+'&transfermode='+document.form2.ttransfermode.value;
			tval=tval+'&mode='+document.form2.tmode.value;
		    window.parent.store.form1.servers.value=window.parent.store.form1.servers.value+tval+'\n';
		 } // end addserver
		//-->
		</script>
      </td>
    </tr>
  </table>
</form>
<p>
  <?
}//direct

if ($action=="clientlogin") {
?>
</p>
<form name="loginform" method="post" action="<? echo("$PHP_SELF?action=login"); ?>">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="2"><font size="3"><b><font face="Arial, Helvetica, sans-serif">Ftp 
        server log-in</font></b></font></td>
    </tr>
    <tr> 
      <td width="150"><b><font size="2" face="Arial, Helvetica, sans-serif">Server:</font></b></td>
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="tserver" size="40">
        </font></td>
    </tr>
    <tr> 
      <td width="150"><b><font size="2" face="Arial, Helvetica, sans-serif">Port:</font></b></td>
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="tport" size="40" value="21">
        </font></td>
    </tr>
    <tr> 
      <td width="150"><b><font size="2" face="Arial, Helvetica, sans-serif">Username:</font></b></td>
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="tuser" size="40" value="anonymous">
        </font></td>
    </tr>
    <tr> 
      <td width="150"><b><font size="2" face="Arial, Helvetica, sans-serif">Password:</font></b></td>
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="tpass" size="40">
        </font></td>
    </tr>
    <tr> 
      <td width="150"><b><font size="2" face="Arial, Helvetica, sans-serif">Path:</font></b></td>
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="tpath" size="40" value="/">
        </font></td>
    </tr>
    <tr>
      <td width="150"><b><font size="2" face="Arial, Helvetica, sans-serif">Pasive 
        mode:</font></b></td>
      <td>
        <input type="checkbox" name="tpasive" value="true" checked>
      </td>
    </tr>
    <tr> 
      <td width="150">&nbsp;</td>
      <td> 
        <input type="submit" name="Submit" value="Log-In">
      </td>
    </tr>
  </table>
</form>
<?
}//clientlogin

if ($action=="startrecursiv") {
    ?>
		<form name="form2" method="post" action="<? echo("$PHP_SELF?action=recursiv"); ?>">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td colspan="2"><font size="3"><b><font face="Arial, Helvetica, sans-serif">Recursiv 
        file fetcher</font></b></font></td>
    </tr>
    <tr valign="top" bgcolor="#999900"> 
      <td width="150"><font size="2" face="Arial, Helvetica, sans-serif"><b>Server 
        + start path:</b></font></td>
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="tserver" size="40">
        <input type="checkbox" name="tpasive" value="true" checked>
        pasive mode <br>
        <select name="ttransfermode">
          <option value="ftp">FTP</option>
          <option value="file">File</option>
        </select>
        transfer mode </font></td>
    </tr>
    <tr valign="top" bgcolor="#999900"> 
      <td width="150">&nbsp;</td>
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <select name="tmode">
          <option value="FTP_BINARY">Binary</option>
          <option value="FTP_ASCII">Text</option>
        </select>
        mode </font></td>
    </tr>
    <tr valign="top" bgcolor="#999900">
      <td width="150"><font size="2" face="Arial, Helvetica, sans-serif"><b>Buffer:</b></font></td>
      <td><font size="2" face="Arial, Helvetica, sans-serif">
        <input type="text" name="tbuffer">
        <input type="checkbox" name="tautobuffer" value="true" checked>
        Auto</font></td>
    </tr>
    <tr valign="top"> 
      <td width="150">&nbsp;</td>
      <td> 
        <input type="submit" name="Submit2" value="Start">
        <font size="2" face="Arial, Helvetica, sans-serif">(may take some time!)</font></td>
    </tr>
  </table>
</form>

	
<font face="Arial, Helvetica, sans-serif">
<?
}//startrecursiv

if ($action=="recursiv") {
	//parse tserver
	$tserver=$tserver."/a.zip";
    $surl=parse_url($tserver);
	$spath=pathinfo($surl["path"]);
	eregi("()(.*)(".$surl["path"].")",$tserver,$tserver1);
	$tserver1=$tserver1[2];
//	echo($tserver1);
//	exit;

	if ($surl["user"]==NULL) {
	    $surl["user"]="anonymous";
	}
	
	//connect
	$conn_id = ftp_connect($surl["host"],$surl["port"]);
	@ftp_pasv($connid,$tpasive);
	ftp_login($conn_id, $surl["user"], $surl["pass"]);
	ftp_chdir($conn_id, $spath["dirname"]);


function analysedir($dirline) 
{ 
    global $systyp,$ftp_server,$stop; 
     
    if(ereg("([-dl])[rwxst-]{9}",substr($dirline,0,10))) { 
        $systyp = "UNIX"; 
    } 
     
    if(substr($dirline,0,5) == "total") { 
        $dirinfo[0] = -1; 
    } elseif($systyp=="Windows_NT") { 
        if(ereg("[-0-9]+ *[0-9:]+[PA]?M? +<DIR> {10}(.*)",$dirline,$regs)) { 
            $dirinfo[0] = 1; 
            $dirinfo[1] = 0; 
            $dirinfo[2] = $regs[1]; 
        } elseif(ereg("[-0-9]+ *[0-9:]+[PA]?M? +([0-9]+) (.*)",$dirline,$regs)) { 
            $dirinfo[0] = 0; 
            $dirinfo[1] = $regs[1]; 
            $dirinfo[2] = $regs[2]; 
        } 
    } elseif($systyp=="UNIX") { 
        if(ereg("([-d])[rwxst-]{9}.* ([0-9]*) [a-zA-Z]+ [0-9: ]*[0-9] (.+)",$dirline,$regs)) { 
            if($regs[1]=="d")    $dirinfo[0] = 1; 
            $dirinfo[1] = $regs[2]; 
            $dirinfo[2] = $regs[3]; 
        } 
    } 
     
    if(($dirinfo[2]==".")||($dirinfo[2]=="..")) $dirinfo[0]=0; 

    // array -> 0 = switch, directory or not 
    // array -> 1 = filesize (if dir =0) 
    // array -> 2 = filename or dirname 
         
    return $dirinfo; 
} 

function rekdir($dir) 
{ 
    global $conn_id,$filetyps,$exectyps,$ftp_server,$banlist,$size,$ssize,$tserver1,$results; 
	global $ttotalstr;
    
    $dirlist = ftp_rawlist($conn_id,$spath["dirname"]); 

    for($i=0;$i<count($dirlist);$i++) { 
        $dirinfo = analysedir($dirlist[$i]); 
     
        if($dirinfo[0]==1) { 
            $newdir = "$dir/$dirinfo[2]"; 
             
            if(($dirinfo[2]=="~")||(substr($dirinfo[2],0,1)==" ")) 
                $chdir=ftp_chdir($conn_id,$newdir); 
            else    $chdir=ftp_chdir($conn_id,$dirinfo[2]); 
             
            $stop = 0; 
             
            if(!$stop && $chdir) { 
                rekdir($newdir); 
            } 
         
            if(!@ftp_chdir($conn_id,$dir))    ftp_cdup($conn_id); 
		       } elseif($dirinfo[0]==0) { 

            $results.="&go=".urlencode("$tserver1$dir/$dirinfo[2]").$ttotalstr; 
             
            $size += $dirinfo[1]; 
        } 

    } 
     
} 
	//get values
	$ttotalstr="";

	if ($tautobuffer) {
		$ttotalstr=$ttotalstr.'&buffer=AUTO';
	}
	else{
		$ttotalstr=$ttotalstr.'&buffer=$tbuffer';
	}

	$ttotalstr=$ttotalstr."&pasive=$tpasive";
	$ttotalstr=$ttotalstr."&transfermode=$ttransfermode";
	$ttotalstr=$ttotalstr."&mode=$tmode\n";


	$real_systyp = ftp_systype($conn_id); 
    $systyp = $real_systyp; 
	
	$results="";

    rekdir($spath["dirname"]); 

	//close ftp session
	ftp_quit($conn_id);
	?>
<b><font size="3">Results: </font></b></font> 
<form name="form1" method="post" action="">
  <div align="left"> 
    <p> 
      <textarea name="results" cols="77" rows="7" wrap="OFF"><? echo($results);?></textarea>
      <br>
      <input type="button" name="Submit3" value="Add" onClick="MM_callJS('addserver()')">
	   <script language="JavaScript">
		<!--
		 function addserver()
		 {
		    window.parent.store.form1.servers.value=window.parent.store.form1.servers.value+document.form1.results.value;
		 } // end addserver
		//-->
	 </script>
    </p>
    </div>
</form>
<?
}//recursiv

if ($action=="login") {
	include ("functions.inc.php");

	if (!$conn_id = ftp_connect(urldecode($tserver),$tport)) {
	    echo("Error while connecting!<br>");
		exit;
	}
	ftp_pasv($conn_id,$tpasive);
	if (!ftp_login($conn_id, $tuser, $tpass)) {
	    echo("Error while logging in!<br>");
		exit;
	}
	if (!ftp_chdir($conn_id, urldecode($tpath))) {
	    echo("Error while changing directory!<br>");
	}
	$parsedurl["scheme"]="ftp";
	$parsedurl["host"]=urldecode($tserver);
	$parsedurl["port"]=$tport;
	$parsedurl["user"]=$tuser;
	$parsedurl["pass"]=$tpass;
	$parsedurl["path"]=urldecode($tpath);

	$curdir=ftp_pwd($conn_id);
	echo("<b>Current path: $curdir </b><br>");
	
	$curfiles=ftp_rawlist($conn_id,$currdir);
	?>
<form name="form3" method="post" action="<? echo("$PHP_SELF?action=login"); ?>">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td width="100"><b><font size="2" face="Arial, Helvetica, sans-serif">Type</font></b></td>
      <td><b><font size="2" face="Arial, Helvetica, sans-serif">Filename</font></b></td>
      <td width="100"> 
        <div align="right"><b><font size="2" face="Arial, Helvetica, sans-serif">Size 
          (Byte)</font></b></div>
      </td>
      <td width="100"> 
        <div align="center"><b><font size="2" face="Arial, Helvetica, sans-serif">Type</font></b></div>
      </td>
      <td width="60"> 
        <div align="center"><b><font size="2" face="Arial, Helvetica, sans-serif">Add</font></b></div>
      </td>
    </tr>
    <?
	$java="";
	for ($i=1;$i<count($curfiles);$i++) {
		$curdir1="";
		ereg("([-d])([rwxst-]{9}).* ([0-9]*) ([a-zA-Z]+[0-9: ]* [0-9]{2}:?[0-9]{2}) (.+)", $curfiles[$i], $regs);

		$out = array("is_dir" =>($regs[1] == "d") ? true : false,"mod" => $regs[2],"size" => $regs[3],"time" =>  $regs[4],"name" => $regs[5],"raw" => $regs[0]);
		
		//create javascript
		if (!$out["is_dir"]) {
			$java.="if (document.form3.add$i.checked){";
		    $java.="tval=tval+'&go=".urlencode(glue_url($parsedurl)."/".$out["name"])."&buffer=2&transfermode=ftp&pasive=$tpasive';";
			$java.="if (document.form3.binary$i.checked){";
			$java.="tval=tval+'&binary=FTP_BINARY\\n';\n}else{\n";
			$java.="tval=tval+'&binary=FTP_ASCII\\n';\n";
			$java.="}";
			$java.="}";
		}

		//end javascript
		?>
    <tr> 
      <td width="100"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <?
			if ($out["is_dir"]) {
				echo("Directory");
				$sign="";
				if ($curdir[strlen($curdir)-1]!="/") {
				    $sign="/";
				}
				$curdir1=$curdir.$sign.$out["name"];
				$show="<a href=\"$PHP_SELF?action=login&tserver=$tserver&tport=$tport&tpath=$curdir1&tpass=$tpass&tuser=$tuser\">".$out["name"]."</a>";
				
			}
			else{
				echo("File");
				$show=$out["name"];    
			}
		?>
        </font></td>
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <? echo($show);?>
        </font></td>
      <td width="100"> 
        <div align="right"><font size="2" face="Arial, Helvetica, sans-serif"> 
          <? echo($out["size"]);?>
          </font></div>
      </td>
      <td width="100"> 
        <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
          <?
			if (!$out["is_dir"]) {
			    ?>
          <input type="checkbox" name="<? echo("binary".$i);?>" value="true" checked>
          Binary 
          <?
			}else{echo("-");}
	  ?>
          </font></div>
      </td>
      <td width="60"> 
        <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"> 
          <?
			if (!$out["is_dir"]) {
			    ?>
          <input type="checkbox" name="<? echo("add".$i);?>" value="true">
          <?
			}else{
				echo("-");
				?>
				<input type="hidden" name="<? echo("add".$i);?>" value="false">
				<?}
	  ?>
          <input type="hidden" name="<? echo("hidden".$i);?>" value="<? echo($out["name"]);?>">
          </font></div>
      </td>
    </tr>
    <?
	}//for curfiles
?>
    <tr> 
      <td colspan="5">
        <div align="right">
          <input type="button" name="Submit4" value="Add" onClick="MM_callJS('addserver()')">
		
		<script language="JavaScript">
		<!--
		 function addserver()
		 {
			var tval;
			<? echo($java);?>

			window.parent.store.form1.servers.value=window.parent.store.form1.servers.value+tval;
		 } // end addserver
		//-->
		</script>

        </div>
      </td>
    </tr>
  </table>
</form>
<?
	
	//close connection
	ftp_quit($conn_id);

}//login

?>
</body>
</html>
<?
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	END
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
?>