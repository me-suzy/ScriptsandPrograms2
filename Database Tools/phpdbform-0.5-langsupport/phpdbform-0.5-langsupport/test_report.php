<?php
// a Simple report
// if want to see a more complete report, take a look at test_report_wt.php
include_once("phpdbform/phpdbform_main.php");
include_once("phpdbform/phpdbform_report.php");
check_login(1);

$stmt = "select c.cod, c.name, c.email, c.sex, c.active, c.type, t.type as type_name, c.os, c.birthday from contact c left outer join type t on (c.type = t.cod) order by c.email";
$form = new phpdbform_report( $phpdbform_main->db, $stmt, "Contacts" );

function makelink( &$field )
{
	$txt = "<a href=\"test_contact.php?id={$field->value}\">{$field->value}</a>";
	$field->value = $txt;
}

function showhello( &$field )
{
	global $i;
	$field->value .= " ".(++$i);
}

$i = 0;

draw_adm_header( "Testing report" );
$form->process();
$form->fields["cod"]->onprint = "makelink";
$form->add_dummy( "dummy", "Dummy Field", "Hello " );
$form->fields["dummy"]->onprint = "showhello";
$form->draw();
$form->free();
print "<a href=\"test_contact.php\">Add contact</a>";
draw_adm_footer();
?>
