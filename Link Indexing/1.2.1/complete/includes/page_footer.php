<?php
// parse footer template
$t->set_file("page_footer", "overall_footer");
$t->parse("page_all", "page_footer", true);

// output entire page
$t->p("page_all");
?>