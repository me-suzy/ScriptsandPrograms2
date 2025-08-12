<?php

/******************************************
* File      :   functions.mod.php
* Project   :   Contenido
* Descr     :   Defines the 'mod' related
*               functions
*
* Author    :   Olaf Niemann, Jan Lengowski
* Created   :   21.01.2003
* Modified  :   25.03.2003
*
* © four for business AG
******************************************/
cInclude ("includes", "functions.tpl.php");
cInclude ("includes", "functions.con.php");
        
function modEditModule($idmod, $name, $description, $input, $output, $template) {

    global $db, $client, $auth, $cfg, $sess, $area_tree;

    $db2    = new DB_Contenido;
    $date   = date("Y-m-d H:i:s");
    $author = "".$auth->auth["uname"]."";

    set_magic_quotes_gpc($name);
    set_magic_quotes_gpc($description);
    set_magic_quotes_gpc($input);
    set_magic_quotes_gpc($output);
    set_magic_quotes_gpc($template);

    # No module id passed, create a new module
    if (!$idmod) {

        $tmp_newid = $db->nextid($cfg["tab"]["mod"]);
        $idmod = $tmp_newid;

        $sql = "INSERT INTO ".$cfg["tab"]["mod"]." (idmod,name, description, deletable, input, output, template, idclient, author, created, lastmodified) VALUES ('$tmp_newid','$name', '$description', '1', '$input', '$output', '$template', '$client', '$author', '$date', '$date')";
        $db->query($sql);

        //set new $poss_area
        showareas("10");

        $poss_area = "'".implode("','",$area_tree["10"])."'";

        //select The overall rights for this actions
        $sql="SELECT * FROM ".$cfg["tab"]["rights"]." WHERE idclient='$client' AND idarea IN ($poss_area) AND idcat = '0' AND idaction != '0'";
        $db->query($sql);

        while($db->next_record()){

            # insert this rights for the new id
            $sql="INSERT INTO ".$cfg["tab"]["rights"]."
            (idright, user_id,idarea,idaction,idcat,idclient,idlang)
            VALUES ('".$db->nextid($cfg["tab"]["rights"])."', '".$db->f("user_id")."','".$db->f("idarea")."','".$db->f("idaction")."','$tmp_newid','$client','".$db->f("idlang")."')";
            $db2->query($sql);
        }

        return $idmod;

    # Module id passed
    # Edit module
    } else {

        $sql = "UPDATE ".$cfg["tab"]["mod"]." SET name='$name', description='$description', input='$input', output='$output', template='$template', author='$author', lastmodified='$date' WHERE idmod='$idmod'";
        $db->query($sql);

        /* Generate code */
        conGenerateCodeForAllArtsUsingMod($idmod);
        return $idmod;
        
    }

}

function modDeleteModule($idmod) {

    # Global vars
    global $db, $sess, $client, $cfg, $area_tree;

    $sql = "DELETE FROM ".$cfg["tab"]["mod"]." WHERE idmod = '".$idmod."' AND idclient = '".$client."'";
    $db->query($sql);


    # set new $poss_area
    showareas("10");

    $poss_area = "'".implode("', '" ,$area_tree["10"]). "'";

    $sql="DELETE FROM ".$cfg["tab"]["rights"]." WHERE idcat = '$idmod' AND idclient = '$client' AND idarea IN ($poss_area)";
    $db->query($sql);

}

// $code: Code to evaluate
// $id: Unique ID for the test function
// $mode: true if start in php mode, otherwise false
// Returns true or false

function modTestModule ($code, $id, $output = false) 
{
	global $cfg, $modErrorMessage;
	
	$magicvalue = 0;
    
    $db = new DB_Contenido;

	/* Put a $ in front of all CMS variables
	   to prevent PHP error messages */
    $sql = "SELECT type FROM ".$cfg["tab"]["type"];
    $db->query($sql);
    
    while ($db->next_record())
    {
    	$code = str_replace($db->f("type").'[','$'.$db->f("type").'[', $code);
    }
    
    /*$code = str_replace('CMS_VALUE','$CMS_VALUE', $code);
    $code = str_replace('CMS_VAR','$CMS_VAR', $code);*/

    $code = str_replace('[CMS_VALUE[','[', $code);
    for ($i = 0; $i < 10; $i++) {
       $code = str_replace('['.$i.'CMS_VALUE[','['.$i, $code);
    }
    $code = str_replace(']]',']', $code);
    $code = str_replace('$$CMS_','$CMS_', $code);
    $code = str_replace('CMS_VALUE','$CMS_VALUE', $code);
    $code = str_replace('CMS_VAR','$CMS_VAR', $code);
    
	/* If the module is an output module, escape PHP since
       all output modules enter php mode */    
    if ($output == true)
    {
    	$code = "?>\n" . $code . "\n<?php";
    }
    
    
    /* Looks ugly: Paste a function declarator
       in front of the code */
    $code = "function foo".$id." () {" . $code;
    $code .= "\n}\n";
    
    
    /* Set the magic value */
    $code .= '$magicvalue = 941;';
    
    /* To parse the error message, we prepend and
       append a phperror tag in front of the output */
    ini_set("error_prepend_string","<phperror>");
    ini_set("error_append_string","</phperror>");

    /* Turn off output buffering and error reporting, eval the code */
	ob_start();
	ini_set("display_errors", true);
    $output = eval($code);
    ini_set("display_errors", false);
    
    /* Get the buffer contents and turn it on again */
	$output = ob_get_contents();
    ob_end_clean();
    
    /* Remove the prepend and append settings */
    ini_restore("error_prepend_string");
    ini_restore("error_append_string");
    
    /* Strip out the error message */
    $start = strpos($output, "<phperror>");
    $end = strpos($output, "</phperror>");
    
    /* More stripping: Users shouldnt see where the file
       is located, but they should see the error line */
    if ($start !== false)
    {
    	$start = strpos($output, "eval()");
    	
    	$modErrorMessage = substr($output, $start, $end - $start);
    	
    	/* Kill that HTML formatting */
    	$modErrorMessage = str_replace("<b>","",$modErrorMessage);
    	$modErrorMessage = str_replace("</b>","",$modErrorMessage);
    	$modErrorMessage = str_replace("<br>","",$modErrorMessage);
    }
    
    /* Now, check if the magic value is 941. If not, the function
       didn't compile */
    if ($magicvalue != 941)
    {
    	return false;
    } else {
    	return true;
    }
}

?>
