<?

$dbh=mysql_connect ("localhost", "ebuilder_shop", "30-245346") or die ('I cannot connect to the database.');
mysql_select_db ("ebuilder_shop");

$uploadsrel = "../../graphics"; // Relative uploads Dir
$uploadsrel2 = "../../graphics/"; // Relative uploads Dir with a forward /
$uploadsurl = "http://www.ebuilders.ws/demos/shop/graphics/";// uploads url
$uploadscript = "http://www.www.ebuilders.ws/demos/shop/admin/jscript/upload2.php"; //uploads scripts
$poolurl = "http://www.www.ebuilders.ws/demos/shop/graphics"; //pool url without the forward /
$filesize = "50000"; // file size in k bites
$perpage = "3"; // products per page
$qbpath = "jscript"; //editor url
$qbpathjs = "jscript/quickbuild.js"; //editor js url
$qbpathtable = "jscript/tabedit.js"; //table editor js url
//Secure URL
$secureurl = "http://www.ebuilders.ws/demos/shop";
//home
$homepage = "http://www.www.ebuilders.ws/demos/shop";
?>
