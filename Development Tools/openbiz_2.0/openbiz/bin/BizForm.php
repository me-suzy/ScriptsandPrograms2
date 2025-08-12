<?PHP
/* define modes constants */
define ("MODE_R", "READ");   //READ
define ("MODE_N", "NEW");    //NEW
define ("MODE_E", "EDIT");    //EDIT
define ("MODE_Q", "QUERY");   //QUERY
//define ("MODE_EPT", "EMPTY");   //EMPTY

/**
 * BizForm class - BizForm is the base class that contains UI controls. 
 * BizForm is a html form that is included in a BizView which is a html page.
 * 
 * @package BizView
 * @author rocky swen 
 * @copyright Copyright (c) 2005
 * @access public 
 */
class BizForm extends MetaObject implements iSessionObject 
{
   // metadata vars are public, necessary for metadata inheritance
   public $m_Title;
   public $m_jsClass;
   public $m_DataObjName;
   public $m_InheitFrom;
   public $m_Height = 640;
   public $m_Width = 800;
   public $m_Range = 10;
   public $m_FullPage = "Y";
   public $m_SearchRule = null;
   public $m_DisplayModes = null;
   public $m_RecordRow = null;
   public $m_ToolBar = null;
   public $m_NavBar = null;
   
   protected $m_DataObj;
   public $m_CursorIndex = 0;
   protected $m_CursorIDMap = array();
   protected $m_Mode = MODE_R;
   protected $m_OnSortField;
   protected $m_OnSortFlag;
   public $m_ActiveRecord = array();
   protected $m_RecordChanged = false;
   protected $m_SubForms = null;
   //protected $m_Dependency = null;
   protected $m_FixSearchRule = ""; // rename to FixSearchRule which is the search rule always applying on the search
   protected $m_AccessFlag;
   protected $m_SortedControlKeys = null;
   protected $m_HistoryInfo;
   
   protected $m_ParentFormName;
   protected $m_PrtCommitPending = false;
   
   /**
    * BizForm::__construct(). Initialize BizForm with xml array
    * 
    * @param array $xmlArr
    * @return void 
    */
   function __construct(&$xmlArr)
   {
      global $g_BizSystem;

      $this->ReadMetadata($xmlArr);
      
      $this->InheritParentObj();
   }
   
   protected function ReadMetadata(&$xmlArr)
   {
      $this->m_Name = $xmlArr["BIZFORM"]["ATTRIBUTES"]["NAME"];
      $this->m_InheritFrom = $xmlArr["BIZFORM"]["ATTRIBUTES"]["INHERITFROM"];
      $this->m_Title = $xmlArr["BIZFORM"]["ATTRIBUTES"]["TITLE"];
      $this->m_Description = $xmlArr["BIZFORM"]["ATTRIBUTES"]["DESCRIPTION"];
      $this->m_SearchRule = $xmlArr["BIZFORM"]["ATTRIBUTES"]["SEARCHRULE"];
      $this->m_Package = $xmlArr["BIZFORM"]["ATTRIBUTES"]["PACKAGE"];
      $this->m_Class = $xmlArr["BIZFORM"]["ATTRIBUTES"]["CLASS"];
      $this->m_jsClass = $xmlArr["BIZFORM"]["ATTRIBUTES"]["JSCLASS"];
      $this->m_Height = $xmlArr["BIZFORM"]["ATTRIBUTES"]["HEIGHT"];
      $this->m_Width = $xmlArr["BIZFORM"]["ATTRIBUTES"]["WIDTH"];
      $this->m_Range = $xmlArr["BIZFORM"]["ATTRIBUTES"]["PAGESIZE"];
      $this->m_FullPage = $xmlArr["BIZFORM"]["ATTRIBUTES"]["FULLPAGE"];

      $this->m_Name = $this->PrefixPackage($this->m_Name);
      $this->m_DataObjName = $this->PrefixPackage($xmlArr["BIZFORM"]["ATTRIBUTES"]["BIZDATAOBJ"]);
      
      $this->m_DisplayModes = new MetaIterator($xmlArr["BIZFORM"]["DISPLAYMODES"]["MODE"],"DisplayMode");
      $this->m_RecordRow = new RecordRow($xmlArr["BIZFORM"]["BIZCTRLLIST"]["BIZCTRL"],"FieldControl",$this);
      $this->m_ToolBar = new ToolBar($xmlArr["BIZFORM"]["TOOLBAR"]["CONTROL"],"HTMLControl",$this);
      $this->m_NavBar = new NavBar($xmlArr["BIZFORM"]["NAVBAR"]["CONTROL"],"HTMLControl",$this);
   }
   
   protected function InheritParentObj()
   {
      if (!$this->m_InheritFrom) return;
      global $g_BizSystem;
      $prtObj = $g_BizSystem->GetObjectFactory()->GetObject($this->m_InheritFrom);

      $this->m_Title = $this->m_Title ? $this->m_Title : $prtObj->m_Title;
      $this->m_Description = $this->m_Description ? $this->m_Description : $prtObj->m_Description;
      $this->m_SearchRule = $this->m_SearchRule ? $this->m_SearchRule : $prtObj->m_SearchRule;
      $this->m_jsClass = $this->m_jsClass ? $this->m_jsClass: $prtObj->m_jsClass;
      $this->m_Height = $this->m_Height ? $this->m_Height: $prtObj->m_Height;
      $this->m_Width = $this->m_Width ? $this->m_Width: $prtObj->m_Width;
      $this->m_Range = $this->m_Range ? $this->m_Range: $prtObj->m_Range;
      $this->m_FullPage = $this->m_FullPage ? $this->m_FullPage: $prtObj->m_FullPage;
      $this->m_BizObjName = $this->m_BizObjName ? $this->m_BizObjName: $prtObj->m_BizObjName;
      
      $this->m_DisplayModes->merge($prtObj->m_DisplayModes);
      $this->m_RecordRow->merge($prtObj->m_RecordRow);
      $this->m_ToolBar->merge($prtObj->m_ToolBar);
      $this->m_NavBar->merge($prtObj->m_NavBar);
   }
   
