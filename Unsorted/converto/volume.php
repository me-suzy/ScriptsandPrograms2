<?

echo "<title>BE Technic 'Converto' Converter - Volume -=( nulled by WDYL )=-</title>";

echo "<link rel='stylesheet' type='text/css' href='converter.css'>
<form method='POST' action='volume.php'>
  <select size='21' name='from'>
  <option value='ukwinebarrel'>barrel(UK,wine)</option>
  <option value='ukbarrel'>barrel(UK)</option>
  <option value='usdrybarrel'>barrel(US,dry)</option>
  <option value='usliquidbarrel'>barrel(US,liquid)</option>
  <option value='uspetroleumbarrel'>barrel(US,petroleum)</option>
  <option value='cl'>centiliter</option>
  <option value='cbft'>cubic foot</option>
  <option value='cbinch'>cubic inch</option>
  <option value='cbmt'>cubicmeter</option>
  <option value='cbyd'>cubic yard</option>
  <option value='ukgallon'>gallon(UK)</option>
  <option value='usdrygallon'>gallon(US,dry)</option>
  <option value='usliquidgallon'>gallon(US,liquid)</option>
  <option value='lt'>liter</option>
  <option value='ml'>milliliter</option>
  <option value='ukliquidounce'>ounce(UK,liquid)</option>
  <option value='usliquidounce'>ounce(US,liquid)</option>
  <option value='ukpint'>pint(UK)</option>
  <option value='usdrypint'>pint(US,dry)</option>
  <option value='usliquidpint'>pint(US,liquid)</option>
  <option value='shot'>shot</option>
  </select>
  <img src='img/arrow.gif'>
  <select size='21' name='to'>
   <option value='ukwinebarrel'>barrel(UK,wine)</option>
  <option value='ukbarrel'>barrel(UK)</option>
  <option value='usdrybarrel'>barrel(US,dry)</option>
  <option value='usliquidbarrel'>barrel(US,liquid)</option>
  <option value='uspetroleumbarrel'>barrel(US,petroleum)</option>
  <option value='cl'>centiliter</option>
  <option value='cbft'>cubic foot</option>
  <option value='cbinch'>cubic inch</option>
  <option value='cbmt'>cubicmeter</option>
  <option value='cbyd'>cubic yard</option>
  <option value='ukgallon'>gallon(UK)</option>
  <option value='usdrygallon'>gallon(US,dry)</option>
  <option value='usliquidgallon'>gallon(US,liquid)</option>
  <option value='lt'>liter</option>
  <option value='ml'>milliliter</option>
  <option value='ukliquidounce'>ounce(UK,liquid)</option>
  <option value='usliquidounce'>ounce(US,liquid)</option>
  <option value='ukpint'>pint(UK)</option>
  <option value='usdrypint'>pint(US,dry)</option>
  <option value='usliquidpint'>pint(US,liquid)</option>
  <option value='shot'>shot</option>
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


$myFile=fopen('volume.dat','r');


   do
   {
   $line=fgets($myFile,30);
   $lineArray=explode(' ',$line);
   if($lineArray[0]==$from)
      $temp=(double)$lineArray[1]*$fromvalue;
   }
   while($lineArray[0]!=$from);



fclose($myFile);

$myFile=fopen('volume.dat','r');


   do
   {
   $line=fgets($myFile,30);
   $lineArray=explode(' ',$line);
   if($lineArray[0]==$to)
      $tovalue=$temp/(double)$lineArray[1];
   }
   while($lineArray[0]!=$to);


fclose($myFile);


$fromvalue=number_format($fromvalue);
$tovalue=number_format($tovalue,3);
   echo "<h3>Result</h3> ";
   echo "<b>";
   echo $fromvalue;
   echo "</b>"." <i>$from</i>".$fromadd." = "."<b>";
   echo $tovalue;
   echo "</b>"." <i>$to</i>".$toadd;

?>