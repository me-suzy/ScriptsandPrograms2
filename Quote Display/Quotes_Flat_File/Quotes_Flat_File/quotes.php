<?php
    include ('func.php'); // this references the HTML that will be output to the browser.
    $filename = "quotes.db"; // Enter the path to the file with the quotes.
    $lines = file($filename);
    $numQuotes = count ($lines);
    $ranNum = rand(1, $numQuotes);
    $line = $lines[$ranNum-1];
    $element = explode("::",$line);
    $theQuote = $element[0];
    $theRef = $element[1];
    $theLink = $element[2];
//    $output = outputLine($theQuote, $theRef, $theLink);
    $output = outputTable($theQuote, $theRef, $theLink);
    echo $output;
?>

