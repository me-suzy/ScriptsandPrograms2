<?php
/*script written by Skorch the most extreme cliff jumper from http://12feetunder.com. This script is free and can be modified anyway you see fit. You can contact me, with ideas, new versions or help installing or customizing your script, through my website. if you like this script i would appreciate a link back to my site. I'm trying to stimulate a nautral linking campaign so pick your favorite page and hook it up with some anchor text and, if I'm lucky, a description so it has "content"*/
?>

<h1>How quotes,aposhtrphes or any other HTML entities are entered, in any field, may result in an error. The string validate function is being devolped for V1.3! V 1.3 will also support multiple Image sets per page. It's currently limited to 1.</h1>
<div class="r">
<form name="Ran_Img" method="post" action="form.php">
 <p class="btl"><input type="text" name="size"></p>
 <p class="btl"><input type="text" name="url"></p>
 <p class="btl"><input type="text" name="alt"></p>
 <p class="btl"><input type="text" name="caption"></p>
<input type="submit" name="Submit" value="Insert"></p>
</form></div>
<div class="l">
<p class="btr">CSS class: </p>
<p class="btr">Img URL: </p>
<p class="btr">Alt Tag: </p>
<p class="btr">Caption: </p>
<p class="uk">I have no problems dumping data without any verification. This form script was meant to be used by Webmasters only! I also use phpmyadmin to add image rows.</p>
<p>Thanks for using my script! Powered by the <a href="http://12feetunder.com">Extreme Cliff Jumpers</a> at 12 Feet Under! Be sure to check out some death-defying thrills and chills courtesy of gravity and giant brass balls!</p>
</div>

<?php
include ('include.php');
$class=$_POST['size'];
$img=$_POST['url'];
$alt=$_POST['alt'];
$text=$_POST['caption'];
$put="INSERT INTO $table VALUES ('class=$class','url=$img_s','alt=$alt','caption=$text')";
$do=mysql_query($put) or die ("Image set not dumped in db: ".mysql_error();


?>