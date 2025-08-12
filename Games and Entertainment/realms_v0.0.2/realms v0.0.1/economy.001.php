<?php
if($dowhat=="neweco"&&$user[position]=="Admin"){
        $totcash=0;
        $totplayers=0;
        $timenum=time();

        $psel=mysql_query("select * from `users` where `position`!='Admin'");
        while($p=mysql_fetch_array($psel)){
                $csel=mysql_query("select * from `characters` where `owner`='$p[id]'");
                while($c=mysql_fetch_array($csel)){
                        $totcash=$totcash+$c[cash];
                }
                $totplayers=$totplayers+1;
                $totcash=$totcash+$p[bank];
        }
        $avcash=$totcash/$totplayers;
        $avcash=round($avcash,2);

        mysql_query("INSERT INTO `economy` (`timenum` , `totcash` , `totplayers`)VALUES ('$timenum','$totcash','$totplayers')");
}

$timenum=time();
if(!$wayness){
        $wayness="desc";
}
if(!$limit){
        $limit=20;
}
print "<center><b><u>Economics</u></b></center><br><table border=1 cellspacing=2 cellpadding=2><tr><td>time</td><td>total cash</td><td>total users</td><td>average</td></tr>";
if($limit=="none"){
        $ecsel=mysql_query("select * from `economy` order by timenum $wayness");
}else{
        $ecsel=mysql_query("select * from `economy` order by timenum $wayness limit $limit");
}
while($ec=mysql_fetch_array($ecsel)){
        $sec=$timenum-$ec[timenum];
        $minago=0;
        $hrago=0;
        $dayago=0;
        while($sec>60){
                $sec=$sec-60;
                $minago=$minago+1;
        }
        while($minago>60){
                $minago=$minago-60;
                $hrago=$hrago+1;
        }
        while($hrago>24){
                $hrago=$hrago-24;
                $dayago=$dayago+1;
        }
        $avcash=$ec[totcash]/$ec[totplayers];
        $avcash=round($avcash,2);

        $ec[totcash]=number_format($ec[totcash]);
        $avcash=number_format($avcash);
        print "<tr><td>$dayago days, $hrago hours, $minago minutes, $sec seconds ago</td><td>$ec[totcash]</td><td>$ec[totplayers]</td><td>$avcash cash per user</tr>";
}
print "</table>";