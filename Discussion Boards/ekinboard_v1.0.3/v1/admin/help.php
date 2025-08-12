<?php
if($_userlevel != 3){

    die("<center><span class=red>You need to be an admin to access this page!</span></center>");

}



echo "<a href=\"index.php\"><img src=\"images/home_lnk.gif\" border=0 alt=\"\"></a><img src=\"images/spacer.gif\" border=0 alt=\"\" width=5><img src=\"images/help_title.gif\" border=0 alt=\"\"><p>";


    if ($_GET['help'] != '') { // If we have a file, go get the contents of it

        $_GET['help']=str_replace("\\", "", $_GET['help']);

        $_GET['help']=str_replace("/", "", $_GET['help']);

        $_GET['help']=str_replace(".", "", $_GET['help']);



        $page_contents=@file_get_contents("http://ekinboard.com/help/".$_GET['help'].".php");

    }

    if ($page_contents == '') { // If blank, get the index

        $page_contents=@file_get_contents("http://ekinboard.com/help/index.php");

    }


    echo $page_contents;

print_r($files);

?>