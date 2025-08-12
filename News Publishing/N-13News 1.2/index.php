<?php
ob_start();
include 'config.php';


session_start();
ob_end_clean();

$tmpname = $_SESSION['name'];

if($tmpname == ""){
        }else{
$sql = "SELECT user FROM `$newsadmin` WHERE user = '$tmpname'";
$query = mysql_query($sql);
$tmpname = mysql_result($query,0);
}
$sql = "SELECT nppage FROM `$newsoptions` WHERE 1";
$query = mysql_query($sql);
$nppage = mysql_result($query,0);

$sql = "SELECT cppage FROM `$newsoptions` WHERE 1";
$query = mysql_query($sql);
$cppage = mysql_result($query,0);
        $id = $_GET['id'];
        if(is_numeric($id)){ }else{ $id = 0; }
$sql = "SELECT * FROM `$newscomments` WHERE pid = '$id'";
$query = mysql_query($sql);
$totalcomments = mysql_num_rows($query);

$sql = "SELECT * FROM `$newstable`";
$query = mysql_query($sql);
$totalnews = mysql_num_rows($query);


$from = $_GET['from'];
if(is_numeric($from)){ }else{ $from = 0; }
if($from == ""){
$from = 0;
}
$page = $_GET['page'];
if(is_numeric($page)){ }else{ $page = 1; }
if($page == ""){
$page = 1;
}




$sql = "SELECT commentsorder FROM `$newsoptions` WHERE 1";
$query = mysql_query($sql);
$commentsorder = mysql_result($query,0);

$sql = "SELECT newsorder FROM `$newsoptions` WHERE 1";
$query = mysql_query($sql);
$newsorder = mysql_result($query,0);

$sql =  "SELECT * FROM $newstable ORDER BY 0+ID $newsorder LIMIT $from ,$nppage";
$result = mysql_query($sql)
        or die ("Couldn't execute query.");

$sql2 = "SELECT header FROM $newsoptions WHERE 1";
$result2 = mysql_query($sql2);
$sql3 = "SELECT footer FROM $newsoptions WHERE 1";
$result3 = mysql_query($sql3);
$sql4 = "SELECT template FROM $newsoptions WHERE 1";
$result4 = mysql_query($sql4);
$header = mysql_result($result2,0);
$footer = mysql_result($result3,0);
$template = mysql_result($result4,0);


echo $header;

