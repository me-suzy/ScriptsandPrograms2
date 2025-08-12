<?

function errorlink() {
 extract($GLOBALS);

 $link = "error=true";
 
 if (isset($adminpasserror)) { $link = $link . "&adminpasserror=" . $adminpasserror; };
 
 if (isset($sqlip)) { $link = $link . "&sqlip=" . $sqlip; };
 if (isset($sqliperror)) { $link = $link . "&sqliperror=" . $sqliperror; };
 
 if (isset($sqluser)) { $link = $link . "&sqluser=" . $sqluser; };
 if (isset($sqlusererror)) { $link = $link . "&sqlusererror=" . $sqlusererror; };
 
 if (isset($sqlpasserror)) { $link = $link . "&sqlpasserror=" . $sqlpasserror; };
 
 if (isset($dbname)) { $link = $link . "&dbname=" . $dbname; };
 if (isset($dbnameerror)) { $link = $link . "&dbnameerror=" . $dbnameerror; };
 
 if (isset($tableprefix)) { $link = $link . "&tableprefix=" . $tableprefix; };
 if (isset($tableprefixerror)) { $link = $link . "&tableprefixerror=" . $tableprefixerror; };
 
 $link = $link . "&botconfuser=" . $botconfuser;
 return $link;
};
//end function errorout




//Variable Pickup and validation --------------------------------------------

//Administrator Password
if (!empty($_POST["adminpass"]) && !empty($_POST["adminpassconfirm"])) {
 if ($_POST["adminpass"] == $_POST["adminpassconfirm"]) {
  $adminpass = $_POST["adminpass"];
 } else {
  $adminpasserror = "mismatch";
 };
} else {
 $adminpasserror = "blank";
};

//Bot Confuser Enabled
if ($_POST["loginsecurity"] == "checked") {
 $botconfuser = "true";
} else {
 $botconfuser = "false";
};


//MySQL IP
if (!empty($_POST["sqlip"])) {
 $sqlip = $_POST["sqlip"];
} else {
 $sqliperror = "blank";
};

//MySQL Username
if (!empty($_POST["sqluser"])) {
 $sqluser = $_POST["sqluser"];
} else {
 $sqlusererror = "blank";
};

//MySQL Password
if (!empty($_POST["sqlpass"])) {
 $sqlpass = $_POST["sqlpass"];
} else {
 $sqlpasserror = "blank";
};

//MySQL DB name
if (!empty($_POST["dbname"])) {
 $dbname = $_POST["dbname"];
} else {
 $dbnameerror = "blank";
};

//MySQL Table Prefix
if (!empty($_POST["tableprefix"])) {
 $tableprefix = $_POST["tableprefix"];
} else {
 $tableprefixerror = "blank";
};

//Variable Gathering complete


if (isset($adminpasserror) || isset($webiperror) || isset($webporterror) || isset($scriptlocationerror) || isset($sqliperror) || isset($sqlusererror) || isset($sqlpasserror) || isset($dbnameerror) || isset($tableprefixerror)) {
  header("Location: http://" . $_SERVER['HTTP_HOST'] . str_replace( "\\", "/", dirname($_SERVER['PHP_SELF'])) . "index.php?" . errorlink() );
} 




//write config.php file

$configfilename = str_replace( "/setup", "/", dirname($_SERVER["SCRIPT_FILENAME"])) . "config.php";
$configfile = @fopen($configfilename, 'w');
if (!$configfile) {
  //error opening file
  $errorstring = "Unable to open config.php file for writing.<BR>";
  $error = "true";
} else {
  //file is open
  $errorstring = "File config.php opened for writing.<BR>";
  @fwrite($configfile, "<?\r\n");
  @fwrite($configfile, "\$adminusername = '" . $adminusername . "';\r\n");
  @fwrite($configfile, "\$adminpass = '" . $adminpass . "';\r\n");
  @fwrite($configfile, "\$botconfuser = '" . $botconfuser . "';\r\n");
  @fwrite($configfile, "\$sqlip = '" . $sqlip . "';\r\n");
  @fwrite($configfile, "\$sqluser = '" . $sqluser . "';\r\n");
  @fwrite($configfile, "\$sqlpass = '" . $sqlpass . "';\r\n");
  @fwrite($configfile, "\$dbname = '" . $dbname . "';\r\n");
  @fwrite($configfile, "\$tableprefix = '" . $tableprefix . "';\r\n");
  @fwrite($configfile, "?>");
  @fclose($configfile);
  $errorstring = $errorstring . "Finished writing to config.php file, file closed.<BR>";
};

//end write config.php file




//Database Stuff

if (!isset($error)) {
  //connect to database
  $datastream = @mysql_connect($sqlip, $sqluser, $sqlpass);
  if (!$datastream) {
    $errorstring = $errorstring . "Could not connect to MySQL Server: " . mysql_error() . "<BR>";
    $error = "true";
  };
};


if (!isset($error)) {
  $errorstring = $errorstring . "Connected to MySQL Server successfully<BR>";

  //select database
  @mysql_select_db($dbname);
  if (!$datastream) {
      $errorstring = $errorstring . "Could not select database:" . mysql_error() . "<BR>";
      $error = "true";
  };
};


