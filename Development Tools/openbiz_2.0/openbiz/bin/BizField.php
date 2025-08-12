<?PHP

/**
 * BizField - class BizField is the class of a logic field which mapps to a table column
 * 
 * @package BizDataObj
 * @author rocky swen
 * @copyright Copyright (c) 2005
 * @version 1.2
 * @access public
 **/
class BizField extends MetaObject 
{
   public $m_BizObjName;
   public $m_Join = null;
   public $m_Column = null;
   public $m_ValueExpression = null;
   //public $m_SqlText = null;
   public $m_Index;

   public $m_Value = null;
   public $m_Type = null;
   public $m_Format = null;
   public $m_Required = null;
   public $m_Validator = null;
   public $m_SqlExpression = null;

   function __construct(&$xmlArr, $bizObj)
   {
      $this->m_Name = $xmlArr["ATTRIBUTES"]["NAME"];
      $this->m_BizObjName = $bizObj->m_Name;
      $this->m_Package = $bizObj->m_Package;
      $this->m_Join = $xmlArr["ATTRIBUTES"]["JOIN"];
      $this->m_Column = $xmlArr["ATTRIBUTES"]["COLUMN"];
      $this->m_ValueExpression = $xmlArr["ATTRIBUTES"]["VALUE"];
      $this->m_Type = $xmlArr["ATTRIBUTES"]["TYPE"];
      $this->m_Format = $xmlArr["ATTRIBUTES"]["FORMAT"];
      $this->m_Required = $xmlArr["ATTRIBUTES"]["REQUIRED"];
      $this->m_Validator = $xmlArr["ATTRIBUTES"]["VALIDATOR"];
      $this->m_SqlExpression = $xmlArr["ATTRIBUTES"]["SQLEXPR"];
      
      $this->m_BizObjName = $this->PrefixPackage($this->m_BizObjName);
   }
   
   /**
    * BizField::GetValue() - get the value of the field. 
    * 
    * @return mixed
    */
   public function GetValue()
   {
      $value = $this->m_Value;
      
      if ($this->m_ValueExpression) {
         $value = $this->GetDataObj()->EvaluateExpression($this->m_ValueExpression);
      }
      if ($this->m_Format) {
         global $g_BizSystem;
         $value = $g_BizSystem->GetTypeManager()->ValueToFormattedString($this->m_Type, $this->m_Format, $value);
      }
      return $value;
   }
   
   /**
    * BizField::SetValue() - set the value of the field. 
    * 
    * @param mixed $val
    * @return void
    */
   public function SetValue($val)
   {
      $this->m_Value = $val;
   }
   
   protected function GetDataObj()
   {
      global $g_BizSystem;
	   return $g_BizSystem->GetObjectFactory()->GetObject($this->m_BizObjName);
   }
   
   /**
    * BizField::CheckRequired() - check if the field is a required field 
    * 
    * @return boolean
    */
   public function CheckRequired()
   {
      $ret = true;
      if ($this->m_Required == "Y") {
         if (!$this->m_Value || $this->m_Value == "") $ret = false;
      }
      return $ret;
   }
   
   /**
    * BizField::Validate() - check if the field has valid value 
    * 
    * @return boolean
    */
   public function Validate()
   {
      $ret = true;
      if ($this->m_Validator)
         $ret = $this->GetDataObj()->EvaluateExpression($this->m_Validator);
      return $ret;
   }
   
}

?>