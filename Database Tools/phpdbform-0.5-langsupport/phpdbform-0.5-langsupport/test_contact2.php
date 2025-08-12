<?php
include_once("phpdbform/phpdbform_main.php");
include_once("phpdbform/phpdbform_db.php");

include_once("phpdbform/phpdbform_textbox.php");
include_once("phpdbform/phpdbform_static_listbox.php");
include_once("phpdbform/phpdbform_checkbox.php");
include_once("phpdbform/phpdbform_listbox.php");
include_once("phpdbform/phpdbform_static_radiobox.php");
include_once("phpdbform/phpdbform_date.php");
include_once("phpdbform/phpdbform_textarea.php");

check_login(1);

$form = new phpdbform( $phpdbform_main->db, "contact", "cod", "name,email", "name" );

new phpdbform_textbox( $form, "name", "Name:", 30 );
new phpdbform_textbox( $form, "email", "E-mail:", 50 );
new phpdbform_static_listbox( $form, "sex", "Male", "male,female" );
new phpdbform_checkbox( $form, "active", "Active?", "Y", "N" );
new phpdbform_listbox( $form, "type", "Type", $phpdbform_main->db, "type", "cod", "type", "type" );
new phpdbform_static_radiobox( $form, "os", "Operating System:", "linux,windows,unix" );
new phpdbform_date( $form, "birthday", "BirthDay", "fmtEUR" ); // fmtUSA, fmtEUR, fmtSQL
new phpdbform_textarea( $form, "obs", "Obs", 30, 10 );

$phpdbform_main->theme->header = "
<style type=\"text/css\">
	body, td {
		font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
		font-size: 10px;
	}

	input, select, textarea {
		font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
		font-size: 10px;
		background: #FFFFF0;
	}
</style>
";

draw_adm_header( "Test Contact 2" );
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