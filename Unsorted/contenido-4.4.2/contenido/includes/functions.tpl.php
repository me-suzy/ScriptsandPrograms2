<?php

/******************************************
* File      :   functions.tpl.php
* Project   :   Contenido
* Descr     :   Defines the Template
*               related functions
*
* Author    :   Olaf Niemann
* Created   :   21.01.2003
* Modified  :   21.01.2003
*
* © four for business AG
******************************************/

cInclude ("includes", "functions.tpl.php");
cInclude ("includes", "functions.con.php");

/**
 * Edit or create a new Template
 *
 * @author Olaf Niemann <Olaf.Niemann@4fb.de>
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG <www.4fb.de>
 */
function tplEditTemplate($changelayout, $idtpl, $name, $description, $idlay, $c) {

        global $db;
        global $sess;
        global $auth;
        global $client;
        global $cfg;
        global $cfgPathInc;
        global $area_tree;
        $db2= new DB_Contenido;

        $date = date("Y-m-d H:i:s");
        $author = "".$auth->auth["uname"]."";

        //******** entry in 'tpl'-table ***************
        set_magic_quotes_gpc($name);
        set_magic_quotes_gpc($description);
        
        if (!$idtpl) {

            $idtpl = $db->nextid($cfg["tab"]["tpl"]);
            $idtplcfg = $db->nextid($cfg["tab"]["tpl_conf"]);

            /* Insert new entry in the
               Template Conf table  */
            $sql = "INSERT INTO ".$cfg["tab"]["tpl_conf"]."
                    (idtplcfg, idtpl, author) VALUES
                   ('".$idtplcfg."', '".$idtpl."', '".$auth->auth["uname"]."')";

            $db->query($sql);

            /* Insert new entry in the
               Template table  */
            $sql = "INSERT INTO ".$cfg["tab"]["tpl"]."
                    (idtpl, idtplcfg, name, description, deletable, idlay, idclient, author, created, lastmodified) VALUES
                    ('".$idtpl."', '".$idtplcfg."', '".$name."', '".$description."', '1', '".$idlay."', '".$client."', '".$author."', '".$date."', '".$date."')";

            $db->query($sql);

            /* set new $poss_area */
            showareas("12");

            $poss_area="'".implode("','",$area_tree["12"])."'";

            //select The overall rights for this actions
            $sql="SELECT * FROM ".$cfg["tab"]["rights"]." WHERE idclient='$client' AND idarea IN ($poss_area) AND idcat='0' AND idaction!='0'";
            $db->query($sql);

            while($db->next_record()){
                  //insert this rights for the new id
                  $sql="INSERT INTO ".$cfg["tab"]["rights"]."
                        (idright, user_id,idarea,idaction,idcat,idclient,idlang)
                        VALUES ('".$db->nextid($cfg["tab"]["rights"])."', '".$db->f("user_id")."','".$db->f("idarea")."','".$db->f("idaction")."','$idtpl','$client','".$db->f("idlang")."')";
                  $db2->query($sql);
            }
                
        } else {

            /* Update */
            $sql = "UPDATE ".$cfg["tab"]["tpl"]." SET name='$name', description='$description', idlay='$idlay', author='$author', lastmodified='$date' WHERE idtpl='$idtpl'";
            $db->query($sql);

            if (is_array($c)) {
				
				/* Delete all container assigned to this template */	
                  $sql = "DELETE FROM ".$cfg["tab"]["container"]." WHERE idtpl='".$idtpl."'";
                  $db->query($sql);
				
               foreach($c as $idcontainer => $dummyval) {
								  						
                  $sql = "INSERT INTO ".$cfg["tab"]["container"]." (idcontainer, idtpl, number, idmod) VALUES ";
                  $sql .= "(";
                  $sql .= "'".$db->nextid($cfg["tab"]["container"])."', ";
                  $sql .= "'".$idtpl."', ";
                  $sql .= "'".$idcontainer."', ";
                  $sql .= "'".$c[$idcontainer]."'";
                  $sql .= ") ";
                  $db->query($sql);
                
               }
            }

            /* Generate code */
            conGenerateCodeForAllartsUsingTemplate($idtpl);
            
        }

        //******** if layout is changed stay at 'tpl_edit' otherwise go to 'tpl'
        if ($changelayout != 1) {
            $url = $sess->url("main.php?area=tpl_edit&idtpl=$idtpl&frame=4");
            header("location: $url");
        } else {
            return $idtpl;
        }

}

/**
 * Delete a template
 *
 * @param int $idtpl ID of the template to duplicate
 *
 * @return $new_idtpl ID of the duplicated template
 * @author Olaf Niemann <Olaf.Niemann@4fb.de>
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG <www.4fb.>
 */
