<?php

$XFA['action'] = index().module().'/contact_us';

if($_POST['submit']){

    //checking
		$temp = getModulePath('form');
    require_once($temp.'/class.validator.php');
    require_once($temp.'/class.validator_email.php');
    require_once($temp.'/class.validator_empty.php');
		require_once($temp.'/class.text_on_image.php');

    // Register the subclasses to use
    $v[]= new ValidateEmpty($_POST['email'],'Email');
		$v[]= new ValidateEmail($_POST['email']);

		$v[]= new ValidateEmpty($_POST['subject'],'Subject');
		$v[]= new ValidateEmpty($_POST['body'],'Message');
		$v[]= new ValidateEmpty($_POST['text_on_image'],'Submit Code');
		    
    // Perform each validation
    foreach($v as $validator){
        if(!$validator->isValid()){
            while($error = $validator->getError()){
                $errorMsg.= '<li><font color="#ff0000">'.$error."</font></li>\n";
            }
        }
    }
		
		$text_on_image = & new text_on_image();
		if(!$text_on_image->CheckCode()){
		    $errorMsg.= '<li><font color="#ff0000">Wrong Submit Code</font></li>'."\n";
		}
		
    if(isset($errorMsg)){
        echo '<div align="center"><br><font size="4">There were errors :</font><ul>'.$errorMsg.'</ul></div>';
				require('form.contact_us_form.php');
    }else{
        require('form.contact_us_do.php');
    }
		
}else{
    require('form.contact_us_form.php');
}

?>