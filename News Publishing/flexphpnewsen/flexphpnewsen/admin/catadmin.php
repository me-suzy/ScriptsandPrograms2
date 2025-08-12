<?php
require("./NewsSql.inc.php");
$db = new NewsSQL($DBName);
include("./usercheck.php");

$PicturePath = "../photo/";

if (empty($page)){
$page = 0;
}
$record = 20;

if ($Delcatalog==$admin_yes) {
$db->delcatalog($catid,$PicturePath);
}
if (!empty($addcatalog)) {
$db->addcatalog($catalogname,$description,$parentid);
}
if (!empty($editcatalog)) {
$db->editcatalog($catalogname,$description,$parentid,$catid);
}
$result = $db->getallcatalog($page,$record);
?>
<html>
<head>
<title><?php print "$admin_catalogadmin"; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php print "$admin_charset"; ?>">
<link rel="stylesheet" href="style/style.css" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0">
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
              <tr bgcolor="#CCCCCC"> 
                <td>&nbsp;</td>
                <td><?php print "$admin_name"; ?></td>
                <td><?php print "$admin_parentcatalog"; ?></td>
                <td colspan="3"><?php print "$admin_opreation"; ?></td>
              </tr>
              <?php
              if (!empty($result)) {
	        while ( list($key,$val)=each($result) ) {
	        $catalogid = stripslashes($val["catalogid"]);
	        $catalogname = stripslashes($val["catalogname"]);
	        $parentid  = stripslashes($val["parentid"]);
	        $parentname = $db->getcatalognamebyid($parentid);
              ?>
              <tr bgcolor="#FFFFFF">
              <td><?php print "$catalogid"; ?></td>
                <td><?php print "$catalogname"; ?></td>
                <td><?php print "$parentname"; ?></td>                
                <td><a href="catalognews.php?catid=<?php print "$catalogid"; ?>" class="en_b"><?php print "$admin_news"; ?></a></td>
                <td><a href="editcatalog.php?catid=<?php print "$catalogid"; ?>" class="en_b"><?php print "$admin_edit"; ?></a></td> 
                <td><a href="delcatalog.php?catid=<?php print "$catalogid"; ?>" class="en_b"><?php print "$admin_del"; ?></a></td>               
              </tr>
              <?php
              }
              }
              ?>                       
            <tr bgcolor="#FFFFFF">
            <td align="right" colspan="4">
            <?php
              $pagenext = $page+1;
		$result1 = $db->getallcatalog($pagenext,$record);
		if ($page!=0)
		{
		$pagepre = $page-1;		
		print "<a href=\"$PHP_SELF?page=$pagepre\"><font color=\"#FF0000\">$admin_previouspage</font></a>&nbsp;&nbsp;&nbsp;";
		}
		if (!empty($result1))
		{
		print "<a href=\"$PHP_SELF?page=$pagenext\"><font color=\"#FF0000\">$admin_nextpage</font></a>&nbsp;";
		}
		?>
            </td>
            </tr>
            </table>
            </td>
        </tr>
        <tr>
        <td align="center">
        <form action="<?php print "$PHP_SELF"; ?>" method="POST">        
        <table width="300" border="0" cellspacing="1" cellpadding="4" bgcolor="#F2F2F2">
             <tr bgcolor="#FFFFFF"> 
                <td width="83"><?php print "$admin_name"; ?> :</td>
                <td width="198"><input type="text" name="catalogname"></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td><?php print "$admin_description"; ?> :</td>
                <td><textarea name="description" cols="17" rows="5"></textarea></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td><?php print "$admin_parentcatalog"; ?> :</td>
                <td>
                <select name="parentid">
                <option value="0" selected><?php print "$admin_none"; ?></option>
                <?php
                $nameinfo = $db->getallcatalogname(); 
                if (!empty($nameinfo)){
	            while (list($key,$val)=each($nameinfo)) {
		    $catalogid = stripslashes($val["catalogid"]);
		    $catalogname = stripslashes($val["catalogname"]);
		    print "<option value=\"$catalogid\">$catalogname</option>";
		 }
		}
                ?>
                </select>
                </td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td>&nbsp;</td>
                <td><input type="submit" name="addcatalog" value="<?php print "$admin_add"; ?>"></td>
              </tr>
        </table>
        <p><a href="admin_index.php"><?php print "$admin_back"; ?></a>
            </p>
        </form>
        </td>
        </tr>
      </table>
      
    </td>
</tr>
<tr>
    <td align="center" valign="top" height="40">&nbsp;</td>
  </tr>
</table>
<?php
include("bottom.php3");
?>
</body>
</html>
