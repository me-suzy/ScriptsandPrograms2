<?

   if($mid == "") header("Location: index.php");
   else{
	require "conf/sys.conf";
	require "lib/mysql.lib";
	require "lib/ban.lib";

	include "tpl/top.ihtml";

	$db = c();


	$r = q("select * from members where login='$mid' and status=0");

        if(e($r)){
		   echo "<br><br><b><font size=4>Confirmation error!</font></b><br>";
          echo "We are sorry .. but your login name does not exists or you have already confirmed! ";
        } else {

	if($affid != ""){
		$refbonus=def_affi();
	 	if(!e(q("select id from members where id='$affid'")))
		{
		q("update members_credits set credits_num=credits_num+'$refbonus' where user_id='$affid'");
		q("INSERT INTO event (`id`, `sender`, `title`, `contents`, `type`, `user_id`, `credits`, `status`, `rdate`) VALUES ('', '$mid', ' New referral : $mid', 'A new member referred by you confirmed his account: $mid .<br> Referral bonus : $refbonus', 'refer', '$affid', '$refbonus', '1','".strtotime(date("d M Y H:i:s"))."')");
		};
  	    };

	  $member = f($r);
         if ($requireapproval) q("update members set status=2 where login='$mid' and status=0");
	else q("update members set status=1 where login='$mid' and status=0");

	  if(e(q("select id from members_policy where user_id='$member[id]'")))
	  q("insert into members_policy values('0','$member[id]','$newm_trusted','$newm_expirable','$newm_approval','$newm_free')");

	$cred = def_credits();
	$credits = $cred[1];

	 if(e(q("select id from  members_credits where user_id='$member[id]'")))
	 q("insert into members_credits values('','$member[id]','$credits')");

	  echo "<br><br><b><font size=4>Confirmation success!</font></b><br>";
	  echo "User login: $member[login]<br>Dear $member[fname] $member[lname], now you can access your account!<br><a href=".$ROOT_HOST."login.php>Click here to sign in</a>";

        }
        d($db);

	include "tpl/bottom.ihtml";
   }
?>