<?php
/////////////////////////////////////////////////////////
// Security check - Begin
/////////////////////////////////////////////////////////
if (isset($l) && !is_dir($int_path.'/languages/'.$l) && $l != "")
{
      include $int_path."/languages/default/default.php";
      include $int_path."/templates/header.php";
      $sql = "INSERT INTO ".$mysql_admin." (ip, sys, path, date) VALUES (INET_ATON('".ip()."'), '".$_ENV['HTTP_USER_AGENT']."', '".$_ENV['REQUEST_URI']."', NOW(''))";
      mysql_query($sql);
      $data=date("H:i:s d/m/Y", time() + $date_diff*60*60);

      echo $err_mes_top.W_INC_PERM.ip().W_LOG_WR.W_SSYSTEM.$_ENV['HTTP_USER_AGENT'].W_SDATE.$data.$err_mes_bottom;
      include $int_path."/templates/footer.php";
      die;
}
/////////////////////////////////////////////////////////
// Security check - End
/////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////
// Languages check - Begin
/////////////////////////////////////////////////////////
if ($l == "")
{
include $int_path."/languages/default/default.php";
}
else
{
include $int_path."/languages/".$l."/".$l.".php";
}
/////////////////////////////////////////////////////////
// Languages check - Begin
/////////////////////////////////////////////////////////
?>
