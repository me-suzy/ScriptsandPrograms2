<?
#######################################
###         ComusTGP version 1.3.3  ###
###         nibbi@nibbi.net         ###
###         Copyright 2002          ###
#######################################
?>
<html>
<head>
<title>Comus TGP Submit Page</title>
</head>
<body bgcolor="#FFFFFF" topmargin="0">
<form action="post.php" method=POST onSubmit="return(checkit())" name="info"><br>
<? 
include ($DOCUMENT_ROOT . "/includes/formcheck.inc.php"); 
include ($DOCUMENT_ROOT . "/includes/config.inc.php");
?>
  <div align="left">
    <table align="center">
      <tr> 
        <td width="75"> 
          <div align="left"><font face=Arial size=-1><b>Name</b></font></div>
        </td>
        <td width="130"> 
          <div align="left"> 
            <input type=TEXT name=nickname>
          </div>
        </td>
      </tr>
      <tr> 
        <td width="75"> 
          <div align="left"><font face=Arial size=-1><b>Email</b></font></div>
        </td>
        <td width="130"> 
          <div align="left"> 
            <input type=TEXT name=email>
          </div>
        </td>
      </tr>
      <tr> 
        <td width="75"> 
          <div align="left"><font face=Arial size=-1><b>URL</b></font></div>
        </td>
        <td width="130"> 
          <div align="left"> 
            <input type=TEXT name=url value="http://">
          </div>
        </td>
      </tr>
      <tr> 
        <td width="75"> 
          <div align="left"><font face=Arial size=-1><b>Category</b></font></div>
        </td>
        <td width="130"> 
          <div align="left"> 
            <select name=category>
              <?

   $query = "SELECT * FROM tblCategories ORDER BY Category";
   $result = mysql_query ($query)
        or die ("Query failed");

   if ($result) {

   while ($r = mysql_fetch_array($result)) { 

   $Category = $r["Category"];
                              
      echo"<option>$Category";

      }
   } 
?>
            </select>
          </div>
        </td>
      </tr>
      <tr> 
        <td width="75"><font face=Arial size=-1><b>Desc</b></font></td>
        <td width="130"> 
          <input type="text" name="description" maxlength=<? $descleng; ?>>
        </td>
      </tr>
<?  if($usepreferred == 'Yes'){
echo "<tr> 
        <td width=\"75\"><b>Password</b></td>
        <td width=\"130\">
          <input type=\"text\" name=\"pass\">
        </td>
      </tr>"; 
} ?>
      <tr> 
        <td colspan=2> 
          <div align="center"> 
            <input type=submit value="Post Gallery" name="submit">
          </div>
        </td>
      </tr>
      <tr> 
        <td colspan=2> 
          <div align="center"> <font size="2"><a href="http://www.nibbi.net/scripts/comus/">Powered 
            by Comus TGP</a></font></div>
        </td>
      </tr>
    </table>
    <div align="center"></div>
  </div>
</form>
</body>
</html>
