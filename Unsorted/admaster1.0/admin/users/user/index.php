<?
include "sys/Conf.inc";
include "sys/template/Template.inc";
include "sys/db/DBObject.inc";

$index = new Template ("AdminUsers", "Index");

print $index->display ();
?>