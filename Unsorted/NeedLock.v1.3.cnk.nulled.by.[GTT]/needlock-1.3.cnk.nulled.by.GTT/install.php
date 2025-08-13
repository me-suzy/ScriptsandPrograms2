<?php

// Define usable dirs:

$DIRS = array();
$DIRS['TOP']       = './';
$DIRS['INC']       = './inc/';
$DIRS['DRIVERS']   = './inc/drivers/';
$DIRS['LIBS']      = './inc/libs/';
$DIRS['LANGS']     = './lang/';
$DIRS['MODULES']   = './modules/';
$DIRS['TPLS']      = './tpl/';
$DIRS['MAIL_TPLS'] = './tpl/mail/';


/*
 @ Get functions:
*/
require ($DIRS['INC'].'functions.class.php');
$STD = new functions;

/*
 @ Check for lock file;
*/
if ( file_exists('install.lock') ) {
   Error("Can't proceed with installation. Install script locked.");
}

/*
  @ Load INFO
*/
require ($DIRS['TOP'].'inf.php');

/*
 @ Check for write permissions for inf.php
*/
$needsecure_path_parts = pathinfo( $_SERVER["SCRIPT_FILENAME"] );
$real_path = $needsecure_path_parts['dirname'] . "/";

if ( !is_writable( $real_path ) ) {
   Error("Lock directory is not writeable. Please, chmod it to 0777.");
}

if ( !is_writable( $real_path . "inf.php" ) ) {
   Error("inf.php is not writeable. Please, chmod it to 0666.");
}

/*
  @ Run primary autoconfiguration
*/
auto_config();

