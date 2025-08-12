<?
   header("Content-Type: text/plain");
   echo "Policy script checker.\n";
   echo "-------------------------------\n\n";

   require("../conf/sys.conf");
   require("bots/errbot");
   require("bots/mcbot");

   echo "Reading accounts information ..\n\n";  
   $db = con_srv();

   $r = _query("select * from members");

   $c = 0;

   while($f = _fetch($r)){
     $policies = _query("select * from members_policy where user_id='$f[id]'");

     if($f[status] == 0 && strtotime(date("d M Y")) > ($f[rdate] + 7*4*24*60*60)){
	echo "User $f[login] ($f[lname] $f[fname]) will be deleted. Not confirmed account in 1 month.";
	_query("delete from members where id='$f[id]'");
	$c++;
      }

        if(!_empty($policies)){
  		$py = _fetch($policies);
	$camps = _query("select * from campaigns where user_id='$f[id]'");

		while($camp = _fetch($camps)){
			$log = @_fetch(_query("select * from logs where user_id='$camp[id]'"));
			$prev = @_fetch(_query("select * from prev where cid='$camp[id]'"));

			if($py[free] && strtotime(date("d M Y")) > ($log[idate] + 7*4*24*60*60) && $prev[prev_number] < 1){
				echo "Campaign will be deleted. Account has free status and there is no activity\n";
				_query("delete from cmpaigns where id='$camp[id]'");
				_query("delete from clicks where cid='$camp[id]'");
				_query("delete from previews where cid='$camp[id]'");
				_query("delete from prev where cid='$camp[id]'");
				$c++;
			}
			if($py[expirable] && strtotime(date("d M Y")) > ($log[idate] + 7*4*24*60*60) && $prev[prev_number] < 1){
				echo "Campaign will be deleted. Account has expirable status and there is no activity\n";
				_query("delete from cmpaigns where id='$camp[id]'");
				_query("delete from clicks where cid='$camp[id]'");
				_query("delete from previews where cid='$camp[id]'");
				_query("delete from prev where cid='$camp[id]'");
				$c++;
			}
		}	

	}
   }

   echo "-------------------------------";
   if(!$c) echo "\nNo items found!\n";
   else echo "\nTotal $c item(s) found.\n";
	
   dc_srv($db);
?>