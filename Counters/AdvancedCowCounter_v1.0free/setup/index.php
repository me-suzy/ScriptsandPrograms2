<HTML>
<HEAD>
  <META NAME="GENERATOR" CONTENT="Adobe PageMill 3.0 Win">
  <TITLE>Welcome to Advanced CowCounter Setup</TITLE>
</HEAD>
<BODY BACKGROUND="CowSpots.gif" BGCOLOR="#ffffff">

<FORM ACTION="setup.php" ENCTYPE="x-www-form-urlencoded" METHOD="POST">
<H1><IMG SRC="CowLogo.gif" WIDTH="90" HEIGHT="81" ALIGN="MIDDLE"
BORDER="0" NATURALSIZEFLAG="3">Advanced CowCounter Setup<HR ALIGN=LEFT></H1>

<P>Welcome to the installation wizard for the Advanced CowCounter
from Meezerk.com. Please fill in <B>ALL</B> of the information below
to get started.</P>

<?
  if ($_POST["error"] == "true") {
    ?>
      <P><FONT COLOR="#ff0000">It would appear that you did not fill in all 
      of the information required or the information was wrong for this 
      installation to take place.  Please be sure to fill in <B>ALL</B> 
      information and ensure that all information is correct before you
      try again.</FONT></P>
    <?
  };
?>

<P><CENTER><TABLE WIDTH="90%" BORDER="0" CELLSPACING="2" CELLPADDING="2">
  <CAPTION ALIGN="TOP"><B>General Setup (for CowCounter)</B></CAPTION>

  <TR>
    <TD WIDTH="50%" ALIGN="RIGHT">
    Administrator Password:</TD> 
    <TD WIDTH="50%">
    <INPUT NAME="adminpass" TYPE="password" SIZE="25">
    <?
      if($_POST["adminpasserror"] == "mismatch") {
        ?>
          <FONT COLOR="#ff0000">Oops, this didn't match...</FONT>
        <?
      } elseif($_POST["adminpasserror"] == "blank") {
        ?>
          <FONT COLOR="#ff0000">Oops, you forgot this...</FONT>
        <?
      } elseif($_POST["error"] == "true") {
        ?>
          <FONT COLOR="#ff0000">This needs to be entered again...</FONT>
        <?
      };
    ?>
    </TD>
  </TR>
  <TR>
    <TD WIDTH="50%" ALIGN="RIGHT">
    Confirm Administrator Password:</TD> 
    <TD WIDTH="50%">
    <INPUT NAME="adminpassconfirm" TYPE="password" SIZE="25">
    <?
      if($_POST["adminpasserror"] == "mismatch") {
        ?>
          <FONT COLOR="#ff0000"> ...with this.</FONT>
        <?
      } elseif($_POST["adminpasserror"] == "blank") {
        ?>
          <FONT COLOR="#ff0000"> ...and this.</FONT>
        <?
      } elseif($_POST["error"] == "true") {
        ?>
          <FONT COLOR="#ff0000"> ...along with this.</FONT>
        <?
      };
    ?>
    </TD>
  </TR>
  <TR>
    <TD WIDTH="50%" ALIGN="RIGHT">
    Bot Cracker Confuser Enabled:</TD> 
    <TD WIDTH="50%">
    <INPUT TYPE="checkbox" NAME="loginsecurity" VALUE="checked" CHECKED="1"> (NOT YET IMPLEMENTED.)
    </TD>
  </TR>
</TABLE></CENTER></P>


