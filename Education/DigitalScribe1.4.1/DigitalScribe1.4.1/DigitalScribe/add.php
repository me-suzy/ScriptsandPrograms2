<?
require("access.inc.php");

	$tempfile=$HTTP_POST_FILES['userfile']['tmp_name'];
	$file=$tempfile[0];


$blankfield = 0;
IF (!$HTTP_POST_VARS['stufirstname']) $blankfield++;
IF (!$HTTP_POST_VARS['stulastname'])  $blankfield++;
IF (!$HTTP_POST_VARS['title'])        $blankfield++;
IF (!$HTTP_POST_VARS['teacher'])      $blankfield++;
IF (!$HTTP_POST_VARS['project'])      $blankfield++;

IF ($blankfield > 0) {
$bad = "bad";
   header("Location:addstuwork2.php?teacher=$HTTP_POST_VARS[teacher]&bad=$bad");
                    }
ELSEIF (is_uploaded_file($file)) {

mysql_connect("$host","$login","$pass") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());
mysql_select_db("$db") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

$HTTP_POST_VARS['stuwork'] = strip_tags($HTTP_POST_VARS['stuwork']);
$HTTP_POST_VARS['stufirstname'] = strip_tags($HTTP_POST_VARS['stufirstname']);
$HTTP_POST_VARS['stulastname'] = strip_tags($HTTP_POST_VARS['stulastname']);
$HTTP_POST_VARS['teacher'] = strip_tags($HTTP_POST_VARS['teacher']);
$HTTP_POST_VARS['project'] = strip_tags($HTTP_POST_VARS['project']);
$HTTP_POST_VARS['title'] = strip_tags($HTTP_POST_VARS['title']);


	$basenameArray=$HTTP_POST_FILES['userfile']['name'];
	$basename=$basenameArray[0];

	$basename = eregi_replace(" ", "_", $basename);
	$basename = eregi_replace("%20", "_", $basename); 

    $upload_dir = "images";


        for ($i = 0; $i < count($file); $i++) {
            if ($file[$i] != "none") {
                $filename = explode(".", $basename);
                if ($filename[1] != "jpg" && $filename[1] != "gif" && $filename[1] != "jpeg" && $filename[1] != "JPG" && $filename[1] != "GIF" && $filename[1] != "JPEG") {
                    echo "File extension '${filename[1]}' not allowed.  Only .gif and .jpg files can be uploaded.";
                    exit;
                }

	$basename = eregi_replace(" ", "_", $basename);
	$basename = eregi_replace("%20", "_", $basename); 
                if (file_exists("$upload_dir/$basename")) {
                    $cnt = 2;
                    while (file_exists("$upload_dir/${filename[$i]}$cnt.${filename[1]}")) {
                        $cnt++;
                    }
                    $basename = "${filename[0]}$cnt.${filename[1]}";
	$basename = eregi_replace(" ", "_", $basename);
	$basename = eregi_replace("%20", "_", $basename); 
                    $filename = explode(".", $basename);
                }
               
move_uploaded_file ($file,"$upload_dir/$basename");
            
}

mysql_query("INSERT INTO ".$conf['tbl']['studentwork']." (stufirstname, stulastname, title,
stuwork, TID, active, filename, project) VALUES
('".addslash($HTTP_POST_VARS['stufirstname'])."','".addslash($HTTP_POST_VARS['stulastname'])."','".addslash($HTTP_POST_VARS['title'])."','".addslash($HTTP_POST_VARS['stuwork'])."','$HTTP_POST_VARS[teacher]','$HTTP_POST_VARS[active]','$basename','".addslash($HTTP_POST_VARS['project'])."')");

}
}
ELSE {

mysql_connect("$host","$login","$pass") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());
mysql_select_db("$db") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

$HTTP_POST_VARS['stuwork'] = strip_tags($HTTP_POST_VARS['stuwork']);
$HTTP_POST_VARS['stufirstname'] = strip_tags($HTTP_POST_VARS['stufirstname']);
$HTTP_POST_VARS['stulastname'] = strip_tags($HTTP_POST_VARS['stulastname']);
$HTTP_POST_VARS['teacher'] = strip_tags($HTTP_POST_VARS['teacher']);
$HTTP_POST_VARS['project'] = strip_tags($HTTP_POST_VARS['project']);
$HTTP_POST_VARS['title'] = strip_tags($HTTP_POST_VARS['title']);

mysql_query("INSERT INTO ".$conf['tbl']['studentwork']." (stufirstname, stulastname, title,
stuwork, TID, active, filename, project) VALUES
('".addslash($HTTP_POST_VARS['stufirstname'])."','".addslash($HTTP_POST_VARS['stulastname'])."','".addslash($HTTP_POST_VARS['title'])."','".addslash($HTTP_POST_VARS['stuwork'])."','$HTTP_POST_VARS[teacher]','$HTTP_POST_VARS[active]','','".addslash($HTTP_POST_VARS['project'])."')");

}
?>
<HTML><HEAD><TITLE>Your Work Was Added</TITLE>
<LINK REL ="stylesheet" HREF="style.css" TYPE="text/css">
<? 
include("header1.php");

$stuname=deslash($HTTP_POST_VARS['stufirstname']);
?>

<span class=title>Thank you <? echo "$stuname" ?>, your work has been added.</span>
<P><span class=title><A HREF=addstuwork.php>Add more work</A>.</span>

<?
include("footer.php");
?>