/*
  @ Get incoming data
*/
$IN = $STD->parse_incoming();

 switch( $IN['act'] ) {
   case 'idx':
    print idx();
   break;
   case 'config':
    print config();
   break;
   case 'crdb':
    cr_database();
   break;
   case 'adm':
    if ( $IN['step'] == 'proceed' ) {
       cr_admin();
    } else {
       print admin();
    }
   break;
   case 'done':
    print done();
   break;
   default:
    print idx();
   break;
 }

 /*
   @ Proceed routines:
 */

 function auto_config() {
    global $STD,$IN,$INFO,$DIRS,$_SERVER;

    $needsecure_path_parts   = pathinfo( $_SERVER["SCRIPT_FILENAME"] );
	$NEW_INFO['BASE_PATH']   = $needsecure_path_parts['dirname'] . "/";
    $NEW_INFO['IMG_PATH']    = $needsecure_path_parts['dirname'] . "/i/";

    $needsecure_url_parts    = pathinfo( $_SERVER["REQUEST_URI"] );
    $NEW_INFO['BASE_URL']    = "http://" . $_SERVER["HTTP_HOST"] . $needsecure_url_parts['dirname'] . "/";
    $NEW_INFO['IMG_URL']     = "http://" . $_SERVER["HTTP_HOST"] . $needsecure_url_parts['dirname'] . "/i/";

    $NEW_INFO['HTA_TOP_DIR'] = $NEW_INFO['BASE_PATH'];

    @rename( "{$NEW_INFO['BASE_PATH']}inf.php", "{$INFO['BASE_PATH']}inf.php.bak" );
    @chmod( "{$NEW_INFO['BASE_PATH']}inf.php.bak", 0666 );

    saveConfig( $NEW_INFO );

 }

 function cr_database() {
   global $STD,$IN,$INFO,$DIRS;

   /*
     @ Update configuration
   */

   $NEW_INFO['SQL_DRIVER'] = $IN['SQL_DRIVER'];
   $NEW_INFO['SQL_HOST']   = $IN['SQL_HOST'];
   $NEW_INFO['SQL_PORT']   = $IN['SQL_PORT'];
   $NEW_INFO['SQL_USER']   = $IN['SQL_USER'];
   $NEW_INFO['SQL_PSWD']   = $IN['SQL_PSWD'];
   $NEW_INFO['SQL_NAME']   = $IN['SQL_NAME'];
   $NEW_INFO['SQL_PREFIX'] = $IN['SQL_PREFIX'];

   @rename( "{$NEW_INFO['BASE_PATH']}inf.php", "{$INFO['BASE_PATH']}inf.php.bak" );
   @chmod( "{$NEW_INFO['BASE_PATH']}inf.php.bak", 0666 );

   $INFO = saveConfig( $NEW_INFO );

   /*
     @ Create database structure
   */

   $QUERIES = load_sql();

   $INFO['SQL_DRIVER'] = !$INFO['SQL_DRIVER'] ? 'mysql' : $INFO['SQL_DRIVER'];
   $to_require = $DIRS['DRIVERS'] . $INFO['SQL_DRIVER'] . $INFO['PHP_EXT'];
   require ($to_require);

   // Create local database object
   $DB = new $INFO['SQL_DRIVER'];

   // Configure params for connection
   $DB->obj['sql_host']           =   $INFO['SQL_HOST'];
   $DB->obj['sql_port']           =   $INFO['SQL_PORT'];
   $DB->obj['sql_user']           =   $INFO['SQL_USER'];
   $DB->obj['sql_pass']           =   $INFO['SQL_PSWD'];
   $DB->obj['sql_database']       =   $INFO['SQL_NAME'];
   $DB->obj['sql_tbl_prefix']     =   $INFO['SQL_PREFIX'];

   $DB->connect();

   for ( $i=0; $i<count($QUERIES); $i++ ) {
      $DB->query($QUERIES[$i]);
   }

   $url = "install.php?act=adm";
   @flush();
   redirectPage($url,"Database created successfully.");


 }

 function cr_admin() {
   global $STD,$IN,$INFO,$DIRS;

   if ( empty( $IN['admin_login'] ) || empty( $IN['admin_email'] ) || empty( $IN['admin_passwd'] ) || empty( $IN['admin_repasswd'] ) ) {
	  Error("Not all required fields completed.");
   }

   if ( trim($IN['admin_passwd']) != trim($IN['admin_repasswd']) ) {
      Error("PassWord and PassWord repeat doesn't match. Try again");
   }

   $NEW_ADMIN['id'] = time();

   $NEW_ADMIN['email'] = $STD->clean_email( trim( $IN['admin_email'] ) );
   if ( !$NEW_ADMIN['email'] ) {
      Error("Invalid Email format. Try again.");
   }

   $NEW_ADMIN['name']     = trim( $IN['admin_login'] );
   $NEW_ADMIN['password'] = md5( trim( $IN['admin_passwd'] ) );

   /*
     @ parse to database
   */

   $INFO['SQL_DRIVER'] = !$INFO['SQL_DRIVER'] ? 'mysql' : $INFO['SQL_DRIVER'];
   $to_require = $DIRS['DRIVERS'] . $INFO['SQL_DRIVER'] . $INFO['PHP_EXT'];
   require ($to_require);

   // Create local database object
   $DB = new $INFO['SQL_DRIVER'];

   // Configure params for connection
   $DB->obj['sql_host']           =   $INFO['SQL_HOST'];
   $DB->obj['sql_port']           =   $INFO['SQL_PORT'];
   $DB->obj['sql_user']           =   $INFO['SQL_USER'];
   $DB->obj['sql_pass']           =   $INFO['SQL_PSWD'];
   $DB->obj['sql_database']       =   $INFO['SQL_NAME'];
   $DB->obj['sql_tbl_prefix']     =   $INFO['SQL_PREFIX'];

   $DB->connect();

   $DB->query("INSERT INTO ns_admins (id,name,password,email,level,lang) VALUES ('{$NEW_ADMIN['id']}','{$NEW_ADMIN['name']}','{$NEW_ADMIN['password']}','{$NEW_ADMIN['email']}','1','en')");

   $NEW_INFO['ADMIN_EMAIL'] = $NEW_ADMIN['email'];
   $NEW_INFO['EMAIL_OUT']   = $NEW_ADMIN['email'];
   $NEW_INFO['EMAIL_IN']    = $NEW_ADMIN['email'];

   @rename( "{$NEW_INFO['BASE_PATH']}inf.php", "{$INFO['BASE_PATH']}inf.php.bak" );
   @chmod( "{$NEW_INFO['BASE_PATH']}inf.php.bak", 0666 );

   $INFO = saveConfig( $NEW_INFO );

   $url = "install.php?act=done";
   @flush();
   redirectPage($url,"Administrator account added successfully.");

 }

 /*
   @ Output routines:
 */

 function idx() {
   global $STD,$IN,$INFO,$DIRS;

return <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">

<html>
<head>

      <title>Ready to install</title>

      <meta name="description" content="Secure system is an advances access manager">
      <meta name="rating" content="General">
      <meta name="robots" content="index,follow">
      <meta name="expires" content="never">
      <meta name="distribution" content="Global">
      <meta name="revisit-after" content="7 Days">
      <meta name="publisher" content="Publish">
      <meta name="copyright" content="Something">
      <meta http-Equiv="Content-Type" content="text/html; charset=koi8-r" />

      <style type=text/css media=all>@import url(style.css);</style>
      <link rel="stylesheet" type="text/css" href="style.css">

      <script language='JavaScript' type='text/javascript'>
        function contact_webmaster() {
                admin_email_one = 'friend';
                admin_email_two = 'domain.xxx';
                window.location = 'mailto:'+admin_email_one+'@'+admin_email_two+'?subject=Message installation';
        }
      </script>

</head>

<body>

<table width="100%" height="100%" cellSpacing="0" cellPadding="0" border="0" align="center"><tr>
 <td align="center" vAlign="middle">

    <table width="500" cellSpacing="3" cellPadding="0" border="0" align="center"><tr>
      <td height='20' style='border: 1px solid black; background-color: #aab9d6;' align='center' vAlign='middle'>
        <strong>Ready to install</strong>
      </td>
    </tr><tr>
      <td height="50" align="center" vAlign="middle" style="border: 1px solid #DDDDDD; background-color: #F0F0F0;">

        <br>
        <span style="font-weight: bold; color: red;">Now all are ready to install</span><br><br>
        <a class="news_lnk" href="install.php?act=config">proceed next >></a>
        <br><br>

      </td>
    </tr><tr>
      <td height='20' style='border: 1px solid black; background-color: #aab9d6;' align='center' vAlign='middle'>
        <strong>
	 <a class='my_error_lnk' href='javascript:history.back()'>go back</a>
	 &nbsp;&nbsp;|&nbsp;&nbsp;
	 <a class='my_error_lnk' href='javascript:contact_webmaster()'>tech support</a>
	 </strong>
      </td>
    </tr></table>

 </td>
</tr></table>

</body>
</html>
EOF;

 }

 function config() {
   global $STD,$IN,$INFO,$DIRS;

return <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">

<html>
<head>

      <title>SQL Configuration</title>

      <meta name="description" content="Secure system is an advances access manager">
      <meta name="rating" content="General">
      <meta name="robots" content="index,follow">
      <meta name="expires" content="never">
      <meta name="distribution" content="Global">
      <meta name="revisit-after" content="7 Days">
      <meta name="publisher" content="Publish">
      <meta name="copyright" content="Something">
      <meta http-Equiv="Content-Type" content="text/html; charset=koi8-r" />

      <style type=text/css media=all>@import url(style.css);</style>
      <link rel="stylesheet" type="text/css" href="style.css">

      <script language='JavaScript' type='text/javascript'>
        function contact_webmaster() {
                admin_email_one = 'friend';
                admin_email_two = 'domain.xxx';
                window.location = 'mailto:'+admin_email_one+'@'+admin_email_two+'?subject=Message about installation';
        }
      </script>

</head>

<body>

<table width="100%" height="100%" cellSpacing="0" cellPadding="0" border="0" align="center"><tr>
 <td align="center" vAlign="middle">

    <table width="500" cellSpacing="3" cellPadding="0" border="0" align="center"><tr>
      <td height="50" align="center" vAlign="middle" style="border: 1px solid #DDDDDD; background-color: #F0F0F0;">

        <form action="install.php" method="post">
        <input type="hidden" name="act" value="crdb">

      <table width="410" cellSpacing="3" cellPadding="3" border="0"><tr>
           <td colSpan="2" style="font-size: 12px; color: #104176; padding-left: 3px;" align="left" vAlign="middle">
          <strong>Database setup</strong>
	 </td>
        </tr><tr>
         <td colSpan="2" style="background-color: #79a0cd; border: 1px solid #000000; color: #000000; padding-left: 3px;" align="left" vAlign="middle">
          <strong>SQL Settings</strong>
	 </td>
        </tr><tr>
         <td width="180" style="background-color: #edf3ff; padding-left: 3px;" align="left" vAlign="middle">
          SQL Type
	 </td>
	 <td width="230" style="background-color: #edf3ff; padding-left: 3px;" align="left" vAlign="middle">
          <select size="1" style="width: 200px;" name="SQL_DRIVER">
           <option value="mysql" selected>MySQL</option>
           <option value="pgsql">PgSQL</option>
           <option value="mssql">MsSQL</option>
          </select>
	 </td>
        </tr><tr>
	 <td width="180" style="background-color: #edf3ff; padding-left: 3px;" align="left" vAlign="middle">
          SQL Host
	 </td>
	 <td width="230" style="background-color: #edf3ff; padding-left: 3px;" align="left" vAlign="middle">
          <input type="text" style="width: 200px;" name="SQL_HOST" value="localhost">
	 </td>
	</tr><tr>
         <td width="180" style="background-color: #edf3ff; padding-left: 3px;" align="left" vAlign="middle">
          SQL Port
	 </td>
	 <td width="230" style="background-color: #edf3ff; padding-left: 3px;" align="left" vAlign="middle">
          <input type="text" style="width: 200px;" name="SQL_PORT" value="">
	 </td>
	</tr><tr>
	 <td width="180" style="background-color: #edf3ff; padding-left: 3px;" align="left" vAlign="middle">
          SQL User
	 </td>
	 <td width="230" style="background-color: #edf3ff; padding-left: 3px;" align="left" vAlign="middle">
          <input type="text" style="width: 200px;" name="SQL_USER" value="root">
	 </td>
	</tr><tr>
	 <td width="180" style="background-color: #edf3ff; padding-left: 3px;" align="left" vAlign="middle">
          SQL Password
	 </td>
	 <td width="230" style="background-color: #edf3ff; padding-left: 3px;" align="left" vAlign="middle">
          <input type="password" style="width: 200px;" name="SQL_PSWD" value="">
	 </td>
	</tr><tr>
	 <td width="180" style="background-color: #edf3ff; padding-left: 3px;" align="left" vAlign="middle">
          Database Name
	 </td>
	 <td width="230" style="background-color: #edf3ff; padding-left: 3px;" align="left" vAlign="middle">
          <input type="text" style="width: 200px;" name="SQL_NAME" value="needlock">
	 </td>
        </tr><tr>
         <td width="180" style="background-color: #edf3ff; padding-left: 3px;" align="left" vAlign="middle">
          Tables prefix
	 </td>
	 <td width="230" style="background-color: #edf3ff; padding-left: 3px;" align="left" vAlign="middle">
          <input type="text" style="width: 200px;" name="SQL_PREFIX" value="ns_">
	 </td>
        </tr><tr>
         <td colSpan="2" style="background-color: #edf3ff;" align="center" vAlign="middle">
          <input type="submit" class="submit" value="proceed next >>" style="width: 400px;">
	 </td>
    </tr></table>

   </form>

      </td>
    </tr><tr>
      <td height='20' style='border: 1px solid black; background-color: #aab9d6;' align='center' vAlign='middle'>
        <strong>
	 <a class='my_error_lnk' href='javascript:history.back()'>go back</a>
	 &nbsp;&nbsp;|&nbsp;&nbsp;
	 <a class='my_error_lnk' href='javascript:contact_webmaster()'>tech support</a>
	 </strong>
      </td>
    </tr></table>

 </td>
</tr></table>

</body>
</html>
EOF;


 }

 function admin() {
   global $STD,$IN,$INFO,$DIRS;

return <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">

<html>
<head>

      <title>Installation complete</title>

      <meta name="description" content="Secure system is an advances access manager">
      <meta name="rating" content="General">
      <meta name="robots" content="index,follow">
      <meta name="expires" content="never">
      <meta name="distribution" content="Global">
      <meta name="revisit-after" content="7 Days">
      <meta name="publisher" content="Publish">
      <meta name="copyright" content="Something">
      <meta http-Equiv="Content-Type" content="text/html; charset=koi8-r" />

      <style type=text/css media=all>@import url(style.css);</style>
      <link rel="stylesheet" type="text/css" href="style.css">

      <script language='JavaScript' type='text/javascript'>
        function contact_webmaster() {
                admin_email_one = 'friend';
                admin_email_two = 'domain.xxx';
                window.location = 'mailto:'+admin_email_one+'@'+admin_email_two+'?subject=Message about installation';
        }
      </script>

</head>

<body>

<table width="100%" height="100%" cellSpacing="0" cellPadding="0" border="0" align="center"><tr>
 <td align="center" vAlign="middle">

    <table width="500" cellSpacing="3" cellPadding="0" border="0" align="center"><tr>
      <td height="50" align="center" vAlign="middle" style="border: 1px solid #DDDDDD; background-color: #F0F0F0;">

        <form action="install.php" method="post">
        <input type="hidden" name="act" value="adm">
        <input type="hidden" name="step" value="proceed">

      <table width="410" cellSpacing="3" cellPadding="3" border="0"><tr>
	   <td colSpan="2" style="font-size: 12px; color: #104176; padding-left: 3px;" align="left" vAlign="middle">
          <strong>Administrator Setup</strong>
	   </td>
      </tr><tr>
       <td colSpan="2" style="background-color: #79a0cd; border: 1px solid #000000; color: #000000; padding-left: 3px;" align="left" vAlign="middle">
          <strong>Create default administrator</strong>
	   </td>
     </tr><tr>
	  <td width="180" style="background-color: #edf3ff; padding-left: 3px;" align="left" vAlign="middle">
          UserName
	  </td>
	  <td width="230" style="background-color: #edf3ff; padding-left: 3px;" align="left" vAlign="middle">
          <input type="text" style="width: 200px;" name="admin_login">
	  </td>
     </tr><tr>
      <td width="180" style="background-color: #edf3ff; padding-left: 3px;" align="left" vAlign="middle">
          Email
	  </td>
	  <td width="230" style="background-color: #edf3ff; padding-left: 3px;" align="left" vAlign="middle">
          <input type="text" style="width: 200px;" name="admin_email">
	  </td>
     </tr><tr>
	  <td width="180" style="background-color: #edf3ff; padding-left: 3px;" align="left" vAlign="middle">
          PassWord
	  </td>
	  <td width="230" style="background-color: #edf3ff; padding-left: 3px;" align="left" vAlign="middle">
          <input type="password" style="width: 200px;" name="admin_passwd">
	  </td>
     </tr><tr>
	  <td width="180" style="background-color: #edf3ff; padding-left: 3px;" align="left" vAlign="middle">
          Repeat PassWord
	  </td>
	  <td width="230" style="background-color: #edf3ff; padding-left: 3px;" align="left" vAlign="middle">
          <input type="password" style="width: 200px;" name="admin_repasswd">
	  </td>
     </tr><tr>
      <td colSpan="2" style="background-color: #edf3ff;" align="center" vAlign="middle">
       <input type="submit" class="submit" value="create administrator" style="width: 400px;">
      </td>
     </tr></table>

      </td>
    </tr><tr>
      <td height='20' style='border: 1px solid black; background-color: #aab9d6;' align='center' vAlign='middle'>
        <strong>
	 <a class='my_error_lnk' href='javascript:history.back()'>go back</a>
	 &nbsp;&nbsp;|&nbsp;&nbsp;
	 <a class='my_error_lnk' href='javascript:contact_webmaster()'>tech support</a>
	 </strong>
      </td>
    </tr></table>

 </td>
</tr></table>

</body>
</html>
EOF;

 }

 function done() {
   global $STD,$IN,$INFO,$DIRS;

   $time_installed = time();
   $fh = fopen($INFO['BASE_PATH']."install.lock", "w" );
   fputs($fh,"Installed on {$time_installed}");
   fclose($fh);

return <<<EOF
<!-- Something -->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">

<html>
<head>

      <title>Installation complete</title>

      <meta name="description" content="Secure system is an advances access manager">
      <meta name="rating" content="General">
      <meta name="robots" content="index,follow">
      <meta name="expires" content="never">
      <meta name="distribution" content="Global">
      <meta name="revisit-after" content="7 Days">
      <meta name="publisher" content="Publish">
      <meta name="copyright" content="Something">
      <meta http-Equiv="Content-Type" content="text/html; charset=koi8-r" />

      <style type=text/css media=all>@import url(style.css);</style>
      <link rel="stylesheet" type="text/css" href="style.css">

      <script language='JavaScript' type='text/javascript'>
        function contact_webmaster() {
                admin_email_one = 'friend';
                admin_email_two = 'domain.xxx';
                window.location = 'mailto:'+admin_email_one+'@'+admin_email_two+'?subject=Message about installation';
        }
      </script>

</head>

<body>

<table width="100%" height="100%" cellSpacing="0" cellPadding="0" border="0" align="center"><tr>
 <td align="center" vAlign="middle">

    <table width="500" cellSpacing="3" cellPadding="0" border="0" align="center"><tr>
      <td height="50" align="center" vAlign="middle" style="border: 1px solid #DDDDDD; background-color: #F0F0F0;">

        <br>
        <span style="font-weight: bold; color: red;">Installation complete. Now you can log in to your Admin CP</span><br><br>
        <a class="news_link" href="{$INFO['BASE_URL']}admin.php">proceed to log in</a>
        <br><br>

      </td>
    </tr><tr>
      <td height='20' style='border: 1px solid black; background-color: #aab9d6;' align='center' vAlign='middle'>
        <strong>
	 <a class='my_error_lnk' href='javascript:history.back()'>go back</a>
	 &nbsp;&nbsp;|&nbsp;&nbsp;
	 <a class='my_error_lnk' href='javascript:contact_webmaster()'>tech support</a>
	 </strong>
      </td>
    </tr></table>

 </td>
</tr></table>

</body>
</html>
EOF;

 }

 /*
   @ Additional routines
 */

 function load_sql() {

  $SQL[] = "CREATE TABLE ns_admin_logs (
  id varchar(32) NOT NULL default '',
  ctime varchar(24) NOT NULL default '0000-00-00 00:00:00',
  admin_id varchar(32) NOT NULL default '',
  admin_name varchar(64) NOT NULL default '',
  admin_level int(3) NOT NULL default '0',
  admin_email varchar(64) NOT NULL default '',
  admin_action text NOT NULL,
  admin_ip varchar(12) NOT NULL default '',
  PRIMARY KEY  (id)
  )";

  $SQL[] = "CREATE TABLE ns_admin_sessions (
  id varchar(32) NOT NULL default '0',
  admin_id varchar(32) NOT NULL default '',
  admin_name varchar(64) NOT NULL default '',
  admin_level int(3) NOT NULL default '1',
  admin_ip varchar(16) NOT NULL default '',
  last_activity varchar(32) NOT NULL default '',
  PRIMARY KEY  (id)
  )";

  $SQL[] = "CREATE TABLE ns_admins (
  id varchar(12) NOT NULL default '',
  name varchar(40) NOT NULL default '',
  password varchar(100) NOT NULL default '',
  email varchar(64) NOT NULL default '',
  level int(3) default '5',
  lang varchar(8) NOT NULL default '',
  PRIMARY KEY  (id)
  )";

  $SQL[] = "CREATE TABLE ns_anounces (
  id varchar(12) NOT NULL default '',
  date date default NULL,
  header varchar(250) default NULL,
  body text,
  PRIMARY KEY  (id)
  )";

  $SQL[] = "CREATE TABLE ns_members (
  id varchar(12) NOT NULL default '',
  name varchar(40) NOT NULL default '',
  password varchar(100) NOT NULL default '',
  plain_password varchar(64) NOT NULL default '',
  regdate date default NULL,
  expire date default NULL,
  email varchar(40) default NULL,
  realname varchar(250) default NULL,
  lang varchar(10) NOT NULL default 'en',
  extra1 text,
  extra2 text,
  extra3 text,
  extra4 text,
  extra5 text,
  extra6 text,
  extra7 text,
  extra8 text,
  extra9 text,
  extra10 text,
  member_ip varchar(12) default NULL,
  member_browser varchar(64) default NULL,
  member_referrer varchar(250) default NULL,
  last_login datetime default NULL,
  count_visits int(8) default NULL,
  access_dirs text,
  suspended tinyint(1) default NULL,
  approved tinyint(1) default NULL,
  authcode varchar(24) default NULL,
  PRIMARY KEY  (id)
  )";

  $SQL[] = "CREATE TABLE ns_sessions (
  id varchar(31) NOT NULL default '0',
  member_id varchar(32) default NULL,
  member_name varchar(64) default NULL,
  member_ip varchar(16) default NULL,
  member_browser varchar(64) default NULL,
  last_activity varchar(32) NOT NULL default ''
  )";

  return $SQL;

 }

 function saveConfig($new_info) {
     global $STD,$IN,$INFO,$DIRS;

     $INF = '';

     $NEW_INFO_FH = $INFO;

    foreach ( $new_info as $new_info_key => $new_info_val ) {
            $NEW_INFO_FH[ $new_info_key ] = $new_info_val;
    }

    $FH = @fopen( $DIRS['TOP'] . 'inf.php' , "w" );


$INF .= "<?php

";

foreach ( $NEW_INFO_FH as $info_key => $info_val ) {
$INF .= "\$INFO[\"".$info_key."\"]\t\t=\t\"".$info_val."\";\n";
}

$INF .= "

?>
";

    @fputs( $FH, $INF );
    @fclose($FH);

    return $NEW_INFO_FH;

}

