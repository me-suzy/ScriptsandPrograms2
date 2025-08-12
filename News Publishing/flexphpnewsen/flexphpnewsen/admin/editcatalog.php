<?php
require("./NewsSql.inc.php");
$db = new NewsSQL($DBName);
include("./usercheck.php");
$result = $db->getcatalogbyid($catid);
$catalogname = $result[0]["catalogname"];
$description = $result[0]["description"];
$parentid = $result[0]["parentid"];
?>
<html>
<head>
<title><?php print "$admin_catalogadmin"; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php print "$admin_charset"; ?>">
<link rel="stylesheet" href="style/style.css" type="text/css">
</head>
<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0">
<form action="catadmin.php" method="POST">
<input type="hidden" name="catid" value="<?php print "$catid"; ?>">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr> 
    <td align="center" valign="top"> 
      <?php
      include("top.php3");
      ?>
      <hr width="90%" size="1" noshade>
      <table width="90%" border="0" cellspacing="0" cellpadding="4" height="300">
        <tr> 
          <td align="center"> 
            <table width="400" border="0" cellspacing="1" cellpadding="4" bgcolor="#F2F2F2">
              <tr bgcolor="#FFFFFF"> 
                <td width="183"><?php print "$admin_name"; ?> :</td>
                <td width="198"><input type="text" name="catalogname" value="<? print "$catalogname"; ?>"></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td><?php print "$admin_description"; ?> :</td>
                <td><textarea name="description" cols="17" rows="5"><?php print "$description"; ?></textarea></td>
              </tr> 
              <tr bgcolor="#FFFFFF"> 
                <td><?php print "$admin_parentcatalog"; ?> :</td>
                <td>
                <select name="parentid">
                <?php
                if ($parentid==0){
                ?>
                <option value="0" selected><?php print "$admin_none"; ?></option>
                <?php
                }else{
                ?>
                <option value="0"><?php print "$admin_none"; ?></option>
                <?php
                }
                $nameinfo = $db->getallcatalogname(); 
                if (!empty($nameinfo)){
	            while (list($key,$val)=each($nameinfo)) {
		    $catalogid = stripslashes($val["catalogid"]);
		    $catalogname = stripslashes($val["catalogname"]);
		    if ($catalogid==$parentid){
		    print "<option value=\"$catalogid\" selected>$catalogname</option>";
		    }else{
		    print "<option value=\"$catalogid\">$catalogname</option>";
		    }
		 }
		}
                ?>
                </select>
                </td>
              </tr>                           
            </table> 
            <p>
            <input type="submit" name="editcatalog" value="<?php print "$admin_ok"; ?>">             
            </p>                      
          </td>
        </tr>
      </table>
      
    </td>
</tr>
<tr>
    <td align="center" valign="top" height="40">&nbsp;</td>
  </tr>
</table>
</form>
<?php
include("bottom.php3");
?>
</body>
</html>