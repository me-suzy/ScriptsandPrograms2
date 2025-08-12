<?php
# streber - a php5 based project management system  (c) 2005 Thomas Mann / thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in _docs/license.html

/**
 * functions related to parsing field-times to forms and parse form-params into db-format
 *
 *
 * for rendering as HTML see render/render_form.inc
 *
 * included by: db/db_item.inc
 *
 * @author: Thomas Mann
 * @uses:
 * @usedby:
 *
 */


#====================================================================================
function FieldString_getFormElement(&$field,&$obj) {
    $name= $field->name;
    $field_id= $obj->_type.'_'.$name;

    return new Form_Input($field_id, $field->title, $obj->$name);
}

function FieldString_parseForm(&$field,&$obj) {
    $name= $field->name;
    $field_id= $obj->_type.'_'.$name;
    $value=get($field_id);
    if($value || $value==="" || $value==FALSE) {
        $obj->$name= $value;
    }
}

#====================================================================================
function FieldPassword_getFormElement(&$field,&$obj) {
    $name= $field->name;
    $field_id= $obj->_type.'_'.$name;

    return new Form_Password($field_id, $field->title, $obj->$name);
}

function FieldPassword_parseForm(&$field,&$obj) {
    $name= $field->name;
    $field_id= $obj->_type.'_'.$name;
    $value=get($field_id);
    if($value || $value==="" || $value==FALSE) {
        $obj->$name= $value;
    }
}



#====================================================================================
function FieldInt_getFormElement(&$field,&$obj) {
    $name= $field->name;
    $field_id= $obj->_type.'_'.$name;
    return new Form_Input($field_id, $field->title, $obj->$name);
}

function FieldInt_parseForm(&$field,&$obj) {
    $name= $field->name;
    $field_id= $obj->_type.'_'.$name;
    $value=get($field_id);
    if($value) {
        $obj->$name= $value;
    }
}

#====================================================================================
function FieldPercentage_getFormElement(&$field,&$obj) {
    $name= $field->name;
    $field_id= $obj->_type.'_'.$name;
    return new Form_Input($field_id, $field->title, $obj->$name);
}

function FieldPercentage_parseForm(&$field,&$obj) {
    $name= $field->name;
    $field_id= $obj->_type.'_'.$name;
    $value=get($field_id);
    if($value) {
        $obj->$name= $value;
    }
}

#====================================================================================
function FieldBool_getFormElement(&$field,&$obj) {
    $name= $field->name;
    $field_id= $obj->_type.'_'.$name;
    return new Form_Checkbox($field_id, $field->title, $obj->$name);
}

/**
* Bug/ToDo
* there is no way to see wether a checkbox was turned of or not rendered in the form.
* The option will always be turned off !!
*/
function FieldBool_parseForm(&$field,&$obj) {
    $name= $field->name;
    $field_id= $obj->_type.'_'.$name;

    if(get($field_id)=="on") {
        $obj->$name= true;
    }
    else {
        $obj->$name= false;
    }
}


#====================================================================================

function FieldText_getFormElement(&$field,&$obj) {
    $name= $field->name;
    $field_id= $obj->_type.'_'.$name;

    return new Form_Edit($field_id, $field->title, $obj->$name);
}

function FieldText_parseForm(&$field,&$obj) {
    $name= $field->name;
    $field_id= $obj->_type.'_'.$name;
    $value=get($field_id);
    if($value || $value==="") {
        $obj->$name= $value;
    }
}

#====================================================================================
function FieldDatetime_getFormElement(&$field,&$obj) {
    $name= $field->name;
    $field_id= $obj->_type.'_'.$name;

    return new Form_DateTime($field_id, $field->title, $obj->$name);
}

