<?PHP
/**
 * HTMLControl - class HTMLControl is the base class of HTML controls
 * 
 * @package BizView
 * @author rocky swen 
 * @copyright Copyright (c) 2005
 * @version 1.2
 * @access public 
 */
class HTMLControl extends MetaObject implements iUIControl 
{
   public $m_Caption;
   public $m_Image;
   public $m_Style;
   public $m_Type;
   public $m_Function;
   public $m_FunctionType;
   public $m_DisplayMode;
   public $m_State = "ENABLED";
   public $m_SelectFrom = null;
   public $m_Hidden = "N";
   public $m_PostAction;
   public $m_URL;
   
   public $m_Value = "";
   public $m_Width = null;
   public $m_Height = null;
   public $m_Enabled = "Y";
   public $m_HTMLAttr = "";
   public $m_Access = null;
   public $m_AccessFlag = 2;
   //public $m_BizFormName;
   
   protected $m_Mode;
   protected $m_DataFormat = "";

   function __construct(&$xmlArr, $formObj)
   {
      //$this->m_BizFormName = $formObj->m_Name;
      $this->m_Name = $xmlArr["ATTRIBUTES"]["NAME"];
      $this->m_Package = $formObj->m_Package;
      $this->m_Caption = $xmlArr["ATTRIBUTES"]["CAPTION"];
      $this->m_Image = $xmlArr["ATTRIBUTES"]["IMAGE"];
      $this->m_Type = $xmlArr["ATTRIBUTES"]["TYPE"];
      $this->m_Style = $xmlArr["ATTRIBUTES"]["STYLE"];
      $this->m_FunctionType = $xmlArr["ATTRIBUTES"]["FUNCTIONTYPE"];
      $this->m_Function = $xmlArr["ATTRIBUTES"]["FUNCTION"];
      $this->m_Width = $xmlArr["ATTRIBUTES"]["WIDTH"];
      $this->m_Height = $xmlArr["ATTRIBUTES"]["HEIGHT"];
      $this->m_Enabled = $xmlArr["ATTRIBUTES"]["ENABLED"];
      $this->m_HTMLAttr = $xmlArr["ATTRIBUTES"]["HTMLATTR"];
      $this->m_SelectFrom = $xmlArr["ATTRIBUTES"]["SELECTFROM"];
      $this->m_Hidden = $xmlArr["ATTRIBUTES"]["HIDDEN"];
      $this->m_PostAction = $xmlArr["ATTRIBUTES"]["POSTACTION"];
      $this->m_URL = $xmlArr["ATTRIBUTES"]["URL"];
      
      $this->m_Access = $xmlArr["ATTRIBUTES"]["ACCESS"];
      $this->m_DisplayMode = $xmlArr["ATTRIBUTES"]["DISPLAYMODE"];
      if (!$this->m_Type)
         $this->m_Type = "TEXT";
      
      // if no class name, add default class name. i.e. NewRecord => ObjName.NewRecord
      if ($this->m_Function) { 
         $pos_dot = strpos($this->m_Function, ".");
         $pos_lpt = strpos($this->m_Function, "(");
         if (!$pos_dot || $pos_lpt < $pos_dot)
            $this->m_Function = $formObj->m_Name.".".$this->m_Function;
      }
      
      if ($this->m_SelectFrom && !strpos($this->m_SelectFrom, ".") && 
          (strpos($this->m_SelectFrom, "(") || strpos($this->m_SelectFrom, "[")) && $formObj->m_Package)  
         $this->m_SelectFrom = $formObj->m_Package.".".$this->m_SelectFrom;
   } 

   /**
    * HTMLControl::SetState() - Set state (Enabled or Disabled) of this control
    * 
    * @param string $state 
    * @return void 
    */
   public function SetState($state)
   {
      $this->m_State = $state;
   }
   
   /**
    * HTMLControl::SetMode() - Set display mode of this control
    * 
    * @param string $mode 
    * @return void 
    */
   public function SetMode($mode, $dataFormat)
   {
      $this->m_Mode = $mode;
      $this->m_DataFormat = $dataFormat;
   }
   
