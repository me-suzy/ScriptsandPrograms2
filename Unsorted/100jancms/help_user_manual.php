<?php
/***************************************\
|					|
|	    100janCMS v1.01		|
|    ------------------------------	|
|    Supplied & Nulled by GTT '2004	|
|					|
\***************************************/
// do not cache
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    	        // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate");  	        // HTTP/1.1
header ("Pragma: no-cache");                          	        // HTTP/1.0
 
// Restrict acces to this page

//this page clearance
$arr = array (  
  '0' => 'ADMIN',
  '1' => 'ARTICLES_MASTER',
  '2' => 'COMMENTS_MASTER',  
  );
define('CONSTANT_ARRAY',serialize($arr));

// check
include 'restrict_access.php';

//configuration file
include 'config_inc.php'; 


?>
<html>
<head>
<title>100janCMS Articles Control</title>
<?php echo "$text_encoding"; ?>
<link href="cms_style.css" rel="stylesheet" type="text/css">

<style type="text/css">
body
{
background-image: 
url("images/app/page_bg.jpg");
background-repeat: 
repeat-y;
background-attachment: 
fixed;
}
</style>

</head>

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0" class="maintext">
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
  <tr> 
    <td class="titletext0">Help:<span class="titletext0blue"> User Manual</span></td>
  </tr>
</table>
<br>
<br>
<span class="titletext0">100janCMS Articles Control</span> <span class="maintextplavi2"><strong>&nbsp;User 
Manual<br>
</strong></span>version 1.0<br>
<br>
<br>
<span class="maintextplavi2"><strong> <a name="contents" id="contents"></a>Contents</strong></span><br>
<br>
&#8226; <a href="#install"><strong>Installation instructions</strong></a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#install_step1">Installation: 
Step 1</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#install_step2">Installation: 
Step 2</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#install_step3">Installation: 
Step 3</a><br>
<br>
&#8226; <a href="#general"><strong>General Use instructions</strong></a><br>
<br>
&#8226; <a href="#articles"><strong>Articles</strong></a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#articles_items_add">Add 
new Article</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#articles_items_htmlarea">htmlArea</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#articles_items_search">View/Edit 
Articles: Search Articles</a> <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#articles_items_list">View/Edit 
Articles: Articles listing</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#articles_items_edit">View/Edit 
Articles: Edit Article</a><br>
<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#articles_marker_add">Add 
new Marker</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#articles_marker_search">View/Edit 
Markers: Search Marker</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#articles_marker_list">View/Edit 
Markers: Markers listing</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#articles_marker_edit">View/Edit 
Markers: Edit Marker</a><br>
<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#articles_cat_add">Add 
new Category</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#articles_cat_search">View/Edit 
Categories: Search Categories</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#articles_cat_list">View/Edit 
Categories: Categories listing</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#articles_cat_edit">View/Edit 
Categories: Edit Category</a><br>
<br>
&#8226; <a href="#comments"><strong>Comments</strong></a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#comments_search">View/Edit 
Comments: Search Comments</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#comments_list">View/Edit 
Comments: Comments listing</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#comments_edit">View/Edit 
Comments: Edit Comment</a><br>
<br>
&#8226; <a href="#visitors"><strong>Visitors</strong></a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#visitors_search">View/Edit 
Visitors: Search Visitors</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#visitors_list">View/Edit 
Visitors: Visitors listing</a><br>
<br>
&#8226; <a href="#users"><strong>Users</strong></a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#users_add">Add 
new User</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#users_search">View/Edit 
Users: Search Users</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#users_list">View/Edit 
Users: Users listing</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#users_edit">View/Edit 
Users: Edit User</a><br>
<br>
&#8226; <a href="#help"><strong>Help</strong></a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#help_manual">User 
manual</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#help_eula">License 
Agreement</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#help_about">About</a><br>
<br>
&#8226; <a href="#admin"><strong>Admin</strong></a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#admin_config">View/Edit 
Configuration</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; <a href="#admin_logout">Logout</a><br>
<br>
&#8226; <a href="#symbols"><strong>Symbols</strong></a><br>
<br>
<br>
<br>
<hr align="left" width="700" size="1" color="#969696" noshade>
<span class="maintextplavi2"><strong><a name="install" id="install"></a>Installation 
instructions </strong></span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0" class="maintext">
  <tr> 
    <td>Follow the instructions to install application.</td>
  </tr>
