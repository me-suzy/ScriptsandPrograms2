<?
/****************************************************************************
* Copyright 2003   Richard Harkrider (c) All rights reserved.
* Created 06/2003 Last Modified 09/28/2004
* Richard Harkrider, http://hiqformmail.com.
* *****
*     This program is free software; you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation; either version 2 of the License, or
*    (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with this program; if not, write to the Free Software
*    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
* 
* ***** See HiQ Formmail documentation for link to full license *****
* 
* This application will only function at PHP Version 4.1 or higher.  
*
*        *******************************************************
*        ** I have no way test this softeware and therefor    **
*        ** DO NOT support its use in a Windows environment.  **
*        ** When time is available I'll try to help but give  **
*        ** no guarantees.                                    **
*        *******************************************************
* 
*****************************************************************************/
//                   BEGIN ENVIRONMENT DEFINATION AREA
//************ Initialize some misc. items 
$this_version = "1.5.1";
$errorlist = '';   
$content = '';
$recipient = '';
$bcc = '';
$sendBlank = '';
$selectedMultiple = '';
// File extentions not allowed to be attached.
$badextns = "bat,com,exe,scr,vbs,vbe,js,reg,pcd,inf,plf,pcd";
//******                     BEGIN FUNCTION DEFINITIONS  
// All form errors are issued by this function
function issueErrors($errorlist, $ehead, $efoot) {
  global $this_version;
  include($ehead);
  print "<BR>The form was not submitted for the following reasons<BR>";
  print "$errorlist<BR><BR>";
  echo "<BR><BR><small>Brought to you by <a href=\"http://hiqformmail.com\" target=\"_blank\">HiQ Formmail Version $this_version</a></small>\n\n";
  include($efoot);
  exit;
}

function issueSingleError($errorlist) {
  global $this_version;
  print "<BR>An error was encountered.  Mail may not have been sent.<BR>";
  print "$errorlist<BR><BR>";
  echo "<BR><BR><small>Brought to you by <a href=\"http://hiqformmail.com\" target=\"_blank\">HiQ Formmail Version $this_version</a></small>\n\n";
  
}

// See if user is in the banned list. 
function isBanned($banned, $email) {
  global $errorlist;
  $found = 0;
  $temp_mail = explode("@", $email);
  if($email != '') {
    for ($x=0; $x < count($banned); $x++) {
      $temp_ban = explode("@", $banned[$x]);   
      if (eregi ($temp_ban[1], $temp_mail[1])) {
        if($temp_ban[0] == '*') $found = 1;
        if(strtolower($temp_ban[0]) == strtolower($temp_mail[0])) $found = 1;
      }
    }
  }   
  if($found != 0) 
    $errorlist .= "<BR>You attempting to use from a <b>banned email address - $email.</b><BR>";
}

// parse the form and create the content string which we will send
function parseForm($includeBlank) {
  global $HTTP_POST_VARS;
  $formData = "";
  // build reserved keyword array
  $hidden_list[] = "MAX_FILE_SIZE";
  $hidden_list[] = "subject";
  $hidden_list[] = "redirect";
//  $hidden_list[] = "auto_responder";
  $hidden_list[] = "configfilename";
  $hidden_list[] = "SelectRecipient";
  $hidden_list[] = "SelectRecipient_Special";
  $hidden_list[] = "MultipleValueFormat";
// The superglobals $_POST is prefered but
// for backward compatability using the $HTTP_POST_VARS
//  foreach($_POST as $varName => $value){
  $seperator = " & ";
  $listas = explode(",", getPostValue("MultipleValueFormat"));
  if($listas[0] == 'column' && $listas[1] != '') $seperator = " $listas[1] ";
  foreach($HTTP_POST_VARS as $varName => $value){
    $found = 0;
    $varN = str_replace("_", " ", $varName);    // Turn underscores into blanks  
    if(in_array($varName, $hidden_list)) $found = 1;
    if($found == 0) {
      if(is_array($value)) {
	    if($listas[0] != 'column') {
          for($k=0; $k < count($value); $k++) {
            $formData .= "$varN:  $value[$k]\n";
          }
		} else {
		  $formdata .= "$varN: " . implode($seperator, $value) . "\n";  
        }
      } else {
	    if($value != '') {
          $formData .= "$varN:  $value\n";
        } else {
          // BY default blank fields are not printed.
          if($includeBlank != '') $formData .= "$varN:  $value\n";
        }
	  }
    }
  } 
  return $formData;
}

