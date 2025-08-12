<?php

/*----------------[      Instant Access Code Generator (GD/PHP)      ]---------------*/

/*                                                                                   */

/*   This PHP4 script program is written by Infinity Interactive. It could not be,   */

/*  copied, modified and/or reproduced in any form let it be private or public       */

/*  without the appropriate permission of its authors.                               */

/*                                                                                   */

/*  Date    : 14.5.2002                                                              */

/*  Authors : Svetlin Staev (svetlin@developer.bg), Kiril Angov (kirokomara@yahoo.com) */

/*                                                                                   */

/*              Copyright(c)2002 Infinity Interactive. All rights reserved.          */

/*-----------------------------------------------------------------------------------*/

include("prepend.php3");

$db = new DB_Credigold();

function randomPass($length = 7) {



     // all the chars we want to use

    $all = explode( " ",

            "A B D E F H K L M N P Q R S T W X Y Z "

                 . " 1 2 3 4 5 6 7 8 9");



    for($i=0;$i<$length;$i++) {

        srand((double)microtime()*700000000);

        $randy = rand(0, 28);

        $pass .= $all[$randy];

    }



    return $pass;

}

$code = randomPass("7");

$now = date("YmdHis", time());

$db->query("DELETE FROM access_codes WHERE user_id='".session_id()."'");

$db->query("INSERT INTO access_codes SET user_id='".session_id()."',

            access_code='".$code."', changed='".$now."'");



Header("Content-type: image/png");

$width  = 100;

$height = 20;

$im     = ImageCreateFromJpeg("images/access_code.jpg");

$black  = ImageColorAllocate($im, 0,0,0);

ImageString($im, 10, 101, 6, $code, $black);

ImagePNG($im);

ImageDestroy($im);



?>

