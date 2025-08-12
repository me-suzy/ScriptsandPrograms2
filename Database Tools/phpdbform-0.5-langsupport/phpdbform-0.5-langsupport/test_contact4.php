<?php
// this demonstrates how to use the filter
include_once("phpdbform/phpdbform_main.php");
include_once("phpdbform/phpdbform_db.php");

include_once("phpdbform/phpdbform_textbox.php");
include_once("phpdbform/phpdbform_static_listbox.php");
include_once("phpdbform/phpdbform_checkbox.php");
include_once("phpdbform/phpdbform_listbox.php");
include_once("phpdbform/phpdbform_static_radiobox.php");
include_once("phpdbform/phpdbform_date.php");
include_once("phpdbform/phpdbform_textarea.php");
include_once("phpdbform/phpdbform_hidden.php");

check_login(1);

// the last 2 fields are for selection form
$form = new phpdbform( $phpdbform_main->db, "contact", "cod", "name,email", "name" );

// if we are using the selection form, we can also add a filter!
$form->add_filter( "email", "Filter e-mail:", 50 );

new phpdbform_textbox( $form, "name", "Name:", 30 );
new phpdbform_textbox( $form, "email", "E-mail:", 30 );
new phpdbform_static_listbox( $form, "sex", "Male", "male,female" );
new phpdbform_checkbox( $form, "active", "Active?", "Y", "N" );
new phpdbform_listbox( $form, "type", "Type", $phpdbform_main->db, "type", "cod", array("cod","type"), "type" );
new phpdbform_static_radiobox( $form, "os", "Operating System:", "linux,windows,unix" );
new phpdbform_date( $form, "birthday", "BirthDay", "fmtEUR" ); // fmtUSA, fmtEUR, fmtSQL
new phpdbform_textarea( $form, "obs", "Obs", 40, 10 );
new phpdbform_hidden( $form, "value" );

$form->fields["value"]->value = 123;
draw_adm_header( "Test Contact 4" );
$form->process();
?>

<table border="1" cellspacing="0" cellpadding="5" bordercolor="#0000FF" bgcolor="#EDEDEF" frame="border" rules="groups">
<tr bgcolor="#F0E1A6">
    <td colspan=3><b>Select:</b><br><?php $form->selform->draw(); ?></td>
</tr>
<?php $form->draw_header(); ?>
<tr>
    <td colspan=3><?php $form->fields["name"]->draw(); ?></td>
</tr>
<tr>
    <td colspan=3><?php $form->fields["email"]->draw(); ?></td>
</tr>
<tr>
    <td><?php $form->fields["sex"]->draw(); ?></td>
    <td><?php $form->fields["active"]->draw(); ?></td>
    <td><?php $form->fields["type"]->draw(); ?></td>
</tr>
<tr>
    <td colspan=2><?php $form->fields["os"]->draw(); ?></td>
    <td><?php $form->fields["birthday"]->draw(); ?></td>
</tr>
<tr>
    <td colspan=3><?php $form->fields["obs"]->draw(); ?></td>
</tr>
<tr>
    <td><?php $form->draw_delete_button( "Delete record" ); ?></td>
    <td>&nbsp;</td>
    <td><?php $form->draw_submit( "Send", false ); ?></td>
</tr>
</table>
<?php $form->draw_footer(); ?>
<br><br><hr>
version 2: a little less ugly<br>
This isn't a nice design, but it shows how to draw your own form. ;-)
<?php
draw_adm_footer();
?>
