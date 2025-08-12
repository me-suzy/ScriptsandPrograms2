<?php

// class.selector.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Authors: Martin Brotzeller, $Author: johann $
// $Id: class.selector.php,v 1.20.2.2 2005/09/10 09:37:24 johann Exp $

// check whether the lib has been included - authentication!
if (!defined('lib_included')) die('Not to be called directly!');


/**
* Klasse PHProjektSelector
*
* Anzeige und Auswahl aus einer Gruppe von Objekten, primär User bzw Kontakte
*
* @author Martin Brotzeller
* @copyright (c) 2004 Mayflower GmbH
* @package PHProjekt
* @access public
*/
class PHProjektSelector {
    /** Typ: single-select oder multiple-select
    *
    * @access public
    */
    var $type;
    /** View: selectbox oder radio/checkboxes
    *
    * @access public
    */
    var $view;
    /** Datasource: Quelle aus der die Objekte ausgelesen werden, primär User- bzw Kontaktetabelle
    * Anhand der datasource wird eine Datei eingelesen die die Quell-spezifischen Funktionen enthält.
    * Zu jeder Datasource gehört eine Doku über die benötigten Felder in sourcedata, sowie
    * eine Funktion
    *      fetch_fields();
    * sowie eine Funktion
    *      show_filters();
    * und eine Funktion
    *      parse_filters();
    *
    * @access private
    */
    var $datasource;
    /** Sourcedata: Daten die zur Anfrage an die Datenquelle benötigt werden; abhängig von der verwendeten Quelle
    *
    * @access private
    */
    var $sourcedata;
    /** Name: Identifier für das Formular
    *
    * @access private
    */
    var $name;
    /**
    * name des Submitbuttons im FinishFormular, das gepostet wird, wenn alle Selektiererei beendet sein soll.
    * Sinn: Dieses Formular soll nur abgeschickt werden, wenn Änderungen , die per JS gemacht
    * wurden noch nicht zum Server übertragen und im Selektor aktualisiert wurden.
    * Anzugeben ist hier formname.buttonname also zB: finishForm.buttonname
    *
    * @access public
    */
    var $finishFormSubmitName = 'finishForm.finishButton';


    /**
    * Array mit Namen und Values für Hidden-Fields, die im Formular noch gebraucht werden.
    * Wie zB mode, view, ...
    *
    * @access private
    * @var array ( formelementNAME => formElementValue, ... )
    */
    var $hidden_fields = array();

    /** Konstruktor
    *
    * @param $name       eindeutiger Name zur Identifikation des Selektors
    * @param $datasource Datenquelle aus der ausgelesen wird
    * @param $sourcedata Daten für Auslese und Darstellung der Objekte
    * @param $type       Anzeigetyp
    * @param $view       Anzeigemodus
    * @access public
    */
    function PHProjektSelector($name, $datasource=NULL, $sourcedata=array(), $type='single', $view='select') {
        global $phprojekt_selector_idx;

        if (!isset($name) || $name=="") die('Bitte einen Namen angeben');
        $this->name = $name;
        if ($datasource != NULL && in_array($datasource, array('contacts', 'users'))){
            require_once("../lib/selector/datasource_".$datasource.".php");
            $this->sourcedata =& $sourcedata;
            $this->datasource =  $datasource;
        } else {
            die("Datenquelle für Auswahl nicht definiert!");
        }

        if ($type=="single" || $type="multiple") $this->type = $type;
        else die("Ungültiger Typ $type für Auswahl angegeben");
        if ($view=="select" || $view="buttons") $this->view  = $view;
        else die("Ungültiger View $view für Auswahl angegeben");

        if (!isset($phprojekt_selector_idx) ||  $phprojekt_selector_idx == "") {
            $phprojekt_selector_idx = 0;
        }
    }

    /**
    * Hidden fields setzen, die im Formulat gesetzt sein müssen ($mode, $view usw).
    * Die übergebene Array wird durchlaufen und konvertiert keys und values auf string.
    *
    * @access public
    * @param $fields array (ElementName => ElementValue, ...)
    */
    function set_hidden_fields($fields) {
        if (!is_array($fields)) return;
        foreach ($fields AS $name => $value) {
            $this->hidden_fields[(string)$name] = (string)$value;
        }
    }

    /** Anzeige der Auswahl
    *
    * @param $preselect Vorauswahl, bestehend aus array von IDs passend zur Datenquelle
    * @param $submiturl URL die die Auswahl auswerten soll
    * @param $size      Anzeigemenge, falls 0 wird global min(PHPR_FILTER_MAXHITS,Treffer) genommen
    * @param $window    Falls true, immer extra Fenster zeigen
    * @access public
    */
    function display($preselect=array(), $submiturl="", $size=0, $window=0, $readonly=0) {
        echo $this->get($preselect, $submiturl, $size,$window,$readonly);
    }

