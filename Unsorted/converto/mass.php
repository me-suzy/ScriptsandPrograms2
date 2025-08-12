<?

echo "<title>BE Technic 'Converto' Converter - Mass -=( nulled by WDYL-WTN )=-</title>";

echo "<link rel='stylesheet' type='text/css' href='converter.css'>
<form method='POST' action='mass.php'>
  <select size='13' name='from'>
  <option value='dram'>dram</option>
  <option value='grain'>grain</option>
  <option value='gram'>gram</option>
  <option value='UKLonghundredweight'>Hundredweight(UK,long)</option>
  <option value='USShorthundredweight'>Hundredweight(US,short)</option>
  <option value='kg'>kilogram</option>
  <option value='lb'>Lbs</option>
  <option value='mg'>milligram</option>
  <option value='ounce'>ounce</option>
  <option value='pound'>pound</option>
  <option value='longton'>ton(long)</option>
  <option value='ton'>ton(tonne)</option>
  <option value='shortton'>ton(short)</option>
  </select>
  <img src='img/arrow.gif'>
  <select size='13' name='to'>
  <option value='dram'>dram</option>
  <option value='grain'>grain</option>
  <option value='gram'>gram</option>
  <option value='UKLonghundredweight'>Hundredweight(UK,long)</option>
  <option value='USShorthundredweight'>Hundredweight(US,short)</option>
  <option value='kg'>kilogram</option>
  <option value='lb'>Lbs</option>
  <option value='mg'>milligram</option>
  <option value='ounce'>ounce</option>
  <option value='pound'>pound</option>
  <option value='longton'>ton(long)</option>
  <option value='ton'>ton(tonne)</option>
  <option value='shortton'>ton(short)</option>
  </select></p>
  <p> <h3>Convert</h3><input type='text' name='fromvalue' size='19'>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='index.html'>Back to main page</a> </p>
  <p><input type='submit' value='Calculate' name='B1' class='button'>&nbsp;<input type='reset' value='Reset' name='B2' class='button'></p>
</form>";

if(!($fromvalue))
   {
   echo "Please fill From value";
   exit;
   }

if($from==''||$to=='')
   {
   echo "Please select both <b>From</b> and <b>To</b>";
   exit;
   }


$myFile=fopen('mass.dat','r');


   do
   {
   $line=fgets($myFile,30);
   $lineArray=explode(' ',$line);
   if($lineArray[0]==$from)
      $temp=(double)$lineArray[1]*$fromvalue;
   }
   while($lineArray[0]!=$from);



fclose($myFile);

$myFile=fopen('mass.dat','r');


   do
   {
   $line=fgets($myFile,30);
   $lineArray=explode(' ',$line);
   if($lineArray[0]==$to)
      $tovalue=$temp/(double)$lineArray[1];
   }
   while($lineArray[0]!=$to);


fclose($myFile);


if($fromvalue>1)
   $fromadd='s';
if($tovalue>1)
   $toadd='s';

$fromvalue=number_format($fromvalue);
$tovalue=number_format($tovalue,3);
   echo "<h3>Result</h3> ";
   echo "<b>";
   echo $fromvalue;
   echo "</b>"." <i>$from</i>".$fromadd." = "."<b>";
   echo $tovalue;
   echo "</b>"." <i>$to</i>".$toadd;

?>