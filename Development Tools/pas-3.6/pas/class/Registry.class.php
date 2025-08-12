<?php
// Copyright 2001 - 2004 SQLFusion LLC           info@sqlfusion.com

 /**
  * Registry
  * This files contain the Registry class and all the core regitryfield class
  * @see RegistryFieldBase, Registry
  * @package PASClass
  */

 /**
  *  Apply load and apply the registry rules.
  *
  *  The Registry is load from a .reg.xml file in registry/ directory.
  *  for each field element in the .reg.xml file a RegistryField object
  *  is created based on its fieldtype (rdata).<br>
  *  It can then be apply for Form context or Display context.
  *  Each RegistryField object contains a default_form and default_disp
  *  method that generate HTML code for both context.
  *  From the .reg.xml files all the rdata values are accessible
  *  with the getRdata().
  *  And they can also be methods of a RegistryField object.
  *  This is to simplify the extention of the RegistryField object.
  *  Currently ordering or executions of methods as been diseabled.
  *
  * @package PASClass
  * @author Philippe Lewicki  <phil@sqlfusion.com>
  * @copyright  SQLFusion LLC 2001-2004
  * @version 3.0.0
  * @access public
  */
Class RegistryFieldBase extends BaseObject {

    var $processed = "";
    var $field_name;
    var $default_value;
    var $nothing = true;
    var $dbc;
    var $form_name = "";
    var $rdata = Array();
    var $exec_order = Array();
    var $originalval ;
    var $field_value;

    /**
     * RegistryFieldBase constructor
     *
     * The constructor used by the Registry object to
     * create new fields for that registry.
     * @param string $field_name name of the files of that registry / table
     * @param array $rtype_rdata array with all rdata tags in format $rtype_rdata[name]=value loaded from the xml file.
     * @param sqlConnect $dbc database connexion from the Registry object. Needed if a registry field needs to run a query
     * @access public
     */

    function RegistryFieldBase($field_name, $rtype_rdata=0, $dbc=0) {
        $this->field_name = $field_name;
        $this->dbc = $dbc;
        if (is_array($rtype_rdata)) {
            $this->setRDatas($rtype_rdata);
        } else {
            $this->setRDatas(Array()) ;
        }
     /** Curently ordering is not implemented
      *  Not sure its needed.
        $this->exec_order['default'] = 20;
        $this->exec_order['required'] = 30;
        $this->exec_order['hidden'] = 40;
        $this->exec_order['readonly'] = 50;
        $this->exec_order['textline'] = 500;
        $this->exec_order['nothing'] = 50000;
      **/
        $this->setLogRun(false);
    }

    /**
     * setFormName set a name for the form context
     *
     * In the form context some fields type needs the name of the form to generate some javascripts code.
     * this value comes from the Registry object. Use the method from Registry not this one.
     *
     * @param string $formname Name of the form for the form context
     * @see getFormName()
     */
    function setFormName($formname) {
        $this->setLogRun(false);
        $this->form_name = $formname ;
    }

    /**
     * getFormName return the name of the form
     * @return string Name of the form
     */
    function getFormName() {
        return $this->form_name;
    }

    /**
     * @return sqlConnect current database connection
     */
    function getDbCon() {
        return $this->dbc;
    }

    /**
     * process will execute all rdata methods and default_ for a context
     *
     * Process recieve the context and the value of the field.
     * The rdata can be used with getRData() and setRdata() but they can also be methods of a RegistryField object.
     * If the reg.xml files for that field contains a rdata thats define as a method, that method will then be executed.
     * Naming Format for a rdata method follow this convention :
     * rdata<context>_rdatatype
     * For exemple for the reg.xml sample :
     * <code>
     * <rdata type="datef">d/m/Y - H:i:s</rdata>
     * </code>
     * The associate rdata method for a Display context would be :
     * <code>
     * rdata_Disp_datef($field_value);
     * </code>
     * After processing all the rdata methods the default_<Context> methods are executed for that field.
     *
     * @param string $context name of the context usualy "disp" or "form"
     * @param string $field_value value of field.
     * @see getRData(), setRData()
     */

    function process($context, $field_value) {
      $this->originalval = $field_value;
      $this->setFieldValue($field_value);
      $this->processed = "";
      if (empty($field_value) && !empty($this->default_value)) {
          if (strlen($this->getFieldValue()) == 0)  { $this->setFieldValue($this->default_value) ; }
          $field_value = $this->default_value;
          //$this->setLog("\n init set field value to default".$field_value);
      }
      //  $this->setLog("\n Initialisation of processing field".$this->field_name);
      //  asort($this->exec_order) ;  Sort currently not used
      foreach($this->rdata as $rtype=>$rdata) {
      //  $this->setLog("\n rtype ".$rtype." order ".$order);
        if (!empty($rdata)) {
         //      $this->setLog("\n looking for method "."rdata".$context."_".$rtype);
            if (method_exists($this, "rdata".$context."_".$rtype)) {
              // $this->setLog("\n found method "."rdata".$context."_".$rtype." for:".$this->field_name);
               $this->{"rdata".$context."_".$rtype}($field_value) ;
            }
        }
       // $this->setLog("\n Set to default if :empty(".$field_value.") && !empty(".$this->default_value.")");
        if (empty($field_value) && !empty($this->default_value)) {        
            if (strlen($this->getFieldValue()) == 0)  { $this->setFieldValue($this->default_value) ; }
            $field_value = $this->default_value;
          //  $this->setLog("\n rdata set field value to default:".$field_value);
        }
      }
      if (method_exists($this, "default_".$context)) {
         //   $this->setLog("\n default".$context." -field-name->".$this->field_name."|--value-->".$field_value."<");
          $this->{"default_".$context}($field_value) ;
      }
      return $this->processed;
    }

    /**
     * rdataForm_default rdata method
     *
     * All fields can have default value.
     * If a default rdata is found with a value and the field_value is empty then the default value found is assign  to the property default_value
     * @param string $field_value value of the field
     */

    function rdataForm_default($field_value="") {
        $rdata = $this->getRData('default');
        if(!empty($rdata)) {
            if (substr($rdata, 0, 1) == "[" && substr($rdata, strlen($rdata) -1, 1) == "]") {
                $defaultvar = substr($rdata, 1, strlen($rdata) -2 ) ;
                if (ereg(";", $defaultvar)) {
                    $a_paramdefaultvar = explode(";", $defaultvar);
                    $defaultvalue = $a_paramdefaultvar[0]($a_paramdefaultvar);
                } else {
                    $defaultvalue = $GLOBALS[$defaultvar] ;
                }
            } else {
                $defaultvalue = $rdata;
            }
        $this->default_value = $defaultvalue;
        } else {
            $this->default_value = "";
        }
        $this->setLog("\n default (".$this->field_name.":".$rdata.") varname: ".$defaultvar." value:".$this->default_value);
    }

    /**
     * rdataForm_required rdata method require for form context.
     *
     * This rdata will trigger the mydb.checkRequired event to check if the field contains a value.
     * @param string $field_value value of the field
     */
    function rdataForm_required($field_value="") {
        if ($this->getRData('required')) {
            $fval = "<input type=\"hidden\" name=\"mydb_events[6]\" value=\"mydb.checkRequired\"/>" ;
            $fval .= "<input type=\"hidden\" name=\"required[".$this->getFieldName()."]\" value=\"yes\"/>" ;
            $this->processed .= $fval;
        }
    }

    /**
     * rdataForm_hidden rdata method hidden for form context
     *
     * This if a rdata hidden is found then the hidden field is set in form context
     * all other rdata methods and default_method will be check the getRData('hidden') before processing.
     * and return nothing in all contexts.
     * @param string $field_value value of the field
     */
    function rdataForm_hidden($field_value="") {
        if ($this->getRData('hidden')) {
            $this->processed .= "<input type=\"hidden\" name=\"fields[$this->field_name]\" value=\"".htmlentities($this->getFieldValue())."\"/>"  ;
        }
    }

    /**
     * rdataForm_hidden rdata method readonly for form context
     *
     * This if a rdata readonly is found then the hidden field is set in form context
     * all other rdata methods and default_method will be check the getRData('readonly') before processing.
     * and if in the disp context it will display its value.
     * @param string $field_value value of the field
     */
    function rdataForm_readonly($field_value="") {
        if ($this->getRData('readonly')) {
            $this->processed .= "<input type=\"hidden\" name=\"fields[$this->field_name]\" value=\"".htmlentities($this->getFieldValue())."\"/>"  ;
        }
    }

    /**
     * @deprecate not used and dont use it
     */
    function rdataForm_nothing($field_value="") {
             $this->processed .= "<input class=\"adformfield\" type=\"text\" name=\"fields[".$this->field_name."]\" value=\"".$this->getFieldValue()."\"/>";
    }

    
   /**
    * setRDatas 
    * Set the rdata for this field with a new Array of RDatas.
    * The keys contains rdata types.
    * This will overwrite all preset rdatas.
    *
    * @param array $a_rtypes Array will all rdata for this field
    */
    function setRDatas($a_rtypes) {
        $this->rdata = $a_rtypes;
    }
   /** 
    * getRData 
    * Return all the rdatas type as an array
    * The array is indexed on the type.
    *
    * @return array with all rdata type indexed on the type
    */
    function getRDatas() {
        return $this->rdata;
    }
    
   /**
    * getRData 
    * Return the value of one RData type
    *
    * @param string $rtype type of the RData
    * @return string value of the rdata
    */
    function getRData($rtype) {
        return $this->rdata[$rtype];
    }
    
   /**
    * setRData 
    * Set only one rdata for this field.
    * 
    * @param string $type type of rdata (hidden, default, textline...)
    * @param string value values of this rdata.
    * @see setRdatas
    */
    function addRData($type, $value) {
        $this->rdata[$type] = $value;
    }
    
    /**
    * setRData 
    * Set only one rdata for this field.
    * 
    * @param string $type type of rdata (hidden, default, textline...)
    * @param string value values of this rdata.
    * @see addRdata, setRdata
    */
    function setRdata($type, $value) {
        $this->addRData($type, $value);
    }
    
    function setFieldName($field_name) {
        $this->field_name = $field_name;
    }
    
    function getFieldName() {
        return $this->field_name;
    }
    
    /** 
     * Set the value of a field.
     * Used with getFieldValue it allow rdata methods to 
     * share and change the value of the field.
     * @access private
     * @param string $field_value value of the field to be set.
     */    
    function setFieldValue($field_value) {
        $this->field_value = $field_value;
    }
    function getFieldValue() {
        return $this->field_value;
    }
    
    /**
     * Change PHP tags to HTML entities.
     * This is an important to security feature
     * So the users can execute php code from datacontent they
     * have access to.
     *
     * PHL Note: This function seems to have an influance on perfomances.
     * Will need more QA and profiling.
     * 
     * @access private
     * @param string $code field value to parse and transform php tags.
     * @return string $code string with the php tags in html entities.
     */    
    function no_PhpCode($code) {
        $code = str_replace("<?", "&lt;?", $code);
        $code = str_replace("?>", "?&gt;", $code);
        $code = str_replace("<%", "&lt;?", $code);
        $code = str_replace("%>", "?&gt;", $code);
        return $code;
    }
}