    /** Auswahl als String
    *
    * @param $preselect Vorauswahl, bestehend aus array von IDs passend zur Datenquelle
    * @param $submiturl URL die die Auswahl auswerten soll
    * @param $size      Anzeigemenge, falls 0 wird global min(PHPR_FILTER_MAXHITS,Treffer) genommen
    * @param $window    Falls true, immer extra Fenster zeigen
    * @access public
    */
    function get($preselect=array(), $submiturl="", $size=0, $window=0, $readonly=0) {
        global $sthis;

        if (isset($this->sourcedata['choose'])) $choose = $this->sourcedata['choose'];
        else $choose = "Choose";

        $selstr = "";
        $fetch  = $this->datasource."fetch_fields";
        $fields = $fetch($this->sourcedata);
        // falls Anzeigemenge nicht erreicht, einfach select anzeigen
        if ((is_array($fields) && (PHPR_FILTER_MAXHITS==0 || PHPR_FILTER_MAXHITS>count($fields)) && !$window)) {
            if ($size==0) $size = min(PHPR_FILTER_MAXHITS, count($fields));
            $selstr .= $this->show_select($fields, $preselect, $submiturl, $size);
        } else {
            // sonst Button zum Fenster öffnen bereitstellen
            if ($size==0) $size = PHPR_FILTER_MAXHITS;
            //$sthis = urlencode((serialize(array('this'=>$this, 'preselect'=>$preselect, 'size'=>$size))));
            if (!$readonly) {
                //unset($_SESSION['sthis']);
                $_SESSION['sthis1'][$this->name] = array('this'=>$this,'preselect'=>$preselect,'size'=>$size);
                // FIXME when does this occur??
                $selstr .= "<input title='".__('This button opens a popup window')."' type='button' value='$choose' onclick='window.open(\"cselector.php?name=".$this->name."\",\"Selektor\",\"width=700;height=600;scrollbars=yes\");return false;' />\n";
            }
            if (!empty($preselect)) {
                $options = $this->sourcedata;
                $options['limit']   = 0;
                $options['where'][] = $this->sourcedata['ID']." in ('".implode("','",array_keys($preselect))."')";
                $prefields = $fetch($options);
                $selstr .= $this->list_items($prefields);
            }
            //$sthis = urlencode((serialize(array('this'=>$this, 'preselect'=>$preselect, 'size'=>$size))));
            //$selstr .= "<input title='".__('This button opens a popup window')."' type='button' value='Choose' onclick='window.open(\"cselector.php?sthis=$sthis\",\"Selektor\",\"width=800;height=600\");return false;' />\n";
        }
        return $selstr;
    }