function Error($err_msg) {
     global $STD,$IN,$INFO,$DIRS;

print '
<!-- Something -->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">

<html>
<head>

      <title>Installation -> Error Page</title>

      <meta name="description" content="Secure system is an advances access manager">
      <meta name="rating" content="General">
      <meta name="robots" content="index,follow">
      <meta name="expires" content="never">
      <meta name="distribution" content="Global">
      <meta name="revisit-after" content="7 Days">
      <meta name="publisher" content="Publish">
      <meta name="copyright" content="Something">
      <meta http-Equiv="Content-Type" content="text/html; charset=koi8-r" />

      <style type=text/css media=all>@import url(style.css);</style>
      <link rel="stylesheet" type="text/css" href="style.css">

      <script language=\'JavaScript\' type=\'text/javascript\'>
        function contact_webmaster() {
                admin_email_one = \'friend\';
                admin_email_two = \'domain.xxx\';
                window.location = \'mailto:\'+admin_email_one+\'@\'+admin_email_two+\'?subject=Message about installation\';
        }
      </script>

</head>

<body>

<table width="100%" height="100%" cellSpacing="0" cellPadding="0" border="0" align="center"><tr>
 <td align="center" vAlign="middle">

    <table width="500" cellSpacing="3" cellPadding="0" border="0" align="center"><tr>
      <td height=\'20\' style=\'border: 1px solid black; background-color: #aab9d6;\' align=\'center\' vAlign=\'middle\'>
        <strong>An error has occured</strong>
      </td>
    </tr><tr>
      <td height="50" align="center" vAlign="middle" style="border: 1px solid #DDDDDD; background-color: #F0F0F0;">

        <br>
        <span style="font-weight: bold; color: red;">'.$err_msg.'</span>
        <br><br>

      </td>
    </tr><tr>
      <td height=\'20\' style=\'border: 1px solid black; background-color: #aab9d6;\' align=\'center\' vAlign=\'middle\'>
        <strong>
	 <a class=\'my_error_lnk\' href=\'javascript:history.back()\'>go back</a>
	 &nbsp;&nbsp;|&nbsp;&nbsp;
	 <a class=\'my_error_lnk\' href=\'javascript:contact_webmaster()\'>tech support</a>
	 </strong>
      </td>
    </tr></table>

 </td>
</tr></table>

</body>
</html>
';

     exit;

}

