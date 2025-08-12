<?php

/******************************************
* File      :   includes.tplcfg_edit_form.php
* Project   :   Contenido
* Descr     :   Displays form for configuring a template
*
* Author    :   Olaf Niemann
* Created   :   2002
* Modified  :   28.03.2003
*
* © four for business AG
*****************************************/
$tpl->reset();

$sql = "SELECT * FROM ".$cfg["tab"]["container_conf"]." WHERE idtplcfg='".$idtplcfg."'";
$db->query($sql);

$a_c = array();

while ($db->next_record()) {
    $a_c[$db->f("number")] = $db->f("container");                // 'varstring' is safed in $a_c
}

//Form
$formaction = $sess->url("main.php");
#<input type="hidden" name="action" value="tplcfg_edit">
$hidden     = '<input type="hidden" name="area" value="tpl_cfg">
               <input type="hidden" name="frame" value="'.$frame.'">
               <input type="hidden" name="idcat" value="'.$idcat.'">
               <input type="hidden" name="idart" value="'.$idart.'">
               <input type="hidden" name="idtpl" value="'.$idtpl.'">
               <input type="hidden" name="lang" value="'.$lang.'">
               <input type="hidden" name="idtplcfg" value="'.$idtplcfg.'">
               <input type="hidden" name="changetemplate" value="0">';

$tpl->set('s', 'FORMACTION', $formaction );
$tpl->set('s', 'HIDDEN', $hidden );


/*
//SELECT Box for Templates
$tpl->set('s', 'TEMPLATECAPTION', $lngCon["template"]);
$tpl2 = new Template;
$tpl2->set('s', 'NAME', 'idtpl');
$tpl2->set('s', 'CLASS', 'text_medium');
$tpl2->set('s', 'OPTIONS', 'onchange="tplcfgform.changetemplate.value=1;tplcfgform.submit();"');

if ($idlay != "0" && $idart || $idtpl == "0") {
    $tpl2->set('d', 'VALUE',    '0');
    $tpl2->set('d', 'CAPTION',  $lngForm["nothing"]);
    $tpl2->set('d', 'SELECTED', '');
    $tpl2->next();
}
*/

$sql = "SELECT
            idtpl,
            name
        FROM
            ".$cfg["tab"]["tpl"]."
        WHERE
            idclient = '".$client."' AND
            idtpl    = '".$idtpl."'";
        
$db->query($sql);
$db->next_record();

$tpl->set('s', 'TEMPLATECAPTION', "Template: ");
$tpl->set('s', 'TEMPLATESELECTBOX', $db->f("name"));

//************** For all Containers list module input
$sql = "SELECT
            *
        FROM
            ".$cfg["tab"]["container"]."
        WHERE
            idtpl='$idtpl' ORDER BY idcontainer ASC";
        
$db->query($sql);
while ($db->next_record()) {
        $a_d[$db->f("number")] = $db->f("idmod");                // 'list of used modules' is safed in $a_d
}

if (isset($a_d) && is_array($a_d)) {
    foreach ($a_d as $cnumber=>$value) {
        // nur die Container anzeigen, in denen auch ein Modul enthalten ist
        if ($value != 0) {

                $sql = "SELECT
                            *
                        FROM
                            ".$cfg["tab"]["mod"]."
                        WHERE
                            idmod = '".$a_d[$cnumber]."'";
                        
                $db->query($sql);
                $db->next_record();

                $input = $db->f("input")."\n";

                $modulecaption = $lngCon["moduleincontainer"]." ".$cnumber.": ";
                $modulename    = $db->f("name");

//              echo "$a_c[$cnumber]<br><br>";

                $varstring = array();
                if (isset($a_c[$cnumber])) {
                    $a_c[$cnumber] = preg_replace("/&$/", "", $a_c[$cnumber]);
                    $tmp1 = preg_split("/&/", $a_c[$cnumber]);

                    foreach ($tmp1 as $key1=>$value1) {
                            $tmp2 = explode("=", $value1);
                            foreach ($tmp2 as $key2=>$value2) {
                                    $varstring[$tmp2[0]]=$tmp2[1];
                            }
                    }
                }
                    foreach ($varstring as $key3=>$value3) {
                            $CiCMS_VALUE = "C".$key3."CMS_VALUE";
                            $CiCMS_VAR = "C".$key3."CMS_VAR";
                            $tmp = urldecode($value3);
                            $tmp = str_replace("\'","'",$tmp);    // ' war das einzige Sonderzeichen was mit \ maskiert wurde. !?
                            $input  = str_replace("CMS_VALUE[$key3]", $tmp, $input);
                    }

                    $input  = str_replace("CMS_VAR", "C".$cnumber."CMS_VAR" , $input);
                    $input = ereg_replace("(CMS_VALUE\[)([0-9]*)(\])", "", $input);

                    ob_start();
                    eval($input);
                    $modulecode = ob_get_contents();
                    ob_end_clean();

                    $tpl->set('d', 'MODULECAPTION', $modulecaption);
                    $tpl->set('d', 'MODULENAME',    $modulename);
                    $tpl->set('d', 'MODULECODE',    $modulecode);
                    $tpl->next();

        }
    }
}

$tpl->set('s', 'SCRIPT',        '');
$tpl->set('s', 'MARKSUBMENU',   '');

$buttons = '<a href="javascript:history.back()"><img src="images/but_cancel.gif" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="image" src="images/but_ok.gif">';

$tpl->set('s', 'BUTTONS', $buttons);

# Generate template
$tpl->generate($cfg['path']['templates'] . $cfg['templates']['tplcfg_edit_form']);

?>