// mail the content
function mailIt($content, $subject, $email, $recipient, $bcc) {
  global $attachuser, $attachtemp, $attachsize, $attachtype, $this_version;
  $headpart = "";
  $msgpart = "";
  // build message headers   
  $headpart = "From: $email\n"; 
  $headpart .= "Reply-To: $email\n";  
  if ($bcc != '') $headpart .= "Bcc: $bcc\n";
  $headpart .= "X-Mailer: HiQ FormMail $this_version\n";
  
  if ($attachuser) {
    // create a MIME boundary string
    $boundary = md5(uniqid(time()));

    // add MIME data to the message headers
    $headpart = "MIME-Version:1.0\n";
    $headpart .= "From: $email\n"; 
    $headpart .= "Reply-To: $email\n";  
    if ($bcc != '') $headpart .= "Bcc: $bcc\n";
    $headpart .= "X-Mailer: HiQ FormMail Version $this_version\n";
    $headpart .= "Content-Type: multipart/mixed;\n\tboundary=\"$boundary\"\n";
          
    $msgpart  = "This is a multi-part message in MIME format.\n";
    $msgpart .= "\n--$boundary\n";
    $msgpart .= "Content-Type: text/plain\n";
    $msgpart .= "Content-Transfer-Encoding: 8bit\n\n";
    $msgpart .= "$content\n";   
    for($i = 0; $i < count($attachuser); $i++) {
      $msgpart .= "\n--$boundary\n";
      $fname = basename($attachuser[$i]);
      $msgpart .= "Content-Type: $attachtype[$i]; \n\tname=\"$fname\"\n";
      $msgpart .= "Content-Transfer-Encoding: base64\n";
	  $msgpart .= "Content-Disposition: attachment; filename=\"$fname\"\n\n";
      $fcontent = fread(fopen($attachtemp[$i], "r"), filesize($attachtemp[$i]));
      $fcontent = chunk_split(base64_encode($fcontent));      
      $msgpart .= "$fcontent\n";
    }
    $msgpart .= "\n--$boundary--\n";
  } else {
    $msgpart .= "$content\n";
  }
  
  if(!mail($recipient, $subject, $msgpart, $headpart)) {
    deleteTempFiles();
    issueSingleError("An undetermined error occured while attempting to send mail.");
  }
  deleteTempFiles();
}

function getPostValue($fieldName, $default = '') {
  global $HTTP_POST_VARS;
   if (array_key_exists($fieldName, $HTTP_POST_VARS)) {
       return $HTTP_POST_VARS[$fieldName];
   } else {
       return $default;
   }
}

// function to delete temp files from the server. Make sure things are cleaned up.
function deleteTempFiles () {
  global $attachtemp;
  for($i = 0; $i < count($attachtemp); $i++) {  
    if(file_exists($attachtemp[$i])) {
      unlink($attachtemp[$i]);	// delete temp file
    } 
  }
}  

//******                        PROCESSING BEGINS HERE
if($_SERVER['REQUEST_METHOD'] == "GET") {
  print "<br><br><strong>Sorry but HiQ FormMail is not designed to be used in this manner";
  print "<br>TERMINATED</strong><br>";
  exit;
}
// Process config file
$configname = getPostValue ('configfilename');
if($configname == '') $configname = "HiQFMConfg";
include ('./class.HiQFMConfig.php');
$config = new FormConfiguration ($configname);
$email = getPostValue ('email');
$thebanned = $config->getDirective('banned');
if($email != '') {
  if ($thebanned[0] != '')isBanned($thebanned, $email);
  if (!eregi('^[_\\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\\.)+[a-z]{2,6}$', $email)) 
    $errorlist .= "<BR><b>The email address ($email) is not valid.</b><BR>";
}    

// Process recipient information

//$config->dump ();
$temp = getPostValue('SelectRecipient_Special');  // Adding SelectRecipient - scalar 
$selectedMultiple = getPostValue('SelectRecipient');   // Adding SelectRecipient - array

if(is_array($selectedMultiple)) {
  $selectedTemp = implode(",", $selectedMultiple);
} else {
  $selectedTemp = $selectedMultiple;
}  
if($selectedTemp != '') {
  if($temp != '') $temp .= ",";
  $temp .= $selectedTemp;  // Add recipients array to $temp
}
     
