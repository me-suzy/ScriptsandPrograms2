<?
//Just edit this string and you're ready
$file = "quote.txt";//file containing data/quotes
//You don't have to edit anything below here

//Just making sure you specified the file name
if(!$file){echo "You haven't specified the file containing your data";exit;};

//Opens the file and gets the data
$array = file($file);
$count = count($array);//counts the number of quotes
$quote = $_REQUEST["quote"];
$rand = rand(0, --$count);//generates a random
if (!$quote){ echo $array[$rand];}
else {echo $array[--$quote];};
?>