<?PHP
include_once("HTMLControl.php");

/**
 * FieldControl - class FieldControl is the base class of field control who binds with a bizfield
 * 
 * @package BizView
 * @author rocky swen 
 * @copyright Copyright (c) 2005
 * @version 1.2
 * @access public 
 */
class FieldControl extends HTMLControl 
{
   public $m_BizFieldName;
   public $m_DisplayName;
   public $m_BizFormName;
   public $m_ValuePicker = null;
   public $m_DrillDownLink = null;
   //public $m_Hidden = "N";
   public $m_Enabled = "Y";
   public $m_Sortable = "N";
   public $m_DataType = null;
   public $m_DataFormat = null;
   public $m_SortFlag = null;
   public $m_Order;

   function __construct(&$xmlArr, $formObj)
   {
      parent::__construct($xmlArr, $formObj);
      $this->m_BizFormName = $formObj->m_Name;
      $this->m_BizFieldName = $xmlArr["ATTRIBUTES"]["FIELDNAME"];
      $this->m_DisplayName = $xmlArr["ATTRIBUTES"]["DISPLAYNAME"];
      $this->m_ValuePicker  = $xmlArr["ATTRIBUTES"]["VALUEPICKER"];
      $this->SetDrillDownLink ($xmlArr["ATTRIBUTES"]["DRILLDOWNLINK"]);
      //$this->m_Hidden = $xmlArr["ATTRIBUTES"]["HIDDEN"];
      $this->m_Enabled = $xmlArr["ATTRIBUTES"]["ENABLED"];
      $this->m_Sortable = $xmlArr["ATTRIBUTES"]["SORTABLE"];
      $this->m_DataType = $xmlArr["ATTRIBUTES"]["DATATYPE"];
      $this->m_Order = $xmlArr["ATTRIBUTES"]["ORDER"];
      $this->m_Mode = MODE_R;

      // if no class name, add default class name. i.e. NewRecord => ObjName.NewRecord
      $this->m_ValuePicker = $this->PrefixPackage($this->m_ValuePicker);
      
      if (!$this->m_BizFieldName)
         $this->m_BizFieldName = $this->m_Name;
   } 

   private function SetDrillDownLink($ddLinkString)
   { 
      // linkTo string with format:otherView,otherForm.ctrl=my_ctrl
      if (strlen($ddLinkString) < 1)
         return;
      $pos = strpos($ddLinkString, "=");
      $this->m_DrillDownLink["my_ctrl"] = substr($ddLinkString, $pos + 1, strlen($ddLinkString) - $pos);
      $other = substr($ddLinkString, 0, $pos);
      $pos = strpos($other, ",");
      $pos1 = strpos($other, ".", $pos + 1);
      $linkView = substr($other, 0, $pos);
      $this->m_DrillDownLink["link_view"] = $this->PrefixPackage($linkView);
      $linkForm = substr($other, $pos + 1, $pos1 - $pos-1);
      $this->m_DrillDownLink["link_form"] = $this->PrefixPackage($linkForm);
      $this->m_DrillDownLink["link_ctrl"] = substr($other, $pos1 + 1, strlen($other) - $pos1); 
   } 
   
   /**
    * FieldControl::SetValue() - set the value of the control
    * 
    * @param mixed $val 
    * @return void 
    */
   public function SetValue($val)
   {
      $this->m_Value = $val;
   } 
   
   /**
    * FieldControl::GetValue() - get the value of the control
    *  
    * @return mixed
    */
   public function GetValue()
   {
      return $this->m_Value;
   } 

   /**
    * FieldControl::SetSortFlag() - set the sort flag of the control
    * 
    * @param integer $flag 1 or 0
    * @return void
    */
   public function SetSortFlag($flag=null) 
   {
     $this->m_SortFlag = $flag;
   }

   protected function SetType($type)
   {
      $this->m_DataType = $type;
      if ($this->m_DataType == "Date")
         $this->m_DataFormat = "yyyy-mm-dd";
      if ($this->m_DataType == "Datetime")
         $this->m_DataFormat = "yyyy-mm-dd HH:MM:SS";
   }
   
   protected function GetFormObj()
   {
      global $g_BizSystem;
	   return $g_BizSystem->GetObjectFactory()->GetObject($this->m_BizFormName);
   }

