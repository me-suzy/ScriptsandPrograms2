<?

ob_start();
include("conn.php");
include("cms_menupos.php");
include("cms_news.php");
include("cms_gallery.php");
include("paging_class.php");


//.........include for pics demensions





//----------- show menu function

function dmenu() {

$sql = "SELECT * FROM menu WHERE deleted='0'";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {
$id = stripslashes($result["id"]);
$name = stripslashes($result["name"]);
$type = stripslashes($result["type"]);


echo "<a href='main.php?menuid=$id&type=$type'>$name</a>";
echo "<br>";

} // end while

}// end function




//----------- show common menu

function commenu($menuid){

}


//add category

 function addcat($name,$placement){
  if(!$name) {

  echo "
<form name='input' action='addcat.php' method='post' >

 <table width='90%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>
 <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Category Name</td>
 <td width='75%' align = 'left'><input type='text' name='name' style='background-color:#FFFFD7' size= '45'></td>
  </tr>

  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Category Placement</td>
        <td width='75%' align = 'left' bgcolor='#EAFDEB'><select name='placement'>
            <option selected>Primary</option>
            <option>Secondary</option>
            <option>Tertiary </option>
			<option>Quaternary </option>
</select></td>
  </tr>";

  menupos4();

echo "<tr bgcolor='FFFFFF'>
 <td width='100%' colspan = '2' align = 'center'><input type='submit' value='Add Category'></td>
  </tr>
</form>
</table>";

  }else {

 global $change, $menu, $position;
 
  $position = cmenupos2($change,$position,$menu);


$sql6 = "INSERT INTO category SET  name = '$name', placement = '$placement', position = '$position'";
$query6 = mysql_query($sql6) or die("Cannot query the database.<br>" . mysql_error());





echo "<table width='90%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>
 <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Category Name</td>
 <td width='75%' align = 'left'>$name</td>
  </tr>

  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Category Placement</td>
        <td width='75%' align = 'left' bgcolor='#EAFDEB'>$placement</td>
  </tr>

 <tr bgcolor='FFFFFF'>
 <td width='100%' colspan = '2' align = 'center'>
  <form method='post'>
    <div align='center' valign = 'middle'>
      <input type='button' value='Close Window' onclick='window.close()'>
    </div>
  </form>


 </td>
  </tr>

</table>

";




      } //endif

 }// end function



//------------add page

function addmenu($name, $type,$id, $placement, $enabled, $news, $poll, $continue, $cat ) {

if(!$name) {


$sql3 = "SELECT * FROM category ORDER BY position ASC";
$query3 = mysql_query($sql3) or die("Cannot query the database.<br>" . mysql_error());

$sql2 = "SELECT * FROM menu WHERE deleted='0' and type = 'news' ORDER BY catposition ASC, position ASC ";
$query2 = mysql_query($sql2) or die("Cannot query the database.<br>" . mysql_error());



echo " <table width='90%' border='0'  >
 <tr>
 <td width='100%' align = 'left' class='cat' >
<form name='input' action='addmenu.php' method='post' >

<table width='100%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>
 <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Menu Name</td>
 <td width='75%' align = 'left'><input type='text' name='name' style='background-color:#FFFFD7' size= '45'></td>
  </tr>
 <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Page Type</td>
        <td width='75%' align = 'left' bgcolor='#EAFDEB'><select name='type'>
            <option selected>content</option>
            <option>news</option>
            <option>gallery</option>
            <option>contact</option>
          </select></td>
  </tr>";

 //<tr bgcolor='FFFFFF'>
 //<td width='25%' align = 'left' class = 'leftform'>Menu Placement</td>
 //       <td width='75%' align = 'left' bgcolor='#EAFDEB'> Placement:
 //         <select name='placement' >
 //           <option value='Primary' selected>Primary
 //           <option value='Secondary'>Secondary
 //           <option value='Tertiary'>Tertiary
 //         </select> </td>
 // </tr>


echo "<tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Show on Menu</td>
 <td width='75%' align = 'left'>Yes:<input type='radio' name='enabled' value ='yes' checked >&nbsp; No:<input type='radio' name='enabled' value ='no'  > </td>
  </tr>
<tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Show News Summary</td>
 <td width='75%' align = 'left' bgcolor='#E7FCFE'>
 Yes:<input type='radio' name='news' value ='yes'>&nbsp; No:<input type='radio' name='news' value ='no' CHECKED> &nbsp; (if yes which news page)
 
 <select name='showsum'>";

      while($result = mysql_fetch_array($query2)) {
           $name = stripslashes($result["name"]);
            $pid = stripslashes($result["id"]);
        echo "<option>$name</option>";
      }
       echo     "<option>all_news_pages</option> 
	             </select>
 

  </td>
  </tr>
<tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Show Poll No.</td>
 <td width='75%' align = 'left'><input type='text' name='poll' value = '0'>
           (show poll number, 0 for <b>No</b> polls) </td>
  </tr>";

  menupos2();

  echo "<tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Menu Category</td>
        <td width='75%' align = 'left'><select name='cat'>";

      while($result = mysql_fetch_array($query3)) {
           $name = stripslashes($result["name"]);

        echo "<option>$name</option>";
      }
       echo     "</select></td>
  </tr>
  </table>
   <br>";

echo "</td>
 <tr>
<td width='100%' height ='4'  colspan = '2'></td>
  </tr>
<tr bgcolor='FFFFFF'>
 <td width='100%' colspan = '2' align = 'center'><input type='submit' value='continue'></td>
  </tr>
</form>
</td>
</table>";

}else{

global $change, $menu, $position, $showsum;
//entry posted

$position = cmenupos($change,$position,$menu);


if( ($type == "gallery")||($type == "news") ||($type == "interview") || ($type == "competition") || ($type == "contact") ||($type == "registration") ) {

$news = "no";
}// end if


$catpos = catpos($cat);
$catplc = catplace($cat);

if($news = "no") {
$showsum = "[no pages]";
}

$sql6 = "INSERT INTO menu SET  pid ='0',  name = '$name', cat ='$cat',type = '$type', placement = '$catplc', poll ='$poll', news = '$news',show_n_page ='$showsum', enabled = '$enabled',position = '$position', catposition = '$catpos'";
$query6 = mysql_query($sql6) or die("Cannot query the database.<br>" . mysql_error());

$lid = mysql_insert_id();

echo "<form name='input' action='addmenupost.php' method='post' enctype='multipart/form-data'>";
echo "<table width='90%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>
 <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Menu Name</td>
 <td width='75%' align = 'left'>$name</td>
  </tr>
 
 <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Page Type</td>
 <td width='75%' align = 'left'>$type</td>
  </tr>
  
   <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left' class = 'leftform'>Menu Category</td>
 <td width='75%' align = 'left'>$cat</td>
  </tr>

 <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left' class = 'leftform'>Menu Placement</td>
 <td width='75%' align = 'left'><b>$catplc</b></td>
  </tr>

<tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Show on Menu</td>
 <td width='75%' align = 'left'>$enabled </td>
  </tr>

<tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>News Summary</td>
 <td width='75%' align = 'left'>$news (news summary)</td>
  </tr>

<tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Show Poll No.</td>
 <td width='75%' align = 'left'>$poll (Show poll number )</td>
  </tr>
</td>
</table>
<br>";

showbottom($type,$lid);



}// end if

}// end function




