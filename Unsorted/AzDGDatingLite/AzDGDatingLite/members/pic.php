<?php
##############################################################################
# \-\-\-\-\-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/-/-/-/-/-/ #
##############################################################################
# AzDGDatingLite                Version 1.1.0                                 #
# Writed by                     AzDG (support@azdg.com)                      #
# Created 25/05/02              Last Modified 12/09/02                       #
# Scripts Home:                 http://www.azdg.com                          #
##############################################################################
include "../config.inc.php";
include "../templates/secure.php";
include "user.php";
include "../templates/header.php";
$sql = "SELECT * FROM $mysql_table WHERE user = '$username'";
$result = mysql_db_query($mysql_base, $sql, $mysql_link);
while ($i = mysql_fetch_array($result)) {
$imgname = $i[imgname];
$id = $i[id];
$img = $i[pic];
}
if ($page == update) {
if (isset($HTTP_POST_FILES['file1']['name'])) $file1_name = $HTTP_POST_FILES['file1']['name'];
	else $file1_name = "";
if (isset($HTTP_POST_FILES['file1']['size'])) $file1_size = $HTTP_POST_FILES['file1']['size'];
	else $file1_size = "";
if (isset($HTTP_POST_FILES['file1']['tmp_name'])) $file1_tmp = $HTTP_POST_FILES['file1']['tmp_name'];
	else $file1_tmp = "";
    
if (($file1_name == "")||($file1_size == "")||($file1_tmp == "")) {
echo $err_mes_top.$lang[50].$err_mes_bottom;
include "../templates/footer.php";
die;
}

      function getextension($filename)
      {
      	$filename 	= strtolower($filename);
	    $extension 	= split("[/\\.]", $filename);
	    $n 		= count($extension)-1;
	    $extension 	= $extension[$n];
	    return $extension;
        }

		$file_type 	= getextension($file1_name);
   		if( $file_type!="gif" && $file_type!="jpg" ){
 
        echo $err_mes_top.$lang[69].$err_mes_bottom;
        include "../templates/footer.php";
        die;
        }
        $MaxSize1000 	= $MaxSize*1000;

		if($file1_size > $MaxSize1000)
		{
        echo $err_mes_top.$lang[70].$err_mes_bottom;
        include "../templates/footer.php";
        die;
}
$time = time();
if (!empty($imgname))
{
// Delete file
unlink ($int_path."/members/uploads/".$imgname);
}

$dir = date("mY", $time);
if (!is_dir($int_path.'/members/uploads/'.$dir))
{
umask(0);
mkdir ("uploads/".$dir, 0777);
}
$fileb = date("dHis", $time);
$filee = rand(0, 999);
$fn = $fileb."-".$filee;

$pic = $url."/members/uploads/".$dir."/".$fn.".".$file_type;
$intpic = $dir."/".$fn.".".$file_type;
if(function_exists("is_uploaded_file"))
  {
  if(is_uploaded_file($HTTP_POST_FILES['file1']['tmp_name']))
	{
	if(move_uploaded_file($HTTP_POST_FILES['file1']['tmp_name'], $int_path."/members/uploads/".$intpic))
		{
		}
	}
}

$sql2 = "UPDATE $mysql_table SET imgtime='$time', imgname='$intpic', pic='$pic' WHERE user = '$username'";
mysql_db_query($mysql_base, $sql2, $mysql_link);
        echo $err_mes_top.$lang[186]."<br><br><input type=\"button\" value=\"".$lang[141]."\" class=input OnClick=\"location.href='index.php?l=".$l."&username=".$username."&password=".$password."'\">".$suc_mes_bottom;
        include "../templates/footer.php";
        die;
} else {
echo "<form action=pic.php?l=".$l."&page=update&username=$username&password=$password method=post enctype='multipart/form-data'><Table Border=\"1\" CellSpacing=\"0\" CellPadding=\"4\" bordercolor=black>
    <Tr>
		<Td Width=\"500\" align=center class=head bgcolor=".$color4." colspan=2>$lang[47]</Td>
	</Tr>";
if ($img != "")
{
echo "<Tr>
		<Td Width=\"500\" align=center class=dat colspan=2>$lang[48]</Td>
	</Tr><Tr>
		<Td Width=\"500\" align=center class=head colspan=2><img src='$img' border=0></Td>
	</Tr>";
}
echo "<Tr bgcolor=".$color3.">
		<Td Width=\"200\" align=center class=mes>$lang[49]</td><Td Width=\"300\" align=center class=mes><input class=input type=file name=file1></Td>
	</Tr><Tr>
		<Td Width=\"500\" align=right class=head colspan=2><input class=input type=submit value='$lang[47]'></Td>
	</Tr>
</table></form>";
}
include "../templates/footer.php";
?>
