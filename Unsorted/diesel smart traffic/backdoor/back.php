<?
  require("../conf/sys.conf");
  require("bots/errbot");
  require("bots/mcbot");


  $root=0;
  $inic="Administrator";
  if(isset($sc)) parse_str(base64_decode($sc));
  else setcookie("sc","");
  $pms=base64_encode("login=".$login."&"."pswd=".$pswd);

  if($login==$ADMIN_LOGIN && $pswd==$ADMIN_PSWD){
    $root=1;
    setcookie ("sc",$pms);
  }
  else{
     _fatal("Access denied!","You don't have access to view this page!");
  }
?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
  <title>ADMIN</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body bgcolor=FFFFFF class=bodytext link=0 vlink=0 alink=0>
<table width="100%" border="0" cellspacing="1" cellpadding="1" height="100%">
  <tr>
    <td>
      <table width="317" border="0" cellspacing="1" cellpadding="1" align="center">
        <tr>
          <td>
            <table width="317" border="0">
              <tr>
                <td><b><font size=-1>ADMINISTRATOR AREA</font></b></td>
              </tr>
              <tr>
                <td><span class="small"></span> </td>
              </tr>
              <tr>
                <td>
                  <hr noshade align=left size=1 width=317>
                  <?php
  $db=con_srv();
  $adm_txt="";
  if(!$root) $adm_txt="WHERE fadm='N'";
  $r=_query("SELECT * FROM menus $adm_txt");
  for($i=0;$i<mysql_numrows($r);$i++){
   $f=mysql_fetch_array($r);
   $color="0";
   if($f[fadm]=="Y") $color="AA0000";
   if ($f[link]) 
   echo "<li type=square><a href=\"$f[link]\"><font color=$color class=links><font size=-1>$f[topic]</a></font></font></l; i>";
   else echo "<BR><BR> <b><font color=$color class=links><font size=2>$f[topic]</font></font></b>";
  }
?>
<hr noshade align=left size=1 width=317>
<?
  $rr = _query("select * from campaigns where status='0'");
  if(_empty($rr)) echo "<li style=square>&nbsp;<font size=-1>There are no new campaigns</li>";
  else echo "<li style=square>&nbsp;<font size=-1><a href=engine.php?spec=campaigns>Total: ".mysql_numrows($rr)." new campaign(s).</a></li>";

  $rr = _query("select * from banners where status='0'");
  if(_empty($rr)) echo "<li style=square>&nbsp;<font size=-1>There are no new banners</li>";
  else echo "<li style=square>&nbsp;<font size=-1><a href=engine.php?spec=banners>Total: ".mysql_numrows($rr)." new banner(s).</a></li>";
  dc_srv($db);
?>
                  <a href=%5C%22engine.php?spec=menus%5C%22><font color=0 class=links></font></a>
                  <hr width=317 align=left noshade color=0>
                </td>
              </tr>
            </table>
            <table width="317" border="0" cellspacing="2" cellpadding="1" height="24">
              <tr align="center">
                <td bgcolor="#EEEEEE" width="70"><a href="index.php">logout</a></td>
                <td bgcolor="#DDDDDD" width="70"><a href="engine.php?spec=menus">edit
                  menus</a></td>
                <td bgcolor="#AAAAAA">&nbsp;</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table><p>

</body>
</html>
