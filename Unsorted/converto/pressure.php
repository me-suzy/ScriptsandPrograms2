<?

echo "<title>BE Technic 'Converto' Converter - Pressure -=( nulled by WDYL-WTN )=-</title>";

echo "<link rel='stylesheet' type='text/css' href='converter.css'>
<form method='POST' action='pressure.php'>
  <select size='8' name='from'>
  <option value='atm'>atmosphere</option>
  <option value='bar'>bar</option>
  <option value='cmofmercury'>centimeter of mercury</option>
  <option value='dyne/sqcm'>dyne/square centimeter</option>
  <option value='gr-force/sqcm'>gram-force/square centimeter</option>
  <option value='newton/sqmt'>newton/square meter</option>
  <option value='ounce-force/sqinch'>ounce-force/square inch</option>
  <option value='pascal'>pascal</option>
  </select>
  <img src='img/arrow.gif'>
  <select size='8' name='to'>
  <option value='atm'>atmosphere</option>
  <option value='bar'>bar</option>
  <option value='cmofmercury'>centimeter of mercury</option>
  <option value='dyne/sqcm'>dyne/square centimeter</option>
  <option value='gr-force/sqcm'>gram-force/square centimeter</option>
  <option value='newton/sqmt'>newton/square meter</option>
  <option value='ounce-force/sqinch'>ounce-force/square inch</option>
  <option value='pascal'>pascal</option>
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


$myFile=fopen('pressure.dat','r');


   do
   {
   $line=fgets($myFile,30);
   $lineArray=explode(' ',$line);
   if($lineArray[0]==$from)
      $temp=(double)$lineArray[1]*$fromvalue;
   }
   while($lineArray[0]!=$from);



fclose($myFile);

$myFile=fopen('pressure.dat','r');


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