function FieldDatetime_parseForm(&$field,&$obj) {
    $name= $field->name;
    $field_id= $obj->_type.'_'.$name;
    $value_date=get($field_id.'_date');
    if($value_date ==="" || $value_date ==="-") {
        $value_date=="0000-00-00";
    }
    $value_time=get($field_id.'_time');
    if($value_time==="" || $value_time=== "-") {
        $value_time="00-00-00";
    }
    if($value_date && $value_time) {
        $value= $value_date." ". $value_time;

        # dd.mm.yyyyy hh:mm:ss
        if(
            preg_match("/\b(\d?\d)[^\d](\d?\d)[^\d](\d\d\d\d)\s+(\d\d)[^\d](\d?\d)[^\d](\d?\d)\b/",$value,$matches)
            && count($matches)==7
        ) {
            $value=$matches[3].'-'.$matches[2].'-'.$matches[1] .' '. $matches[4] .'-'. $matches[5] .'-'. $matches[6];
        }
        # yyyy-mm-dd hh:mm:ss
        else if(
            preg_match("/\b(\d\d\d\d)[^\d](\d?\d)[^\d](\d?\d)\s+(\d\d)[^\d](\d\d)[^\d](\d\d)\b/",$value,$matches)
            && count($matches)==7
        ) {
            # $value=$value;        # just fine...
        }
        # Thursday, January 01, 1970 01:00:00
        else if(
            preg_match("/(\w*)\s(\d\d),\s*(\d\d\d\d)\s+(\d\d)[^\d](\d\d)[^\d](\d\d)/i", $value, $matches)
            && count($matches)==7
            && $mon=string2month($matches[1])
        ) {
            $value=$matches[3].'-'.$mon.'-'.$matches[2] .' '. $matches[4] .'-'. $matches[5] .'-'. $matches[6];
        }
        # Thursday, January 01, 1970 01:00
        else if(
            preg_match("/(\w*)\s(\d\d),\s*(\d\d\d\d)\s+(\d\d)[^\d](\d\d)/i", $value, $matches)
            && count($matches)==6
            && $mon=string2month($matches[1])
        ) {
            $value=$matches[3].'-'.$mon.'-'.$matches[2] .' '. $matches[4] .'-'. $matches[5] .'-00';
        }
        # Thursday, January 01, 1970
        else if(
            preg_match("/(\w*)\s(\d\d),\s*(\d\d\d\d)/i", $value, $matches)
            && count($matches)==4
            && $mon=string2month($matches[1])
        ) {
            $value=$matches[3].'-'.$mon.'-'.$matches[2] .' 00-00-00';
        }
        # Donnerstag, 30. Novemeber 1970 12:12:00
        else if(
            preg_match("/(\d?\d)\.\s*(\w*)\s*(\d\d\d\d)\s+(\d\d)[^\d](\d\d)[^\d](\d\d)/i", $value, $matches)
            && count($matches)==7
            && $mon=string2month($matches[2])
        ) {
            $value=$matches[3].'-'.$mon.'-'.$matches[1] .' '. $matches[4] .'-'. $matches[5] .'-'. $matches[6];
        }

        # Tue, 31.12.2001 12:12:00
        else if(
            preg_match("/(\d\d)\.\s*(\d\d)\.\s*(\d\d\d\d)\s+(\d\d)[^\d](\d\d)[^\d](\d\d)/i", $value, $matches)
            && count($matches)==7
        ) {
            $value=$matches[3].'-'.$mon.'-'.$matches[1] .' '. $matches[4] .'-'. $matches[5] .'-'. $matches[6];
        }

        # Tue, 31.12.2001 12:12
        else if(
            preg_match("/(\d\d)\.\s*(\d\d)\.\s*(\d\d\d\d)\s+(\d?\d)[^\d](\d?\d)/i", $value, $matches)
            && count($matches)==6
        ) {
            $value=$matches[3].'-'.$matches[2].'-'.$matches[1] .' '. $matches[4] .'-'. $matches[5] .'-00';
        }


        else {
            global $PH;
            $PH->message="WARNING '$value' isn't a known format for date.";
            $value="0000-00-00 00:00:00";
        }
        $obj->$name= $value;

    }
}

#====================================================================================
function FieldDate_getFormElement(&$field,&$obj) {
    $name= $field->name;
    $field_id= $obj->_type.'_'.$name;
    return new Form_Date($field_id, $field->title, $obj->$name);
}