</table>
<br>
<table width="700" border="0" cellpadding="0" cellspacing="0" class="maintext">
  <tr> 
    <td> &#8226; Unzip distribution archive to your local computer to a temporary 
      ('temp') folder (preserving archive's folders structure).<br>
      &#8226; Create a folder of your choice (application folder) under root folder 
      of your host, where you will install SOFTWARE PRODUCT (by default use '100jancms', 
      e.g. http://www.yourdomain.com/100jancms/).<br>
      &#8226; Upload all files from temp/100jancms folder to the application folder.<br> 
      &#8226; Make sure the following files and folders are writable (CHMOD 666) 
      by application:<br> <br> <em>[files]:</em><br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- 
      http://www.yourdomain.com/100jancms/config_connection.php<br> <em>[folders]:</em><br> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- http://www.yourdomain.com/100jancms/images/<br> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- http://www.yourdomain.com/100jancms/images/articles/<br> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- http://www.yourdomain.com/100jancms/images/articles/depot/<br> 
      <br> &#8226; Run install.php file from web browser from the application 
      folder location, e.g. http://www.yourdomain.com/100jancms/install.php<br>
      &#8226; Follow the onscreen instructions to complete installation of SOFTWARE 
      PRODUCT. You will need information to connect to your database. Contact 
      your hosting administrator for database connection information. </td>
  </tr>
</table>
<br>
<br>
<span class="maintextplavi2"><strong><a name="install_step1" id="install_step1"></a></strong></span><span class="titletext0">Installation: 
</span><span class="titletext0blue">Step 1</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0" class="maintext">
  <tr> 
    <td>In this step you need to read End User License Agreement, and to agree 
      to it before you proceed.</td>
  </tr>
</table>
<br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext"> <span class="maintextplavi21">Before 
      install</span><br>
      &#8226; Comply with the <strong>'Before install'</strong> instructions.<br>
      <br>
      <span class="maintextplavi21">End User License Agreement</span><br>
      &#8226; Read <strong>End User License Agreement</strong> in full.<br>
      &#8226; If you agree check&nbsp;<span class="maintextplaviinvert"> I agree 
      to the End User License Agreement&nbsp;</span> checkbox and click <span class="maintextplaviinvert">&nbsp;Next&nbsp;</span> 
      button. You must agree to EULA in order to use the product.</td>
  </tr>
</table>
<br>
<br>
<span class="maintextplavi2"><strong><a name="install_step2" id="install_step2"></a></strong></span><span class="titletext0">Installation: 
</span><span class="titletext0blue">Step 2</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0" class="maintext">
  <tr> 
    <td>In this step you need to provide database connection data and set master 
      administrator data.</td>
  </tr>
</table>
<br>
<span class="maintextplavi21">Database configuration</span><br>
<br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Database 
      Server Hostname&nbsp;</span> Database server hostname.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Database 
      Table Prefix&nbsp;</span> Database Table Prefix, prefix that will be added 
      to each database table name.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Database 
      Name&nbsp;</span> Database name.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Database 
      Username&nbsp;</span> Database username.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Database 
      Password&nbsp;</span> Database password.</td>
  </tr>
</table>
<br>
<span class="maintextplavi21">General configuration</span><br>
<br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Application 
      URL&nbsp;</span> Application URL is a full URL to a folder on server where 
      application is installed.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Encoding 
      meta tag&nbsp;</span> Encoding meta tag, complete html declaration, uses 
      UTF-8 by default.</td>
  </tr>
</table>
<br>
<span class="maintextplavi21">Master Administrator configuration</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert"></span>Master 
      administrator is a master user account, it always holds administrator privilege 
      and can not be deleted.</td>
  </tr>
</table>
<br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Administrator 
      Full name&nbsp;</span> Administrator full name.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Administrator 
      Username&nbsp;</span> Administrator username.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Administrator 
      Password&nbsp;</span> Administrator password.</td>
  </tr>
</table>
<br>
<br>
<span class="maintextplavi2"><strong><a name="install_step3" id="install_step3"></a></strong></span><span class="titletext0">Installation: 
</span><span class="titletext0blue">Step 3</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0" class="maintext">
  <tr>
    <td>In this step application will try to install itself.</td>
  </tr>
</table>
<br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="top" class="maintext"> <span class="maintextplavi21">a) 
      Installation successful</span><br>
      If application was installed successfully:<br>
      <br>
      &#8226; Make sure to delete the following files from application folder 
      for security: <br>
      <br>
      <em> [files]:</em><br>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- http://www.yourdomain.com/100jancms/install.php<br>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- http://www.yourdomain.com/100jancms/install_2.php<br>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- http://www.yourdomain.com/100jancms/install_3.php<br>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- http://www.yourdomain.com/100jancms/install_eula.php 
      <p>&#8226; Make sure to apply read only attribut (CHMOD 644) to the following 
        files for security:<br>
        <br>
        <em> [files]:</em><br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- http://www.yourdomain.com/100jancms/config_connection.php<br>
        <br>
        &#8226; Now you can login in to application using master administrator 
        username and password, that you specified during installation.<br>
        <br>
        <span class="maintextplavi21">b) Installation unsuccessful</span><br>
        If application was NOT installed successfully:<br>
        <br>
        &#8226; Check Database Server Hostname, Database Name and Database Password 
        data, and try again. Contact your hosting administrator for database connection 
        information. <br>
      </p>
      </td>
  </tr>
</table>

<br>
<br>
<br>
<hr align="left" width="700" size="1" color="#969696" noshade>
<span class="maintextplavi2"><strong><a name="general" id="general"></a></strong></span><span class="maintextplavi2"><strong>General 
Use instructions</strong></span> <br>
<br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="top" class="maintext"> <span class="maintextplavi21">Product 
      specific instructions</span><br>
      <br>
      &#8226; Required fields are marked with asterix.<br>
      &#8226; Invalid fields are reported with alert box and fieldsmarked with 
      dashed border around field.<br>
      &#8226; <a href="#articles_items_htmlarea">htmlArea</a> is a html based 
      WYSIWYG editor. Accepts and edits html and plaintext to be inserted as a 
      article into database. <br>
      &#8226; Note that <a href="#articles_items_htmlarea">htmlArea</a>'s WYSIWYG 
      features and its ability to fully accept html, does not relieve you from 
      correct usage of html within itself that is not visible by its WYSIWYG features. 
      This applies to cases when importing html from external online resouces. 
      Some imported data may not be fisicly available to that code, such as external 
      javascript or cascading style sheet files. Also, always check if resource 
      is copyrighted.<br>
      &#8226; Images uploaded through Insert Internal Image dialog to be used 
      in <a href="#articles_items_htmlarea">htmlArea</a> is not associated with 
      article in database, it is only referenced, so deleting article does not 
      delete internal image. Manage internal images using Insert Internal Images 
      dialog.<br>
      &#8226; Articles are internaly sorted by: (1) Date/Time, (2) priority, (3) 
      internal ID.<br>
      &#8226; Except for WYSIWYG editor and comments text, use only alphanumeric 
      characters to enter any additional data within the application (A-Z, 0-9, 
      and underscore &quot;_&quot; instead of space character). This applies to 
      markers, categories, titles, ... etc.<br>
      &#8226; Quotes are not allowed in marker and category fields.<br>
      &#8226; Marker that is in use can not be deleted. You must first re-assign 
      all articles with that marker to a different marker, then that unused marker 
      can be deleted.<br>
      &#8226; Deleting article deletes all associated comments.<br>
      &#8226; When new marker is created, you must allow its use in user privileges 
      settings. <br>
      &#8226; Moving articles actualy just changes article's time stamp by adding 
      or substracting one second from target article time stamp, since articles 
      are sorted by Date/Time. <br>
      &#8226; User without privileges can not log in. Administrator must allow 
      access to at least one marker or comments section before user can log in.<br>
      &#8226; Always log out. Loging out saves current user settings.<br>
      <br>
      <span class="maintextplavi21">Additional useful information</span> <br>
      <br>
      &#8226; For maintaining MySQL database we suggest using the following tools:<br>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; phpMyAdmin (web 
      based)<br>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#8226; MySQL-Front (windows 
      application)</td>
  </tr>
