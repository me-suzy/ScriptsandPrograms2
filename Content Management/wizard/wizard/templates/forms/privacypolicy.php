<?php

// Online Privacy Policy
// Copyright 2006 Philip Shaddock www.ragepictures.com

// main configuration file
	include_once '../../inc/config_cms/configuration.php';
// database class
	include_once '../../inc/db/db.php';
// language translation
	include_once '../../inc/languages/' . $language . '.public.php';
// authentication
	include_once '../../inc/functions/user.php';
// configuration parameters
	$db = new DB();
	$db->query("SELECT * FROM ". DB_PREPEND . "config");
	$config = $db->next_record();
	$db->close();		
	
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Confirmation</title>
<link href="<?php echo CMS_WWW; ?>/templates/css/basestyles.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" type="image/ico" href="<?php echo CMS_WWW; ?>/favicon.ico" />
</head>

<body id="bd">

<!--Outside Table-->
	<table align="center" cellspacing="0" id="alertTable">
      <tr><td>
	  <table width="100%" border="0" cellpadding="5" cellspacing="0">
      <tr>
        <td colspan="2" id="alertHeader">Privacy Policy</td>
      </tr>
      <tr><td>
	  <table align="center" width="90%" border="0" cellpadding="5" cellspacing="0" >
	  <tr><td>&nbsp;</td></tr>
	  <tr><td>
       
<!--Inside Table-->

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr><td >

<p><?php echo COMPANY_NAME; ?> and our associates take your online privacy seriously. We know you want your personally identifiable information ("Personal Information") protected. </p>
<p>
Personal Information means any information that may be used to identify an individual, including, but not limited to, a first and last name, a home or other physical address and an email address or other contact information, whether at work or at home.</p>

<p>
If you provide us with Personal Information, we may need to transmit that Information within <?php echo COMPANY_NAME; ?> or to <?php echo COMPANY_NAME; ?> third-party service providers, across borders, and from your country or jurisdiction to other countries or jurisdictions around the world. </p>

<p>We use "cookies" to identify registered members on repeat visits and to track some of their Personal Information. If you do not understand cookies, we suggest you Google information about their use. Modern browsers can be set to notify you of the receipt of a cookie, or you can block cookies using settings in your browsers. However, if you choose to block cookies, you will need to log back in every time you attempt to access restricted pages. </p>
<p>
<p>Where we collect Personal Information, we intend to state the purpose for which it is gathered and whether we intend to share it outside of <?php echo COMPANY_NAME; ?> or those working on <?php echo COMPANY_NAME; ?>'s behalf. We and our associates do not intend to transmit your Personal Information without your consent to third parties who are not bound to act on <?php echo COMPANY_NAME; ?>'s behalf unless such transfer is legally required. </p>
<p>We intend to take reasonable and appropriate steps to protect the Personal Information that you share with us from unauthorized access or disclosure. </p>
<p>Third parties may provide additional services available through <?php echo SITE_NAME; ?>. <?php echo COMPANY_NAME; ?> may have to provide information, including Personal Information, to third-party service providers to help us deliver programs, products, information, and services. <?php echo COMPANY_NAME; ?> will take reasonable steps to ensure that these third-party service providers are obligated to protect Personal Information on <?php echo COMPANY_NAME; ?>'s behalf. </p><p>
<?php echo COMPANY_NAME; ?> does not intend to transfer Personal Information without your consent to third parties who are not bound to act on <?php echo COMPANY_NAME; ?>'s behalf unless such transfer is legally required. Similarly, it is against <?php echo COMPANY_NAME; ?>'s policy to sell Personal Information collected online without consent. </p>
<h3>Children's Privacy</h3>
<p><?php echo SITE_NAME; ?> does not have content for children. Therefore we do not intend to collect Personal Information from anyone we know to be under 13 years of age. </p>
<p><b>While we cannot guarantee privacy perfection, we will address any issue to the best of our abilities as soon as possible.</B> </p>
<p>By using this Web site, you consent to the terms of our Online Privacy Policy and to <?php echo COMPANY_NAME; ?>'s processing of Personal Information for the purposes given above as well as those explained where <?php echo COMPANY_NAME; ?> collects Personal Information on the Web. Should the Online Privacy Policy change, we intend to take every reasonable step to ensure that these changes are brought to your attention by posting all changes prominently on our web site for a reasonable period of time.</p>

<p align="center">Return to <a href="<?php echo CMS_WWW; ?>">Home</a></p>
<p>&nbsp;</p>


<!-- End inside table -->
	</td>
    
  </tr>
</table>
</td>
    
  </tr>
</table>