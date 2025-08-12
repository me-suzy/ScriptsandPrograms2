<?

echo "<title>BE Technic 'Converto' Converter - Length -=( nulled by WDYL-WTN )=-</title>";

echo "<link rel='stylesheet' type='text/css' href='converter.css'>
<form method='POST' action='length.php'>
  <select size='16' name='from'>
  <option value='angstrom'>angstrom</option>
  <option value='cable'>cable length</option>
  <option value='centimeter'>centimeter</option>
  <option value='chain'>chain</option>
  <option value='fathom'>fathom</option>
  <option value='foot'>foot</option>
  <option value='inch'>inch</option>
  <option value='kilometer'>kilometer</option>
  <option value='league'>league</option>
  <option value='meter'>meter</option>
  <option value='micron'>micron</option>
  <option value='landmile'>mile(land)</option>
  <option value='seamile'>mile(nautical)</option>
  <option value='millimeter'>millimeter</option>
  <option value='point'>point(typography)</option>
  <option value='yard'>yard</option>
  </select>
  <img src='img/arrow.gif'>
  <select size='16' name='to'>
  <option value='angstrom'>angstrom</option>
  <option value='cable'>cable length</option>
  <option value='centimeter'>centimeter</option>
  <option value='chain'>chain</option>
  <option value='fathom'>fathom</option>
  <option value='foot'>foot</option>
  <option value='inch'>inch</option>
  <option value='kilometer'>kilometer</option>
  <option value='league'>league</option>
  <option value='meter'>meter</option>
  <option value='micron'>micron</option>
  <option value='landmile'>mile(land)</option>
  <option value='seamile'>mile(nautical)</option>
  <option value='millimeter'>millimeter</option>
  <option value='point'>point(typography)</option>
  <option value='yard'>yard</option>
  </select></p>
  <p> <h3>Convert</h3> <input type='text' name='fromvalue' size='19'>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='index.html'>Back to main page</a></p>
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


$myFile=fopen('length.dat','r');


   do
   {
   $line=fgets($myFile,30);
   $lineArray=explode(' ',$line);
   if($lineArray[0]==$from)
      $temp=(double)$lineArray[1]*$fromvalue;
   }
   while($lineArray[0]!=$from);



fclose($myFile);

$myFile=fopen('length.dat','r');


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
if($from=='inch'&&$fromvalue>1)
   $fromadd='es';
if($to=='inch'&&$tovalue>1)
   $toadd='es';
if($from=='foot'&&$fromvalue>1)
{
   $from='feet';
   $fromadd='';
}
if($to=='foot'&&$tovalue>1)
{
   $to='feet';
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