<?
//deleted code
/*-----------------------------------------------------------------
<P><CENTER><TABLE WIDTH="90%" BORDER="0" CELLSPACING="2" CELLPADDING="2">
  <CAPTION ALIGN="TOP"><B>Web Server Setup</B></CAPTION>
   
  <TR>
    <TD WIDTH="50%" ALIGN="RIGHT">
    IP Address or Hostname of Webserver:</TD> 
    <TD WIDTH="50%">
    <INPUT NAME="webip" TYPE="text" SIZE="30" VALUE="<? if(isset($_POST["webip"])) { echo $_POST["webip"]; } else { echo $_SERVER['SERVER_NAME']; }; ?>">
    <?
      if($_POST["webiperror"] == "blank") {
        ?>
          <FONT COLOR="#ff0000">Oops, you forgot this.</FONT>
        <?
      };
    ?>
    </TD>
  </TR>
  <TR>
    <TD WIDTH="50%">
    <P ALIGN=RIGHT>Webserver Port:&nbsp;</TD> 
    <TD WIDTH="50%">
    <INPUT NAME="webport" TYPE="text" SIZE="5" VALUE="<? if(isset($_POST["webport"])) { echo $_POST["webport"]; } else { echo $_SERVER['SERVER_PORT']; }; ?>">
    <?
      if($_POST["webporterror"] == "blank") {
        ?>
          <FONT COLOR="#ff0000">Oops, you forgot this.</FONT>
        <?
      };
    ?>
    </TD>
  </TR>
  <TR>
    <TD WIDTH="50%" ALIGN="RIGHT">
    Location of Script (count.php) relative to Domain Name:</TD> 
    <TD WIDTH="50%">
    <INPUT NAME="scriptlocation" TYPE="text" SIZE="35" VALUE="<? if(isset($_POST["scriptlocation"])) { echo $_POST["scriptlocation"]; } else { echo str_replace( "/setup", "/", ( str_replace( "\\", "/", dirname($_SERVER['PHP_SELF'])) ) ); }; ?>">
    <?
      if($_POST["scriptlocationerror"] == "blank") {
        ?>
          <FONT COLOR="#ff0000">Oops, you forgot this.</FONT>
        <?
      };
    ?>
    </TD>
  </TR>
</TABLE></CENTER></P>
------------------------------------------------------*/
//end of deleted code
?>

<P><CENTER><TABLE BORDER="0" CELLSPACING="2" CELLPADDING="2" 
WIDTH="90%">
  <CAPTION ALIGN="TOP"><B>MySQL Server Setup</B></CAPTION>
   
  <TR>
    <TD WIDTH="50%" ALIGN="RIGHT">
    IP Address or Hostname of Database Server:<BR>
    (must be MySQL)</TD> 
    <TD WIDTH="50%">
    <INPUT NAME="sqlip" TYPE="text" SIZE="30" <? if(isset($_POST["sqlip"])) { echo 'VALUE="' . $_POST["sqlip"] . '"'; }; ?>>
    <?
      if($_POST["sqliperror"] == "blank") {
        ?>
          <FONT COLOR="#ff0000">Oops, you forgot this.</FONT>
        <?
      };
    ?>
    </TD>
  </TR>
  <TR>
    <TD WIDTH="50%" ALIGN="RIGHT">
    User Account Name:</TD> 
    <TD WIDTH="50%">
    <INPUT NAME="sqluser" TYPE="text" SIZE="25" <? if(isset($_POST["sqluser"])) { echo 'VALUE="' . $_POST["sqluser"] . '"'; }; ?>>
    <?
      if($_POST["sqlusererror"] == "blank") {
        ?>
          <FONT COLOR="#ff0000">Oops, you forgot this.</FONT>
        <?
      };
    ?>
    </TD>
  </TR>
  <TR>
    <TD WIDTH="50%" ALIGN="RIGHT">
    User Account Password:</TD> 
    <TD WIDTH="50%">
    <INPUT NAME="sqlpass" TYPE="password" SIZE="25">
    <?
      if($_POST["sqlpasserror"] == "blank") {
        ?>
          <FONT COLOR="#ff0000">Oops, you forgot this.</FONT>
        <?
      } elseif($_POST["error"] == "true") {
        ?>
          <FONT COLOR="#ff0000">This needs to be entered again.</FONT>
        <?
      };
    ?>
    </TD>
  </TR>
  <TR>
    <TD WIDTH="50%" ALIGN="RIGHT">
    Database Name:</TD> 
    <TD WIDTH="50%">
    <INPUT NAME="dbname" TYPE="text" SIZE="25" <? if(isset($_POST["dbname"])) { echo 'VALUE="' . $_POST["dbname"] . '"'; }; ?>>
    <?
      if($_POST["dbnameerror"] == "blank") {
        ?>
          <FONT COLOR="#ff0000">Oops, you forgot this.</FONT>
        <?
      };
    ?>
    </TD>
  </TR>
  <TR>
    <TD WIDTH="50%" ALIGN="RIGHT">
    Table Name Prefix</TD> 
    <TD WIDTH="50%">
    <INPUT NAME="tableprefix" TYPE="text" SIZE="10" VALUE="<? if(isset($_POST["tableprefix"])) { echo $_POST["tableprefix"]; } else { echo "cc_"; }; ?>">
    <?
      if($_POST["tableprefixerror"] == "blank") {
        ?>
          <FONT COLOR="#ff0000">Oops, you forgot this.</FONT>
        <?
      };
    ?>
    </TD>
  </TR>
</TABLE></CENTER></P>

<P><CENTER><INPUT NAME="submit" TYPE="submit" VALUE="Submit">
<INPUT NAME="name" TYPE="reset" VALUE="Reset"></CENTER></FORM>

</BODY>
</HTML>