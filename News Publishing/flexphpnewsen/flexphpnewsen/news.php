<?php
require("./NewsSql.inc.php"); 
$db = new NewsSQL($DBName);
if ((!empty($PR)) && ($Rating>=1)){
   $db->set_Rating($newsid,$Rating);
}
$result = $db->getnewsbyid($newsid);
$title = $result[0]["title"];
$content = $result[0]["content"];
$picture = $result[0]["picture"];
$picturepath = "./photo/";
$viewnum = $result[0]["viewnum"];
$sourceurl = $result[0]["sourceurl"];
$source = $result[0]["source"];
$adddate = $result[0]["adddate"];
$db->addhit($viewnum,$newsid);
$Rate = $result[0]["rating"];
$Rate1 = $Rate*10;
$Rate2 = ceil($Rate1);
$Rate = $Rate2/10;

$pre[0] = $newsid-1;

for ($i=1; $i<=$prenumber-1; $i++){
$pre[$i] = $pre[$i-1]-1;
}

$next[0] = $newsid+1;

for ($i=1; $i<=$nextnumber-1; $i++){
$next[$i] = $next[$i-1]+1;
}

?>
<html>
<head>
<title><?php  print "$title"; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php print "$front_charset"; ?>">
<link rel="stylesheet" href="./style/style.css" type="text/css">
<script language="JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
// -->
</script>
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0">
<?php
include("top.php3");
?>
<table width="770" border="0" cellspacing="1" cellpadding="0" align="center" class="table_01">  
  <tr> 
    <td class="table_02" width="160" valign="top"> 
      <table width="160" border="0" cellspacing="0" cellpadding="3">
        <tr> 
          <td colspan="3" bgcolor="#F2F2F2" class="menu_in">::<?php print "$front_rateit"; ?>:</td>
        </tr>
        <tr> 
          <td width="36"> 
            <div align="right"><?php print "$front_rating"; ?>:</div>
          </td>
          <td width="73"> 
            <?php 
              $width = $Rate*10;
              if (!empty($salt)){
              print "<img src=\"./images/redline.gif\" width=\"$width\" height=\"8\" alt=\"$salt\">";
              }
              else{          
              print "<img src=\"./images/redline.gif\" width=\"$width\" height=\"8\">"; 
              }
            ?>
          </td>
          <td width="33"> 
            <?php print "$Rate"; ?>
          </td>
        </tr>
      </table>
      
      <hr noshade size="1" color=#999999>
      <form action="<?php print "$PHP_SELF"; ?>" method="post">
      <input type="hidden" name="newsid" value="<?php print "$newsid"; ?>">      
      <table width="160" border="0" cellspacing="0" cellpadding="3">	  
        <tr> 
          <td bgcolor="#F2F2F2" class="menu_in" colspan="2">::<?php print "$front_letmerateit"; ?>:</td>
        </tr>
        <tr> 
          <td width="9">&nbsp; </td>
          <td width="139"> 
            <select name="Rating">
              <option selected value=5>5(<?php print "$front_ratebest"; ?>)</option>
              <option value=4>4</option>
              <option value=3>3</option>
              <option value=2>2</option>
              <option value=1>1</option>
            </select>
          </td>
        </tr>
        <tr> 
          <td colspan="2"> 
            <div align="center"> 
              <input type="submit" name="PR" value="<?php print "$front_ratesubmit"; ?>">
            </div>
          </td>
        </tr>
      </table>
      </form>
      
      <hr noshade size="1" color=#999999>
      <table width="160" border="0" cellspacing="0" cellpadding="4">
        <tr> 
          <td><img src="./images/left_search.gif" width="152" height="16"></td>
        </tr>
        <tr> 
          <td>            
          <form action="search.php" method="POST">                   
            <table border="0" cellspacing="0" cellpadding="0" width="145" align="right">
              <tr>
                  <td><input type="text" name="keyword" value="" size="12"></td>
              </tr>
              <tr><td><input type="submit" name="searchsubmit" value="<?php print "$front_searchsubmit"; ?>"></td></tr>
              <tr><td>&nbsp;</td></tr>
            </table>            
            </form>
          </td>
        </tr>
      </table>      
    </td>
    
    <td class="menu" bgcolor="#FFFFFF" valign="top" width="410"> 
      <table width="410" border="0" cellspacing="0" cellpadding="4"> 
         <tr> 
            <td bgcolor="#F2F2F2" class="menu_in">::<? print "$title"; ?></td>
          </tr>
          <?php
        if (!empty($picture)){
          
          print "<tr> 
                 <td align=\"center\"><img src=\"$picturepath$picture\" alt=\"$title\"></td>
                 </tr>";
          
        }
        
        if (!empty($content)){
              $content = nl2br($content);
        print "<tr> 
               <td>$content</td>
              </tr>";
        }
                 
         if (!empty($sourceurl)){
         print "<tr>
                  <td bgcolor=\"#F2F2F2\" class=\"menu_in\">$front_source :</td>
                </tr>
                <tr>
                  <td><a href=\"$sourceurl\" class=\"en_b\" target=\"_blank\">$source</a></td>
                </tr>";
        }
        else {
        if (!empty($source)){
        print "<tr>
                  <td bgcolor=\"#F2F2F2\" class=\"menu_in\">$front_source :</td>
               </tr>
               <tr>
                  <td>$source</td>
               </tr>";
           }
        }
        
        if (!empty($adddate)){
        print "<tr>
                  <td bgcolor=\"#F2F2F2\" class=\"menu_in\">$front_adddate :</td>
               </tr>
               <tr>
                  <td>$adddate</td>
               </tr>";
           }
                       
        for ($i=0; $i<=$prenumber-1; $i++){
          $prename[$i] = $db->getname($pre[$i]);
          if (!empty($prename[$i])){
          $prenotemptytag = true;
          }
          }
          for ($i=0; $i<=$nextnumber-1; $i++){
          $nextname[$i] = $db->getname($next[$i]);
          if (!empty($nextname[$i])){
          $nextnotemptytag = true;
          }
          }
          if (($prenotemptytag)||($nextnotemptytag)){
          print "<tr>
                 <td bgcolor=\"#F2F2F2\" class=\"menu_in\">$front_more ...</td>
                 </tr>
                 <tr> 
                 <td>";
          for ($i=0; $i<=$prenumber-1; $i++){
          if (!empty($prename[$i])){      
          print "<a href=\"news.php?newsid=$pre[$i]\" class=\"en_b\">$prename[$i]</a><br>";
          }
          }
          
          for ($i=0; $i<=$nextnumber-1; $i++){
          if (!empty($nextname[$i])){          
          print "<a href=\"news.php?newsid=$next[$i]\" class=\"en_b\">$nextname[$i]</a><br>";  
          }
          }
          print "</td>
                </tr>";
          }         
        
        ?>
      </table>             
    </td>
    <td class="table_02" background="./images/right_bg.gif" valign="top">       
      <table width="200" border="0" cellspacing="0" cellpadding="6">
        <tr> 
          <td>
          &nbsp;
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?php
include("bottom.php3");
?>
</body>
</html>