</table>
<br>
<br>
<br>
<hr align="left" width="700" size="1" color="#969696" noshade>
<span class="maintextplavi2"><strong> <a name="articles"></a>Articles</strong></span><br>
<br>
<span class="maintextplavi2"><strong><a name="articles_items_add" id="articles_items_add"></a></strong></span><span class="titletext0">Articles: 
</span><span class="titletext0blue">Add new Article</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext"> &#8226; Adds new article.</td>
  </tr>
</table>
<br>
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Title&nbsp;</span> 
      Article title.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Marker&nbsp;</span> 
      Marker defines a unique place on website where article will be shown.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Status<strong>&nbsp;</strong></span> 
      <strong> Active:</strong> article will be shown on website. <strong>Suspended:</strong> 
      article will not be shown on website.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;Category&nbsp;</span> Category for article.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Image&nbsp;</span> 
      Image associated with article. Browse for an image to upload.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Alignment&nbsp;</span> 
      Left or Right Image alignment.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Alt.&nbsp;</span> 
      Alternate text for image associated with article.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Source&nbsp;</span> 
      Source of article text (e.g.<em> New York Times</em>).</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Location&nbsp;</span> 
      Location for article (e.g. <em>New York</em>).</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Keywords&nbsp;</span> 
      Comma separated words used to track related/similar articles (e.g. <em>Roses, 
      Garden, Flowershop</em>).</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Priority&nbsp;</span> 
      Priority criteria. By default, articles with priority set will be shown 
      before other articles regardless of Date/Time posted.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Flag&nbsp;</span> 
      Additional criteria property (e.g. the most important articles).</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Expire&nbsp;</span> 
      Number of days after article will be considered expired. Zero (0) means 
      never expire. Expired articles will not be shown on website, yet will remain 
      in database until you delete them.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Date/Time&nbsp; 
      (Day,Month,Year/Hour,Minute,Sec)&nbsp;</span> - Date/Time the article is 
      being posted. Use Update button to update to curernt Date/Time.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Update&nbsp;</span> 
      Updates article date and time stamp to curent.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Opening 
      Text&nbsp;</span> Opening text to be used as a short version of the article. 
      It uses <a href="#articles_items_htmlarea">htmlArea</a> to accept and edit 
      html.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Full 
      Text&nbsp;</span> Full text to be used as a full version of the article. 
      It uses <a href="#articles_items_htmlarea">htmlArea</a> to accept and edit 
      html.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Comments&nbsp;</span> 
      <strong>Allow comments:</strong> article can be commented. <strong>Only 
      by registered users:</strong> article can be commented only by registered 
      users. <strong>Comments must be approved:</strong> comments must be approved 
      by administrator before they shhow up on website.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Insert 
      article&nbsp;</span> Inserts article into database.</td>
  </tr>
</table>


<br>
<br>
<span class="maintextplavi2"><strong><a name="articles_items_htmlarea" id="articles_items_htmlarea"></a></strong></span><span class="maintextplavi2"><strong></strong></span><span class="titletext0">Articles: 
</span><span class="titletext0blue">htmlArea</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext"> &#8226; htmlArea is a html 
      based WYSIWYG editor. Accepts and edits html and plaintext to be inserted 
      as an article into database. <br> &#8226; Note that htmlArea's 
      WYSIWYG features and its ability to fully accept html, does not relieve 
      you from correct usage of html within itself that is not visible by its 
      WYSIWYG features. This applies to cases when importing html from external 
      online resouces. Some imported data may not be fisicly available to that 
      code, such as external javascript or cascading style sheet files. Also, 
      always check if resource is copyrighted. </td>
  </tr>
