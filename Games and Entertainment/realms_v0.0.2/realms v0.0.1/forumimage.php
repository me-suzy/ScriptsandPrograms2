<?php
header ("Content-type: image/png");
include("mysql.php");

function fromhex($string){
   GLOBAL $im;
   sscanf($string, "%2x%2x%2x", $red, $green, $this);
   return ImageColorAllocate($im,$red,$green,$this);
}

if(!$id){
         $id=2;
}

$username=$_COOKIE[username];
$pass=$_COOKIE[pass];
$user = mysql_fetch_array(mysql_query("select * from users where username='$username' and password='$pass'"));
if(empty($user[username])||!$user[username]||!isset($user[username])){
$username="guest";
$pass="guest";
$user = mysql_fetch_array(mysql_query("select * from users where username='$username' and password='$pass'"));
}

$stat = mysql_fetch_array(mysql_query("select * from characters where id='$user[activechar]'"));

$target = mysql_fetch_array(mysql_query("select * from users where id=$id"));
$targetb = mysql_fetch_array(mysql_query("select * from characters where owner=$id"));






         if($user[template]!="darkrealmsie"){
         $style[admin]=FF0000;
         $style[text]=000000;
         $style[back]=FFFFFF;
         }else{
         $style[admin]=FF0000;
         $style[text]=FFFFFF;
         $style[back]=000000;
         }



            $width=120;
            $height=250;
            $im        = imagecreate($width,$height);

            $this      = fromhex("aaaaaa");
            $this_lite = fromhex("cccccc");
            $this_dark = fromhex("777777");
            $white     = fromhex("$style[back]");
            $black     = fromhex("$style[text]");
            $admin     = fromhex("$style[admin]");



            imagefilledrectangle($im,0,0,$width,$height,$white);







       if($target[position]==Admin){
                        $word=admin;

        }else{
                        $word=black;
                }
                imagestring($im, 4, 2, 2, "$target[username]($target[id])", $$word);
            imagestring($im, 3, 2, 14, "Char: $targetb[name]", $$word);
            imagestring($im, 3, 2, 26, "Posts: $target[forumposts]", $$word);

            imagefilledellipse($im,70,95,80,80,$black);
            imageline($im,70,130,70,210,$black);
            imageline($im,50,175,90,160,$black);
            imageline($im,70,210,85,235,$black);
            imageline($im,70,210,55,235,$black);


            imageline($im,71,131,71,211,$black);
            imageline($im,51,176,91,161,$black);
            imageline($im,71,211,86,236,$black);
            imageline($im,71,211,56,236,$black);


            imageline($im,72,132,72,212,$black);
            imageline($im,52,177,92,162,$black);
            imageline($im,72,212,87,237,$black);
            imageline($im,72,212,57,237,$black);


            imageline($im,73,133,73,213,$black);
            imageline($im,53,178,93,163,$black);
            imageline($im,73,213,88,238,$black);
            imageline($im,73,213,58,238,$black);

            imageline($im,74,134,74,214,$black);
            imageline($im,54,179,94,164,$black);
            imageline($im,74,214,89,239,$black);
            imageline($im,74,214,59,239,$black);



            imagefilledellipse($im,75,100,80,80,$$word);
            imageline($im,75,135,75,215,$$word);
            imageline($im,55,180,95,165,$$word);
            imageline($im,75,215,90,240,$$word);
            imageline($im,75,215,60,240,$$word);

                      imagefilledellipse($im,75,100,78,78,$white);



            imagepng($im);
?>