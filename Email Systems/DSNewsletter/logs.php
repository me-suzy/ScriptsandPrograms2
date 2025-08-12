 <?
include("header.php");
?>
 <?php
 include("config.php");
 $id = $_POST['id'];
 $read = file_get_contents("$place");
 $place = "logs/$id";
 if ($read != ""){
 echo "Article/Log is/has been";
readfile($place);
} else {
echo "Logfile not found.";
}
?>
<?
include("footer.php");
?>