</table>
<br>
    
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/ed_format_bold.gif" width="18" height="18" align="absmiddle"> 
      <span class="maintextplaviinvert">&nbsp;Bold&nbsp;</span> Toggles the selected 
      text between Bold and Normal text. </td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/ed_format_italic.gif" width="18" height="18" align="absmiddle">&nbsp;<span class="maintextplaviinvert">&nbsp;Italic&nbsp;</span> 
      Toggles the selected text between Italicized and Normal text.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/ed_format_underline.gif" width="18" height="18" align="absmiddle">&nbsp;<span class="maintextplaviinvert">&nbsp;Underline&nbsp;</span> 
      Toggles the selected text between Underlined and Normal text.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/ed_align_left.gif" width="18" height="18" align="absmiddle">&nbsp;<span class="maintextplaviinvert">&nbsp;Justify 
      Left &nbsp;</span> Aligns the selected paragraph to the left.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/ed_align_center.gif" width="18" height="18" align="absmiddle">&nbsp;<span class="maintextplaviinvert">&nbsp;Justify 
      Center &nbsp;</span> Centers the selected content. </td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/ed_align_right.gif" width="18" height="18" align="absmiddle"> 
      <span class="maintextplaviinvert">&nbsp;Justify Right&nbsp;</span> Aligns 
      the selected paragraph to the right. </td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/ed_align_full.gif" width="18" height="18" align="absmiddle">&nbsp;<span class="maintextplaviinvert">&nbsp;Justify 
      Full &nbsp;</span> Aligns the selected paragraph fully justified. </td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/ed_align_none.gif" width="18" height="18" align="absmiddle"> 
      <span class="maintextplaviinvert">&nbsp;Remove Alignment&nbsp;</span> Removes 
      alignment in selected paragraph.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/ed_format_strike.gif" width="18" height="18" align="absmiddle"> 
      <span class="maintextplaviinvert">&nbsp;Striketrough&nbsp;</span> Toggles 
      the selected text between Strikethrough and Normal text.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/ed_format_sub.gif" width="18" height="18" align="absmiddle"> 
      <span class="maintextplaviinvert">&nbsp;Subscript&nbsp;</span> Toggles the 
      selected text between Subscript and Normal text.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/ed_format_sup.gif" width="18" height="18" align="absmiddle"> 
      <span class="maintextplaviinvert">&nbsp;Superscript&nbsp;</span> Toggles 
      the selected text between Superscript and Normal text. </td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/ed_list_num.gif" width="18" height="18" align="absmiddle"> 
      <span class="maintextplaviinvert">&nbsp;Number Ordered List&nbsp;</span> 
      Number ordered list.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/ed_list_bullet.gif" width="18" height="18" align="absmiddle"> 
      <span class="maintextplaviinvert">&nbsp;Bulleted List&nbsp;</span> Bulleted 
      list.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/ed_indent_less.gif" width="18" height="18" align="absmiddle"> 
      <span class="maintextplaviinvert">&nbsp;Decrease Indent&nbsp;</span> Unindents 
      the selected content.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/ed_indent_more.gif" width="18" height="18" align="absmiddle"> 
      <span class="maintextplaviinvert">&nbsp;Increase Indent&nbsp;</span> Indents 
      the selected content. </td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/ed_color_fg.gif" width="18" height="18" align="absmiddle"> 
      <span class="maintextplaviinvert">&nbsp;Font Color&nbsp;</span> Opens the 
      Color Picker Dialog allowing to change the foreground color of the selected 
      text.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/ed_color_bg.gif" width="18" height="18" align="absmiddle"> 
      <span class="maintextplaviinvert">&nbsp;Background Color&nbsp;</span> Opens 
      the Color Picker Dialog allowing to change the background color of the selected 
      text. </td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/insert_table.gif" width="18" height="18" align="absmiddle"> 
      <span class="maintextplaviinvert">&nbsp;Insert Table&nbsp;</span> Opens 
      the Insert Table Dialog allowing to insert a table into the editor at the 
      selected point. </td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/ed_hr.gif" width="18" height="18" align="absmiddle"> 
      <span class="maintextplaviinvert">&nbsp;Horizontal Rule&nbsp;</span> Inserts 
      Horizontal Rule. </td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/ed_link.gif" width="18" height="18" align="absmiddle"> 
      <span class="maintextplaviinvert">&nbsp;Hyperlink In Text &amp; Images&nbsp;</span> 
      Opens the Hyperlink Editor Dialog to allow a hyperlink to be created in 
      the selected content or edit the selected one. </td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/ed_unlink.gif" width="18" height="18" align="absmiddle"> 
      <span class="maintextplaviinvert">&nbsp;Remove Hyperlink&nbsp;</span> Removes 
      hyperlink. </td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/ed_anchor.gif" width="18" height="18" align="absmiddle"> 
      <span class="maintextplaviinvert">&nbsp;Anchor&nbsp;</span> Inserts a named 
      anchor.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/ed_image_100jan.gif" width="18" height="18" align="absmiddle"> 
      <span class="maintextplaviinvert">&nbsp;Insert Internal image&nbsp;</span> 
      Opens the Insert Internal Image Dialog, allowing to Browse available images 
      to be inserted into the editor at the selected position, preview existing 
      images and upload new images. </td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/ed_image.gif" width="18" height="18" align="absmiddle"> 
      <span class="maintextplaviinvert">&nbsp;Insert External Image&nbsp;</span> 
      Opens the Insert External Image Dialog, allowing images to be inserted by 
      linking its URL.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/ed_cut.gif" width="18" height="18" align="absmiddle"> 
      <span class="maintextplaviinvert">&nbsp;Cut&nbsp;</span> Cut contents.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/ed_copy.gif" width="18" height="18" align="absmiddle"> 
      <span class="maintextplaviinvert">&nbsp;Copy&nbsp;</span> Copy contents.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/ed_paste.gif" width="18" height="18" align="absmiddle"> 
      <span class="maintextplaviinvert">&nbsp;Paste&nbsp;</span> Paste contents.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/ed_print.gif" width="18" height="18" align="absmiddle"> 
      <span class="maintextplaviinvert">&nbsp;Print&nbsp;</span> Prints contents 
      of Editor to default printer.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/fullscreen_maximize.gif" width="18" height="18" align="absmiddle"> 
      <span class="maintextplaviinvert">&nbsp;Fullscreen Editor&nbsp;</span> Switches 
      from Normal Editor to Full Screen Mode. </td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/fullscreen_minimize.gif" width="18" height="18" align="absmiddle"> 
      <span class="maintextplaviinvert">&nbsp;Minimize Editor&nbsp;</span> Switches 
      from Full Screen Mode to Normal Editor.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext"><img src="htmlarea/images/ed_html.gif" width="18" height="18" align="absmiddle"> 
      <span class="maintextplaviinvert">&nbsp;View HTML Source&nbsp;</span> Toggles 
      the editor between HTML source and WYSIWYG modes.</td>
  </tr>
</table>
<br>
<br>
<span class="maintextplavi2"><strong><a name="articles_items_search" id="articles_items_search"></a></strong></span><span class="titletext0">Articles: 
View/Edit Articles:</span> <span class="titletext0blue">Search Articles</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext"> &#8226; Searches articles.</td>
  </tr>
</table>
<br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Title&nbsp;</span> 
      Search by articles title.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Marker&nbsp;</span> 
      Search by articles marker.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Category&nbsp;</span> 
      Search by articles category.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Containing 
      text&nbsp;</span> Full text search over articles 'opening text', 'full text' 
      and 'title' database entries.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Date&nbsp;</span> 
      Search by articles date.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Expired 
      only &nbsp;</span> Search only expired articles.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;In 
      the past&nbsp;</span> Search by specified number of days ago, the article 
      was posted. 'In the past' date search has priority over 'From-To' date search. 
    </td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;From-To&nbsp;</span> 
      Search by specified time range, specified by From-To Day/Month/Year.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Show 
      <em>[number]</em> results per page&nbsp;</span> Limits number of results 
      per page to display.</td>
  </tr>
  <tr>
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;Search articles&nbsp;</span> Executes search.</td>
  </tr>