/**
 * Class strFBFieldTypeChar
 *
 * This is the default field type.
 * Its used when no other field type are set and it extends fields type that
 * doesn't need more than a textline field.
 * Also if no textline rdata are set the default will fallback to textline,
 * @package PASClass
 */

Class strFBFieldTypeChar extends RegistryFieldBase {
    function rdataForm_textline($field_value="") {
        $field_value = $this->getFieldValue();
        if (!$this->getRData('hidden') && !$this->getRData('readonly')) {
            if (!$this->getRData('execute'))  {
                $field_value = $this->no_PhpCode($field_value);
            }
            $regparams = explode(":", $this->getRData('textline')) ;
            $disabled = ""; if ($this->getRdata('disabled')) { $disabled = " disabled"; }
            $this->processed  .= "<input class=\"adformfield\" type=\"text\" name=\"fields[".$this->getFieldName()."]\" value=\"".htmlentities($field_value)."\"  size=\"$regparams[0]\"  maxlength=\"$regparams[1]\"".$disabled."/>";
        }
    }

    function default_Form($field_value="") {
        $field_value = $this->getFieldValue();
        if (!$this->getRData('hidden') && !$this->getRData('readonly') && strlen($this->getRData('textline')) == "") {
            if (!$this->getRData('execute'))  {
                $field_value = $this->no_PhpCode($field_value);
            }
            $disabled = ""; if ($this->getRdata('disabled')) { $disabled = " disabled"; }
            $this->processed  .= "<input class=\"adformfield\" type=\"text\" name=\"fields[".$this->getFieldName()."]\" value=\"".htmlentities($field_value)."\"  size=\"40\"  maxlength=\"100\"".$disabled."/>";
        }
    }

    function rdataDisp_substring($field_value="") {
        $field_value = substr( $this->getFieldValue(), 0, $this->getRData("substring")) ;
        $this->setFieldValue($field_value);
    }
    
    function default_Disp($field_value="") {
        $field_value = $this->getFieldValue();
        if (!$this->getRData('hidden')) {
            if (!$this->getRData('execute'))  {
                $field_value = $this->no_PhpCode($field_value);
            }
            $this->processed .= $field_value;
        }
    }
}

/**
 * Class strFBFieldTypeInt
 *
 * Inherit everything from strFBFieldTypeChar
 * @package PASClass
 */
Class strFBFieldTypeInt extends  strFBFieldTypeChar {
     function default_disp($field_value="") {
         if (!$this->getRData('hidden')) {
             if (strlen($this->getRData('numberformat'))>0) {
                 list($prestr, $dec_num, $dec_sep, $thousands,  $poststr) = explode(":", $this->getRData('numberformat'));
                 $this->processed .= $prestr.number_format($this->getFieldValue(), $dec_num, $dec_sep, $thousands).$poststr;
             } else {
                  $this->processed .= $this->getFieldValue();
             }
         }
     }
}

/**
 * Class strFBFieldTypeText
 *
 * Display a textarea box in Form context
 * @package PASClass
 */
Class strFBFieldTypeText extends RegistryFieldBase {
    function default_Form($field_value="") {
    $rdata = $this->getRData('textarea');
    if (!$this->getRData('hidden') && !$this->getRData('readonly')) {
        $disabled = ""; if ($this->getRdata('disabled')) { $disabled = " disabled"; }
        if(strlen($this->getRData('htmleditor')) > 2) {
             // temporary diseabled, will be replaced by field type (HTMLArea)
            $fieldname = "fields[".$this->getFieldName()."]" ;
        } else {
            $fieldname = "fields[".$this->getFieldName()."]" ;
        //     $fieldvalue = htmlentities($fieldvalue);
        }
        $regparams = explode(":", $rdata) ;
        $fval .= "<textarea id=\"".$this->getFieldName()."\" class=\"adformfield\" name=\"".$fieldname."\" rows=\"".$regparams[1]."\" cols=\"".$regparams[0]."\"" ;
        if (strlen($this->getRData('wrap')) > 0 ) { $fval .= " wrap=\"".$this->getRData('wrap')."\"" ;}
        $fval .=$disabled.">".htmlentities($this->getFieldValue())."</textarea>\n";
        $this->processed .= $fval;

    }
    }
    
  function rdataDisp_substring($field_value="") {
        $field_value = substr( $this->getFieldValue(), 0, $this->getRData("substring")) ;
        $this->setFieldValue($field_value);
  }
  
  function default_Disp($field_value="") {
        $field_value = $this->getFieldValue();
        if (!$this->getRData('hidden')) {
            if ($this->getRData('html')) {
                $this->processed .= htmlspecialchars($field_value);
            } else {
                if (!$this->getRdata('execute')) {
                    $field_value = $this->no_PhpCode($field_value);
                }
                $this->processed .= $field_value;
            }
        }
    }

}

/**
 * Class strFBFieldTypeListBox RegistryField class
 *
 * Display a drop down (SELECT) in the Form context.
 * The content of the drop down are from a separate table.
 * Display the value of the displayfield from the other table in the Disp context.
 * @package PASClass
 */