    /** Erzeugen der Auswahlbox(en)
    *
    * @param $fields    Array von Feldern
    * @param $preselect Vorauswahl aktiver Objekte
    * @param $submiturl Falls vorhanden wird das Formular dorthin gelenkt, sonst ohne Form-Tags
    * @param $size      Anzeigehöhe
    * @access
    */
    function show_select($fields, $preselect, $submiturl, $size) {
        $selstr = "";
        $disableSubmit  = $this->finishFormSubmitName.'.disabled=true; ';
        $disableSubmit .= $this->finishFormSubmitName.'.setAttribute("class","submit_disabled","true"); ';

        if ($submiturl != "")
            $selstr .= "<script type='text/javascript'>\n<!--\nfunction selectme() {\nselectall('".$this->name."');\n}\n//-->\n</script>\n";
        //$selstr .= "<form action='$submiturl' method='post' name='".$this->name."' onsubmit=\"selectall('".$this->name."dsts');\">\n";
        //$selstr .= "<form action='$submiturl' method='post' name='".$this->name."' onsubmit=\"selectme();\">\n";

        $selstr .= "\n<input type='hidden' name='parse".$this->name."' value='".$this->type.$this->view."' />\n";
        if ($this->view == "select"){
            if ($this->type == "multiple"){
                $srcslct = array();
                $dstslct = array();
                if (!empty($fields)) {
                    foreach ($fields as $k => $v) {
                        if (isset($preselect[$k])) $dstslct[$k] = $v;
                        else $srcslct[$k] = $v;
                    }
                }
                $selstr.= "<table border='0' class='selector_select_multiple'>\n\t<tr>\n\t<td width='200' class='selector_select_multiple'>\n";
                $selstr.= "\t\t".__('found elements')."<br />\n";
                $selstr.= "\t\t<select size='$size' name='".$this->name."srcs[]' multiple='multiple' class='selector_select_multiple'>\n";
                foreach ($srcslct as $k => $v) {
                    $selstr.= "\t\t\t<option value='$k'>".xss($v)."</option>\n";
                }
                $selstr.= "\t\t</select>\n";
                $selstr.= "\t</td>\n\t<td valign='top' class='selector_select_multiple'>\n";
                $selstr.= "\t\t<input class='selector_mover' type='submit' name='movsrcdst' value='->' onclick='".$disableSubmit."MoveOption(\"".$this->name."srcs[]\",\"".$this->name."dsts[]\");return false;' /><br /><br />\n";
                $selstr.= "\t\t<input class='selector_mover' type='submit' name='movdstsrc' value='<-' onclick='".$disableSubmit."MoveOption(\"".$this->name."dsts[]\",\"".$this->name."srcs[]\");return false;' />\n";
                $selstr.= "\t</td>\n\t<td width='200' class='selector_select_multiple'>\n";
                $selstr.= "\t\t".__('chosen elements')."<br />\n";
                $selstr.= "\t\t<select size='$size' name='".$this->name."dsts[]' multiple='multiple' class='selector_select_multiple'>\n";
                foreach ($dstslct as $k => $v) {
                    $selstr.= "\t\t\t<option value='$k'>".xss($v)."</option>\n";
                }
                $selstr.= "\t\t</select>\n";
                $selstr.= "\t</td>\n\t</tr>\n</table>\n";
            } else {
                // type is "single"
                $selstr.="<select size=$size name='".$this->name."dsts[]'>\n";
                foreach ($fields as $k => $v) {
                    $selected=(isset($preselect[$k]))?"selected=selected":"";
                    $selstr.="\t<option value='$k' $selected>$v</option>\n";
                }
                $selstr.="</select>\n";
            }
        } else {
            // view="buttons"
            if ($this->type=="multiple") {
                $pattern="<tr><td><input type='checkbox' name='".$this->name."dsts[]' value='%s' %s /> %s </td></tr>";
            } else {
                // type is "single"
                $pattern="<tr><td><input type='radio' name='".$this->name."dsts[]' value='%s' %s /> %s </td></tr>";
            }
            $selstr.= "<table border='0'>\n";
            foreach ($fields as $k => $v) {
                $checked = (isset($preselect[$k])) ? "checked='checked'" : "";
                $selstr .= sprintf($pattern, $k, $checked, $v);
            }
            $selstr.= "</table>\n";
        }
        if ($submiturl != "") {
            // $selstr.= "<input type='image' src='$path_pre/images/los.gif' />\n";
            $selstr.= "<input type='submit' class='submit' value='".__('go')."' />\n";
            $selstr.= "</form>\n";
        }
        return $selstr;
    }


