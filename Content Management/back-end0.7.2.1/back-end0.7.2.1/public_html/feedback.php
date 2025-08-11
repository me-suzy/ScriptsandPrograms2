<?php

   // don't cache this page
   $cachetimeout = -1;

   require('./config.php');

   $content = null;

   // $Id: feedback.php,v 1.15 2005/04/25 18:42:38 mgifford Exp $
   $pageTitle = pslgetText('Feedback'); // The name to be displayed in the header
   $xsiteobject = pslgetText('Feedback'); // This Defines The META Tag Object Type

   global $_BE, $_PSL;

   $ary['section'] = 'services';

   if (!empty($_GET['login'])) {
      $auth->login_if($_GET['login']);
   }

//   slashhead($pageTitle, $_PSL['metatags'], '', $chosenTemplate['header']);

   // Feedback Form
   if (!isset($semail)) {
      if (!empty($_REQUEST['email'])) {
         $semail = clean($_REQUEST['email']);
      } elseif (!empty($_REQUEST['Email'])) {
         $semail = clean($_REQUEST['Email']);
      } elseif (!empty($_REQUEST['semail'])) {
         $semail = clean($_REQUEST['semail']);
      } else {
      	 $semail = null;
      }
   }

   if (!isset($firstName)) {
      if (!empty($_REQUEST['firstName'])) {
         $firstName = clean($_REQUEST['firstName']);
      } elseif (!empty($_REQUEST['FirstName'])) {
         $firstName = clean($_REQUEST['FirstName']);
      } elseif (!empty($_REQUEST['First_Name'])) {
         $firstName = clean($_REQUEST['First_Name']);
      } else {
      	 $firstName = null;
      }
   }

   if (isset($remailDropDown)) {
      // <select name="remailDropDown"><option value="">Select<option value="0">Webmaster<option value="1">OpenConcept 1<option value="2">OpenConcept 2</select>
      // $remailArray = array("webmaster@back-end.org","back-end1@back-end.org","back-end3@back-end.org");
      // $remail = $remailArray[$remailDropDown];

      // The above code was replaced with the following to allow the admin to manage this through the admin interface.
      $q = " SELECT email FROM $usertable WHERE uid = '$remailDropDown' ";
      if ($r = mysql_query($q)) {
         $remail = mysql_result($r, 0);
         // echo "Member Email: $remail";
      } else {
         echo 'Invalid Member';
      }
   }
   if (!isset($remail)) {
      $remail = $_PSL['site_owner'];
   }
   if (!isset($title)) {
      $title = $_PSL['site_name'] . ' Feedback';
   }

   // Email Validation
   function validEmail($semail) {
      global $error, $error_html;
      // Decides if the email address is valid. Checks syntax and MX records,
      // for total smartass value. Returns "valid", "invalid-mx" or  "invalid-form".

      // Validates the email address. I guess it works. *shrug*
      if (eregi("^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}$", $semail, $check)) {
         if (checkdnsrr(substr(strstr($check[0], '@'), 1), "ANY") ) {
            $error = 0;
         } else {
            $error = 1;
            $error_html .= 'This email address is invalid ' . $semail . ', because it has an inacurate DNS Record.<br />';
         }
      } else {
         $error = 1;
         $error_html .= 'This email address is invalid ' . $semail . '<br />';
      }
      return $error;
   }

   // Old Verification
   // if (eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$", $semail)) {

   if (!validEmail($semail)) {

      // The Real IP Address of the Sender
      $userinfo = 'RealFrom: ' . $_SERVER['REMOTE_ADDR'] . "\nPostedFrom: " . @$_SERVER['HTTP_REFERER'] . "  \nBrowserType:" . $_SERVER['HTTP_USER_AGENT'] . " \n------\n\n";

      $sentData = clean(array_merge ($_GET, $_POST));

      // Takes the posted values and matches them up with their keys
      // Assign variables from the posted variables as if they were an array
      if (empty($msgtext)) $msgtext = '';
      if (empty($Email)) $Email = '';
      while (list($key, $val) = each($sentData)) {
         $msgtext .= "$key = $val\n";
      }
      $msgtext = stripslashes($msgtext);

      // From
      $headers = "From: $semail\n"; // , -f$Email

      // Set replyto
      $headers .= "Reply-To: $semail\n";

      // mailer
      $headers .= "X-Mailer: PHP " . phpversion() . "\n";

      // Urgent message!
      // $headers .= "X-Priority: 1\n";

      if (isset($sentData['ccme'])) {
         $headers .= "cc: {$sentData['ccme']}\n";
      }

      $headers .= "Return-Path: $remail\n";
      // Return path for errors

      // Sends it all to the the email below
      // echo "<pre>$remail, $title, $msgtext, $headers</pre>";
      if(@mail($remail, $title, html_entity_decode($msgtext), $headers)) {
         $emailSent = true;
      }

      $content = '<br /><strong>';
      if ($firstName) {
         $content .= ucfirst($firstName) . ', ';
      }
      $content .= pslgetText('Thanks!') . '</strong><p><hr width="50%" /></p><p><blockquote>' . nl2p($msgtext) . '</blockquote></p>';
      // $content .=  "<hr width=\"50%\"><a href=\"$HTTP_REFERER\">To Go Back</a>";

   } else {
      $content .= "<br /><strong>$firstName your comments were not sent to us because you did not use a valid email address ($semail).</strong>";
      // $content .=  "<a href=\"$HTTP_REFERER\">To Go Back</a>";
   }

   if (!(isset($emailSent) && $emailSent)) {
      $content = 'There was a problem sending this message, please contact the webmaster';
   }

//   slashfoot($chosenTemplate['footer']);

   $breadcrumb = '<a href="' . $_PSL['rooturl'] . '/">' . pslgetText('Home') . '</a> &#187; ' . $title;

   // generate the page
   generatePage($ary, $pageTitle, $breadcrumb, $content);

   page_close();

?>
