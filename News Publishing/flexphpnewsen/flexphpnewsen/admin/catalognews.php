<?php
require("./NewsSql.inc.php");
$db = new NewsSQL($DBName);
include("./usercheck.php");

$PicturePath = "../photo/";

if (empty($page)){
$page = 0;
}
$record = 20;

if ($Delnews==$admin_yes) {
$db->delnews($newsid,$PicturePath);
}

if (!empty($addnews)) {
$newsid = $db->addnews($catalogid,$title,$content,$viewnum,$rating,$ratenum,$source,$sourceurl,$isdisplay);
   if ((!empty($userfile)) && (!empty($userfile_name))) {
   $prefix = time();
   $userfile_name = $prefix.$userfile_name;
   $dest1 = $PicturePath.$userfile_name;
   copy($userfile, $dest1);
   $db->add_Picture($newsid,$userfile_name,$PicturePath);
   }
}

if (!empty($editnews)) {
   if ((!empty($userfile)) && (!empty($userfile_name))) {   
   $prefix = time();
   $userfile_name = $prefix.$userfile_name;
   $dest1 = $PicturePath.$userfile_name;
   copy($userfile, $dest1);
   $db->add_Picture($newsid,$userfile_name,$PicturePath);
   }
$db->editnews($catalogid,$title,$content,$viewnum,$rating,$ratenum,$source,$sourceurl,$isdisplay,$newsid);
   
}

if (!empty($DP1)) {
   $db->del_Picture($newsid,$PicturePath);
}

$result = $db->getcatalognews($page,$record,$catid);
?>
<html>
<head>
<title><?php print "$admin_newsadmin"; ?></title>
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
                <td><?php print "$admin_title"; ?></td>
                <td><?php print "$admin_catalog"; ?></td>                
                <td colspan="2"><?php print "$admin_opreation"; ?></td>
              </tr>
              <?php
              if (!empty($result)) {
	        while ( list($key,$val)=each($result) ) {
	        $newsid = stripslashes($val["newsid"]);
	        $catalogid = stripslashes($val["catalogid"]);
	        $title = stripslashes($val["title"]);	        
	        $cataname = $db->getcatalognamebyid($catalogid);
              ?>
              <tr bgcolor="#FFFFFF">
              <td><?php print "$newsid"; ?></td>
                <td><?php print "$title"; ?></td>
                <td><?php print "$cataname"; ?></td>                
                <td><a href="editcatanews.php?newsid=<?php print "$newsid"; ?>&catid=<?php print "$catid"; ?>" class="en_b"><?php print "$admin_edit"; ?></a></td>
                <td><a href="delcatanews.php?newsid=<?php print "$newsid"; ?>&catid=<?php print "$catid"; ?>" class="en_b"><?php print "$admin_del"; ?></a></td>                              
              </tr>
              <?php
              }
              }
              ?>                       
            <tr bgcolor="#FFFFFF">
            <td align="right" colspan="5">
            <?php
              $pagenext = $page+1;
		$result1 = $db->getcatalognews($pagenext,$record,$catid);
		if ($page!=0)
		{
		$pagepre = $page-1;		
		print "<a href=\"$PHP_SELF?page=$pagepre&catid=$catid\"><font color=\"#FF0000\">$admin_previouspage</font></a>&nbsp;&nbsp;&nbsp;";
		}
		if (!empty($result1))
		{
		print "<a href=\"$PHP_SELF?page=$pagenext&catid=$catid\"><font color=\"#FF0000\">$admin_nextpage</font></a>&nbsp;";
		}
		?>
            </td>
            </tr>
            </table>            
            </td>
        </tr>    
        <tr>
        <td align="center">
        <form action="<?php print "$PHP_SELF"; ?>" method="POST" ENCTYPE="multipart/form-data">
        <input type="hidden" name="catalogid" value="<?php print "$catid"; ?>">
        <input type="hidden" name="catid" value="<?php print "$catid"; ?>">        
        <table width="300" border="0" cellspacing="1" cellpadding="4" bgcolor="#F2F2F2">
             <tr bgcolor="#FFFFFF"> 
                <td width="83"><?php print "$admin_title"; ?> :</td>
                <td width="198"><input type="text" name="title"></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td><?php print "$admin_content"; ?> :</td>
                <td><textarea name="content" cols="17" rows="5"></textarea></td>
              </tr>                
              <tr bgcolor="#FFFFFF"> 
                <td><?php print "$admin_viewnumber"; ?> :</td>
                <td><input type="text" name="viewnum" value="0"></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td><?php print "$admin_rating"; ?> :</td>
                <td><input type="text" name="rating" value="4"></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td><?php print "$admin_ratenumber"; ?> :</td>
                <td><input type="text" name="ratenum" value="1"></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td><?php print "$admin_picture"; ?> :</td>
                <td><input type="file" name="userfile"></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td><?php print "$admin_source"; ?> <img src="../images/help.gif" width="15" height="15" border="0" alt="<?php print "$help_source"; ?>"> :</td>
                <td><input type="text" name="source" value=""></td>
              </tr> 
              <tr bgcolor="#FFFFFF"> 
                <td><?php print "$admin_sourceurl"; ?> :</td>
                <td><input type="text" name="sourceurl" value=""></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td><?php print "$admin_isdisplay"; ?> :</td>
                <td>
                <select name="isdisplay">
                <option value="1" selected><?php print "$admin_yes"; ?></option>
                <option value="0"><?php print "$admin_no"; ?></option>
                </select>
                </td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td>&nbsp;</td>
                <td><input type="submit" name="addnews" value="<?php print "$admin_add"; ?>"></td>
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