Class strFBFieldTypeListBox extends RegistryFieldBase {
    function default_Form($field_value="") {
        $rdata = $this->getRData('list');
        $dbc = $this->getDbCon();
        if (!$this->getRData('hidden') && !$this->getRData('readonly')) {
            list($tablename, $fielduniqid, $fielddisplay, $defaultvalue, $query) = explode (":", $rdata) ;
            if (substr($defaultvalue, 0, 1) == "[" && substr($defaultvalue, strlen($defaultvalue) -1, 1) == "]") {
                $defaultvar = substr($defaultvalue, 1, strlen($defaultvalue) -2 ) ;
                if (ereg(";", $defaultvar)) {
                    $a_paramdefaultvar = explode(";", $defaultvar);
                    if (function_exists($a_paramdefaultvar[0])) {
                        $defaultvalue = $a_paramdefaultvar[0]($a_paramdefaultvar);
                    }
                } else {
                    global $$defaultvar ;
                    $defaultvalue = $$defaultvar ;
                }
            }
            if (strlen($field_value) > 0) { $defaultvalue = $field_value;  }
            $fval = "<select class=\"adformfield\" name=\"fields[".$this->field_name."]\">\n" ;
            if ($this->getRData('emptydefault') != "no") {
                $fval .= "<option value=\"\"></option>";
            }
            if (strlen($query) > 0) {
                $qlist = new sqlSavedQuery($dbc, $query) ;
                $qlist->query() ;
            } else {
                $qlist = new sqlQuery($dbc) ;
                $qlist->query("select  $fielduniqid, $fielddisplay from $tablename order by $fielddisplay") ;
            }
            while ($alistcontent = $qlist->fetchArray()) {
                $tmp_selected = "" ;
                if (trim($alistcontent[0]) == trim($defaultvalue)) { $tmp_selected = " selected" ; }
                $fval .= "<option value=\"".htmlentities($alistcontent[0])."\"".$tmp_selected.">" ;
                for ($i=1; $i<count($alistcontent) ; $i++) {
                    $fval .= htmlentities($alistcontent[$i])." " ;
                }
                $fval .= "</option>\n" ;
            }
            $fval .= "</select>";
            $this->processed .= $fval;
        }
    }

    function default_Disp($field_value="") {
        if (!$this->getRData('hidden')) {
            $dbc = $this->getDbCon();
            list($tablename, $fielduniqid, $fielddisplay, $defaultvalue, $query) = explode (":", $this->getRData('list')) ;
           // if (strlen($query) > 0) { // PhL 20040126 the query return a list of values and can't be used to retrieve the value it self
           //     $qFieldDisplay = new sqlSavedQuery($dbc, $query) ;
           //     $qFieldDisplay->query() ;
          //  } else {
                $qFieldDisplay = new sqlQuery($dbc) ;
                $qFieldDisplay->query("select  $fielduniqid, $fielddisplay from $tablename where $fielduniqid='".$field_value."'") ;
            //}
            $avfielddisplay = $qFieldDisplay->fetchArray() ;
            $fval = "" ;
            for ($i=1; $i<count($avfielddisplay) ; $i++) {
                $fval .= $avfielddisplay[$i]." " ;
            }
        // $fval=$vfielddisplay;
            $fval = substr($fval, 0, strlen($fval)-2);
            $qFieldDisplay->free() ;
            if (!$this->getRdata('execute')) {
                    $fval = $this->no_PhpCode($fval);
            }
            $this->processed .= $fval;
        }
    }
}

/**
 * Class strFBFieldTypeListBoxSmall RegistryField class
 *
 * Display a drop down (SELECT) in the Form context.
 * The content of the drop down are from the rdata listvalues and listkeys
 * @package PASClass
 */
Class strFBFieldTypeListBoxSmall extends RegistryFieldBase {
    function default_Form($field_value="") {
        if (strlen($this->getRData('listvalues'))>0) {
            $values = explode(":", $this->getRData('listvalues'));
        } else {
            $values = explode(":", $this->getRData('listlabels'));
        }
        $labels = explode(":", $this->getRData('listlabels'));
        if (strlen($field_value) > 0) { 
            $defaultvalue = $field_value;  
          } else {
            $defaultvalue = $this->default_value;
         }        
        if (!$this->getRData('hidden') && !$this->getRData('readonly')) {
            $fval = "<select class=\"adformfield\" name=\"fields[".$this->field_name."]\">\n" ;
            if ($this->getRData('emptydefault') != "no") {
                $fval .= "<option value=\"\"></option>";
            }
            for($i=0; $i<count($labels); $i++) {
                $tmp_selected = "";
                if (trim($values[$i]) == trim($defaultvalue)) { $tmp_selected = " selected" ; }
                  $fval .= "\n<option value=\"".htmlentities($values[$i])."\"".$tmp_selected.">".$labels[$i]."</option>" ;
            }
            $fval .= "</select>";
            $this->processed .= $this->no_PhpCode($fval);
        }
    }

    function default_Disp($field_value="") {
        if (!$this->getRData('hidden')) {
        if (strlen( $this->getRData('listvalues'))>0) {
            $values = explode(":", $this->getRData('listvalues'));
        } else {
            $values = explode(":", $this->getRData('listlabels'));
        }
        $labels = explode(":", $this->getRData('listlabels'));
        for($i=0; $i<count($labels); $i++) {
            if (trim($values[$i]) == trim($field_value)) {  $fval = $labels[$i] ; }
        }
        if (!$this->getRdata('execute')) {
            $fval = $this->no_PhpCode($fval);
        }
        $this->processed .= $fval;
        }
    }
}


/**
 * Class strFBFieldTypeFloat
 *
 * Inherit everything from strFBFieldTypeChar
 * @package PASClass
 */
Class strFBFieldTypeFloat extends strFBFieldTypeChar {
     function default_disp($field_value="") {
         if (!$this->getRData('hidden')) {
             if (strlen($this->getRData('numberformat'))>0) {
                 list($prestr, $dec_num,  $dec_sep, $thousands, $poststr) = explode(":", $this->getRData('numberformat'));
                 $this->processed .= $prestr.number_format($field_value, $dec_num,  $dec_sep, $thousands).$poststr;
             } else {
                  $this->processed .= $field_value;
             }
         }
     }
}

/**
 * Class strFBFieldTypeCheckBox RegistryField class
 *
 * Display a checkbox in the Form Context.
 * In Disp context if the box is checked the content of the default value is displayed.
 * @package PASClass
 */
Class strFBFieldTypeCheckBox extends RegistryFieldBase {
    function default_Form($field_value="") {
        if (!$this->getRData('hidden') && !$this->getRData('readonly')) {
            $fval .= "<input type=\"hidden\" name=\"fields[".$this->getFieldName()."]\" value=\"\"/>" ;
            $fval .= "\n<input class=\"adformfield\" name=\"fields[".$this->getFieldName()."]\" value=\"".htmlentities($this->default_value)."\" type=\"checkbox\"";
            if ($this->originalval == $this->default_value) { $fval .= " checked"; }
            $fval .= "/>";
        // $this->setLog("\n".$fname." ".$originalval." == ".$fieldvalue);
        $this->processed .= $fval;
       }
    }
    function default_Disp($field_value="") {
        if (!$this->getRData('hidden')) {
            $this->processed .= $field_value;
        }
    }

}

/**
 * Class strFBFieldTypeRadioButton RegistryField class
 *
 * Display a list of radio buttons in the Form context, retrive the list or radio button from an external table..
 * @package PASClass
 * @see strFBFieldTypeListBox
 */
