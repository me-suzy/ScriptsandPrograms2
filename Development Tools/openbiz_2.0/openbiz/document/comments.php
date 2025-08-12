<?php
   $q1 = $_POST["Q1"];
   $q2 = $_POST["Q2"];
   $q3 = $_POST["Q3"];
   $q4 = $_POST["Q4"];
   $date = gmdate( "d/M/Y:H:i:s");
   $str = $date."<br>";
   $str .= "Like it: ".$q1."<br>";
   $str .= "Want to use it: ".$q2."<br>";
   $str .= "Comments: ".$q3."<br>";
   $str .= "Email: ".$q4."<hr>";
   if($fd = @fopen("comments.html", "a")) {
      fputs($fd, $str); 
      fclose($fd); 
   } 
   header("Location: index.html");
?>