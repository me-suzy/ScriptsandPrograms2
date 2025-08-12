<?php
/******************************************************************************
Description : Defines the 'str' related functions
Copyright   : four for business AG
Author      : Olaf Niemann
Urls        : www.contenido.de
Create date : 2002-03-02

Functions
strNewTree($catname)
strNewCategory($tmp_parentid, $catname)
strOrderedPostTreeList ($idcat, $poststring)
strRemakeTreeTable()
strNextDeeper($tmp_idcat)
strNextPost($tmp_idcat)
strNextBackwards($tmp_idcat)
strRemakeTreeTableFindNext($tmp_idcat,$tmp_level)
strShowTreeTable()
strRenameCategory ($idcat, $lang, $newcategoryname)
strMakeVisible ($idcat, $lang, $visible)
strMakePublic ($idcat, $lang, $public)
strDeleteCategory ($idcat)
strMoveUpCategory ($idcat)
strMoveSubtree ($idcat, $parentid_new)
strMoveCatTargetallowed($idcat, $source)
********************************************************************************/

if (class_exists("DB_Contenido"))
{
	$db_str = new DB_Contenido;
}

function strNewTree($catname) {
        global $db;
        global $client;
        global $lang;
        global $cfg;
        global $area_tree;
        global $sess;
        global $perm;
        global $area_rights;
        global $item_rights;
        global $_SESSION;
		// Flag to rebuild the category table
		global $remakeCatTable;
		global $remakeStrTable;
		$remakeCatTable = true;
		$remakeStrTable = true;
        

        $db2= new DB_Contenido;

        if ($catname == "")
        {
            return;
        }
        $tmp_newid = $db->nextid($cfg["tab"]["cat"]);

        $sql = "SELECT idcat FROM ".$cfg["tab"]["cat"]." WHERE parentid='0' AND postid='0' AND idclient='$client'";
        $db->query($sql);
        $db->next_record();
        $tmp_id = $db->f("idcat");

        $a_languages = getLanguagesByClient($client);
        if (is_array($a_languages)) {

                if (!$tmp_id) {
                        //********** Entry in 'cat'-table ************
                        $sql = "INSERT INTO ".$cfg["tab"]["cat"]." (idcat, preid, postid, idclient) VALUES('$tmp_newid', '0', '0', '$client')";
                        $db->query($sql);

                        //********* enter name of cat in 'cat_lang'-table ******
                        foreach ($a_languages as $tmp_lang) {
                                 if ($tmp_lang == $lang) {
                                         $sql = "INSERT INTO ".$cfg["tab"]["cat_lang"]." (idcatlang, idcat, idlang, name, visible, public, idtplcfg) VALUES('".$db->nextid($cfg["tab"]["cat_lang"])."','$tmp_newid','$tmp_lang','$catname','0','1','0')";
                                        $db->query($sql);
                                } else {
                                        $sql = "INSERT INTO ".$cfg["tab"]["cat_lang"]." (idcatlang, idcat, idlang, name, visible, public, idtplcfg) VALUES('".$db->nextid($cfg["tab"]["cat_lang"])."','$tmp_newid','$tmp_lang','$catname','0','1','0')";
                                        $db->query($sql);
                                }
                        }
                } else {
                        //********** Entry in 'cat'-table ************
                        $sql = "UPDATE ".$cfg["tab"]["cat"]." SET postid='$tmp_newid' WHERE idcat='$tmp_id'";
                        $db->query($sql);

                        //********** Entry in 'cat'-table ************
                        $sql = "INSERT INTO ".$cfg["tab"]["cat"]." (idcat, preid, postid, idclient) VALUES('$tmp_newid', '$tmp_id', '0', '$client')";
                        $db->query($sql);

                        //********* enter name of cat in 'cat_lang'-table ******
                        foreach ($a_languages as $tmp_lang) {
                                 if ($tmp_lang == $lang) {
                                        $sql = "INSERT INTO ".$cfg["tab"]["cat_lang"]." (idcatlang, idcat, idlang, name, visible, public, idtplcfg) VALUES('".$db->nextid($cfg["tab"]["cat_lang"])."','$tmp_newid','$tmp_lang','$catname','0','1','0')";
                                        $db->query($sql);
                                } else {
                                        $sql = "INSERT INTO ".$cfg["tab"]["cat_lang"]." (idcatlang, idcat, idlang, name, visible, public, idtplcfg) VALUES('".$db->nextid($cfg["tab"]["cat_lang"])."','$tmp_newid','$tmp_lang','$catname','0','1','0')";
                                        $db->query($sql);
                                }
                        }
                }

                //set new $poss_area
                showareas("6");

                $poss_area="'".implode("','",$area_tree["6"])."'";

                //select The overall rights for this actions
                $sql="SELECT * FROM ".$cfg["tab"]["rights"]." WHERE idclient='$client' AND idarea IN ($poss_area) AND idcat='0' AND idaction!='0'";
                $db->query($sql);

                while($db->next_record()){
                      //insert this rights for the new id
                      $sql="INSERT INTO ".$cfg["tab"]["rights"]."
                            (idright, user_id,idarea,idaction,idcat,idclient,idlang)
                            VALUES ('".$db2->nextid($cfg["tab"]["rights"])."','".$db->f("user_id")."','".$db->f("idarea")."','".$db->f("idaction")."','$tmp_newid','$client','".$db->f("idlang")."')";
                      $db2->query($sql);

                }

                $perm->load_permissions(true);
                showareas("6");
                
        }
}

