<?
require("checkpass.php");
?>
<?

if ($HTTP_POST_VARS['mode'] == "upload") {
require("../access.inc.php");

	mysql_connect("$host","$login","$pass") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());

	mysql_select_db("$db") OR DIE
        ("There is a problem with the system.  Please notify your system administrator." .mysql_error());


	IF (!$HTTP_POST_VARS['image']) {}
	ELSE {
	unlink("../images/$HTTP_POST_VARS[image]");
     		}

	$tempfile=$HTTP_POST_FILES['userfile']['tmp_name'];
	$file=$tempfile[0];

		IF (is_uploaded_file($file)) {


		$basenameArray=$HTTP_POST_FILES['userfile']['name'];
		$basename=$basenameArray[0];

		$basename = eregi_replace(" ", "_", $basename);
		$basename = eregi_replace("%20", "_", $basename);


    		$upload_dir = "../images";


   		     for ($i = 0; $i < count($file); $i++) {
   		         if ($file[$i] != "none") {
   		             $filename = explode(".", $basename);
    		            if ($filename[1] != "jpg" && $filename[1] != "gif" && $filename[1] != "jpeg" && $filename[1] != "JPG" && $filename[1] != "GIF" && $filename[1] != "JPEG") {
                    echo "File extension '${filename[1]}' not allowd.  Only .gif and .jpg files can be uploaded.";
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


}
    $result = mysql_query("UPDATE ".$conf['tbl']['studentwork']." SET filename='$basename'
        WHERE id='$HTTP_POST_VARS[id]'") or die(mysql_error());

header("Location:indepthadmin.php?ID=$HTTP_POST_VARS[ID]&#$HTTP_POST_VARS[integer]");

exit;
}
    $result = mysql_query("UPDATE ".$conf['tbl']['studentwork']." SET filename=''
        WHERE id='$HTTP_POST_VARS[id]'") or die(mysql_error());

header("Location:indepthadmin.php?ID=$HTTP_POST_VARS[ID]&#$HTTP_POST_VARS[integer]");

}

ELSE {

echo "<HTML><HEAD><TITLE>Change Image</TITLE>";
echo "<LINK REL =\"stylesheet\" HREF=\"style.css\" TYPE=\"text/css\">";
include("../header1.php");

echo "<form ENCTYPE='multipart/form-data' action=newimage.php method=POST>";
echo "<BR>Leave blank to remove an image.<br>";
echo "Your Image must be saved as a .jpg or a .gif to work.";
echo "<BR>Image:<input type=FILE size=25 maxlength=500 name='userfile[]' VALUE=''>";
echo "<br>";
echo "<INPUT TYPE=HIDDEN NAME=id VALUE=$HTTP_GET_VARS[id]>";
echo "<INPUT TYPE=HIDDEN NAME=ID VALUE=$HTTP_GET_VARS[ID]>";
echo "<INPUT TYPE=HIDDEN NAME=integer VALUE=$HTTP_GET_VARS[integer]>";
echo "<INPUT TYPE=HIDDEN NAME=mode VALUE=upload>";
echo "<INPUT TYPE=HIDDEN NAME=image VALUE=$HTTP_GET_VARS[image]>";
echo "<input type=submit value=Submit>";
echo "</FORM>";
include("../footer.php");

}
?>