   /**
    * FieldControl::RenderHeader() -  When render table, it return the table header; when render array, it return the display name
    * 
    * @return string HTML text
    */
   public function RenderHeader()
   {
      if ($this->m_Hidden == "Y")
         return null;
      if ($this->m_DataFormat == "table" && $this->m_Sortable == "Y") {
         //$rule = "[" . $this->m_BizFieldName . "]";
         $rule = $this->m_Name;
         $function = $this->m_BizFormName . ".SortRecord(" . $rule . ")";
         $val = "<a href=javascript:CallFunction('" . $function . "',false)>" . $this->m_DisplayName . "</a>";
         if ($this->m_SortFlag == "ASC")
            $val .= "<img src=\"../images/up_arrow.gif\">";
         else if ($this->m_SortFlag == "DESC")
            $val .= "<img src=\"../images/down_arrow.gif\">";
      } else
         $val = $this->m_DisplayName;
      return $val;
   } 

   /**
    * FieldControl::Render() - Draw the control according to the mode
    * 
    * @returns stirng HTML text
    */
   public function Render()
   {
      $val = $this->m_Value;
      if ($this->m_Image)
         $val = "<img src=\"../images/".$this->m_Image."\" border=0> $val";
      if ($val && $this->m_Function) {
         $val = "<a href=\"javascript:CallFunction('" . $this->m_Function . "')\">$val</a>";
      }
      
      if ($this->m_Mode != MODE_E && $this->m_Mode != MODE_N && $this->m_Mode != MODE_Q)
         $tmpMode = 'READ';
         
      if (!$val && $tmpMode == MODE_R) {
         $val = "&nbsp;";
      }

      if ($tmpMode == MODE_R && $this->m_DrillDownLink) {
         $otherCtrl = $this->GetFormObj()->GetControl($this->m_DrillDownLink["my_ctrl"]);
         $this->m_DrillDownLink["my_ctrl_val"] = $otherCtrl->GetValue();
         $rule = $this->m_DrillDownLink["link_form"] . "." . $this->m_DrillDownLink["link_ctrl"] . "=\'" . $this->m_DrillDownLink["my_ctrl_val"] . "\'";
         $val = "<a href=javascript:DrillDownToView('" . $this->m_DrillDownLink["link_view"] . "','$rule')>" . $val . "</a>";
      } 

      if ($tmpMode != MODE_R)
      {
         $ctrlName = $this->m_Name;
         //$this->m_Name = $this->m_BizFieldName; // todo: should change the name to be the fieldname?
         $val = parent::Render(); 
         
         if ($this->m_ValuePicker != null) {
            $img = "<img src=\"../images/more.gif\" border=0 alt=\"Select... \">";
            $function = $this->m_BizFormName . ".ShowPopup(" . $this->m_ValuePicker . "," . $ctrlName . ")";
            $val .= "<a href=\"javascript:CallFunction('" . $function . "',false,null,true)\">" . $img . "</a>";
         }
         else if ($this->m_Type == "Date" || $this->m_Type == "Datetime") {
            $this->SetType($this->m_Type);
            $img = "<img src=\"../images/calendar.gif\" border=0 alt=\"Select date...\">";
            //$ctrl = $this->m_BizFormName . "." . $this->m_BizFieldName;
            $ctrl = $this->m_Name;
            //$val .= "<a href=\"javascript: void(0);\" onclick=\"g_Calendar.show(event,'" . $ctrl . "',false,'yyyy-mm-dd'); return false;\">" . $img . "</a>";
            $val .= "<a href=\"javascript: void(0);\" onclick=\"popUpCalendar(this, '$ctrl', '$this->m_DataFormat');\" onmousemove='window.status=\"Select a date\"' onmouseout='window.status=\"\"'>" . $img . "</a>";
         }
      }

      return $val;
   }
}
/*
class TreeNodeCtrl extends FieldControl 
{
   public function Render()
   {
      // get the folder image and leave image
      $origImg = $this->m_Image;
      list($img1, $img2) = split(";", $this->m_Image);
      $img1 = trim($img1); $img2 = trim($img2);
      // get expand function
      $origFunc = $this->m_Function;
      list($f1, $f2) = split(";", $this->m_Function);
      $f1 = trim($f1); $f2 = trim($f2);
      
      // if the record has child
      $formObj = $this->GetFormObj();
      $chldFlagCtrl = $formObj->GetControl("TreeNodeChldFlag");
      //$recArr = $formObj->GetDataObj()->GetRecord(0);
      //$childFlag = $recArr[$chldFlagCtrl->m_BizFieldName];
      if ($chldFlagCtrl->GetValue() == $chldFlagCtrl->m_SelectFrom) {
         $this->m_Image = $img1;
         $this->m_Function = $f1;
      }
      else {
         $this->m_Image = $img2;
         $this->m_Function = $f2;
      }

      $rdr = parent::Render();
      // set the original attributes back
      $this->m_Function = $origFunc;
      $this->m_Image = $origImg;
      return $rdr;
   }  
}
*/
?>