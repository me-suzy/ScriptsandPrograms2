<?php
   if($user[position]=="Guest"){
   print"Guests cannot enter.";
   return;
   }

if ($REQUEST_METHOD == "POST")
{
    $uploaddir = "./entries/";
    $pext = getFileExtension($imgfile_name);
    $pext = strtolower($pext);
    if (($pext != "jpg")  && ($pext != "jpeg")  && ($pext != "gif")  && ($pext != "png"))
    {
        print "<h1>ERROR</h1>Image Extension Unknown.<br>";
        print "The file you uploaded had the following extension: $pext</p>\n";
    return;
    }
    if ($imgfile_size=="0")
    {
        print "<h1>ERROR</h1> Filesize too big or file mismatch<br>";
    return;
    }

    $new_w=150;
    $new_h=150;

    print"<pre>

    * original filename           : $imgfile_name
    * size of uploaded file       : $imgfile_size
    * mime-type of uploaded file  : $imgfile_type
    * file extension              : .$pext
    </pre>";
    if ($pext==jpeg){
    $src_img = imagecreatefromjpeg($imgfile);
    }elseif ($pext==jpg){
    $src_img = imagecreatefromjpeg($imgfile);
    }elseif ($pext==png){
    $src_img = imagecreatefrompng($imgfile);
    }elseif ($pext==gif){
    $src_img = imagecreatefromgif($imgfile);
    }else{
    print"<BR>Something went wrong<br>";
    }


    $old_x = imageSX($src_img);
    $old_y = imageSY($src_img);
        if ($old_x > $old_y) {
                $thumb_w2 = $new_w;
                $thumb_h2 = $old_y * ($new_h/$old_x);
        }
        if ($old_x < $old_y) {
                $thumb_w2 = $old_x * ($new_w/$old_y);
                $thumb_h2 = $new_h;
        }
        if ($old_x == $old_y) {
                $thumb_w2 = $new_w;
                $thumb_h2 = $new_h;
        }
        $thumb_w = $new_w;
        $thumb_h = $new_h;

        $dst_img = ImageCreateTrueColor($new_w, $new_h);
        imagefill($dst_img, 0, 0, 0xffffff);

        imagecopyresampled($dst_img, $src_img, $new_w/2-$thumb_w2/2, $new_h/2-$thumb_h2/2, 0, 0, $thumb_w2, $thumb_h2, $old_x, $old_y);

        $gogoname = explode(".",$imgfile_name);
        $frofroname = $gogoname[0];

        $currentcont = getcontestnumber();
        $imagenumber = $currentcont;
        $imagenumber.= "_";
        $imagenumber.= $user[id];
        $imagethumb = $imagenumber;
        $imagethumb.= ".thumb.jpg";
        $imagename = $imagenumber;
        $imagename.= ".";
        $imagename.= $pext;

        $dstfilename = $uploaddir . $imagethumb;
        $srcfilename = $uploaddir . $imagename;


        //imagejpeg($src_img, $srcfilename, 80);

if (!copy($imgfile, $srcfilename)) {
   print "<br>failed to copy $file...<br>\n";
}else{

imagejpeg($dst_img, $dstfilename, 80);


$entered = mysql_fetch_array(mysql_query("select * from contest_entries where user=$user[id] and contest=$currentcont"));
  if(!$entered[id]){
  mysql_query("INSERT INTO `contest_entries` (`user`,`contest`,`filename`,`thumbnail`)
                VALUES
                ('$user[id]','$currentcont','$srcfilename','$dstfilename')") or die("<br>Could not register.");
  }else{
  mysql_query("update contest_entries set `filename`='$srcfilename' where id=$entered[id]");
  mysql_query("update contest_entries set `thumbnail`='$dstfilename' where id=$entered[id]");
  }

}

        print"<table><tr><td><img src=\"$srcfilename\"></td><td><font size=10>&raquo;</font></td><td><img src=\"$dstfilename\"></td></tr></table>";
        imagedestroy($dst_img);
        imagedestroy($src_img);

    }





?>