   /**
    * BizForm::GetSessionContext() - Retrieve Session data of this object
    * 
    * @param SessionContext $sessCtxt
    * @return void 
    */
   //todo: pack session vars into a single array
	public function GetSessionVars($sessCtxt)
	{
      $sessCtxt->GetObjVar($this->m_Name, "Mode", $mode);
      $sessCtxt->GetObjVar($this->m_Name, "CursorIndex", $this->m_CursorIndex);
      $sessCtxt->GetObjVar($this->m_Name, "CursorIDMap", $this->m_CursorIDMap);
      $sessCtxt->GetObjVar($this->m_Name, "SubForms", $this->m_SubForms);
      $sessCtxt->GetObjVar($this->m_Name, "ParentFormName", $this->m_ParentFormName);
      $sessCtxt->GetObjVar($this->m_Name, "PrtCommitPending", $this->m_PrtCommitPending);
      //$sessCtxt->GetObjVar($this->m_Name, "Dependency", $this->m_Dependency);
      $sessCtxt->GetObjVar($this->m_Name, "FixSearchRule", $this->m_FixSearchRule);
      $sessCtxt->GetObjVar($this->m_Name, "OnSortField", $this->m_OnSortField);
      $sessCtxt->GetObjVar($this->m_Name, "OnSortFlag", $this->m_OnSortFlag);
      $sessCtxt->GetObjVar($this->m_Name, "AccessFlag", $this->m_AccessFlag);
      $sessCtxt->GetObjVar($this->m_Name, "ActiveRecord", $this->m_ActiveRecord);

      $this->m_HistoryInfo = $sessCtxt->GetViewHistory($this->m_Name);
      
      $this->SetDisplayMode ($mode);
      $this->m_RecordRow->SetRecordArr($this->m_ActiveRecord);
      $this->SetSortFieldFlag($this->m_OnSortField, $this->m_OnSortFlag);
	}

	/**
    * BizForm::SetSessionContext() - Save Session data of this object
    * 
    * @param SessionContext $sessCtxt
    * @return void 
    */
	public function SetSessionVars($sessCtxt)
	{
      $sessCtxt->SetObjVar($this->m_Name, "Mode", $this->m_Mode);
      $sessCtxt->SetObjVar($this->m_Name, "CursorIndex", $this->m_CursorIndex);
      $sessCtxt->SetObjVar($this->m_Name, "CursorIDMap", $this->m_CursorIDMap);
      $sessCtxt->SetObjVar($this->m_Name, "SubForms", $this->m_SubForms);
      $sessCtxt->SetObjVar($this->m_Name, "ParentFormName", $this->m_ParentFormName);
      $sessCtxt->SetObjVar($this->m_Name, "PrtCommitPending", $this->m_PrtCommitPending);
      //$sessCtxt->SetObjVar($this->m_Name, "Dependency", $this->m_Dependency);
      $sessCtxt->SetObjVar($this->m_Name, "FixSearchRule", $this->m_FixSearchRule);
      $sessCtxt->SetObjVar($this->m_Name, "OnSortField", $this->m_OnSortField);
      $sessCtxt->SetObjVar($this->m_Name, "OnSortFlag", $this->m_OnSortFlag);
      $sessCtxt->SetObjVar($this->m_Name, "AccessFlag", $this->m_AccessFlag);
      $sessCtxt->SetObjVar($this->m_Name, "ActiveRecord", $this->m_ActiveRecord);

	   $sessCtxt->SetViewHistory($this->m_Name, $this->GetHistoryInfo());
	}
	
	public function ClearSessionVars($sessCtxt)
	{
	   // clear active record, view history is depend on it.
	   unset($this->m_ActiveRecord);
	   $this->m_ActiveRecord = null;
	   // clear session vars
	}
	
	/**
    * BizForm::GetHistoryInfo() - get history info array
    * 
    * @return array
    */
	public function GetHistoryInfo()
	{
	   if ($this->m_ActiveRecord) { 
	      $histInfo[0] = $this->m_ActiveRecord["Id"];
	      return $histInfo;
	   }
	   return null;
	}
	
	/**
    * BizForm::CleanHistoryInfo() - clear history info so that the data set is fresh
    * 
    * @return array
    */
	public function CleanHistoryInfo()
	{
	   $this->m_HistoryInfo = null;
	}
	
	// get post action url/view/... This method is called in Render() to determine the post action
	protected function GetPostAction()
	{
	   // check if the current rpc call has postaction specified
	   global $g_BizSystem;
      // get the control that issues the call 
	   $ctrlname = $g_BizSystem->GetClientProxy()->GetFormInputs("__this");
	   $ctrlobj = $this->GetControl($ctrlname);
	   if ($ctrlobj->m_PostAction)
	      return $ctrlobj->m_PostAction;
      return null;
	}
	
	/**
    * BizForm::GetDisplayMode() - get display mode object
    * 
    * @return DisplayMode
    */
   final public function GetDisplayMode()
   {
      if (($dispmode = $this->m_DisplayModes->get($this->m_Mode)))
         return $dispmode;
      foreach ($this->m_DisplayModes as $dispmode) {
         return $dispmode;
      }
      $errmsg = BizSystem::GetMessage("ERROR", "BFM_ERROR_INVALID_DISPMODE",array($this->m_Name));
      trigger_error($errmsg, E_USER_ERROR);
   } 
   /**
    * BizForm::SetDisplayMode() - set display mode as given mode
    * 
    * @param string $mode - MODE_R|MODE_N|MODE_E|MODE_Q
    * @return void
    */
   final public function SetDisplayMode($mode)
   {
      if (!$mode) $mode = MODE_R;
      $this->m_Mode = $mode;
      $dispmode = $this->m_DisplayModes->get($this->m_Mode);
      $this->m_RecordRow->SetMode($mode, $dispmode->m_DataFormat);
      $this->m_ToolBar->SetMode($mode, $dispmode->m_DataFormat);
      $this->m_NavBar->SetMode($mode, $dispmode->m_DataFormat);
   }
   /**
    * BizForm::SetAccessFlag() - set access flag of the bizform
    * 
    * @param int $flag - 0|1|2
    * @return void
    */
   final public function SetAccessFlag($flag)
   {
      if (!$flag) $flag = 2;
      $this->m_AccessFlag = $flag;
      $this->m_RecordRow->SetAccessFlag($flag);
      $this->m_ToolBar->SetAccessFlag($flag);
      $this->m_NavBar->SetAccessFlag($flag);
   }
	
