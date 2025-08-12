<?


$step=$HTTP_GET_VARS["step"];


if($step == "showrefs")   {

$process=$HTTP_GET_VARS["process"];

$date=$HTTP_GET_VARS["date"];

print "<u><font face=verdana size=2><b>Referers for $date</b></font></u><br><br>";

if($process == "getrawreferers")  {
$filenamereferers="../logs/".$date."_referers_raw";
}

if($process == "getuniquereferers")  {
$filenamereferers="../logs/".$date."_referers_unique";
}

if($process == "getuniquereferers_thismonth")  {
$filenamereferers="../logs/".$date."_allreferers_unique";
}

if($process == "getrawreferers_thismonth")  {
$filenamereferers="../logs/".$date."_allreferers_raw";
}





$filecontents=ParseFile("$filenamereferers");


$array = split("\n", $filecontents);
$count = count($array);

for ($i=0; $i<=$count; $i++)
   {
       echo "<a href=\"$array[$i]\" target=\"_blank\"><font face=verdana size=2>$array[$i]</font></a><br>"; // add appropiate HTML tags here
   } 


exit;

}






$month=$HTTP_POST_VARS['monthdrop'];
$year=$HTTP_POST_VARS['yeardrop'];

if(empty($month) or empty($year))  {
$month=date("m");
$year=date("Y");
}


if($month == "04" or $month == "06" or $month == "09" or $month == "11") {
$datemax="30";
}

if($month == "01" or $month == "03" or $month == "05" or $month == "07" or $month == "08" or $month == "10" or $month == "12") {
$datemax="31";
}

if($month == "02") {

if($year == "2004" or $year == "2008" or $year == "2012")  {
$datemax=29;
}
else  {
$datemax=28;
 }
}

$totaluniquehits=0;
$totalrawhits=0;

if(file_exists("../logs/alltotal_unique"))  {
$totaluniquesall=ParseFile("../logs/alltotal_unique");
}
else  {
$totaluniquesall=0;
}


if(file_exists("../logs/alltotal_raw"))  {
$totalrawsall=ParseFile("../logs/alltotal_raw");
}
else  {
$totalrawsall=0;
}

?>


<html>

<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Stats</title>
</head>

<body bgcolor="#F2F2F2">

<div align="center">
  <center> <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="300" id="AutoNumber1">
    <tr>
      <td width="164"><b><font size="1" face="Verdana">Total Uniques for all 
      months:</font></b></td>
      <td width="136"><font size="1" face="Verdana"><center><? echo $totaluniquesall;  ?></center></font></td>
    </tr>
    <tr>
      <td width="164"><b><font size="1" face="Verdana">Total Raws for all 
      months: </font></b></td>
      <td width="136"><font size="1" face="Verdana"><center><? echo $totalrawsall;  ?></center></font></td>
    </tr>
  </table>
  </center>
</div>






<form method="POST" action="index.php">
  <p align="center">
  <b><font size="2" face="Verdana"><br>
  Date: </font></b><font face="Verdana"><b><select size="1" name="yeardrop">

<? 

$todaysyear=date("Y");


for($i=2003;$i<=$todaysyear;$i++)   {

if($i==$year)   {
echo "<option selected>$i</option>";
}
else  {
echo "<option>$i</option>";
}

}


  ?>

  </select>-


<select size="1" name="monthdrop">


<? 

$todaysyear=date("Y");


for($i=1;$i<=12;$i++)   {


if($i<=9)  {
$k='0'.$i;
}
else  {
$k=$i;
}



if($k==$month)   {
echo "<option selected>$k</option>";
}
else  {
echo "<option>$k</option>";
}

}
?>



</select></b></font><b><font size="2" face="Verdana">&nbsp;
  </font></b><font face="Verdana"><b><input type="submit" value="Go" name="B1"></b></font></p>
  </p>
</form>
<div align="center">
  <center>
  <table border="0" cellspacing="1" style="border-collapse: collapse" bordercolor="#111111" width="300" id="AutoNumber1" height="98">
    <tr>
      <td width="132" bgcolor="#E2E2E2" align="center" height="21"><b>
      <font size="1" face="Verdana">Date</font></b></td>
      <td width="132" bgcolor="#E2E2E2" align="center" height="21"><b>
      <font size="1" face="Verdana">Raw Hits</font></b></td>
      <td width="136" bgcolor="#E2E2E2" align="center" height="21"><b>
      <font size="1" face="Verdana">Unique Hits</font></b></td>
    </tr>


<?

for($j=1;$j<=$datemax;$j++)  {

if($j<=9)  {
$m='0'.$j;
}
else {
$m=$j;
}

$dateeveryday=$year."-".$month."-".$m;
$datethismonth=$year."-".$month;

$filename_raw='../logs/'.$dateeveryday."_raw";
$filename_unique='../logs/'.$dateeveryday."_unique";


if(file_exists($filename_raw))  {
$rawhits=ParseFile($filename_raw);
}
else  {
$rawhits="0";
}


if(file_exists($filename_unique))  {
$uniquehits=ParseFile($filename_unique);
}
else  {
$uniquehits="0";
}

$totaluniquehits=$totaluniquehits+$uniquehits;
$totalrawhits=$totalrawhits+$rawhits;


echo "    <tr>
      <td width=\"132\" height=\"15\"><font face=verdana size=1>$dateeveryday</font></td>
      <td width=\"132\" height=\"15\"><font face=verdana size=1><a href=\"index.php?step=showrefs&process=getrawreferers&date=$dateeveryday\">$rawhits</a></font></td>
      <td width=\"136\" height=\"15\"><font face=verdana size=1><a href=\"index.php?step=showrefs&process=getuniquereferers&date=$dateeveryday\">$uniquehits</a></font></td>
    </tr>";


}
   


echo "


    <tr>
      <td width=\"132\" height=\"19\"><font face=verdana size=1><b>Total:</b></font></td>
      <td width=\"132\" height=\"19\"><font face=verdana size=1><b><a href=\"index.php?step=showrefs&process=getrawreferers_thismonth&date=$datethismonth\">$totalrawhits</a></b></font></td>
      <td width=\"136\" height=\"19\"><font face=verdana size=1><b><a href=\"index.php?step=showrefs&process=getuniquereferers_thismonth&date=$datethismonth\">$totaluniquehits</a></b></font></td>
    </tr>";

?>

  </table>
  </center>
</div>




</body>

</html>



<?

##This function opens a file and returns its contents to the caller
function ParseFile ($filename)
{

if(!file_exists($filename))   {
print "<center><font size=1 face=verdana><b>Nothing found in the file</center></font>";
exit;
}
else  {    

if(is_readable($filename))  {
$handle=fopen($filename,"r");
$contents = fread ($handle, filesize ($filename));
fclose ($handle);

return $contents;
}
else  {
print "<center><font size=1 face=verdana>Unable to open the file: <b>$filename</b>. <br>File is found in the specified directory but it has not been possible to open it. <br>This may be a permission problem. Please set permission of this file to : <b>644</b>";
exit;
     }

   }
}

?>
