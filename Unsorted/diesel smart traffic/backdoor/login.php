<?
  require("../conf/sys.conf");
  require("../lib/ban.lib");
  require("../lib/mysql.lib");

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

  $mem= f(q("select login, pswd from members where id='$id'"));
  header("Location: ../login.php?action=submit&login=$mem[login]&pswd=$mem[pswd]");

?>