function strNewCategory($tmp_parentid, $catname) {
        global $db;
        global $client;
        global $lang;
        global $cfg;
        global $area_tree;
        global $perm;
		// Flag to rebuild the category table
		global $remakeCatTable;
		global $remakeStrTable;


      
        $db2= new DB_Contenido;

        if ($catname == "")
        {
            return;
        }
        
		$remakeCatTable = true;
		$remakeStrTable = true;        
		
        #$sql = "SELECT MAX(idcat) FROM ".$cfg["tab"]["cat"];
        #$db->query($sql);
        #$db->next_record();
        #//list($key, $value) = each($db->Record);
        #$a_tmp = each($db->Record);
        #$tmp_maxid = $a_tmp[1];                     // an nullter Stelle ist key=0, an 1 stelle ist value
        #$tmp_newid = $tmp_maxid + 1;
        $tmp_newid = $db->nextid($cfg["tab"]["cat"]);

        $sql = "SELECT idcat FROM ".$cfg["tab"]["cat"]." WHERE parentid='$tmp_parentid' AND postid=0";
        $db->query($sql);
        $db->next_record();
        $tmp_id = $db->f("idcat");

        if (!$tmp_id) {
                //********** Entry in 'cat'-table ************
                $sql = "INSERT INTO ".$cfg["tab"]["cat"]." (idcat, parentid, preid, postid, idclient) VALUES('$tmp_newid', '$tmp_parentid', '0', '0', '$client')";
                $db->query($sql);


                //********* enter name of cat in 'cat_lang'-table ******
                $a_languages = getLanguagesByClient($client);
                foreach ($a_languages as $tmp_lang) {
                         if ($tmp_lang == $lang) {
                                 $sql = "INSERT INTO ".$cfg["tab"]["cat_lang"]." (idcatlang, idcat, idlang, name, visible, public, idtplcfg) VALUES('".$db->nextid($cfg["tab"]["cat_lang"])."','$tmp_newid','$tmp_lang','$catname','0','1','0')";
                                $db->query($sql);
                        } else {
                                $sql = "INSERT INTO ".$cfg["tab"]["cat_lang"]." (idcatlang, idcat, idlang, name, visible, public, idtplcfg) VALUES('".$db->nextid($cfg["tab"]["cat_lang"])."','$tmp_newid','$tmp_lang','$catname','0','1','0')";
                                $db->query($sql);
                        }
                }
        } else {
                //********** Entry in 'cat'-table ************
                $sql = "UPDATE ".$cfg["tab"]["cat"]." SET postid='$tmp_newid' WHERE idcat='$tmp_id'";
                $db->query($sql);

                //********** Entry in 'cat'-table ************
                $sql = "INSERT INTO ".$cfg["tab"]["cat"]." (idcat, parentid, preid, postid, idclient) VALUES('$tmp_newid', '$tmp_parentid', '$tmp_id', '0', '$client')";
                $db->query($sql);

                //********* enter name of cat in 'cat_lang'-table ******
                $a_languages = getLanguagesByClient($client);
                foreach ($a_languages as $tmp_lang) {
                         if ($tmp_lang == $lang) {
                                $sql = "INSERT INTO ".$cfg["tab"]["cat_lang"]." (idcatlang, idcat, idlang, name, visible, public, idtplcfg) VALUES('".$db->nextid($cfg["tab"]["cat_lang"])."','$tmp_newid','$tmp_lang','$catname','0','1','0')";
                                $db->query($sql);
                        } else {
                                $sql = "INSERT INTO ".$cfg["tab"]["cat_lang"]." (idcatlang, idcat, idlang, name, visible, public, idtplcfg) VALUES('".$db->nextid($cfg["tab"]["cat_lang"])."','$tmp_newid','$tmp_lang','$catname','0','1','0')";
                                $db->query($sql);
                        }
                }

        }

        //set new $poss_area
        showareas("6");

        $poss_area="'".implode("','",$area_tree["6"])."'";

        //Select all rights for the parentid
        $sql="SELECT * FROM ".$cfg["tab"]["rights"]." WHERE  idclient = '$client' AND idcat = '$tmp_parentid' AND idaction != '0'";
        $db->query($sql);
        while($db->next_record()){
              //insert this rights for the new id
              $sql="INSERT INTO ".$cfg["tab"]["rights"]."
                    (idright, user_id,idarea,idaction,idcat,idclient,idlang)
                    VALUES ('".$db2->nextid($cfg["tab"]["rights"])."','".$db->f("user_id")."','".$db->f("idarea")."','".$db->f("idaction")."','$tmp_newid','$client','".$db->f("idlang")."')";
              $db2->query($sql);

        }

        $perm->load_permissions(true);
        showareas("6");
        
        strRemakeTreeTable();
        
        return($tmp_newid);

}

