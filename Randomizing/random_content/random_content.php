<?

/* 
   Random Content v1.0
   www.triumphantmedia.com/resources
*/

$random_content="random_content.txt"; 
$random_content=file("$random_content");
$display=rand(0, sizeof($random_content)-1);
echo $random_content[$display];

?>