	/**
    * BizForm::GetDataObj() - get object instance of BizDataObj defined in its metadata file
    * 
    * @return BizDataObj
    */
	final public function GetDataObj()
	{
	   global $g_BizSystem;
	   if (!$this->m_DataObj) {
	     if ($this->m_DataObjName)
           $this->m_DataObj = $g_BizSystem->GetObjectFactory()->GetObject($this->m_DataObjName);
	   }
      return $this->m_DataObj;
	}
	
	final public function SetDataObj($dataObj)
	{
	   $this->m_DataObj = $dataObj;
	}
	
	/**
    * BizForm::ProcessDataObjError() - handle the error from DataObj method, report the error as an alert window
    * 
    * @param int $errCode
    * @return string
    */
	public function ProcessDataObjError($errCode=0)
	{
	   global $g_BizSystem;
      $errorMsg = $this->GetDataObj()->GetErrorMessage();
      BizSystem::log(LOG_DEBUG, "DATAOBJ", "DataObj error = $errprMsg");
      return $g_BizSystem->GetClientProxy()->ShowErrorMessage($errorMsg);
	}
	
	/**
    * BizForm::SetCursorIndex() - set the current cursor index to the given value, dataobj's cursor is changed accordingly.
    * 
    * @param int $cursorIndex
    * @return void
    */
	final protected function SetCursorIndex($cursorIndex=0)
	{
	   //global $g_BizSystem;
	   //$g_BizSystem->ErrorBacktrace();
	   $this->m_CursorIndex = $cursorIndex;
      $this->GetDataObj()->MoveCursorTo($this->m_CursorIndex, true);
      $this->UpdateActiveRecord($this->GetDataObj()->GetRecord(0));
	}
	
	/**
    * BizForm::UpdateActiveRecord() - update the active record with given record array
    * 
    * @param array $recArr
    * @return void
    */
	final public function UpdateActiveRecord($recArr)
	{
	   $this->m_ActiveRecord = $recArr;
      $this->m_RecordRow->SetRecordArr($this->m_ActiveRecord);  // needed ???
	}
	
	/**
    * BizForm::SetSubForms() - set the sub forms of this form. This form is parent of other forms
    * 
    * @param string $subForms - sub controls string with format: ctrl1;ctrl2...
    * @return void
    */
	final public function SetSubForms($subForms)
   { 
      // sub controls string with format: ctrl1;ctrl2...
      if (!$subForms || strlen($subForms) < 1) {
         $this->m_SubForms = null;
         return;
      } 
      $subFormArr = split(";", $subForms);
      unset($this->m_SubForms);
      foreach ($subFormArr as $subForm) {
         $this->m_SubForms[] = $this->PrefixPackage($subForm);
      }
   } 
   
   public function GetSubForms() { return $this->m_SubForms; }
   
   public function GetParentForm() { return $this->m_ParentFormName; }
   
   public function SetParentForm($prtFormName) { $this->m_ParentFormName = $prtFormName; }
   
   public function GetPrtCommitPending() { return $this->m_PrtCommitPending; }
   
   public function SetPrtCommitPending($flag) { $this->m_PrtCommitPending = $flag; }
   
   protected function CanShowData() { return !$this->GetPrtCommitPending(); } // parent form has new record pending

   /**
    * BizForm::GetControl() - get a control (FieldControl or HTMLControl) object
    * 
    * @param string $ctrlname - name of the control
    * @return HTMLControl or FieldControl
    */
	public function GetControl($ctrlname)
	{
	   if ($this->m_RecordRow->get($ctrlname)) return $this->m_RecordRow->get($ctrlname);
	   if ($this->m_ToolBar->get($ctrlname)) return $this->m_ToolBar->get($ctrlname);
	   if ($this->m_NavBar->get($ctrlname)) return $this->m_NavBar->get($ctrlname);
	}
	
	/**
    * BizForm::SetSearchRule() - set the search rule of the bizform, this search rule will apply on its bizdataobj
    * 
    * @param string $rule - search rule has format "[fieldName1] opr1 Value1 AND/OR [fieldName2] opr2 Value2"
    * @return void
    */
   public function SetSearchRule($rule = null)
	{
      if ($this->m_SearchRule && $rule)
         $this->m_SearchRule = $this->m_SearchRule . " AND " . $rule;
      if (!$this->m_SearchRule && $rule)
         $this->m_SearchRule = $rule;
	}
	
	/**
    * BizForm::SetFixSearchRule() - set the dependent search rule of the bizform, this search rule will apply on its bizdataobj.
    * The dependent search rule (session var) will always be with bizform until it get set to other value
    * 
    * @param string $rule - search rule has format "[fieldName1] opr1 Value1 AND/OR [fieldName2] opr2 Value2"
    * @return void
    */
	public function SetFixSearchRule($rule = null)
	{
      if ($this->m_FixSearchRule && $rule)
         $this->m_FixSearchRule = $this->m_FixSearchRule . " AND " . $rule;
      if (!$this->m_FixSearchRule && $rule)
         $this->m_FixSearchRule = $rule;
	}
	
	/**
    * BizForm::_run_search() - call RunSearch of its dataobj by applying its FixSearchRule and SearchRule
    * Its dataobj current search rule will be replaced by its FixSearchRule and SearchRule.
    * 
    * @return void
    */
	public function _run_search()
   {
      $dataobj = $this->GetDataObj();
      //$this->CountDependency();
      if (strlen($this->m_FixSearchRule) > 0) {
         if (strlen($this->m_SearchRule) > 0)
            $this->m_SearchRule .= " AND " . $this->m_FixSearchRule;
         else
            $this->m_SearchRule = $this->m_FixSearchRule;
      } 
      $dataobj->ClearSearchRule();
      $dataobj->SetSearchRule($this->m_SearchRule);
      $dataobj->SetPageRange($this->m_Range);
      return $dataobj->RunSearch();
   }
   
