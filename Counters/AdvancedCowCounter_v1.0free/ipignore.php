<?

//---------------------------------------------------------------
//This program is free software; you can redistribute it and/or
//modify it under the terms of the GNU General Public License
//as published by the Free Software Foundation; either version 2
//of the License, or (at your option) any later version.
//
//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details.
//
//Meezerk's Advanced CowCounter - An Advanced Website Counter.
//Copyright (C) 2004  Daniel Foster  dan_software@meezerk.com
//---------------------------------------------------------------

//session check

session_start();
include("config.php");

if (!(($_SESSION['ip'] == $_SERVER['REMOTE_ADDR']) && ($_SESSION['pass'] == $adminpass) && ($_SESSION['access'] == "granted"))) {
  //session info bad
  header("Location: http://" . $_SERVER['HTTP_HOST'] . str_replace( "\\", "/", dirname($_SERVER['PHP_SELF'])) . "/logout.php?session=bad");

} else {
  //session info good

  include("header.php");

  $sqlstatement = "SELECT * FROM " . $tableprefix . "ipignore ORDER BY ipaddress";
  $datastream = @mysql_connect($sqlip, $sqluser, $sqlpass);
  @mysql_select_db($dbname);
  $result = @mysql_query($sqlstatement);

  if (!$result) {
    //error getting data
    ?>
      There seems to have been an error, the error code returned from you MySQL database was:<BR>
      <?
        echo mysql_error() . "<BR>";
      ?>
      If you do not understand this error, please contact your local system administrator.<BR>
    <?
  } else {
    // results retrieved successfully.

    $rowcount = mysql_num_rows($result);

    ?>
      <H3>Edit IPs to Ignore <A HREF="help.php#EditIgnoreIP"><IMG BORDER=1 SRC="help.gif"></A></H3>
    <?
    if(!($rowcount > 0)) {
      echo "No IPs are being ignored at this time.";
    } else {
      //ips exist
      ?>
        <TABLE BORDER="1" CELLSPACING="1" CELLPADDING="1">
         <TR>
          <TH NOWRAP> IP Address </TH>
          <TH NOWRAP> Description </TH>
          <TH NOWRAP> Delete </TH>
         </TR>
         <?
          for ($rowcounter=0; $rowcounter < $rowcount; $rowcounter++) {
           ?>
            <TR>
             <TD ALIGN="CENTER" NOWRAP>
              <? echo str_replace( "%", "*", mysql_result($result,$rowcounter,"ipaddress")); ?>
             </TD>
             <TD>
              <? echo mysql_result($result,$rowcounter,"description"); ?>
             </TD>
             <FORM ACTION="ipignoredo.php" ENCTYPE="x-www-form-urlencoded" METHOD="POST">
              <TD>
               <INPUT NAME="ipid" TYPE="hidden" VALUE="<? echo mysql_result($result,$rowcounter,"ipid"); ?>">
               <INPUT NAME="submit" TYPE="submit" VALUE="Delete">
              </TD>
             </FORM>
            </TR>
           <? 
           //end for loop
          };
         ?>
        </TABLE>
      <?
      //end rowcount
    };
    //Add IP Table

    ?>
<BR>
<HR>

<H3>Add a New IP Address to Ignore</H3>

<FORM ACTION="ipignoredo.php" ENCTYPE="x-www-form-urlencoded" METHOD="POST">

<TABLE BORDER="1" CELLSPACING="1" CELLPADDING="2">
 <CAPTION ALIGN="BOTTOM">
  <INPUT NAME="submit" TYPE="submit" VALUE="Create">
  <P><CENTER>You can use the asterisk ( * ) as a wildcard if needed.</CENTER></P>
 </CAPTION>
 <TR>
  <TH NOWRAP>IP Address</TH>
  <TH NOWRAP>Description</TH>
 </TR>
 <TR>
  <TD>
   <INPUT NAME="ipaddress" TYPE="text" SIZE="20" MAXLENGTH="15">
  </TD>
  <TD>
   <INPUT NAME="description" TYPE="text" SIZE="35" MAXLENGTH="255">
  </TD>
 </TR>
</TABLE>

</FORM>

    <?


    // end if result
  };

  include("footer.php");
};
?>