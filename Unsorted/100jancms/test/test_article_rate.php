<?php 
/***************************************\
|					|
|	    100janCMS v1.01		|
|    ------------------------------	|
|    Supplied & Nulled by GTT '2004	|
|					|
\***************************************/
include "../100jancms/config_connection.php";

//receive posted data
$id=$_GET["id"];
$vote=$_GET["vote"];
$sentby=$_GET["sentby"];
$cookie_name=$_GET["cookie_name"];


//load data
$query="SELECT * FROM ".$db_table_prefix."articles_items WHERE idArtc=".$id;
$result=mysql_query($query);
$row = mysql_fetch_array($result); //wich row
$rate=$row["rate"];



	$new_rate=$rate+$vote;
			
			$update_query="UPDATE ".$db_table_prefix."articles_items  SET rate='".$new_rate."' WHERE idArtc=".$id;
		    mysql_query($update_query) or die($update_query);
			
	//set cookie
	$hour= date("d",time());
	$minute= date("i",time());
	$hour=(((24-$hour)*60)-$minute)*60; //how many sec till midnight

	setcookie ($cookie_name, $id , time()+$hour); //till midnight
    header("Location: ".$sentby."?id=".$id."&voted=1"); 

?>