   /**
    * BizForm::MoveNext() - move to next page, it will be NextPage() in 2.0 
    * 
    * @return string - html content of next page
    */
   public function MoveNext() // will be NextPage in 2.0 
   {
      $this->GetDataObj()->NextPage();
      $this->SetCursorIndex(0);
      return $this->ReRender();
   }
   /**
    * BizForm::MovePrev() - move to previous page, it will be PrevPage() in 2.0 
    * 
    * @return string - html content of previous page
    */
   public function MovePrev() // will be PrevPage in 2.0 
   {
      $this->GetDataObj()->PrevPage();
      $this->SetCursorIndex(0);
      return $this->ReRender();
   } 
   /**
    * BizForm::SelectRecord() - Select the record to selected row (if form show list of records)
    * 
    * @return string - HTML content of this form and its sub forms whose content are changed with their parent form change
    */
   public function SelectRecord()
   {
      global $g_BizSystem;
      $rownum = $g_BizSystem->GetClientProxy()->GetFormInputs("__SelectedRow");
      $cursorIndex = $rownum-1;
      if ($this->m_CursorIndex == $cursorIndex)
         return;
      $this->SetCursorIndex($cursorIndex);
      return $this->ReRender(false);   // not redraw the this form, but draw the subforms
   }
   
   /**
    * BizForm::SearchRecord() - show the query record mode
    * 
    * @return string - HTML text of this form's query mode
    */
   public function SearchRecord()
   {
      $this->UpdateActiveRecord(null);
      $this->SetDisplayMode(MODE_Q);
      return $this->ReRender(true,false);
   } 
   /**
    * BizForm::NewRecord() - show the new record mode
    * 
    * @return string - HTML text of this form's new mode
    */
   public function NewRecord()
   {
      global $g_BizSystem;
      $this->SetDisplayMode(MODE_N);
      $this->UpdateActiveRecord($this->GetDataObj()->NewRecord());
      return $this->ReRender();
   } 
   /**
    * BizForm::EditRecord() - edit the record of current row
    * 
    * @return string - HTML text of this form's edit mode
    */
   public function EditRecord()
   {
      $this->SetDisplayMode(MODE_E);
      return $this->ReRender(true,false);
   } 
   /**
    * BizForm::SaveRecord() - Save current edited record with input
    * 
    * @return string - HTML text of this form's read mode
    */
   public function SaveRecord()
   { 
      global $g_BizSystem;
      foreach ($this->m_RecordRow as $fldCtrl) {
         $value = $g_BizSystem->GetClientProxy()->GetFormInputs($fldCtrl->m_Name);
         $recArr[$fldCtrl->m_BizFieldName] = $value;
      }

      $recArr["Id"] = $this->m_ActiveRecord["Id"];

      if ($this->m_Mode == MODE_N)
         $ok = $this->GetDataObj()->InsertRecord($recArr);
      else if ($this->m_Mode == MODE_E)
         $ok = $this->GetDataObj()->UpdateRecord($recArr);
      if (!$ok) 
         return $this->ProcessDataObjError($ok);

      $this->UpdateActiveRecord($this->GetDataObj()->GetRecord(0));
      $this->SetDisplayMode (MODE_R);
      return $this->ReRender();
   } 
   /**
    * BizForm::DeleteRecord() - Delete the record of current row
    * 
    * @return string - HTML text of this form's current mode
    */
   public function DeleteRecord()
   {
      global $g_BizSystem;
      //$recId = $this->m_ActiveRecord["Id"];
      $ok = $this->GetDataObj()->DeleteRecord($this->m_ActiveRecord);
      if (!$ok) 
         return $this->ProcessDataObjError($ok);

      $this->UpdateActiveRecord($this->GetDataObj()->GetRecord(0));
      return $this->ReRender();
   }
   /**
    * BizForm::RemoveRecord() - Remove the record out of the associate relationship 
    * 
    * @return string - HTML text of this form's current mode
    */
   public function RemoveRecord()
   {
      global $g_BizSystem;
      $ok = $this->GetDataObj()->RemoveRecord($this->m_ActiveRecord,$bPrtObjUpdated);
      if (!$ok) 
         return $this->ProcessDataObjError($ok);

      $html = "";
      // rerender parent form's driving form (its field is updated in M-1 case)
      if ($bPrtObjUpdated) { 
         $prtForm = $g_BizSystem->GetObjectFactory()->GetObject($this->m_ParentFormName);
         $html = $prtForm->ReRender();
      }
      $this->UpdateActiveRecord($this->GetDataObj()->GetRecord(0));
      return $html . $this->ReRender();
   }
   
   /**
    * BizForm::CopyRecord() - Copy current record and paste to a new record
    * Note: copy record will error out if db table uses composite columns as its primary key
    *  
    * @return string - HTML text of this form's current mode
    */
   public function CopyRecord()
   {
      global $g_BizSystem;
      // get new record array
      $recArr = $this->GetDataObj()->NewRecord();
      
      $this->m_ActiveRecord["Id"] = $recArr["Id"]; // replace with new Id field
      $this->m_RecordRow->SetRecordArr($this->m_ActiveRecord);
      $this->GetDataObj()->InsertRecord($this->m_ActiveRecord);
      $this->UpdateActiveRecord($this->GetDataObj()->GetRecord(0));
      return $this->ReRender();
   }
   
   /**
    * BizForm::SortRecord() - sort record on given column
    * 
    * @param strinf $sort_col with format as "fieldControl,1|0" which means sorting on field by ASC|DESC
    * @return string - HTML text of this form's current mode
    */
   public function SortRecord($sort_col)
   {
      $pos = strpos($sort_col, ",");
      if ($pos > 0)
         $reverse_flag = substr($sort_col, $pos + 1);
      $sortflag = ($reverse_flag == 1) ? "DESC" : "ASC";
      $sort_col = substr($sort_col, 0, $pos); 
      
      // change the OnSortField
      $this->m_OnSortField = $sort_col;
      $this->m_OnSortFlag = $sortflag;

      // turn off the OnSort flag of the old onsort field
      $this->SetSortFieldFlag($this->m_OnSortField, null);
      
      // turn on the OnSort flag of the new onsort field
      $this->SetSortFieldFlag($this->m_OnSortField, $sortflag);
      
      // change the sort rule and issue the query
      $this->GetDataObj()->SetSortRule("[" . $this->GetControl($this->m_OnSortField)->m_BizFieldName . "] " . $sortflag);
      $ok = $this->GetDataObj()->RunSearch();
      if (!$ok) 
         return $this->ProcessDataObjError($ok);

      $this->SetCursorIndex(0);
      return $this->ReRender();
   }
   
   protected function SetSortFieldFlag($sortFld, $sortFlag)
   {
      if ($sortFld) {
         $fldCtrl = $this->GetControl($sortFld);
         $fldCtrl->SetSortFlag($sortFlag);
      }
   }
   
