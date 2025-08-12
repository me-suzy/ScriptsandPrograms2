<?php
/*
--------------------------------------------------------------
|Sgal 2.0                                                    |
|(c)Adrian Wisernig 2005                                     |
|For help or more scripts go to:                             |
|http://www.statc.net                                        |
--------------------------------------------------------------
*/
session_start();
?>
<html>
<head>
<title>Sgal 2 Admin</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
a  { color: #ff9900; text-decoration: none }
a:visited  { color: #ff9900; text-decoration: none }
a:hover  { color: #ff9900; text-decoration: none }
a:active  { color: #ff9900; text-decoration: none }
</style>
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table id="Table_01" width="800" height="600" border="0" cellpadding="0" cellspacing="0" align="center">
        <tr>
                <td rowspan="2">
                        <img src="images/template_01.gif" width="458" height="136" alt="Sgal 2.0- created by Statc Web Solutions"></td>
                <td colspan="3">
                        <img src="images/template_02.gif" width="342" height="102" alt=""></td>
        </tr>
        <tr height="34">
                <td height="34">
                        <img src="images/template_03.gif" width="22" height="34" alt=""></td>
                <td rowspan="2" align="center" width="278" background="images/template_04.gif"><a href="admin.php">Main</a>  <a href="admin.php?page=add">Add</a> <a href="admin.php?page=options">Options</a></b></td>
                <td height="34">
                        <img src="images/template_05.gif" width="42" height="34" alt=""></td>
        </tr>
        <tr>
                <td colspan="2">
                        <img src="images/template_06.gif" width="480" height="16" alt=""></td>
                <td>
                        <img src="images/template_07.gif" width="42" height="16" alt=""></td>
        </tr>
        <tr>
                <td colspan="4" valign="top" width="800">
                        <div width="800" style="overflow:auto;height:410px">
<?php
include 'config.php';
include 'func.php';
// display
if(!isset($_SESSION['user'])&&!isset($_POST['login']))
   {
   echo'
        <center><form action="" method="POST">
        <font color="#ff9900"><b>Username</b></font>:<input name="user" type="text"><br>
        <font color="#ff9900"><b>Password:</b></font> <input type="password" name="password">
        <br><input type="submit" name="loginb" value="Login">
        </form></center>';

   }
   else
   {
// if user is logged in
    if(empty($_GET['page']))
        {
        echo'<table width="800">';
        if(is_file("images.xml"))
        {
        $parser = xml_parser_create();
        $images=null;
        $images=array();
        xml_set_element_handler($parser, 'startElement', 'endElement');
        xml_set_character_data_handler($parser, 'characterData');
        $data = xml_parse($parser, file_get_contents('images.xml'));
        if(!$data) {
                        die(sprintf('XML error: %s at line %d',
                        xml_error_string(xml_get_error_code($parser)),
                        xml_get_current_line_number($parser)));
                   }
        xml_parser_free($parser);
        for($i=0;$i<count($images);$i++)
                {
                 if($i%4==0 && $i>1){echo'</tr></tr>';}
                 echo'<td align="center"><img src="thumb.php?url=' . $images[$i]['url'] . '"><br>Upload Date:' . $images[$i]['upDate'] . '<br>Last Date Viewed:' . $images[$i]['lastDate'] . '<br>Hits:' . $images[$i]['hits'] . '<br>' . $images[$i]['caption'] . '<br><a href="admin.php?page=delete&image=' . $images[$i]['url'] . '">Delete</a></td>';
                }
        echo'</table>';
        }
        else
        {
         echo'<center>No images uploaded.</center>';
        }
        }

   if($_GET['page']=="add")
        {
                        if(!empty($_POST['uploadb']))
                        {




                                list($img_name,$img_ext)=explode('.',$_FILES['image']['name']);
                                if ($img_ext=="jpg" || $img_ext=="gif" || $img_ext=="png")
                                {
                                $copy=copy($_FILES['image']['tmp_name'], 'images/'.$_FILES['image']['name']);
                                $images=null;
                                $images=array();
                                if(is_file("images.xml"))
                                {
                                $parser = xml_parser_create();
                                xml_set_element_handler($parser, 'startElement', 'endElement');
                                xml_set_character_data_handler($parser, 'characterData');
                                $data = xml_parse($parser, file_get_contents('images.xml'));
                                if(!$data) {
                                die(sprintf('XML error: %s at line %d',
                                xml_error_string(xml_get_error_code($parser)),
                                xml_get_current_line_number($parser)));
                                           }
                                xml_parser_free($parser);
                                }
                                $images[count($images)]=array("imageName"=>$_POST['imgname'],"upDate"=>date("m.d.y"),"lastDate"=>date("m.d.y"),"url"=>'images/'.$_FILES['image']['name'],"caption"=>$_POST['caption'],"hits"=>0);
                                write_xml($images,"images.xml");
                                echo'Image was uploaded. Back to <a href="admin.php">main</a>';
                                }
                        }
                        else
                        {
                                echo'
                                <form action="" method="POST" ENCTYPE="multipart/form-data">
                                <br>Name:<input type="text" name="imgname">
                                <br>Caption:<input type="text" name="caption" size=20>
                                <br>File: <input type="file" name="image" size="30">
                                <br><input type="submit" name="uploadb" value="Upload"></form>';
                        }
        
        
        }
   if($_GET['page']=="stats")
        {
        
        
        
        
        }
  if($_GET['page']=="delete")
        {
        unlink($_GET['image']);
        $parser = xml_parser_create();
        xml_set_element_handler($parser, 'startElement', 'endElement');
        xml_set_character_data_handler($parser, 'characterData');
        $data = xml_parse($parser, file_get_contents('images.xml'));
        if(!$data) {
        die(sprintf('XML error: %s at line %d',
        xml_error_string(xml_get_error_code($parser)),
        xml_get_current_line_number($parser)));
                    }
        xml_parser_free($parser);
        for($i=0;$i<count($images);$i++)
        {
        if($images[$i]['url']==trim($_GET['image']))
                {
                        $images[$i]=null;
                        echo'Image deleted. Back to <a href="admin.php">main.</a>';

                }
        }
        write_xml($images,"images.xml");
        
        
        
        }
  if($_GET['page']=="options")
  
        {
        if(empty($_POST['changeB']))
                {
                 echo'
                 <center><form action="" method="POST">
                        <font color="#ff9900">                Username:<input type="text" name="userupdate" size="24" value="' . $validuser . '"></font>
                        <p><font color="#ff9900">Password:<input type="text" name="passupdate" size="24" value="' . $validpass . '"></font></p>
                        <p><font color="#ff9900">Images per row:<input type="text" name="rowupdate" size="2"></font></p>
                        <p><font color="#ff9900">Display Style:</font></p>
<SELECT NAME="style" SIZE="2"><OPTION VALUE="t">Slide Show</OPTION><OPTION VALUE="g">Gallery Page</OPTION></select>
                        <p><input type="submit" name="changeB" value="Update"></p>
                </form></center>';
                
                
                }
                else
                {
                if(is_file("config.php")){ unlink("config.php");}
                switch ($_POST['style']) {
                             case "t":
                             $intdis=1;
                             break;
                             case "g":
                             $intdis=0;
                             break;
                             }
                $config = '<?php $maximages="' . $_POST['rowupdate'] . '";$validuser= "' . $_POST['userupdate'] . '"; $validpass= "' . $_POST['passupdate'] . '";$intdis= ' . $intdis . ';?>';
                $handle=fopen("config.php","a+");
                $write=fwrite($handle,$config);
                fclose($handle);
                echo'Sgal configuration was updated.';
                }
        
        
        
        }




 }
if(!empty($_POST['loginb']))
        {
         if($_POST['user']==$validuser && $_POST['password']==$validpass)
         {
         $_SESSION['user']=$_POST['user'];
         }
         else
         {
          echo'Invalid user';
         }
         
        }



?>
</div>
                        </td>
        </tr>
        <tr>
                <td colspan="4">
                        <img src="images/template_09.gif" width="800" height="16" alt=""></td>
        </tr>
        <tr>
                <td colspan="4">
                        <img src="images/template_10.gif" width="800" height="20" alt=""></td>
        </tr>
</table>
<br><center><font size="-1">Powered by&nbsp;<a title="Statc.net Web Solutions" href="http://statc.net">Sgal</a></font></center>
</body>
</html>
