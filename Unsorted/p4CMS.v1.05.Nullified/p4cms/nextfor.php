
<table width="100" border="0" align="right" cellpadding="2" cellspacing="0"> 
	<tr> 
		<td width="50%"> <?

$ende=$start-$limitpage;
$start=$start+$limitpage;
$check=$start-$limitpage;

if($check!=0)
{
if($modnav=="logs"){ ?>
<input class="button" name="Schaltfl&auml;che" type="button" value=" < " onClick="location.href = ['logs.php?start=<? print $ende; ?>&show=<?=$_REQUEST['show'];?>&tag=<?=$_REQUEST['tag'];?>&monat=<?=$_REQUEST['monat'];?>']"> 
<? 
} else if($mondnav=="comment"){?>
<input class="button" name="Schaltfl&auml;che" type="button" value=" < " onClick="location.href = ['module.php?module=comment&page=mod.overview.php&start=<? print $ende; ?>&show=<?=$_REQUEST['show'];?>']"> 

<? } else { ?>
<input class="button" name="Schaltfl&auml;che" type="button" value=" < " onClick="location.href = ['<? echo $_SERVER['PHP_SELF'] ?>?<? if($nav!="news") {?>act=show&newsid=<?=$_REQUEST[newsid];?>&<? } ?>start=<? print $ende; ?>&show=<?=$_REQUEST['show'];?>']"> 
<?
}
}


$visible=$wieviele/$limitpage;


$mu=0;
$start_mu=0;

$erg=$wieviele/$limitpage;
$erg= ceil($erg);

if($erg < 1) {
	$erg=1;
}
?> </td> 
		<td width=10> <div align="center"> 
				<form style="display:inline" action="" method="post"> 
					<table border="0" cellpadding="0" cellspacing="1"> 
						<tr> 
							<td> <select class="jump" name=start id=start> 
									<?


//----------------
//  Dropdownmenü
//-----------------

while ($mu < $erg)
{
	
	$seite = $mu+1;
	
	if($start_mu == $chk_start)
	{
		if($visible <= 1) {}
		else 	{
			echo "<option class=\"jump\" value=$start_mu selected>Seite $seite</option>";
			
		}
	}
	else	{
		echo "<option value=$start_mu>Seite $seite</option>";
		
	}
	
	$mu++;
	$start_mu=$start_mu+$limitpage;
}


?> 
								</select> </td> 
							<td> <input class="button" onclick="this.blur()" onfocus="this.blur()" type="submit" name="Submit" value="anzeigen"> </td> 
						</tr> 
					</table> 
				</form> 
			</div></td> 
		<td width="50%"> <div align="right"> 
				<?
if($start >= $wieviele or $start == $wieviele){}else
{

if($modnav=="logs"){ ?>
<input class="button" name="Schaltfl&auml;che" type="button" value=" > " onClick="location.href = ['logs.php?start=<? print $start; ?>&show=<?=$_REQUEST['show'];?>&tag=<?=$_REQUEST['tag'];?>&monat=<?=$_REQUEST['monat'];?>']"> 
<? 
} else if($mondnav=="comment"){?>
<input class="button" name="Schaltfl&auml;che" type="button" value=" > " onClick="location.href = ['module.php?module=comment&page=mod.overview.php&start=<? print $start; ?>&show=<?=$_REQUEST['show'];?>']"> 

<? } else { ?>
<input class="button" name="Schaltfläche" type="button" value=" > " onClick="location.href = ['<? echo $_SERVER['PHP_SELF'] ?>?<? if($nav!="news") {?>act=show&newsid=<?=$_REQUEST[newsid];?>&<? } ?>start=<? print $start; ?>&show=<?=$_REQUEST['show'];?>']"> 
<?
}

}
?> 
			</div></td> 
	</tr> 
</table>
