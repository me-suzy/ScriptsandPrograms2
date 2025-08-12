<?php

/******************************************
* File      :   include.con_editcontent.php
* Project   :   Contenido
* Descr     :   Include for editing the
*               content in an article
*
* Author    :   Jan Lengowski
*
* Created   :   00.00.0000
* Modified  :   15.04.2003
*
* © four for business AG
******************************************/

if (!is_object($db2)) $db2 = new DB_Contenido;


		
if ( isset($idcat) ) {

        if( $action == 20 || $action == 10 ) {

            if( $data != "" ) {

                $array1 = explode("||", substr($data, 0, -2));

                foreach($array1 as $value){

                    $array2 = explode("|", $value);

                    if ( $array2[2] == "!!" ){
                        $array2[2] = "";
                    } else {
                        $array2[2] = str_replace("§%%§", "|", $array2[2]);
                    }

                    conSaveContentEntry($array2[0], "CMS_".$array2[3], $array2[1], $array2[2]);
                    conGenerateCodeForArtInAllCategories($idart);					
                }

                conGenerateCodeForArtInAllCategories($idart);
                
            }
        }

if ( $action == 10 ){

/*	echo "<pre>";
	print_r($HTTP_POST_VARS);
	print_r($HTTP_GET_VARS);
	echo "</pre>";*/
	
	header("Location: ".$cfg["path"]["contenido_fullhtml"].$cfg["path"]["includes"]."include.backendedit.php?type=$type&typenr=$typenr&idcat=$idcat&idart=$idart&idartlang=$idartlang&contenido=$contenido&lang=$lang");
    //include ($cfg["path"]["contenido"].$cfg["path"]["includes"].'include.'.$type.'.php');

} else {

    $markSubItem = markSubMenuItem(3, true);
    
$scripts .= <<<EOD

<script language="javascript">

// searches the classname in the td above or the tr above
function getCellClass(element) {

    var el = document.getElementById(element);
    var cell = el.offsetParent;

    // if there is a classname in the td return the classname
    if ( cell.className != '' ) {
        return cell.className;
    }

    // set a flag in this td
    if ( cell.id == '' ) {
       cell.id = 'yes';
       var flg = 'yes'
       
    } else {
       var flg = cell.id;
       
    }

    //else go to the tagname table above   and search dowen for the tr tags
    while (el.tagName != 'TABLE') {
          el = el.offsetParent;
    }


    var elements = el.getElementsByTagName('TR');

    //go thrue all tr tags
    for (var row in elements) {

        if (isNaN(elements[row])) {
             var cells = elements[row].getElementsByTagName('TD');
             for (var id in cells) {
                 if(isNaN(cells[id])){

                   // check if the flg(td cell) is in this tr    if true return the calssname
                   if (cells[id].id==flg&&elements[row].className != '') {
                       return elements[row].className;
                   }
                 }
             }

        }




    }



    return false;
}


function setcontent(idart, act) {

        var a = document.getElementsByTagName("*");
        var str = '';
        var aId = '';
        var dcoElementCnt = 0;

        // loop through all elements
        for (var i=0; i < a.length; i++) {
                aId = a[i].id;
                aIdPrefix = aId.substr(0,4);

                // search for the id which containes HTML
                if (aIdPrefix == 'HTML') {

                        // check if its an 'contentEditable' Field
                        if (a[i].isContentEditable == true) {

                                 // read out the content
                                 var aContent = a[i].innerHTML;

                                 // split the idname in data - datas 0 is the Fieldname   2 is the typeid
                                 var data = aId.split("_");

                                 if ( aContent == "" ) {
                                    aContent = "!!";
                                    
                                 } else {

                                    // if there is an | in the text set a replacement chr because we use it later as isolator
                                    while( aContent.search(/\|/) != -1 ) {
                                        aContent = aContent.replace(/\|/,"§%%§");
                                    }
                                 }
                                 
                                 // build the string which will be send
                                 str += idart +'|'+ data[2] +'|'+ aContent +'|'+ data[0] +'||';

                        }
                        
                        
                        
                        
                        
                }

        }
        
        // set the string
        document.forms.editcontent.data.value = str;

        // set the action string
        if ( act != 0 ) {
            document.forms.editcontent.action = act;
        }

        // if there are 3 arguments, the className has to be seached
        if(arguments.length > 2){

            //search the class of the above element
            var classname = getCellClass(arguments[2]);

            if ( classname ) {
                document.forms.editcontent.con_class.value = classname;
            }
        }
        
        // submit the form
        document.forms.editcontent.submit();
        
}

</script>

EOD;

        $scripts .= '<script src="'.$cfg["path"]["contenido_fullhtml"].'external/mozile/mozileLoader.js" type="text/javascript"></script>';
        
		ob_start();
		
        echo "<form name=\"editcontent\" method=\"post\" action=\"".$sess->url("front_content.php?area=con_editcontent&idart=$idart&idcat=$idcat&lang=$lang&action=20")."\">\n";
        echo "<input type=\"hidden\" name=\"changeview\" value=\"edit\">\n";
        echo "<input type=\"hidden\" name=\"data\" value=\"\">\n";
        echo "<input type=\"hidden\" name=\"con_class\" value=\"\">\n";
        echo "</form>";
        
        $contentform = ob_get_contents();
        ob_end_clean();
        

        #
        # extract IDCATART
        #
        $sql = "SELECT
                    idcatart
                FROM
                    ".$cfg["tab"]["cat_art"]."
                WHERE
                    idcat = '".$idcat."' AND
                    idart = '".$idart."'";
                    
        $db->query($sql);
        $db->next_record();
        
        $idcatart = $db->f("idcatart");

        #
        # Article is not configured,
        # if not check if the category
        # is configured. It neither the
        # article or the category is
        # configured, no code will be
        # created and an error occurs.
        #
        
        $sql = "SELECT
                    a.idtplcfg AS idtplcfg
                FROM
                    ".$cfg["tab"]["art_lang"]." AS a,
                    ".$cfg["tab"]["art"]." AS b
                WHERE
                    a.idart     = '".$idart."' AND
                    a.idlang    = '".$lang."' AND
                    b.idart     = a.idart AND
                    b.idclient  = '".$client."'";

        $db->query($sql);
        $db->next_record();

        if ( $db->f("idtplcfg") != 0 ) {

            #
            # Article is configured
            #
            $idtplcfg = $db->f("idtplcfg");

            $a_c = array();

            $sql2 = "SELECT
                        *
                     FROM
                        ".$cfg["tab"]["container_conf"]."
                     WHERE
                        idtplcfg = '".$idtplcfg."'
                     ORDER BY
                        number ASC";

            $db2->query($sql2);

            while ( $db2->next_record() ) {
                $a_c[$db2->f("number")] = $db2->f("container");
                
            }
                
        } else {

            #
            # Check whether category is
            # configured.
            #
            $sql = "SELECT
                        a.idtplcfg AS idtplcfg
                    FROM
                        ".$cfg["tab"]["cat_lang"]." AS a,
                        ".$cfg["tab"]["cat"]." AS b
                    WHERE
                        a.idcat     = '".$idcat."' AND
                        a.idlang    = '".$lang."' AND
                        b.idcat     = a.idcat AND
                        b.idclient  = '".$client."'";

            $db->query($sql);
            $db->next_record();

            if ( $db->f("idtplcfg") != 0 ) {

                #
                # Category is configured,
                # extract varstring
                #
                $idtplcfg = $db->f("idtplcfg");

                $a_c = array();

                $sql2 = "SELECT
                            *
                         FROM
                            ".$cfg["tab"]["container_conf"]."
                         WHERE
                            idtplcfg = '".$idtplcfg."'
                         ORDER BY
                            number ASC";

                $db2->query($sql2);

                while ( $db2->next_record() ) {
                    $a_c[$db2->f("number")] = $db2->f("container");

                }

            } else {

                #
                # Article nor Category
                # is configured. Creation of
                # Code is not possible. Write
                # Errormsg to DB.
                #

                include_once ($cfg["path"]["contenido"].$cfg["path"]["classes"]."class.notification.php");
                include_once ($cfg["path"]["contenido"].$cfg["path"]["classes"]."class.table.php");

                if ( !is_object($notification) ) {
                    $notification = new Contenido_Notification;
                }

                $sql = "SELECT title FROM ".$cfg["tab"]["art_lang"]." WHERE idartlang = '".$idartlang."'";
                $db->query($sql);
                $db->next_record();
                $art_name = $db->f("title");

                $cat_name = "";
                conCreateLocationString($idcat, "&nbsp;/&nbsp;", $cat_name);
                
                $sql = "SELECT name FROM ".$cfg["tab"]["lang"]." WHERE idlang = '".$lang."'";
                $db->query($sql);
                $db->next_record();
                $lang_name = $db->f("name");
                
                $sql = "SELECT name FROM ".$cfg["tab"]["clients"]." WHERE idclient = '".$client."'";
                $db->query($sql);
                $db->next_record();
                $client_name = $db->f("name");

                $noti_html = '<table cellspacing="0" cellpadding="2" border="0">

                                <tr class="text_medium">
                                    <td colspan="2">
                                        <b>'.i18n("No template assigned to the category<br>and/or the article").'</b><br><br>
                                        '.i18n("The code for the following article<br>couldnt be generated:").'
                                        <br><br>
                                    </td>
                                </tr>
                                
                                <tr class="text_medium">
                                    <td >'.i18n("Article").':</td>
                                    <td><b>'.$art_name.'</b></td>
                                </tr>
                                
                                <tr class="text_medium">
                                    <td >'.i18n("Category").':</td>
                                    <td><b>'.$cat_name.'</b></td>
                                </tr>
                                
                                <tr class="text_medium">
                                    <td>'.i18n("Language").':</td>
                                    <td><b>'.$lang_name.'</b></td>
                                </tr>
                                
                                <tr class="text_medium">
                                    <td>'.i18n("Client").':</td>
                                    <td><b>'.$client_name.'</b></td>
                                </tr>
                                
                                <tr>
                                    <td colspan="2">&nbsp;</td>
                                </tr>

                              </table>';
                
                $code = '
                        <html>
                            <head>
                                <title>Error</title>
                                <link rel="stylesheet" type="text/css" href="'.$cfg["path"]["contenido_fullhtml"].$cfg["path"]["styles"].'contenido.css"></link>
                            </head>
                            <body style="margin: 10px">'.$notification->returnNotification("error", $noti_html).'</body>
                        </html>';
                
                $sql = "SELECT * FROM ".$cfg["tab"]["code"]." WHERE idcatart='".$idcatart."' AND idlang='".$lang."'";
                
                $db->query($sql);
                
                if ($db->next_record()) {
                    $sql = "UPDATE ".$cfg["tab"]["code"]." SET code='".$code."', idlang='".$lang."', idclient='".$client."' WHERE idcatart='".$idcatart."' AND idlang='".$lang."'";
                    $db->query($sql);

                } else {
                    $sql = "INSERT INTO ".$cfg["tab"]["code"]." (idcode, idcatart, code, idlang, idclient) VALUES ('".$db->nextid($cfg["tab"]["code"])."', '".$idcatart."', '".$code."', '".$lang."', '".$client."')";
                    $db->query($sql);
                }

                echo $code;
                    
            }
            
        }

        #
        # Get IDLAY and IDMOD array
        #
        $sql = "SELECT
                    a.idlay AS idlay,
                    a.idtpl AS idtpl
                FROM
                    ".$cfg["tab"]["tpl"]." AS a,
                    ".$cfg["tab"]["tpl_conf"]." AS b
                WHERE
                    b.idtplcfg  = '".$idtplcfg."' AND
                    b.idtpl     = a.idtpl";

        $db->query($sql);
        $db->next_record();

        $idlay = $db->f("idlay");
        $idtpl = $db->f("idtpl");

        #
        # List of used modules
        #
        $sql = "SELECT
                    number,
                    idmod
                FROM
                    ".$cfg["tab"]["container"]."
                WHERE
                    idtpl = '".$idtpl."'
                ORDER BY
                    number ASC";

        $db->query($sql);
        
        while ( $db->next_record() ) {
            $a_d[$db->f("number")] = $db->f("idmod");
        }


        #
        # Get code from Layout
        #
        $sql = "SELECT * FROM ".$cfg["tab"]["lay"]." WHERE idlay = '".$idlay."'";
        
        $db->query($sql);
        $db->next_record();
        
        $code = $db->f("code");
        $code = AddSlashes($code);

        #
        # Create code for all containers
        #
        if ($idlay) {

                $tmp_returnstring = tplBrowseLayoutForContainers($idlay);
                $a_container = explode("&", $tmp_returnstring);
                
                foreach ($a_container as $key=>$value) {

                    $sql = "SELECT * FROM ".$cfg["tab"]["mod"]." WHERE idmod='".$a_d[$value]."'";
                    
                    $db->query($sql);
                    $db->next_record();
                    
                    $output = $db->f("output");
                    $output = AddSlashes($output);

                    $template = $db->f("template");

                    $tmp1 = preg_split("/&/", $a_c[$value]);
                    
                    $varstring = array();
                    
                    foreach ($tmp1 as $key1=>$value1) {
                            $tmp2 = explode("=", $value1);
                            foreach ($tmp2 as $key2 => $value2) {
                                    $varstring["$tmp2[0]"] = $tmp2[1];
                            }
                    }

                    foreach ($varstring as $key3=>$value3) {
                        $CiCMS_VALUE = "C".$key3."CMS_VALUE";
                        $tmp = urldecode($value3);
                        $tmp = str_replace("\'", "'", $tmp);    // ' war das einzige Sonderzeichen was mit \ maskiert wurde. !?
                        $output  = str_replace("CMS_VALUE[$key3]", $tmp, $output);
                    }

                    $output = ereg_replace("(CMS_VALUE\[)([0-9]*)(\])", "", $output);
                    $code  = str_replace("CMS_CONTAINER[$value]", $output, $code);
                        
                }
        }

        #
        # Find out what kind of CMS_... Vars are in use
        #
        $sql = "SELECT
                    *
                FROM
                    ".$cfg["tab"]["content"]." AS A,
                    ".$cfg["tab"]["art_lang"]." AS B,
                    ".$cfg["tab"]["type"]." AS C
                WHERE
                    A.idtype    = C.idtype AND
                    A.idartlang = B.idartlang AND
                    B.idart     = '".$idart."' AND
                    B.idlang    = '".$lang."'";
        
        $db->query($sql);
        
        while ( $db->next_record() ) {
            $a_content[$db->f("type")][$db->f("typeid")] = $db->f("value");
        }

        $sql = "SELECT idartlang FROM ".$cfg["tab"]["art_lang"]." WHERE idart='".$idart."' AND idlang='".$lang."'";
        
        $db->query($sql);
        $db->next_record();
        
        $idartlang = $db->f("idartlang");

        #
        # Replace all CMS_TAGS[]
        #
        $sql = "SELECT idtype, type, code FROM ".$cfg["tab"]["type"];

        $db->query($sql);

        while ( $db->next_record() ) {

            $tmp = preg_match_all("/(".$db->f("type")."\[+\d+\])/", $code, $match);
            $a_[strtolower($db->f("type"))] = $match[0];
            $success = array_walk($a_[strtolower($db->f("type"))], 'extractNumber');

            foreach ($a_[strtolower($db->f("type"))] as $val) {

                $edit = "true";
                eval ($db->f("code"));
                $code  = str_replace("".$db->f("type")."[$val]", $tmp, $code);

            }

        }

        /* output the code */
        $code = stripslashes($code);
        $code = preg_replace("/<\/head>/i", "$markSubItem $scripts</head>", $code);
        $code = preg_replace("/<\/body>/i", "$contentform</body", $code);
      
      	eval("?>\n".$code."\n<?php\n");
      	
    }
}
page_close();

?>
