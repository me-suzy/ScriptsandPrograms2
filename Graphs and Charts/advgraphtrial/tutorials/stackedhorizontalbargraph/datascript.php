<?php

function datascript() {


    // This datascript() function is designed to work with
    // the graphing functions.
    // In this demo script the array is populated with 
    // hardcoded values, however in reality these would be 
    // acquired from another source eg. a database.
    
    // Any code for accessing a database should go here.
    // In addition, if session data exists then that data is
    // also accessible at this point.

    // Example code to get the value of a variable 'userid' 
    // from the session:-
    /*
        session_start();
        $userid = $_SESSION['userid'];
    */


    // Populate an array with all the data information
    $lines[0] = "data1series1: 150";
    $lines[1] = "data2series1: 100";
    $lines[2] = "data3series1: 130";
    $lines[3] = "data4series1: 40";
    $lines[4] = "data5series1: 150";
    $lines[5] = "data6series1: 35";

    $lines[6] = "data1series2: 456.98";
    $lines[7] = "data2series2: 374.38";
    $lines[8] = "data3series2: 473.39";
    $lines[9] = "data4series2: 343.45";
    $lines[10] = "data5series2: 123.98";
    $lines[11] = "data6series2: 429.38";

    $lines[12] = "data1series3: 156.98";
    $lines[13] = "data2series3: 274.38";
    $lines[14] = "data3series3: 173.39";
    $lines[15] = "data4series3: 243.45";
    $lines[16] = "data5series3: 223.98";
    $lines[17] = "data6series3: 129.38";

    // return the array to the graph function    
    return $lines;
}





// DO NOT ADD ANYTHING (not even a space character) BEYOND THIS POINT !
?>