//------------add page show bottom

function showbottom($type,$lid) { 
switch ($type) {
case 'content':


echo "<table width='90%'  border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>";
echo "<tr bgcolor='FFFFFF'><td width='25%' align = 'left'  class = 'leftform'>Main Title</td>";
echo "<td width='75%' align = 'left'><input type='text' name='title' value = '$title' style='background-color:#FFFFD7' value = ''size=45></td>";
echo " </tr>";
echo "<tr bgcolor='FFFFFF'><td width='25%' align = 'left'  class = 'leftform'>Upload Image 1</td>";
echo "<td width='75%' align = 'left'><input type= 'file' name='img1' size='30'></td>";
echo " </tr>";
echo "<tr bgcolor='FFFFFF'><td width='25%' align = 'left'  class = 'leftform'>Upload Image 2</td>";
echo "<td width='75%' align = 'left'><input type= 'file' name='img2' size=30'></td>";
echo " </tr>";

echo "<tr bgcolor='FFFFFF'><td width='25%' align = 'left'  class = 'leftform'>Upload Image 3</td>";
echo "<td width='75%' align = 'left'><input type= 'file' name='img3' size=30'></td>";
echo " </tr>



  <tr bgcolor='CCCCCC'> 
    <td width='25%' align = 'left'  class = 'leftform'>Image 1-Link to address</td>
    <td width='75%' colspan='2' align = 'left'><input type='text' name='lnk1' value = '$lnk1'  style='background-color:#FFFFD7' size=30></td>
  </tr>
  

  <tr bgcolor='CCCCCC'> 
    <td width='25%' align = 'left'  class = 'leftform'>Image 2-Link to address</td>
    <td width='75%' colspan='2' align = 'left'><input type='text' name='lnk2' value = '$lnk2'  style='background-color:#FFFFD7' size=30></td>
  </tr>
  
  
    <tr bgcolor='CCCCCC'> 
    <td width='25%' align = 'left'  class = 'leftform'>Image 3-Link to address</td>
    <td width='75%' colspan='2' align = 'left'><input type='text' name='lnk3' value = '$lnk3'  style='background-color:#FFFFD7' size=30></td>
  </tr>


    <tr bgcolor='FFFFFF'> 
    <td width='100%' height ='2'  colspan = '2' align = 'center'><b>Heading</b></td>
  </tr>
  
<tr bgcolor='FFFFFF'> 
<td width='100%' height ='2'  colspan ='2'><textarea name='heading' style='width:595; height:100'></textarea></td>
</tr>";




echo "<tr bgcolor='FFFFFF'> <td width='100%' height ='2'  colspan = '2' align = 'center'><b>Content</b></td>";
echo " </tr>";

echo "<tr bgcolor='FFFFFF'> <td width='100%' height ='2'  colspan ='2'><textarea name='content' style='width:595; height:450'></textarea></td>";
echo " </tr>";
echo "<tr bgcolor='FFFFFF'>
<td width='100%' height ='4'  colspan = '2'></td>
  </tr>
<tr bgcolor='FFFFFF'>
 <td width='100%' colspan = '2' align = 'center'><input type='submit' value='submit'>
 <input type='hidden' name='id' value = '$lid'>
  <input type='hidden' name='type' value = '$type'>
 </td>
  </tr>
  </table>
</form>";

break;

case 'news':
echo "<table width='90%'  border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>";
echo "<tr bgcolor='FFFFFF'>
      <td width='25%' align = 'center' >Your new menu have been added, click news items to add news to this page</td></tr>";
	  
echo "<tr bgcolor='FFFFFF'>
        <td width='25%' height = '25' align = 'center' valign = 'middle'>
<form method='post'>
    <div align='center' valign = 'middle'>
      <input type='button' value='Close Window' onclick='window.close()'>
    </div>
  </form>
 </td>
 </tr>
 </table> ";

break;


case 'competition':
echo "<table width='90%'  border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>";
echo "<tr bgcolor='FFFFFF'>
      <td width='25%' align = 'center' >Your new menu have been added, click <b>List Items</b> to add competitions to this page</td></tr>";
	  
echo "<tr bgcolor='FFFFFF'>
        <td width='25%' height = '25' align = 'center' valign = 'middle'>
<form method='post'>
    <div align='center' valign = 'middle'>
      <input type='button' value='Close Window' onclick='window.close()'>
    </div>
  </form>
 </td>
 </tr>
 </table> ";

break;

case 'interview':
echo "<table width='90%'  border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>";
echo "<tr bgcolor='FFFFFF'>
      <td width='25%' align = 'center' >Your new menu have been added, click <b>List Items</b> to add interviews to this page</td></tr>";
	  
echo "<tr bgcolor='FFFFFF'>
        <td width='25%' height = '25' align = 'center' valign = 'middle'>
<form method='post'>
    <div align='center' valign = 'middle'>
      <input type='button' value='Close Window' onclick='window.close()'>
    </div>
  </form>
 </td>
 </tr>
 </table> ";

break;




case 'gallery':
echo "<table width='90%'  border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>";
echo "<tr bgcolor='FFFFFF'>
      <td width='25%' align = 'center' >Your new menu have been added, click <b>Gallery Items</b> to add your pictures to this page</td></tr>";
	  
echo "<tr bgcolor='FFFFFF'>
        <td width='25%' height = '25' align = 'center' valign = 'middle'>
<form method='post'>
    <div align='center' valign = 'middle'>
      <input type='button' value='Close Window' onclick='window.close()'>
    </div>
  </form>
 </td>
 </tr>
 </table> ";

break;

case 'contact':
echo "<table width='90%'  border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>


 <tr bgcolor='FFFFFF'><td width='25%' align = 'left'  class = 'leftform'>Main Title</td>
 <td width='75%' align = 'left'><input type='text' name='title' style='background-color:#FFFFD7' value = ''size=45></td>
 </tr>
 
 
 
  <tr bgcolor='FFFFFF'> 
    <td width='25%' align = 'left'  class = 'leftform'>Upload Image 1</td>
    <td width='60%' align = 'left'><input type= 'file' name= 'img1' size='15'> 
   </tr>
 
  <tr bgcolor='CCCCCC'> 
    <td width='25%' align = 'left'  class = 'leftform'>Image 1-Link to address</td>
    <td width='75%' colspan='2' align = 'left'><input type='text' name='lnk1' value = '$lnk1'  style='background-color:#FFFFD7' size=30></td>
  </tr>
 
 
 
 
 
 
 
 
 
 
 
         <tr bgcolor='FFFFFF'>
      <td width='25%' align = 'center' colspan ='2' ><b>Optional Text </b></td></tr>";
	  
echo "<tr bgcolor='FFFFFF'> <td width='100%' height ='2'  colspan ='2'><textarea name='content' style='width:595; height:100'></textarea></td>";
echo " </tr>
      <tr bgcolor='FFFFFF'>
	  <td width='25%' align = 'left'  class = 'leftform' colspan ='2'>Please email where you the form posted to</td></tr>
	  <tr bgcolor='FFFFFF'>
       <td width='75%' colspan ='2' align = 'center' >Email: <input type='text' name='email' style='background-color:#FFFFD7' value = '' size=45></td>
 </tr>
 <tr bgcolor='FFFFFF'>
       <td width='75%' colspan ='2' align = 'center' height = '30'><input type='submit' value='submit >>'> 
	    <input type='hidden' name='id' value = '$lid'>
		<input type='hidden' name='type' value = '$type'>
		 </td>
 </tr>
   </table>
   </form>
  
   <br>
   
   <table width='90%'  border='0' >
   <tr>
   <td align = 'center'> sample contact form </td
   </tr>
   </table>
   
  <br>
   
   <center>
   <table width='50%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>
 <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Name: </td>
 <td width='75%' align = 'left'><input type='text' name='name' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Email: </td>
 <td width='75%' align = 'left'><input type='text' name='name' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Subject: </td>
 <td width='75%' align = 'left'><input type='text' name='name' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Comment: </td>
 <td width='75%' align = 'left'><TEXTAREA NAME='comments' COLS=40 ROWS=6 style='background-color:#F2F2F2' disabled></TEXTAREA>
  </td>
  </tr>
  
       <tr bgcolor='FFFFFF'>
	  <td width='25%' align = 'center'  class = 'leftform' colspan ='2'>[reset button] &nbsp; [submit bottom]</td>
	  </tr>
  
  
  </table>
  </center>
    ";
	
	
break;



case 'registration':
echo "<table width='90%'  border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>


 <tr bgcolor='FFFFFF'><td width='25%' align = 'left'  class = 'leftform'>Main Title</td>
 <td width='75%' align = 'left'><input type='text' name='title' style='background-color:#FFFFD7' value = ''size=45></td>
 </tr>
         <tr bgcolor='FFFFFF'>
      <td width='25%' align = 'center' colspan ='2' ><b>Optional Text </b></td></tr>";
	  
echo "<tr bgcolor='FFFFFF'> <td width='100%' height ='2'  colspan ='2'><textarea name='content' style='width:595; height:100'></textarea></td>";
echo " </tr>
      <tr bgcolor='FFFFFF'>
	  <td width='25%' align = 'left'  class = 'leftform' colspan ='2'>Please email where you the form posted to</td></tr>
	  <tr bgcolor='FFFFFF'>
       <td width='75%' colspan ='2' align = 'center' >Email: <input type='text' name='email' style='background-color:#FFFFD7' value = '' size=45></td>
 </tr>
 <tr bgcolor='FFFFFF'>
       <td width='75%' colspan ='2' align = 'center' height = '30'><input type='submit' value='submit >>'> 
	    <input type='hidden' name='id' value = '$lid'>
		<input type='hidden' name='type' value = '$type'>
		 </td>
 </tr>
   </table>
   </form>
  
   <br>
   
   <table width='90%'  border='0' >
   <tr>
   <td align = 'center'> sample registration Form</td
   </tr>
   </table>
   
  <br>
   
   <center>
   
   <table width='50%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>
 <tr bgcolor='FFFFFF'>

 <td width='25%' align = 'left'  class = 'leftform'>First Name: </td>
 <td width='75%' align = 'left'><input type='text' name='fname' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>

   <tr bgcolor='FFFFFF'>
  <td width='25%' align = 'left'  class = 'leftform'>Last Name: </td>
 <td width='75%' align = 'left'><input type='text' name='lname' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Email: </td>
 <td width='75%' align = 'left'><input type='text' name='name' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Address1: </td>
 <td width='75%' align = 'left'><input type='text' name='add1' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
    <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Address2: </td>
 <td width='75%' align = 'left'><input type='text' name='add2' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
  
  
  
  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>PostCode: </td>
 <td width='75%' align = 'left'><input type='text' name='pcode' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
    <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>City: </td>
 <td width='75%' align = 'left'><input type='text' name='city' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
    <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Phone: </td>
 <td width='75%' align = 'left'><input type='text' name='phone' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
    <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Mobile: </td>
 <td width='75%' align = 'left'><input type='text' name='mobile' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
  
    <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Country: </td>
 <td width='75%' align = 'left'>
 <select name='country'>
            <option selected>Anguilla</option>
            <option>Antigua</option>
            <option>Africa</option>
            <option>Uk</option>
			<option>USA</option>
          </select>
 
 </td>
  </tr>
  
  
  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Comment: </td>
 <td width='75%' align = 'left'><TEXTAREA NAME='comments' COLS=40 ROWS=6 style='background-color:#F2F2F2' disabled></TEXTAREA>
  </td>
  </tr>
  
       <tr bgcolor='FFFFFF'>
	  <td width='25%' align = 'center'  class = 'leftform' colspan ='2'>[reset button] &nbsp; [submit bottom]</td>
	  </tr>
  
  
  </table>
  </center>
    ";

}//end switch

}//end function





