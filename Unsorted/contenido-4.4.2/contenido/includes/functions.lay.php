<?php
/******************************************
* File      :   functions.lay.php
* Project   :   Contenido
* Descr     :   Defines the Layout
*               related functions
*
* Author    :   Jan Lengowski
* Created   :   00.00.0000
* Modified  :   09.05.2003
*
* © four for business AG
******************************************/

cInclude ("includes", "functions.tpl.php");
cInclude ("includes", "functions.con.php");

/**
 * Edit or Create a new layout
 *
 * @param int $idlay Id of the Layout
 * @param string $name Name of the Layout
 * @param string $description Description of the Layout
 * @param string $code Layout HTML Code
 * @return int $idlay Id of the new or edited Layout
 *
 * @author Olaf Niemann <olaf.niemann@4fb.de>
 * @copryright four for business AG <www.4fb.de>
 */
function layEditLayout($idlay, $name, $description, $code) {

    global $db, $client, $auth, $cfg, $sess, $area_tree;

    $db2= new DB_Contenido;

    $date = date("Y-m-d H:i:s");
    $author = "".$auth->auth["uname"]."";

    set_magic_quotes_gpc($name);
    set_magic_quotes_gpc($description);
    set_magic_quotes_gpc($code);

    if (!$idlay) {

        $tmp_newid = $db->nextid($cfg["tab"]["lay"]);
        $idlay = $tmp_newid;

        $sql = "INSERT INTO ".$cfg["tab"]["lay"]." (idlay,name, description, deletable, code, idclient, author, created, lastmodified) VALUES ('$tmp_newid','$name', '$description', '1', '$code', '$client', '$author', '$date', '$date')";
        $db->query($sql);

        //set new $poss_area
        showareas("8");

        $poss_area = "'".implode("','",$area_tree["8"])."'";

        //select The overall rights for this actions
        $sql="SELECT * FROM ".$cfg["tab"]["rights"]." WHERE idclient='".$client."' AND idarea IN ($poss_area) AND idcat='0' AND idaction != '0'";
        $db->query($sql);

        while ( $db->next_record() ) {

        //insert this rights for the new id
        $sql = "INSERT INTO ".$cfg["tab"]["rights"]."
                (idright, user_id,idarea,idaction,idcat,idclient,idlang)
                VALUES ('".$db->nextid($cfg["tab"]["rights"])."', '".$db->f("user_id")."','".$db->f("idarea")."','".$db->f("idaction")."','$tmp_newid','$client','".$db->f("idlang")."')";
            
        $db2->query($sql);

        }
        
        return $idlay;

    } else {

        $sql = "UPDATE ".$cfg["tab"]["lay"]." SET name='$name', description='$description', code='$code', author='$author', lastmodified='$date' WHERE idlay='$idlay'";
        $db->query($sql);

        /* Update CODE table*/
        conGenerateCodeForAllartsUsingLayout($idlay);
        
        return $idlay;
    }

}

/**
 *
 *
 *
 *
 *
 *
 */

function layDeleteLayout($idlay) {
        global $db;
        global $client;
        global $cfg;
        global $area_tree;

        $sql = "SELECT * FROM ".$cfg["tab"]["tpl"]." WHERE idlay='$idlay'";
        $db->query($sql);
        if ($db->next_record()) {
                return "0301";                // layout is still in use, you cannot delete it
        } else {
                $sql = "DELETE FROM ".$cfg["tab"]["lay"]." WHERE idlay='$idlay'";
                $db->query($sql);
        }

        //set new $poss_area
        showareas("8");

        $poss_area="'".implode("','",$area_tree["8"])."'";

        $sql="DELETE FROM ".$cfg["tab"]["rights"]." WHERE idcat='$idlay' AND idclient='$client' AND idarea IN ($poss_area)";
        $db->query($sql);

}
?>
