<?

include($DOCUMENT_ROOT . "/includes/config.inc.php");

$file_ht = $DOCUMENT_ROOT . "/templates/html_galtmpl.txt";

if ($cjultra == Yes)
{
   $cjstring = $cjstring;
   $cjstring2 = $cjstring2;	
}else{
   $cjstring = "";
   $cjstring2 = "";
}

/* Orginal Qurey: */

if ($onlycat)
{
	$query = "select * from tblTgp WHERE accept='yes' AND category='$onlycat' ORDER BY id DESC LIMIT $galinmain";
}
else
{
	$query = "select * from tblTgp WHERE accept='yes' ORDER BY id DESC LIMIT $galinmain";
}

$result = mysql_query($query) or die ("Query failed");

if ($result)
{  
	if($gtemplate)
	{
		$h_htmlt = $gtemplate;
	}
	else
	{
		$f_htmlt = fopen($file_ht, "r");
		$f_dlugh = filesize($file_ht);
		$h_htmlt = fread($f_htmlt,$f_dlugh);
		fclose($f_htmlt);
		/*	$r = mysql_fetch_array($result); */
	}
	$numerek = 0;
	$numgal  = 1;
	while ($r = mysql_fetch_array($result))
	{
		if($fromgal)
		{
			$numtmp = 1;
			while($numtmp < $fromgal)
			{
				$r = mysql_fetch_array($result);
				$numtmp++;
			}
			$fromgal = '';
		}
		$galtmpl = $h_htmlt;
		$id = $r["id"];
		$cat = $r["category"];
		$url = $r["url"];
		$desc1 = $r["description"];
		$desc = substr($desc1, 0, 23);
		$date = $r["date"];
        $date = substr("$date", 5);    
		$nick = $r["nickname"];
		$npic = $r["numpic"];
		$ppost = $r["ppost"];
		$nurl = $cjstring . $url . $cjstring2;
		$numgale = sprintf("%d",$numgal);
		$galtmpl  = ereg_replace("%numgal%",$numgale,$galtmpl);		
		$galtmpl  = ereg_replace("%cat%",$cat,$galtmpl);
		$galtmpl  = ereg_replace("%url%",$nurl,$galtmpl);
		$galtmpl  = ereg_replace("%desc%",$desc,$galtmpl);
		$galtmpl  = ereg_replace("%date%",$date,$galtmpl);
		$galtmpl  = ereg_replace("%id%",$id,$galtmpl);
		$galtmpl  = ereg_replace("%nick%",$nick,$galtmpl);		
		$galtmpl  = ereg_replace("%numpic%",$npic,$galtmpl);
        $galtmpl  = ereg_replace("%overurl%",$url,$galtmpl);
		if($ppost == 'yes')
		{
			$galtmpl  = ereg_replace("%pp%",'<B>' ,$galtmpl);
			$galtmpl  = ereg_replace("%pk%",'</B>',$galtmpl);
		}
		else
		{
			$galtmpl  = ereg_replace("%pp%",'' ,$galtmpl);
			$galtmpl  = ereg_replace("%pk%",'',$galtmpl);
		}

		echo $galtmpl;
		if ($numerek == $galinmain) { break; }
		if ($numgal == $maxtolist)  { break; }
		$numerek++;
		$numgal++;
	} 
}
else
{
}
$fromgal	= '';
$maxtolist	= '';
$gtemplate	= '';
$onlycat	= '';
?>


