<?php
// +--------------------------------------------------------------------+
// | DO NOT REMOVE THIS                                                 |
// +--------------------------------------------------------------------+
// | export-single.php                                                  |
// | Export as single document.                                         |
// +--------------------------------------------------------------------+
// | Author:  Cezary Tomczak [www.gosu.pl]                              |
// | Project: SimpleDoc                                                 |
// | URL:     http://gosu.pl/php/simpledoc.html                         |
// | License: GPL                                                       |
// +--------------------------------------------------------------------+

require 'shared/prepend.php';

$id = get('id');
$name = get_name($id);
$html = fetch_document($id);

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"$name\"");
header("Pragma: no-cache");
header("Expires: 0");

echo $html;

?>