function redirectPage($url,$initial_msg) {
     global $STD,$IN,$INFO,$DIRS;

print '
<!-- Something -->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">

<html>
<head>

      <title>Installation -> Redirect Page</title>

      <meta name="description" content="Secure system is an advances access manager">
      <meta name="rating" content="General">
      <meta name="robots" content="index,follow">
      <meta name="expires" content="never">
      <meta name="distribution" content="Global">
      <meta name="revisit-after" content="7 Days">
      <meta name="publisher" content="Publish">
      <meta name="copyright" content="Something">
      <meta http-equiv="refresh" content="3; url='.$url.'">
      <meta http-Equiv="Content-Type" content="text/html; charset=koi8-r" />

      <style type=text/css media=all>@import url(style.css);</style>
      <link rel="stylesheet" type="text/css" href="style.css">

</head>

<body>

<table width="100%" height="100%" cellSpacing="0" cellPadding="0" border="0" align="center"><tr>
 <td align="center" vAlign="middle">

    <table width="500" cellSpacing="0" cellPadding="0" border="0" align="center"><tr>
      <td height="50" align="center" vAlign="middle" style="border: 1px solid #DDDDDD; background-color: #F0F0F0;">
       '.$initial_msg.'<br>
       <a href="'.$url.'">'.$redirect_link_text.'</a>
      </td>
    </tr></table>

 </td>
</tr></table>

</body>
</html>
';

     exit;

}


 /*
   @ The End! :)
 */

?>