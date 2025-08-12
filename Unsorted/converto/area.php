<?

echo "<title>BE Technic 'Converto' Converter - Area -=( nulled by WDYL-WTN )=-</title>";

echo "<link rel='stylesheet' type='text/css' href='converter.css'>
<form method='POST' action='area.php'>
  <select size='12' name='from'>
  <option value='acre'>acre</option>
  <option value='are'>are</option>
  <option value='dekare'>dekare</option>
  <option value='hectare'>hectare</option>
  <option value='sqcentimeter'>square centimeter</option>
  <option value='sqfoot'>square foot</option>
  <option value='sqinch'>square inch</option>
  <option value='sqkilometer'>square kilometer</option>
  <option value='sqmeter'>square meter</option>
  <option value='sqmile'>square mile(land)</option>
  <option value='sqmillimeter'>square millimeter</option>
  <option value='sqyard'>square yard</option>
  </select>
  <img src='img/arrow.gif'>
  <select size='12' name='to'>
  <option value='acre'>acre</option>
  <option value='are'>are</option>
  <option value='dekare'>dekare</option>
  <option value='hectare'>hectare</option>
  <option value='sqcentimeter'>square centimeter</option>
  <option value='sqfoot'>square foot</option>
  <option value='sqinch'>square inch</option>
  <option value='sqkilometer'>square kilometer</option>
  <option value='sqmeter'>square meter</option>
  <option value='sqmile'>square mile(land)</option>
  <option value='sqmillimeter'>square millimeter</option>
  <option value='sqyard'>square yard</option>
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


$myFile=fopen('area.dat','r');


   do
   {
   $line=fgets($myFile,30);
   $lineArray=explode(' ',$line);
   if($lineArray[0]==$from)
      $temp=(double)$lineArray[1]*$fromvalue;
   }
   while($lineArray[0]!=$from);



fclose($myFile);

$myFile=fopen('area.dat','r');


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
if($from=='sqinch'&&$fromvalue>1)
   $fromadd='es';
if($to=='sqinch'&&$tovalue>1)
   $toadd='es';
if($from=='sqfoot'&&$fromvalue>1)
{
   $from='sqfeet';
   $fromadd='';
}
if($to=='sqfoot'&&$tovalue>1)
{
   $to='sqfeet';
   $toadd='';
}

$fromvalue=number_format($fromvalue);
$tovalue=number_format($tovalue,3);
   echo "<h3>Result</h3> ";
   echo "<b>";
   echo $fromvalue;
   echo "</b>"." <i>$from</i>".$fromadd." = "."<b>";
   echo $tovalue;
   echo "</b>"." <i>$to</i>".$toadd;

?>