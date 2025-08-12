<?php

// Processing templates

$tmpl = new Template ( "html/footer.html" );

$tmpl -> param ( 'design_dir', "design/" . $design_directory . "/" );

echo $tmpl -> parse();

?>