//---------------select page type

function stype($type,$id){


switch ($type) {

case 'home':
shome($type,$id);
break;


case 'content':

 scontent($type,$id);
  break;

case 'news':
 
snews($type,$id);
 break;
 
 case 'gallery':
 
sgallery($type,$id);
 break;
 
 case 'contact':
 scontact($type,$id);
 break;
 
 }// end case

}//end function


//-------------- select page post


function sptype($type,$id){

switch ($type) {
case 'home':
 sphome($type,$id);
  break;

case 'content':
 spcontent($type,$id);
  break;
 
 case 'contact':
 spcontact($type,$id);
  break; 
  
   case 'registration':
 spregistration($type,$id);
  break;
  
  case 'news':
 sp_items($type,$id);
  break;
  
  case 'gallery':
 sp_items($type,$id);
  break;
  
  

 }// end case

}//end function



//----------edit type

function etype($type,$id, $position){
   //echo $type;
switch ($type) {

case 'home':
ehome($type,$id,$position);
break;


case 'content':
 econtent($type,$id,$position);

  break;
  
case 'contact':
 econtact($type,$id,$position);
break;

case 'registration':
 eregistration($type,$id,$position);
break;




case 'news':
 e_news($type,$id,$position);
break;

case 'gallery':
 e_gallery($type,$id, $position);
break;


  }

}//end function


//-----------------edit news page

function e_news($type,$id,$position){

echo "</td>
<tr bgcolor='#FFFFFF'>
 <td width='100%' height ='4'  colspan = '2'></td>
  </tr>
<tr bgcolor='#FFFFFF'>
 <td width='100%' colspan = '2' align = 'center'><input type='submit' value='Update'></td>
  </tr>
<input type='hidden' name='id' value = '$id'>
<input type='hidden' name='type' value = '$type'>
<input type='hidden' name='position' value = '$position'>


</form>
</table>";

}//end function


//-----------------edit gallery page

function e_gallery($type,$id, $position){

echo "</td>
<tr bgcolor='#FFFFFF'>
 <td width='100%' height ='4'  colspan = '2'></td>
  </tr>
<tr bgcolor='#FFFFFF'>
 <td width='100%' colspan = '2' align = 'center'><input type='submit' value='Update'></td>
  </tr>
<input type='hidden' name='id' value = '$id'>
<input type='hidden' name='type' value = '$type'>
<input type='hidden' name='position' value = '$position'>


</form>
</table>";

}//end function








//--------------edit home page

