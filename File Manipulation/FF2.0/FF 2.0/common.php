<?php
# ---------------------------------------------------------------------
# eFusian
# Copyright (C) 2002 by the Fusian Development Team.
# http://www.efusian.co.uk
# ---------------------------------------------------------------------
# Author:              Oliver James Ibbotson Esq.
# Date:                1st February 2003
# ---------------------------------------------------------------------
# Script Name:         common.php / FileFusian
# Script Version:      2.0.1
#
# Description:         This script is the common file for the
#                      file-fusian script.
#
# Revision History:
# ---------------------------------------------------------------------
?>


<?php

/* Upload Routine */

if(isset($fupload))
{

	require 'config.php';
	
	$ext = strrchr($fupload_name,'.');
	
	if ($ext == $filetype1 || $ext == $filetype2 || $ext == $filetype3 || $ext == $filetype4 || $ext == $filetype5)
	{
		
	/* Peform Upload Actions */
	
	$final_path=$file_dir . "/" . $fupload_name;					# Generate final path & filename.
	move_uploaded_file( $fupload, $final_path ) or die ("Unable To Copy");	# Moves file.
	
	
	/* Perform Post Upload Actions */
	
	upload_report($fupload, $fupload_name, $fupload_size, $fupload_type, $file_url, $file);	# Call upload report generation function.

		if($logs_active == "1")
		{
	
		uplog_add($logfile, $fupload_name,  $REMOTE_ADDR);  # Log IP Address, FileName & Date to log file

		}
	
	}

	else
	{
	
	echo '<script language="JavaScript">';
		
	echo 'error = window.open("error.php?id=filetype","","width=350,height=235,status=no,toolbar=no,menubar=no");';

	echo '</script>';

       }
   
}

?>



<?php

/* Call Directory Listing */

if($_GET['id'] == "dirlist")
{
	
	require ('config.php');

	directory_listing($file_dir, $file_url);
	
}

?>



<?php

/* Upload Report Generation Function */
	
function upload_report($fupload, $fupload_name, $fupload_size, $fupload_type, $file_url, $file)
{
   
   	/* Javascript Popup Version */
   	
   	require('config.php');
   	
   	echo '<SCRIPT LANGUAGE="JavaScript">';
   	echo 'reportwin=window.open("about:blank","reportwin","status=no,location=no,toolbar=no,directories=no,resizable=no,width=600,height=400,top=100,left=100");';

   	echo 'reportwin.document.open();';
   	
   	echo 'reportwin.document.write("<font face=verdana color=darkblue>");';
   	print 'reportwin.document.write("<img src=\"$logo_file\">");';
   	
   	echo 'reportwin.document.close();';
   	
   	echo '</script>';
   	
   	
   	
   	/* Generate Upload Report */
	
	/* echo "";
	echo "<h3>Upload Report</h3>";
	echo '</font>';
	
	echo "<b>Path:</b> $fupload<br>";
	echo "<b>Name:</b> $fupload_name<br>";
	echo "<b>Size:</b> $fupload_size bytes<br>";
	echo "<b>Type:</b> $fupload_type<p>";
	
	echo "<b>Uploaded File:</b> <a href='$file_url/$fupload_name' target='_blank'>$fupload_name</a><br>";
	echo "<b>Destination:</b> $file_dir/$fupload_name <br><br>";*/
	
}
	
?>



<?php

/* Add Upload To Log File */

function uplog_add($logfile, $fupload_name, $REMOTE_ADDR)
{
	
	$fp = @fopen($logfile, a) or die("Couldn't Open Log File");
	
	$datestamp = date("l dS of F Y");
	$logentry = "$fupload_name " . "was uploaded by:" . " $REMOTE_ADDR " . "on" . " $datestamp" . "\n";
	
	fwrite($fp, $logentry);
	
	$fclose($fp);
	
}
	
?>



<?php 

/* Directory Listing Function */

function directory_listing($file_dir, $file_url)
{
  
  require('config.php');									# Reads in configuration file.
  
  echo("<link href='$skin' rel='stylesheet' type='text/css' />");
  
  $directory = opendir($file_dir);		    						# Open Directory for reading.
  
  
  echo '<center>';

  
  echo '<table class="dirlist-main-table">';
  echo '<tr><td class="dirlist-main-table">';
  
  
  echo '<table class="dirlist-logo-table">';
  echo '<tr><td class="dirlist-logo-table">';
  
  echo '<center>';
  echo "<img src='$logo_file'>";								# Display FileFusian logo.
  
  echo '</td></tr>';
  echo '</table>';
  
  
  echo '<table class="dirlist-dirtable">';
  
  $cssblock = "";
  
  for($fcount = 0; $fcount < $listsize; $fcount++)   					# File display loop - runs until $listsize value is reached.
    {
      
      if($cssblock != "dirlist-dirtable")
      {
      		
      		$cssblock = "dirlist-dirtable";
      		
      }
      
      else
      {
      	
      		$cssblock = "dirlist-dirtable-one";
      		
      }

      
  
      $file = readdir ($directory);
      
      if($file != "" && $file != "." && $file != "..")
      {
      	
      echo "<tr><td class='$cssblock'>";
      
      echo '<img src="http://efusian.co.uk/~filefusian/images/download.gif">';	# Link to download icon.
      
      print " <a href='$file_url/$file' target='_blank'>$file</a>";					# Prints filename hyperlink to directory listing.
      
      echo '</td></tr>';
      
      }
      

    }
    
  echo '</table>';
  
  
  
  echo '<table class="dirlist-basetable">';
  echo '<tr><td class="dirlist-basetable">';
  
  echo '<a href="javascript:window.close();">Close Directory Listing</a>';
  
  echo '</td></tr>';
  echo '</table>';
  
  
  
  echo '</td></tr>';
  echo '</table>';
  
}
 
?>