<?php
include("common.php");

?>
<html>
<body>
<?php

if (session_is_registered("whossession")) {
    if (($_SESSION['who']) == "admin") {
        $query = "SHOW TABLES;";

        $result = db_query($query);

        while ($row = db_fetch_array($result)) {
            foreach($row as $test) {
                $query1[] = "SHOW CREATE TABLE {$test};";
            } 
        } 
        foreach($query1 as $test) {
            $result = db_query($test);
            while ($row = db_fetch_array($result)) {
                foreach($row as $test2) {
                    echo $test2 . "&nbsp;&nbsp;&nbsp;";
                } 
                echo "<br>";
            } 
            echo "<br><br>";
        } 
    } else
        echo "<center>You must login to use this feature</center>";
} else
    echo "<center>You must login to use this feature</center>";

?>
</body>
</html>
