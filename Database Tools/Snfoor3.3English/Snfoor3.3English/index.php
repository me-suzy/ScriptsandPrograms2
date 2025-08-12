<? 
#######################################################################
#                                  Snfoor3.3
#                               www.magtrb.com
#                              26/09/05 23:00
#######################################################################
/*   Give Snfoor Directory chem 777 */

$secret = "123";   // Cpanel password
// data bases names
$datapases = array("Database1","Database2","Database3","Database4");
// users names
$usernames = array("user1","user2","user3");
// passwords
$passwords=array( "password1","password2","password3");
#  if you would like to add more databases or users or passwords just add
# ,"xxxx"
# in the end of the array expmple :
#$usernames = array("user1","user2","user3","xxxx","yyyyy");
########################################################################
#Credit:     I modifyied : bigdump
#            Author:       Alexey Ozerov (alexey at ozerov dot de)
#            Copyright:    GPL (C) 2003-2005
#            More Infos:   http://www.ozerov.de/bigdump
#
#-----------------------------------------------------------------------
#           and: Raga's MySQL Dumper
#             More Infos:   http://www.ragadesign.com
#
######################## Don't write under this line #########################
######################## Don't write under this line #########################
######################## Don't write under this line #########################

session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>SNFOOR 3.3</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1256" />

<style type="text/css">