function ehome($type,$id,$position) {

include("con_home.php");

$sql = "SELECT * FROM $type WHERE id ='$id'";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {

$title = stripslashes($result["title"]);
$img1 = stripslashes($result["img1"]);
$img2 = stripslashes($result["img2"]);
$img3 = stripslashes($result["img3"]);
$img4 = stripslashes($result["img4"]);


$lnk1 = stripslashes($result["lnk1"]);
$lnk2 = stripslashes($result["lnk2"]);
$lnk3 = stripslashes($result["lnk3"]);
$lnk4 = stripslashes($result["lnk4"]);



$heading = stripslashes($result["heading"]);

$content = stripslashes($result["content"]);

}


echo "<table width='100%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>
  <tr bgcolor='FFFFFF'> 
    <td width='25%' align = 'left'  class = 'leftform'>Main Title</td>
    <td width='75%' colspan='2' align = 'left'><input type='text' name='title' value = '$title'  style='background-color:#FFFFD7' size=45></td>
  </tr>
  
  
  <tr bgcolor='FFFFFF'> 
    <td width='25%' align = 'left'  class = 'leftform'>Upload Image 1 </td>
    <td width='60%' align = 'left' class = 'cmsdem'><input type= 'file' name= 'img1' size='15'> 
      &nbsp; [$img1] &nbsp; $imgsize1</td>
    <td width='15%' align = 'left'><INPUT type='checkbox' name='rmove[]' value='img1'>
      remove</td>
  </tr>
  
  
  <tr bgcolor='FFFFFF'> 
    <td width='25%' align = 'left'  class = 'leftform'>Upload Image 2</td>
    <td width='60%' align = 'left' class = 'cmsdem'><input type= 'file' name='img2' size=15'> &nbsp; 
      [$img2] &nbsp; $imgsize2</td>
    <td width='15%' align = 'left'><INPUT type='checkbox' name='rmove[]' value='img2'>
      remove</td>
  </tr>
  
  <tr bgcolor='FFFFFF'> 
    <td width='25%' align = 'left'  class = 'leftform'>Upload Image 3</td>
    <td width='60%' align = 'left' class = 'cmsdem'><input type= 'file' name='img3' size=15'>
      &nbsp; [$img3] &nbsp; $imgsize3</td>
    <td width='15%' align = 'left'><INPUT type='checkbox' name='rmove[]' value='img3'>
      remove</td>
  </tr>
  
  
  <tr bgcolor='FFFFFF'> 
    <td width='25%' align = 'left'  class = 'leftform'>Upload Image 4</td>
    <td width='60%' align = 'left' class = 'cmsdem'><input type= 'file' name='img4' size=15'>
      &nbsp; [$img4] &nbsp; $imgsize4</td>
    <td width='15%' align = 'left'><INPUT type='checkbox' name='rmove[]' value='img4'>
      remove</td>
  </tr>
  
  
  <tr bgcolor='CCCCCC'> 
    <td width='25%' align = 'left'  class = 'leftform'>Image 1-Link to address</td>
    <td width='75%' colspan='2' align = 'left'><input type='text' name='lnk1' value = '$lnk1'  style='background-color:#FFFFD7' size=30></td>
  </tr>
  

  <tr bgcolor='CCCCCC'> 
    <td width='25%' align = 'left'  class = 'leftform'>Image 2-Link to address</td>
    <td width='75%' colspan='2' align = 'left'><input type='text' name='lnk2' value = '$lnk2'  style='background-color:#FFFFD7' size=30></td>
  </tr>
  
  
    <tr bgcolor='CCCCCC'> 
    <td width='25%' align = 'left'  class = 'leftform'>Image 3-Link to address</td>
    <td width='75%' colspan='2' align = 'left'><input type='text' name='lnk3' value = '$lnk3'  style='background-color:#FFFFD7' size=30></td>
  </tr>
  
    <tr bgcolor='CCCCCC'> 
    <td width='25%' align = 'left'  class = 'leftform'>Image 4-Link to address</td>
    <td width='75%' colspan='2' align = 'left'><input type='text' name='lnk4' value = '$lnk4'  style='background-color:#FFFFD7' size=30></td>
  </tr>


  
  
 

    <tr bgcolor='FFFFFF'> 
    <td width='100%' height ='2'  colspan = '3' align = 'center'><b>Heading</b></td>
  </tr>
  
<tr bgcolor='FFFFFF'> 
<td width='100%' height ='2'  colspan ='3'><textarea name='heading' style='width:640; height:100'>$heading</textarea></td>
</tr>
  
  
  
  <tr bgcolor='FFFFFF'> 
    <td width='100%' height ='2'  colspan = '3' align = 'center'><b>Content</b></td>
  </tr>
  <tr bgcolor='FFFFFF'> 
    <td width='100%' height ='2'  colspan ='3'><textarea name='content' style='width:640; height:450'>$content</textarea></td>
  </tr> 

  
  
  
  
  
  
</table>

  </td>
<tr bgcolor='#FFFFFF'>
 <td width='100%' height ='4'  colspan = '2'></td>
  </tr>
<tr bgcolor='#FFFFFF'>
 <td width='100%' colspan = '2' align = 'center'><input type='submit' value='Update'></td>
  </tr>
<input type='hidden' name='id' value = '$id'>
<input type='hidden' name='type' value = '$type'>
<input type='hidden' name='position' value = '$position'>


</form>

</table>";


}// end function



















//--------------edit content page

