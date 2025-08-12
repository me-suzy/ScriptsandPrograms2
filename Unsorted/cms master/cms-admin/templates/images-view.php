<?php
require("../../cms-config.php");

//----- START INCLUDE COMMON LIBS --------------------------------------------
$dir_name = "$CFG->dir_root/cms-libs";
$dir = dir("$dir_name/");
$dir->read(); $dir->read();
while (($lib = $dir->read())) {
    require("$dir_name/$lib");
}
$dir->close();
//----- END INCLUDE COMMON LIBS ----------------------------------------------

//----- START INCLUDE ADMIN LIBS ---------------------------------------------
$dir_name = "$CFG->dir_admin/libs";
$dir = dir("$dir_name/");
$dir->read(); $dir->read();
while (($lib = $dir->read())) {
    require("$dir_name/$lib");
}
$dir->close();
//----- END INCLUDE ADMIN LIBS -----------------------------------------------

//----- START INIT CLASSES -----
$ServerVars = new ServerVars();
$Db = new DB($CFG->db_host, $CFG->db_name, $CFG->db_user, $CFG->db_pass);
$Db->connect();
$Session = new Session();
$Base = new Base();
$Auth = new Auth();
$Images = new Images();
$Lang_images = new LangImages();
$Lang = new Lang();
//----- END INIT CLASSES -----

$image["name"] = $ServerVars->GET["name"];
$image["size"] = getimagesize("$CFG->dir_root/cms-images/".$image["name"]);

?>

<html>
<head>
<title><?php echo $image["name"] ?></title>
<link rel=StyleSheet href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=koi8-r">
</head>

<body bgcolor=#ffffff text=#000000 link=#c80000 vlink=#c80000 alink=#c0c0c0 leftmargin=5 topmargin=5 marginwidth=5 marginheight=5>

<table width=100% height=100% cellpadding=0 cellspacing=0 border=0>
<tr><td valign=middle align=center>
<img src="/cms-images/<?php echo $image["name"] ?>" width=<?php echo $image["size"][0] ?> height=<?php echo $image["size"][1] ?> border=0 alt="<?php echo $image["name"] ?>">
</td></tr>
</table>
</body>
</html>