$recipientList = $config->getDirective('recipient');
$recipient_in[0] = '';
$incount = 0;
$selectedRecipient = explode(",", $temp);  //Selected reipients as an array
for ($k=0; $k < count($selectedRecipient); $k++) {
  $temp1 = trim(strtolower($selectedRecipient[$k])); // $temp1 set to user
  if($selectedRecipient[$k] != '') {
    $found = 0;
    for($l=0; $l < count($recipientList); $l++) {
      $temp2 = explode('@', $recipientList[$l]); // $temp2 set to user portion of email
	  if(trim(strtolower($temp2[0])) == $temp1) {
	    $recipient_in[$incount] = $recipientList[$l]; // Add valid recipient to list
	    $incount++;
	    $found = 1;
	  }   
    } 
    if($found == 0) $errorlist .= "<BR>The user ($temp1) has no entry in the list of valid recipients.<BR>";
  }
}
// If there were no valid selected recipients send mail to full list
if($incount == 0) $recipient_in = $config->getDirective('recipient');
if ($recipient_in[0] == '') {
  $errorlist .= "<BR>Config file contains no recipient entry.  Correct or contact Admin.";
}
for ($i=0; $i < count($recipient_in);$i++) {
  $recipient_to_test = trim($recipient_in[$i]);
  if (!eregi("^[_\\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\\.)+[a-z]{2,6}$", $recipient_to_test)) {
    $errorlist .= "<BR><b>The recipient address ($recipient_to_test) is not valid.</b><BR>";
  }
  // Build recipent string
  if ($i == 0) {
    $recipient .= $recipient_to_test;
  } else {
    $recipient .= ",$recipient_to_test";
  }
}

// Check for a bcc
$bcc_in = $config->getDirective('bcc');
if($bcc_in[0] != '') { 
  for ($i=0; $i < count($bcc_in);$i++) {
    $bcc_to_test = trim($bcc_in[$i]);
    if (!eregi("^[_\\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\\.)+[a-z]{2,6}$", $bcc_to_test)) {
      $errorlist .= "<BR><b>The bcc address ($bcc_to_test) is not valid.</b><BR>";
    }
    // Build recipent string
    if ($i == 0) {
      $bcc .= $bcc_to_test;
    } else {
      $bcc .= ",$bcc_to_test";
    }
  }
}

// prepare the content
$sent_at = date('F jS, Y') . ' at ' . date('h:iA (T).');
$content .= "The following was submitted on " . $sent_at . "\n";
$content .= "HiQFormMail version $this_version - " . $_SERVER['REMOTE_ADDR'] . " - " . $_SERVER['HTTP_REFERER'] . "\n\n";

// Set the mail subject.
$subject = getPostValue('subject');
if($subject == '') $subject = $config->getDirective('subject');
$content .= "Subject: $subject\n";
$sendBlank = $config->getDirective('send_blank_fields');
$content .= parseForm($sendBlank);
$required_list = $config->getDirective('required_fields');
if ($required_list[0] != '') { 
  for ($i=0; $i < count($required_list);$i++) {
    $required_list[$i] = trim($required_list[$i]);
    $the_value = getPostValue($required_list[$i]);        
    if ($the_value == '') $errorlist .= "Missing field:  $required_list[$i]<BR>\n";           
  }
}

// Check if there are file(s) to attach -- if so do it.
$blockextns = $config->getDirective('non_valid_extensions');  // allowed extensions setup.
$temp = strtolower(implode(",", $blockextns));
$nono = $badextns;
if($temp != '') $nono .= ",$temp";
$nonoexts = explode(",", $nono);

$content .= "\n";
$temp = '';
$valid_types = $config->getDirective('valid_attach_types');  // valid file types setup.
$temp = strtolower(implode(",", $valid_types));
$valid_types = explode(",", $temp);

$temp = '';
$valid_extns = $config->getDirective('valid_attach_extensions');  //valid file extensions setup.
if($valid_extns[0] != '') $temp = strtolower(implode(",", $valid_extns));
$valid_extns = explode(",", $temp);

