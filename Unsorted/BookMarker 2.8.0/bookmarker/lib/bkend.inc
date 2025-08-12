<?php
# common code that goes at the end of every page.

# standard error message, and message handler.
include(LIBDIR . "bkmsg.inc");
if (isset ($bk_output_html)) {
  $tpl->set_var(MESSAGES, $bk_output_html);
}

$tpl->parse("BODY", array("body", "standard"));
$tpl->p("BODY");

page_close();
?>