function tplDeleteTemplate($idtpl) {

        global $db, $client, $lang, $cfg, $area_tree;

        $sql = "DELETE FROM ".$cfg["tab"]["tpl"]." WHERE idtpl='$idtpl'";
        $db->query($sql);
        
        /* JL 160603 : Delete all unnecessary entries */
                
        $sql = "DELETE FROM ".$cfg["tab"]["container"]." WHERE idtpl = $idtpl";
        $db->query($sql);
                
        $idsToDelete = array();                
        $sql = "SELECT idtplcfg FROM ".$cfg["tab"]["tpl_conf"]." WHERE idtpl = $idtpl";
        $db->query($sql);        
        while ( $db->next_record() ) { 
        	$idsToDelete[] = $db->f("idtplcfg"); 
        }
        
        foreach ( $idsToDelete as $id ) {
        	
        	$sql = "DELETE FROM ".$cfg["tab"]["tpl_conf"]." WHERE idtplcfg = $id";        	
        	$db->query($sql);
        	
        	$sql = "DELETE FROM ".$cfg["tab"]["container_conf"]." WHERE idtplcfg = $id";
        	$db->query($sql);
        	
        }

        //set new $poss_area
        showareas("12");

        $poss_area="'".implode("','",$area_tree["12"])."'";

        $sql="DELETE FROM ".$cfg["tab"]["rights"]." WHERE idcat='$idtpl' AND idclient='$client' AND idarea IN ($poss_area)";
        $db->query($sql);

}


/**
 * Duplicate a template
 *
 * @param int $idtpl ID of the template to duplicate
 *
 * @return $new_idtpl ID of the duplicated template
 *
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG <www.4fb.>
 */
function tplBrowseLayoutForContainers($idlay) {
        global $db;
        global $cfg;

        $sql = "SELECT code FROM ".$cfg["tab"]["lay"]." WHERE idlay='$idlay'";
        $db->query($sql);
        $db->next_record();
        $code = $db->f("code");

        preg_match_all ("/CMS_CONTAINER\[([0-9]*)\]/", $code, $a_container);

        if (is_array($a_container[1])) {
            $tmp_returnstring = implode("&",$a_container[1]);
        }
        return $tmp_returnstring;
}

/**
 * Duplicate a template
 *
 * @param int $idtpl ID of the template to duplicate
 *
 * @return $new_idtpl ID of the duplicated template
 *
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG <www.4fb.>
 */
function tplDuplicateTemplate($idtpl) {

    global $db, $client, $lang, $cfg, $sess, $auth;

    $db2 = new DB_Contenido;

    $sql = "SELECT
                *
            FROM
                ".$cfg["tab"]["tpl"]."
            WHERE
                idtpl = '".$idtpl."'";

    $db->query($sql);
    $db->next_record();
    
    $idclient   = $db->f("idclient");
    $idlay      = $db->f("idlay");
    $new_idtpl  = $db->nextid($cfg["tab"]["tpl"]);
    $name       = $db->f("name") . " - Kopie";
    $descr      = $db->f("description");
    $author     = $auth->auth["uname"];
    $created    = time();
    $lastmod    = time();
    
    $sql = "INSERT INTO
                ".$cfg["tab"]["tpl"]."
                (idclient, idlay, idtpl, name, description, deletable,author, created, lastmodified)
            VALUES
                ('".$idclient."', '".$idlay."', '".$new_idtpl."', '".$name."', '".$descr."', '1', '".$author."', '".$created."', '".$lastmod."')";

    $db->query($sql);
    
    
    $a_containers = array();
    
    $sql = "SELECT
                *
            FROM
                ".$cfg["tab"]["container"]."
            WHERE
                idtpl = '".$idtpl."'
            ORDER BY
                number";

    $db->query($sql);

    while ($db->next_record()) {
        $a_containers[$db->f("number")] = $db->f("idmod");
    }
    
    foreach ($a_containers as $key => $value) {

        $nextid = $db->nextid($cfg["tab"]["container"]);
        
        $sql = "INSERT INTO ".$cfg["tab"]["container"]."
                (idcontainer, idtpl, number, idmod) VALUES ('".$nextid."', '".$new_idtpl."', '".$key."', '".$value."')";

        $db->query($sql);

    }

    return $new_idtpl;

}

/**
 * Checks if a template is in use
 *
 * @param int $idtpl Template ID
 *
 * @return bool is template in use
 *
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG <www.4fb.de>
 */
function tplIsTemplateInUse($idtpl) {

    global $cfg, $client, $lang;

    $db = new DB_Contenido;
    $db2 = new DB_Contenido;

    $sql = "SELECT idtplcfg FROM ".$cfg["tab"]["tpl_conf"]." WHERE idtpl = '".$idtpl."'";
    $db->query($sql);

    while ($db->next_record()) {

        /* Check categorys */
        $sql = "SELECT
                    b.idcatlang
                FROM
                    ".$cfg["tab"]["cat"]." AS a,
                    ".$cfg["tab"]["cat_lang"]." AS b
                WHERE
                    a.idclient  = '".$client."' AND
                    a.idcat     = b.idcat AND
                    b.idlang    = '".$lang."' AND
                    b.idtplcfg  = '".$db->f("idtplcfg")."'";


        $db2->query($sql);

        if ( $db2->next_record() ) {
            return true;
        }

        /* Check articles */
        $sql = "SELECT
                    b.idartlang
                FROM
                    ".$cfg["tab"]["art"]." AS a,
                    ".$cfg["tab"]["art_lang"]." AS b
                WHERE
                    a.idclient  = '".$client."' AND
                    a.idart     = b.idart AND
                    b.idlang    = '".$lang."' AND
                    b.idtplcfg  = '".$db->f("idtplcfg")."'";


        $db2->query($sql);

        if ( $db2->next_record() ) {
            return true;
        }
    }

    return false;

}


?>
