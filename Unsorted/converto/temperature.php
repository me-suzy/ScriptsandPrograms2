<?

echo "<title>BE Technic 'Converto' Converter - Temperature -=( nulled by WDYL-WTN )=-</title>";

echo "<link rel='stylesheet' type='text/css' href='converter.css'>
<form method='POST' action='temperature.php'>
  <select size='3' name='from'>
  <option value='C'>Celcius</option>
  <option value='F'>Fahrenheit</option>
  <option value='Kelvin'>Kelvin</option>
  </select>
  <img src='img/arrow.gif'>
  <select size='3' name='to'>
  <option value='C'>Celcius</option>
  <option value='F'>Fahrenheit</option>
  <option value='Kelvin'>Kelvin</option>
  </select></p>
  <p> <h3>Convert</h3><input type='text' name='fromvalue' size='19'>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='index.html'>Back to main page</a> </p>
  <p><input type='submit' value='Calculate' name='B1' class='button'>&nbsp;<input type='reset' value='Reset' name='B2' class='button'></p>
</form>";


if($from==''||$to=='')
   {
   echo "Please select both <b>From</b> and <b>To</b>";
   exit;
   }


if($from==$to)
   $tovalue=$fromvalue;
else
if($from=='C'&&$to=='F')
   {
      if($fromvalue<-273)
         die("From value is below absolute zero !");
      $tovalue=($fromvalue*100)/180+32;
   }
else
   if($from=='F'&&$to=='C')
   {
      $tovalue=((10*$fromvalue)-32)/18;
      if($tovalue<-273)
         die("Result is below absolute zero !");
   }
else
   if($from=='C'&&$to=='Kelvin')
   {
      if($fromvalue<-273)
         die("From value is below absolute zero !");
      $tovalue=$fromvalue+273;

   }
else
   if($from=='Kelvin'&&$to=='C')
   {
      $tovalue=$fromvalue-273;
      if($tovalue<-273)
         die("Result is below absolute zero !");
   }
else
   if($from=='Kelvin'&&$to=='F')
   {
      $tovalue=$fromvalue-273;
      if($tovalue<-273)
         die("Result is below absolute zero !");
      $tovalue=($tovalue*100)/180+32;
   }
else
   if($from=='F'&&$to=='Kelvin')
   {
      $tovalue=((10*$fromvalue)-32)/18;
      if($tovalue<-273)
         die("Result is below absolute zero !");
      $tovalue=$tovalue+273;
   }

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