function strOrderedPostTreeList ($idcat, $poststring) {
        global $db;
        global $client;
        global $lang;
        global $cfg;

        $sql = "SELECT idcat FROM ".$cfg["tab"]["cat"]." WHERE parentid=0 AND preid='$idcat'";
        $db->query($sql);
        if ( $db->next_record() ) {
                $tmp_idcat = $db->f("idcat");
                $poststring = $poststring.",".$tmp_idcat;
                $poststring = strOrderedPostTreeList($tmp_idcat, $poststring);
        }

        return $poststring;

}

function strRemakeTreeTable() {
        global $db;
        global $client;
        global $lang;
        global $cfg;
		// Flag to rebuild the category table
		global $remakeCatTable;
		global $remakeStrTable;
		$remakeCatTable = true;
		$remakeStrTable = true;
        
        $poststring = "";

        $sql = "DELETE FROM ".$cfg["tab"]["cat_tree"];                    // empty 'cat_tree'-table
        $db->query($sql);

        $sql = "SELECT idcat FROM ".$cfg["tab"]["cat"]." WHERE parentid=0 AND preid='0'";
        $db->query($sql);
        while($db->next_record())
        {
                $idcats[] = $db->f("idcat");
        }

        if (is_array($idcats)) {
                foreach ($idcats as $value) {
                        $poststring = $poststring.$value.strOrderedPostTreeList ($value, "").",";
                }
        }
        $poststring=ereg_replace(",$","", $poststring);
        $a_maincats = explode(",", $poststring);
        if (is_array($a_maincats)){
                foreach ($a_maincats as $tmp_idcat) {
                        strRemakeTreeTableFindNext($tmp_idcat,0);
                }
        }
}

function strNextDeeper($tmp_idcat) {
        global $cfg, $db_str;

        $sql = "SELECT idcat FROM ".$cfg["tab"]["cat"]." WHERE parentid='$tmp_idcat' AND preid='0'";
        $db_str->query($sql);
        if ($db_str->next_record()) {                         //******deeper element exists
                return $db_str->f("idcat");
        } else {                                        //******deeper element does not exist
                return 0;
        }
}

