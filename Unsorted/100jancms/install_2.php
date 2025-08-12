<html>
<head>
<title>100janCMS Articles Control: Installation</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="cms_style.css" rel="stylesheet" type="text/css">
<link REL = "SHORTCUT ICON" href="images/app/icon.ico">

<script type="text/javascript" src="checkform.js"></script>

<script language="JavaScript" type="text/JavaScript">
function step1_go()

{
	this.location="install.php";
}
function step3_go()

{
	this.location="install_3.php";
}
</script>
</head>

<body leftmargin="20" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0" class="maintext" scroll="auto">
<form action="install_3.php" method="post" enctype="multipart/form-data" name="step2" onSubmit="return checkform(step2);">
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
        
  <tr> 
          
    <td class="titletext0"><span class="maintext"><br>
        <img src="images/app/logo_login.jpg" width="128" height="44"><br>
      <br>
            </span>Installation:<span class="titletext0blue"> Step 2</span></td>
  </tr>
      
</table>

<br>
      <span class="titletext0blue">Database configuration:</span><br>
	  <br>
      <strong>Database Server Hostname:</strong><br>

<input name="db_host" type="text" class="formfields" id="db_host" value="localhost" size="30" maxlength="255" alt="anything" emsg="Database Server Hostname">
  <img src="images/app/asterix.jpg" width="9" height="8" align="absmiddle"><br>
  <strong>Database Table Prefix:</strong><br>
  <input name="db_table_prefix" type="text" class="formfields" id="db_table_prefix" value="100jancms_" size="30" maxlength="255" alt="anything" emsg="Database Table Prefix">
  <img src="images/app/asterix.jpg" width="9" height="8" align="absmiddle"><br>
  <strong>Database Name:</strong><br>
      
  <input name="db_database" type="text" class="formfields" id="db_database" size="30" maxlength="255" alt="anything" emsg="Database Name">
  <img src="images/app/asterix.jpg" width="9" height="8" align="absmiddle"><br>
      <strong>Database Username:</strong><br>
      
  <input name="db_username" type="text" class="formfields" id="db_username" size="30" maxlength="255" alt="anything" emsg="Database Username">
  <img src="images/app/asterix.jpg" width="9" height="8" align="absmiddle"><br>
	  <strong>Database Password:</strong><br>
      
  <input name="db_password" type="text" class="formfields" id="db_password" size="30" maxlength="255" alt="anything" emsg="Database Password">
  <img src="images/app/asterix.jpg" width="9" height="8" align="absmiddle"><br>
      <br>
      <br>
      
      <span class="titletext0blue">General configuration:</span><br>
	  <br>
  <strong>Application URL:</strong><br>
      
  <input name="app_url" type="text" class="formfields" id="app_url" value="http://www.yourdomain.com/100jancms/" size="75" maxlength="255" alt="anything" emsg="Application URL">
  <img src="images/app/asterix.jpg" width="9" height="8" align="absmiddle"><br>
      <strong>Encoding meta tag:</strong><br>
      
<input name="encoding" type="text" class="formfields" id="encoding" value='<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' size="75" maxlength="255" alt="anything" emsg="Encoding meta tag" title="Restriction:
- use double quotes">
  <img src="images/app/asterix.jpg" width="9" height="8" align="absmiddle"><br>

<br>
<br>
<span class="titletext0blue">Master Administrator configuration:</span><br>
<br>
<strong> Administrator Full name:</strong><br>
      
<input name="admin_fname" type="text" class="formfields" id="admin_fname" size="30" maxlength="255" alt="anything" emsg="Administrator Full name">
  <img src="images/app/asterix.jpg" width="9" height="8" align="absmiddle"><br>
<strong>Administrator Username:</strong><br>
      
<input name="admin_username" type="text" class="formfields" id="admin_username" size="30" maxlength="255" alt="anything" emsg="Administrator Username">
  <img src="images/app/asterix.jpg" width="9" height="8" align="absmiddle"><br>
<strong>Administrator Password:</strong><br>
      
<input name="admin_pass" type="text" class="formfields" id="admin_pass" size="30" maxlength="255" alt="length" min="6" emsg="Administrator Password">
  <img src="images/app/asterix.jpg" width="9" height="8" align="absmiddle"><br>
<br>
<br>
<br>
<br>
<input name="back" type="button" class="formfields2" id="back" style="width: 75px; height: 30px;" onClick="step1_go()" value="&lt;- Back" align="absmiddle">
  <input name="next" type="submit" class="formfields2" id="next" style="width: 75px; height: 30px;" value="Next -&gt;" align="absmiddle">
</form>
<br>
<br>
<br>
<br>
<br>
</body>
</html>
