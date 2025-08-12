<?php
# streber - a php5 based project management system  (c) 2005 Thomas Mann / thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in _docs/license.html

/**
* classes for rendering forms
*
* Usage:<pre>
*
*    $form=new PageForm();
*    $form->add(new Form_checkbox('checkbox_id',"Label",$value));
*    $form->add(new Form_input('input_id','Another label',$other_value));
*
*    ### add all fields of dbItem with view_in_forms==TRUE to form ###
*    foreach($effort->fields as $field) {
*        $form->add($field->getFormElement(&$effort));
*    }
*    echo ($form);
*
*</pre>
*
*
* @includedby  render/render_page.inc
*
* @author     Thomas Mann
* @uses       pageElement
* @usedby     most pages
*/

#====================================================================================
# PageFormElement
#====================================================================================
class PageFormElement extends PageElement{
    public  $name;
    public  $value;
    public  $type;
    public  $title;

    PUBLIC function __construct() {
    }
}

#====================================================================================
# Input
#====================================================================================
class Form_Input extends PageFormElement {

    PUBLIC function __construct($name=false, $title="", $value=false)
    {
        $this->type='input';
        parent::__construct();
        $this->name=    $name;
        $this->title=   $title;
        $this->value=   $value;
    }

    PUBLIC function __toString()
    {
        $buffer= "<p><label>$this->title</label><input class=inp name='$this->name' value='".htmlspecialchars($this->value, ENT_QUOTES)."'></p>";
        return $buffer;
    }
}

#====================================================================================
# Password
#====================================================================================
class Form_Password extends PageFormElement {

    PUBLIC function __construct($name=false, $title="", $value=false)
    {
        $this->type='input';
        parent::__construct();
        $this->name=    $name;
        $this->title=   $title;
        $this->value=   $value;
    }

    PUBLIC function __toString()
    {
        $buffer= "<p><label>$this->title</label><input class=inp type='password' name='$this->name' value='".htmlspecialchars($this->value, ENT_QUOTES)."'></p>";
        return $buffer;
    }
}



#====================================================================================
# HiddenField
#====================================================================================
class Form_HiddenField extends PageFormElement {

    PUBLIC function __construct($name=false, $title="", $value=false)
    {
        $this->type='input';
        parent::__construct();
        $this->name=    $name;
        $this->title=   $title;
        $this->value=   $value;
    }

    PUBLIC function __toString()
    {
        $buffer= "<p><input type=hidden name='$this->name' value='$this->value'></p>";
        return $buffer;
    }
}

#====================================================================================
# DateTime
#====================================================================================
class Form_DateTime extends PageFormElement
{
    PUBLIC function __construct($name=false, $title="", $value=false)
    {
        $this->type='datetime';
        parent::__construct();
        $this->name=    $name;
        $this->title=   $title;
        $this->value=   $value;
    }

    PUBLIC function __toString()
    {
        $time= strtotime($this->value);
        if($time<0 || $time==false) {
            $str_array=mysqlDatetime2utc($this->value);
            $str= $str_array['year']  ."-".
                    $str_array['mon'] .'-'.
                    $str_array['day'] ." ".
                    $str_array['hour'].":".
                    $str_array['min']  .":".
                    $str_array['sec'];
            $time= strtotime($str);

        }

        #$str= $str_array['year']."-".$str_array['mon'].'-'.$str_array['day'];
        #$value_date= date("D, d.m.Y",$str );
        $value_date= date("D, d.m.Y",$time);
        $value_time= date("H:i",$time);

        $label=isset($this->title)
            ? $this->title
            : ucwords(str_replace('_',' ',$this->name));
        $tooltip= isset($this->tooltip)
            ? "title='$this->tooltip'"
            :  ucwords($this->name);

        $field_id= $this->name;

        $buffer= "<p $tooltip class=datetime>"
            ."<label>$label</label>"
            ."<input class=inp_date id='{$field_id}_date' name='{$field_id}_date' value='$value_date'>"
            ."<span class=button_calendar id='trigger_+{$field_id}_date'>...</span>"

            ."<input class=inp_time id='{$field_id}_time' name='{$field_id}_time' value='$value_time'>"
            ."<span class=slider_time id='drag_{$field_id}' >%</span>"
            ."<script>"
            ."DragSlider.init('drag_{$field_id}','{$field_id}_time','time');
              Calendar.setup(
                {
                  inputField  : \"{$field_id}_date\",         // ID of the input field
                  ifFormat    : \"%a, %d.%m.%Y\",    // the date format
                  button      : \"trigger_+{$field_id}_date\"       // ID of the button
                }
              );"
            ."</script>";

        return $buffer;
    }
}

#====================================================================================
# Date
#====================================================================================
class Form_Date extends PageFormElement {

    PUBLIC function __construct($name=false, $title="", $value=false)
    {
        $this->type='date';
        parent::__construct();
        $this->name=    $name;
        $this->title=   $title;
        $this->value=   $value;
    }

    PUBLIC function __toString()
    {
        $str_value=$this->value;
        if($str_value=="0000-00-00") {
            $str_value="-";
        }


        $buffer= "<p class=datetime >"
            ."<label>$this->title</label>"
            ."<input class=inp_date name='$this->name' id='$this->name' value='$str_value'>"
            ."<span class=button_calendar id='trigger_+$this->name'>...</span>"
            ."</p>";

        $buffer.="<script type=\"text/javascript\">
          Calendar.setup(
            {
              inputField  : \"$this->name\",         // ID of the input field
              ifFormat    : \"%a, %d.%m.%Y\",    // the date format
              button      : \"trigger_+$this->name\"       // ID of the button
            }
          );
          DragSlider('drag_{$this->name}','{$this->name}');
        </script>" ;
        return $buffer;
    }
}

