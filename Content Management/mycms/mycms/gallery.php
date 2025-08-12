 <?
 include("paging_class.php");
  include("conn.php");

global $id, $action, $gid, $n_name, $type;


if(!$action){


global $id,$host, $username, $password, $database;

$paging=new paging(6,5);
$paging->db("$host","$username","$password","$database");


//$sql9 = "SELECT * FROM news WHERE id = '$id'";
//$query9 = mysql_query($sql9) or die("Cannot query the database.<br>" . mysql_error());

$paging->query("SELECT * FROM gallery WHERE id = '$id' ORDER BY dedate DESC,detime DESC");
$page=$paging->print_info();

echo "


<table width='490' border='0' cellpadding='0' cellspacing='0'>
  <tr>
    <td>"; 
      s_sponsors2($type,$id);
    echo "</td>
  </tr>
</table>


<table width='490' border='0' cellpadding='0' cellspacing='0'>
<tr>
<td bgcolor='#EAF3FD' class = 'djbox'>


<center>
<table width='430' border='0' cellpadding='0' cellspacing='0'>
<tr bgcolor='#EAF3FD'>
<td width='45%' align = 'left' valign = 'top' height= '30' class = 'leftform' >
<b>$n_name</b>
</td>
</tr>
</table>";

echo " <table width='430' border='0' cellpadding='0' cellspacing='0' >";
echo "<tr  >";

 //while($result = mysql_fetch_array($query9)) {
$row = 0;
$icount = 1;
while($result=$paging->result_assoc()) {
$gid = stripslashes($result["gid"]);
$itemtitle = stripslashes($result["title"]);
$mimage = stripslashes($result["m_image"]);
$title = substr($itemtitle, 0,30);

if($mimage == "none"){
$path1 = "";
} else {
$path1 = "<img src='galimages/$mimage' width='115' height='86'>";
}


//if($icount == 2) {
if(($icount % 2) == 0) {

$brk = "</tr><tr>";
 //echo $row;
}else {
$brk = "";
}

echo "<td width='33%' align = 'center' valign = 'middle' class = 'leftform2' bgcolor='#EAF3FD'>$path1<br>$title <br><a href='index.php?action=more&type=gallery&gid=$gid&id=$id'>more</a></td>";
echo $brk;


 $row = $row + 1 ;
  $icount = $icount + 1 ;
}// end while

echo "</tr></table><br>";
echo "<div class = 'newsdate'>".$paging->print_link()."</div>";
echo "</center>


</td>
</tr></table><br><br>";


} else {

if($action == "more"){


global $type;

$sql2 = "SELECT * FROM gallery WHERE gid = '$gid'";
$query2 = mysql_query($sql2) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query2)) {
$itemtitle = stripslashes($result["title"]);
$mimage = stripslashes($result["m_image"]);
$s_image1 = stripslashes($result["s_image1"]);
$s_image2 = stripslashes($result["s_image2"]);
$s_image3 = stripslashes($result["s_image3"]);
$heading = stripslashes($result["heading"]);
$content = stripslashes($result["content"]);

if($mimage == "none"){
$path1 = "";
} else {
$path1 = "<img src='galimages/$mimage' >";
}

if($s_image1 == "none"){
$path2 = "";
} else {
$path2 = "<img src='galimages/$s_image1' >";
}

if($s_image2 == "none"){
$path3 = "";
} else {
$path3 = "<img src='galimages/$s_image2' >";
}


if($s_image3 == "none"){
$path4 = "";
} else {
$path4 = "<img src='galimages/$s_image3' >";
}

}//end while

?>


<center>
<table width="90%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td bgcolor="#EAF3FD" ><a href="index.php?id=<?=$id?>&type=<?=$type?>" class = "gallink" >Back 
        to <?=$n_name?></a>
		</td>
  </tr>
</table>
  <table width="90%" border="0" cellpadding="0" cellspacing="0" class = "galbox">
    <tr> 
      <td width="1%" valign = 'top'>&nbsp;</td>
      <td width="1%" valign="top">&nbsp; </td>
      <td width="98%" valign="top">
		<span class="floatimgleft"><?=$path2?> <br /><?=$itemtitle?></span>       
        
       
        <?=$heading?>
        <br> <br> 
        <?=$content?><br>
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td height="4"> 
              <?=$path3?>
            </td>
          </tr>
          <tr> 
            <td height="4"></td>
          </tr>
          <tr> 
            <td height="4"> 
              <?=$path4?>
            </td>
          </tr>
        </table> </td>
    </tr>
  </table>
</center>

<?

}
}

?>