</table>
<br>
<br>
<span class="maintextplavi2"><strong><a name="articles_items_list" id="articles_items_list"></a></strong></span><span class="titletext0">Articles: 
View/Edit Articles:</span><span class="titletext0blue"> Article listing</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext"> &#8226; Lists articles.</td>
  </tr>
</table>
<br>
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Check 
      All &nbsp;</span> Selects all items.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Uncheck 
      All &nbsp;</span> Unselects all items.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Source&nbsp;</span> 
      Selects source article to move.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Target&nbsp;</span> 
      Selects target article where moving.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Cancel&nbsp;</span> 
      Cancels article moving.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Delete 
      article(s)&nbsp;</span> Deletes articles.</td>
  </tr>
  <tr>
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Reassign 
      marker&nbsp;</span> Reassigns marker.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Back&nbsp;</span> 
      Cancels listing.</td>
  </tr>
</table>
<br>
<br>
<span class="maintextplavi2"><strong><a name="articles_items_edit" id="articles_items_edit"></a></strong></span><span class="titletext0">Articles: 
View/Edit Articles:</span><span class="titletext0blue"> Edit Article</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext"> &#8226; Edits article.</td>
  </tr>
</table>
<br>
    
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Title&nbsp;</span> 
      Article title.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Marker&nbsp;</span> 
      Marker defines a unique place on website where article will be shown.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Status<strong>&nbsp;</strong></span> 
      <strong> Active:</strong> article will be shown on website. <strong>Suspended:</strong> 
      article will not be shown on website. <strong>Expired</strong>: article 
      will not be shown on website.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;Category&nbsp;</span> Category for article.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Image&nbsp;</span> 
      Image associated with article. Browse for an image to upload.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Alignment&nbsp;</span> 
      Left or Right Image alignment.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Alt.&nbsp;</span> 
      Alternate text for image associated with article.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;View 
      Image&nbsp;</span> Views image in a new window.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Delete 
      Image&nbsp;</span> Deletes articles associated image.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Source&nbsp;</span> 
      Source of article text (e.g.<em> New York Times</em>).</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Location&nbsp;</span> 
      Location for article (e.g. <em>New York</em>).</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Keywords&nbsp;</span> 
      Comma separated words used to track related/similar articles (e.g. <em>Roses, 
      Garden, Flowershop</em>) Note: do not leave comma at the end! (e.g. wrong 
      usage: <em>Roses, Garden, Flowershop<strong><font color="#000000">,</font></strong></em> 
      ).</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Priority&nbsp;</span> 
      Priority criteria. By default, articles with priority set will be shown 
      before other articles regardless of Date/Time posted.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Flag&nbsp;</span> 
      Additional criteria property (e.g. the most important articles).</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Expire&nbsp;</span> 
      Number of days after article will be considered expired. Zero (0) means 
      never expire. Expired articles will not be shown on website, yet will remain 
      in database until you delete them.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Date/Time&nbsp; 
      (Day,Month,Year/Hour,Minute)&nbsp;</span> - Date/Time the article is being 
      posted. Use Update button to update to curernt Date/Time.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Update&nbsp;</span> 
      Updates article date and time stamp to curent.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Opening 
      Text&nbsp;</span> Opening text to be used as a short version of the article. 
      It uses <a href="#articles_items_htmlarea">htmlArea</a> to except and edit 
      html.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Full 
      Text&nbsp;</span> Full text to be used as a full version of the article. 
      It uses <a href="#articles_items_htmlarea">htmlArea</a> to except and edit 
      html.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Comments&nbsp;</span> 
      <strong>Allow comments:</strong> article can be commented. <strong>Only 
      by registered users:</strong> article can be commented only by registered 
      users. <strong>Comments must be approved:</strong> comments must be approved 
      by administrator before they shhow up on website.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Added 
      by&nbsp;</span> Username of user who originaly added the article.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Eddited 
      by&nbsp;</span> Username of user who later edited the article.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Visits&nbsp;</span> 
      Number of visits article had at full text page.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Rate&nbsp;</span> 
      Article rate, rated by visitors.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Save 
      article&nbsp;</span> Saves article into database.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Delete&nbsp;</span> 
      Deletes article.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Cancel&nbsp;</span> 
      Cancels editing article.</td>
  </tr>
</table>
<br>
<br>
<span class="maintextplavi2"><strong><a name="articles_marker_add" id="articles_marker_add"></a></strong></span><span class="titletext0">Articles: 
</span><span class="titletext0blue">Add new Marker</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext"> &#8226; Adds new marker.</td>
  </tr>
</table>
<br>
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Marker&nbsp;</span> 
      Marker defines a unique place on website where article will be shown.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Comment&nbsp;</span> 
      Comment related to marker.</td>
  </tr>
  <tr>
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Add 
      marker&nbsp;</span> Adds marker.</td>
  </tr>
</table>
<br>
<br>
<span class="maintextplavi2"><strong><a name="articles_marker_search" id="articles_marker_search"></a></strong></span><span class="titletext0">Articles: 
View/Edit Markers:</span><span class="titletext0blue"> Search Markers</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext"> &#8226; Searches markers.</td>
  </tr>
</table>
<br>
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Marker&nbsp;</span> 
      Searches by marker.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Show 
      <em>[number]</em> results per page&nbsp;</span> Limits number of results 
      per page to display.</td>
  </tr>
  <tr>
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;Search markers&nbsp;</span> Executes search.</td>
  </tr>
</table>
<br>
<br>
<span class="maintextplavi2"><strong><a name="articles_marker_list" id="articles_marker_list"></a></strong></span><span class="titletext0">Articles: 
View/Edit Markers:</span><span class="titletext0blue"> Markers listing</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext"> &#8226; Lists markers.</td>
  </tr>
