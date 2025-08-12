 <?
  include("paging_class.php");
  include("conn.php");

global $id, $action, $nid, $n_name, $type;
$paging=new paging(10,5);
$paging->db("localhost","text101","nokia2210","text101_insaka");


if(!$action){


$paging->query("SELECT * FROM news WHERE id = '$id' ORDER BY dedate DESC,detime ASC");
$page=$paging->print_info();

  echo "<br><br><br>$n_name<br>
  
  <table width='90%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE' >
<tr bgcolor='FFFFFF'>
<td width='70%' align = 'right'  class = 'leftform' bgcolor='#E7FCFE'>
Record $page[start] - $page[end] of $page[total] ( total $page[total_pages] pages )
</td>
</table>";


echo " <table width='90%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE' >";

 //while($result = mysql_fetch_array($query9)) {
$row = 0;
while($result=$paging->result_assoc()) {
$nid = stripslashes($result["nid"]);
$itemtitle = stripslashes($result["itemtitle"]);
$heading = stripslashes($result["itemheading"]);
$today = stripslashes($result["today"]);

$title = substr($itemtitle, 0,70);

echo "<tr bgcolor='FFFFFF'>
<td width='70%' align = 'left' colspan = '2' class = 'leftform' bgcolor='#E7FCFE'><b>$title<b></td>
 </tr>
 <tr>
 <td width='70%' align = 'left' colspan = '2'  class = 'leftform' bgcolor='#ffffff'>$heading</td>
</tr>
 <tr>
 <td width='70%' align = 'left'  class = 'leftform' bgcolor='#ffffff'><a href='index.php?action=more&type=news&nid=$nid&id=$id'>more</a></td>
 <td width='70%' align = 'right'  class = 'newsdate' bgcolor='#ffffff'>$today</td>
</tr>

  ";

 $row = $row + 1 ;
}// end while

echo "</table><br>";
echo $paging->print_link();


} else {

if($action == "more"){

global $type;

$sql2 = "SELECT * FROM news WHERE nid = '$nid'";
$query2 = mysql_query($sql2) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query2)) {
$itemtitle = stripslashes($result["itemtitle"]);
$heading = stripslashes($result["itemheading"]);
$content = stripslashes($result["content"]);
$img1 = stripslashes($result["img1"]);


if($img1 == "none"){
$path1 = "";
} else {
$path1 = "<img src='newsimages/$img1'>";
}





}

?>
<br><br>
<table width='90%' border='0' cellpadding='1' cellspacing='1' >
  <tr bgcolor='FFFFFF'> 
    <td height="21" colspan="2" valign = 'top' bgcolor="#E7FCFE" class = "leftform"><p>&gt;&gt;<a href="index.php?id=<?=$id?>&type=<?=$type?>">Back 
        to <?=$n_name?></a></p>
      </td>
  
  <tr bgcolor='FFFFFF'> 
    <td valign = 'top'> 
      <?=$path1?>
    </td>
    <td valign = 'top'> 
      <?=$itemtitle?>
      <br> 
      <?=$heading?>
    </td>
  <tr bgcolor='FFFFFF'> 
    <td colspan="2" valign = 'top'> 
      <?=$content?>
    </td>
</table>


<?
}
}












 ?>
