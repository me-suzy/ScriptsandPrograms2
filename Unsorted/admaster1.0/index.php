<?
include "sys/Conf.inc";
include "sys/template/Template.inc";
include "sys/db/DBObject.inc";

$index = new Template ("Base", "Index");
print $index->display ();
?>