function econtent($type,$id,$position) {

$sql = "SELECT * FROM $type WHERE id ='$id'";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {

$title = stripslashes($result["title"]);
$img1 = stripslashes($result["img1"]);
$img2 = stripslashes($result["img2"]);
$img3 = stripslashes($result["img3"]);

$lnk1 = stripslashes($result["lnk1"]);
$lnk2 = stripslashes($result["lnk2"]);
$lnk3 = stripslashes($result["lnk3"]);

$heading = stripslashes($result["heading"]);
$content = stripslashes($result["content"]);

}


echo "<table width='100%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>
  <tr bgcolor='FFFFFF'> 
    <td width='25%' align = 'left'  class = 'leftform'>Main Title</td>
    <td width='75%' colspan='2' align = 'left'><input type='text' name='title' value = '$title'  style='background-color:#FFFFD7' size=45></td>
  </tr>
  
  
  <tr bgcolor='FFFFFF'> 
    <td width='25%' align = 'left'  class = 'leftform'>Upload Image 1</td>
    <td width='60%' align = 'left'><input type= 'file' name= 'img1' size='15'> 
      &nbsp; [$img1] &nbsp; </td>
    <td width='15%' align = 'left'><INPUT type='checkbox' name='rmove[]' value='img1'>
      remove</td>
  </tr>
  
  <tr bgcolor='FFFFFF'> 
    <td width='25%' align = 'left'  class = 'leftform'>Upload Image 2</td>
    <td width='60%' align = 'left'><input type= 'file' name='img2' size=15'> &nbsp; 
      [$img2] &nbsp; </td>
    <td width='15%' align = 'left'><INPUT type='checkbox' name='rmove[]' value='img2'>
      remove</td>
  </tr>
  <tr bgcolor='FFFFFF'> 
    <td width='25%' align = 'left'  class = 'leftform'>Upload Image 3</td>
    <td width='60%' align = 'left'><input type= 'file' name='img3' size=15'>
      &nbsp; [$img3] &nbsp; </td>
    <td width='15%' align = 'left'><INPUT type='checkbox' name='rmove[]' value='img3'>
      remove</td>
  </tr>
  
  
  
  
  
   <tr bgcolor='CCCCCC'> 
    <td width='25%' align = 'left'  class = 'leftform'>Image 1-Link to address</td>
    <td width='75%' colspan='2' align = 'left'><input type='text' name='lnk1' value = '$lnk1'  style='background-color:#FFFFD7' size=30></td>
  </tr>
  

  <tr bgcolor='CCCCCC'> 
    <td width='25%' align = 'left'  class = 'leftform'>Image 2-Link to address</td>
    <td width='75%' colspan='2' align = 'left'><input type='text' name='lnk2' value = '$lnk2'  style='background-color:#FFFFD7' size=30></td>
  </tr>
  
  
    <tr bgcolor='CCCCCC'> 
    <td width='25%' align = 'left'  class = 'leftform'>Image 3-Link to address</td>
    <td width='75%' colspan='2' align = 'left'><input type='text' name='lnk3' value = '$lnk3'  style='background-color:#FFFFD7' size=30></td>
  </tr>
  
  
  
  
  <tr bgcolor='FFFFFF'> 
    <td width='100%' height ='2'  colspan = '3' align = 'center'><b>Heading</b></td>
  </tr>
  
<tr bgcolor='FFFFFF'> 
<td width='100%' height ='2'  colspan ='3'><textarea name='heading' style='width:640; height:100'>$heading</textarea></td>
</tr>
  
  
  
  <tr bgcolor='FFFFFF'> 
    <td width='100%' height ='2'  colspan = '3' align = 'center'><b>Content</b></td>
  </tr>
  <tr bgcolor='FFFFFF'> 
    <td width='100%' height ='2'  colspan ='3'><textarea name='content' style='width:640; height:450'>$content</textarea></td>
  </tr>
</table>

  </td>
<tr bgcolor='#FFFFFF'>
 <td width='100%' height ='4'  colspan = '2'></td>
  </tr>
<tr bgcolor='#FFFFFF'>
 <td width='100%' colspan = '2' align = 'center'><input type='submit' value='Update'></td>
  </tr>
<input type='hidden' name='id' value = '$id'>
<input type='hidden' name='type' value = '$type'>
<input type='hidden' name='position' value = '$position'>


</form>

</table>";


}// end function




//--------------------edit contact page


function econtact($type,$id,$position) {

global $position;

$sql = "SELECT * FROM $type WHERE id ='$id'";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {
$title = stripslashes($result["title"]);
$email = stripslashes($result["email"]);

$img1 = stripslashes($result["img1"]);
$lnk1 = stripslashes($result["lnk1"]);




$content = stripslashes($result["content"]);
}

echo "<table width='90%'  border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>


 <tr bgcolor='FFFFFF'><td width='25%' align = 'left'  class = 'leftform'>Main Title</td>
 <td width='75%' align = 'left' colspan='2'><input type='text' name='title' style='background-color:#FFFFD7' value = '$title'size=45></td>
 </tr>
 
 
 
 
  <tr bgcolor='FFFFFF'> 
    <td width='25%' align = 'left'  class = 'leftform'>Upload Image 1</td>
    <td width='60%' align = 'left'><input type= 'file' name= 'img1' size='15'> 
      &nbsp; [$img1] &nbsp; </td>
    <td width='15%' align = 'left'><INPUT type='checkbox' name='rmove[]' value='img1'>
      remove</td>
  </tr>
 
  <tr bgcolor='CCCCCC'> 
    <td width='25%' align = 'left'  class = 'leftform'>Image 1-Link to address</td>
    <td width='75%' colspan='2' align = 'left'><input type='text' name='lnk1' value = '$lnk1'  style='background-color:#FFFFD7' size=30></td>
  </tr>
 
 
 
 
 
 
         <tr bgcolor='FFFFFF'>
      <td width='25%' align = 'center' colspan ='3' ><b>Optional Text </b></td></tr>";
	  
echo "<tr bgcolor='FFFFFF'> <td width='100%' height ='2'  colspan ='3'><textarea name='content' style='width:595; height:100'>$content</textarea></td>";
echo " </tr>
      <tr bgcolor='FFFFFF'>
	  <td width='25%' align = 'left'  class = 'leftform' colspan ='3'>Please email where you the form posted to</td></tr>
	  <tr bgcolor='FFFFFF'>
       <td width='75%' colspan ='3' align = 'center' >Email: <input type='text' name='email' style='background-color:#FFFFD7' value = '$email' size=45></td>
 </tr>
 
 
 
 
 
 
 
 <tr bgcolor='FFFFFF'>
       <td width='75%' colspan ='3' align = 'center' height = '30'><input type='submit' value='update >>'> 
	    <input type='hidden' name='id' value = '$id'>
		<input type='hidden' name='type' value = '$type'>
		<input type='hidden' name='position' value = '$position'>
		 </td>
 </tr>
   </table>
   </form>
  
   <br>
   
   <table width='90%'  border='0' >
   <tr>
   <td align = 'center'> sample contact form </td
   </tr>
   </table>
   
  <br>
   
   <center>
   <table width='50%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>
 <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Name: </td>
 <td width='75%' align = 'left'><input type='text' name='name' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Email: </td>
 <td width='75%' align = 'left'><input type='text' name='name' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Subject: </td>
 <td width='75%' align = 'left'><input type='text' name='name' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Comment: </td>
 <td width='75%' align = 'left'><TEXTAREA NAME='comments' COLS=40 ROWS=6 style='background-color:#F2F2F2' disabled></TEXTAREA>
  </td>
  </tr>
  
       <tr bgcolor='FFFFFF'>
	  <td width='25%' align = 'center'  class = 'leftform' colspan ='2'>[reset button] &nbsp; [submit bottom]</td>
	  </tr>
  
  
  </table>
  




  </center>
    ";





}//end function






//--------------------edit registrationb