FONT             {FONT-FAMILY: ms sans serif; FONT-SIZE: 12px}
BODY             {FONT-FAMILY: ms sans serif;
                  FONT-SIZE: 12px;
                  background:#ffffff}
P                {FONT-FAMILY: ms sans serif; FONT-SIZE: 14px}
DIV              {FONT-FAMILY: ms sans serif; FONT-SIZE: 14px}

td {
	border: 2px solid #7E3A9E;
	border-right: 2px solid #87529F;
	border-bottom: 2px solid #87529F;
	background-color: #E9DBF4;
	color: #3C0954;
	FONT-FAMILY: ms sans serif; FONT-SIZE: 14px
}
table {
	margin-left: auto;
	margin-right: auto;
}
form {
	padding: 0px;
	margin: 0px;
}
A:link       {color:#000000;
FONT-SIZE: 12px;
FONT-FAMILY: ms sans serif;text-decoration:none}
A:active     {color:#525293;
FONT-SIZE: 12px;
FONT-FAMILY: ms sans serif;text-decoration:none}
A:visited    {color:#525293;
FONT-SIZE: 12px;
FONT-FAMILY: ms sans serif;text-decoration:none}
A:hover      {color:#525293;
FONT-SIZE: 12px;
 FONT-FAMILY: ms sans serif;text-decoration:underline}
</style>

</head>

<body>


<h2 align="center">SNFOOR 3.3</h2>
<?

if ($_GET['action'] == "logout") {
	session_destroy();
	unset ($_SESSION['secret']);
}

$path_name = pathinfo($_SERVER['PHP_SELF']);
$this_script = $path_name['basename'];
if ($_SESSION['secret'] !== $secret) {

	if ($_POST['secret'] == $secret) {
		$_SESSION['secret'] = $secret;
	}
	else {
	  echo "<p align=\"center\"><b style=\"color: red\">Administrator Control Panel</b><br /></p><br />\n";
		echo "<form action=\"$PHP_SELF\" method=\"post\">	<p align=\"center\">\n";
		echo "<input name=\"secret\" type=\"password\" size=\"20\"><br>\n";
		echo "<input name=\"submit\" type=\"submit\" value=\"Enter\">\n";
		echo "</form>\n";
		exit;
	}
}

if ($action=="backup"){

echo "<p align=\"center\">Please choose the database you would like to backup</p><br />\n";
echo"<form method=\"POST\" action=\"$PHP_SELF?action=backup\"><p dir=\"ltr\" align=\"center\">
  <span> Database </span><select size=\"1\" name=\"database\">";
   Magtrb($datapases);
 echo"</select> <span>&nbsp; User </span>&nbsp;&nbsp;
  <select size=\"1\" name=\"username\">";
   Magtrb($usernames);
 echo" </select><span>&nbsp; Password  </span>&nbsp;&nbsp;
  <select size=\"1\" name=\"password\">";
   Magtrb($passwords);
 echo" </select><br><br>
  <input name=\"submit\" type=\"submit\" value=\"Backup\"></p>
  </form>";


if ($_POST['username']) {
	$filename = time() . "-" . $_POST['database'] . ".gz";	
	$backupcommand = "mysqldump -u " . $_POST['username'] . " --password=\"" . $_POST['password'] . "\" " . $_POST['database'] . " | gzip > " . $filename;
	passthru ($backupcommand);
	
	if (filesize($filename) > 100) {
		echo "<p align=\"center\">Backup successfully taken! Download:<br> <a href=\"$filename\">$filename</a></p><br /><br />\n";
	}
	else {
		echo "<p align=\"center\">Backup failed. Please verify the details submitted.</p><br /><br />";
		@unlink ($filename);
	}
}

if ($_GET['delete']) {
	unlink ($_GET['delete']);
}


if ($dir = @opendir("./")) {
	while (($file = readdir($dir)) !== false) {
		if ($file != ".." && $file != "." && $file !== $this_script && $file{0} !== ".") {
			$filelist[] = $file;
		}
	}
	closedir($dir);
}

if ($filelist) {
	arsort($filelist);
	
	
	echo "<table cellpadding=\"4\" cellspacing=\"1\">\n";
	foreach ($filelist as $key) {
		echo "<tr>\n";
		echo "<td><p align=\"left\"><a href=\"" . $root_dir . $key . "\" class=\"fname\">" . $key . "</a></td>\n";
		echo "<td><p align=\"left\" dir=\"ltr\">" . date ("d M Y - H:i:s", filemtime($key)) . "</td>\n";
    echo "<td><p align=\"left\"><a href=\"" . $this_script . "?action=backup&delete=" . $root_dir . $key . "\">[ Delete ]</a></td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n";
}

?>

<br />


<?
}
elseif ($action=="dump"){
//**************************************************************************

$NunLines = 1500;

if($_POST["dbusername"]){
$dbusername = $_POST["dbusername"];  }else {
      if($_SESSION['dbusername']){
      $dbusername = $_SESSION['dbusername'];}else{
      $dbusername ="username";}
       }
if($_POST["dbpassword"]){
$dbpassword = $_POST["dbpassword"]; } else {
      if($_SESSION['dbpassword']){
      $dbpassword = $_SESSION['dbpassword'];}else{
      $dbpassword ="password";}
       }
if($_POST["dbdatabase"]){
$dbdatabase = $_POST["dbdatabase"]; } else {
      if($_SESSION['dbdatabase']){
      $dbdatabase = $_SESSION['dbdatabase'];}else{
      $dbdatabase ="database";}
       }
if($_POST["linespersession"]){
$linespersession = $_POST["linespersession"]; } else {
      if($_SESSION['linespersession']){
      $linespersession = $_SESSION['linespersession'];}else{
      $linespersession =$NunLines;}
       }


$_SESSION['dbusername'] = $dbusername;
$_SESSION['dbpassword'] = $dbpassword;
$_SESSION['dbdatabase'] = $dbdatabase;
$_SESSION['linespersession'] = $linespersession;


echo "<p align=\"center\">Please choose the database you would like to dump to</p>\n";
echo "<form action=\"$PHP_SELF?action=dump\" method=\"post\">
    <p dir=\"ltr\" align=\"center\">
  <span lang=\"ar-sa\">Database : </span><select size=\"1\"dir=\"ltr\" value=\"$dbdatabase\"  name=\"dbdatabase\">";
   Magtrb($datapases);
 echo"</select> <span lang=\"ar-sa\">&nbsp; user :</span>&nbsp;&nbsp;
  <select size=\"1\" dir=\"ltr\" value=\"$dbusername\" name=\"dbusername\">";
   Magtrb($usernames);
 echo" </select><span lang=\"ar-sa\">&nbsp; password :</span>&nbsp;&nbsp;
  <select size=\"1\" dir=\"ltr\" value=\"$dbpassword\" name=\"dbpassword\">";
   Magtrb($passwords);
 echo" </select><br> <span lang=\"ar-sa\">&nbsp; line/session :</span>&nbsp;&nbsp;
  <input name=\"linespersession\" size=\"4\" dir=\"ltr\" value=\"$linespersession\"><br><br>
  <input name=\"submit\" type=\"submit\" value=\"show database\"></p>
  </form><br />";


IF ($dbdatabase =="database" || $dbpassword =="password"){exit;}
IF (!$dbdatabase || !$dbpassword){exit;}
$db_server  = "localhost";
$filename  = "";
$delaypersession = 0;
$comment[0]="#";
$comment[1]="-- ";


@define ("VERSION","Snfoor3.3");
@define ("MAX_LINE_LENGTH",65536);

@ini_set("auto_detect_line_endings", true);

@header("Expires: Mon, 1 Dec 2003 01:00:00 GMT");
@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", false);
@header("Pragma: no-cache");

?>

<table width="780" cellspacing="0" cellpadding="0">
<tr><td class="transparent">

<?

$error = false;
$file  = false;


if (!$error && !function_exists("version_compare"))
{ echo ("<p class=\"error\">PHP version 4.1.0 is required for BigDump to proceed. You have PHP ".phpversion()." installed. Sorry!</p>\n");
  $error=true;
}

// Calculate PHP max upload size (handle settings like 10M or 100K)

if (!$error)
{ $upload_max_filesize=ini_get("upload_max_filesize");
  if (eregi("([0-9]+)K",$upload_max_filesize,$tempregs)) $upload_max_filesize=$tempregs[1]*1024;
  if (eregi("([0-9]+)M",$upload_max_filesize,$tempregs)) $upload_max_filesize=$tempregs[1]*1024*1024;
  if (eregi("([0-9]+)G",$upload_max_filesize,$tempregs)) $upload_max_filesize=$tempregs[1]*1024*1024*1024;
}

// Handle file upload

$upload_dir=dirname($_SERVER["SCRIPT_FILENAME"]);

if (!$error && isset($_REQUEST["uploadbutton"]))
{ if (is_uploaded_file($_FILES["dumpfile"]["tmp_name"]) && ($_FILES["dumpfile"]["error"])==0)
  {
    $uploaded_filename=str_replace(" ","_",$_FILES["dumpfile"]["name"]);
    $uploaded_filepath=str_replace("\\","/",$upload_dir."/".$uploaded_filename);
    	
    if (file_exists($uploaded_filename))
    { echo ("<p class=\"error\">File $uploaded_filename already exist! Delete and upload again!</p>\n");
    }
    else if (eregi("(\.php|\.php3|\.php4|\.php5)$",$uploaded_filename))
    { echo ("<p class=\"error\">You may not upload this type of files.</p>\n");
    }
    else if (!@move_uploaded_file($_FILES["dumpfile"]["tmp_name"],$uploaded_filepath))
    { echo ("<p class=\"error\">Error moving uploaded file ".$_FILES["dumpfile"]["tmp_name"]." to the $uploaded_filepath</p>\n");
      echo ("<p>Check the directory permissions for $upload_dir (must be 777)!</p>\n");
    }
    else
    { echo ("<p class=\"success\">Uploaded file saved as $uploaded_filename</p>\n");
    }
  }
  else
  { echo ("<p class=\"error\">Error uploading file ".$_FILES["dumpfile"]["name"]."</p>\n");
  }
}


// Handle file deletion (delete only in the current directory for security reasons)

if (!$error && isset($_REQUEST["delete"]) && $_REQUEST["delete"]!=basename($_SERVER["SCRIPT_FILENAME"]))
{ if (@unlink(basename($_REQUEST["delete"])))
    echo ("<p class=\"success\">".$_REQUEST["delete"]." was removed successfully</p>\n");
  else
    echo ("<p class=\"error\">Can't remove ".$_REQUEST["delete"]."</p>\n");
}


// Open the database

if (!$error)
{ $dbconnection = @mysql_connect($db_server,$dbusername,$dbpassword);
  if ($dbconnection)
    $db = mysql_select_db($dbdatabase);
  if (!$dbconnection || !$db)
  { echo ("<p class=\"error\">Database connection failed due to ".mysql_error()."</p>\n");
    echo ("<p>Edit the database settings in ".$_SERVER["SCRIPT_FILENAME"]." or contact your database provider</p>\n");
    $error=true;
  }
}


// List uploaded files in multifile mode

if (!$error && !isset($_REQUEST["fn"]) && $filename=="")
{ if ($dirhandle = opendir($upload_dir))
  { $dirhead=false;
    while (false !== ($dirfile = readdir($dirhandle)))
    { if ($dirfile != "." && $dirfile != ".." && $dirfile!=basename($_SERVER["SCRIPT_FILENAME"]))
      { if (!$dirhead)
        { echo ("<table cellspacing=\"2\" cellpadding=\"2\">\n");
          echo ("<tr><th><p align=\"center\">Filename</td><th><p align=\"center\">Size</td><th><p align=\"center\">Date&amp;Time</td><th><p align=\"center\">Type</td><th>&nbsp;</td><th>&nbsp;</td>\n");
          $dirhead=true;
        }
        echo ("<tr><td><p align=\"center\">$dirfile</td><td class=\"right\">".filesize($dirfile)."</td><td>".date ("Y-m-d H:i:s", filemtime($dirfile))."</td>");
        if (eregi("\.gz$",$dirfile))
          echo ("<td>GZip</td>");
        else
          echo ("<td>SQL</td>");
        if (!eregi("\.gz$",$dirfile) || function_exists("gzopen"))
          echo ("<td><p align=\"center\"><a href=\"".$_SERVER["PHP_SELF"]."?action=dump&start=1&fn=$dirfile&foffset=0&totalqueries=0\">Start Import</a> into $db_name at $db_server</td>\n");
        else
          echo ("<td>&nbsp;</td>\n");
        echo ("<td><p align=\"center\"><a href=\"".$_SERVER["PHP_SELF"]."?action=dump&delete=$dirfile\">Delete file</a></td></tr>\n");
      }

    }
    if ($dirhead) echo ("</table>\n");
    else echo ("<p>No uploaded files found in the working directory</p>\n");
    closedir($dirhandle);
  }
  else
  { echo ("<p class=\"error\">Error listing directory $upload_dir</p>\n");
    $error=true;
  }
}


// Single file mode

if (!$error && !isset ($_REQUEST["fn"]) && $filename!="")
{ echo ("<p><a href=\"".$_SERVER["PHP_SELF"]."?action=dump&start=1&fn=$filename&foffset=0&totalqueries=0\">Start Import</a> from $filename into $db_name at $db_server</p>\n");
}


// File Upload Form

if (!$error && !isset($_REQUEST["fn"]) && $filename=="")
{

// Test permissions on working directory

  do { $tempfilename=time().".tmp"; } while (file_exists($tempfilename));
  if (!($tempfile=@fopen($tempfilename,"w")))
  { echo ("<p>Upload form disabled. Permissions for the working directory <i>$upload_dir</i> <b>must be set to 777</b> in order ");
    echo ("to upload files from here. Alternatively you can upload your dump files via FTP.</p>\n");
  }
  else
  { fclose($tempfile);
    unlink ($tempfilename);

    echo ("<p align=\"center\">You can now upload your dump file up to $upload_max_filesize bytes (".round ($upload_max_filesize/1024/1024)." Mbytes)  ");
    echo ("directly from your browser to the server. Alternatively you can upload your dump files of any size via FTP.</p>\n");
?>
<form method="POST" action="<? echo '".$PHP_SELF."?action=dump'; ?>" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="$upload_max_filesize">
<p align="center">Dump file: <input type="file" name="dumpfile" accept="*/*" size=60"></p>
<p align="center"><input type="submit" name="uploadbutton" value="Upload"></p>
</form>
<?php
  }
}


// Open the file

if (!$error && isset($_REQUEST["fn"]))
{

// Recognize GZip filename

  if (eregi("\.gz$",$_REQUEST["fn"]))
    $gzipmode=true;
  else
    $gzipmode=false;

  if ((!$gzipmode && !$file=fopen($_REQUEST["fn"],"rt")) || ($gzipmode && !$file=gzopen($_REQUEST["fn"],"rt")))
  { echo ("<p align=\"center\"> class=\"error\">Can't open ".$_REQUEST["fn"]." for import</p>\n");
    echo ("<p align=\"center\">You have to upload the ".$_REQUEST["fn"]." to the server</p>\n");
    $error=true;
  }

// Get the file size (can't do it fast on gzipped files, no idea how)

  else if ((!$gzipmode && fseek($file, 0, SEEK_END)==0) || ($gzipmode && gzseek($file, 0, SEEK_SET)==0))
  { if (!$gzipmode) $filesize = ftell($file);
    else $filesize = gztell($file); // Always zero, ignore
  }
  else
  { echo ("<p class=\"error\">I can't get the filesize of ".$_REQUEST["fn"]."</p>\n");
    $error=true;
  }
}


// ****************************************************
// START IMPORT SESSION HERE
// ****************************************************

if (!$error && isset($_REQUEST["start"]) && isset($_REQUEST["foffset"]))
{
  echo ("<p align=\"center\">Processing file: ".$_REQUEST["fn"]."</p>\n");
  echo ("<p align=\"center\">Starting at the line: ".$_REQUEST["start"]."</p>\n");

// Check $_REQUEST["foffset"] upon $filesize (can't do it on gzipped files)

  if (!$gzipmode && $_REQUEST["foffset"]>$filesize)
  { echo ("<p align=\"center\" class=\"error\">UNEXPECTED: Can't set file pointer behind the end of file</p>\n");
    $error=true;
  }

// Set file pointer to $_REQUEST["foffset"]

  if (!$error && ((!$gzipmode && fseek($file, $_REQUEST["foffset"])!=0) || ($gzipmode && gzseek($file, $_REQUEST["foffset"])!=0)))
  { echo ("<p align=\"center\" class=\"error\">UNEXPECTED: Can't set file pointer to offset: ".$_REQUEST["foffset"]."</p>\n");
    $error=true;
  }

// Start processing queries from $file

  if (!$error)
  { $query="";
    $queries=0;
    $totalqueries=$_REQUEST["totalqueries"];
    $linenumber=$_REQUEST["start"];
  //  $querylines=0;
    $inparents=false;

    while (($linenumber<$_REQUEST["start"]+$linespersession || $query!="")
       && ((!$gzipmode && $dumpline=fgets($file, MAX_LINE_LENGTH)) || ($gzipmode && $dumpline=gzgets($file, MAX_LINE_LENGTH))))
    {

// Handle DOS and Mac encoded linebreaks (I don't know if it will work on Win32 or Mac Servers)

      $dumpline=ereg_replace("\r\n$", "\n", $dumpline);
      $dumpline=ereg_replace("\r$", "\n", $dumpline);

// DIAGNOSTIC
// echo ("<p>Line $linenumber: $dumpline</p>\n");

// Skip comments and blank lines only if NOT in parents

      if (!$inparents)
      { $skipline=false;
        reset($comment);
        foreach ($comment as $comment_value)
        { if (!$inparents && (trim($dumpline)=="" || strpos ($dumpline, $comment_value) === 0))
          { $skipline=true;
            break;
          }
        }
        if ($skipline)
        { $linenumber++;
          continue;
        }
      }

// Remove double back-slashes from the dumpline prior to count the quotes ('\\' can only be within strings)

      $dumpline_deslashed = str_replace ("\\\\","",$dumpline);

// Count ' and \' in the dumpline to avoid query break within a text field ending by ;
// Please don't use double quotes ('"')to surround strings, it wont work

      $parents=substr_count ($dumpline_deslashed, "'")-substr_count ($dumpline_deslashed, "\\'");
      if ($parents % 2 != 0)
        $inparents=!$inparents;

// Add the line to query

      $query .= $dumpline;

// Don't count the line if in parents (text fields may include unlimited linebreaks)

   /*   if (!$inparents)
        $querylines++;

// Stop if query contains more lines as defined by MAX_QUERY_LINES

      if ($querylines>MAX_QUERY_LINES)
      {
        echo ("<p align=\"center\" class=\"error\">Stopped at the line $linenumber. </p>");
        echo ("<p align=\"center\">At this place the current query includes more than ".MAX_QUERY_LINES." dump lines. That can happen if your dump file was ");
        echo ("created by some tool which doesn't place a semicolon followed by a linebreak at the end of each query, or if your dump contains ");
        echo ("extended inserts. Please read the BigDump FAQs for more infos.</p>\n");
        $error=true;
        break;
      }
             */
// Execute query if end of query detected (; as last character) AND NOT in parents

      if (ereg(";$",trim($dumpline)) && !$inparents)
      { if (!mysql_query(trim($query), $dbconnection))
        { echo ("<p align=\"center\" class=\"error\">Error at the line $linenumber: ". trim($dumpline)."</p>\n");
          echo ("<palign=\"center\" >Query: ".trim($query)."</p>\n");
          echo ("<palign=\"center\" >MySQL: ".mysql_error()."</p>\n");
          $error=true;
          break;
        }
        $totalqueries++;
        $queries++;
        $query="";
       // $querylines=0;
      }
      $linenumber++;
    }
  }

// Get the current file position

  if (!$error)
  { if (!$gzipmode)
      $foffset = ftell($file);
    else
      $foffset = gztell($file);
    if (!$foffset)
    { echo ("<p align=\"center\" class=\"error\">UNEXPECTED: Can't read the file pointer offset</p>\n");
      $error=true;
    }
  }

// Finish message and restart the script

  if (!$error)
  { echo ("<p align=\"center\">Stopping at the line: ".($linenumber-1)."</p>\n");
    echo ("<p align=\"center\">Queries performed (this session/total): $queries/$totalqueries</p>\n");
    echo ("<p align=\"center\">Total bytes processed: $foffset (".round($foffset/1024)." KB)</p>\n");
    if ($linenumber<$_REQUEST["start"]+$linespersession)
    { echo ("<p align=\"center\" class=\"success\"><font color=\"#FF0000\">Congratulations: End of file reached, assuming OK</font></p>\n");
      echo ("<p align=\"center\" class=\"success\"><a href=\"http://www.hotscripts.com/Detailed/39206.html\">Vote for Snfoor at hotscripts</a></p>\n");

       $error=true;
    }
    else
    { if ($delaypersession!=0)
        echo ("<p align=\"center\" >Now I'm <b>waiting $delaypersession milliseconds</b> before starting next session...</p>\n");
      echo ("<script language=\"JavaScript\" type=\"text/javascript\">window.setTimeout('location.href=\"".$_SERVER["PHP_SELF"]."?action=dump&start=$linenumber&fn=".$_REQUEST["fn"]."&foffset=$foffset&totalqueries=$totalqueries\";',500+$delaypersession);</script>\n");
      echo ("<noscript>\n");
      echo ("<p align=\"center\"><a href=\"".$_SERVER["PHP_SELF"]."?action=dump&start=$linenumber&fn=".$_REQUEST["fn"]."&foffset=$foffset&totalqueries=$totalqueries\">Continue from the line $linenumber</a> (Enable JavaScript to do it automatically)</p>\n");
      echo ("</noscript>\n");
      echo ("<p align=\"center\">Press <a href=\"".$_SERVER["PHP_SELF"]."?action=dump\">STOP</a> to abort the import <b>OR WAIT!</b></p>\n");
    }
  }
  else
    echo ("<p align=\"center\" class=\"error\">Stopped on error</p>\n");
}

if ($error)
  echo ("<p align=\"center\"><a href=\"".$_SERVER["PHP_SELF"]."?action=dump\">Start from the beginning</a> (DROP the old tables before restarting)</p>\n");

if ($dbconnection) mysql_close();
if ($file && !$gzipmode) fclose($file);
else if ($file && $gzipmode) gzclose($file);


}

function Magtrb($x){
if (!empty($x))
{
	foreach ($x AS $_key)
	{
	echo"<option>$_key</option>";	
	}
}
}
?>
</td></tr></table>
<div style="color: #999999; font-size: 7pt;"> <p align="center">
[ <a href="<?PHP echo $this_script; ?>">Home</a> ] - [ <a href="<?PHP echo $this_script; ?>?action=backup">Backup</a> ] - [ <a href="<?PHP echo $this_script; ?>?action=dump">Dump</a> ]-[ <a href="http://www.magtrb.com" target="_blank">Magtrb Soft</a> ]-[ <a href="<?PHP echo $this_script; ?>?action=logout">Exit</a> ]
</div>
</body>
</html>

