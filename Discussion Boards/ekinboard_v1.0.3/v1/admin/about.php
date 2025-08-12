<?PHP
if($_userlevel != 3){

    die("<center><span class=red>You need to be an admin to access this page!</span></center>");

}



echo "<a href=\"index.php\"><img src=\"images/home_lnk.gif\" border=0 alt=\"\"></a><img src=\"images/spacer.gif\" border=0 alt=\"\" width=5><img src=\"images/about_title.gif\" border=0 alt=\"\"><p>";


    if (isset($_GET['d'])) { // If we have a file, go get the contents of it

        $_GET['d']=str_replace("\\", "", $_GET['d']);

        $_GET['d']=str_replace("/", "", $_GET['d']);

        $_GET['d']=str_replace(".", "", $_GET['d']);



        $page_contents=@file_get_contents("http://ekinboard.com/about/".$_GET['d'].".php");

    }

    if ($page_contents == '') { // If blank, get the index

        $page_contents=@file_get_contents("http://ekinboard.com/about/index.php");

    }


    echo $page_contents;

print_r($files);

?>