function strHasArticles($tmp_idcat) {
        global $cfg, $db_str;

        $sql = "SELECT idart FROM ".$cfg["tab"]["cat_art"]." WHERE idcat='$tmp_idcat'";
        
        $db_str->query($sql);
        if ($db_str->next_record()) {                         //******post element exists
                return true;
        } else {                                        //******post element does not exist
                return false;
        }
}
function strNextPost($tmp_idcat) {
        global $db;
        global $cfg;

        $sql = "SELECT idcat FROM ".$cfg["tab"]["cat"]." WHERE preid='$tmp_idcat'";
        $db->query($sql);
        if ($db->next_record()) {                         //******post element exists
                $tmp_idcat = $db->f("idcat");
                $sql = "SELECT parentid FROM ".$cfg["tab"]["cat"]." WHERE idcat='$tmp_idcat'";
                $db->query($sql);
                if ($db->next_record()) {                         //******parent from post must not be 0
                        $tmp_parentid = $db->f("parentid");
                        if ($tmp_parentid != 0) {
                                return $tmp_idcat;
                        } else {
                                return 0;
                        }
                } else {
                        return 99;
                }
        } else {                                        //******post element does not exist
                return 0;
        }
}

function strNextBackwards($tmp_idcat) {
        global $db;
        global $cfg;

        $sql = "SELECT parentid FROM ".$cfg["tab"]["cat"]." WHERE idcat='$tmp_idcat'";
        $db->query($sql);
        if ($db->next_record()) {                         //******parent exists
                $tmp_idcat = $db->f("parentid");
                if ($tmp_idcat != 0) {
                        $sql = "SELECT idcat FROM ".$cfg["tab"]["cat"]." WHERE preid='$tmp_idcat'";
                        $db->query($sql);
                        if ($db->next_record()) {                         //******parent has post
                                $tmp_idcat = $db->f("idcat");
                                $sql = "SELECT parentid FROM ".$cfg["tab"]["cat"]." WHERE idcat='$tmp_idcat'";
                                $db->query($sql);
                                if ($db->next_record()) {                         //******parent from post must not be 0
                                        $tmp_parentid = $db->f("parentid");
                                        if ($tmp_parentid != 0) {
                                                return $tmp_idcat;
                                        } else {
                                                return 0;
                                        }
                                } else {
                                        return 99;
                                }
                        } else {                                        //******parent has no post
                                return strNextBackwards($tmp_idcat);
                        }
                } else {
                        return 0;
                }
        } else {                                        //******no parent
                return 0;
        }

}

function strRemakeTreeTableFindNext($tmp_idcat,$tmp_level) {
        global $db;
        global $cfg;

        //************* Insert Element in 'cat_tree'-table **************
        $sql = "INSERT INTO ".$cfg["tab"]["cat_tree"]." (idtree, idcat, level) VALUES ('".$db->nextid($cfg["tab"]["cat_tree"])."', '$tmp_idcat', '$tmp_level')";
        $db->query($sql);

        //************* dig deeper, if possible ******
        $tmp = strNextDeeper($tmp_idcat);
        if ($tmp != 0) {
                $tmp_idcat = $tmp;
                $tmp_level++;

                strRemakeTreeTableFindNext($tmp_idcat,$tmp_level);

        } else {
                $tmp = strNextPost($tmp_idcat);
        //************ if not get post element ********
                if ($tmp != 0) {
                        $tmp_idcat = $tmp;

                        strRemakeTreeTableFindNext($tmp_idcat,$tmp_level);

        //************ if that's not possible either go backwards *********
                } else {
                        $tmp = strNextBackwards($tmp_idcat);
                        if ($tmp != 0) {
                                $tmp_idcat = $tmp;
                                $sql = "SELECT A.level FROM ".$cfg["tab"]["cat_tree"]." AS A, ".$cfg["tab"]["cat"]." AS B WHERE A.idcat=B.idcat AND B.postid='$tmp_idcat'";
                                $db->query($sql);
                                if ($db->next_record()) {
                                        $tmp_level = $db->f("level");
                                } else {
                                        $level = 0;
                                }
                                if ($tmp_level != 0) {
                                        strRemakeTreeTableFindNext($tmp_idcat,$tmp_level);
                                }
                        }
                }

        }
}