</table>
<br>
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Check 
      All &nbsp;</span> Selects all items.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Uncheck 
      All &nbsp;</span> Unselects all items.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Delete 
      marker(s) &nbsp;</span> Deletes markers.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Back&nbsp;</span> 
      Cancels listing.</td>
  </tr>
</table>
<br>
<br>
<span class="maintextplavi2"><strong><a name="articles_marker_edit" id="articles_marker_edit"></a></strong></span><span class="titletext0">Articles: 
View/Edit Markers:</span><span class="titletext0blue"> Edit Marker</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext"> &#8226; Edits marker.</td>
  </tr>
</table>
<br>
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Marker&nbsp;</span> 
      Marker defines a unique place on website where article will be shown.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Comment&nbsp;</span> 
      Comment related to marker.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Save 
      marker&nbsp;</span> Saves marker.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Delete 
      marker&nbsp;</span> Deletes marker.</td>
  </tr>
  <tr>
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Cancel&nbsp;</span> 
      Cancels editing marker.</td>
  </tr>
</table>
<br>
<br>
<span class="maintextplavi2"><strong><a name="articles_cat_add" id="articles_cat_add"></a></strong></span><span class="titletext0">Articles: 
</span><span class="titletext0blue">Add new Category</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext"> &#8226; Adds new category.</td>
  </tr>
</table>
<br>
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;Category&nbsp;</span> Category for article.</td>
  </tr>
</table>
<br>
<br>
<span class="maintextplavi2"><strong><a name="articles_cat_search" id="articles_cat_search"></a></strong></span><span class="titletext0">Articles: 
View/Edit Categories:</span><span class="titletext0blue"> Search Categories</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext"> &#8226; Searches categories.</td>
  </tr>
</table>
<br>
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;Category&nbsp;</span> Category for article.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Show 
      <em>[number]</em> results per page&nbsp;</span> Limits number of results 
      per page to display.</td>
  </tr>
  <tr>
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;Search categories&nbsp;</span> Executes search.</td>
  </tr>
</table>
<br>
<br>
<span class="maintextplavi2"><strong><a name="articles_cat_list" id="articles_cat_list"></a></strong></span><span class="titletext0">Articles: 
View/Edit Categories:</span><span class="titletext0blue"> Categories listing</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext"> &#8226; Lists categories.</td>
  </tr>
</table>
<br>
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Check 
      All &nbsp;</span> Selects all items.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Uncheck 
      All &nbsp;</span> Unselects all items.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Delete 
      categories&nbsp;</span> Deletes categories.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Back&nbsp;</span> 
      Cancels listing.</td>
  </tr>
</table>
<br>
<br>
<span class="maintextplavi2"><strong><a name="articles_cat_edit" id="articles_cat_edit"></a></strong></span><span class="titletext0">Articles: 
View/Edit Categories:</span><span class="titletext0blue"> Edit Category</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext"> &#8226; Edits category.</td>
  </tr>
</table>
<br>
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;Category&nbsp;</span> Category for article.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Save 
      category&nbsp;</span> Saves category.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Delete 
      category&nbsp;</span> Deletes category.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Cancel&nbsp;</span> 
      Cancels editing category.</td>
  </tr>
</table>
<br>
<br>
<br>
<hr align="left" width="700" size="1" color="#969696" noshade>
<span class="maintextplavi2"><strong><a name="comments" id="comments"></a>Comments</strong></span><br>
<br>
<span class="maintextplavi2"><strong><a name="comments_search" id="comments_search"></a></strong></span><span class="titletext0">Comments: 
View/Edit Comments: </span><span class="titletext0blue">Search Comments</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext"> &#8226; Searches comments.</td>
  </tr>
</table>
<br>
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;Approved&nbsp;</span> Searches approved comments.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;For approval&nbsp;</span> Searches comments that are not yet approved.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;Comment text&nbsp;</span> Search by comment text.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Marker&nbsp;</span> 
      Searches by marker.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Show 
      <em>[number]</em> results per page&nbsp;</span> Limits number of results 
      per page to display.</td>
  </tr>
  <tr>
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;Search comments&nbsp;</span> Executes search.</td>
  </tr>
</table>
<br>
<br>
<span class="maintextplavi2"><strong><a name="comments_list" id="comments_list"></a></strong></span><span class="titletext0">Comments: 
View/Edit Comments: </span><span class="titletext0blue">Comments listing</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext"> &#8226; Lists comments.</td>
  </tr>
</table>
<br>
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Check 
      All &nbsp;</span> Selects all items.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Uncheck 
      All &nbsp;</span> Unselects all items.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Edit 
      this comment&nbsp;</span> Edits comment.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Click 
      to approve this comment&nbsp;</span> Approves comment.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Approve 
      comment(s)&nbsp;</span> Approves comments.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Delete 
      comment(s) &nbsp;</span> Deletes comments.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Back&nbsp;</span> 
      Cancels listing.</td>
  </tr>
</table>
<br>
<br>
<span class="maintextplavi2"><strong><a name="comments_edit" id="comments_edit"></a></strong></span><span class="titletext0">Comments: 
View/Edit Comments: </span><span class="titletext0blue">Edit Comment</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext"> &#8226; Edits comments.</td>
  </tr>
</table>
<br>
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;Posted by&nbsp;</span> Comment's author username.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Comment&nbsp;</span> 
      Comment's text.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Date/Time&nbsp;</span> 
      Date/Time the comment has been posted.</td>
  </tr>
  <tr>
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Save 
      comment&nbsp;</span> Saves comment.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Approve 
      comment &nbsp;</span> Approves comment.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Delete 
      comment &nbsp;</span> Deletes comment.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Cancel&nbsp;</span> 
      Cancels editing comment.</td>
  </tr>