function eregistration($type,$id,$position) {

global $position;

$sql = "SELECT * FROM $type WHERE id ='$id'";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {
$title = stripslashes($result["title"]);
$email = stripslashes($result["email"]);
$content = stripslashes($result["content"]);
}

echo "<table width='90%'  border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>


 <tr bgcolor='FFFFFF'><td width='25%' align = 'left'  class = 'leftform'>Main Title</td>
 <td width='75%' align = 'left'><input type='text' name='title' style='background-color:#FFFFD7' value = '$title'size=45></td>
 </tr>
         <tr bgcolor='FFFFFF'>
      <td width='25%' align = 'center' colspan ='2' ><b>Optional Text </b></td></tr>";
	  
echo "<tr bgcolor='FFFFFF'> <td width='100%' height ='2'  colspan ='2'><textarea name='content' style='width:595; height:100'>$content</textarea></td>";
echo " </tr>
      <tr bgcolor='FFFFFF'>
	  <td width='25%' align = 'left'  class = 'leftform' colspan ='2'>Please email where you the form posted to</td></tr>
	  <tr bgcolor='FFFFFF'>
       <td width='75%' colspan ='2' align = 'center' >Email: <input type='text' name='email' style='background-color:#FFFFD7' value = '$email' size=45></td>
 </tr>
 <tr bgcolor='FFFFFF'>
       <td width='75%' colspan ='2' align = 'center' height = '30'><input type='submit' value='update >>'> 
	    <input type='hidden' name='id' value = '$id'>
		<input type='hidden' name='type' value = '$type'>
		<input type='hidden' name='position' value = '$position'>
		 </td>
 </tr>
   </table>
   </form>
  
   <br>
   
   <table width='90%'  border='0' >
   <tr>
   <td align = 'center'> sample registration form </td
   </tr>
   </table>
   
  <br>
   
   <center>
  <table width='50%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>
 <tr bgcolor='FFFFFF'>

 <td width='25%' align = 'left'  class = 'leftform'>First Name: </td>
 <td width='75%' align = 'left'><input type='text' name='fname' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>

   <tr bgcolor='FFFFFF'>
  <td width='25%' align = 'left'  class = 'leftform'>Last Name: </td>
 <td width='75%' align = 'left'><input type='text' name='lname' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Email: </td>
 <td width='75%' align = 'left'><input type='text' name='name' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Address1: </td>
 <td width='75%' align = 'left'><input type='text' name='add1' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
    <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Address2: </td>
 <td width='75%' align = 'left'><input type='text' name='add2' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
  
  
  
  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>PostCode: </td>
 <td width='75%' align = 'left'><input type='text' name='pcode' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
    <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>City: </td>
 <td width='75%' align = 'left'><input type='text' name='city' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
    <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Phone: </td>
 <td width='75%' align = 'left'><input type='text' name='phone' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
    <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Mobile: </td>
 <td width='75%' align = 'left'><input type='text' name='mobile' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
  
    <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Country: </td>
 <td width='75%' align = 'left'>
 <select name='country'>
            <option selected>Anguilla</option>
            <option>Antigua</option>
            <option>Africa</option>
            <option>Uk</option>
			<option>USA</option>
          </select>
 
 </td>
  </tr>
  
  
  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Comment: </td>
 <td width='75%' align = 'left'><TEXTAREA NAME='comments' COLS=40 ROWS=6 style='background-color:#F2F2F2' disabled></TEXTAREA>
  </td>
  </tr>
  
       <tr bgcolor='FFFFFF'>
	  <td width='25%' align = 'center'  class = 'leftform' colspan ='2'>[reset button] &nbsp; [submit bottom]</td>
	  </tr>
  
  
  </table>




  </center>
    ";





}//end function






















//----------------editnote

function editnote($type,$id) {
switch ($type) {
case 'news':
 enews($type,$id);
  
   break;

case 'gallery':
 egallery($type,$id);
  break;   
   
   
}// end switch

}// end function


//----------------edit news
function enews($type,$id) {

echo "<br>";
echo "to edit update or delete news items for this page click <B>LIST ITEMS</B> on the top menu"; 


}// end function

//----------------edit news

function egallery($type,$id) {

echo "<br>";
echo "to edit update or delete gallery items for this page click gallery items on the top menu"; 


}// end function







//------------- show content page

function scontent($type,$id) {

$sql = "SELECT * FROM $type WHERE id ='$id'";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {
$title = stripslashes($result["title"]);
$img1 = stripslashes($result["img1"]);
$img2 = stripslashes($result["img2"]);
$img3 = stripslashes($result["img3"]);
$content = stripslashes($result["content"]);

}// end while

if($img1 == "none"){
$path1 = "";
} else {
$path1 = "<img src='../images/$img1'>";
}

if($img2 == "none"){
$path2 = "";
} else {
$path2 = "<img src='../images/$img2'>";
}

if($img3 == "none"){
$path3 = "";
} else {
$path3 = "<img src='../images/$img3'>";
}


echo "<table width='90%'  border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>";
echo " <tr bgcolor='FFFFFF'>";
echo "<td width='19%' align = 'left'>Main Title</td>";
echo " <td width='81%' align = 'left'><b>$title</b></td>";
echo "  </tr>";

echo " <tr bgcolor='FFFFFF'>";
echo " <td width='15%' align = 'left'>Image 1 </td>";
echo " <td width='85%' align = 'left'>$path1</td>";
echo "  </tr>";

echo "<tr bgcolor='FFFFFF'>";
echo " <td width='15%' align = 'left'>Image 2 </td>";
echo " <td width='85%' align = 'left'>$path2</td>";
echo "  </tr>";

echo "<tr bgcolor='FFFFFF'>";
echo " <td width='15%' align = 'left'>Image 3 </td>";
echo " <td width='85%' align = 'left'>$path3</td>";
echo "  </tr>";

echo "<tr bgcolor='FFFFFF'>";
echo " <td width='100%' height ='2'  colspan = '2' align = 'center'> <b>Content</b></td>";
echo "  </tr>";
echo "<tr bgcolor='FFFFFF'>";
echo  "<td width='85%' align = 'left' colspan = '2'>$content</td>";
echo "</tr>";
echo "</table>";
echo "</center>";

}// end function






//------------- show home page

function shome($type,$id) {



$sql = "SELECT * FROM $type WHERE id ='$id'";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {
$title = stripslashes($result["title"]);
$img1 = stripslashes($result["img1"]);
$img2 = stripslashes($result["img2"]);
$img3 = stripslashes($result["img3"]);
$img4 = stripslashes($result["img4"]);
$heading = stripslashes($result["heading"]);
$content = stripslashes($result["content"]);

}// end while

if($img1 == "none"){
$path1 = "";
} else {
$path1 = "<img src='../images/$img1'>";
}

if($img2 == "none"){
$path2 = "";
} else {
$path2 = "<img src='../images/$img2'>";
}

if($img3 == "none"){
$path3 = "";
} else {
$path3 = "<img src='../images/$img3'>";
}

if($img4 == "none"){
$path4 = "";
} else {
$path4 = "<img src='../images/$img4'>";
}


echo "<table width='90%'  border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>";
echo " <tr bgcolor='FFFFFF'>";
echo "<td width='19%' align = 'left'>Main Title</td>";
echo " <td width='81%' align = 'left'><b>$title</b></td>";
echo "  </tr>";

echo " <tr bgcolor='FFFFFF'>";
echo " <td width='15%' align = 'left'>Image 1 </td>";
echo " <td width='85%' align = 'left'>$path1</td>";
echo "  </tr>";

echo "<tr bgcolor='FFFFFF'>";
echo " <td width='15%' align = 'left'>Image 2 </td>";
echo " <td width='85%' align = 'left'>$path2</td>";
echo "  </tr>";

echo "<tr bgcolor='FFFFFF'>";
echo " <td width='15%' align = 'left'>Image 3 </td>";
echo " <td width='85%' align = 'left'>$path3</td>";
echo "  </tr>";


echo "<tr bgcolor='FFFFFF'>";
echo " <td width='15%' align = 'left'>Image 4 </td>";
echo " <td width='85%' align = 'left'>$path4</td>";
echo "  </tr>";



echo "<tr bgcolor='FFFFFF'>";
echo " <td width='100%' height ='2'  colspan = '2' align = 'center'> <b>Heading</b></td>";
echo "  </tr>";
echo "<tr bgcolor='FFFFFF'>";
echo  "<td width='85%' align = 'left' colspan = '2'>$heading</td>";
echo "</tr>";



echo "<tr bgcolor='FFFFFF'>";
echo " <td width='100%' height ='2'  colspan = '2' align = 'center'> <b>Content</b></td>";
echo "  </tr>";
echo "<tr bgcolor='FFFFFF'>";
echo  "<td width='85%' align = 'left' colspan = '2'>$content</td>";
echo "</tr>";
echo "</table>";
echo "</center>";

}// end function


