   /**
    * HTMLControl::SetAccessFlag() - Set access flag of this control
    * 
    * @param int $flag
    * @return void 
    */
   public function SetAccessFlag($flag)
   {
      $this->m_AccessFlag = $flag;
   }
   
   /**
    * HTMLControl::CanAccess() - Check if the control is accessible
    * 
    * @return boolean 
    */
   public function CanAccess()
   {
      if ($this->m_AccessFlag == 2)
         return true; 
      if ($this->m_AccessFlag == 1 && $this->m_Access == "WRITE")
         return false;
      if ($this->m_AccessFlag == 0)
         return false;
      return true; 
   }
   
   /**
    * HTMLControl::CanDisplayed() - Check if the control can be displayed
    * 
    * @return boolean 
    */
   public function CanDisplayed()
   {
      if ($this->CanAccess() == false)
         return false;
      if ($this->m_Hidden == "Y")
         return false;
      if (!$this->m_DisplayMode)
         return true;
      if ($this->m_DisplayMode == $this->m_Mode)
         return true;
      if (strpos($this->m_DisplayMode, $this->m_Mode) === false)
         return false;
      return true;
   }

   /**
    * HTMLControl::Render() - render the control by html
    * 
    * @return string HTML text
    */
   public function Render()
   {
      $cType = strtoupper($this->m_Type);
      if ($cType == "TEXT")   $sHTML = $this->RenderText();
      else if ($cType == "LISTBOX")   $sHTML = $this->RenderListBox();
      else if ($cType == "TEXTAREA")   $sHTML = $this->RenderTextArea();
      else if ($cType == "CHECKBOX")   $sHTML = $this->RenderCheckBox();
      else if ($cType == "RADIO")   $sHTML = $this->RenderRadio();
      else if ($cType == "BUTTON")   $sHTML = $this->RenderButton();
      else if ($cType == "HTMLBUTTON")   $sHTML = $this->RenderHTMLButton();
      else if ($cType == "RESETBUTTON")   $sHTML = $this->RenderResetButton();
      else if ($cType == "SUBMITBUTTON")   $sHTML = $this->RenderSubmitButton();
      else if ($cType == "PASSWORD")   $sHTML = $this->RenderPassword();
      else if ($cType == "HTMLBLOCK")   $sHTML = $this->RenderHTMLBlock();
      else $sHTML = $this->RenderText();
      return $sHTML;
   }

   protected function GetStyle()
   {
      if ($this->m_Width && $this->m_Width>=0)
         $style .= "width:".$this->m_Width.";";
      if ($this->m_Height && $this->m_Height>=0)
         $style .= "height:".$this->m_Height.";";
      if ($this->m_Style)
         $style .= $this->m_Style;
      if (!$style)
         return null;
      return "STYLE='$style'";
   }

   protected function GetFunction()
   {  
      $temp = "";
      if ($this->m_FunctionType == "Page")
         $temp = ",true";
      else if ($this->m_FunctionType == "Popup")
         $temp = ",false,null,true";
      
      $func = null;
      $name = $this->m_Name;
      if ($this->m_Function) {
         $cType = strtoupper($this->m_Type);
         if ($cType=="TEXT" || $cType=="TEXTAREA" || $cType=="LISTBOX")
            $func = "onChange=\"SetOnElement('$name'); CallFunction('" . $this->m_Function . "'$temp);\"";
         else 
            $func = "onClick=\"SetOnElement('$name'); CallFunction('" . $this->m_Function . "'$temp);\"";
      }
      else if ($this->m_URL) {
         $cType = strtoupper($this->m_Type);
         if ($cType=="TEXT" || $cType=="TEXTAREA" || $cType=="LISTBOX")
            $func = "onChange=\"window.location='".$this->m_URL."'\"";
         else 
            $func = "onClick=\"window.location='".$this->m_URL."'\"";
      }
      return $func;
   }

