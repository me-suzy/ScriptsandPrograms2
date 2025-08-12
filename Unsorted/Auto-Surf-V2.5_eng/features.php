<?php
require('./prepend.inc.php');
?>

<?
include("./templates/main-header.txt");
?>


<br><font size="3"><b>Features:</b><br><br></center>
<li>Sign up bonus: <? echo "$points_register"; ?> visits
<li>Ratio 10:<? echo "$viewpoints"; ?> This means <? echo "$endratio"; ?>% more traffic on your website
<li>Bonus for active users: Between <? echo "$myrow20[0]"; ?> and <? echo "$myrow20[1]"; ?> bonus visits each time you login
<li>Each time you click on an advertiser's banner you'll recieve <? echo "$myrow20[2]"; ?> - <? echo "$myrow20[3]"; ?> bonus visits
<li>You'll recieve <? echo "$refpointsc"; ?>% of your referrals' points
<li>When one of your referrals earns <? echo "$points_referer_jackpot_views"; ?> points, you'll recieve <? echo "$points_referer_jackpot_points"; ?> bonus visits
<li>Announcing a cheater to the admin: <? echo "$points_report"; ?> bonus visits </li>
<li>You can also change your earned visits into bannerviews in the surfbar.<br>&nbsp;&nbsp;&nbsp; For each visit you can get <? echo "$tausch"; ?> bannerviews.</li>

<?
include("./templates/main-footer.txt");
?>