   /**
    * BizForm::Cancel() - Cancel current edit or query, then go read mode
    * 
    * @return string - HTML text of this form's current mode
    */
   public function Cancel()
   {
      $this->SetCursorIndex($this->m_CursorIndex);
      $prevMode = $this->m_Mode;
      $this->SetDisplayMode(MODE_R);
      if ($prevMode == MODE_N)   // NEW mode to READ mode, has record change, need to refresh the subforms
         return $this->ReRender(true, true); 
      // EDIT to READ, no record change
      return $this->ReRender(true,false);
   } 
	
   /**
    * BizForm::RunSearch() - Run search on query mode, then go read mode
    * 
    * @return string - HTML text of this form's read mode
    */
   public function RunSearch()
   {
      BizSystem::log(LOG_DEBUG,"FORMOBJ",$this->m_Name."::RunSearch()");
      global $g_BizSystem;
      $this->m_SearchRule = "";
      foreach ($this->m_RecordRow as $fldCtrl) {
         $value = $g_BizSystem->GetClientProxy()->GetFormInputs($fldCtrl->m_Name);
         if ($value) {
            $searchStr = $this->InputValToRule($fldCtrl->m_BizFieldName, $value);
            if ($this->m_SearchRule == "")
               $this->m_SearchRule .= $searchStr;
            else
               $this->m_SearchRule .= " AND " . $searchStr;
         }
      }

      $err = $this->_run_search();
      if (!$err) 
         return $this->ProcessDataObjError($err);

      $this->SetCursorIndex(0);
      $this->SetDisplayMode (MODE_R);
      return $this->ReRender();
   }
   
   /**
    * BizForm::RefreshQuery() - clear the search rule and do the original query when view first loaded
    * 
    * @return string - HTML text of this form's read mode
    */
   public function RefreshQuery()
   {
      if ($this->m_OnSortField) {
         $this->SetSortFieldFlag($this->m_OnSortField, null);
         $this->m_OnSortField = null;
         $this->GetDataObj()->SetSortRule(null);
      }
      $this->m_SearchRule = "";
      $err = $this->_run_search();
      if (!$err) 
         return $this->ProcessDataObjError($ok);
      $this->SetCursorIndex(0);
      $this->SetDisplayMode (MODE_R);
      return $this->ReRender();
   }
   
   /**
    * BizForm::InputValToRule() - convert the user input on a given fieldcontrol in qeury mode to search rule
    * 
    * @param string $field - fieldcontrol name
    * @param string $inputVal - use input text
    * @return string - searchRule
    */
   protected function InputValToRule($field, $inputVal)
   {
      $val = trim($inputVal);
      // check " AND ", " OR "
      if (($pos=strpos(strtoupper($val), " AND "))!==false) {
         $inputArr = spliti(" AND ", $val);
         $retStr = null;
         foreach($inputArr as $v)
            $retStr .= ($retStr) ? " AND ".$this->InputValToRule($field, $v) : $this->InputValToRule($field, $v);
         return $retStr;
      }
      else if (($pos=strpos(strtoupper($val), " OR "))!==false) {
         $inputArr = spliti(" OR ", $val);
         $retStr = null;
         foreach($inputArr as $v)
            $retStr .= ($retStr) ? " OR ".$this->InputValToRule($field, $v) : $this->InputValToRule($field, $v);
         return "(".$retStr.")";
      }
      // check >=, >, <=, <, =
      if (($pos=strpos($val, ">="))!==false) {
         $opr = ">="; $oprlen = 2;
      }
      else if (($pos=strpos($val, ">"))!==false) {
         $opr = ">"; $oprlen = 1;
      }
      else if (($pos=strpos($val, "<="))!==false) {
         $opr = "<="; $oprlen = 2;
      }
      else if (($pos=strpos($val, "<"))!==false) {
         $opr = "<"; $oprlen = 1;
      }
      else if (($pos=strpos($val, "="))!==false) {
         $opr = "="; $oprlen = 1;
      }
      if ($opr) {
         $val = trim(substr($val, $pos+$oprlen));
      }
      
      if (strpos($val, "*") !== false) {
         $opr = "LIKE";
         $val = str_replace("*", "%", $val);
      }
      if (strpos($val, "'") !== false) {
         $val = str_replace("'", "\\'", $val);
      }
      if (!$opr)
         $opr = "=";

      return "[" . $field . "] " . $opr . " '" . $val . "'";
   }
   
   /**
    * BizForm::ShowPopup() - Popup a selection BizForm in a dynamically generated BizView
    * 
    * @param string $formName - the popup bizform
    * @return string - HTML text of popup view
    */
   public function ShowPopup($formName, $ctrlName="")
   {
      // generate an xml attribute array for a dynamic bizview
      $xmlArr["BIZVIEW"]["ATTRIBUTES"]["NAME"] = "__DynPopup";
      $xmlArr["BIZVIEW"]["ATTRIBUTES"]["DESCRIPTION"] = "Select record";
      $xmlArr["BIZVIEW"]["ATTRIBUTES"]["PACKAGE"] = $this->m_Package;
      $xmlArr["BIZVIEW"]["ATTRIBUTES"]["CLASS"] = "BizView";
      $xmlArr["BIZVIEW"]["ATTRIBUTES"]["TEMPLATE"] = "popup.tpl";
      $xmlArr["BIZVIEW"]["CONTROLLIST"]["CONTROL"]["ATTRIBUTES"]["FORM"] = $formName;
      // create a BizViewPopup with the xml array
      global $g_BizSystem;
      //$popupView = new BizView($xmlArr);
      $popupView = $g_BizSystem->GetObjectFactory()->CreateObject("DynPopup",$xmlArr);
      // set the ParentFormName and ParentCtrlName of the popup form
      $popupForm = $g_BizSystem->GetObjectFactory()->GetObject($formName);
      $popupForm->SetParentForm($this->m_Name);
      // set the dimension of the popup
      $w = $popupForm->m_Width;
      $h = $popupForm->m_Height;
      $popupView->SetPopupSize($w, $h);
      // render the popup
      $popupView->Render();
   }
   
   /**
    * BizForm::ShowSelectForm() - Popup a selection BizForm
    * 
    * @param string $ctrlName - the control user clicks to brings up the popup bizform
    * @return string - HTML text of this form's read mode
    */
   public function ShowSelectForm($ctrlName)
   {
      // get the Select BizForm name according to the ctrlName
      $bizctrl = $this->GetControl($ctrlName);
      $this->ShowPopup($bizctrl->m_SelectBFName);
   }
   