Class strFBFieldTypeRadioButton extends strFBFieldTypeChar {
    function default_Form($field_value="") {
        //        $rdata = $this->getRData('radiobutton');
        $dbc = $this->getDbCon();
        $fieldvalue = $field_value;
        $fname = $this->getFieldName();
        if (!$this->getRData('hidden') && !$this->getRData('readonly')) {
            list($tablename, $fielduniqid, $fielddisplay, $defaultvalue) = explode (":", $this->getRData('radiobutton')) ;
            if (substr($defaultvalue, 0, 1) == "[" && substr($defaultvalue, strlen($defaultvalue) -1, 1) == "]") {
                $defaultvar = substr($defaultvalue, 1, strlen($defaultvalue) -2 ) ;
                if (ereg(";", $defaultvar)) {
                    $a_paramdefaultvar = explode(";", $defaultvar);
                    $defaultvalue = $a_paramdefaultvar[0]($a_paramdefaultvar);
                } else {
                    global $$defaultvar ;
                    $defaultvalue = $$defaultvar ;
                }
            }
            if (strlen($fieldvalue) > 0) { $defaultvalue = $fieldvalue;  }
            $qlist = new sqlQuery($dbc) ;
            $qlist->query("select $fielddisplay, $fielduniqid from $tablename order by $fielddisplay") ;
            while (list($vfielddisplay, $vfielduniqid) = $qlist->fetchArray()) {
                $tmp_selected = "" ;
                if ($vfielduniqid == $defaultvalue) { $tmp_selected = " checked" ; }
                $fval .= "<input type=\"radio\" name=\"fields[".$fname."]\" value=\"".htmlentities($vfielduniqid)."\"".$tmp_selected."/>".$this->no_PhpCode($vfielddisplay)."\n" ;
                if ($this->getRData("vertical") != "no") { $fval.="<br/>"; } else { $fval.="&nbsp;&nbsp;"; }
                $tmp_selected = "" ;
            }
            $this->processed .= $fval;
        }
    }

    function default_Disp($field_value="") {
        if (!$this->getRData('hidden')) {
            $dbc = $this->getDbCon();
            list($tablename, $fielduniqid, $fielddisplay, $defaultvalue, $query) = explode (":", $this->getRData('radiobutton')) ;
            $qFieldDisplay = new sqlQuery($dbc) ;
            $qFieldDisplay->query("select  $fielduniqid, $fielddisplay from $tablename where $fielduniqid='".$field_value."'") ;
            $avfielddisplay = $qFieldDisplay->fetchArray() ;
            $fval = "" ;
            for ($i=1; $i<count($avfielddisplay) ; $i++) {
                $fval .= $avfielddisplay[$i]." " ;
            }
        // $fval=$vfielddisplay;
            $fval = substr($fval, 0, strlen($fval)-2);
            $qFieldDisplay->free() ;
            $this->processed .= $this->no_PhpCode($fval);
        }
    }
}

/**
 * Class strFBFieldTypeRadioButtonSmall RegistryField class
 *
 * Display a list of radio buttons in the Form context,
 * retrive the list or radio button from rdata radiovalues and radiolabels
 * @package PASClass
 * @see strFBFieldTypeListBoxSmall
 */
Class strFBFieldTypeRadioButtonSmall extends RegistryFieldBase {
    function default_Form($field_value="") {
        if (strlen( $this->getRData('radiovalues'))>0) {
            $values = explode(":", $this->getRData('radiovalues'));
        } else {
            $values = explode(":", $this->getRData('radiolabels'));
        }
        $labels = explode(":", $this->getRData('radiolabels'));
       // if (strlen($field_value)==0 && strlen($this->default_value)>0) {
       //    $field_value = $this->default_value;
       // }
        if (!$this->getRData('hidden') && !$this->getRData('readonly')) {
            for($i=0; $i<count($labels); $i++) {
                $tmp_selected = "";
                if (trim($values[$i]) == trim($field_value)) { $tmp_selected = " checked" ; }
                  $fval .= "\n<input class=\"adformfield\" type=\"radio\" name=\"fields[".$this->field_name."]\" value=\"".htmlentities($values[$i])."\"".$tmp_selected."/>".$labels[$i];
                  if ($this->getRData("vertical") != "no") { $fval.="<br/>"; } else { $fval.="&nbsp;&nbsp;"; }
            }
            $this->processed .= $this->no_PhpCode($fval);
        }
    }

    function default_Disp($field_value="") {
        if (!$this->getRData('hidden')) {
        if (strlen( $this->getRData('radiovalues'))>0) {
            $values = explode(":", $this->getRData('radiovalues'));
        } else {
            $values = explode(":", $this->getRData('radiolabels'));
        }
        $labels = explode(":", $this->getRData('radiolabels'));
        for($i=0; $i<count($labels); $i++) {
            if (trim($values[$i]) == trim($field_value)) {  $fval = $labels[$i] ; }
        }
        $this->processed .= $this->no_PhpCode($fval);
        }
    }
}

/**
 * Class strFBFieldTypeEmail RegistryField class
 *
 * In the Form context trigger the EventAction: mydb.checkEmail to check the value field in is a real  email address.
 * In the Disp context display the email content around a mailto: link.
 * @package PASClass
 */
Class strFBFieldTypeEmail extends strFBFieldTypeChar {
    function default_Form($field_value="") {
        if (!$this->getRData('hidden') && !$this->getRData('readonly')) {            
            $this->processed .= "<input type=\"hidden\" name=\"mydb_events[7]\" value=\"mydb.checkEmail\"/>" ;
            $this->processed .="<input name=\"emailfield[]\" type=\"hidden\" value=\"".$this->field_name."\"/>" ;
        }
    }

    function default_Disp($field_value="") {
        if (!$this->getRData('hidden')) {
          if (!$this->getRdata('execute')) {
                    $field_value = $this->no_PhpCode($field_value);
          }
          $this->processed .= "<a class=\"mailtolink\" href=\"mailto:".$field_value."\">".$field_value."</a>" ;
        }
    }

}

/**
 * Class strFBFieldFile RegistryField class
 *
 * In the Form context Display a input type File and trigger the EventAction: mydb.formatPictureField that will process the uploaded file.
 * In the Disp context if the file is an image display it in an image tag, otherwize in a link to download it.
 * @package PASClass
 */
Class strFBFieldTypeFile extends RegistryFieldBase {
    function default_Disp($field_value="") {
        $file_path = trim($this->getRData('picture'));
        if (!ereg("/$", $file_path)) {
            $file_path .= "/";
        }
        if (!$this->getRdata('execute')) {
            $field_value = $this->no_PhpCode($field_value);
        }
        if ($this->getRData('showpicture')=="1" && !empty($field_value)) {
            $fval="<img border=\"0\" src=\"".$file_path.$field_value."\">";
         } else {
            $fval = $file_path.$field_value;
            $fval = "<a href=\"".$fval."\">".$fval."</a>" ;
         }
         $this->processed .= $fval;
    }

    function default_Form($field_value="") {
        if (!$this->rdata['hidden'] && !$this->rdata['readonly']) {
            if (!$this->getRdata('execute')) {
                    $field_value = $this->no_PhpCode($field_value);
            }
            list ($filedir, $filename) = explode(":", $this->rdata['picture']) ;
            $fval .= "<input type=\"hidden\" name=\"mydb_events[5]\" value=\"mydb.formatPictureField\"/>" ;
            if (strlen($filedir) > 0) {
                $fval .= "<input type=\"hidden\" name=\"filenameuploaded[]\" value=\"$filename\"/>" ;
            }
            $fval .= "<input type=\"hidden\" name=\"filedirectoryuploaded[]\" value=\"$filedir\"/>" ;
            $fval .= "<input type=\"hidden\" name=\"filefield[]\" value=\"".$this->field_name."\"/>";
            $fval .= "<input type=\"hidden\" name=\"fields[".$this->field_name."]\" value=\"".$field_value."\"/>";
            $fval .= "<input class=\"adformfield\" name=\"userfile[]\" type=\"file\"/>";
            if($field_value!="") $fval .= "(".$field_value.")";
            $this->processed .= $fval;
        }
    }
}

/**
 * Class strFBFieldTypeDate RegistryField class
 *
 * In the Form context display the date in 3 line field and trigger the EventAction: mydb.formatDateField to reformat the 3 fields in a standard unix timestamp.
 * In the Disp context display the date in the format template provided in the rdata datef
 * @package PASClass
 */