function strShowTreeTable() {
        global $db;
        global $sess;
        global $client;
        global $lang;
        global $idcat;
        global $cfg;
        global $lngStr;

        echo "<br><table cellpadding=$cellpadding cellspacing=$cellspacing border=$border >";
        $sql = "SELECT * FROM ".$cfg["tab"]["cat_tree"]." AS A, ".$cfg["tab"]["cat"]." AS B, ".$cfg["tab"]["cat_lang"]." AS C WHERE A.idcat=B.idcat AND B.idcat=C.idcat AND C.idlang='$lang' AND B.idclient='$client' ORDER BY A.idtree";
        $db->query($sql);
        while($db->next_record())
        {
                $tmp_id    = $db->f("idcat");
                $tmp_name  = $db->f("name");
                $tmp_level = $db->f("level");

                echo "<tr><td>".$tmp_id." | ".$tmp_name." | ".$tmp_level."</td>";
                echo "<td><a class=action href=\"".$sess->url("main.php?action=20&idcat=$tmp_id")."\">".$lngStr["actions"]["20"]."</a></td>";
                echo "<td><a class=action href=\"".$sess->url("main.php?action=30&idcat=$tmp_id")."\">".$lngStr["actions"]["30"]."</a></td>";
                echo "</td></tr>";

        }
        echo "</table>";
}

function strRenameCategory ($idcat, $lang, $newcategoryname) {
        global $db;
        global $cfg;
		// Flag to rebuild the category table
		global $remakeCatTable;
		global $remakeStrTable;
		$remakeCatTable = true;
		$remakeStrTable = true;


        if ($newcategoryname != "") {

                $sql = "UPDATE ".$cfg["tab"]["cat_lang"]." SET name='$newcategoryname' WHERE idcat='$idcat' AND idlang='$lang' ";
                $db->query($sql);

        } else {

                //echo ("Fehlermeldung aufrufen: strrenamecategory");
//                Header("Location: str_main.php?error=2");    // ohne Namen wird nicht umbenannt.

        }

}


function strMakeVisible ($idcat, $lang, $visible) {
        global $db;
        global $cfg;
		// Flag to rebuild the category table
		global $remakeCatTable;
		global $remakeStrTable;
		$remakeCatTable = true;
		$remakeStrTable = true;


                $a_catstring = strDeeperCategoriesArray($idcat);
        foreach ($a_catstring as $value) {
                $sql = "UPDATE ".$cfg["tab"]["cat_lang"]." SET visible='$visible' WHERE idcat='$value' AND idlang='$lang' ";
                $db->query($sql);
        }
}

function strMakePublic ($idcat, $lang, $public) {
        global $db;
        global $cfg;
		// Flag to rebuild the category table
		global $remakeCatTable;
		global $remakeStrTable;
		$remakeCatTable = true;
		$remakeStrTable = true;


                $a_catstring = strDeeperCategoriesArray($idcat);
        foreach ($a_catstring as $value) {
                $sql = "UPDATE ".$cfg["tab"]["cat_lang"]." SET public='$public' WHERE idcat='$value' AND idlang='$lang' ";
                $db->query($sql);
        }
}

function strDeeperCategoriesArray($idcat_start) {
        global $db;
        global $client;
        global $cfg;

        $sql = "SELECT * FROM ".$cfg["tab"]["cat_tree"]." AS A, ".$cfg["tab"]["cat"]." AS B WHERE A.idcat=B.idcat AND idclient='$client' ORDER BY idtree";
        $db->query($sql);
        $i = 0;
        while ($db->next_record()) {
                if ($db->f("parentid") < $idcat_start) {        // ending part of tree
                        $i = 0;
                }
                if ($db->f("idcat") == $idcat_start) {        // starting part of tree
                        $i = 1;
                }
                if ($i == 1) {
                        $catstring[] = $db->f("idcat");
                }
        }

        return $catstring;
}


