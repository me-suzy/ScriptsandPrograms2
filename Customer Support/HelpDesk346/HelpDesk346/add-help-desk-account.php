<?
include("checksession.php"); 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Help Desk Accounts Creation Page</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="670"> <p align="center"><strong><img src="images/help-desk-account-managment.jpg" alt="Help Desk Account Managment" width="594" height="176" border="0" usemap="#Map2">
        <map name="Map2">
          <area shape="rect" coords="4,130,70,176" href="reportproblem.php">
          <area shape="rect" coords="80,128,159,174" href="helpDeskAccessAllCalls.php">
          <area shape="rect" coords="173,129,274,173" href="DataAccessSearch.php">
          <area shape="rect" coords="292,126,375,177" href="ocm-first.php">
          <area shape="rect" coords="384,128,447,174" href="search.php">
          <area shape="rect" coords="454,128,544,169" href="DataAccess.php">
        </map>
        <br>
        Help Desk User Account Creation. &quot;Accounts used to login to the help 
        desk&quot;.</strong><br>
        <br>
      </p>
      <p align="center">Press &quot;Create Accounts&quot; button at the bottom 
        of the page when you have entered your desired accounts.</p>
      <hr noshade>
      Account 1 
      <form name="form1" method="post" action="add-processor.php">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td>First Name: 
              <input name="FirstName" type="text" id="FirstName"> </td>
            <td>Example: Ted</td>
            <td width="57%">&nbsp;</td>
          </tr>
          <tr> 
            <td><p>Last Name: 
                <input name="LastName" type="text" id="LastName2">
              </p></td>
            <td>Example: Sloth</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td><strong>User Account</strong>: 
              <input name="UserAccount" type="text" id="UserAccount2"> </td>
            <td>Example TSloth</td>
            <td>&nbsp; </td>
          </tr>
          <tr> 
            <td width="30%"><strong>Password</strong>: 
              <input name="password" type="password" id="password55"></td>
            <td width="13%">Example: superglobal</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="2"><font color="#FF0000"><strong>Path to help desk <font color="#FF0000">*This 
              is required:</font></strong><font color="#000000"></font></font> 
              <input name="pathToHelpDesk" type="text" id="password" size="70" maxlength="200"></td>
            <td rowspan="2"><p>&nbsp;</p></td>
          </tr>
          <tr> 
            <td colspan="2"><p><font color="#000000"> <font color="#FF0000"><strong>Above</strong></font> 
                You must enter the absolute web path to your help desk DataAccess.php 
                file! Example, if my server was of the domain helpdeskreloaded.com 
                my abosulte path would be as follows: http://http://www.helpdeskreloaded.com/helpdesk/helpdesk/DataAccess.php</font></p>
              <p><font color="#000000">Example 2:</font><font color="#000000"><br>
                http://<strong>yourserverordomiangoeshere</strong>/helpdesk/DataAccess.php</font></p></td>
          </tr>
        </table>
        <hr noshade>
        Account 2 
        <table width="75%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td>First Name: 
              <input name="FirstName2" type="text" id="FirstName2"> </td>
          </tr>
          <tr> 
            <td><p>Last Name: 
                <input name="LastName2" type="text" id="LastName3">
              </p></td>
          </tr>
          <tr> 
            <td>User Account: 
              <input name="UserAccount2" type="text" id="UserAccount3"> </td>
          </tr>
          <tr> 
            <td>Password: 
              <input name="password2" type="password" id="password54"></td>
          </tr>
          <tr>
            <td>Path to help desk 
              <input name="pathToHelpDesk2" type="text" id="pathToHelpDesk" size="70" maxlength="200"></td>
          </tr>
        </table>
        <hr noshade>
        Account 3 
        <table width="75%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td>First Name: 
              <input name="FirstName3" type="text" id="FirstName3"> </td>
          </tr>
          <tr> 
            <td><p>Last Name: 
                <input name="LastName3" type="text" id="LastName3">
              </p></td>
          </tr>
          <tr> 
            <td>User Account: 
              <input name="UserAccount3" type="text" id="UserAccount3"> </td>
          </tr>
          <tr> 
            <td>Password: 
              <input name="password3" type="password" id="password53"></td>
          </tr>
          <tr>
            <td>Path to help desk 
              <input name="pathToHelpDesk3" type="text" id="pathToHelpDesk2" size="70" maxlength="200"></td>
          </tr>
        </table>
        <hr noshade>
        Account 4 
        <table width="75%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td>First Name: 
              <input name="FirstName4" type="text" id="FirstName4"> </td>
          </tr>
          <tr> 
            <td><p>Last Name: 
                <input name="LastName4" type="text" id="LastName4">
              </p></td>
          </tr>
          <tr> 
            <td>User Account: 
              <input name="UserAccount4" type="text" id="UserAccount4"> </td>
          </tr>
          <tr> 
            <td>Password: 
              <input name="password4" type="password" id="password4"></td>
          </tr>
          <tr>
            <td>Path to help desk 
              <input name="pathToHelpDesk4" type="text" id="pathToHelpDesk4" size="70" maxlength="200"></td>
          </tr>
        </table>
        <hr noshade>
        Account 5 
        <table width="75%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td>First Name: 
              <input name="FirstName5" type="text" id="FirstName5"> </td>
          </tr>
          <tr> 
            <td><p>Last Name: 
                <input name="LastName5" type="text" id="LastName5">
              </p></td>
          </tr>
          <tr> 
            <td>User Account: 
              <input name="UserAccount5" type="text" id="UserAccount5"> </td>
          </tr>
          <tr> 
            <td>Password: 
              <input name="password5" type="password" id="password5"></td>
          </tr>
          <tr>
            <td>Path to help desk 
              <input name="pathToHelpDesk5" type="text" id="pathToHelpDesk5" size="70" maxlength="200"></td>
          </tr>
        </table>
        <hr noshade>
        <input type="submit" name="Submit" value="Create Accounts" class="button">
        <br>
        <p>&nbsp; </p>
      </form>
      
    </td>
  </tr>
</table>

<div align="center"><br>
  <a href="http://www.helpdeskreloaded.com"><img src="http://www.helpdeskreloaded.com/reload/help-desk-copyright.jpg" alt="http://www.helpdeskreloaded.com Help Desk Software By  HelpDeskReloaded &quot;Help Desk Reloaded&quot;" border="0"></a> 
</div>
</body>
</html>