   /**
    * BizForm::CallService() - invoke service method, this bizform name is passed to the method
    * 
    * @param string $class
    * @param string $method
    * @return mixed - return value of the service method
    */
   public function CallService($class, $method)
   {
      global $g_BizSystem;
      //$ret = $g_BizSystem->CallService($class, $method, $this->m_Name);
      $svcobj = $g_BizSystem->GetService($class);
      $svcobj->$method($this->m_Name);
   }
   
   /**
    * BizForm::HandlePostAction() - post action is the redirected page/view after an action is finished successfully
    * 
    * @param string $postAction postaction can be view:xxx, url:xxx, mode:xxx
    * @return string - redirect page or view
    */
   public function HandlePostAction($postAction)
   {
      global $g_BizSystem;
      $pos = strpos($postAction, ":");
      $tag = substr($postAction, 0, $pos);
      $content = substr($postAction, $pos+1);
      if ($tag == "view") 
         return $g_BizSystem->GetClientProxy()->ReDirectView($content);
      else if ($tag == "url")
         return $g_BizSystem->GetClientProxy()->ReDirectPage($content);
      else if ($tag == "mode") 
         {}
      else 
         return;
   }
   
   /**
    * BizForm::Render() - render this form (return html content), called by bizview's render method (called when form is loaded). 
    * Query is issued before returning the html content.
    * 
    * @return string - HTML text of this form's read mode
    */
	public function Render()
	{
	   // when in NEW mode or when parent form in NEW mode, do nothing
	   global $g_BizSystem;
	   if ($this->m_ParentFormName) {
         $prtForm = $g_BizSystem->GetObjectFactory()->GetObject($this->m_ParentFormName);
         $prtMode = $prtForm->GetDisplayMode()->GetMode();
	   }
	   if ($this->m_Mode != MODE_N && $prtMode != MODE_N)
	   {
   	   if ($this->GetDataObj()) {
            $err = $this->_run_search();
            if (!$err) 
               return $this->ProcessDataObjError($ok);
            $cursorIndex = 0;
            if ($this->m_HistoryInfo) {
               $recId = $this->m_HistoryInfo[0];
               //echo "hist recid=".$recId;
               $cursorIndex = $this->GetDataObj()->MoveToRecord($recId);
            }
            $this->SetCursorIndex($cursorIndex);
   	   }
	   }
	   if ($this->m_Mode == MODE_N)
         $this->UpdateActiveRecord($this->GetDataObj()->NewRecord());
	   
	   global $g_BizSystem;
	   // prepare the subforms' dataobjs, since the subform relates to parent form by dataobj association
	   if ($this->m_SubForms) {
   	   foreach($this->m_SubForms as $subForm) {
            $formObj = $g_BizSystem->GetObjectFactory()->GetObject($subForm);
            $dataObj = $this->GetDataObj()->GetRefObject($formObj->m_DataObjName);
            if ($dataObj)
               $formObj->SetDataObj($dataObj);
         }
	   }
	   
      return $this->RenderHTML();
	}
	
	/**
    * BizForm::ReRender() - rerender this form (form is rendered already) .
    * 
    * @param boolean $redrawForm - whether render this form again or not
    * @param boolean $hasRecordChange - if record change, need to render subforms
    * @return string - HTML text of this form's read mode
    */
	public function ReRender($redrawForm=true, $hasRecordChange=true)
	{
	   // consider the postAction
	   $postAction = $this->GetPostAction();
      if ($postAction) {
         return $this->HandlePostAction($postAction);
      }
	   
      global $g_BizSystem;
      if ($redrawForm)
	      $rtVal = $g_BizSystem->GetClientProxy()->ReDrawForm($this->m_Name, $this->RenderHTML());
	   if ($hasRecordChange)
	      $rtVal .= $this->ReRenderSubForms();
	   return $rtVal;
	}
	
	/**
    * BizForm::ReRenderSubForms() - rerender sub forms who has dependecy on this form. 
    * This method is called when parent form's change affect the sub forms
    * 
    * @return string - HTML text of this form's read mode
    */
	protected function ReRenderSubForms()
   {
      if (!$this->m_SubForms)
         return;
      
      global $g_BizSystem;
      $mode = $this->GetDisplayMode()->GetMode();
      foreach($this->m_SubForms as $subForm) {
         $formObj = $g_BizSystem->GetObjectFactory()->GetObject($subForm);
         if ($mode == MODE_N) {  // parent form on new mode
            $formObj->SetPrtCommitPending(true); 
         }
         else {
            $formObj->SetPrtCommitPending(false); 
            $dataObj = $this->GetDataObj()->GetRefObject($formObj->m_DataObjName);
            if ($dataObj)
               $formObj->SetDataObj($dataObj);
            $err = $formObj->_run_search();
            if (!$err) 
               return $formObj->ProcessDataObjError($err);
            $formObj->SetCursorIndex(0);
         }
         $rtVal .= $formObj->ReRender();
      } 
      return $rtVal;
   } 
	
   /**
    * BizForm::RenderHTML() - render html content of this form
    * 
    * @return string - HTML text of this form's read mode
    */
	protected function RenderHTML()
	{
	   $dispmode = $this->GetDisplayMode();
	   $this->SetDisplayMode($dispmode->GetMode());
	   
      $smarty = BizSystem::GetSmartyTemplate();
      $smarty->assign_by_ref("name", $this->m_Name);
      $smarty->assign_by_ref("title", $this->m_Title);
      $smarty->assign_by_ref("toolbar", $this->m_ToolBar->Render());
      $smarty->assign_by_ref("navbar", $this->m_NavBar->Render()); 
      
      if ($dispmode->m_DataFormat == "array") // if dataFormat is array, call array render function
         $smarty->assign_by_ref("fields", $this->RenderArray());
      else if ($dispmode->m_DataFormat == "table") // if dataFormat is table, call table render function.
         $smarty->assign_by_ref("table", $this->RenderTable());
      else if ($dispmode->m_DataFormat == "block" && $dispmode->m_FormatStyle)
         $smarty->assign_by_ref("block", $this->RenderFormattedTable());
      //else if ($dispmode->m_DataFormat == "htmltree")
      //   $this->RenderFormattedTree($smarty);
      
      //$this->RenderFormattedTable($smarty);
	   return $smarty->fetch($dispmode->m_TemplateFile);
	}
	/*
	final protected function CanModeDisplayed($CtrlDispMode)
   {
      if (!$CtrlDispMode)
         return true;
      if ($CtrlDispMode == $this->m_Mode)
         return true;
      if (strpos($CtrlDispMode, $this->m_Mode) === false)
         return false;
      return true;
   }
   */
   /**
    * BizForm::RenderArray() - Render form as array format using array template
    * @return string 1d array
    */
   protected function RenderArray()
   {
      //$this->SetCursorIndex(0);
      $columns = $this->m_RecordRow->RenderColumn();
      foreach($columns as $key=>$val)
         $fields[$key]["label"] = $val;

      $this->m_RecordRow->SetRecordArr($this->m_ActiveRecord);
      $controls = $this->m_RecordRow->Render(); 
      if ($this->CanShowData()) {
         foreach($controls as $key=>$val) {
            $fields[$key]["control"] = $val;
         }
      }
      return $fields;
   }
   
