 <?

  include("paging_class.php");

  include("conn.php");



global $id, $action, $nid, $n_name, $type, $former, $host, $username, $password, $database;

$paging=new paging(10,5);

$paging->db("$host","$username","$password","$database");





 echo "

<table width='100%' border='0' cellpadding='0' cellspacing='0'>

 <tr> 

    <td align='center' height = '4'></td>

  </tr>

  

  <tr>

    <td align='center'>"; 

      s_sponsors2($type,$id);

    echo "</td>

  </tr>

</table>";





if(!$action){



$icount = 1;



$paging->query("SELECT * FROM news WHERE id = '$id' ORDER BY dedate DESC,detime DESC");

$page=$paging->print_info();



  









 

  

  echo "

 

       <center>    







<table width='100%' border='0' cellpadding='0' cellspacing='0'>

<tr>

<td  class = 'newsbox1'>



  

  <table width='90%' border='0' cellpadding='1' cellspacing='1'>

<tr bgcolor='FFFFFF'>

<td width='100%' align = 'left'  class = 'leftform' bgcolor='#EAF3FD'>

<b>$n_name News</b>

</td>

</tr>

</table>

<Br>

";





echo " <table width='100%' border='0' cellpadding='0' cellspacing='0' >";



 //while($result = mysql_fetch_array($query9)) {

$row = 0;

while($result=$paging->result_assoc()) {

$nid = stripslashes($result["nid"]);

$itemtitle = stripslashes($result["itemtitle"]);

$heading = stripslashes($result["itemheading"]);

$today = stripslashes($result["today"]);

$title = substr($itemtitle, 0,70);

$img1 = stripslashes($result["img1"]);



if($img1 == "none"){

$path1 = "";

} else {

$path1 = "<img src='newsimages/$img1' width='75' height='75'>";

}





if($row == 0) {

echo "

 <tr >

 <td width='0%' valign='top' rowspan = '2'>$path1</td>

  <td width='99	%' align = 'left'  class = 'mytitle' ><b>$title<b></td>

</tr>



 <tr>

 <td width='100%' align = 'left' valign = 'top' class = 'myheading'>$heading..<a href='index.php?action=more&type=news&nid=$nid&id=$id' class = 'menunews'>more</a></td>

</tr>



 <tr >

  <td width='100%' align = 'right' colspan = '2'  class = 'newsdate'>$today</td>

</tr>







";



} else {



echo "<tr >

<td width='70%' align = 'left' colspan = '2' >&#8226;&nbsp;<a href='index.php?action=more&type=news&nid=$nid&id=$id' class = 'menunews' >$title</a></td>

 </tr>";



}//end if

  

  

  

  

  



 $row = $row + 1 ;

}// end while



echo "</table><br>













</td>

</tr>

</table>





";

//echo $paging->print_link();

echo "<div class = 'newsdate'>".$paging->print_link()."</div>";

echo "</center>";



} else {



if($action == "more"){



global $type, $former, $host, $username, $password, $database, $id;



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

$path1 = "<img src='newsimages/$img1'  >";

}





if ($former) {

$type = "content";

}//end if





}



?>



<table width='100%' border='0' cellpadding='1' cellspacing='1' >

  <tr bgcolor='FFFFFF' > 

    <td width="78%" valign = 'top' class = "frntbox12"> <div class = "displaynews"><b><?=$itemtitle?></b></div><br>

     <span class="floatimgleft"> <?=$path1?></span> 

     

    

      <br> 

      <?=$heading?>

      <?=$content?>

    </td>

    <td width="22%" valign = 'top' bgcolor="FFFFFF" class = "frntbox13"><table width="100%" border="0" cellpadding="0" cellspacing="0">

        <tr> 

          <td height="23"><div align="center"><img src="images/arrow.gif" width="5" height="5"> 

              <font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="index.php?id=<?=$id?>&type=news">More 

              news </a> </font> </div></td>

        </tr>

      </table>

      <table width="100%" border="0" cellspacing="0" cellpadding="0">

        <tr> 

          <td align="center"> 

            <? saud($nid,$type);?>

            </td>

        </tr>

      </table>

      <p>&nbsp;</p></td>

</table>





<?

}

}

 ?>