Class strfFBFieldTypeDate extends RegistryFieldBase {
    function default_Form($field_value="") {
        $fieldvalue = $field_value;
        if (!$this->getRData('hidden') && !$this->getRData('readonly')) {
            $fname = $this->getFieldName();
            list($dateFormat, $today, $hidden, $popup) = explode(":", $this->getRData('datef')) ;
            if ($today == "today" && $fieldvalue < 10) {
                $fieldvalue = time() ;
            }
            if ($hidden) {
                $datefieldtype = "hidden" ;
            } else {
                $datefieldtype = "text" ;
            }
            $day = date("d", $fieldvalue) ; $month = date("m", $fieldvalue) ; $year = date("Y", $fieldvalue) ;
            $hour = date("H", $fieldvalue) ; $minute = date("i", $fieldvalue) ; $second = date("s", $fieldvalue) ;
            $fval .= "<div class=adformfield> <input type=hidden name=datefieldname[] value=\"".$fname."\">";
            $fval .= "<input type=\"hidden\" name=\"mydb_events[4]\" value=\"mydb.fieldsToArray\"/>" ;
            $fval .= "<input type=\"hidden\" name=\"fields[$fname]\" value=\"\"/>" ;
            $fday = " <input type=\"$datefieldtype\" name=\"datefieldday_$fname\" value=\"".$day."\"  size=\"4\"  maxlength=\"2\"/>" ;
            $fmonth = " <input type=\"$datefieldtype\" name=\"datefieldmonth_$fname\" value=\"".$month."\"  size=\"4\"  maxlength=\"2\"/>" ;
            $fyear = " <input type=\"$datefieldtype\" name=\"datefieldyear_$fname\" value=\"".$year."\"  size=\"4\" maxlength=\"4\"/>" ;
            if (ereg("[His]", $dateFormat)) {
                $fhour = " <input type=\"$datefieldtype\" name=\"datefieldhour[$fname]\" value=\"".$hour."\"  size=\"4\"  maxlength=\"2\"/>" ;
                $fminute = " <input type=\"$datefieldtype\" name=\"datefieldminute[$fname]\" value=\"".$minute."\"  size=\"4\"  maxlength=\"2\">" ;
                $fsecond = " <input type=\"$datefieldtype\" name=\"datefieldsecond[$fname]\" value=\"".$second."\"  size=\"4\"  maxlength=\"2\">" ;
                $datefields = str_replace("H", "phlppehour", $dateFormat) ;
                $datefields = str_replace("i", "phlppeinute", $datefields) ;
                $datefields = str_replace("s", "phlppesecon", $datefields) ;
                $dateFormat = $datefields ;
            }
            $datefields = str_replace("d", "phlppesjour", $dateFormat) ;
            $datefields = str_replace("m", "phlppesos", $datefields) ;
            $datefields = str_replace("Y", "phlppesanne", $datefields) ;
            $datefields = str_replace("phlppesjour", $fday, $datefields) ;
            $datefields = str_replace("phlppesos", $fmonth, $datefields) ;
            $datefields = str_replace("phlppesanne", $fyear, $datefields) ;
            if (ereg("[His]", $dateFormat)) {
                $datefields = str_replace("phlppehour", $fhour, $datefields) ;
                $datefields = str_replace("phlppeinute", $fminute, $datefields) ;
                $datefields = str_replace("phlppesecon", $fsecond, $datefields) ;
            }
            if ($hidden) {
                $datefields = str_replace("/", "", $datefields) ;  $datefields = str_replace("-", "", $datefields) ;
            } elseif (($popup == "1") && (file_exists($this->dbc->getProjectDirectory()."images/popup_icon_calendar.gif"))) {
                if ($this->datejsinclude) {
                    $js = "
                    <script language=\'javascript\'>
                        function open_popup_calendar(url, form, field, field2, field3) {
                            if (form=='') form = 'forms[0]';
                            var old_value1 = eval('document.'+form+'.'+field+'.value');    old_value1 = escape(old_value1);
                            var old_value2 = eval('document.'+form+'.'+field2+'.value');old_value2 = escape(old_value2);
                            var old_value3 = eval('document.'+form+'.'+field3+'.value');old_value3 = escape(old_value3);
                            new_window = open(url+'?form='+form+'&field='+field+'&field2='+field2+'&field3='+field3+'&old_value1='+old_value1+'&old_value2='+old_value2+'&old_value3='+old_value3,'Calendar','left=30,top=30,resizable=yes,width=250,height=200');
                            return false;
                        }
                    </script>
                        ";

                        echo $js;
                        $this->datejsinclude = false;
                }
                $fval .= "<a HREF=\"#\" onClick=\"open_popup_calendar('popup_calendar.php','".$this->getFormName()."','datefieldyear_".$fname."','datefieldmonth_".$fname."','datefieldday_".$fname."');\"><img SRC=\"images/popup_icon_calendar.gif\" BORDER=0></a>";
            }
            $fval .= $datefields ;
            $fval .= "<input type=\"hidden\" name=\"mydb_events[31]\" value=\"mydb.formatDateField\"/>" ;
            $fval .= "</div>";
            $this->processed .= $fval;
         }

    }
    function default_Disp($field_value="") {

        if (!$this->getRData('hidden') && strlen($this->getRData('datef')) > 2) {
            $this->setLog("\n datef Display : ".$this->getRData('datef')." - Timestamp:".$field_value);
            $dateformat = explode(":", $this->getRData('datef'))  ;
            $this->processed .= date($dateformat[0], $field_value);
        }
    }
}

/**
 * Class strFBFieldTypeLogin RegistryField class
 *
 * In the Form context display a text line feild. It works with the password field.
 * @package PASClass
 */
Class strFBFieldTypeLogin extends strFBFieldTypeChar {
    function default_Form($field_value="") {
        $this->processed .= "<input name=\"accessfield[".$this->getRData('access')."]\" type=\"hidden\" value=\"".$this->getFieldName()."\"/>";
    }
}

/**
 * Class strFBFieldTypePassword RegistryField class
 *
 * In the Form context display 2 text line field in password mode and trigger the EventAction: mydb.checkUsernamePassword to check it the username and password dont already exists and if the 2 passwords are the same.
 * This RegistryField requires a strFBFieldTypeLogin field in the same form.
 * @package PASClass
 */
Class strFBFieldTypePassword  extends RegistryFieldBase {
    function default_Form($field_value="") {
        if (!$this->getRData('hidden') && !$this->getRData('readonly')) {
            if (!$this->getRdata('execute')) {
               $field_value = $this->no_PhpCode($field_value);
            }
            $fval = "<input name=\"accessfield[".$this->getRData('access')."]\" type=\"hidden\" value=\"".$this->getFieldName()."\"/>";
            $fval .= "<input type=\"hidden\" name=\"mydb_events[20]\" value=\"mydb.checkUsernamePassword\">" ;
            $fval .= "<input type=\"password\" name=\"fields[".$this->getFieldName()."]\" value=\"".$field_value."\"/>" ;
            $fval .=  "\n<br/><input type=\"password\" name=\"fieldrepeatpass[".$this->getFieldName()."]\" value=\"".$field_value."\"/>"  ;
            $this->processed .= $fval;
        }
    }
    function default_Disp($field_value="") {
    if (!$this->getRData('hidden')) {
            if (!$this->getRdata('execute')) {
                $field_value = $this->no_PhpCode($field_value);
            }
            $this->processed .= $field_value;
        }
    }

}

/**
 * Class strFBFieldTypeListBoxFile RegistryField class
 *
 * In the Form context display a drop down (SELECT) with a list of files as content.
 * @package PASClass
 */
Class strFBFieldTypeListBoxFile extends RegistryFieldBase {
    function default_Form($field_value="") {
        if (!$this->getRData('hidden') && !$this->getRData('readonly')) {
            $dbc = $this->getDbCon();
            list($directory,  $extention, $defaultvalue) = explode (":", $this->getRData('listfile')) ;
            if (strlen($field_value) > 0) {  $defaultvalue = $field_value;  }
            $this->setLog("\n Default value: ".$defaultvalue." field value :".$field_value);
            $fval = "<select class=\"adformfield\" name=\"fields[".$this->getFieldName()."]\">\n" ;
            $fval .= "<option value=\"\"></option>";
            $dirqueries = dir($dbc->getProjectDirectory().$directory);
            $this->setLogRun(false);
            $this->setLog("\n list box dir ".$dbc->getProjectDirectory().$directory);
            if (strlen($extention) > 0) {
                while ($entry = $dirqueries->read()) {
                    // echo $entry;
                    if (strlen($entry) > 2 && eregi($extention."$", $entry) && !eregi("\.sys.\php$", $entry)) {
                        $dirname = str_replace($extention, "", $entry) ;
                        $a_listfile[$entry] = $dirname ;
                    }
                }
            } else {
                while ($entry = $dirqueries->read()) {
                    if (strlen($entry) > 2) {
                        $a_listfile[$entry] = $entry ;
                    }
                }
            }
            if (is_array($a_listfile)) {
                ksort($a_listfile) ;

                while (list($entry, $listcontent) = each($a_listfile)) {
                    $tmp_selected = "" ;
                    if (trim($listcontent) == trim($defaultvalue)) { $tmp_selected = " selected" ; }
                    $fval .= "<option value=\"".htmlentities($listcontent)."\"".$tmp_selected.">" ;
                    $fval .= $listcontent ;
                    $fval .= "</option>\n" ;
                }
            }
            $fval .= "</select>";
            $this->processed .= $this->no_PhpCode($fval);
       }
    }
    function default_Disp($field_value="") {
    if (!$this->getRData('hidden')) {
        list ($directory, $extension, $defaultvalue) = explode(":", $this->getRData('listfile')) ;
            if (strlen($extension) > 0) {
                $this->processed .= $this->no_PhpCode($field_value).$extension;
            } else {
                $this->processed .= $this->no_PhpCode($field_value);
            }
        }
    }
}

