<?php

//variable declarations
$counter_data = "counterdata.txt"; //counter data file
$image_dir = "/digits/1";	//image directory
$style = ""; //enter text for text.Anything else for graphics
//check if file exists
if(!($fp = fopen($counter_data,"r+"))) die ("cannot open counter file");
//read in the current count from the file
$count = (int) fread($fp, 20);
//close the file
fclose($fp);
//increment count
$count++;
//text counter
if ($style == "text") 
{
	echo $count;
}
//graphical counter
else 
{
	$digit = strval($count);
	for ($i = 0; $i < strlen($count); $i++) 
	{
		echo "<img src=$image_dir/$digit[$i].gif>";
	}
}
$fp = fopen($counter_data, "w");
fwrite($fp , $count);
//close the file
fclose($fp);
?>