</table>
<br>
<br>
<br>
<hr align="left" width="700" size="1" color="#969696" noshade>
<span class="maintextplavi2"><strong><a name="visitors" id="visitors"></a></strong></span><span class="maintextplavi2"><strong>Visitors</strong></span><br>
<br>
<span class="maintextplavi2"><strong><a name="visitors_search" id="visitors_search"></a></strong></span><span class="titletext0">Visitors: 
View/Edit Visitors: </span><span class="titletext0blue">Search Visitors</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext"> &#8226; Searches visitors.</td>
  </tr>
</table>
<br>
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;Username&nbsp;</span> Searches by username.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;Full name&nbsp;</span> Searches by full name.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;Old visitors, Last logged in before <em>[months]</em> or more.&nbsp;</span> 
      Search all visitors logged before selected number of months.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Show 
      <em>[number]</em> results per page&nbsp;</span> Limits number of results 
      per page to display.</td>
  </tr>
  <tr>
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;Search visitors&nbsp;</span> Executes search.</td>
  </tr>
</table>
<br>
<br>
<span class="maintextplavi2"><strong><a name="visitors_list" id="visitors_list"></a></strong></span><span class="titletext0">Visitors: 
View/Edit Visitors:</span><span class="titletext0blue"> Visitors listing</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext"> &#8226; Lists visitors.</td>
  </tr>
</table>
<br>
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Check 
      All &nbsp;</span> Selects all items.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Uncheck 
      All &nbsp;</span> Unselects all items.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Edit 
      this comment&nbsp;</span> Edits comment.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Click 
      to approve this comment&nbsp;</span> Approves comment.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Approve 
      comment(s)&nbsp;</span> Approves comments.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Delete 
      comment(s) &nbsp;</span> Deletes comments.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Back&nbsp;</span> 
      Cancels listing.</td>
  </tr>
</table>
<br>
<br>
<br>
<hr align="left" width="700" size="1" color="#969696" noshade>
<span class="maintextplavi2"><strong><a name="users" id="users"></a></strong></span><span class="maintextplavi2"><strong>Users</strong></span><br>
<br>
<span class="maintextplavi2"><strong><a name="users_add" id="users_add"></a></strong></span><span class="titletext0">Users: 
</span><span class="titletext0blue">Add new User</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext"> &#8226; Adds new user.</td>
  </tr>
</table>
<br>
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;Full name&nbsp;</span> User full name.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;Username&nbsp;</span> User username.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;Password&nbsp;</span> User password.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;E-mail&nbsp;</span> User e-mail.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;Privileges&nbsp;</span> User privileges.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Check 
      All &nbsp;</span> Selects all items.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Uncheck 
      All &nbsp;</span> Unselects all items.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Add 
      user &nbsp;</span> Adds user.</td>
  </tr>
</table>
<br>
<br>
<span class="maintextplavi2"><strong><a name="users_search" id="users_search"></a></strong></span><span class="titletext0">Users: 
View/Edit Users: </span><span class="titletext0blue">Search Users</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext"> &#8226; Searches users.</td>
  </tr>
</table>
<br>
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;Full name&nbsp;</span> Searches by full name.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;Username&nbsp;</span> Searches by username.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Show 
      <em>[number]</em> results per page&nbsp;</span> Limits number of results 
      per page to display.</td>
  </tr>
  <tr>
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;Search users&nbsp;</span> Executes search.</td>
  </tr>
</table>
<br>
<br>
<span class="maintextplavi2"><strong><a name="users_list" id="users_list"></a></strong></span><span class="titletext0">Users: 
View/Edit Users: </span><span class="titletext0blue">Users listing</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext"> &#8226; Lists users.</td>
  </tr>
</table>
<br>
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Check 
      All &nbsp;</span> Selects all items.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Uncheck 
      All &nbsp;</span> Unselects all items.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Delete 
      user(s) &nbsp;</span> Deletes users.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Back&nbsp;</span> 
      Cancels listing.</td>
  </tr>
</table>
<br>
<br>
<span class="maintextplavi2"><strong><a name="users_edit" id="users_edit"></a></strong></span><span class="titletext0">Users: 
View/Edit Users: </span><span class="titletext0blue">Edit User</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext"> &#8226; Edits user.</td>
  </tr>
</table>
<br>
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;Full name&nbsp;</span> User full name.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;Username&nbsp;</span> User username.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;Password&nbsp;</span> User password.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;E-mail&nbsp;</span> User e-mail.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert"> 
      &nbsp;Privileges&nbsp;</span> User privileges.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Check 
      All &nbsp;</span> Selects all items.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Uncheck 
      All &nbsp;</span> Unselects all items.</td>
  </tr>
  <tr>
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Delete 
      user&nbsp;</span> Deletes user.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Cancel&nbsp;</span> 
      Cancels user editing.</td>
  </tr>
  <tr> 
    <td height="18" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Save 
      user &nbsp;</span> Saves user.</td>
  </tr>
</table>
<br>
<br>
<br>
<hr align="left" width="700" size="1" color="#969696" noshade>
<span class="maintextplavi2"><strong><a name="help" id="help"></a></strong></span><span class="maintextplavi2"><strong>Help</strong></span><br>
<br>
<span class="maintextplavi2"><strong><a name="help_manual" id="help_manual"></a></strong></span><span class="titletext0">Help:</span><span class="titletext0blue"> 
User manual</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext"> &#8226; This manual.</td>
  </tr>
</table>
<br>
<br>
<span class="maintextplavi2"><strong><a name="help_eula" id="help_eula"></a></strong></span><span class="titletext0">Help:</span><span class="titletext0blue"> 
License Agreement</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext"> &#8226; End User License 
      Agreement.</td>
  </tr>
</table>
<br>
<br>
<span class="maintextplavi2"><strong><a name="help_about" id="help_about"></a></strong></span><span class="titletext0">Help:</span><span class="titletext0blue"> 
About</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext"> &#8226; Product version 
      and copyright information.</td>
  </tr>
