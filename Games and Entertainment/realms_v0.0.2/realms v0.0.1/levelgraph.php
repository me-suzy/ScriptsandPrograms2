<?php
  header ("Content-type: image/png");
include("mysql.php");

function fromhex($string){
   GLOBAL $im;
   sscanf($string, "%2x%2x%2x", $red, $green, $this);
   return ImageColorAllocate($im,$red,$green,$this);
}

if(!$whatto){
         $whatto=level;
        }

$ammount=rand(1,50);
$rankings=9;

$tsel = mysql_query("select * from users where `rank`='Member' order by $whatto desc limit $rankings");
$i=1;
while ($top = mysql_fetch_array($tsel)) {
if($i<=$rankings){
$thing.="$top[$whatto]-";
$thingb.="$top[user]-";
$i++;
}
}
$email=$_COOKIE[email];
$pass=$_COOKIE[pass];
$stat = mysql_fetch_array(mysql_query("select * from users where email='$email' and pass='$pass'"));
$style = mysql_fetch_array(mysql_query("select * from styles where owner='$stat[id]'"));
$thing.="$stat[$whatto]";
$thingb.="You";

$style[back] = substr("$style[back]", 1);

            $values=explode("-","$thing");
            $valuesb=explode("-","$thingb");

            $columns  = count($values);

            $width = 300;
            $height = 300;

            $padding = 5;

            $column_width = $width / $columns ;

            $im        = imagecreate($width,$height);
            $this      = fromhex("aaaaaa");;
            $this_lite = fromhex("cccccc");
            $this_dark = fromhex("777777");
            $white     = fromhex("ffffff");
            $textcol     = fromhex("000000");


            imagefilledrectangle($im,0,0,$width,$height,$textcol);
            imagefilledrectangle($im,2,2,$width-2,$height-2,$white);



            $maxv = 0;

            for($i=0;$i<$columns;$i++)$maxv = max($values[$i],$maxv);

            for($i=0;$i<$columns;$i++)
            {
             $column_height = ($height / 100) * (( $values[$i] / $maxv) *100);
             $x1 = $i*$column_width+$padding;
             $y1 = $height-$column_height+$padding;
             $x2 = (($i+1)*$column_width)-$padding;
             $y2 = $height;
             $x3 = $x1;
             $x4 = $x3;
             $y3 = $y2-12;
             $y4 = $height-12;
             $y5 = $height-100;

             if($i==$rankings){
             $valuesb[$i]="You";
             $values[$i]="$stat[$whatto]";
                     }
             imagefilledrectangle($im,$x1,$y1,$x2,$y2,$this);
             imageline($im,$x1,$y1,$x1,$y2,$this_lite);
             imageline($im,$x1,$y2,$x2,$y2,$this_lite);
             imageline($im,$x2,$y1,$x2,$y2,$this_dark);
             imagestringup($im, 3, $x3, $y4, "$valuesb[$i]", $textcol);
             imagestringup($im, 3, $x3, $y5, "$values[$i]", $textcol);
            }


                        if($whatto==clicktot){
            imagestring($im, 5, 10, 10, "Clicks Total", $textcol);
            }elseif($whatto==clicktod){
            imagestring($im, 5, 10, 10, "Clicks Today", $textcol);
            }else{
            imagestring($im, 5, 10, 10, "$whatto", $textcol);
            }



            imagepng($im);
?>