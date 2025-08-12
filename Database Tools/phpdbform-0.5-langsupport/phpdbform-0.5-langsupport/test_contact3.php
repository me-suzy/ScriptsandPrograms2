<?php
// phpdbform without the selectform
// we need to fill $form->keyvalue with the key values (comma separated)
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

$form = new phpdbform( $phpdbform_main->db, "contact", "cod" );

new phpdbform_textbox( $form, "name", "Name:", 30 );
new phpdbform_textbox( $form, "email", "E-mail:", 30 );
new phpdbform_static_listbox( $form, "sex", "Male", "male,female" );
new phpdbform_checkbox( $form, "active", "Active?", "Y", "N" );
new phpdbform_listbox( $form, "type", "Type", $phpdbform_main->db, "type", "cod", "cod,type", "type" );
new phpdbform_static_radiobox( $form, "os", "Operating System:", "linux,windows,unix" );
new phpdbform_date( $form, "birthday", "BirthDay", "fmtEUR" ); // fmtUSA, fmtEUR, fmtSQL
new phpdbform_textarea( $form, "obs", "Obs", 30, 6 );

$events = "";

// $lform contains the form that called this event
function show_delete_message( &$lform )
{
    global $events;
    $events = "alert(\"record deleted\");";
    return true;
}

function show_insert_message( &$lform )
{
    global $events;
    $events = "alert(\"record inserted\");";
    return true;
}

function show_update_message( &$lform )
{
    global $events;
    $events = "alert(\"record updated\");";
    return true;
}

// this show how to append events into phpdbform
// if they return true, the actions will be taken (insert/delete/update)
// on false, it won't execute
$form->ondelete = "show_delete_message";
$form->oninsert = "show_insert_message";
$form->onupdate = "show_update_message";

draw_adm_header( "Test Contact 3" );
?>
<table border="1" cellspacing="0" cellpadding="4" align="center">
<tr><th>Listing</th><th>Editing</th></tr>
<tr><td width="200" valign="top">
<a href="test_contact3.php">Insert new record</a><br>
<?php
// process the form first
if( isset($_GET["cod"]) ) {
    $form->keyvalue = array( intval($_GET["cod"]) );
}
$form->process();
// listing values from contact, set url param cod with the key
$ret = $phpdbform_main->db->query( "select cod, name from contact order by name", "filling contact listing" );
while( $row = $phpdbform_main->db->fetch_row($ret) )
{
    echo "<a href=\"test_contact3.php?cod={$row[0]}\">{$row[1]}</a><br>";
}
$phpdbform_main->db->free_result( $ret );
?>
</td><td width="300">
<?php
$form->draw();
?>
</td></tr></table>
<script language="JavaScript">
<?php echo $events; ?>
</script>
<?php
draw_adm_footer();
?>