   /**
    * BizForm::RenderTable() - Render form as table format using table template
    * @return string 2d array
    */
   protected function RenderTable()
   {
      $this->GetDataObj()->MoveCursorTo(0, true);
      $records = array();
      $records[] = $this->m_RecordRow->RenderColumn();
      $counter = 0;
      while ($counter < $this->m_Range) {
         if ($this->CanShowData())
            $arr = $this->GetDataObj()->GetRecord(1);
         if (!$arr)
            break;
         $this->m_RecordRow->SetRecordArr($arr);
         $tblRow = $this->m_RecordRow->Render();
         $records[] = $tblRow;
      }
      return $records;
   }
   
   /**
    * BizForm::RenderFormattedTable() - Render form as table format using table format style
    * Example as template->m_FormatStyle[0,1,2,3,4,5]:table,head,rowodd,roweven,rowsel,cell
    *
    * @return string HTML text
    */
   protected function RenderFormattedTable()
   {
      $this->GetDataObj()->MoveCursorTo(0, true);
      $doCacheMode = $this->GetDataObj()->GetCacheMode();
      
      $dispmode = $this->GetDisplayMode();
      //$this->SetDisplayMode($dispmode->GetMode());
      $cls_tbl = strlen($dispmode->m_FormatStyle[0])>0 ? "class=".$dispmode->m_FormatStyle[0] : "";
      $sHTML = "<table width=100% border=0 cellspacing=0 cellpadding=3 $cls_tbl>";
      $sHTML .= "<tbody id='".$this->m_Name."_tbody' Highlighted='".$this->m_Name."_data_".($this->m_CursorIndex+1)."' SelectedRow='".($this->m_CursorIndex+1)."'>";
      
      // print column header
      $cls_head = strlen($dispmode->m_FormatStyle[1])>0 ? "class=".$dispmode->m_FormatStyle[1] : "";
      $columns = $this->m_RecordRow->RenderColumn();
      foreach($columns as $colname)
         $sHTML .= "<th $cls_head>$colname</th>";

      // print column data table
      $name = $this->m_Name;
      $cls_rowodd = strlen($dispmode->m_FormatStyle[2])>0 ? "class=".$dispmode->m_FormatStyle[2] : "";
      $attr_rowodd = strlen($dispmode->m_FormatStyle[2])>0 ? "normal=".$dispmode->m_FormatStyle[2] : "";
      $cls_roweven = strlen($dispmode->m_FormatStyle[3])>0 ? "class=".$dispmode->m_FormatStyle[3] : "";
      $attr_roweven = strlen($dispmode->m_FormatStyle[3])>0 ? "normal=".$dispmode->m_FormatStyle[3] : "";
      $cls_rowsel = strlen($dispmode->m_FormatStyle[4])>0 ? "class=".$dispmode->m_FormatStyle[4] : "";
      $attr_rowsel = strlen($dispmode->m_FormatStyle[4])>0 ? "select=".$dispmode->m_FormatStyle[4] : "";
      $cls_cell = strlen($dispmode->m_FormatStyle[5])>0 ? "class=".$dispmode->m_FormatStyle[5] : "";
      $counter = 0;
      while ($counter < $this->m_Range) {
         if ($this->CanShowData())
            $arr = $this->GetDataObj()->GetRecord(1);
         else 
            $arr = null;
         //print_r($arr); echo "<br>";
         if (!$arr && $this->m_FullPage == "N")
            break;
         if (!$arr)
            $sHTML .= "<tr><td colspan=99>&nbsp;</td></tr>\n";
         else {
            $this->m_CursorIDMap[$counter] = $arr["Id"];

            $this->m_RecordRow->SetRecordArr($arr);
            $tblRow = $this->m_RecordRow->Render(); 
            $rowHTML = "";
            foreach($tblRow as $cell)
               $rowHTML .= "<td valign=top $cls_cell>$cell</td>\n";
            $rownum = $counter+1;
            $rowid = $name."_data_".$rownum;
            $attr = $rownum % 2 == 0 ? "$attr_roweven $attr_rowsel" : "$attr_rowodd $attr_rowsel";
            if ($counter == $this->m_CursorIndex && $doCacheMode==1) $style_class = $cls_rowsel;
            else if ($rownum % 2 == 0) $style_class = $cls_roweven;
            else $style_class = $cls_rowodd;
            $onclick = ($doCacheMode==1) ? "onclick=\"CallFunction('$name.SelectRecord($rownum,0,1)');\"" : "";
            $sHTML .= "<tr id='$rowid' $style_class $attr $onclick>$rowHTML</tr>\n";
         }
         $counter++;
      } // while
      // move daraobj's cursor to the UI current record
      $this->GetDataObj()->MoveCursorTo($this->m_CursorIndex, true);

      $sHTML .= "</table>";

      // restore the RecordRow data because it gets changed during record navigation
      $this->m_RecordRow->SetRecordArr($this->m_ActiveRecord);
      
      return $sHTML;
   } 
}

/**
 * RecordRow class - RecordRow is the class that contains FieldControls
 * 
 * @package BizView
 */
class RecordRow extends MetaIterator implements iUIControl 
{
   protected $m_SortedControlKeys;
   
   public function SetMode($mode, $dataFormat) 
   { 
      foreach ($this->m_var as $ctrl)
         $ctrl->SetMode($mode, $dataFormat);
   }
   