if (!isset($error)) {
  $errorstring = $errorstring . "Database selected successfully<BR>";

  //create table CounterDescription
  $sqlstatement = "CREATE TABLE " . $tableprefix . "counterdescription (
		counterid MEDIUMINT NOT NULL AUTO_INCREMENT, 
		userorder MEDIUMINT NOT NULL DEFAULT '8388607',
		name VARCHAR(30) NOT NULL,
		lastviewed BIGINT NOT NULL DEFAULT '0', 
		type VARCHAR(3) NOT NULL, 
		destination TINYTEXT, 
		startingcount INT DEFAULT '0' NOT NULL, 
		datetimestart DATETIME DEFAULT 'NOW()' NOT NULL,
		datetimeviewreset DATETIME DEFAULT 'NOW()' NOT NULL,
                minimumdigits INT DEFAULT '0' NOT NULL,
		PRIMARY KEY (counterid)
	)";

  $create_result = @mysql_query($sqlstatement);
  if ($create_result) {
    $errorstring = $errorstring . "Created CounterDescription table successfully.<BR>";
  } else {
    $errorstring = $errorstring . "CounterDescription table creation failed: " . mysql_error() . "<BR>";
    $error = "true";
  };
};


if (!isset($error)) {
  //create table Counting
  $sqlstatement = "CREATE TABLE " . $tableprefix . "counting (
		countid BIGINT NOT NULL AUTO_INCREMENT,
		counterid MEDIUMINT NOT NULL,
		time DATETIME NOT NULL,
		ipaddress CHAR(15),
		hostname TINYTEXT,
		browsertext TINYTEXT,
		PRIMARY KEY (countid)
	)";

  $create_result2 = @mysql_query($sqlstatement);
  if ($create_result2) {
    $errorstring = $errorstring . "Created Counting table successfully.<BR>";
  } else {
    $errorstring = $errorstring . "Counting table creation failed:" . mysql_error() . "<BR>";
    $error = "true";
  };
};


if (!isset($error)) {
  //create table IPIgnore
  $sqlstatement = "CREATE TABLE " . $tableprefix . "ipignore (
		ipid MEDIUMINT NOT NULL AUTO_INCREMENT,
		ipaddress CHAR(15),
                description TINYTEXT,
		PRIMARY KEY (ipid)
	)";

  $create_result2 = @mysql_query($sqlstatement);
  if ($create_result2) {
    $errorstring = $errorstring . "Created IPIgnore table successfully.<BR>";
  } else {
    $errorstring = $errorstring . "IPIgnore table creation failed:" . mysql_error() . "<BR>";
    $error = "true";
  };
};



//close database
@mysql_close($datastream);


?>
<HTML>
 <HEAD>
  <TITLE>Welcome to Advanced CowCounter Setup</TITLE>
 </HEAD>
 <BODY BACKGROUND="CowSpots.gif" BGCOLOR="#ffffff">
  <H1>
   <IMG SRC="CowLogo.gif" WIDTH="90" HEIGHT="81" ALIGN="MIDDLE" BORDER="0">Advanced CowCounter Setup
   <HR ALIGN=LEFT>
  </H1>
  <CENTER>
    <TABLE WIDTH="80%">
      <TR>
        <TD>
          Attempting database setup... Standby...<BR>
          <?
            echo $errorstring;
            echo "<P><BR></P>";
          ?>
        </TD>
      </TR>
      <?
        if (isset($error)) {
      ?>
      <TR>
        <TD>
          Since it looked like you had problems during setup, use the error replies above to resolve the problem or contact your system administrator.  You can click the "BACK" button below to return to the previous page to check to make sure all settings were correct.
        </TD>
      </TR>
      <TR>
        <TD ALIGN="CENTER">
          <FORM ACTION="index.php" ENCTYPE="x-www-form-urlencoded" METHOD="POST">
            <INPUT NAME="error" TYPE="hidden" VALUE="true">
            <INPUT NAME="admin" TYPE="hidden" VALUE="<? echo $adminusername; ?>">
            <INPUT NAME="sqlip" TYPE="hidden" VALUE="<? echo $sqlip; ?>">
            <INPUT NAME="sqluser" TYPE="hidden" VALUE="<? echo $sqluser; ?>">
            <INPUT NAME="dbname" TYPE="hidden" VALUE="<? echo $dbname; ?>">
            <INPUT NAME="tableprefix" TYPE="hidden" VALUE="<? echo $tableprefix; ?>">
            <INPUT NAME="submit" TYPE="submit" VALUE="BACK">
          </FORM>
        </TD>
      </TR>
      <?
        } else {
          // no error creating database
          ?>
            <TR>
              <TD>
                Since it looks like you didn't have any problems, Advanced Cow Counter is now setup.  You may now proceed to the login page.
              </TD>
            </TR>
            <TR>
              <TD ALIGN="CENTER">
                <FORM ACTION="../index.php" METHOD="POST">
                  <INPUT NAME="firstlogin" TYPE="submit" VALUE="LOGIN">
                </FORM>
              </TD>
            </TR>
          <?
        };
      ?>
    </TABLE>
  </CENTER>
 </BODY>
</HTML>