#====================================================================================
# Time
#====================================================================================
class Form_Time extends PageFormElement {

    PUBLIC function __construct($name=false, $title="", $value=false)
    {
        $this->type='time';
        parent::__construct();
        $this->name=    $name;
        $this->title=   $title;
        $this->value=   $value;
    }

    PUBLIC function __toString()
    {
        $tooltip= isset($field->tooltip)
            ? "title='$this->tooltip'"
            :  ucwords($this->name);

        $buffer= "<p $tooltip class=datetime>"
            ."<label>$this->title</label>"
            ."<input class=inp_time id='{$this->name}' name='{$this->name}' value='$this->value'>"
            ."<span class=slider_time id='drag_{$this->name}' >%</span>"
            ."<script>"
            ."DragSlider.init('drag_{$this->name}','{$this->name}','time');
            </script>
            ";


        return $buffer;
    }
}

#====================================================================================
# Form_Edit
#====================================================================================
class Form_Edit extends PageFormElement {

    PUBLIC function __construct($name=false, $title="", $value=false)
    {
        $this->type='edit';
        parent::__construct();
        $this->name=    $name;
        $this->title=   $title;
        $this->value=   $value;
    }

    PUBLIC function __toString()
    {
        $buffer= "<p><label>$this->title:</label><textarea rows=5 name='$this->name'>".htmlspecialchars($this->value, ENT_QUOTES)."</textarea></p>";
        return $buffer;
    }
}

#====================================================================================
# Form_Dropdown
#====================================================================================
class Form_Dropdown extends PageFormElement {

    private $options;

    PUBLIC function __construct($name=false, $title="", $options=array(), $value=false)
    {
        $this->type='dropdown';
        parent::__construct();
        $this->name=    $name;
        $this->title=   $title;
        $this->value=   $value;
        $this->options=$options;
        $this->options=$options
            ? $options
            :array();
    }

    PUBLIC function __toString()
    {
        $buffer= "<p><label>$this->title</label>";
        $buffer.="<select size=1 name='$this->name'>$this->value</textarea>";
        foreach($this->options as $key=>$value) {
            $str_selected= $this->value==$value
            ? "selected"
            :"";
            $buffer.="<option value=$value $str_selected>$key</option>";
        }
        $buffer.="</select></p>";
        return $buffer;
    }
}

#====================================================================================
# Form_Checkbox
#====================================================================================
class Form_Checkbox extends PageFormElement {

    private $options;

    PUBLIC function __construct($name=false, $title="", $value=false)
    {
        $this->type='checkbox';
        parent::__construct();
        $this->name=    $name;
        $this->title=   $title;
        $this->value=   $value;
    }

    PUBLIC function __toString()
    {
        $checked=$this->value
            ? 'checked'
            : '';
        $buffer= "<p>";
        $buffer.="<label>&nbsp</label><span class=checker><input type='checkbox' name='$this->name' $checked>$this->title</span>";
        $buffer.="</p>";
        return $buffer;
    }
}

class Form_CustomHTML extends PageFormElement {
    PRIVATE $html='';

    PUBLIC function __construct($html='')
    {
        $this->type='html';
        parent::__construct();
        $this->html=  $html;
    }

    PUBLIC function __toString()
    {
        return $this->html;
    }
}




/**
* PageForm
*
* - holds a list of controls (like input-fields, buttons, etc.)
* - it does not render the actuall form-tag (which is done in render_page.inc)
*   the action of the major-form is always just index.php method POST
* - the target is given by go
*
*/
class PageForm extends PageElement 
{
    public  $form_elements;
    public  $button_cancel= false;    # set to true to render
    public  $button_apply= false;     # set to true to render
    public  $button_submit= "Submit";

    public  $form_options=array();  # currently a list of html-snips

    PUBLIC function __construct()
    {
        $this->form_elements=array();
        parent::__construct();
    }

    PUBLIC function __toString()
    {
        $buffer= "<div class=form>";
        foreach($this->form_elements as $key=>$value) {
            $buffer.=$value->render();
        }

        $buffer.="<div class=formbuttons>";

        ### form options ###
        if($this->form_options) {
            foreach($this->form_options as $fo) {
                $buffer.= $fo;
            }
        }


        ### form - buttons ###
        if($str= $this->button_cancel) {
            $buffer.= "<input class='button cancel' type=button value='Cancel' onclick=\"javascript:document.my_form.form_do_cancel.value='1';document.my_form.submit();\">";     
            $buffer.= "<input type=hidden name=form_do_cancel value=0>";
            
            
        }
        
        if($str= $this->button_apply) {
            $buffer.= "<input class='button apply'  type=button value='Apply' onclick=\"javascript:document.my_form.form_do_apply.value='1';document.my_form.submit();\">";      
            $buffer.= "<input type=hidden name=form_do_apply value=0>";
        }

        if($str= $this->button_submit) {
            $buffer.= "<input class='button submit' type=submit value='$str'>";        #@@@ add correct style h
        }        

        $buffer.="</div>";
        
        $buffer.="</div>";
        return $buffer;
    }

    PUBLIC function add($e) {
        if(!($e instanceof PageFormElement)) {
            throw new RenderException("PageForm::add requires a FormElement");
        }
        $this->form_elements[$e->name]=$e;
    }
}

?>