function strDeleteCategory ($idcat) {
        global $db;
        global $lang;
        global $client;
        global $cfg;
        global $area_tree;
        
		// Flag to rebuild the category table
		global $remakeCatTable;
		global $remakeStrTable;
		$remakeCatTable = true;
		$remakeStrTable = true;


        $sql = "SELECT * FROM ".$cfg["tab"]["cat"]." WHERE parentid='$idcat'";
        $db->query($sql);

        if ($db->next_record()) {
                return "0201";                // category has subcategories
        } else {
                $sql = "SELECT * FROM ".$cfg["tab"]["cat_art"]." WHERE idcat='$idcat'";
                $db->query($sql);

                if ($db->next_record()) {
                        return "0202";                // category has arts
                } else {
                        $sql = "SELECT * FROM ".$cfg["tab"]["cat"]." WHERE idcat='$idcat'";
                        $db->query($sql);
                        $db->next_record();
                        $tmp_preid  = $db->f("preid");
                        $tmp_postid = $db->f("postid");

                        ////// update pre cat set new postid
                        if ($tmp_preid != 0) {
                                $sql = "UPDATE ".$cfg["tab"]["cat"]." SET postid='$tmp_postid' WHERE idcat='$tmp_preid'";
                                $db->query($sql);
                        }

                        ////// update post cat set new preid
                        if ($tmp_postid != 0) {
                                $sql = "UPDATE ".$cfg["tab"]["cat"]." SET preid='$tmp_preid' WHERE idcat='$tmp_postid'";
                                $db->query($sql);
                        }

                        ////// delete entry in 'cat'-table
                        $sql = "DELETE FROM ".$cfg["tab"]["cat"]." WHERE idcat='$idcat' ";
                        $db->query($sql);

                        ////// delete entry in 'cat_lang'-table
                        $sql = "DELETE FROM ".$cfg["tab"]["cat_lang"]." WHERE idcat='$idcat'";
                        $db->query($sql);

                }

                $sql = "SELECT idtplcfg FROM ".$cfg["tab"]["cat_lang"]." WHERE idcat='$idcat'";
                $db->query($sql);
                while ($db->next_record()) {
                        ////// delete entry in 'tpl_conf'-table
                        $sql = "DELETE FROM ".$cfg["tab"]["tpl_conf"]." WHERE idtplcfg='".$db->f("idtplcfg")."'";
                        $db->query($sql);
                }
                
                //set new $poss_area
                showareas("6");

                $poss_area="'".implode("','",$area_tree["6"])."'";

                $sql="DELETE FROM ".$cfg["tab"]["rights"]." WHERE idcat='$idcat' AND idclient='$client' AND idarea IN ($poss_area)";
                $db->query($sql);

        }
}

function strMoveUpCategory ($idcat) {
        global $db;
        global $sess;
        global $cfg;
        
		// Flag to rebuild the category table
		global $remakeCatTable;
		global $remakeStrTable;
		$remakeCatTable = true;
		$remakeStrTable = true;

		
        $sql = "SELECT idcat, preid, postid FROM ".$cfg["tab"]["cat"]." WHERE idcat='$idcat'";
        $db->query($sql);
        $db->next_record();
        $tmp_idcat  = $db->f("idcat");
        $tmp_preid  = $db->f("preid");
        $tmp_postid = $db->f("postid");

        if ($tmp_preid != 0) {
                $sql = "SELECT idcat, preid, postid FROM ".$cfg["tab"]["cat"]." WHERE idcat='$tmp_preid'";
                $db->query($sql);
                $db->next_record();
                $tmp_idcat_pre  = $db->f("idcat");
                $tmp_preid_pre  = $db->f("preid");
                $tmp_postid_pre = $db->f("postid");

                $sql = "SELECT idcat, preid, postid FROM ".$cfg["tab"]["cat"]." WHERE idcat='$tmp_preid_pre'";
                $db->query($sql);
                $db->next_record();
                $tmp_idcat_pre_pre  = $db->f("idcat");
                $tmp_preid_pre_pre  = $db->f("preid");
                $tmp_postid_pre_pre = $db->f("postid");

                $sql = "SELECT idcat, preid, postid FROM ".$cfg["tab"]["cat"]." WHERE idcat='$tmp_postid'";
                $db->query($sql);
                $db->next_record();
                $tmp_idcat_post  = $db->f("idcat");
                $tmp_preid_post  = $db->f("preid");
                $tmp_postid_post = $db->f("postid");

                $sql = "UPDATE ".$cfg["tab"]["cat"]." SET  postid='$tmp_idcat' WHERE idcat='$tmp_preid_pre'";
                $db->query($sql);

                $sql = "UPDATE ".$cfg["tab"]["cat"]." SET  preid='$tmp_idcat', postid='$tmp_postid' WHERE idcat='$tmp_preid'";
                $db->query($sql);

                $sql = "UPDATE ".$cfg["tab"]["cat"]." SET  preid='$tmp_preid_pre', postid='$tmp_preid' WHERE idcat='$tmp_idcat'";
                $db->query($sql);

                $sql = "UPDATE ".$cfg["tab"]["cat"]." SET  preid='$tmp_idcat_pre' WHERE idcat='$tmp_postid'";
                $db->query($sql);

        }
}

