<?php
include_once("phpdbform/phpdbform_main.php");
include_once("phpdbform/phpdbform_db.php");

include_once("phpdbform/phpdbform_textbox.php");
include_once("phpdbform/phpdbform_static_listbox.php");
include_once("phpdbform/phpdbform_checkbox.php");
include_once("phpdbform/phpdbform_exlistbox.php");
include_once("phpdbform/phpdbform_static_radiobox.php");
include_once("phpdbform/phpdbform_date.php");
include_once("phpdbform/phpdbform_textarea.php");

check_login(1);

$form = new phpdbform( $phpdbform_main->db, "contact", "cod", "name,email", "name" );

new phpdbform_textbox( $form, "name", "Name:", 30 );
new phpdbform_textbox( $form, "email", "E-mail:", 50 );
new phpdbform_static_listbox( $form, "sex", "Sex", "male,female" );
new phpdbform_checkbox( $form, "active", "Active?", "Y", "N" );
new phpdbform_exlistbox( $form, "type", "Type", $phpdbform_main->db, "type", "cod", "type", "type", "address,nb", "address,nb" );
new phpdbform_textbox( $form, "address", "Address:", 30 );
new phpdbform_textbox( $form, "nb", "Number:", 10 );
new phpdbform_static_radiobox( $form, "os", "Operating System:", "linux,windows,unix" );
new phpdbform_date( $form, "birthday", "BirthDay", "fmtEUR" ); // fmtUSA, fmtEUR, fmtSQL
new phpdbform_textarea( $form, "obs", "Obs", 30, 10 );
$form->javascript = "onSubmit=\"alert('enviando'); return true;\"";


draw_adm_header( "Test Contact" );

if( isset($_GET["id"]) ) $form->keyvalue = array( intval($_GET["id"]) );

$form->process();
$form->draw();
draw_adm_footer();
?>