$content .= "\n";
$attachCount = 0;
if (isset($_FILES['attachment']['name'])) {
  $vname = 'attachment';
  for ($i=0; $i < count($_FILES['attachment']['name']); $i++) {
    $file_name = $_FILES[$vname]['name'][$i];
    $file_tmp_name = $_FILES[$vname]['tmp_name'][$i];
    $tmp_files[] = $file_tmp_name;  // Keep uploaded file names for deletion in case of errors.
    $file_type = $_FILES[$vname]['type'][$i];
    $file_size = $_FILES[$vname]['size'][$i];
    $file_error = $_FILES[$vname]['error'][$i];
    if ($file_size <= 0) {
      if (trim($file_name)!= "") {
        $errorlist .= "<BR>file attach failed - <B> $file_name </B><BR>";
      }
    } else {
      if (is_uploaded_file($file_tmp_name)) {
        $is_safe_mode = $config->getDirective('safe_mode');      
        if($is_safe_mode != '') {
          $destn = $config->getDirective('safemode_temp_dir'); 
          $destn_dir = "./$destn";
          if(is_writable($destn_dir)) {
            $fname = basename($file_tmp_name);
            $destn_file = "$destn_dir/$fname";
            if(move_uploaded_file($file_tmp_name, $destn_file)) {
              $file_tmp_name = $destn_file;
            } else {
              $errorlist .= "<BR>-SAFE MODE- move of $file_tmp_name to $destn_file failed";
            }
          } else {
            $errorlist .= "<BR>-SAFE MODE- can not write to $destn";
          }
        }     
        if (is_readable($file_tmp_name)) {
          $attachtemp[] = $file_tmp_name;
          $attachuser[] = $file_name;
          $attachsize[] = $file_size;
        } else {    
          $errorlist .= "<BR>File $file_tmp_name - ($file_name) cannot be opened for reading.";
        }
      } else {
        $errorlist .= "<BR>Is_uploaded_file test on $file_tmp_name - ($file_name) failed.";
      }
	  $type = explode('/', $file_type);
      $exts = explode('.', $file_name);     
      $extn_to_test = strtolower($exts[count($exts) - 1]);     
      //Value of 'all' passes type testing	
      if ($valid_types[0] != 'all') {
        if(!in_array($type[0], $valid_types)) {
          $errorlist .= "<BR>file attach failed invalid type of <B>$type[0]</B> - <B>$file_name </B><BR>";
        }
      }
	  // Test file extension against possible config file defined.
      if($valid_extns[0] != '') {
        if(!in_array($extn_to_test, $valid_extns)) {
          $errorlist .= "<BR>File attach failed: this extension is not in the valid list / <B>$extn_to_test</B> - <B>$file_name </B><BR>";
        }
      }
      if(in_array($extn_to_test, $nonoexts)) {
        $errorlist .= "<BR>File attach failed: this extension is in the invalid list / <B>$extn_to_test</B> - <B>$file_name </B><BR>";
      }

      $attachtype[] = $file_type;
      $content .= "Attached file:   $file_name\n";
    }
  }
}    
//End Attach File

// Add eviromental variables to content if requested
$thisDir = $config->getDirective('HTTP_HOST');
if($thisDir != '') $content .= "HTTP HOST: " . $_SERVER['HTTP_HOST']."\n";
$thisDir = $config->getDirective('HTTP_REFERER');
if($thisDir != '') $content .= "HTTP_REFERER: " . $_SERVER['HTTP_REFERER']."\n"; 
$thisDir = $config->getDirective('REMOTE_ADDR');
if($thisDir != '') $content .= "REMOTE_ADDR: " . $_SERVER['REMOTE_ADDR']."\n"; 
$thisDir = $config->getDirective('BROWSER');
if($thisDir != '') $content .= "BROWSER: " . $_SERVER['HTTP_USER_AGENT']."\n"; 

// IF NO ERRORS send it off
// If there are errors we need to delete any temp files which may have 
// been uploaded prior to calling the issueErrors function.
if ($errorlist != "") {
  deleteTempFiles();
  $ehead = $config->getDirective('er_header_file');
  $efoot = $config->getDirective('er_footer_file');
  issueErrors($errorlist, $ehead, $efoot);
} else {
//
//print "<br> Recipients are $recipient";
  mailIt(stripslashes($content), stripslashes($subject), $email, $recipient, $bcc);
//  
}

// If an auto responder defined in form,  check existance & send it if exists.
// For security reasons the responder file MUST exist in the same directory as the script.
$autores = basename($config->getDirective('auto_responder'));
if ($autores != '') {
  if (file_exists($autores)) {
    $fd = fopen($autores, "rb");
    $ar_message = fread($fd, filesize($autores));
    fclose($fd);
    $ressubj = "RE: $subject";
    if(!mail($email, $ressubj, $ar_message, "From: $recipient_in[0]")) {
      issueSingleError("An undetermined error occured while attempting to send a response.");
    }
  } else {
    issueSingleError("The requested response file, <STRONG>$autores</STRONG> was not found.");
  }
}
$echo_it = str_replace("\n", "<br>", $content); 
// if the redirect option is set: redirect them
$redirect = getPostValue('redirect');

if($redirect == '')$redirect = $config->getDirective('redirect');
if ($redirect != '') {
  header("Location: $redirect");
  exit;
} else {
  $hffile = $config->getDirective('header_file');
  include($hffile);
  print "<BR>$echo_it";
  echo "<BR><BR><small>Brought to you by <a href=\"http://hiqformmail.com\" target=\"_blank\">HiQ Formmail Version $this_version</a></small>\n\n";
  $hffile = $config->getDirective('footer_file');
  include($hffile);
exit;
} 
?>