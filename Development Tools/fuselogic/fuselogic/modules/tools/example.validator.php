<?php
if ( $_POST['register'] ) {
    //require_once('class.validator.php');

    // Register the subclasses to use
    $v['u']=new ValidateUser($_POST['user']);
    $v['p']=new ValidatePassword($_POST['pass'],$_POST['conf']);
    $v['e']=new ValidateEmail($_POST['email']);

    // Perform each validation
    foreach($v as $validator) {
        if (!$validator->isValid()) {
            while ($error=$validator->getError()) {
                @$errorMsg.="<li>".$error."</li>\n";
            }
        }
    }
    if (isset($errorMsg)) {
        print ("<p>There were errors:<ul>\n".$errorMsg."</ul>");
				$xfa['validator.example'] = $_SERVER['PHP_SELF'].'?fuseaction='.circuit().'.validator.example';
        include("form.validator.php");				
    } else {
        print ('<h2>Form Valid!</h2>');
    }
} else {
$xfa['validator.example'] = $_SERVER['PHP_SELF'].'?fuseaction='.circuit().'.validator.example';
include("form.validator.php");
}
?>