</table>
<br>
<br>
<br>
<hr align="left" width="700" size="1" color="#969696" noshade>
<span class="maintextplavi2"><strong><a name="admin" id="admin"></a></strong></span><span class="maintextplavi2"><strong>Admin</strong></span><br>
<br>
<span class="maintextplavi2"><strong><a name="admin_config" id="admin_config"></a></strong></span><span class="titletext0">Admin:</span><span class="titletext0blue"> 
View/Edit Configuration</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext"> &#8226; Maintains application 
      core settings.</td>
  </tr>
</table>
<br>
<span class="maintextplavi21">General configuration</span><br>
<br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Application 
      URL&nbsp;</span> Application URL is a full URL to a folder on server where 
      application is installed.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Encoding 
      meta tag&nbsp;</span> Encoding meta tag, complete html declaration, uses 
      UTF-8 by default.</td>
  </tr>
</table>
<br>
<span class="maintextplavi21">Articles configuration</span><br>
<br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Add 
      new article: Start WYSIWYG editor in html mode (Opening text)&nbsp;</span> 
      If checked will start WYSIWYG editor in html mode.</td>
  </tr>
  <tr> 
    <td height="18" align="left" valign="middle" class="maintext">&#8226; <span class="maintextplaviinvert">&nbsp;Add 
      new article: </span><span class="maintextplaviinvert">Start WYSIWYG editor 
      in html mode (Full Text)&nbsp;</span> If checked will start WYSIWYG editor 
      in html mode.</td>
  </tr>
  <tr>
    <td height="18" align="left" valign="middle" class="maintext"><table width="700" border="0" cellpadding="0" cellspacing="0">
        <tr> 
          <td height="18" align="left" valign="middle" class="maintext">&#8226; 
            <span class="maintextplaviinvert">&nbsp;Edit article: </span><span class="maintextplaviinvert">Start 
            WYSIWYG editor in html mode (Opening text)&nbsp;</span> If checked 
            will start WYSIWYG editor in html mode.</td>
        </tr>
        <tr> 
          <td height="18" align="left" valign="middle" class="maintext">&#8226; 
            <span class="maintextplaviinvert">&nbsp;Edit article: </span><span class="maintextplaviinvert"></span><span class="maintextplaviinvert">Start 
            WYSIWYG editor in html mode (Full Text)&nbsp;</span> If checked will 
            start WYSIWYG editor in html mode.</td>
        </tr>
        <tr> 
          <td height="18" align="left" valign="middle" class="maintext">&#8226; 
            <span class="maintextplaviinvert">&nbsp;Article image max upload filesize 
            <em>[number]</em> kb&nbsp;</span> Sets the maximum upload image size 
            in kb.</td>
        </tr>
        <tr> 
          <td height="18" align="left" valign="middle" class="maintext">&#8226; 
            <span class="maintextplaviinvert">&nbsp;Internal image preview&nbsp;</span> 
            <strong>Full:</strong> Image preview in Insert Internal Image dialog 
            will be shown. <strong>No:</strong> Image preview in Insert Internal 
            Image dialog will NOT be shown.</td>
        </tr>
        <tr>
          <td height="18" align="left" valign="middle" class="maintext">&#8226; 
            <span class="maintextplaviinvert">&nbsp;Save config&nbsp;</span> Saves 
            configuration. </td>
        </tr>
      </table></td>
  </tr>
</table>
<br>
<br>
<span class="maintextplavi2"><strong><a name="admin_logout" id="admin_logout"></a></strong></span><span class="titletext0">Admin:</span><span class="titletext0blue"> 
Logout</span><br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="middle" class="maintext"> &#8226; Logs out from application.</td>
  </tr>
</table>
<br>
<br>
<br>
<hr align="left" width="700" size="1" color="#969696" noshade>
<span class="maintextplavi2"><strong><a name="symbols" id="symbols"></a></strong></span><span class="maintextplavi2"><strong>Symbols</strong></span><br>
<br>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="left" valign="top" class="maintext"><p> &#8226; <img src="images/app/asterix.jpg" width="9" height="8" align="absmiddle"> 
        Required field.<br>
        &#8226; <img src="images/app/e.jpg" width="101" height="20" align="absmiddle"> 
        Invalid field.<br>
        &#8226; <img src="images/app/active.gif" width="20" height="20" align="absmiddle">Item 
        is checked. <br>
        &#8226; <img src="images/app/archive.gif" width="20" height="20" align="absmiddle">Item 
        is not checked. <br>
        &#8226; <img src="images/app/i16.gif" width="16" height="16" align="absmiddle"> 
        Information.<br>
        &#8226; <img src="images/app/i.gif" width="20" height="20" align="absmiddle"> 
        Priority.<br>
        &#8226; <img src="images/app/a.gif" width="20" height="20" align="absmiddle"> 
        Administrator.<br>
        &#8226; <img src="images/app/m_notepad.gif" width="20" height="20" align="absmiddle">Edit 
        comment.<br>
        &#8226; <img src="images/app/plus.gif" width="9" height="9" align="absmiddle"> 
        Expands branch.<br>
        &#8226; <img src="images/app/minus.gif" width="9" height="9" align="absmiddle"> 
        Collapses branch. <br>
        &#8226; <img src="images/app/all_good.jpg" width="16" height="16" align="absmiddle"> 
        Action was successful.<br>
        &#8226; <img src="images/app/no_good.jpg" width="16" height="16" align="absmiddle"> 
        Action was unsuccessful.<br>
        &#8226; <img src="images/app/none.jpg" width="16" height="16" align="absmiddle"> 
        None found.<br>
        &#8226; <img src="images/app/printer32.jpg" width="33" height="33" align="absmiddle"> 
        Prints contents. </p></td>
  </tr>
</table>

	
<br>
<br>
<br>
<br>
<br>
<br>
	<br>
<hr align="left" width="700" size="1" color="#969696" noshade>
<span class="maintext">Copyright &copy; 2004 100jan Design Studio.</span><span class="maintext"> 
All Rights Reserved.</span><span class="maintext"><br>
Nullified by GTT '2004</span><br>
<br>
<br>
<br>
<br>
    

</body>
</html>