    /** Funktion zum Anzeigen der Auswahl in einem separaten Fenster mit Filtermöglichkeit
    *
    * @param $preselect  Array mit vorselektierten Einträgen
    * @param $size       Anzeigemenge
    * @param $postaction Adresse, auf die regulär gepostet werden soll
    * @access
    */
    function show_window($preselect, $size, $postaction="") {
        global $filters, $path_pre, $selektor_answer;

        if (empty($postaction)) $postaction = $_SERVER['PHP_SELF'];
        $_SESSION['filters'] =& $filters;
        $sarr =& $filters[$this->name];
        $options = $this->sourcedata;
        if (!empty($sarr)) foreach ($sarr as $k=>$v) {
            $options['where'][] = $v;
        }
        if (!is_array($options['where'][0])) {
            $options['where'][0]="( ".$options['where'][0];
            $options['where'][]="1=1 ) OR ".$options['ID']." in ('".implode("','",array_keys($preselect))."')";
        } else {
            $options['wherechosen'] = array_keys($preselect);
        }
        $fetch  = $this->datasource."fetch_fields";
        $fields = $fetch($options);
        $sthis  = urlencode((serialize(array('this'=>$this, 'preselect'=>$preselect, 'size'=>$size))));

        echo "
<script type='text/javascript'>
<!--
function selectme() {
    selectall('".$this->name."dsts[]');
}
//-->
</script>

<form action='".$postaction."' method='post' name='".$this->name."' onsubmit=\"selectme();\">
";

        //echo "<form action='".$_SERVER['PHP_SELF']."' method='post' onsubmit=\"selectall('".$this->name."dsts[]');\">\n";
        // hack: build get params from hidden fields for href links...
        $getprm = array_merge($this->hidden_fields, array('preselect'=>implode('-', array_keys($preselect))));
        $dspl = $this->datasource."display_filters1";
        echo $dspl($options, $sthis, $this->name, $getprm);

        if (is_numeric($fields)) {
            $lim = $options['limit'] ? $options['limit'] : PHPR_FILTER_MAXHITS + count($preselect);
            if (!empty($this->sourcedata['filter'])) {
                // echo "<br />Die Treffermenge von $fields übersteigt die Anzeigemenge von ".$lim.". Bitte setzen Sie einen schärferen Filter.<br /><br />\n";
                // echo $fields." &gt; ".$lim.": ".__('too many hits')." ".__('please extend filter');
                echo __('too many hits')." ".__('please extend filter')."<br /><br />\n";
            } else {
                // echo "<br />Die Treffermenge von $fields übersteigt die Anzeigemenge von ".$lim."<br /><br />\n";
                // echo $fields." &gt; ".$lim.": ".__('too many hits');
                echo __('too many hits')."<br /><br />\n";
            }
            //foreach ($preselect as $k => $v)
            //    echo "<input type='hidden' name='".$this->name."dsts[]' value='$k' />";
            if (is_array($options['where'][0])) {
                $options['wherechosen'] = array_keys($preselect);
                foreach ($options['where'] as $k => $v) {
                    if (is_array($v)) $options['where'][$k][0] .= ' AND 0 ';
                }
            } else {
                // verhindert daß neue kontakte gefunden werden, nur bereits gewählte werden gezeigt.
                $options['where'][0] .= " AND 0 ";
            }
            $fields = $fetch($options);
            //} else {
        }
        if (isset($selektor_answer) && $selektor_answer != '') {
            echo "<div class='answer'>$selektor_answer</div>\n";
        }
        echo $this->show_select($fields, $preselect, '', $size);

        // add all needed hidden fields
        foreach ($this->hidden_fields as $name => $value) {
            echo "<input type='hidden' name='".$name."' value='".xss($value)."' />\n";
        }
        unset($name, $value);

        echo "<br />\n<input type='submit' class='submit' value='".__('Refresh')."' />\n</form>\n";
    }


    /** Auflisten von Einträgen, speziell selektierte Einträge wenn die Auswahl über ein extra Fenster geht
    *
    * @param $items Liste von Einträgen die gezeigt werden sollen
    * @access private
    */
    function list_items($items) {
        global $bgcolor1, $bgcolor2;

        $lpar   = 0;
        $selstr = "<table border='0'>\n";
        foreach ($items as $key =>$value){
            $bgnow = $lpar ? $bgcolor1 : $bgcolor2;
            $selstr .= "    <tr><td bgcolor='$bgnow'> $value</td></tr>\n";
            $lpar^=1;
        }
        $selstr .= "</table>\n<br />\n";
        return $selstr;
    }


    /** Entscheidet anhand von type und view woher die Ergebnisse zu beziehen sind.
    *
    *
    * @access public
    */
    function get_chosen() {
        $result = array();
        //echo $this->type.$this->view."post: ".var_export($_POST[$this->name."dsts"],true);
        switch ($this->type.$this->view) {
            case "singleselect":
            case "multipleselect":
            case "multiplebuttons":
                if (!empty($_POST[$this->name."dsts"]))
                    foreach($_POST[$this->name."dsts"] as $key=>$val){
                        $result[$val] = "on";
                    }
                break;
            case "singlebuttons":
                $result = array($_POST[$this->name."dsts"][0]=>'on');
                break;
        }
        //echo "chosen: ".var_export($result,true);
        return $result;
    }
    /** Speichert Daten im Vorgegebenen Feld der angegebenen Tabelle
    *
    * @access public
    */
    function save_chosen() {
        //error_log('save chosen, save is'.var_export($this->sourcedata['save'],true));
        if (isset($this->sourcedata['save']) && !empty($this->sourcedata['save'])) {
            $sav = $this->sourcedata['save'];
            if (is_array($this->sourcedata['save'])) {
                if ($sav['method'] == 'serialize') {
                    $chos = $this->get_chosen();
                    foreach ($chos as $k => $v) $chos[$k] = $k;
                    $query = xss("UPDATE ".DB_PREFIX.$sav['table']."
                                     SET ".$sav['field']."='".serialize($chos)."'
                                   WHERE ".$sav['where']);
                    //error_log("saving $query");
                    db_query($query) or db_die();
                }
            } else if (is_string($this->sourcedata['save']) && function_exists($this->sourcedata['save'])) {
                $chos   = $this->get_chosen();
                $savfun = $this->sourcedata['save'];
                $savfun($chos, $this->sourcedata['savedata']);
            }
        }
    }
}

?>
