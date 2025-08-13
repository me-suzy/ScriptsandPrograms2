<? 
ob_start();
error_reporting(7);
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
 
 if ($HTTP_SESSION_VARS[u_gid] == 1) {


mysql_connect("$sql_server","$sql_user","$sql_passwort");
mysql_select_db("$sql_db");

$monate=array("Januar","Februar","März","April","Mai","Juni","Juli","August",
"September","Oktober","November","Dezember");
?>
<link href="/p4cms/style/style.css" rel="stylesheet" type="text/css">
<!-- BEST DOCS YEAH ;D -->
<? if($_REQUEST['mode']=="docs"){ ?>

	<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">


        <tr bgcolor="#FAFAFB"> 
          <? if(!isset($_REQUEST['jahr'])){$_REQUEST['jahr']=date("Y");} ?> 
          <td bgcolor="#EAEBEE"><b>Statistik</b> <b> 
            <?=$_REQUEST['jahr'];?> 
            </b></td> 
          <td>
            <div align="right"> 
<form name="goto" method="post" action=""> 
<select name="jahr"> 
<?
for ($jahrz=2003; $jahrz<=date("Y"); $jahrz++){
if($_REQUEST['jahr']==$jahrz){
$jumpto .= "<option value=".$jahrz." selected>".$jahrz."</option>";} else {
$jumpto .= "<option value=".$jahrz.">".$jahrz."</option>"; }
}
echo $jumpto;
if(!isset($_REQUEST['anzahl'])){$_REQUEST['anzahl']="10";}
?> 
</select> 
 <select name="anzahl"> 
                  <option value="10" <? if($_REQUEST['anzahl']=="10")echo"selected"; ?>>10 pro Monat</option> 
                  <option value="20" <? if($_REQUEST['anzahl']=="20")echo"selected"; ?>>20 pro Monat</option> 
                  <option value="50" <? if($_REQUEST['anzahl']=="50")echo"selected"; ?>>50 pro Monat</option> 
                  <option value="75" <? if($_REQUEST['anzahl']=="75")echo"selected"; ?>>75 pro Monat</option> 
                  <option value="100" <? if($_REQUEST['anzahl']=="100")echo"selected"; ?>>100 pro Monat</option> 
          </select> 
                
<input name="Submit" type="submit" class="button" value="anzeigen"> 
</form> 
          </div></td> 
        </tr> 
    </table> 
      <br>
      <?
	
	for ($monatz=1; $monatz<=12; $monatz++)
		{
					$queryz = "SELECT * FROM " .$sql_prefix. "docstats WHERE monat='$monatz' and jahr='$_REQUEST[jahr]'";
					$resz = mysql_query($queryz);
					$exists = @mysql_num_rows($resz);
					
					if($exists!="0"){
	
	?> 
      <!-- EINZELNE MONATE --> 
      <table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

        <tr bgcolor="#FAFAFB"> 
          <td colspan="3">
            <table width="100%"  border="0" cellspacing="0" cellpadding="1"> 
              <tr> 
                <td>Die 10 beliebtesten Dokumente <?="<b>".$monate[$monatz-1]."</b>";?>. (<a href="#" onClick="window.open('module.php?module=stats&d4sess=<? echo($sessid); ?>&page=mod.days.php&monat=<?=$monatz;?>&jahr=<?=$_REQUEST['jahr'];?>&write=<?=$monate[$monatz-1];?>', 'tage', 'width=600,height=650,top=0,left=0,scrollbars=yes');">Tages&uuml;bersicht anzeigen</a>) </td> 
                <td><div align="right"><a href="#" onClick="window.open('module.php?module=stats&d4sess=<? echo($sessid); ?>&page=mod.days.php&monat=<?=$monatz;?>&jahr=<?=$_REQUEST['jahr'];?>&write=<?=$monate[$monatz-1];?>', 'tage', 'width=600,height=650,top=0,left=0,scrollbars=yes');"></a></div></td> 
              </tr> 
          </table></td> 
        </tr> 
        <tr> 
          <td width="10%" class="boxheader"><b>Platz</b></td> 
          <td bgcolor="#F4F5F7" class="boxheader"><b>Dokument</b></td> 
          <td width="10%" class="boxheader"><div align="center"><b>Abrufe</b></div></td> 
        </tr> 
        <?
	  $queryall = "SELECT * FROM " .$sql_prefix. "docstats WHERE monat='$monatz' and jahr='$_REQUEST[jahr]' order by hits DESC limit 0,$_REQUEST[anzahl]";
	  $resall = mysql_query($queryall);
	  $numall = mysql_num_rows($resall);
	  
	  $i=1;
	  
	  
	  while($row=mysql_fetch_array($resall)){
	 
	 
	  ?> 
        <tr bgcolor="#FAFAFB"> 
          <td><?=$i;?></td> 
          <td><a target="_blank" href="<?=$row['ref'];?>"> 
            <?=$row['ref'];?> 
          </a></td> 
          <td>
            <div align="center"> <b> 
              <?=$row[hits];?> 
          </b> </div></td> 
        </tr> 
        <? unset($proz); $i++;} ?> 
      </table> 
      <hr style="color:#EAEBED" noshade size="1"> 
      <!-- EINZELNE MONATE --> 
      <? }} ?>
<br>
<? } else { ?>
<!-- BEST DOCS YEAH ;D --> 
<?
if(isset($_REQUEST['jahr'])){ 
$adqm = " WHERE jahr='$_REQUEST[jahr]'"; 
$adq = " and jahr='$_REQUEST[jahr]'"; 
}

if($_REQUEST['jahr']=="alle"){ $adqm = "";}
if($_REQUEST['jahr']=="alle"){ $adq = ""; $adqm = "";}


if(!isset($_REQUEST['jahr'])){ 
$adqm = " WHERE jahr='".date("Y")."'";
$adq = " and jahr='".date("Y")."'"; 
$zusatz = "selected";
}

$jumpmonat .= '<form style="display:inline" action="module.php?module=stats&page=mod.show.php" method="post" enctype="multipart/form-data">';
$monate=array("Januar","Februar","März","April","Mai","Juni","Juli","August",
"September","Oktober","November","Dezember");

if(!isset($_REQUEST['jahr'])){$_REQUEST['jahr']=date("Y");}
$jumpmonat .= '<select name="jahr">';

for ($jahrz=2003; $jahrz<=date("Y"); $jahrz++){
if($_REQUEST['jahr']==$jahrz){
$jumpmonat .= "<option value=".$jahrz." selected>".$jahrz."</option>";} else {
$jumpmonat .= "<option value=".$jahrz.">".$jahrz."</option>"; }
}

$jumpmonat .= '</select>';
$jumpmonat .= '<input name=""  class=button type="submit" value="anzeigen">';
$jumpmonat .= '</form>';

		$sql = "SELECT * FROM " . $sql_prefix ."stats order by stamp DESC limit 0,1";
		$res = mysql_query($sql);
		$row = mysql_fetch_array($res);
		$anfang = date("d.m.Y",$row['stamp']);
		
		$heute = date("d.m.Y");
		$sql = "SELECT * FROM " . $sql_prefix ."stats where datum='".$heute."'";
		$res = mysql_query($sql);
		$besucherheute = @mysql_numrows($res);

		$sql = "SELECT * FROM " . $sql_prefix ."stats $adqm";
		$res = mysql_query($sql);
		$numall = @mysql_numrows($res);

		//===============================================================
		// WINDOWS XP
		//===============================================================
		$sql = "SELECT * FROM " . $sql_prefix ."stats WHERE os = 'XP' $adq";
		$res = mysql_query($sql);
		if(mysql_num_rows($res)!=0){
		$numxp = @mysql_numrows($res);
		$prozxpgif = ($numxp*100)/$numall/1.1;
		$prozxp = round(($numxp*100)/$numall,2);}else{$prozme="0";$numxp="0";}
		//===============================================================
		// WINDOWS ME
		//===============================================================
		$sql = "SELECT * FROM " . $sql_prefix ."stats WHERE os = 'Me' $adq";
		$res = mysql_query($sql);
		if(mysql_num_rows($res)!=0){
		$numme = @mysql_numrows($res);
		$prozmegif =($numme*100)/$numall/1.1;
		$prozme = round(($numme*100)/$numall,2);}else{$prozme="0"; $numme="0";}
		//===============================================================
		// WINDOWS 2000
		//===============================================================
		$sql = "SELECT * FROM " . $sql_prefix ."stats WHERE os = '2000' $adq";
		$res = mysql_query($sql);
		if(mysql_num_rows($res)!=0){
		$num2000 = @mysql_numrows($res);
		$proz2000gif =($num2000*100)/$numall/1.1;
		$proz2000 = round(($num2000*100)/$numall,2);}else{$proz2000="0";$num2000="0";}
		//===============================================================
		// WINDOWS NT
		//===============================================================
		$sql = "SELECT * FROM " . $sql_prefix ."stats WHERE os = 'NT' $adq";
		$res = mysql_query($sql);
		$numnt = @mysql_numrows($res);
		if(mysql_num_rows($res)!=0){
		$prozntgif =($numnt*100)/$numall/1.1;
		$proznt = round(($numnt*100)/$numall,2);}else{$proznt="0";}
		//===============================================================
		// WINDOWS 95/98
		//===============================================================
		$sql = "SELECT * FROM " . $sql_prefix ."stats WHERE os = 'Windows' $adq";
		$res = mysql_query($sql);
		$numwin = @mysql_numrows($res);
		if(mysql_num_rows($res)!=0){
		$prozwingif =($numwin*100)/$numall/1.1;
		$prozwin = round(($numwin*100)/$numall,2);	}else{$prozwin="0";}
		//===============================================================
		// Linux
		//===============================================================
		$sql = "SELECT * FROM " . $sql_prefix ."stats WHERE os = 'Linux' $adq";
		$res = mysql_query($sql);
		$numlinux = @mysql_numrows($res);
		if(mysql_num_rows($res)!=0){
		$prozlinuxgif =($numlinux*100)/$numall/1.1;
		$prozlinux = round(($numlinux*100)/$numall,2);			}		else{$prozlinux="0";}	
		//===============================================================
		// MAC
		//===============================================================
		$sql = "SELECT * FROM " . $sql_prefix ."stats WHERE os = 'Mac' $adq";
		$res = mysql_query($sql);
		$nummac = @mysql_numrows($res);
		if(mysql_num_rows($res)!=0){
		$prozmacgif =($nummac*100)/$numall/1.1;
		$prozmac = round(($nummac*100)/$numall,2);			}else{$prozmac="0";}
		//===============================================================
		// FREE BSD
		//===============================================================
		$sql = "SELECT * FROM " . $sql_prefix ."stats WHERE os = 'FreeBSD' $adq";
		$res = mysql_query($sql);
		$numbsd = @mysql_numrows($res);
		if(mysql_num_rows($res)!=0){
		$prozbsdgif =($numbsd*100)/$numall/1.1;
		$prozbsd = round(($numbsd*100)/$numall,2);			}else{$prozbsd="0";}
		//===============================================================
		// OPEN BSD
		//===============================================================
		$sql = "SELECT * FROM " . $sql_prefix ."stats WHERE os = 'OpenBSD' $adq";
		$res = mysql_query($sql);
		$numopenbsd = @mysql_numrows($res);
		if(mysql_num_rows($res)!=0){
		$prozopenbsdgif =($numopenbsd*100)/$numall/1.1;
		$prozopenbsd = round(($numopenbsd*100)/$numall,2);			}else{$prozopenbsd="0";}
		//===============================================================
		// BeOS
		//===============================================================
		$sql = "SELECT * FROM " . $sql_prefix ."stats WHERE os = 'BeOS' $adq";
		$res = mysql_query($sql);
		$numbeos = @mysql_numrows($res);
		if(mysql_num_rows($res)!=0){
		$prozbeosgif =($numbeos*100)/$numall/1.1;
		$prozbeos = round(($numbeos*100)/$numall,2);				}else{$prozbeos="0";}
		//===============================================================
		// IRIX
		//===============================================================
		$sql = "SELECT * FROM " . $sql_prefix ."stats WHERE os = 'IRIX' $adq";
		$res = mysql_query($sql);
		$numirix = @mysql_numrows($res);
		if(mysql_num_rows($res)!=0){
		$prozirixgif =($numirix*100)/$numall/1.1;
		$prozirix = round(($numirix*100)/$numall,2);				}else{$prozirix="0";}
		//===============================================================
		// OS2
		//===============================================================
		$sql = "SELECT * FROM " . $sql_prefix ."stats WHERE os = 'OS2' $adq";
		$res = mysql_query($sql);
		$numos2 = @mysql_numrows($res);
		if(mysql_num_rows($res)!=0){
		$prozos2gif =($numos2*100)/$numall/1.1;
		$prozos2 = round(($numos2*100)/$numall,2);			}else{$prozos2="0";}
		//===============================================================
		// SUN OS
		//===============================================================
		$sql = "SELECT * FROM " . $sql_prefix ."stats WHERE os = 'SunOS' $adq";
		$res = mysql_query($sql);
		$numsunos = @mysql_numrows($res);
		if(mysql_num_rows($res)!=0){
		$prozsunosgif =($numsunos*100)/$numall/1.1;
		$prozsunos = round(($numsunos*100)/$numall,2);			}else{$prozsunos="0";}
		//===============================================================
		// UNBEKANNT
		//===============================================================
		$sql = "SELECT * FROM " . $sql_prefix ."stats WHERE os = '?' $adq";
		$res = mysql_query($sql);
		$numunbekannt = @mysql_numrows($res);
		if(mysql_num_rows($res)!=0){
		$prozunbekanntgif =($numunbekannt*100)/$numall/1.1;
		$prozunbekannt = round(($numunbekannt*100)/$numall,2);}else{$prozunbekannt="0"; $prozunbekanntgif="0";}
		
		


		//===============================================================
		// MSI
		//===============================================================
		$sql = "SELECT * FROM " . $sql_prefix ."stats WHERE browser = 'MSIE' $adq";
		$res = mysql_query($sql);
		if(mysql_num_rows($res)!=0){
		$nummsi = @mysql_numrows($res);
		$prozmsigif =($nummsi*100)/$numall/1.1;
		$prozmsi = round(($nummsi*100)/$numall,2);}else{$prozmsi="0"; $nummsi="0";}
		
		//===============================================================
		// NETSCAPE
		//===============================================================
		$sql = "SELECT * FROM " . $sql_prefix ."stats WHERE browser = 'Netscape' $adq";
		$res = mysql_query($sql);
		if(mysql_num_rows($res)!=0){
		$numnn = @mysql_numrows($res);
		$proznngif =($numnn*100)/$numall/1.1;
		$proznn = round(($numnn*100)/$numall,2);}else{$proznn="0"; $numnn="0";}
	
		//===============================================================
		// OPERA
		//===============================================================
		$sql = "SELECT * FROM " . $sql_prefix ."stats WHERE browser = 'Opera' $adq";
		$res = mysql_query($sql);
		if(mysql_num_rows($res)!=0){
		$numopera = @mysql_numrows($res);
		$prozoperagif =($numopera*100)/$numall/1.1;
		$prozopera = round(($numopera*100)/$numall,2);}else{$prozopera="0"; $numopera="0";}

		//===============================================================
		// OPERA
		//===============================================================
		$sql = "SELECT * FROM " . $sql_prefix ."stats WHERE browser = 'Mozilla' $adq";
		$res = mysql_query($sql);
		if(mysql_num_rows($res)!=0){
		$nummozilla = @mysql_numrows($res);
		$prozmozillagif =($nummozilla*100)/$numall/1.1;
		$prozmozilla = round(($nummozilla*100)/$numall,2);}else{$prozmozilla="0";$nummozilla="0";}

		//===============================================================
		// KONQUEROR
		//===============================================================
		$sql = "SELECT * FROM " . $sql_prefix ."stats WHERE browser = 'Konqueror' $adq";
		$res = mysql_query($sql);
		if(mysql_num_rows($res)!=0){
		$numkon = @mysql_numrows($res);
		$prozkon =($numkon*100)/$numall/1.1;
		$prozkon = round(($numkon*100)/$numall,2);}else{$prozkon="0"; $numkon="0";}
		
		//===============================================================
		// LYNX
		//===============================================================
		$sql = "SELECT * FROM " . $sql_prefix ."stats WHERE browser = 'Lynx' $adq";
		$res = mysql_query($sql);
		if(mysql_num_rows($res)!=0){
		$numlynx = @mysql_numrows($res);
		$prozlynx =($numlynx*100)/$numall/1.1;
		$prozlynx = round(($numlynx*100)/$numall,2);}else{$prozlynx="0"; $numlynx="0";}
		
		//===============================================================
		// BROWSER UNBEKANNT
		//===============================================================
		$sql = "SELECT * FROM " . $sql_prefix ."stats WHERE browser = '?' $adq";
		$res = mysql_query($sql);
		if(mysql_num_rows($res)!=0){
		$numunbe = @mysql_numrows($res);
		$prozunbe =($numunbe*100)/$numall/1.1;
		$prozunbe = round(($numunbe*100)/$numall,2);}		else{$prozunbe="0"; $numunbe="0";}
		
		//===============================================================
		// STATISTIK FÜR JANUAR BIS DEZEMBER
		//===============================================================	
		$modules="modules";
		$theme="modules/stats";
		
		$tpl =& new Template("$abs_pfad$modules/stats/statsmonat_s.htm");		
		$tpl->Insert("{textmonatsuebersicht}", "Monatsübersicht");
		$statsmonat .= $tpl->VOut();
		
		for ($monatz=1; $monatz<=12; $monatz++)
					{
					$sql2 = "SELECT * FROM " . $sql_prefix ."stats WHERE monat='".$monatz."' $adq";
					$res2 = mysql_query($sql2);
					$num2 = @mysql_numrows($res2);
					$monat2 = $monate[$monatz-1];
					
					if($num2!=0){
							$hitsinp = ($num2*100)/$numall/1.1;
							} else {$hitsinp = 0;}
					
							$tpl =& new Template("$abs_pfad$modules/stats/statsmonat_m.htm");		
							$tpl->Insert("{theme}", $theme);
							$tpl->Insert("{hoehe}", $barheight);
							$tpl->Insert("{monat}", $monat2);
							$tpl->Insert("{hits}", $num2);
							$tpl->Insert("{hitsinp}", $hitsinp);
							$statsmonat .= $tpl->VOut();
					}			
		$tpl =& new Template("$abs_pfad$modules/stats/statsmonat_f.htm");		
		$tpl->Insert("{uname}", $blubb);
		$statsmonat .= $tpl->VOut();
		
		//===============================================================
		// STATISTIK TOP REFERER / 10 = default
		//===============================================================	
		$tpl =& new Template("$abs_pfad$modules/stats/statsrefer_s.htm");		
		$tpl->Insert("{textmonatsuebersicht}", "lll");
		$tpl->Insert("{texttopreferer}","Top-Referer");
		$statsrefer .= $tpl->VOut();
		
		$sql_ref = "SELECT * FROM " . $sql_prefix ."referer  WHERE name!='' $adq order by visits DESC limit 0,10";
		$res_ref = mysql_query($sql_ref);
		$num_ref = mysql_numrows($res_ref);
		
		$query="select DISTINCT name,visits  from " . $sql_prefix ."referer";
		$result=mysql_query($query);
		while ( $row = mysql_fetch_array($result)){
		$num_all = $num_all + ($row['visits']);}
		
		$x=1;
		while($x-1 < $num_ref){
							$row=mysql_fetch_array($res_ref);
							
						
							
							$refname = $row['name'];
							$num = $row['visits'];
								if($num!=0){
								$hits = $num;
								$hitsgifref = ($num*100)/$num_all/1.1;
								} else {$hits = 0;}
					
							$tpl =& new Template("$abs_pfad$modules/stats/statsrefer_m.htm");		
							$tpl->Insert("{theme}", $theme);
							$tpl->Insert("{hoehe}", $barheight);
							$tpl->Insert("{refname}", $refname);
							$tpl->Insert("{hits}", $hits);
							$tpl->Insert("{hitsgif}", $hitsgifref);
							$tpl->Insert("{id}", $x);
							$statsrefer .= $tpl->VOut();
							$x++;
				}
				
		$tpl =& new Template("$abs_pfad$modules/stats/statsrefer_f.htm");		
		$tpl->Insert("{uname}", $blubb);
		$statsrefer .= $tpl->VOut();
		
		
					
$tpl =& new Template("$abs_pfad$modules/stats/statistik.htm");
$tpl->Insert("{hoehe}", "20");
$tpl->Insert("{theme}", $theme);	
$tpl->Insert("{uname}", $numall);

$tpl->Insert("{xp_besuche}", $numxp);
$tpl->Insert("{xp_prozentgif}", $prozxpgif);
$tpl->Insert("{xp_prozent}", $prozxp);

$tpl->Insert("{me_besuche}", $numme);
$tpl->Insert("{me_prozentgif}", $prozmegif);
$tpl->Insert("{me_prozent}", $prozme);

$tpl->Insert("{2000_besuche}", $num2000);
$tpl->Insert("{2000_prozentgif}", $proz2000gif);
$tpl->Insert("{2000_prozent}", $proz2000);

$tpl->Insert("{nt_besuche}", $numnt);
$tpl->Insert("{nt_prozentgif}", $prozntgif);
$tpl->Insert("{nt_prozent}", $proznt);

$tpl->Insert("{win_besuche}", $numwin);
$tpl->Insert("{win_prozentgif}", $prozwingif);
$tpl->Insert("{win_prozent}", $prozwin);

$tpl->Insert("{linux_besuche}", $numlinux);
$tpl->Insert("{linux_prozentgif}", $prozlinuxgif);
$tpl->Insert("{linux_prozent}", $prozlinux);

$tpl->Insert("{mac_besuche}", $nummac);
$tpl->Insert("{mac_prozentgif}", $prozmacgif);
$tpl->Insert("{mac_prozent}", $prozmac);

$tpl->Insert("{bsd_besuche}", $numbsd);
$tpl->Insert("{bsd_prozentgif}", $prozbsdgif);
$tpl->Insert("{bsd_prozent}", $prozbsd);

$tpl->Insert("{openbsd_besuche}", $numopenbsd);
$tpl->Insert("{openbsd_prozentgif}", $prozopenbsdgif);
$tpl->Insert("{openbsd_prozent}", $prozopenbsd);

$tpl->Insert("{beos_besuche}", $numbeos);
$tpl->Insert("{beos_prozentgif}", $prozbeosgif);
$tpl->Insert("{beos_prozent}", $prozbeos);

$tpl->Insert("{irix_besuche}", $numirix);
$tpl->Insert("{irix_prozentgif}", $prozirixgif);
$tpl->Insert("{irix_prozent}", $prozirix);

$tpl->Insert("{os2_besuche}", $numos2);
$tpl->Insert("{os2_prozentgif}", $prozos2gif);
$tpl->Insert("{os2_prozent}", $prozos2);

$tpl->Insert("{sunos_besuche}", $numsunos);
$tpl->Insert("{sunos_prozentgif}", $prozsunosgif);
$tpl->Insert("{sunos_prozent}", $prozsunos);

$tpl->Insert("{unbekannt_besuche}", $numunbekannt);
$tpl->Insert("{unbekannt_prozentgif}", $prozunbekanntgif);
$tpl->Insert("{unbekannt_prozent}", $prozunbekannt);


// BROWSER

$tpl->Insert("{msi_besuche}", $nummsi);
$tpl->Insert("{msi_prozentgif}", $prozmsigif);
$tpl->Insert("{msi_prozent}", $prozmsi);

$tpl->Insert("{nn_besuche}", $numnn);
$tpl->Insert("{nn_prozentgif}", $proznngif);
$tpl->Insert("{nn_prozent}", $proznn);

$tpl->Insert("{opera_besuche}", $numopera);
$tpl->Insert("{opera_prozentgif}", $prozoperagif);
$tpl->Insert("{opera_prozent}", $prozopera);

$tpl->Insert("{mozilla_besuche}", $nummozilla);
$tpl->Insert("{mozilla_prozentgif}", $prozmozillagif);
$tpl->Insert("{mozilla_prozent}", $prozmozilla);

$tpl->Insert("{kon_besuche}", $numkon);
$tpl->Insert("{kon_prozentgif}", $prozkongif);
$tpl->Insert("{kon_prozent}", $prozkon);

$tpl->Insert("{lynx_besuche}", $numlynx);
$tpl->Insert("{lynx_prozentgif}", $prozlynxgif);
$tpl->Insert("{lynx_prozent}", $prozlynx);

$tpl->Insert("{bunbe_besuche}", $numunbe);
$tpl->Insert("{bunbe_prozentgif}", $prounbegif);
$tpl->Insert("{bunbe_prozent}", $prozunbe);

$tpl->Insert("{unbekannt_besuche}", $numunbekannt);
$tpl->Insert("{unbekannt_prozentgif}", $prozunbekanntgif);
$tpl->Insert("{unbekannt_prozent}", $prozunbekannt);

$tpl->Insert("{textunbekannt}" , "unbekannt");
$tpl->Insert("{textos}" , "Betriebssystem");
$tpl->Insert("{textbrowser}" , "Browser");
$tpl->Insert("{textgesamt}" , "gesamt");
$tpl->Insert("{auswahl}" , $jumpmonat);
$tpl->Insert("{besuche}" , $numall);
$tpl->Insert("{textbesucheseit}" , "Besuche seit");
$tpl->Insert("{textbesucheheute}" , "Besucher heute");
$tpl->Insert("{besucherheute}" , $besucherheute);
$tpl->Insert("{anfang}" , $anfang);
$tpl->Insert("{monatstats}" , $statsmonat);
$tpl->Insert("{statsrefer}" , $statsrefer);
$all = $tpl->VOut();


$tpl =& new Template("$abs_pfad$modules/stats/standart.htm");		
$tpl->Insert("{titel}", "");
$tpl->Insert("{inhalt}", $all);
$tpl->Insert("{theme}", $theme);
$tpl->POut();
}

 } else {
	$msg = "<center>Diese Seite darf nur von Administratoren aufgerufen werden.</center>";
	MsgBox($msg);
 }
?> 