   public function SetAccessFlag($flag) { }
   
   /**
    * RecordRow::GetSortControlKeys() - Get sorted contorl keys, the sort order is defined in metadata file
    * 
    * @return array - sorted key array
    */
   public function GetSortControlKeys()
   {
      if ($this->m_SortedControlKeys)
         return $this->m_SortedControlKeys;
      foreach($this->m_var as $key=>$ctrl)
      {
         if ($ctrl->m_Order)
            $keyOrder[$key] = $ctrl->m_Order;
         else
            $keyNoOrder[] = $key;
      }
      if($keyOrder) {
         asort($keyOrder);
         if ($keyNoOrder)
            $this->m_SortedControlKeys = array_merge($keyNoOrder, array_keys($keyOrder));
         else
            $this->m_SortedControlKeys = array_keys($keyOrder);
      }
      else
         $this->m_SortedControlKeys = $keyNoOrder;
      return $this->m_SortedControlKeys;
   }
   /**
    * RecordRow::SetRecordArr() - assign the record array to RecordRow object. It is usually called before calling its render method.
    * 
    * @param array - record array
    * @return void
    */
   public function SetRecordArr(&$recArr)
   {
      foreach ($this->m_var as $fldCtrl) {
         if (!$recArr) 
            $fldCtrl->SetValue("");
         else if (key_exists($fldCtrl->m_BizFieldName,$recArr))
            $fldCtrl->SetValue($recArr[$fldCtrl->m_BizFieldName]);
      }
   }
   /**
    * RecordRow::Render() - Render the record row with thml text. It is usually called after calling its SetRecordArr method.
    * 
    * @return string - html text
    */
   public function Render()
   {
      $values = array();
      $keylist = $this->GetSortControlKeys();
      foreach ($keylist as $key) {
         $fldCtrl = $this->m_var[$key];
         if (!$fldCtrl->CanDisplayed()) 
            continue; 
         $values[$key] = $fldCtrl->Render();
      }
      return $values;
   }
   /**
    * RecordRow::RenderColumn() - Render the current record display name (header of a html table)
    * 
    * @return array - display name of all fieldcontrols
    */
   public function RenderColumn()
   {
      $values = array();
      foreach ($this->GetSortControlKeys() as $key) {
         $fldCtrl = $this->m_var[$key];
         if (!$fldCtrl->CanDisplayed()) 
            continue;
         $colname = $fldCtrl->RenderHeader();
         if ($colname)
            $values[$key] = $colname;
      }
      return $values;
   }
}

/**
 * ToolBar class - ToolBar is the class that contains HTMLControls
 * 
 * @package BizView
 */
class ToolBar extends MetaIterator implements iUIControl 
{
   public function SetMode($mode, $dataFormat) 
   { 
      foreach ($this->m_var as $ctrl)
         $ctrl->SetMode($mode, $dataFormat);
   }
   
   public function SetAccessFlag($flag) { foreach($this->m_var as $ctrl) $ctrl->SetAccessFlag($flag); }
   
   /**
    * ToolBar::Render() - Render the ToolBar with thml text.
    * 
    * @return string - html text
    */
   public function Render()
   {
      $mode = $this->m_prtObj->GetDisplayMode();
      $tbar = array();
      foreach($this->m_var as $ctrl) {
         $ctrl->SetState("ENABLED");
         // todo: readonly access
         if ($ctrl->CanDisplayed()) 
            $tbar[$ctrl->m_Name] = $ctrl->Render();
      } 
      return $tbar;
   }
}

/**
 * NavBar class - NavBar is the class that contains navigation buttons
 * 
 * @package BizView
 */
class NavBar extends MetaIterator implements iUIControl 
{
   public function SetMode($mode, $dataFormat) 
   { 
      foreach ($this->m_var as $ctrl)
         $ctrl->SetMode($mode, $dataFormat);
   }   
   public function SetAccessFlag($flag) { foreach($this->m_var as $ctrl) $ctrl->SetAccessFlag($flag); }
   
   /**
    * NavBar::Render() - Render the ToolBar with thml text.
    * 
    * @return string - html text
    */
   public function Render()
   {
      if (!$this->m_prtObj->GetDataObj()) return "";
      $nbar = array();
      $curPage = $this->m_prtObj->GetDataObj()->GetCurrentPageNumber();
      $totalPage = $this->m_prtObj->GetDataObj()->GetTotalPageCount();
      foreach($this->m_var as $ctrl) {
         if (!$ctrl->CanDisplayed()) continue;
         if (($curPage == 1) && (strpos($ctrl->m_Function, "MovePrev") > 0))
            $ctrl->SetState("DISABLED");
         else if (($curPage == $totalPage) && (strpos($ctrl->m_Function, "MoveNext") > 0))
            $ctrl->SetState("DISABLED");
         else
            $ctrl->SetState("ENABLED");

         $nbar[$ctrl->m_Name] = $ctrl->Render();
      } 
      // append curPage and totalPage
      $nbar["curPage"] = $curPage;
      $nbar["totalPage"] = $totalPage;
      return $nbar;
   }
}

/**
 * DisplayMode class - contains the BizForm display mode information
 * 
 * @package BizView
 */
class DisplayMode
{
   public $m_Name;
   public $m_DataFormat;
   public $m_FormatStyle = null;
   public $m_InitMode;
   public $m_TemplateFile;

   function __construct(&$xmlArr)
   {
      $this->m_Name = $xmlArr["ATTRIBUTES"]["NAME"];
      $this->m_DataFormat = $xmlArr["ATTRIBUTES"]["DATAFORMAT"];
      $this->m_TemplateFile = $xmlArr["ATTRIBUTES"]["TEMPLATEFILE"];
      $this->m_InitMode = $xmlArr["ATTRIBUTES"]["INITMODE"];
      if ($attr = $xmlArr["ATTRIBUTES"]["FORMATSTYLE"]) {
         $this->m_FormatStyle = array();
         $this->m_FormatStyle = split(",",$attr);
      }
   } 
   
   public function GetMode()
   {
      switch ($this->m_Name) {
         case "READ": $mode = MODE_R; break;
         case "EDIT": $mode = MODE_E; break;
         case "NEW": $mode = MODE_N; break;
         case "QUERY": $mode = MODE_Q; break;
         default: $mode = $this->m_Name;
      }
      return $mode;
   }
}
?>