function strMoveSubtree ($idcat, $parentid_new) {
        global $db;
        global $cfg;
		// Flag to rebuild the category table
		global $remakeCatTable;
		global $remakeStrTable;
		$remakeCatTable = true;
		$remakeStrTable = true;


        $sql = "SELECT idcat, preid, postid FROM ".$cfg["tab"]["cat"]." WHERE idcat='$idcat'";
        $db->query($sql);
        $db->next_record();
        $tmp_idcat  = $db->f("idcat");
        $tmp_preid  = $db->f("preid");
        $tmp_postid = $db->f("postid");

        //****************** update predecessor (pre)**********************
        if ($tmp_preid != 0) {
                $sql = "UPDATE ".$cfg["tab"]["cat"]." SET postid='$tmp_postid' WHERE idcat='$tmp_preid'";
                $db->query($sql);
        }

        //****************** update follower (post)**********************
        if ($tmp_postid != 0) {
                $sql = "UPDATE ".$cfg["tab"]["cat"]." SET preid='$tmp_preid' WHERE idcat='$tmp_postid'";
                $db->query($sql);
        }

        //****************** find new pre ********************
        $sql = "SELECT idcat, preid FROM ".$cfg["tab"]["cat"]." WHERE parentid='$parentid_new' AND postid='0'";
        $db->query($sql);
        if ($db->next_record()) {
                $tmp_new_preid = $db->f("idcat");
                $tmp_preid_2   = $db->f("preid");
                if ($tmp_new_preid != $idcat) {
                        //******************** update new pre: set post **********************
                        $sql = "UPDATE ".$cfg["tab"]["cat"]." SET postid='$idcat' WHERE idcat='$tmp_new_preid'";
                        $db->query($sql);
                } else {
                        $sql = "SELECT idcat FROM ".$cfg["tab"]["cat"]." WHERE idcat='$tmp_preid_2'";
                        $db->query($sql);
                        if ($db->next_record()) {
                                $tmp_new_preid = $db->f("idcat");
                                //******************** update new pre: set post **********************
                                $sql = "UPDATE ".$cfg["tab"]["cat"]." SET postid='$idcat' WHERE idcat='$tmp_new_preid'";
                                $db->query($sql);
                        } else {
                                $tmp_new_preid = 0;
                        }
                }
        } else {
                $tmp_new_preid = 0;
        }

        //*************** update idcat ********************
        $sql = "UPDATE ".$cfg["tab"]["cat"]." SET parentid='$parentid_new', preid='$tmp_new_preid', postid='0' WHERE idcat='$idcat'";
        $db->query($sql);
}

function strMoveCatTargetallowed($idcat, $source) {
        global $cfg;

        $tmpdb = new DB_Contenido;
        $sql = "SELECT parentid FROM ".$cfg["tab"]["cat"]." WHERE idcat='$idcat'";
        $tmpdb->query($sql);
        $tmpdb->next_record();
        $p = $tmpdb->f("parentid");

        if ($p == $source) {
                return 0;
        } elseif ($p == 0) {
                return 1;
        } else {
                return strMoveCatTargetallowed($p, $source);
        }
}
?>
