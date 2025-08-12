<?

echo "<title>BE Technic 'Converto' Converter - Power -=( nulled by WDYL-WTN )=-</title>";

echo "<link rel='stylesheet' type='text/css' href='converter.css'>
<form method='POST' action='power.php'>
  <select size='13' name='from'>
  <option value='btu/h'>btu/hour</option>
  <option value='cal/h'>calorie/hour</option>
  <option value='c-heatunit/h'>centigrade heat unit/hour</option>
  <option value='cbmt-atm/h'>cubic meter atmosphere/hour</option>
  <option value='ftpoundforce'>foot pound-force/hour</option>
  <option value='hp'>horsepower</option>
  <option value='joule/h'>joule/hour</option>
  <option value='kcal/h'>kilocalorie/hour</option>
  <option value='kgforcemeter'>kilogram force-meter/hour</option>
  <option value='kw'>kilowatt</option>
  <option value='nm/h'>newton-meter/hour</option>
  <option value='va'>volt amperkilowatt</option>
  <option value='w'>watt</option>
  </select>
  <img src='img/arrow.gif'>
  <select size='13' name='to'>
  <option value='btu/h'>btu/hour</option>
  <option value='cal/h'>calorie/hour</option>
  <option value='c-heatunit/h'>centigrade heat unit/hour</option>
  <option value='cbmt-atm/h'>cubic meter atmosphere/hour</option>
  <option value='ftpoundforce'>foot pound-force/hour</option>
  <option value='hp'>horsepower</option>
  <option value='joule/h'>joule/hour</option>
  <option value='kcal/h'>kilocalorie/hour</option>
  <option value='kgforcemeter'>kilogram force-meter/hour</option>
  <option value='kw'>kilowatt</option>
  <option value='nm/h'>newton-meter/hour</option>
  <option value='va'>volt amperkilowatt</option>
  <option value='w'>watt</option>
  </select></p>
  <p><h3>Convert</h3> <input type='text' name='fromvalue' size='19'>
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


$myFile=fopen('power.dat','r');


   do
   {
   $line=fgets($myFile,30);
   $lineArray=explode(' ',$line);
   if($lineArray[0]==$from)
      $temp=(double)$lineArray[1]*$fromvalue;
   }
   while($lineArray[0]!=$from);



fclose($myFile);

$myFile=fopen('power.dat','r');


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