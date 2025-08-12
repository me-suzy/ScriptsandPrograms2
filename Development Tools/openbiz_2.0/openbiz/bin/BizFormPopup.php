<?php
/**
 * BizFormPopup class - extension of BizForm to support field selection from a popup form
 * 
 * @package BizView
 */
class BizFormPopup extends BizForm 
{
   //protected $m_ParentCtrlName = null; // popup's parent bizCtrl 
   
	/*public function SetParentFormCtrl($prtForm, $prtCtrl)
	{
	   $this->m_ParentFormName = $prtForm;
	   $this->m_ParentCtrlName = $prtCtrl;
	}*/
	
	/**
    * BizFormPopup::Close() - close the popup window
    * 
    * @return string HTML text
    */
	public function Close()
	{
	   global $g_BizSystem;
	   $sessCtxt = $g_BizSystem->GetSessionContext();
	   $this->ClearSessionVars($sessCtxt);
	   // clear the object sessio vars
	   return $g_BizSystem->GetClientProxy()->ClosePopup();
	}
	
	/**
    * BizFormPopup::JoinToParent() - join a record (popup) to parent form
    * 
    * @return string HTML text
    */
	public function JoinToParent()
   {
      global $g_BizSystem;
      $prtForm = $g_BizSystem->GetObjectFactory()->GetObject($this->m_ParentFormName);
      
      $retRecord = $prtForm->GetDataObj()->JoinRecord($this->GetDataObj());
      
      // update the parent form fields on UI
      foreach ($retRecord as $fld=>$val) {
         $prtForm->m_ActiveRecord[$fld] = $val;
      }
      return $prtForm->ReRender();
   }
   
   /**
    * BizFormPopup::AddToParent() - M-M or M-1/1-1 popup OK button to add a record (popup) to the parent form
    * 
    * @return string HTML text
    */
   // todo: support multiple records
   public function AddToParent()
   {
      global $g_BizSystem;
      
      // todo: if grandparent's mode is new, commit the new record first
      
      $prtForm = $g_BizSystem->GetObjectFactory()->GetObject($this->m_ParentFormName);
      // add record to parent form's dataobj who is M-M or M-1/1-1 to its parent dataobj
      $ok = $prtForm->GetDataObj()->AddRecord($this->m_ActiveRecord, $bPrtObjUpdated);
      if (!$ok) 
         return $prtForm->ProcessDataObjError($ok);

      $this->Close();
      
      $html = "";
      // rerender parent form's driving form (its field is updated in M-1 case)
      if ($bPrtObjUpdated) { 
         $prt_prtForm = $g_BizSystem->GetObjectFactory()->GetObject($prtForm->GetParentForm());
         $html = $prt_prtForm->ReRender();
      }
      // rerender the parent form
      // synch form with data
      $prtForm->UpdateActiveRecord($prtForm->GetDataObj()->GetRecord(0));
	   return $html . $prtForm->ReRender();
   }
}
?>