//---------------------show contact page
function scontact($type,$id) {

$sql = "SELECT * FROM $type WHERE id ='$id'";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {
$title = stripslashes($result["title"]);
$email = stripslashes($result["email"]);
$img1 = stripslashes($result["img1"]);
$content = stripslashes($result["content"]);

}

if($img1 == "none"){
$path1 = "";
} else {
$path1 = "<img src='../images/$img1'>";
}


echo "<table width='90%'  border='0' cellpadding='0' cellspacing='0' class=catTbl mm_noconvert='TRUE'>

<tr bgcolor='FFFFFF'> <td width='75%' align = 'left' colspan = '2'><b>$title</b></td>
 </tr>";
  
echo "<tr bgcolor='FFFFFF'> <td height ='2' colspan = '2'>$content</td>";
echo " </tr>


<tr bgcolor='FFFFFF'>
<td width='15%' align = 'left'>Image 1 </td>
 <td width='85%' align = 'left'>$path1</td>
  </tr>
      
	    </table>
   <br>
   
   <table width='90%'  border='0' >
   <tr>
   <td align = 'center'> Please fill in  the form below</td
   </tr>
   </table>
   
  <br>
   
   <center>
   <table width='50%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>
 <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Name: </td>
 <td width='75%' align = 'left'><input type='text' name='name' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Email: </td>
 <td width='75%' align = 'left'><input type='text' name='name' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>to: </td>
 <td width='75%' align = 'left'><b>[$email]</b></td>
  </tr>
  
  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Subject: </td>
 <td width='75%' align = 'left'><input type='text' name='name' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Comment: </td>
 <td width='75%' align = 'left'><TEXTAREA NAME='comments' COLS=40 ROWS=6 style='background-color:#F2F2F2' disabled></TEXTAREA>
  </td>
  </tr>
  
       <tr bgcolor='FFFFFF'>
	  <td width='25%' align = 'center'  class = 'leftform' colspan ='2'>[reset button] &nbsp; [submit bottom]</td>
	  </tr>
  
  
  </table>
  </center>
    ";




}//end fucntion





//--------------------show post content

function sphome($type,$id) {

$sql = "SELECT * FROM $type WHERE id ='$id'";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {
$title = stripslashes($result["title"]);
$img1 = stripslashes($result["img1"]);
$img2 = stripslashes($result["img2"]);
$img3 = stripslashes($result["img3"]);
$img4 = stripslashes($result["img4"]);
$heading = stripslashes($result["heading"]);

$content = stripslashes($result["content"]);

}

if($img1 == "none"){
$path1 = "";
} else {
$path1 = "<img src='../images/$img1'>";
}

if($img2 == "none"){
$path2 = "";
} else {
$path2 = "<img src='../images/$img2'>";
}

if($img3 == "none"){
$path3 = "";
} else {
$path3 = "<img src='../images/$img3'>";
}

if($img4 == "none"){
$path4 = "";
} else {
$path4 = "<img src='../images/$img4'>";
}


echo "<table width='90%'  border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>";
echo " <tr bgcolor='FFFFFF'>";
echo "<td width='19%' align = 'left'>Main Title</td>";
echo " <td width='81%' align = 'left'><b>$title</b></td>";
echo "  </tr>";

echo " <tr bgcolor='FFFFFF'>";
echo " <td width='15%' align = 'left'>Image 1 </td>";
echo " <td width='85%' align = 'left'>$path1</td>";
echo "  </tr>";

echo "<tr bgcolor='FFFFFF'>";
echo " <td width='15%' align = 'left'>Image 2 </td>";
echo " <td width='85%' align = 'left'>$path2</td>";
echo "  </tr>";

echo "<tr bgcolor='FFFFFF'>";
echo " <td width='15%' align = 'left'>Image 3 </td>";
echo " <td width='85%' align = 'left'>$path3</td>";
echo "  </tr>";

echo "<tr bgcolor='FFFFFF'>";
echo " <td width='15%' align = 'left'>Image 4 </td>";
echo " <td width='85%' align = 'left'>$path4</td>";
echo "  </tr>";



echo "<tr bgcolor='FFFFFF'>";
echo " <td width='100%' height ='2'  colspan = '2' align = 'center'> <b>Heading</b></td>";
echo "  </tr>";
echo "<tr bgcolor='FFFFFF'>";
echo  "<td width='85%' align = 'left' colspan = '2'>$heading</td>";
echo "</tr>";








echo "<tr bgcolor='FFFFFF'>";
echo " <td width='100%' height ='2'  colspan = '2' align = 'center'> <b>Content</b></td>";
echo "  </tr>";
echo "<tr bgcolor='FFFFFF'>";
echo  "<td width='85%' align = 'left' colspan = '2'>$content</td>";
echo "</tr>";

echo "<tr bgcolor='FFFFFF'>";
echo " <td width='100%' height ='2'  colspan = '2' align = 'center'>
      <b>content updated</b>
	   <br>
<form method='post'>
    <div align='center' valign = 'middle'>
      <input type='button' value='Close Window' onclick='window.close()'>
    </div>
  </form>

 </td>";
echo "  </tr>";

echo "</table>";
echo "</center>";

}// end function





















//--------------------show post content

function spcontent($type,$id) {
global $heading;

$sql = "SELECT * FROM $type WHERE id ='$id'";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {
$title = stripslashes($result["title"]);
$img1 = stripslashes($result["img1"]);
$img2 = stripslashes($result["img2"]);
$img3 = stripslashes($result["img3"]);
$content = stripslashes($result["content"]);

}

if($img1 == "none"){
$path1 = "";
} else {
$path1 = "<img src='../images/$img1'>";
}

if($img2 == "none"){
$path2 = "";
} else {
$path2 = "<img src='../images/$img2'>";
}

if($img3 == "none"){
$path3 = "";
} else {
$path3 = "<img src='../images/$img3'>";
}


echo "<table width='90%'  border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>";
echo " <tr bgcolor='FFFFFF'>";
echo "<td width='19%' align = 'left'>Main Title</td>";
echo " <td width='81%' align = 'left'><b>$title</b></td>";
echo "  </tr>";

echo " <tr bgcolor='FFFFFF'>";
echo " <td width='15%' align = 'left'>Image 1 </td>";
echo " <td width='85%' align = 'left'>$path1</td>";
echo "  </tr>";

echo "<tr bgcolor='FFFFFF'>";
echo " <td width='15%' align = 'left'>Image 2 </td>";
echo " <td width='85%' align = 'left'>$path2</td>";
echo "  </tr>";

echo "<tr bgcolor='FFFFFF'>";
echo " <td width='15%' align = 'left'>Image 3 </td>";
echo " <td width='85%' align = 'left'>$path3</td>";
echo "  </tr>";


echo "<tr bgcolor='FFFFFF'>";
echo " <td width='100%' height ='2'  colspan = '2' align = 'center'> <b>Heading</b></td>";
echo "  </tr>";
echo "<tr bgcolor='FFFFFF'>";
echo  "<td width='85%' align = 'left' colspan = '2'>$heading</td>";
echo "</tr>";



echo "<tr bgcolor='FFFFFF'>";
echo " <td width='100%' height ='2'  colspan = '2' align = 'center'> <b>Content</b></td>";
echo "  </tr>";
echo "<tr bgcolor='FFFFFF'>";
echo  "<td width='85%' align = 'left' colspan = '2'>$content</td>";
echo "</tr>";

echo "<tr bgcolor='FFFFFF'>";
echo " <td width='100%' height ='2'  colspan = '2' align = 'center'>
      <b>content updated</b>
	   <br>
<form method='post'>
    <div align='center' valign = 'middle'>
      <input type='button' value='Close Window' onclick='window.close()'>
    </div>
  </form>

 </td>";
echo "  </tr>";

echo "</table>";
echo "</center>";

}// end function


