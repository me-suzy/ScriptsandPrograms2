<?

echo "<title>BE Technic 'Converto' Converter - Speed -=( nulled by WDYL-WTN )=-</title>";

echo "<link rel='stylesheet' type='text/css' href='converter.css'>
<form method='POST' action='speed.php'>
  <select size='10' name='from'>
  <option value='cm/sec'>centimeter/second</option>
  <option value='foot/sec'>foot/second</option>
  <option value='inch/sec'>inch/second</option>
  <option value='km/h'>kilometer/hour</option>
  <option value='knot'>knot</option>
  <option value='mach'>mach</option>
  <option value='mt/sec'>meter/second</option>
  <option value='mile/h'>mile(land)/hour</option>
  <option value='speedoflight'>speed of light(in vacuum)</option>
  <option value='speedofsound'>speed of sound(in the air)</option>
  </select>
  <img src='img/arrow.gif'>
  <select size='10' name='to'>
  <option value='cm/sec'>centimeter/second</option>
  <option value='foot/sec'>foot/second</option>
  <option value='inch/sec'>inch/second</option>
  <option value='km/h'>kilometer/hour</option>
  <option value='knot'>knot</option>
  <option value='mach'>mach</option>
  <option value='mt/sec'>meter/second</option>
  <option value='mile/h'>mile(land)/hour</option>
  <option value='speedoflight'>speed of light(in vacuum)</option>
  <option value='speedofsound'>speed of sound(in the air)</option>
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


$myFile=fopen('speed.dat','r');


   do
   {
   $line=fgets($myFile,30);
   $lineArray=explode(' ',$line);
   if($lineArray[0]==$from)
      $temp=(double)$lineArray[1]*$fromvalue;
   }
   while($lineArray[0]!=$from);



fclose($myFile);

$myFile=fopen('speed.dat','r');


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