<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Untitled</title>
    <script language="JavaScript">
    <?php require("libjs.js"); ?>
    </script>
</head>
<body>
<?php
include("phpdbform/phpdbform_form.php");
include_once("phpdbform/phpdbform_textbox.php");
include_once("phpdbform/phpdbform_password.php");
include_once("phpdbform/phpdbform_static_listbox.php");
include_once("phpdbform/phpdbform_textarea.php");
include_once("phpdbform/phpdbform_checkbox.php");
include_once("phpdbform/phpdbform_filebox.php");

$form = new phpform("form1");
new phpdbform_textbox( $form, "name", "Name:", 20, 20 );
new phpdbform_password( $form, "passwd", "Password:", 20, 20 );
new phpdbform_static_listbox( $form, "option", "Option:", " ,c;car,a;airplane,b;bycicle,s;sp2" );
new phpdbform_textarea( $form, "msg", "Message:", 20, 7 );
new phpdbform_checkbox( $form, "testing", "Testing", "Yes", "No" );
new phpdbform_filebox( $form, "upfile", "Attach file:", 20, 2048, "." );

// This is a default value
// After filling it this value will do nothing
$form->fields["msg"]->value = "You message here";

// This is a javascript code to be run onblur
$form->fields["name"]->onblur = "TestLen(this,10,20);";
$form->fields["passwd"]->onblur = "TestLen(this,4,8);";
$form->fields["option"]->onblur = "TestCar(this);";

$processed = $form->process();
$form->draw();
echo "<hr>";

if( $processed )
{
    reset( $form->fields );
    while( $afield = each( $form->fields ) )
    {
        echo $afield[0] . " - " . $afield[1]->value . "<br>";
    }
}
echo "<hr>";
@reset( $_POST );
while( $k = @each( $_POST ) )
{
    echo $k[0] . " - " . $k[1] . "<br>";
}
?>
<br><br><br><hr><a href="http://www.phpdbform.com" target="_blank">phpDBform site</a>
</body>
</html>
