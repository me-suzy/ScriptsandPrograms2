<?

echo "<title>BE Technic 'Converto' Converter - Computer -=( nulled by WDYL-WTN )=-</title>";

echo "<link rel='stylesheet' type='text/css' href='converter.css'>
<form method='POST' action='computer.php'>
  <select size='7' name='from'>
  <option value='bit'>bit</option>
  <option value='Byte'>Byte</option>
  <option value='KByte'>Kilobyte</option>
  <option value='MByte'>Megabyte</option>
  <option value='GByte'>Gigabyte</option>
  <option value='TByte'>Terrabyte</option>
  <option value='PByte'>Petabyte</option>
  </select>
  <img src='img/arrow.gif'>
  <select size='7' name='to'>
  <option value='bit'>bit</option>
  <option value='Byte'>Byte</option>
  <option value='KByte'>Kilobyte</option>
  <option value='MByte'>Megabyte</option>
  <option value='GByte'>Gigabyte</option>
  <option value='TByte'>Terrabyte</option>
  <option value='PByte'>Petabyte</option>
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


$myFile=fopen('computer.dat','r');


   do
   {
   $line=fgets($myFile,30);
   $lineArray=explode(' ',$line);
   if($lineArray[0]==$from)
      $temp=(double)$lineArray[1]*$fromvalue;
   }
   while($lineArray[0]!=$from);



fclose($myFile);

$myFile=fopen('computer.dat','r');


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