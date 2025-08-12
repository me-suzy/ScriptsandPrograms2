<html>
<body>
<?php
////////////////////////////////////////////////////////////////////////

require "Uploader.php";

////////////////////////////////////////////////////////////////////////
function dumpAssociativeArray($array) {
    $res = '';
    $header = false;
    if (is_array($array) && sizeof($array)) {
        $res .= "<table border=1>\n";
        foreach(@$array as $values) {
            if (!$header) {
                $res .= "<th>" . implode("</th><th>", array_keys($values)) . "</th>\n";
                $header = true;
            }
            $res .= "<tr>\n";
            foreach($values as $key => $value) {
                $res .= "<td>" . ($value != '' ? $value : "&nbsp;") . "</td>";
            }
            $res .= "</tr>\n";
        }
        $res .= "</table>\n";
    }
    return $res;
}

////////////////////////////////////////////////////////////////////////
// show debug information
echo nl2br(Uploader::debug()) . "<br>";

// only images
$allowedTypes = array("image/bmp","image/gif","image/pjpeg","image/jpeg","image/x-png");
$uploadPath = 'c:/temp';
$overwrite = true;

$up = new Uploader();
    if ($up->wasSubmitted()) {
    // files were submitted
    echo dumpAssociativeArray($up->uploadTo($uploadPath, $overwrite, $allowedTypes));
// display form
} else {
    echo $up->openForm(basename(__FILE__)). "\n";
    echo $up->fileField(). "<br>\n";
    echo $up->fileField(). "<br>\n";
    echo $up->closeForm();
    
}
// display error
echo "<br>\n" . nl2br($up->error);

////////////////////////////////////////////////////////////////////////

?>
</body>
</html>