/**
 * Class strFBFieldTypeDateSQL RegistryField class
 *
 * In the Form context display the date in 3 line field and trigger the EventAction: mydb.formatDateSQLField to reformat the 3 fields in a standard SQL dateformat.
 * In the Disp context display the date in the format template provided in the rdata datef
 * @package PASClass
 */
Class strFBFieldTypeDateSQL extends RegistryFieldBase {
   function default_Form($field_value="") {
     if (!$this->getRData('hidden') && !$this->getRData('readonly')) {
        $fieldvalue = $field_value;
        $fname = $this->getFieldName();
        list($dateFormat, $today, $hidden, $popup) = explode(":", $this->getRData('datesql')) ;
        if ($today == "today" && strlen($fieldvalue) < 10) {
        $fieldvalue = date("Y-m-d", time()) ;
        }
        if ($hidden) {
        $datefieldtype = "hidden" ;
        } else {
        $datefieldtype = "text" ;
        }
        list ($year, $month, $day) = explode("-", $fieldvalue) ;

        $fval .= "<div class=\"adformfield\"> <input type=\"hidden\" name=\"datesqlfieldname[]\" value=\"".$fname."\"/>";
        $fval .= "<input type=\"hidden\" name=\"mydb_events[4]\" value=\"mydb.fieldsToArray\"/>" ;
        $fval .= "<input type=\"hidden\" name=\"fields[$fname]\" value=\"\"/>" ;
        $fday = " <input type=\"$datefieldtype\" name=\"datefieldday_$fname\" value=\"".$day."\"  size=\"4\"  maxlength=\"2\"/>" ;
        $fmonth = " <input type=\"$datefieldtype\" name=\"datefieldmonth_$fname\" value=\"".$month."\"  size=\"4\"  maxlength=\"2\"/>" ;
        $fyear = " <input type=\"$datefieldtype\" name=\"datefieldyear_$fname\" value=\"".$year."\"  size=\"4\"  maxlength=\"4\"/>" ;
        $datefields = str_replace("d", "phlppesjour", $dateFormat) ;
        $datefields = str_replace("m", "phlppesos", $datefields) ;
        $datefields = str_replace("Y", "phlppesanne", $datefields) ;
        $datefields = str_replace("phlppesjour", $fday, $datefields) ;
        $datefields = str_replace("phlppesos", $fmonth, $datefields) ;
        $datefields = str_replace("phlppesanne", $fyear, $datefields) ;
        $fval .= "<!-- ".$popup." - ".$this->dbc->getProjectDirectory()."images/popup_icon_calendar.gif --->";
        $popuplink = "";
        if ($hidden) {
        $datefields = str_replace("/", "", $datefields) ;  $datefields = str_replace("-", "", $datefields) ;
        } elseif (($popup == "1") && (file_exists($this->dbc->getProjectDirectory()."images/popup_icon_calendar.gif"))) {
        if ($this->datejsinclude) {
        $js = "
        <script language=\"javascript\">
            function open_popup_calendar(url, form, field, field2, field3) {
                if (form=='') form = 'forms[0]';
                var old_value1 = eval('document.'+form+'.'+field+'.value');    old_value1 = escape(old_value1);
                var old_value2 = eval('document.'+form+'.'+field2+'.value');old_value2 = escape(old_value2);
                var old_value3 = eval('document.'+form+'.'+field3+'.value');old_value3 = escape(old_value3);
                new_window = open(url+'?form='+form+'&field='+field+'&field2='+field2+'&field3='+field3+'&old_value1='+old_value1+'&old_value2='+old_value2+'&old_value3='+old_value3,'Calendar','left=30,top=30,resizable=yes,width=250,height=200');
                return false;
            }
            </script>
            ";
            echo $js;
            $this->datejsinclude = false;
        }
        $popuplink = "<a href=\"#\" onClick=\"open_popup_calendar('popup_calendar.php','".$this->getFormName()."','datefieldyear_".$fname."','datefieldmonth_".$fname."','datefieldday_".$fname."');\"><img SRC=\"images/popup_icon_calendar.gif\" border=\"0\"></a>";
        }
        $fval .= $datefields ;
        $fval .= "<input type=\"hidden\" name=\"mydb_events[30]\" value=\"mydb.formatDateSQLField\"/>" ;
        $fval .= $popuplink;
        $fval .= "</div>";
        $this->processed .= $fval;

      }
    }



    function default_Disp($field_value) {

        if (!$this->getRData('hidden') && strlen($this->getRData('datesql')) > 2) {
            $dateformat = explode(":", $this->getRData("datesql"))  ;
            list($year, $month, $day) = explode("-", $field_value) ;
            $fval = str_replace("d", $day, $dateformat[0]) ;
            $fval = str_replace("m", $month, $fval) ;
            $fval = str_replace("Y", $year, $fval) ;
        } else {
            $fval = "" ;
        }
        $this->processed .= $fval;
    }

}
 /**
  * Extra class du to an historical typo
  **/
Class strfFBFieldTypeDateSQL extends strFBFieldTypeDateSQL {
}
 /**
 * Class strFBFieldTypeTimeSQL RegistryField class
 *
 * In the Form context display a text line field and trigger the EventAction: mydb.formatTimeField reformat and validate the content.
 * @package PASClass
 */
Class strFBFieldTypeTimeSQL extends RegistryFieldBase {
    function default_Form($field_value="") {
      if (!$this->getRData('hidden') && !$this->getRData('readonly')) {
        list($now, $hidden) = explode(":", $this->getRData('timef')) ;
        list($hour, $min, $sec) = explode(":", $field_value) ;

        if ($now == "now" && (($hour == "00" && $min == "00" && $sec == "00") || (strlen($field_value) < 3))) {
            $field_value = date("H:i:s") ;
        }

        if ($hidden) {
            $fval .="<input type=\"hidden\" name=\"fields[".$this->getFieldName()."]\" value=\"$field_value\" size=\"8\"/>";
        } else {
            $fval .="<input type=\"text\" name=\"fields[".$this->getFieldName()."]\" value=\"$field_value\" size=\"8\"/>";
        }

        $fval .= "<input type=\"hidden\" name=\"timefieldname[]\" value=\"".$this->getFieldName()."\"/>";
        $fval .= "<input type=\"hidden\" name=\"mydb_events[35]\" value=\"mydb.formatTimeField\"/>" ;
        $this->processed .= $fval;
     }
   }

   function default_Disp($field_value="") {
    if (!$this->getRData('hidden')) {
        $this->processed .= $field_value;
        }
   }
}


