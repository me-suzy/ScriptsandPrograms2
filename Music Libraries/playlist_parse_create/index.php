<strong>Please Select A Song Request</strong><br>
<br>
<?php
// Read Original PLaylist
include ("config.php");
$handle = fopen($playlist, "rb");
$contents = '';
while (!feof($handle)) {
  $contents .= fread($handle, 8192);
}
fclose($handle);

// Begin parsing and writing
$filename = 'playlist.txt';
$newline = "\n";
$text1 = str_replace ("#EXTINF:0,", "<option>" , $contents); // removes rubbish
$text1 = str_replace (".mp3", "</option>" , $text1); // removes rubbish
$text1 = str_replace ("#EXTM3U", "" , $text1); // removes more rubbish
$f=fopen($filename, "wb");
fputs($f, $text1);
fclose($f); // and writes file
$file = file($filename); // re opens file
$fp = fopen($filename, 'w');
foreach($file as $line){
  $line = trim($line);
  if(!empty($line)){ // removes blank lines
    fwrite($fp, $line.$newline);
  }
}
fclose($fp);
$key = "\\"; // search for backslash, this will remove file path in the playlist, leaving us only with song names
$fc=file($filename);
$f=fopen($filename,"w");
foreach($fc as $line)
{
     if (!strstr($line,$key)) //look for $key in each line
           fputs($f,$line); //place $line back in file
}
fclose($f);
// Read new Text file
$handle1 = fopen("playlist.txt", "rb");
$contents1 = '';
while (!feof($handle1)) {
  $contents1 .= fread($handle1, 8192);
}
fclose($handle1);
print "<form name=\"form1\" method=\"post\" action=\"index.php\">
</select>
	 
<select name=\"select[]\" size=\"15\" multiple>";
echo $contents1;
print "</select><input type=\"submit\" name=\"Submit\" value=\"Submit\">
 <input type=\"hidden\" name=\"good\" value=\"yes\"></form>";

// Create request list

if($_POST['good']=='yes') //if the hidden field was submitted (then we know our form has been)
{
// Create The m3u file with a random name
$filename = "chosen.txt"; // put the random number and the .m3u together.
$fp = fopen($filename, 'a'); // this will create the the m3u file since it doesn't exist
chmod($filename, 0777); // chmod our m3u to 777 so we can write data to it (and later delete the file)
fwrite($fp, ""); // Again not 100% neccesary, but just shows it can be written too
fclose($fp);  // close our m3u file.
// end creation
if (is_writable($filename)) { // if we can write to the file

   if (!$handle = fopen($filename, 'a')) { // but can't open it
         echo "Cannot open file ($filename)"; // print out an error message.
         exit; // and exit.
   } // and close the if statement.

$topicArray = $_POST['select']; // Get The Array of mp3's and wma's from our selectbox.
foreach ($topicArray as $select) // and for everyone
{
 // Write The data from the selectbox to our opened file.
   if (fwrite($handle, $select) === FALSE) { // more error checking for if file cannot be written to, if it can do it.
       echo "Cannot write to file ($filename)"; // and error message if it can't.
       exit; // and exit
   } // and close the if statement
   fwrite($handle, "<br>"); // and write a new line character so that each link to the mp3 / wma goes on a new line so media player
   // can read the file.
   }}}
   // Read Original PLaylist
$handle5 = fopen("chosen.txt", "rb");
$contents5 = '';
while (!feof($handle5)) {
  $contents5 .= fread($handle5, 8192);
}
fclose($handle5);
echo "<strong>Songs To Be Played</strong><br><br>";
echo $contents5;
?>