   protected function RenderText() 
   {
      if ($this->m_SelectFrom)
         return $this->RenderListBox();
      $disabledStr = ($this->m_Enabled == "N") ? "DISABLED=\"true\"" : "";
      $style = $this->GetStyle();
      $func = $this->GetFunction();
      $sHTML = "<INPUT NAME=\"" . $this->m_Name . "\" VALUE=\"" . $this->m_Value . "\" $disabledStr $this->m_HTMLAttr $style $func>";
      return $sHTML;
   }

   protected function RenderListBox() 
   {
      $fromlist = array();
      $this->GetFromList($fromlist);
      $disabledStr = ($this->m_Enabled == "N") ? "DISABLED=\"true\"" : "";
      $style = $this->GetStyle();
      $func = $this->GetFunction();
      $sHTML = "<SELECT NAME=\"" . $this->m_Name . "\" $disabledStr $this->m_HTMLAttr $style $func>";
      foreach ($fromlist as $opt) {
         $selectedStr = ($opt['val'] == $this->m_Value) ? "SELECTED" : "";
         $sHTML .= "<OPTION VALUE=\"" . $opt['val'] . "\" $selectedStr>" . $opt['txt'] . "</OPTION>";
      } 
      $sHTML .= "</SELECT>";
      return $sHTML;
   }

   protected function RenderTextArea() 
   {
      $disabledStr = ($this->m_Enabled == "N") ? "DISABLED=\"true\"" : "";
      $style = $this->GetStyle();
      $func = $this->GetFunction();
      $sHTML = "<TEXTAREA NAME=\"" . $this->m_Name . "\" $disabledStr $this->m_HTMLAttr $style $func>".$this->m_Value."</TEXTAREA>";
      return $sHTML;
   }

   protected function RenderCheckBox() 
   {
      $boolValue = $this->m_SelectFrom;
      $disabledStr = ($this->m_Enabled == "N") ? "DISABLED=\"true\"" : "";
      $checkedStr = ($boolValue == $this->m_Value) ? "CHECKED" : "";
      $style = $this->GetStyle();
      $func = $this->GetFunction();
      $sHTML = "<INPUT TYPE=\"CHECKBOX\" NAME=\"" . $this->m_Name . "\" VALUE='$boolValue' $checkedStr $disabledStr $this->m_HTMLAttr $style $func>";
      return $sHTML;
   }

   protected function RenderRadio() 
   {
      $fromlist = array();
      $this->GetFromList($fromlist);
      $disabledStr = ($this->m_Enabled == "N") ? "DISABLED=\"true\"" : "";
      $style = $this->GetStyle();
      $func = $this->GetFunction();
      foreach ($fromlist as $opt) {
         $checkedStr = ($opt['val'] == $this->m_Value) ? "CHECKED" : "";
         $sHTML .= "<INPUT TYPE=RADIO NAME='".$this->m_Name."' VALUE=\"" . $opt['val'] . "\" $checkedStr $disabledStr $this->m_HTMLAttr $func>" . $opt['txt'] . "";
      }
      return "<SPAN $style>".$sHTML."</SPAN>";
   }

   protected function RenderButton() 
   {
      $style = $this->GetStyle();
      $style_mousehand = $this->m_State == "ENABLED" ? "style='cursor:pointer;'" : "";
      $func = $this->m_State == "ENABLED" ? $this->GetFunction() : "";
      
      if ($this->m_Image)
         $out = "<img src=\"../images/" . $this->m_Image . "\" ALIGN=MIDDLE BORDER=0 $style_mousehand title=\"" . $this->m_Caption . "\">";
      else
         $out = "<div class=\"" . $this->m_Type . "\" $style_mousehand>" . $this->m_Caption . "</div>";

      $out = "<span $this->m_HTMLAttr $style $func>".$out."</span>";
      return $out;
   }

   protected function RenderHTMLButton() 
   {
      $disabledStr = ($this->m_Enabled == "N") ? "DISABLED=\"true\"" : "";
      $func = $this->GetFunction();
      $style = $this->GetStyle();
      $sHTML .= "<INPUT TYPE=BUTTON NAME='$this->m_Name' VALUE='$this->m_Caption' $disabledStr $this->m_HTMLAttr $func $style>";
      return $sHTML;
   }

