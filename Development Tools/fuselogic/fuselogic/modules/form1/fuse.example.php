<?php

$XFA['action'] = index().module().'/example';

if ( $_POST['register'] ) {
    require_once('form.register_lib.php');

    // Register the subclasses to use
    $v[]= new ValidateUser($_POST['user']);
    $v[]= new ValidatePassword($_POST['pass'],$_POST['conf']);
    $v[]= new ValidateEmail($_POST['email']);

    // Perform each validation
    foreach($v as $validator){
        if(!$validator->isValid()){
            while($error = $validator->getError()){
                $errorMsg.="<li>".$error."</li>\n";
            }
        }
    }
		
    if(isset($errorMsg)){
        echo '<div align="center"><h3>There were errors :</h3><ul>'.$errorMsg.'</ul></div>';
				require('form.register_show.php');
    }else{
        echo '<br><h2 align="center">Form Valid!</h2>';
				require('form.register_do.php');
    }
		
}else{
    require('form.register_show.php');
}

?>