//---------------show post contact

function spcontact($type,$id) {

$sql = "SELECT * FROM $type WHERE id ='$id'";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {
$title = stripslashes($result["title"]);
$email = stripslashes($result["email"]);
$img1 = stripslashes($result["img1"]);
$content = stripslashes($result["content"]);
}


if($img1 == "none"){
$path1 = "";
} else {
$path1 = "<img src='../images/$img1'>";
}

echo "<table width='90%'  border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>

<tr bgcolor='FFFFFF'><td width='25%' align = 'left'  class = 'leftform'>Main Title</td>
 <td width='75%' align = 'left'>$title</td>
 </tr>";
  
	  
echo "<tr bgcolor='FFFFFF'> <td width='100%' height ='2'  colspan ='2'>$content</td>";
echo " </tr>
      <tr bgcolor='FFFFFF'>
	  <td width='25%' align = 'left'  class = 'leftform' colspan ='2'>The form will be posted to: <b>[$email]</b></td></tr>
	  
	  
	  <tr bgcolor='FFFFFF'>
<td width='15%' align = 'left'>Image 1 </td>
 <td width='85%' align = 'left'>$path1</td>
  </tr>
	  
	  
 <tr bgcolor='FFFFFF'>
       <td width='75%' colspan ='2' align = 'center' height = '30'>
	   
	         <b>contact updated</b>
	   <br>
<form method='post'>
    <div align='center' valign = 'middle'>
      <input type='button' value='Close Window' onclick='window.close()'>
    </div>
  </form>
	   
		 </td>
 </tr>
   </table>
   </form>
  
   <br>
   
   <table width='90%'  border='0' >
   <tr>
   <td align = 'center'> sample contact form </td
   </tr>
   </table>
   
  <br>
   
   <center>
   <table width='50%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>
 <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Name: </td>
 <td width='75%' align = 'left'><input type='text' name='name' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Email: </td>
 <td width='75%' align = 'left'><input type='text' name='name' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Subject: </td>
 <td width='75%' align = 'left'><input type='text' name='name' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Comment: </td>
 <td width='75%' align = 'left'><TEXTAREA NAME='comments' COLS=40 ROWS=6 style='background-color:#F2F2F2' disabled></TEXTAREA>
  </td>
  </tr>
  
       <tr bgcolor='FFFFFF'>
	  <td width='25%' align = 'center'  class = 'leftform' colspan ='2'>[reset button] &nbsp; [submit bottom]</td>
	  </tr>
  
  
  </table>
  </center>
    ";



}// end function






//---------------show post Registration

function spregistration($type,$id) {

$sql = "SELECT * FROM $type WHERE id ='$id'";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {
$title = stripslashes($result["title"]);
$email = stripslashes($result["email"]);
$content = stripslashes($result["content"]);
}


echo "<table width='90%'  border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>

<tr bgcolor='FFFFFF'><td width='25%' align = 'left'  class = 'leftform'>Main Title</td>
 <td width='75%' align = 'left'>$title</td>
 </tr>";
  
	  
echo "<tr bgcolor='FFFFFF'> <td width='100%' height ='2'  colspan ='2'>$content</td>";
echo " </tr>
      <tr bgcolor='FFFFFF'>
	  <td width='25%' align = 'left'  class = 'leftform' colspan ='2'>The form will be posted to: <b>[$email]</b></td></tr>
	  
 <tr bgcolor='FFFFFF'>
       <td width='75%' colspan ='2' align = 'center' height = '30'>
	   
	         <b>Registration form Created</b>
	   <br>
<form method='post'>
    <div align='center' valign = 'middle'>
      <input type='button' value='Close Window' onclick='window.close()'>
    </div>
  </form>
	   
		 </td>
 </tr>
   </table>
   </form>
  
   <br>
   
   <table width='90%'  border='0' >
   <tr>
   <td align = 'center'> sample registration form </td
   </tr>
   </table>
   
  <br>
   
   <center>
 <table width='50%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>
 <tr bgcolor='FFFFFF'>

 <td width='25%' align = 'left'  class = 'leftform'>First Name: </td>
 <td width='75%' align = 'left'><input type='text' name='fname' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>

   <tr bgcolor='FFFFFF'>
  <td width='25%' align = 'left'  class = 'leftform'>Last Name: </td>
 <td width='75%' align = 'left'><input type='text' name='lname' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Email: </td>
 <td width='75%' align = 'left'><input type='text' name='name' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Address1: </td>
 <td width='75%' align = 'left'><input type='text' name='add1' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
    <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Address2: </td>
 <td width='75%' align = 'left'><input type='text' name='add2' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
  
  
  
  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>PostCode: </td>
 <td width='75%' align = 'left'><input type='text' name='pcode' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
    <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>City: </td>
 <td width='75%' align = 'left'><input type='text' name='city' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
    <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Phone: </td>
 <td width='75%' align = 'left'><input type='text' name='phone' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
    <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Mobile: </td>
 <td width='75%' align = 'left'><input type='text' name='mobile' style='background-color:#F2F2F2' disabled size='30'></td>
  </tr>
  
  
    <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Country: </td>
 <td width='75%' align = 'left'>
 <select name='country'>
            <option selected>Anguilla</option>
            <option>Antigua</option>
            <option>Africa</option>
            <option>Uk</option>
			<option>USA</option>
          </select>
 
 </td>
  </tr>
  
  
  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Comment: </td>
 <td width='75%' align = 'left'><TEXTAREA NAME='comments' COLS=40 ROWS=6 style='background-color:#F2F2F2' disabled></TEXTAREA>
  </td>
  </tr>
  
       <tr bgcolor='FFFFFF'>
	  <td width='25%' align = 'center'  class = 'leftform' colspan ='2'>[reset button] &nbsp; [submit bottom]</td>
	  </tr>
  
  
  </table>
  </center>
    ";



}// end function












//-------------------show posted news and gallery

function sp_items($type,$id) {
echo "<table width='90%' border='0'>
       <tr>
	   <td align = 'center'>
	   	         <b>Entry updated</b>
	   <br>
<form method='post'>
    <div align='center' valign = 'middle'>
      <input type='button' value='Close Window' onclick='window.close()'>
    </div>
  </form>
  </td>
  </tr>
  <table>";
      
}//end function

//--------------------show news

function snews($type,$id) {

echo "Please click news items to view info on this news page ";


}// end function


//--------------------show gallery

function sgallery($type,$id) {

echo "Please click gallery items to view info on this gallery page ";


}// end function

?>