   protected function RenderResetButton()
   {
      $disabledStr = ($this->m_Enabled == "N") ? "DISABLED=\"true\"" : "";
      $style = $this->GetStyle();
      $func = $this->GetFunction();
      $sHTML .= "<INPUT TYPE=RESET NAME='$this->m_Name' VALUE='$this->m_Caption' $disabledStr $this->m_HTMLAttr $style $func>";
      return $sHTML;
   }

   protected function RenderSubmitButton()
   {
      $disabledStr = ($this->m_Enabled == "N") ? "DISABLED=\"true\"" : "";
      $style = $this->GetStyle();
      $func = $this->GetFunction();
      $sHTML .= "<INPUT TYPE=SUBMIT NAME='$this->m_Name' VALUE='$this->m_Caption' $disabledStr $this->m_HTMLAttr $style $func>";
      return $sHTML;
   }

   protected function RenderPassword()
   {
      $disabledStr = ($this->m_Enabled == "N") ? "DISABLED=\"true\"" : "";
      $style = $this->GetStyle();
      $sHTML .= "<INPUT TYPE=PASSWORD NAME='$this->m_Name' VALUE='$this->m_Value' $disabledStr $this->m_HTMLAttr $style>";
      return $sHTML;
   }

   protected function RenderHTMLBlock()
   {
      $style = $this->GetStyle();
      return "<span $style>".$this->m_Caption."</span>";
   }

   protected function GetFromList(&$list)
   {
     $pos0 = strpos($this->m_SelectFrom, "(");
     $pos1 = strpos($this->m_SelectFrom, ")");
     if ($pos0>0 && $pos1>$pos0) {  // select from xml file
        $xmlFile = substr($this->m_SelectFrom, 0, $pos0);
        $tag = substr($this->m_SelectFrom, $pos0 + 1, $pos1 - $pos0-1);
        $tag = strtoupper($tag);
        $xmlFile = BizSystem::GetXmlFileWithPath ($xmlFile);
        if (!$xmlFile) return;
        $xmlArr = &BizSystem::GetXmlArray($xmlFile);
        if ($xmlArr) {
          $i=0;
          foreach($xmlArr["SELECTION"][$tag] as $node) {
            $list[$i]['val'] = $node["ATTRIBUTES"]["VALUE"];
            if ($node["ATTRIBUTES"]["TEXT"])
               $list[$i]['txt'] = $node["ATTRIBUTES"]["TEXT"];
            else
               $list[$i]['txt'] = $list[$i]['val'];
            $i++;
          }
        } 
        return;
     }
      
      $pos0 = strpos($this->m_SelectFrom, "[");
      $pos1 = strpos($this->m_SelectFrom, "]");
      if ($pos0>0 && $pos1>$pos0) {  // select from bizObj
         // support BizObjName[BizFieldName] or BizObjName[BizFieldName4Text:BizFieldName4Value]
         $bizobjName = substr($this->m_SelectFrom, 0, $pos0);
         $pos3 = strpos($this->m_SelectFrom, ":");
         if($pos3>0) {
            $fldName = substr($this->m_SelectFrom, $pos0+1, $pos3-$pos0-1);
            $fldName_v = substr($this->m_SelectFrom, $pos3+1, $pos1-$pos3-1); 
         }
         else {
            $fldName = substr($this->m_SelectFrom, $pos0 + 1, $pos1 - $pos0-1);
            $fldName_v = $fldName;
         }
         global $g_BizSystem;
         $bizobj = $g_BizSystem->GetObjectFactory()->GetObject($bizobjName);
         if (!$bizobj)
            return;
         
         $recList = array();
         $bizobj->FetchRecords("", $recList);
         
         foreach ($recList as $rec)
         {
            $list[$i]['val'] = $rec[$fldName_v];
            $list[$i]['txt'] = $rec[$fldName];
            $i++;
         }
         return;
      }
   }
}

?>