Class strFBFieldTypeEnum extends RegistryFieldBase {
    function default_Form($field_value="") {
        if (!$this->getRData("hidden")) {
            global $conx;
            $query = new sqlQuery($conx);
            $tableName  = "auditlog";
            $columnName = "application";
            $sql = "SHOW COLUMNS FROM $tableName LIKE '$columnName'";

//            $query = new sqlQuery($this->getDbConn());
            $query->query($sql);
            $row = $query->fetchArray();
            $enum = explode("','",
                            preg_replace("/(enum|set)\('(.+?)'\)/",
                                         "\\2", $row["Type"]));
            $fval = "<select name=\"fields[".$this->getFieldName()."]\">";
            for ($i=0; $i<sizeof($applications); $i++) {
                $fval .= "<option value=\"".$applications[$i]."\">".$applications[$i]."</option>";
            }
            $fval .= "</select>";

            $this->processed .= $fval;
        }
    }

    function default_Disp($field_value="") {
        if (!$this->getRData("hidden")) {
            $this->processed .= $field_value;
        }
    }
}


 /**
  *  Registry Object Apply load and apply the registry rules.
  *
  *  The Registry is load from a .reg.xml file in registry/ directory.
  *  for each field element in the .reg.xml file a RegistryField object
  *  is created based on its fieldtype (rdata).
  *  It can then be apply for Form context or Display context.
  *  Each RegistryField object contains a default_form and default_disp
  *  method that generate HTML code for both context.
  *  From the .reg.xml files all the rdata values are accessible
  *  with the getRdata().
  *  And they can also be methods of a RegistryField object.
  *  This is to simplify the extention of the RegistryField object..
  *  The Registry constructor requires a connexion and a prefix of a .reg.xml file.
  *  To apply the registy to a field call the apply method with context, field name and field value.
  *
  *  Alot of the properties currently there are deprecate and should not be use.
  *
  * @package PASClass
  * @author Philippe Lewicki  <phil@sqlfusion.com>
  * @version 3.0.0
  * @access public
  */



