<?php
include_once("phpdbform/phpdbform_main.php");
include_once("phpdbform/phpdbform_db.php");

require_once("phpdbform/phpdbform_textbox.php");
require_once("phpdbform/phpdbform_checkbox.php");

check_login(1);

$form = new phpdbform( $phpdbform_main->db, "type", "cod", "type,business", "type" );
new phpdbform_textbox( $form, "type", "Type:" );
new phpdbform_checkbox( $form, "business", "Business related?", "1", "0" );
new phpdbform_textbox( $form, "address", "Address:", 30 );
new phpdbform_textbox( $form, "nb", "Number:", 10 );

draw_adm_header("Type");
$form->process();
$form->draw();

reset( $form->fields["type"]->form->fields );
while( $fld = each( $form->fields["type"]->form->fields ) )
{
	echo $fld[1]->field."(".$fld[1]->size.",".$fld[1]->maxlength.") =".$fld[1]->value."<br>";
}

draw_adm_footer();
?>