function FieldDate_parseForm(&$field,&$obj) {
    $name= $field->name;
    $field_id= $obj->_type.'_'.$name;
    $value=get($field_id);
    if($value || $value==="") {

        # dd.mm.yyyyy
        if($value=="") {
            $value="0000-00-00";
        }
        else if(
            preg_match("/\b(\d?\d)[^\d](\d?\d)[^\d](\d\d\d\d)/",$value,$matches)
            && count($matches)==4
        ) {
            $value=$matches[3].'-'.$matches[2].'-'.$matches[1];
        }
        # yyyy-mm-dd
        else if(
            preg_match("/\b(\d\d\d\d)[^\d](\d?\d)[^\d](\d?\d)\b/",$value,$matches)
            && count($matches)==4
        ) {
            # $value=$value;        # just fine...
        }
        # Thursday, January 01, 1970
        else if(
            preg_match("/(\w*)\s(\d\d),\s*(\d\d\d\d)/i", $value, $matches)
            && count($matches)==4
            && $mon=string2month($matches[1])
        ) {
            $value=$matches[3].'-'.$mon.'-'.$matches[2];
        }
        # Thursday, January 01, 1970
        else if(
            preg_match("/(\w*)\s(\d\d),\s*(\d\d\d\d)/i", $value, $matches)
            && count($matches)==4
            && $mon=string2month($matches[1])
        ) {
            $value=$matches[3].'-'.$mon.'-'.$matches[2];
        }
        # Donnerstag, 30. Novemeber 1970 12:12:00
        else if(
            preg_match("/(\d?\d)\.\s*(\w*)\s*(\d\d\d\d)/i", $value, $matches)
            && count($matches)==4
            && $mon=string2month($matches[2])
        ) {
            $value=$matches[3].'-'.$mon.'-'.$matches[1];
        }
        else {
            if($value != "-") {
                global $PH;
                $PH->message="WARNING '$value' isn't a known format for date.";
                $value="0000-00-00";
            }
        }
        $obj->$name= $value;
    }
}


#===================================================================================
function FieldTime_getFormElement(&$field,&$obj) {
    $name= $field->name;
    $field_id= $obj->_type.'_'.$name;
    return new Form_Time($field_id, $field->title, $obj->$name);
}

function FieldTime_parseForm(&$field,&$obj) {
    $name= $field->name;
    $field_id= $obj->_type.'_'.$name;
    $value=get($field_id);
    if($value || $value==="") {

        if($value=="" || $value="-") {
            $value="00-00-00";
        }
        # hh:mm:ss
        else if(
            preg_match("/\b(\d?\d)[^\d](\d?\d)[^\d](\d?\d)/",$value,$matches)
            && count($matches)==4
        ) {
            $value= sprintf("%02d-%02d-%02d", $matches[1],$matches[2],$matches[3]);
        }

        # hh:mm
        else if(
            preg_match("/\b(\d?\d)[^\d](\d?\d)/",$value,$matches)
            && count($matches)==3
        ) {
            $value= sprintf("%02d:%02d:00", $matches[1],$matches[2]);
        }
        else {
            global $PH;
            $PH->message="WARNING '$value' isn't a known format for time.";
            $value="00-00-00";
        }
        $obj->$name= $value;
    }
}

#====================================================================================
function FieldHidden_getFormElement(&$field,&$obj) {
    $name= $field->name;
    $field_id= $obj->_type.'_'.$name;
    return new Form_HiddenField($field_id, $field->title, $obj->$name);
}

function FieldHidden_parseForm(&$field,&$obj) {
    $name= $field->name;
    $field_id= $obj->_type.'_'.$name;
    $value=get($field_id);
    if($value) {
        $obj->$name= $value;
    }
}

#====================================================================================
/**
* if rendered at all (off by default) internal fields are rendered as HiddenField 
*/
function FieldInternal_getFormElement(&$field,&$obj) {
    $name= $field->name;
    $field_id= $obj->_type.'_'.$name;
    return new Form_HiddenField($field_id, $field->title, $obj->$name);
}

function FieldInternal_parseForm(&$field,&$obj) {
    $name= $field->name;
    $field_id= $obj->_type.'_'.$name;
    $value=get($field_id);
    if($value) {
        $obj->$name= $value;
    }
}
#====================================================================================
function FieldUser_getFormElement(&$field,&$obj) {
    return new Form_CustomHtml("");
}

function FieldUser_parseForm(&$field,&$obj) {
}

#====================================================================================
function FieldProject_getFormElement(&$field,&$obj) {
    return new Form_CustomHtml("");
}

function FieldProject_parseForm(&$field,&$obj) {
}

#====================================================================================
function FieldOption_getFormElement(&$field,&$obj) {
    return new Form_CustomHtml("");
}
/*
 todo: - add options-attribute to field
*/
/*function FieldOption_getFormElement(&$field,&$obj) {
    $name= $field->name;
    $field_id= $obj->_type.'_'.$name;
    return new Form_Option($field_id, $field->title, $obj->$name);
}*/

/**
* Bug/ToDo
* there is no way to see wether a checkbox was turned of or not rendered in the form.
* The option will always be turned off !!
*/
function FieldOption_parseForm(&$field,&$obj) {
    $name= $field->name;
    $field_id= $obj->_type.'_'.$name;

    $value= get($field_id);
    if(isset($value)) {
        $obj->$name= $value;
    }
}

?>