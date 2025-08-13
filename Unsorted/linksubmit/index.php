<?php
include "header.php";
?>
<?php
$data = file('links.txt');
$data = array_reverse($data);
foreach($data as $element) {
    $element = trim($element);
    $pieces = explode("|", $element);
    echo $pieces[2] . $pieces[1] . "" . $pieces[0] . "";
}
?> 
<?php
include "footer.php";
?>