/*script written by Skorch the most extreme cliff jumper from http://12feetunder.com. This script is free and can be modified anyway you see fit. You can contact me, with ideas, new versions or help installing or customizing your script, through my website. if you like this script i would appreciate a link back to my site. I'm trying to stimulate a nautral linking campaign so pick your favorite page and hook it up with some anchor text and, if I'm lucky, a description so it has "content"*/


<?php 
include('include.php');
$id_me="select set_id from Ran_Img";
$do=mysql_query($id_me);
$num=mysql_num_rows($do);
$num--;
$id=rand(0,$num);
$sql="select * from Ran_Img where set_id=$id";
$get_sql=mysql_query($sql);
$array=mysql_fetch_assoc($get_sql);

/*This is the display info. Feel free to customize but leave the quotes(both single and double(escape double quotes with a /)*/
echo '<img class=/"'.$array['class'].'/" src=/"'.$url.$array['url'].'/" alt=/"'.$array['alt'].'/"><h1>'.$array['caption'].'</h1>';
?>