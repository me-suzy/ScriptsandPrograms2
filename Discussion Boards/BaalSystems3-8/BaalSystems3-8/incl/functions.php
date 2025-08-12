<?
    function getTimeElapsed() {
        global $start_time;
        
        $end_time=split(" ", microtime());
        $end_time=$end_time[1]+$end_time[0];
        
        return number_format(($end_time-$start_time), 3);
    }
    
    function db_connect() {
        global $db;
        if (!mysql_connect($db["host"], $db["user"], $db["pass"]))
            db_report_error("Cannot not connect to the database");
        if (!mysql_select_db($db["dbname"]))
            db_report_error("Cannot not select the database <i>" . $db["dbname"] . "</i>");
    }
    
    function db_report_error($message="", $query="") {
        echo("Database error occured: " . mysql_error() . "<br><b>" . $message . "</b>" . ".<br>Using query: <b>" . $query . "</b><br>Error in " . $_SERVER["PHP_SELF"]);
        exit;
    }
    
    function db_query($query) {
        $result=mysql_query($query);
        if (!$result) {
            db_report_error("Error while executing query", $query);
        }
        return $result;
    }
    
    function db_num_rows($result) {
        return mysql_num_rows($result);
    }
    
    function db_fetch_row($result) {
        $row=mysql_fetch_assoc($result);
        if ($row && count($row) > 0) {
            foreach($row as $key=>$value) {
                $row[$key]=stripslashes($value);
            }
        }
        return $row;
    }
    
    function db_fetch_array($result) {
        $row=mysql_fetch_array($result);
        if ($row && count($row) > 0) {
            foreach($row as $key=>$value) {
                $row[$key]=stripslashes($value);
            }
        }
        return $row;
    }
    
    function db_affected_rows() {
        return mysql_affected_rows();
    }
?>