if($_GET['comments'] == ""){
while($row = mysql_fetch_array( $result )) {
$sql5 = "SELECT * FROM $newscomments WHERE pid = '$row[id]'";
$query5 = mysql_query($sql5);
$numcomments = mysql_num_rows($query5);

$template2 = $template;
$template2 = str_replace("{title}",$row[title],$template2);
$template2 = str_replace("{story}",$row[story],$template2);
$template2 = str_replace("{author}",$row[author],$template2);
$template2 = str_replace("{date}",$row[date],$template2);
$template2 = str_replace("{id}",$row[id],$template2);
$template2 = str_replace("[email]","<a href=\"mailto:$row[email]\">",$template2);
$template2 = str_replace("[/email]","</a>",$template2);
$template2 = str_replace("{email}",$row[email],$template2);
$template2 = str_replace("[comments]","<a href=\"?comments=true&amp;id=$row[id]\">",$template2);
$template2 = str_replace("[/comments]","</a>",$template2);
$template2 = str_replace("{comments}",$numcomments,$template2);
$template2 = str_replace("[img]","<img src=\"",$template2);
$template2 = str_replace("[/img]","\">",$template2);
if($row[avatar] == ""){
$template2 = str_replace("{avatar}","",$template2);
} else {
$template2 = str_replace("{avatar}","<img src=\"$row[avatar]\">",$template2);
}
            $sql = "SELECT * FROM $newssmilies";
            $query = mysql_query($sql);
            while($row = mysql_fetch_array( $query )) {
            $template2 = str_replace($row['keycode'],"<img alt=\"$row[keycode]\" src=\"$row[path]\">",$template2);
            }
echo $template2;
}


$i = ($totalnews / $nppage);
$i = ceil($i);
$s = 1;

function previous($from,$nppage){
if($from == 0){
        return "";
        }else{
        $b = ($from - $nppage);
        return "<a href=\"?from=$b\">";
}
}
$prev = previous($from,$nppage);
echo $prev;


function pages($nppage, $s , $i, $from){
while ($s <= $i){
$y = $s * $nppage;
$x = $y - $nppage;
if($from == $x){
$c .= "$s ";
}else{
$c .= "<a href=\"?from=$x&amp;page=$s\">$s</a> ";
}
$s++;
}
return $c;
}


function nextlink($totalnews, $nppage, $page, $from){
if($page >= ($totalnews / $nppage)){
return "";
}else{
$b = ($from + $nppage);
$c = ($page + 1);
return "<a href=\"?from=$b&amp;page=$c\">";
}
}


$sql = "SELECT npagintation FROM `$newsoptions` WHERE 1";
$query = mysql_query($sql);
$npagintation = mysql_result($query,0);
$npagintation = str_replace("[prev-link]", previous($from,$nppage),$npagintation);
if(previous($from,$nppage) == ""){
$npagintation = str_replace("[/prev-link]", "",$npagintation);
}else{
$npagintation = str_replace("[/prev-link]", "</a>",$npagintation);
}
$npagintation = str_replace("[next-link]", nextlink($totalnews, $nppage, $page, $from),$npagintation);
if(nextlink($totalnews, $nppage, $page, $from) == ""){
$npagintation = str_replace("[/next-link]", "",$npagintation);
}else{
$npagintation = str_replace("[/next-link]", "</a>",$npagintation);
}
$npagintation = str_replace("{pages}", pages($nppage,$s,$i,$from),$npagintation);

echo $npagintation;


}else{
        $id = $_GET['id'];
        if(is_numeric($id)){ }else{ $id = 0; }
        $sql2 = "SELECT * FROM $newstable WHERE id = '$id'";
        $result2 = mysql_query($sql2);
        $sql3 = "SELECT comments FROM $newsoptions WHERE 1";
        $result3 = mysql_query($sql3);
        $commentstemplate = mysql_result($result3,0);
        while($row = mysql_fetch_array($result2)) {
        $sql5 = "SELECT * FROM $newscomments WHERE pid = '$row[id]' ORDER BY 0+ID $commentsorder LIMIT $from ,$cppage";
        $query5 = mysql_query($sql5);
        $sql = "SELECT * FROM $newscomments WHERE pid = '$row[id]'";
        $query = mysql_query($sql);
        $numcomments = mysql_num_rows($query);

        $template2 = $template;
        $template2 = str_replace("{title}",$row[title],$template2);
        $template2 = str_replace("{story}",$row[story],$template2);
        $template2 = str_replace("{author}",$row[author],$template2);
        $template2 = str_replace("{date}",$row[date],$template2);
        $template2 = str_replace("{id}",$row[id],$template2);
        $template2 = str_replace("[email]","<a href=\"mailto:$row[email]\">",$template2);
        $template2 = str_replace("[/email]","</a>",$template2);
        $template2 = str_replace("{email}",$row[email],$template2);
        $template2 = str_replace("[comments]","",$template2);
        $template2 = str_replace("[/comments]","",$template2);
        $template2 = str_replace("{comments}",$numcomments,$template2);
        $template2 = str_replace("[img]","<img src=\"",$template2);
        $template2 = str_replace("[/img]","\">",$template2);
        if($row[avatar] == ""){
        $template2 = str_replace("{avatar}","",$template2);
        } else {
        $template2 = str_replace("{avatar}","<img src=\"$row[avatar]\">",$template2);
        }
            $sql = "SELECT * FROM $newssmilies";
            $query = mysql_query($sql);
            while($row = mysql_fetch_array( $query )) {
            $template2 = str_replace($row['keycode'],"<img src=\"$row[path]\">",$template2);
            }
        echo $template2;
             while($row = mysql_fetch_array($query5)) {
                     $commentstemplate2 = $commentstemplate;

             $commentstemplate2 = str_replace("{date}",$row[date],$commentstemplate2);
             $author = strip_tags($row['user']);
             $commentstemplate2 = str_replace("{author}",$author,$commentstemplate2);
             $message = strip_tags($row['message']);
             $commentstemplate2 = str_replace("{message}",$message,$commentstemplate2);
             $email2 = strip_tags($row['email']);
             $commentstemplate2 = str_replace("{email}",$email2,$commentstemplate2);
             if($email2 == ""){
             $commentstemplate2 = str_replace("[email]","",$commentstemplate2);
             $commentstemplate2 = str_replace("[/email]","",$commentstemplate2);
             }else{
             $commentstemplate2 = str_replace("[email]","<a href=\"mailto:$row[email]\">",$commentstemplate2);
             $commentstemplate2 = str_replace("[/email]","</a>",$commentstemplate2);
             }
             $sql = "SELECT * FROM $newsfilter";
             $result = mysql_query($sql);
                       while($row = mysql_fetch_array($result)) {
                       $commentstemplate2 = eregi_replace($row['filter'],$row['alt'],$commentstemplate2);
                       }
             echo $commentstemplate2;
              }
        }







$i = ($totalcomments / $cppage);
$i = ceil($i);
$s = 1;

function cprevious($from,$cppage){
if($from == 0){
        return " ";
        }else{
        $b = ($from - $cppage);
        $id = $_GET['id'];
        if(is_numeric($id)){ }else{ $id = 0; }
        return "<a href=\"?comments=true&amp;id=$id&amp;from=$b\"> ";
}
}
$prev = cprevious($from,$cppage);
echo $prev;


function cpages($cppage, $s , $i, $from){
while ($s <= $i){
$y = $s * $cppage;
$x = $y - $cppage;
if($from == $x){
$c .= "$s ";
}else{
        $id = $_GET['id'];
        if(is_numeric($id)){ }else{ $id = 0; }
$c .= "<a href=\"?comments=true&amp;id=$id&amp;from=$x&amp;page=$s\">$s</a> ";
}
$s++;
}
return $c;
}


function cnextlink($totalcomments, $cppage, $page, $from){
if($page >= ($totalcomments / $cppage)){
return " ";
}else{
$b = ($from + $cppage);
$c = ($page + 1);
        $id = $_GET['id'];
        if(is_numeric($id)){ }else{ $id = 0; }
return "<a href=\"?comments=true&amp;id=$id&amp;from=$b&amp;page=$c\">";
}
}


$sql = "SELECT cpagintation FROM `$newsoptions` WHERE 1";
$query = mysql_query($sql);
$cpagintation = mysql_result($query,0);
$cpagintation = str_replace("[prev-link]", cprevious($from,$cppage),$cpagintation);
$cpagintation = str_replace("[/prev-link]", "</a>",$cpagintation);
$cpagintation = str_replace("[next-link]", cnextlink($totalcomments, $cppage, $page, $from),$cpagintation);
$cpagintation = str_replace("[/next-link]", "</a>",$cpagintation);
$cpagintation = str_replace("{pages}", cpages($cppage,$s,$i,$from),$cpagintation);

echo $cpagintation;







                if($_POST['B1'] == ""){
                $sql2 = "SELECT commentsform FROM $newsoptions WHERE 1";
                $result2 = mysql_query($sql2);
                if($tmpname == ""){
                        }else{
                $sql = "SELECT email FROM $newsadmin WHERE user = '$tmpname'";
                $query = mysql_query($sql);
                $email = mysql_result($query,0);
                }
                $id = $_GET['id'];
                if(is_numeric($id)){ }else{ $id = 0; }
                $commentsform = mysql_result($result2,0);
                $commentsform = str_replace("{id}",$id,$commentsform);
                $commentsform = str_replace("&lt;","<",$commentsform);
                $commentsform = str_replace("&gt;",">",$commentsform);
                $commentsform = str_replace("{name}","$tmpname",$commentsform);
                $commentsform = str_replace("{email}","$email",$commentsform);
                echo $commentsform;
                }else{

                $sql = "SELECT * FROM `$newsadmin` WHERE user='$_POST[T1]'";
                $query = mysql_query($sql);
                $numrows = mysql_num_rows($query);

                if($numrows > 0){

                           if($tmpname == $_POST['T1']){
                                   }else{
                                         echo "<font color=\"FF0000\">Please choose a different name. or log into the admin area <a href=\"admin.php\">here</a>.";
                                         die;
                                         }

                }
                $sql2 = "SELECT commentsform FROM $newsoptions WHERE 1";
                $result2 = mysql_query($sql2);
                $commentsform = mysql_result($result2,0);
                $id = $_GET['id']; $id = strip_tags($id);
                if(is_numeric($id)){ }else{ $id = 0; }
                $commentsform = str_replace("{id}",$id,$commentsform);
                $commentsform = str_replace("&lt;","<",$commentsform);
                $commentsform = str_replace("&gt;",">",$commentsform);
                $commentsform = str_replace("{name}","$tmpname",$commentsform);
                $commentsform = str_replace("{email}","$email",$commentsform);
                if($_POST['T1'] == ""){
                        echo "<font color=\"FF0000\">Please enter a username.</font>";
                        echo $commentsform;
                        }elseif($_POST['S1'] == ""){
                                echo "<font color=\"FF0000\">Please enter a message.</font>";
                                echo $commentsform;
                        }else{
                $id = $_GET['id'];
                if(is_numeric($id)){ }else{ $id = 0; }
                $sql = "SELECT * FROM `$newscomments` WHERE pid = '$id'";
                $result = mysql_query($sql);
                $comm = mysql_num_rows($result);
                $comm = $comm + 1;
                $user = $_POST['T1'];
                $user = strip_tags($user);
                $email = $_POST['T2'];
                $email = strip_tags($email);
                $message = $_POST['S1'];
                $message = strip_tags($message);
                $sql = "SELECT commentstime FROM `$newsoptions` WHERE 1";
                $query = mysql_query($sql);
                $date2 = mysql_result($query,0);
                $date = gmdate($date2);
                $sql = "INSERT INTO $newscomments (user,email,date,message,pid,id) VALUES ('$user','$email','$date','$message','$id','$comm')";
                $result = mysql_query ($sql);
                $sql = "UPDATE $newstable SET comments = '$comm' WHERE id = '$_GET[id]'";
                $result = mysql_query ($sql);
                echo "Comment added.";
                echo "<SCRIPT LANGUAGE=\"JavaScript\">";
                echo "window.location=\"index.php?comments=true&id=$id\"";
                echo "</script>";
                        }
                }
}
echo "\n$footer";



?>