Class Registry  extends BaseObject {
    /**  Name of the table to store the registry
     * @var String $tbl_registry
     */
    var $tbl_registry = "registry" ;
    /**  Name of the table on which the registry need to be apply
     * @var String $table
     */
    var $table ;

    /** fields array containing all the fields objects from the
     *  registry
     * @var Array $fields contains fields object
     */
    var $fields = Array() ;

    /**  unused for now next version
     * @var String $caracteristic
     */
    var $caracteristic;
    /**  unused in version 2 will be deleted
     * @var String $caracteristic
     * @deprecated compatibility wiht pre MyDB1
     */
    var $field ;
    /**  Reg Info for Options type, typicaly list box, list or choices.
     * @var Array $optiontype index on field of the table
     */
    var $optiontype ;
    /**  Reg Info for list, typicaly list box inside.
     * @var Array $optiontype index on field of the table
     * @deprecated compatibility with pre MyDB2.2
     */
    var $list;
    /**  Reg Info for list of files. $directory, $extention, $defaultvalue
     * @var Array $listfile index on field of the table
     * @deprecated compatibility with pre MyDB2.2
     */
    var $listfile ;
    /**  unused for now next version
     * @var String $language
     * @deprecated compatibility wiht pre MyDB1
     */
    var $language ;
    /**  unused for now next version
     * @var String $currency
     * @deprecated compatibility wiht pre MyDB1
     */
    var $currency ;
    /**  Reg Info for the required field, used in form context.
     * @var Array $required index on field of the table
     * @deprecated compatibility with pre MyDB2.2
     */
    var $required ;
    /**  Reg Info for the default value in a field, used in form context.
     * @var Array $default index on field of the table
     * @deprecated compatibility with pre MyDB2.2
     */
    var $default ;
    /**  Reg Info to tel if its an email field, used in form and display context.
     * @var Array $email index on field of the table
     * @deprecated compatibility with pre MyDB2.2
     */
    var $email ;
    /**  Reg Info to tel if its an hidden field, used in form and display context.
     * @var Array $hidden index on field of the table
     * @deprecated compatibility with pre MyDB2.2
     */
    var $hidden;
    /**  Reg Info to tel if its a readonly field, used in form and display context.
     * @var Array $readonly index on field of the table
     * @deprecated compatibility with pre MyDB2.2
     */
    var $readonly ;
    /**  Reg Info for the label display on the side of field, used in form context.
     * @var Array $label index on field of the table
     * @deprecated compatibility with pre MyDB2.2
     */
    var $label ;
    /**  Reg Info to tel if a field contains a file. used in form and display context.
     * @var Array $picture index on field of the table
     * @deprecated compatibility with pre MyDB2.2
     */
    var $picture ;
    /**  Reg Info to tel if a file field should display a picture. used in display context.
    * @var Array $picture index on field of the table
    * @deprecated compatibility with pre MyDB2.2
    */
    var $showpicture ;
    /**  unused for now next version
    * @var String $categorie
    * @deprecated compatibility with pre MyDB2.2
    */
    var $categorie ;
    /**  unused in version 2 will be deleted
    * @var String $caracteristic
    * @deprecated compatibility wiht pre MyDB1
    */
    var $hidetable;
    /**  Reg Info to tel if its a unix time stamp and contains the date format. used in display and form context.
    * @var Array $datef index on field of the table
    * @deprecated compatibility with pre MyDB2.2
    */
    var $datef ;
    /**  Reg Info to tel if its a SQL format date field and contains the date format. used in display and form context.
    * @var Array $datesql
    * @deprecated compatibility with pre MyDB2.2
    */
    var $datesql ;
    /**  Reg Info to tel if its a SQL time field and contains the date format. used in display and form context.
    * @var Array $timef
    * @deprecated compatibility with pre MyDB2.2
    */
    var $timef ;

    /**  Reg Info to tel if the field is a login or a password type contain type password or login. used in display and form context.
    * @var Array $access index on field of the table
    * @deprecated compatibility with pre MyDB2.2
    */
    var $access ;
    /**  Reg Info to tel if the field is a multiline text field. used in form context.
    * @var Array $textarea index on field of the table
    * @deprecated compatibility with pre MyDB2.2
    */
    var $textarea ;
    /**  Reg Info to tel if the field is a one line text field. used in form context.
    * @var Array $textarea index on field of the table
    * @deprecated compatibility with pre MyDB2.2
    */
    var $textline ;
    /**  Reg Info to tel if the field contains HTML tags and convert them to HMTL entities. used in display context.
    * @var Array $html index on field of the table
    * @deprecated compatibility with pre MyDB2.2
    */
    var $html ;
    /**  Reg Info to tel the type of the field. used in WebIDE.
    * @var Array $html index on field of the table
    */
    var $fieldtype ;
    /**  Reg Info to tel if the field is a checkbox used in form and display context.
    * @var Array $checkbox index on field of the table
    * @deprecated compatibility with pre MyDB2.2
    */
    var $checkbox ;
    /**  Reg Info to tel if the field is a radio button used in form and display context. It will replace the optiontype.
    * @var Array $radiobutton index on field of the table
    * @deprecated compatibility with pre MyDB2.2
    */
    var $radiobutton ;

    /**  Reg Info to tel if the field should display a link to open the Text area as an HTML editor.
    * @var array $htmleditor index on the field of the table and contains 'Yes' if the textarea should display in HTML editor.
    * @deprecated compatibility with pre MyDB2.2
    */
    var $htmleditor ;
    var $htmleditorincludes = true ;
    var $fieldnumber = 0;
    var $totalnumfields = 0;
    var $formname ;
    /** Flag to include the javascript function only once for the date fields
    * @var boolean true or false
    */
    var $datejsinclude = true;

    /**  The database connexion where the regtable and table to apply the registry are.
    * @var sqlConnect $dbc database connexion
    */

    var $dbc ;

    /**
     * Constructor
     * Constructor, create a new instance of a registry for a table. Load all the registry info in the Registry object.
     * To make it available to apply the registry in the different contexts.
     * @param object sqlConnect $dbc  Database connexion
     * @param String $table name of the table where to apply the registry.
     * @access public
     *
     * Constructor now is going to load the reqistry XML or table
     * for each field get its type (if not defined use default char)
     * create a new instance of the associated field type object and store it into an array
     * indexed by the field name.
     * Built a [rtype]=rdata array and pass it to the constructor
     *
     */
    function Registry($dbc, $table="") {
      $this->table = $table ;
      $this->dbc = $dbc ;
      $this->setLogRun(false);
      if (is_object($this->dbc) && $this->dbc->getUseDatabase()) {
        if (strlen($table)>0) {
          if (is_array($table)) {
            $numtables = count($table) ;
            for ($i=0; $i<$numtables; $i++) {
              $qregistry = new sqlQuery($dbc) ;
              $rregistry = $qregistry->query("select rfield, rdata, rtype from $this->tbl_registry where rtable='$this->table[$i]' ") ;
              if ($rregistry >0) {
                while (list($rfield, $rdata, $rtype) = $qregistry->fetchArray()) {
                  $this->totalnumfields++ ;
                  $a_rtype = Array();
                  $fieldname = $this->table[$i].$rfield ;
                  if ($this->{$rtype}[$fieldname] == "") {
                    $this->{$rtype}[$fieldname]=$rdata ;
                    $a_rtype[$fieldname]=$rdata ;
                  }

                }
              }
              $qregistry->free() ;
            }
          } else {
            $qregistry = new sqlQuery($dbc) ;
            $rregistry = $qregistry->query("select rfield, rdata, rtype from $this->tbl_registry where rtable='$this->table' ") ;
            if ($rregistry >0) {
              while (list($rfield, $rdata, $rtype) = $qregistry->fetchArray()) {
                $this->totalnumfields++ ;
                if ($this->{$rtype}[$rfield] == "") {
                  $this->{$rtype}[$rfield]=$rdata ;
                }
              }
            }
            $qregistry->free() ;
          }
        }
      } else {
        if (strlen($table) > 0) {
            include_once($this->dbc->getBaseDirectory()."class/XMLBaseLoad.class.php") ;
            include_once($this->dbc->getBaseDirectory()."class/XMLRegistryLoad.class.php") ;
            $regfilename1 = $this->dbc->getBaseDirectory()."/".$this->tbl_registry."/".$this->table.".reg.xml" ;
            $regfilename2 = $this->dbc->getProjectDirectory()."/".$this->tbl_registry."/".$this->table.".reg.xml" ;
            if (file_exists($regfilename1)) {
                $xmlReg = new XMLRegistryLoad() ;
                $xmlReg->init($regfilename1) ;
            } elseif(file_exists($regfilename2)) {
                $xmlReg = new XMLRegistryLoad() ;
                $xmlReg->init($regfilename2) ;
            }
            if (is_object($xmlReg)) {
                $xmlReg->parse() ;
                $aReg = $xmlReg->finaldata ;
                $xmlReg->free() ;
                if (is_array($aReg)) {
                    reset($aReg)  ;
                    while (list ($rfield, $aFieldtype) = each($aReg)) {
                        $this->totalnumfields++ ;
                        $this->setLog("\n load reg for ".$rfield);
                        $a_rtype = Array();
                        $fieldtype = "";
                        while (list ($rtype, $rdata) = each($aFieldtype)) {
                            if ($this->{$rtype}[$rfield] == "") {
                                $this->{$rtype}[$rfield] = $rdata ;
                                if ($rtype == "fieldtype") {
                                  $fieldtype = $rdata;
                                  $this->setLog("\n field type : ".$fieldtype);
                                } else {
                                  $a_rtype[$rtype]=$rdata ;
                                  $this->setLog("\n rtype :".$rtype." - data ".$rdata);
                                }
                            }
                        }
                        $this->addField($rfield, $fieldtype, $a_rtype, $dbc);
                        if (is_object($this->fields[$rfield])) {
                            $this->setLog("\n Field loaded (".count($a_rtype)."): ".$rfield);
                        }
                    }
                }
            }
        }
      }
      $this->setLog("\n - Registry for ".$table." loaded"); 
    }

  /**
   * Apply the registry on a Display Context.  For a field and a value.
   * This requires that the call is done from a Report object.
   * Temporary methode will be deprecate when Report objects will use
   * the context and directly the apply() method
   *
   * @param String $fname Name of the field
   * @param String $fval Value of the field.
   * @access public
   * @deprecate Used for backward compatibility with old Reports class
   * @see apply()
   */
  function applyRegistry($fname, $fval) {
   return $this->apply("Disp", $fname, $fval) ;
  }

  /**
   * Apply the registry on a Form Context.  For a field and a value.
   * This requires that the call is done from a Report object.
   *
   * @param String $fname Name of the field
   * @param String $fval Value of the field.
   * @access public
   * @see apply()
   * @deprecate Used for backward compatibility with old ReportForm class.
   */
  function applyRegToForm($fname, $fval) {
   return $this->apply("Form", $fname, $fval) ;
  }

  /**
   * Apply the registry.  For a field and a value.
   *
   * @param String $context string of the context to apply reg
   * @param String $fname Name of the field
   * @param String $fval Value of the field.
   * @access public
   */

  function apply($context, $fname, $fval) {
    if (is_object($this->fields[$fname])) {
        $val = $this->fields[$fname]->process($context, $fval);
    } else {
        $field = new strFBFieldTypeChar($fname);
        $val = $field->process($context, $fval);
    }
    $this->setLogRun(false);
    $this->setLog("\n field name ".$fname." - context ".$context);
    $this->setLog("\n processed value :\n\n".$val);
    return $val;
  }

  /**
   * add a field to the registry object.
   * Add a new field to the current registry object.
   *
   * @param string $field_name  Name of the field need to match with the form.
   * @param string $field_type  Field Type this needs to be the class name of a registry field type
   * @param array $a_rtype  Array with all the rdata for that field type. $a_rtype[$rtype_name] = $rdata_value
   * @param sqlConnect $dbc Open Database connection object.
   * @access public
   */
  
  
  function addField($field_name, $field_type="", $a_rtype=0, $dbc=0) {
    if (!empty($field_type) && class_exists($field_type)) {
        $this->fields[$field_name] = new $field_type($field_name, $a_rtype, $dbc);
    } else {
        $this->fields[$field_name] = new strFBFieldTypeChar($field_name, $a_rtype, $dbc);
        $field_type = "strFBFieldTypeChar";
    }
    $rd_fieldtype = $this->fields[$field_name]->getRData("fieldtype");
    if (empty($rd_fieldtype)) {
        $this->fields[$field_name]->setRData("fieldtype", $field_type);
    }
  }
  
  
  /** regToXML
   * Save the current reg object in an XML file.
   * This method will serialize the current registry object to an XML file.
   * This requires that the call is done from a Report object.
   * If the file is in the main PAS registry directory it will overwrite it
   * if not it will overwrite or create a new one in the project registry folded.
   *
   * @param string $name registry filename.
   * @access public
   */
  function regToXML($name="") {
    if (empty($name)) {
        $name = $this->table;
    }
    foreach (array_keys($this->fields) as $key) {
        $byField[$key] = $this->fields[$key]->getRDatas();
    }
    /* Deprecate can't work in PAS 3.0 was working for MyDB 2.2
    $regVars =  get_object_vars($this)  ;
    while(list ($rtype, $value) = each($regVars)) {
      if (is_array($value)) {
        while(list ($rfield, $rdata) = each($value)) {
         if(strlen($rdata)>0) {
            $byField[$rfield][$rtype] = $rdata ;
          }
        }
      }
    }
    */
    $xmlData = "";
    if (!is_array($byField)) { $byField = Array(); }    
    while(list ($rfield, $value) = each($byField)) {
        $xmlData .= "\n  <rfield name=\"".$rfield."\">" ;
      while(list ($rtype, $rdata) = each($value)) {
         $xmlData .=  "\n    <rdata type=\"".$rtype."\">".$rdata."</rdata>" ;
      }
        $xmlData .= "\n  </rfield>" ;
    }
    $regfilename1 = $this->dbc->getBaseDirectory()."/".$this->tbl_registry."/".$name.".reg.xml" ;
    $regfilename2 = $this->dbc->getProjectDirectory()."/".$this->tbl_registry."/".$name.".reg.xml" ;
    if (file_exists($regfilename1)) {
      $fp = fopen($regfilename1, "w") ;
    } else {
      $fp = fopen($regfilename2, "w") ;
    }
    $header = "<?xml version=\"1.0\"?>\n<registry>" ;
    $footer = "\n</registry>" ;
    $xmlFile = $header.$xmlData.$footer ;
    fwrite($fp, $xmlFile) ;
      fclose($fp) ;
  }

  /**
   * setFormName set a name for the form context
   *
   * In the form context some fields type needs the name of the form to generate some javascripts code.
   * It will also set the form name in all the RegistryFields object of that Registry.
   *
   * @param string $formname Name of the form for the form context
   * @see getFormName()
   */

  function setFormName($formname) {
    $this->formname = $formname;
    //print "Registry->setFormName(): $formname<BR>\n";
    foreach (array_keys($this->fields) as $key) {
        //print "this->fields[$key]->setFormName()<BR>\n";
        $this->fields[$key]->setFormName($formname);
    }
  }

  function getFormName() {
    return $this->formname;
  }

}
?>