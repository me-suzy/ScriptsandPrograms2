<?php
include("mysql.php");
// open the .sql file
$fp = @fopen("mysql.sql", "r");
$file = fread($fp, 80000);
fclose($fp);

$lines = explode(';',  $file); // split the .sql into sepearte queries
$cnt = count($lines); // count array elements

// database connection string


// loop each query and execute
for($j=0; ($j<$cnt-1); $j  ) {
    if(!mysql_query($lines[$j])) {
            // echo any errors
            echo "Error on line $j of Query:<br>";
            echo $lines[$j]."<br><Br>";
            die;
    }
}
// show confirmation
echo "Done";
?>