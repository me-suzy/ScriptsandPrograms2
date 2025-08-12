<?PHP
/**
 * BizDataObj class - class BizDataObj is the base class of all data object classes
 * 
 * @package BizDataObj
 * @author rocky swen 
 * @copyright Copyright (c) 2005
 * @access public 
 */
class BizDataObj extends MetaObject implements iSessionObject 
{
   // metadata vars are public, necessary for metadata inheritance
   public $m_Database;
   public $m_SearchRule = null;
   public $m_SortRule = null;
   public $m_OtherSQLRule = null;
   public $m_MainTable = "";
   public $m_BizRecord = null;
   public $m_InheritFrom;
   public $m_AccessRule = null;
   public $m_UpdateCondition = null;
   public $m_DeleteCondition = null;
   public $m_ObjReferences = null;
   public $m_TableJoins = null;
   
   // todo: provide function to access these vars
   protected $m_CurrentPage = 1;
   protected $m_PageNumber = 0;
   protected $m_TotalRecords = 0;
   protected $m_PageRange = 10;
   protected $m_DbConnect = null;
   protected $m_BizResultSet = null;
   protected $m_SqlSearchRule = null;
   protected $m_SqlSortRule = null;
   protected $m_SqlOtherSQLRule = null;
   protected $m_QuerySQL = "";
   protected $m_CacheRecordList = null;
   protected $m_CurrentRecord = null;
   protected $m_Readonly = false;
   protected $m_KeyFldsCols;   /// COMPKEY
   protected $m_CacheMode = 1;   // default cache mode = 1, use cache. if cacheMode=0, no cache is used.
   protected $m_ErrorMessage = "";
   
   protected $m_Association = null;
   protected $m_DataSqlObj = null;
   
   /**
    * BizDataObj::__construct(). Initialize BizDataObj with xml array
    * 
    * @param array $xmlArr
    * @return void 
    */
   function __construct(&$xmlArr)
   {
      global $g_BizSystem;

      $this->ReadMetadata($xmlArr);
      
      $this->InheritParentObj();

      $this->m_CacheRecordList = new CacheRecordList($this->m_Name);
   }
   
   protected function ReadMetadata(&$xmlArr)
   { 
      $this->m_Name = $xmlArr["BIZDATAOBJ"]["ATTRIBUTES"]["NAME"];
      $this->m_InheritFrom = $xmlArr["BIZDATAOBJ"]["ATTRIBUTES"]["INHERITFROM"];
      $this->m_Description = $xmlArr["BIZDATAOBJ"]["ATTRIBUTES"]["DESCRIPTION"];
      $this->m_Package = $xmlArr["BIZDATAOBJ"]["ATTRIBUTES"]["PACKAGE"];
      $this->m_Class = $xmlArr["BIZDATAOBJ"]["ATTRIBUTES"]["CLASS"];
      $this->m_SearchRule = $xmlArr["BIZDATAOBJ"]["ATTRIBUTES"]["SEARCHRULE"];
      $this->m_SortRule = $xmlArr["BIZDATAOBJ"]["ATTRIBUTES"]["SORTRULE"];
      $this->m_OtherSQLRule = $xmlArr["BIZDATAOBJ"]["ATTRIBUTES"]["OTHERSQLRULE"];
      $this->m_AccessRule = $xmlArr["BIZDATAOBJ"]["ATTRIBUTES"]["ACCESSRULE"];
      $this->m_UpdateCondition = $xmlArr["BIZDATAOBJ"]["ATTRIBUTES"]["UPDATECONDITION"];
      $this->m_DeleteCondition = $xmlArr["BIZDATAOBJ"]["ATTRIBUTES"]["DELETECONDITION"];
      $this->m_Database = $xmlArr["BIZDATAOBJ"]["ATTRIBUTES"]["DBNAME"];
      $this->m_MainTable = $xmlArr["BIZDATAOBJ"]["ATTRIBUTES"]["TABLE"];
      $cacheMode = $xmlArr["BIZDATAOBJ"]["ATTRIBUTES"]["CACHEMODE"];
      $this->m_CacheMode = $cacheMode==null ? 1 : $cacheMode;
      
      $this->m_Name = $this->PrefixPackage($this->m_Name);
      
      // build BizRecord
      $this->m_BizRecord = new BizRecord($xmlArr["BIZDATAOBJ"]["BIZFIELDLIST"]["BIZFIELD"],"BizField",$this);
      
      // build TableJoins
      $this->m_TableJoins = new MetaIterator($xmlArr["BIZDATAOBJ"]["TABLEJOINS"]["JOIN"],"TableJoin",$this);
      
      // build ObjReferences
      $this->m_ObjReferences = new MetaIterator($xmlArr["BIZDATAOBJ"]["OBJREFERENCES"]["OBJECT"],"ObjReference",$this);
   }
   
   // Name, Package, Class cannot be inherited
   protected function InheritParentObj()
   {
      if (!$this->m_InheritFrom) return;
      global $g_BizSystem;
      $prtObj = $g_BizSystem->GetObjectFactory()->GetObject($this->m_InheritFrom);
      
      $this->m_Description = $this->m_Description ? $this->m_Description : $prtObj->m_Description;
      $this->m_SearchRule = $this->m_SearchRule ? $this->m_SearchRule : $prtObj->m_SearchRule;
      $this->m_SortRule = $this->m_SortRule ? $this->m_SortRule: $prtObj->m_SortRule;
      $this->m_OtherSQLRule = $this->m_OtherSQLRule ? $this->m_OtherSQLRule: $prtObj->m_OtherSQLRule;
      $this->m_AccessRule = $this->m_AccessRule ? $this->m_AccessRule: $prtObj->m_AccessRule;
      $this->m_UpdateCondition = $this->m_UpdateCondition ? $this->m_UpdateCondition: $prtObj->m_UpdateCondition;
      $this->m_DeleteCondition = $this->m_DeleteCondition ? $this->m_DeleteCondition: $prtObj->m_DeleteCondition;
      $this->m_Database = $this->m_Database ? $this->m_Database: $prtObj->m_Database;
      $this->m_MainTable = $this->m_MainTable ? $this->m_MainTable: $prtObj->m_MainTable;
      
      $this->m_BizRecord->merge($prtObj->m_BizRecord);
      $this->m_TableJoins->merge($prtObj->m_TableJoins);
      $this->m_ObjReferences->merge($prtObj->m_ObjReferences);
   }
   
   /**
    * BizDataObj::GetSessionContext() - Retrieve Session data of this object
    * 
    * @param SessionContext $sessCtxt
    * @return void 
    */
	public function GetSessionVars($sessCtxt)
	{
	   // todo: save QuerySQL
	   $sessCtxt->GetObjVar($this->m_Name, "CurrentPage", $this->m_CurrentPage);
      $sessCtxt->GetObjVar($this->m_Name, "PageRange", $this->m_PageRange);
      $sessCtxt->GetObjVar($this->m_Name, "TotalRecords", $this->m_TotalRecords);
      $sessCtxt->GetObjVar($this->m_Name, "SearchRule", $this->m_SearchRule);
      $sessCtxt->GetObjVar($this->m_Name, "SortRule", $this->m_SortRule);
      $sessCtxt->GetObjVar($this->m_Name, "OtherSqlRule", $this->m_OtherSQLRule);
      $sessCtxt->GetObjVar($this->m_Name, "Association", $this->m_Association);
      
      if ($this->m_PageRange>0)
         $this->m_PageNumber = (int) ceil($this->m_TotalRecords / $this->m_PageRange);
      
      // retrieve CacheRecordList
      if ($this->m_CacheRecordList)
         $this->m_CacheRecordList->GetSessionVars($sessCtxt);
	}
	
	/**
    * BizDataObj::SetSessionContext() - Save Session data of this object
    * 
    * @param SessionContext $sessCtxt
    * @return void 
    */
	public function SetSessionVars($sessCtxt)
	{
	   $sessCtxt->SetObjVar($this->m_Name, "CurrentPage", $this->m_CurrentPage);
      $sessCtxt->SetObjVar($this->m_Name, "PageRange", $this->m_PageRange);
      $sessCtxt->SetObjVar($this->m_Name, "TotalRecords", $this->m_TotalRecords);
      $sessCtxt->SetObjVar($this->m_Name, "SearchRule", $this->m_SearchRule);
      $sessCtxt->SetObjVar($this->m_Name, "SortRule", $this->m_SortRule);
      $sessCtxt->SetObjVar($this->m_Name, "OtherSqlRule", $this->m_OtherSQLRule);
      $sessCtxt->SetObjVar($this->m_Name, "Association", $this->m_Association);
      
      // save CacheRecordList
      if ($this->m_CacheRecordList)
         $this->m_CacheRecordList->SetSessionVars($sessCtxt);
	}
	
	/**
    * BizDataObj::GetRefObject() - Get the object instance defined in the object reference
    * 
    * @param string $objName the object name list in the ObjectReference part
    * @return mixed object instance 
    */
	public function GetRefObject($objName)
	{
	   global $g_BizSystem;
	   // see if there is such object in the ObjReferences
	   $objRef = $this->m_ObjReferences->get($objName);
	   if (!$objRef) 
	      return null;
	   // apply association on the object
	   // $assc = $this->EvaluateExpression($objRef->m_Association);	
	   
	   // get the object instance
	   $obj = $g_BizSystem->GetObjectFactory()->GetObject($objName);   
	   $obj->SetAssociation($objRef, $this);
	   return $obj;
	}
	
	/**
    * BizDataObj::SetAssociation() - set the association of the object
    * 
    * @param ObjReference $objRef
    * @param BizDataObj $asscObj
    * @return void
    */
	protected function SetAssociation($objRef, $asscObj)
	{
      $this->m_Association["AsscObjName"] = $asscObj->m_Name;
      $this->m_Association["Relationship"] = $objRef->m_Relationship;
      $this->m_Association["Table"] = $objRef->m_Table;
      $this->m_Association["Column"] = $objRef->m_Column;
      $this->m_Association["FieldRef"] = $objRef->m_FieldRef;
      $this->m_Association["FieldRefVal"] = $asscObj->GetFieldValue($objRef->m_FieldRef);
      if ($objRef->m_Relationship == "M-M") {
         $this->m_Association["XTable"] = $objRef->m_XTable;
         $this->m_Association["XColumn1"] = $objRef->m_XColumn1;
         $this->m_Association["XColumn2"] = $objRef->m_XColumn2;
         $this->m_Association["XKeyColumn"] = $objRef->m_XKeyColumn;
      }
      //print_r($this->m_Association);
	}
	
	/**
    * BizDataObj::JoinRecord() - pick the joined object's current record to the current record
    * 
    * @param BizDataObj $joinDataObj
    * @return boolean
    */
	public function JoinRecord($joinDataObj)
	{
	   // get the maintable of the joindataobj
	   $joinTable = $joinDataObj->m_MainTable;
	   $joinRecord = null;
	   $returnRecord = array();
	   // find the proper join according to the maintable
	   foreach ($this->m_TableJoins as $tableJoin) {
	      if ($tableJoin->m_Table == $joinTable) {
   	      // populate the column-fieldvalue to columnRef-fieldvalue
   	      // get the field mapping to the column, then get the field value
   	      $joinFieldName = $joinDataObj->m_BizRecord->GetFieldByColumn($tableJoin->m_Column);
   	      if (!$joinFieldName) continue;
   	      if (!$joinRecord) 
   	         $joinRecord = $joinDataObj->GetRecord(0);
   	      $refFieldName = $this->m_BizRecord->GetFieldByColumn($tableJoin->m_ColumnRef);
   	      $returnRecord[$refFieldName] = $joinRecord[$joinFieldName];
   	      // populate joinRecord's field to current record
   	      foreach ($this->m_BizRecord as $fld) {
   	         if ($fld->m_Join == $tableJoin->m_Name) {
   	            // use join column to match joinRecord field's column
   	            $jfldname = $joinDataObj->m_BizRecord->GetFieldByColumn($fld->m_Column);
   	            $returnRecord[$fld->m_Name] = $joinRecord[$jfldname];
   	         }
   	      }
   	      break;
	      }
	   }
	   // return a modified record with joined record data
	   return $returnRecord;
	}
	
	/**
    * BizDataObj::AddRecord() - add a new record to current record set
    * 
    * @param array $recArr
    * @param boolean &$bPrtObjUpdated
    * @return boolean
    */
	public function AddRecord($recArr, &$bPrtObjUpdated)
	{
	   if ($this->m_Association["Relationship"] == "M-M") 
	   {
	      $bPrtObjUpdated = false;
	      return $this->AddRecord_MtoM($recArr);
	   }
	   else if ($this->m_Association["Relationship"] == "M-1" || $this->m_Association["Relationship"] == "1-1") 
	   {
	      $bPrtObjUpdated = true;
	      return $this->AddRecord_Mto1($recArr);
	   }
	   else
	   {
	      $this->m_ErrorMessage = "You cannot add a record in dataobj who doesn't have M-M or M-1 relationship with its parent object";
	      return false;
	   }
	}
	
	protected function AddRecord_MtoM($recArr)
	{
	   if ($this->m_CacheMode == 1) {
	      if ($this->m_CacheRecordList->HasRecord ($recArr)) return true;
	   }
	   else {
   	   // query on this object to get the corresponding record of this object.
   	   $searchRule = "[Id] = '".$recArr["Id"]."'";
   	   $recordList = array();
   	   $this->FetchRecords($searchRule, $recordList, 1);
   	   if (count($recordList) == 1) return true;
	   }
	   
	   // insert a record on XTable
	   global $g_BizSystem;
	   $db = $g_BizSystem->GetDBConnection();
	   if ($this->m_Association["XKeyColumn"])
	      $IdColumn = $this->m_Association["XKeyColumn"];
	   $val1 = $this->m_Association["FieldRefVal"];
	   $val2 = $recArr["Id"];
 	   if ($IdColumn) {
	      $sysid = BizSystem::GetNewSYSID($db, $this->m_Association["XTable"], true);
   	   $sql_col = "(".$IdColumn.",".$this->m_Association["XColumn1"].",".$this->m_Association["XColumn2"].")";
	      $sql_val = "('".$sysid."','".$val1."','".$val2."')";
 	   }
 	   else {
 	      $sql_col = "(".$this->m_Association["XColumn1"].",".$this->m_Association["XColumn2"].")";
	      $sql_val = "('".$val1."','".$val2."')";
 	   }
	   $sql = "INSERT INTO " . $this->m_Association["XTable"] . " " . $sql_col . " VALUES " . $sql_val;
      
      if ($db->Execute($sql) === false) {
	      $this->m_ErrorMessage = $db->ErrorMsg(); 
	      return false;
	   }
	   
	   // add the record to object cache. requery on this object to get the corresponding record of this object.
	   $searchRule = "[Id] = '".$recArr["Id"]."'";
	   $recordList = array();
	   $this->FetchRecords($searchRule, $recordList, 1);
	   // insert the first record to the cache
	   if (count($recordList) == 1)
	      $this->_PostInsertRecord($recordList[0]);
	   //echo "###";
	   //print_r($this->m_CacheRecordList->m_SqlRecordList);
	   return true;
	}
	
	protected function AddRecord_Mto1($recArr)
	{
	   global $g_BizSystem;
	   // set the $recArr[Id] to the parent table foriegn key column
	   // get parent/association dataobj
	   $asscObj = $g_BizSystem->GetObjectFactory()->GetObject($this->m_Association["AsscObjName"]);
	   // call parent dataobj's updateRecord 
	   $updateRecArr["Id"] = $asscObj->GetFieldValue("Id");
	   $updateRecArr[$this->m_Association["FieldRef"]] = $recArr["Id"];
	   $ok = $asscObj->UpdateRecord($updateRecArr);
	   if ($ok == false)
	      return false;
	   // requery on this object
	   $this->m_Association["FieldRefVal"] = $recArr["Id"];
	   return $this->RunSearch();
	}
	
   /**
    * BizDataObj::RemoveRecord() - remove a record from current record set of current association relationship
    * 
    * @param array $recArr
    * @param boolean &$bPrtObjUpdated
    * @return boolean
    */
	public function RemoveRecord($recArr, &$bPrtObjUpdated)
	{
	   if ($this->m_Association["Relationship"] == "M-M")
	   {
	      $bPrtObjUpdated = false; 
	      return $this->RemoveRecord_MtoM($recArr);
	   }
	   else if ($this->m_Association["Relationship"] == "M-1" || $this->m_Association["Relationship"] == "1-1") 
	   {
	      $bPrtObjUpdated = true;
	      return $this->RemoveRecord_Mto1($recArr);
	   }
	   else
	   {
	      $this->m_ErrorMessage = "You cannot add a record in dataobj who doesn't have M-M or M-1 relationship with its parent object";
	      return false;
	   }
	}
	
	protected function RemoveRecord_MtoM($recArr)
	{
	   // insert a record on XTable
	   global $g_BizSystem;
	   $db = $g_BizSystem->GetDBConnection();
	   // todo: need to know the XTable's id and other required columns
	   $sysid = BizSystem::GetNewSYSID($db, $this->m_Association["XTable"], true);
	   $where = $this->m_Association["XColumn1"] . "='" . $this->m_Association["FieldRefVal"] . "'";
	   $where .= " AND " . $this->m_Association["XColumn2"] . "='" . $recArr["Id"] . "'";
	   $sql = "DELETE FROM " . $this->m_Association["XTable"] . " WHERE " . $where;
      
      if ($db->Execute($sql) === false) {
	      $this->m_ErrorMessage = $db->ErrorMsg(); 
	      return false;
	   }
	   
	   // delete the record from object cache
	   $this->_PostDeleteRecord($recArr["Id"]);
	   return true;
	}
	
	protected function RemoveRecord_Mto1($recArr)
	{
	   global $g_BizSystem;
	   // set the $recArr[Id] to the parent table foriegn key column
	   // get parent/association dataobj
	   $asscObj = $g_BizSystem->GetObjectFactory()->GetObject($this->m_Association["AsscObjName"]);
	   // call parent dataobj's updateRecord 
	   $updateRecArr["Id"] = $asscObj->GetFieldValue("Id");
	   $updateRecArr[$this->m_Association["FieldRef"]] = "";
	   $ok = $asscObj->UpdateRecord($updateRecArr);
	   if ($ok == false)
	      return false;
	   // requery on this object
	   $this->m_Association["FieldRefVal"] = "";
	   return $this->RunSearch();
	}
	
	public function GetErrorMessage() { return $this->m_ErrorMessage; }
	
	public function GetField($fldname) { return $this->m_BizRecord->get($fldname); }
	
	public function GetFieldValue($fldname, $getActiveOnly=false) { 
	   if ($getActiveOnly == true)
	      return $this->m_BizRecord->get($fldname)->GetValue();
	   $recArr = $this->GetRecord(0, false); // turn off autoquery
	   if (!$recArr)
	     return null;
	   return $recArr[$fldname]; 
	}
	
	public function GetCurrentPageNumber() { return $this->m_CurrentPage; }
	
	public function GetTotalPageCount() { return $this->m_PageNumber; }
	
	public function SetPageRange($range) { $this->m_PageRange = $range; }
	
	public function GetCacheMode() { return $this->m_CacheMode; }
	
	public function SetCacheMode($cmode) { $this->m_CacheMode = $cmode; }
	
	public function ClearSearchRule() {  $this->m_SearchRule = ""; }
	
	/**
    * BizDataObj::SetSearchRule() - Set search rule as text in sql where clause. i.e. [fieldName] opr Value
    * 
    * @param string $rule search rule has format "[fieldName] opr Value"
    * @return void
    **/
	public function SetSearchRule($rule = null)
	{
	   // search rule has format "[fieldName] opr Value", replace [fieldName] with table.column
	   if (!$rule) {
	      return;
	   }
	   if (!$this->m_SearchRule)
	      $this->m_SearchRule = $rule;
	   else
	      $this->m_SearchRule .= " AND " . $rule;
	}
	
	/**
    * BizDataObj::SetSortRule() - Set search rule as text in sql order by clause. i.e. [fieldName] DESC|ASC
    * 
    * @param string $rule ort rule has format "[fieldName] DESC|ASC"
    * @return void
    **/
   public function SetSortRule($rule = null)
   { 
      // sort rule has format "[fieldName] DESC|ASC", replace [fieldName] with table.column
      $this->m_SortRule = $rule;
   } 
   
   /**
    * BizDataObj::SetOtherSQLRule() - Append extra SQL statment in sql. i.e. GROUP BY [fieldName]
    * 
    * @param string $rule has SQL format "GROUP BY [fieldName] HAVING ..."
    * @return void
    **/
   public function SetOtherSQLRule($rule = null)
   { 
      // $rule has SQL format "GROUP BY [fieldName] HAVING ...". replace [fieldName] with table.column
      $this->m_OtherSQLRule = $rule;
   } 
	
	/**
    * BizDataObj::RuleToSql() - Convert search/sort rule to sql clause, replace [fieldName] with table.column
    * openbiz SQL expression as "[fieldName] opr 'Value' AND/OR [fieldName] opr 'Value'...". "()" is valid syntax
    *
    * @param string $rule "[fieldName] ..."
    * @return string sql statement
    **/
	protected function RuleToSql($rule)
   {
      global $g_BizSystem;
      //$pattern = "/\[([^\]]+)\]\s*([a-zA-Z<>=]+)\s*('[^']+'|-?[0-9\.]+|\s*)/";
      $sqlstr = ""; 
      $startpos = 0;
      while ($startpos<strlen($rule)) {
         $pos0 = strpos($rule,"[",$startpos);
         $sqlstr .= substr($rule, $startpos, $pos0-$startpos);
         $fieldname = substr_lr($rule,"[","]",$startpos);
         if ($fieldname == "") {
            $sqlstr .= substr($rule, $startpos);
            break;
         }
         else {
            $bizFld = $this->m_BizRecord->get($fieldname);
            
            $tableColumn = $this->m_DataSqlObj->GetTableColumn($bizFld->m_Join, $bizFld->m_Column);
            
            preg_match("/\s*[a-zA-Z<>=]+\s*/",substr($rule,$startpos), $match);
            $opr = $match[0];
            
            // if opr is ASC or DESC, it's the sort syntax, no value is parsed.
            if (trim($opr) == "ASC" || trim($opr) == "DESC")
               return "$tableColumn $opr";
            
            $startpos += strlen($opr);   // shift the position index to left
            
            // value can be either 'text', number, or {simple expression}
            $value = substr_lr($rule,"'","'",$startpos,true);  // text value with ''
            $hasQuote = ($value===false) ? false : true;
            
            if ($value===false) $value = substr_lr($rule,"{","}",$startpos,true); // simple expr with {}
            if ($value) // evaluate simple expr
               $value = $this->EvaluateExpression($value);
            
            if ($value===false) $value = substr_lr($rule," "," ",$startpos,true); // number value w/o ''

            // unformat the value. [fieldName] opr 'Value'
            global $g_BizSystem;
            $realVal = $g_BizSystem->GetTypeManager()->FormattedStringToValue($bizFld->m_Type, $bizFld->m_Format, $value);
            
            if ($hasQuote)
               $sqlstr .= " $tableColumn $opr '$realVal' ";
            else
               $sqlstr .= " $tableColumn $opr $realVal ";
         }
      }
      return $sqlstr;
   }
	
   /**
    * BizDataObj::RunSearch() - Run the SQL statement, if no database connection, connect the database first
    * 
    * @param int $page - If $page>0, RunSearch does page query, queyr for the records in given page. If $page<0, RunSearch does a normal query.
    * @param boolean $resetCache - if $resetCache is true, exsiting query results in cache will be cleaned.
    * @return boolean - if return false, the caller can call GetErrorMessage to get the error.
    **/
   // todo: throw BDOException 
	public function RunSearch($page=1,$resetCache=true)
	{
	   global $g_BizSystem;
	   
	   //$g_BizSystem->ErrorBacktrace();
	   
	   // Build Query SQL. todo: should save sql in the session vars to avoid regenerate sql
      $this->BuildQuerySQL(); 
      BizSystem::log(LOG_DEBUG, "DATAOBJ", "Query Sql = ".$this->m_QuerySQL);
      
      // get database connection
      $db = $g_BizSystem->GetDBConnection();

      if ($this->m_PageRange>0 && $page>0) {
         $this->m_BizResultSet = $db->PageExecute($this->m_QuerySQL, $this->m_PageRange, $page);
         if ($this->m_BizResultSet) {
            $this->m_PageNumber = $this->m_BizResultSet->LastPageNo();
            $this->m_TotalRecords = $this->m_BizResultSet->_maxRecordCount;
         }
      }
      else {
         $this->m_BizResultSet = $db->Execute($this->m_QuerySQL);
      }
      if (!$this->m_BizResultSet) {
         $this->m_ErrorMessage = "Error in query: " . $this->m_QuerySQL . ". " . $db->ErrorMsg();
         //trigger_error("Error in query: " . $this->m_QuerySQL . ". " . $db->ErrorMsg(), E_USER_ERROR);
         return false;
      }
         
      // clean up the record cache list
      if ($page<=1 && $resetCache && $this->m_CacheMode==1) {
         $this->m_CurrentPage = 1;
         unset ($this->m_CacheRecordList);
         $this->m_CacheRecordList = null;
         $this->m_CacheRecordList = new CacheRecordList($this->m_Name);
      }
      return true;
	}
	
	/**
    * BizDataObj::BuildQuerySQL() - Build the Select SQL statement based on the fields and search/sort rule
    * 
    * @return void
    **/
	protected function BuildQuerySQL()
	{
	   // todo: if no searchrule or sortrule change ...
      // build the SQL statement based on the fields and search rule
      if (!$this->m_DataSqlObj) {
         include_once("BizDataSql.php");
         $this->m_DataSqlObj = new BizDataSql();
         // add table 
         $this->m_DataSqlObj->AddMainTable($this->m_MainTable);
         // add join table
         foreach($this->m_TableJoins as $tableJoin) {
            $tbl_col = $this->m_DataSqlObj->AddJoinTable($tableJoin);
         } 
         // add columns
         foreach($this->m_BizRecord as $bizFld) {
            if ($bizFld->m_Column && !$bizFld->m_SqlExpression)
               $this->m_DataSqlObj->AddTableColumn($bizFld->m_Join, $bizFld->m_Column);
            if ($bizFld->m_SqlExpression) {
               $this->m_DataSqlObj->AddSqlExpression($this->ConvertSqlExpresion($bizFld->m_SqlExpression));
            }
         }
      }

      // append SearchRule in the WHERE clause
      $sqlSearchRule = $this->RuleToSql($this->m_SearchRule);
      $this->m_DataSqlObj->AddSqlWhere($sqlSearchRule);
      
      // append SearchRule in the ORDER BY clause
      $sqlSortRule = $this->RuleToSql($this->m_SortRule);
      $this->m_DataSqlObj->AddOrderBy($sqlSortRule);
      
      // append SearchRule in the other SQL clause
      $sqlOtherSQLRule = $this->RuleToSql($this->m_OtherSQLRule);
      $this->m_DataSqlObj->AddOtherSQL($sqlOtherSQLRule);
      
      // append SearchRule in the AccessRule clause
      $sqlAccessSQLRule = $this->RuleToSql($this->m_AccessRule);
      //echo $sqlAccessSQLRule;
      $this->m_DataSqlObj->AddSqlWhere($sqlAccessSQLRule);
      
      // add association to SQL
      $this->m_DataSqlObj->AddAssociation($this->m_Association);
      
      $this->m_QuerySQL = $this->m_DataSqlObj->GetSqlStatement();
	}
	
	/**
    * BizDataObj::BuildUpdateSQL() - build update sql UPDATE table SET col1=val1, col2=val2 ... WHERE idcol1='id1' AND idcol2='id2'
    * 
    * @return void
    **/
	protected function BuildUpdateSQL()
	{
	   // generate column value pairs. ??? ignore those whose inputValue=fieldValue
      $sqlArr = $this->m_BizRecord->GetSqlRecord();
      $sql = "";
      foreach($sqlArr as $col=>$val) {
         if ($sql!="") $sql .= ", ".$col."='".$val."'";
         else $sql .= $col."='".$val."'";
      }
      $sql = "UPDATE " . $this->m_MainTable . " SET " . $sql;
      
      // where is to update record on the primary key or composite keys
      $keyFlds = $this->m_BizRecord->GetKeyFields();
      $whereStr = "";
      foreach ($keyFlds as $fldname=>$fldobj) {
        if($whereStr=="") $whereStr .= $fldobj->m_Column."='".$fldobj->GetValue()."'";
        else $whereStr .= " AND ".$fldobj->m_Column."='".$fldobj->GetValue()."'";
      }
      $sql .= " WHERE " . $whereStr;
      return $sql;
	}
	
	/**
    * BizDataObj::BuildDeleteSQL() - build delete sql DELETE FROM table WHERE idcol1='id1' AND idcol2='id2'
    * 
    * @return void
    **/
	protected function BuildDeleteSQL()
	{
      $sql = "DELETE FROM " . $this->m_MainTable;
      
      // where is to delete record on the primary key or composite keys
      $keyFlds = $this->m_BizRecord->GetKeyFields();
      $whereStr = "";
      foreach ($keyFlds as $fldname=>$fldobj) {
        if($whereStr=="") $whereStr .= $fldobj->m_Column."='".$fldobj->GetValue()."'";
        else $whereStr .= " AND ".$fldobj->m_Column."='".$fldobj->GetValue()."'";
      }
      $sql .= " WHERE " . $whereStr;
      return $sql;
	}
	
	/**
    * BizDataObj::BuildInsertSQL() - build insert sql INSERT INTO table_name (column1, column2,...) VALUES (value1, value2,....)
    * 
    * @return void
    **/
	protected function BuildInsertSQL()
	{
	   // generate column value pairs.
      $sqlArr = $this->m_BizRecord->GetSqlRecord();
      $sql_col = ""; $sql_val = "";
      $count = count($sqlArr);
      $i = 0;
      foreach($sqlArr as $col=>$val) {
         if ($i==0) {
            $sql_col .= "(".$col;
            $sql_val .= "('".$val."'";
         }
         if ($i>0 && $i<$count-1) {
            $sql_col .= ", ".$col;
            $sql_val .= ", '".$val."'";
         }
         if ($i==$count-1) {
            $sql_col .= ($i==0) ? $col.")" : ",".$col.")";
            $sql_val .= ($i==0) ? "'".$val."')" : ",'".$val."')";
         }
         $i++;
      }
      $sql = "INSERT INTO " . $this->m_MainTable . " " . $sql_col . " VALUES " . $sql_val;
      return $sql;
	}
	
	/**
    * BizDataObj::FetchRecords()
    */
	public function FetchRecords($searchRule, &$recordList, $recNum=-1, $clearSearchRule=true)
	{
	   if ($recNum == 0) return;
	   $oldCacheMode = $this->m_CacheMode; 
	   $oldSearchRule = $this->m_SearchRule;
	   if ($clearSearchRule)
	     $this->ClearSearchRule();
	   $this->SetSearchRule($searchRule);
      $this->SetCacheMode(0);    // turn off cache mode, not affect the current cache
      $this->RunSearch(-1);  // don't use page search
      $count = 0;
      while (1)
      {
         if ($recNum>0 && $count==$recNum) 
            break;
         $recArray = $this->GetRecord(1);
         if (!$recArray) 
            break;
         $recordList[$count] = $recArray;
         $count++;
      }
      $this->m_SearchRule = $oldSearchRule;
      $this->m_CacheMode = $oldCacheMode; 
	}

	/**
    * BizDataObj::GetRecord() - get the record from query results and move the cursor to next|prev|no record.
    * 
    * @param int $move - cursor move step
    * @param boolean $autoReQuery - if true, auto issue a query when the query result is null.
    * @return array - record array
    **/
	// todo: throw BDOException 
	public function GetRecord($move=1, $autoReQuery=true)
	{
	   if ($this->m_CacheMode == 0) {
	      $recArr = $this->GetRecordNonCacheMode($move, $autoReQuery);
	      if ($recArr == false)  return false;
	      return $recArr;
	   }

	   $hasValidRecord = false;
      if (!$this->m_CacheRecordList->EOF) {
         $recArr = $this->m_CacheRecordList->GetRecord();
         $hasValidRecord = true;
      }
      else {
         if (!$this->m_BizResultSet && $autoReQuery) { 
            $this->RunSearch($this->m_CurrentPage,false);   // requery current page without reset the cache
         }
      	if ($this->m_BizResultSet) {
      	   while($sqlArr = $this->m_BizResultSet->FetchRow()) {
         	   $recArr = $this->m_BizRecord->GetRecordArr($sqlArr);
         	   if ($this->m_CacheRecordList->HasRecord ($recArr)) // if has same record in cache, get next record
         	      continue;
         	   $this->m_CacheRecordList->AppendRecord ($recArr);
         	   $hasValidRecord = true;
         	   break;
      	   }
      	}
      }
      $this->m_CacheRecordList->MoveCursor($move);
      if (!$hasValidRecord) return false;
      return $recArr;
	}
	
	protected function GetRecordNonCacheMode($move=1, $autoReQuery=true)
	{
	   $hasValidRecord = false;
      if (!$this->m_BizResultSet && $autoReQuery) {
         $this->RunSearch($this->m_CurrentPage,false);
      }
      if ($this->m_BizResultSet) {
         if ($this->m_CurrentRecord == null) {
      	   if ($sqlArr = $this->m_BizResultSet->FetchRow()) {
         	   $recArr = $this->m_BizRecord->GetRecordArr($sqlArr);
         	   $hasValidRecord = true;
         	   if ($move == 0) 
         	      $this->m_CurrentRecord = $recArr; // no move, remember current record
         	   else 
         	      $this->m_CurrentRecord = null; // move forward, clear current record
      	   }
         }
         else {
            $recArr = $this->m_CurrentRecord;
            $hasValidRecord = true;
            if ($move == 1) 
               $this->m_CurrentRecord = null; // move forward, clear current record
         }
	   }
	   if (!$hasValidRecord) return false;
	   return $recArr;
	}
	
	/**
    * BizDataObj::NextPage() - Move cursor to next page
    * 
    * @return void
    **/
	public function NextPage()
	{
      if ($this->m_CurrentPage >= $this->m_PageNumber)
         $this->m_CurrentPage = $this->m_PageNumber;
      else
      {
         $this->m_CurrentPage++;
         if ($this->m_CacheMode == 1)
            $this->m_CacheRecordList->SetCursor (($this->m_CurrentPage-1)*$this->m_PageRange);
      }
	}
	
	/**
    * BizDataObj::PrevPage() - Move cursor to previous page
    * 
    * @return void
    **/
	public function PrevPage()
	{
      if ($this->m_CurrentPage <= 1)
         $this->m_CurrentPage = 1;
      else {
         $this->m_CurrentPage--; 
         if ($this->m_CacheMode == 1)
            $this->m_CacheRecordList->SetCursor (($this->m_CurrentPage-1)*$this->m_PageRange);
      }
	}
	
	/*
	public function FirstPage() { $this->m_CurrentPage = 1; }
	public function LastPage() { $this->m_CurrentPage = $this->m_PageNumber; }
	*/
	
	/**
    * BizDataObj::MoveCursorTo() - Move cursor to specific position
    * 
    * @param integer $cursorIndex - cursor position
    * @param boolean $isPageRelative - if the cursor is relative to page
    * @return void
    **/
	public function MoveCursorTo($cursorIndex, $isPageRelative)
   {
      if ($this->m_CacheMode == 0) return;
      
      if ($isPageRelative)
      {
         $cacheCursor = ($this->m_CurrentPage-1)*$this->m_PageRange + $cursorIndex;
         $this->m_CacheRecordList->SetCursor ($cacheCursor);
      }
      else
         $this->m_CacheRecordList->SetCursor ($cursorIndex);
   }
   
   /**
    * BizDataObj::MoveToRecord() - Move cursor to the record with given record Id
    * Note: if record Id is composite key, this method takes no action.
    * 
    * @param string $Id record Id
    * @return void
    **/
   public function MoveToRecord($Id)
   {
      if ($this->m_CacheMode == 0) 
         return 0;
         
      // scan first page
      $this->MoveCursorTo(0, true);
      for ($i=0; $i < $this->m_PageRange; $i++) {
         $recArr = $this->GetRecord(1);
         if (!$recArr) break;
         $curId = $recArr["Id"];
         if ($curId == $Id) return $i;
      }
      // if not found, query this record and add it to top - not touch the exsiting result/cache
      $keyFlds = $this->m_BizRecord->GetKeyFields();
      if (count($keyFlds) > 1) return 0;  // not support compkey
      
      $recList = array();
      $this->FetchRecords("[Id]='$Id'", $recList, 1, true);
      if (count($recList) == 1) {
         $recArr = $recList[0];
         $this->MoveCursorTo(0, true);
         $this->m_CacheRecordList->InsertRecord ($recArr);
      }
      return 0;
   }
   
   /**
    * BizDataObj::UpdateRecord() - Update record using given input record array
    * 
    * @param array $recArr - associated array whose keys are field names of this BizDataObj
    * @return boolean - if return false, the caller can call GetErrorMessage to get the error.
    **/
   // todo: throw BDOException 
   public function UpdateRecord($recArr)
   {
      if (!$this->CanUpdateRecord()) {
         $this->m_ErrorMessage = "You don't have permission to update this record.";
	      return false;
	   }
         
      if (!$recArr["Id"])
         $recArr["Id"] = $this->GetFieldValue("Id");
      $this->m_BizRecord->SetInputRecord($recArr);
      if (!$this->ValidateInput()) return false;
      
      $sql = $this->BuildUpdateSQL();
      BizSystem::log(LOG_DEBUG, "DATAOBJ", "Update Sql = $sql");
      
      global $g_BizSystem;
      $db = $g_BizSystem->GetDBConnection();
      
      if ($db->Execute($sql) === false) {
         $this->m_ErrorMessage = $db->ErrorMsg();
	      return false;
	   }
	   
      // fetch the current again since fields have dependency
      $searchRule = "[Id] = '".$recArr["Id"]."'";
      $recordList = array();
      $this->FetchRecords($searchRule, $recordList, 1);
      $this->_PostUpdateRecord($recordList[0]);
      //$this->_PostUpdateRecord($recArr);
      return true;
   }
   
   private function _PostUpdateRecord($recArr)
   {
      if ($this->m_CacheMode==1) {
         $this->m_CacheRecordList->UpdateRecord($this->m_BizRecord->GetKeyValue(), $recArr);
      }
   }
   
   /**
    * BizDataObj::NewRecord() - Create an empty record
    * 
    * @return array - empty record array
    **/
   public function NewRecord()
   {
      global $g_BizSystem;
      // auto generated SYSID
      $sysid = BizSystem::GetNewSYSID($g_BizSystem->GetDBConnection(), $this->m_MainTable, true); 
      //echo "sysid = ".$sysid;
      $recArr = $this->m_BizRecord->GetEmptyRecordArr();
      // todo: assign default values
      
      // if association is 1-M, set the field (pointing to the column) value as the FieldRefVal
      if ($this->m_Association["Relationship"] == "1-M") {
         foreach ($this->m_BizRecord as $fld) {
            if ($fld->m_Column == $this->m_Association["Column"] && !$fld->m_Join) {
               $recArr[$fld->m_Name] = $this->m_Association["FieldRefVal"];
               break;
            }
         }
      }
      if ($sysid)
         $recArr["Id"] = $sysid;
         
      return $recArr;
   }
   
   /**
    * BizDataObj::InsertRecord() - insert record using given input record array
    * 
    * @param array $recArr - associated array whose keys are field names of this BizDataObj
    * @return boolean - if return false, the caller can call GetErrorMessage to get the error.
    **/
   // todo: throw BDOException 
   public function InsertRecord($recArr)
   {
      $this->m_BizRecord->SetInputRecord($recArr);
      if (!$this->ValidateInput()) return false;
      
      $sql = $this->BuildInsertSQL();
      BizSystem::log(LOG_DEBUG, "DATAOBJ", "Insert Sql = $sql");

      global $g_BizSystem;
      $db = $g_BizSystem->GetDBConnection();
      
      if ($db->Execute($sql) === false) {
	      $this->m_ErrorMessage = $db->ErrorMsg();
	      return false;
	   }

	   $new_id = $recArr["Id"];
	   // if table use auto-generate id column
	   if (!$recArr["Id"] || $recArr["Id"] == "") {
   	   $dbinfo = $g_BizSystem->GetConfiguration()->GetDatabaseInfo($rDBName);
   	   if ($dbinfo == "mysql")
   	      $new_id = mysql_insert_id();
   	   // todo: need to consider other databases. psql - curval, mssql - @@identity ...
   	   // but no universal solution to get id of inserted record
	   }
	   
	   $insertRecArr = $this->m_BizRecord->GetRecordArr();
	   $insertRecArr["Id"] = $new_id;
	   
      $this->_PostInsertRecord($insertRecArr);
      return true;
   }
   
   private function _PostInsertRecord($recArr)
   {
      if ($this->m_CacheMode==1) 
         //$this->m_CacheRecordList->InsertRecord($this->m_BizRecord->GetRecordArr());
         $this->m_CacheRecordList->InsertRecord($recArr);
      $this->m_TotalRecords++;
      $this->m_PageNumber = (int) ceil($this->m_TotalRecords / $this->m_PageRange);
   }
   
   /**
    * BizDataObj::DeleteRecord() - delete current record or delete the given input record
    * 
    * @param array $recArr - associated array whose keys are field names of this BizDataObj
    * @return boolean - if return false, the caller can call GetErrorMessage to get the error.
    **/
   // todo: throw BDOException 
   public function DeleteRecord($recArr=null, $cascadeObjNames=null)
   {
      if (!$this->CanDeleteRecord()) {
         $this->m_ErrorMessage = "You don't have permission to delete this record.";
	      return false;
	   }
	   
      if ($recArr) $this->m_BizRecord->SetInputRecord($recArr);
      else $this->m_BizRecord->SetInputRecord($this->GetRecord(0));

      // cascade delete
	   $ok = $this->CascadeDelete($cascadeObjNames);
	   if ($ok === false) {
	      $this->m_ErrorMessage = "Cascade delete error: ".$this->GetErrorMessage();
	      return false;
	   }
      
      $sql = $this->BuildDeleteSQL();
      BizSystem::log(LOG_DEBUG, "DATAOBJ", "Delete Sql = $sql");
      
      global $g_BizSystem;
      $db = $g_BizSystem->GetDBConnection();
      
      if ($db->Execute($sql) === false) {
	      $this->m_ErrorMessage = $db->ErrorMsg();
	      return false;
	   }
	   
	   $this->_PostDeleteRecord($this->m_BizRecord->GetKeyValue());
	   return true;
   }
   
   private function _PostDeleteRecord($keyVal)
   {
      if ($this->m_CacheMode==1) 
         //$this->m_CacheRecordList->DeleteRecord($this->m_BizRecord->GetKeyValue());
         $this->m_CacheRecordList->DeleteRecord($keyVal);
      $this->m_TotalRecords--;
      $this->m_PageNumber = (int) ceil($this->m_TotalRecords / $this->m_PageRange);
   }
   
   protected function CascadeDelete($cascadeObjNames=null)
   {
      // if no obj name is given, scan all object refs
	   if ($cascadeObjNames == null) {
	      $cascadeObjNames = array();
	      foreach ($this->m_ObjReferences as $objName=>$objRef) 
	         $cascadeObjNames[] = $objName;
	   }
	   global $g_BizSystem;
	   $db = $g_BizSystem->GetDBConnection();
      foreach ($cascadeObjNames as $objName) {
         $objRef = $this->m_ObjReferences->get($objName);
         if (!$objRef->m_CascadeDelete) 
            continue;
         if ($objRef->m_Relationship == "1-M" || $objRef->m_Relationship == "1-1") {
            $table = $objRef->m_Table;
            $column = $objRef->m_Column;
         }
         else if ($objRef->m_Relationship == "M-M") {
            $table = $objRef->m_XTable;
            $column = $objRef->m_XColumn1;
         }
         else continue;
         $fieldVal = $this->GetFieldValue($objRef->m_FieldRef);
         $sql = "DELETE FROM ".$table." WHERE ".$column."='".$fieldVal."'";
         if (!$fieldVal) {
   	      $this->m_ErrorMessage = "delete statement error, $sql"; 
   	      return false;
   	   }
         if ($db->Execute($sql) === false) {
   	      $this->m_ErrorMessage = $db->ErrorMsg(); 
   	      return false;
   	   }
      }
      return true;
   }
   
   /*
   public function UnformatInputRecArr(&$recArr)
   {
      global $g_BizSystem;
      // unformat the inputs
      foreach($recArr as $key=>$value) {
         $bizFld = $this->m_BizRecord->get($key);
         $recArr[$key] = $g_BizSystem->GetTypeManager()->FormattedStringToValue($bizFld->m_Type, $bizFld->m_Format, $value);
      }
   }*/

   /**
    * BizDataObj::ValidateInput() - Validate user input data
    * 
    * @return boolean
    **/
   // todo: throw BDOException 
   protected function ValidateInput()
   {
      foreach($this->m_BizRecord->m_InputFields as $fld) {
         $bizFld = $this->m_BizRecord->get($fld);
         if ($bizFld->CheckRequired() === false) {
            $this->m_ErrorMessage = BizSystem::GetMessage("ERROR", "BDO_ERROR_REQUIRED",array($fld));
            return false;
         }
         if ($bizFld->Validate() === false) {
            $this->m_ErrorMessage = BizSystem::GetMessage("ERROR", "BDO_ERROR_INVALID_INPUT",array($fld,$value,$bizFld->m_Validator));
            return false;
         }
      }
      return true;
   }
   
   /**
    * BizDataObj::SelectFieldsFrom() - Select from a field from a BizObj, then return target fields values
    * 
    * @param string $selectBizObjName - select dataobj name defined in its metadata file (attribute "SelectBizObj" of BizField)
    * @param array $recordArr - if $recordArr is null, use current record of the $selectBizObjName
    * @return string the [fld]=<val> pairs used to update edit mode fields
    **/
   public function SelectFieldsFrom($selectBizObjName, $recordArr=null)
   {
      global $g_BizSystem;
      $flds_vals = "";
      if (!$recordArr) {
         $selectBizObj = $g_BizSystem->GetObjectFactory()->GetObject($selectBizObjName);
         $recordArr = $selectBizObj->GetRecord(0);
         if (!$recordArr) return null;
      }
      
      foreach($this->m_BizRecord as $bizFld)
      {
         if ($bizFld->m_SelectBOName == $selectBizObjName)
            $flds_vals[$bizFld->m_Name] = $recordArr[$bizFld->m_SelectFldName];
      }
      return $flds_vals;
   }
   
   public function CanUpdateRecord()
   {
      if ($this->m_UpdateCondition)
         return $this->EvaluateExpression($this->m_UpdateCondition);
      return true;
   }
   
   public function CanDeleteRecord()
   {
      if ($this->m_DeleteCondition)
         return $this->EvaluateExpression($this->m_DeleteCondition);
      return true;
   }
   
   /**
    * BizDataObj::SelectFieldsFrom() - replace [field name] in the SQL expression with table_alias.column
    * 
    * @param string $sqlExpr - SQL expression supported by the database engine. The syntax is FUNC([FieldName1]...[FieldName2]...)
    * @return string real sql expression with column names
    **/
   public function ConvertSqlExpresion($sqlExpr)
   {
      global $g_BizSystem;
      $sqlstr = $sqlExpr; 
      $startpos = 0;
      while (true) {
         $fieldname = substr_lr($sqlstr,"[","]",$startpos);
         if ($fieldname == "") break;
         else {
            $bizFld = $this->m_BizRecord->get($fieldname);
            $tableColumn = $this->m_DataSqlObj->GetTableColumn($bizFld->m_Join, $bizFld->m_Column);
            $sqlstr = str_replace("[$fieldname]", $tableColumn, $sqlstr);
         }
      }
      return $sqlstr;
   }
   
   /**
    * BizDataObj::SelectFieldsFrom() - evaluate simple expression
    * expression is combination of text, simple expressiones and field variables
    * simple expression - {...}
    * field variable - [field name]
    * expression samples: text1{[field1]*10}text2{function1([field2],'a')}text3
    * 
    * @param string $expression - simple expression supported by the openbiz
    * @return mixed value
    **/
   public function EvaluateExpression($expression)
   {
      // TODO: check if it's "\[", "\]", "\{" or "\}"
      $script = "";
      $start = 0;
      
      // replace [field] with field value
      while (true) {
         $pos0 = strpos($expression, "[", $start);
         $pos1 = strpos($expression, "]", $start);
         if ($pos0 === false) {
            $script .= substr($expression, $start);
            break;
         } 
         if ($pos0 >= 0 && $pos1 > $pos0) {
            $script .= substr($expression, $start, $pos0 - $start);
            $start = $pos1 + 1;
            $fieldName = substr($expression, $pos0 + 1, $pos1 - $pos0-1);
            // get field value
            $fldval = $this->GetFieldValue($fieldName, true);
            if ($fldval)
               $script .= $fldval;
            else
               $script .= substr($expression, $pos0, $pos1 - $pos0);
         } 
         elseif ($pos0 >= 0 && $pos1 <= $pos0)
            break;
      } 
      
      $expression = $script;
      $script = "";
      $start = 0;

      global $g_BizSystem;
      // replace macro @var:key to $userProfile[$key]
      while (true) {
         $pattern = "/@(\w+):(\w+)/";
         if (!preg_match($pattern, $expression, $matches)) break;
         $macro = $matches[0];
         $macro_var = $matches[1];  $macro_key = $matches[2];
         $val = $g_BizSystem->getMacroValue($macro_var, $macro_key);
         if (!$val) $val = "";
            // throw error
         $expression = str_replace($macro, $val, $expression);
      }
      
      // evaluate the expression between {}
      while (true) {
         $pos0 = strpos($expression, "{", $start);
         $pos1 = strpos($expression, "}", $start);
         if ($pos0 === false) {
            $script .= substr($expression, $start);
            break;
         } 
         if ($pos0 >= 0 && $pos1 > $pos0) {
            $script .= substr($expression, $start, $pos0 - $start);
            $start = $pos1 + 1;
            $section = substr($expression, $pos0 + 1, $pos1 - $pos0-1);
            eval ("\$ret = $section;"); 
            $script .= $ret;
         } 
         elseif ($pos0 >= 0 && $pos1 <= $pos0)
            break;
      } 
      //eval ("\$ret = $script;"); 
      return $script;
   }
}

/**
 * TableJoin class - TableJoin defines the table join used in BizDataObj
 * 
 * @package BizDataObj
 */
class TableJoin extends MetaObject 
{
   public $m_BizObjName;
   public $m_Table;
   public $m_Column;
   public $m_JoinRef;
   public $m_ColumnRef;
   public $m_JoinType;

   function __construct(&$xmlArr, $bizObj)
   {
      $this->m_Name = $xmlArr["ATTRIBUTES"]["NAME"];
      $this->m_BizObjName = $bizObj->m_Name;
      $this->m_Package = $bizObj->m_Package;
      $this->m_Description= $xmlArr["ATTRIBUTES"]["DESCRIPTION"];
      $this->m_Table = $xmlArr["ATTRIBUTES"]["TABLE"];
      $this->m_Column = $xmlArr["ATTRIBUTES"]["COLUMN"];
      $this->m_JoinRef = $xmlArr["ATTRIBUTES"]["JOINREF"];
      $this->m_ColumnRef = $xmlArr["ATTRIBUTES"]["COLUMNREF"];
      $this->m_JoinType = $xmlArr["ATTRIBUTES"]["JOINTYPE"];
      
      $this->m_BizObjName = $this->PrefixPackage($this->m_BizObjName);
   }
}

/**
 * ObjReference class - ObjReference defines the object reference of a BizDataObj
 * 
 * @package BizDataObj
 */
class ObjReference extends MetaObject 
{
   public $m_BizObjName;
   public $m_Relationship;
   public $m_Table;
   public $m_Column;
   public $m_FieldRef;
   public $m_XTable;
   public $m_XColumn1;
   public $m_XColumn2;
   public $m_XKeyColumn;
   public $m_CascadeDelete=false;
   //public $m_Association;

   function __construct(&$xmlArr, $bizObj)
   {
      $this->m_Name = $xmlArr["ATTRIBUTES"]["NAME"];
      $this->m_BizObjName = $bizObj->m_Name;
      $this->m_Package = $bizObj->m_Package;
      $this->m_Description= $xmlArr["ATTRIBUTES"]["DESCRIPTION"];
      $this->m_Relationship = $xmlArr["ATTRIBUTES"]["RELATIONSHIP"];
      $this->m_Table = $xmlArr["ATTRIBUTES"]["TABLE"];
      $this->m_Column = $xmlArr["ATTRIBUTES"]["COLUMN"];
      $this->m_FieldRef = $xmlArr["ATTRIBUTES"]["FIELDREF"];
      $this->m_CascadeDelete = ($xmlArr["ATTRIBUTES"]["CASCADEDELETE"] == "Y");
      if ($this->m_Relationship == "M-M") {
         $this->m_XTable = $xmlArr["ATTRIBUTES"]["XTABLE"];
         $this->m_XColumn1 = $xmlArr["ATTRIBUTES"]["XCOLUMN1"];
         $this->m_XColumn2 = $xmlArr["ATTRIBUTES"]["XCOLUMN2"];
         $this->m_XKeyColumn = $xmlArr["ATTRIBUTES"]["XKEYCOLUMN"];
      }
      //$this->m_Association = $xmlArr["ATTRIBUTES"]["ASSOCIATION"];
      
      $this->m_Name = $this->PrefixPackage($this->m_Name);
      $this->m_BizObjName = $this->PrefixPackage($this->m_BizObjName);
   }
}

/**
 * BizRecord class - BizRecord implements basic function of handling record
 * 
 * @package BizDataObj
 */
class BizRecord extends MetaIterator
{
   protected $m_KeyFldColMap = array();   // todo: not support composite key, should remove it
   public $m_InputFields;
   
   function __construct(&$xmlArr, $className, $prtObj=null)
   {
      parent::__construct($xmlArr, $className, $prtObj);
      // generate column index if the column is one of the basetable (m_Column!="")
      $i = 0;
      foreach($this->m_var as $key=>$fld) {  // $key is fieldname, $fld is fieldobj
         if ($fld->m_Column || $fld->m_SqlExpression) {
            $fld->m_Index = $i++;
         }
      }
      $this->m_KeyFldColMap["Id"] = $this->m_var["Id"]->m_Column;
   }
   
   public function GetFieldByColumn($column)
   {
      foreach($this->m_var as $key=>$fld) {  // $key is fieldname, $fld is fieldobj
         if ($fld->m_Column == $column)
            return $key;
      }
      return null;
   }
   
   /**
    * BizRecord::GetEmptyRecordArr() - Get an empty record array. Called by BizDataObj::NewRecord()
    * 
    * @return array
    **/
   final public function GetEmptyRecordArr()
   {
      $recArr = array();
      foreach ($this->m_var as $key=>$fld)
         $recArr[$key] = "";  // todo: assign default value
      return $recArr;
   }
   
   /**
    * BizRecord::GetKeyValue() - Get key (Id) value. If Id is defined as composite key, the returned key value is the combination of key columns
    * 
    * @return array
    **/
   final public function GetKeyValue()
   {
      $keyValue = "";
      foreach($this->m_KeyFldColMap as $fldname=>$colname) {
         if ($keyValue == "")
            $keyValue .= $this->m_var[$fldname]->GetValue();
         else 
            $keyValue .= "#" . $this->m_var[$fldname]->GetValue();
      }
      return $keyValue;
   }
   
   /**
    * BizRecord::GetKeyFields() - Get a list of fields (name) who are defined as keys columns
    * 
    * @return array
    **/
   final public function GetKeyFields()
   {
      $keyFlds = array();
      foreach($this->m_KeyFldColMap as $fldname=>$colname) {
         $keyFlds[$fldname] = $this->m_var[$fldname];
      }
      return $keyFlds;
   }
   /*
   public function SetRecordArr($recArr)
   {
      foreach ($this->m_var as $key=>$fld)
         $recArr[$key] = $fld->SetValue($recArr[$key]);
   }*/
   
   /**
    * BizRecord::SetInputRecord() - assign a record array as the internal record of the BizRecord
    * 
    * @param array $inpuArr
    * @return void
    **/
   final public function SetInputRecord($inputArr)
   {
      global $g_BizSystem;
      // unformat the inputs
      foreach($inputArr as $key=>$value) {   // if allow changing key field, need to keep the old value which is also useful for audit trail
         $bizFld = $this->m_var[$key];
         $realVal = $g_BizSystem->GetTypeManager()->FormattedStringToValue($bizFld->m_Type, $bizFld->m_Format, $value);
         $bizFld->SetValue($realVal);  // todo: keep the old value
      }
      $this->m_var["Id"]->SetValue($this->GetKeyValue());
      $this->m_InputFields = array_keys($inputArr);
   }
   
   /**
    * BizRecord::GetRecordArr() - Get record array by converting input Column-Value array to Field-Value pairs
    * 
    * @return array
    **/
   final public function GetRecordArr($sqlArr=null)
   {
      if ($sqlArr)
         $this->SetSqlRecord($sqlArr);
      $recArr = array();
      foreach ($this->m_var as $key=>$fld)
         $recArr[$key] = $fld->GetValue();
      return $recArr;
   }
   
   private function SetSqlRecord($sqlArr)
   {
      foreach ($this->m_var as $key=>$fld)
      {
         if ($fld->m_Column || $fld->m_SqlExpression) {
            $fld->SetValue($sqlArr[$fld->m_Index]);
         }
      }
      $this->m_var["Id"]->SetValue($this->GetKeyValue());
   }
   
   /**
    * BizRecord::GetSqlArray() - Get sql record as array which is a Column-Value pairs
    * 
    * @return array
    **/
   final public function GetSqlRecord()
   {
      $sqlArr = array();
      foreach ($this->m_InputFields as $key) {
         // ignore the composite key Id field
         if ($key == "Id" && count($this->m_KeyFldColMap) > 1)
            continue;
         $fld = $this->m_var[$key];
         // do not consider joined columns
         if ($fld->m_Column && !$fld->m_Join) {
            // replace ' with \'
            $sqlArr[$fld->m_Column] = str_replace("'","\'", $fld->GetValue());
         }
      }
      return $sqlArr;
   }
}

/**
 * CacheRecordList implements the cache for BizDataObj
 * 
 * @package BizDataObj
 **/
class CacheRecordList implements iSessionObject 
{
   protected $m_BizObjName;
   public $m_SqlRecordList = array();
   protected $m_Cursor = -1;
   protected $m_RowCount = 0;
   protected $m_RowIDMap = array();
   public $EOF = true;
   
   function __construct($bizObjName)
   { 
      $this->m_BizObjName = $bizObjName;
   }

   public function GetSessionVars($sessCtxt)
   {
      $sessCtxt->GetObjVar($this->m_BizObjName, "Cache_List",  $this->m_SqlRecordList);
      $sessCtxt->GetObjVar($this->m_BizObjName, "Cache_RowIDMap",  $this->m_RowIDMap);
      $sessCtxt->GetObjVar($this->m_BizObjName, "Cache_Cursor",  $this->m_Cursor);
      $sessCtxt->GetObjVar($this->m_BizObjName, "Cache_RowCount",  $this->m_RowCount);
      $sessCtxt->GetObjVar($this->m_BizObjName, "Cache_NAR",  $this->m_NAR);
      if ($this->m_Cursor>-1) $this->SetCursor($this->m_Cursor);
   }

   public function SetSessionVars($sessCtxt)
   {
      $sessCtxt->SetObjVar($this->m_BizObjName, "Cache_List",  $this->m_SqlRecordList);
      $sessCtxt->SetObjVar($this->m_BizObjName, "Cache_RowIDMap",  $this->m_RowIDMap);
      $sessCtxt->SetObjVar($this->m_BizObjName, "Cache_Cursor",  $this->m_Cursor);
      $sessCtxt->SetObjVar($this->m_BizObjName, "Cache_RowCount",  $this->m_RowCount);
      $sessCtxt->SetObjVar($this->m_BizObjName, "Cache_NAR",  $this->m_NAR);
   }

   /**
    * CacheRecordList::GetRecord() - get the record on the current cursor
    * 
    * @return array
    **/
   public function GetRecord ()
   {
      return $this->m_SqlRecordList[$this->m_Cursor];
   }
   
   /**
    * CacheRecordList::HasRecord() - check if cache has a record whose Id is same as the Id of the input array
    * 
    * @return boolean
    **/
   public function HasRecord(&$record)
   {
      if (!$this->m_RowIDMap)
         return false;
      $fldId = $record["Id"];
      return array_key_exists($fldId, $this->m_RowIDMap);
   }

   /**
    * CacheRecordList::AppendRecord() - Append a new record into the cache
    * 
    * @param array $record
    * @return void
    **/
   public function AppendRecord (&$record)
   {
      // get id field
      $fldId = $record["Id"];

      // if this id is not found in the cache, add it to cache
      if (!$this->m_RowIDMap || !array_key_exists ($fldId, $this->m_RowIDMap))
      {
         $cacheCount = count($this->m_SqlRecordList);
         $this->m_RowCount = $cacheCount+1;
         $this->m_Cursor = $this->m_RowCount-1;
         $this->m_RowIDMap[$fldId] = $cacheCount;
         $this->m_SqlRecordList[$cacheCount] = $record;
      }
      else {
         // update the record if same id is found
         $this->m_Cursor = $this->m_RowIDMap[$fldId];
         $this->m_SqlRecordList[$this->m_Cursor] = $record;
      }
   }

   /**
    * CacheRecordList::UpdateRecord() - Append a new record into the cache
    * 
    * @param string $id
    * @param array $record
    * @return void
    **/
   public function UpdateRecord ($id, &$record)
   {
      // if this id is not found in the cache, add it to cache
      if (!array_key_exists ($id, $this->m_RowIDMap))
         $this->AppendRecord($record);
      else {
         $this->m_Cursor = $this->m_RowIDMap[$id];
         $this->m_SqlRecordList[$this->m_Cursor] = array_merge($this->m_SqlRecordList[$this->m_Cursor], $record);
      }
   }
   
   /**
    * CacheRecordList::InsertRecord() - Insert a record into the cache
    * 
    * @param array $record
    * @param boolean $before
    * @return void
    **/
   public function InsertRecord (&$record, $before=true)
   {
      if ($this->m_RowCount == 0)
      {
         $this->m_Cursor = 0;
         $this->m_RowCount = 1;
         $this->m_SqlRecordList[$this->m_Cursor] = $record;
         $this->m_RowIDMap[$record["Id"]] = 0;
         return;
      }
      if ($before == false)
      {
         array_splice ($this->m_SqlRecordList, $this->m_Cursor++, 0, "tmp");
      }
      else
      {
         array_splice ($this->m_SqlRecordList, $this->m_Cursor, 0, "tmp");
      }
      $this->m_SqlRecordList[$this->m_Cursor] = $record;
      $this->m_RowCount++;
      
      // regenerate m_RowIDMap - TODO: faster update algorithm?
      unset($this->m_RowIDMap);
      for ($i=0; $i<$this->m_RowCount; $i++)
      {
         $fldId = $this->m_SqlRecordList[$i]["Id"];
         $this->m_RowIDMap[$fldId] = $i;
      }
   }

   /**
    * CacheRecordList::DeleteRecord() - Delete the current record from cache
    * 
    * @return void
    **/
   public function DeleteRecord ($id)
   {
      if (!array_key_exists ($id, $this->m_RowIDMap)) 
         return;
      
      $this->m_Cursor = $this->m_RowIDMap[$id];
      array_splice ($this->m_SqlRecordList, $this->m_Cursor, 1);
      $this->m_RowCount--;
      
      // regenerate m_RowIDMap - TODO: faster update algorithm?
      unset($this->m_RowIDMap);
      for ($i=0; $i<$this->m_RowCount; $i++)
      {
         $fldId = $this->m_SqlRecordList[$i]["Id"];
         $this->m_RowIDMap[$fldId] = $i;
      }
   }

   /**
    * CacheRecordList::GetRowCount() - Get the number of row in the cache
    * 
    * @return integer
    **/
   public function GetRowCount() { return $this->m_RowCount; }
   
   /**
    * CacheRecordList::SetCursor() - Set the cache cursor to specific position
    * 
    * @param integer $pos
    * @return void
    **/
   public function SetCursor ($pos)
   {
      if ($pos > $this->m_RowCount-1) {
         $this->m_Cursor = $this->m_RowCount-1;
         $this->EOF = true;
      }
      else if ($pos < 0)
         $this->m_Cursor = 0;
      else {
         $this->m_Cursor = $pos;
         $this->EOF = false;
      }
   }
   /**
    * CacheRecordList::MoveCursor() - Move cursor of the cache
    * 
    * @param integer $i steps moved - can be minus number
    * @return boolean
    **/
   public function MoveCursor ($i)
   {
      $pos = $this->m_Cursor + $i;
      $this->SetCursor($pos);
   }
}

/**
 * substr_lr() - help function. Get the sub string whose left and right boundary character is $left and $right.
 * The search is in $str, starting from position of $startpos. If $findfirst is true, $left must be the charater on the $startpos.
 * 
 * @return string
 **/
function substr_lr(&$str,$left,$right,&$startpos,$findfirst=false)
{
   $pos0 = strpos($str, $left, $startpos);
   if ($pos0 === false) return false;
   $tmp = trim(substr($str,$startpos,$pos0-$startpos));
   if ($findfirst && $tmp!="") return false;
   
   $posleft = $pos0+strlen($left);
   while(true) {
      $pos1 = strpos($str, $right, $posleft);
      if ($pos1 === false) {
         if (trim($right)=="") {
            $pos1 = strlen($str); // if right is whitespace
            break;
         }
         else return false;
      }
      else {   // avoid \$right is found
         if (substr($str,$pos1-1,1) == "\\")  $posleft = $pos1+1;
         else break;
      }
   }

   $startpos = $pos1 + strlen($right);
   $retStr = substr($str, $pos0 + strlen($left), $pos1-$pos0-strlen($left));
   return $retStr;
}
/*
function trim_lr(&$str,$left,$right)
{
   $retstr = substr_lr($str,$left,$right);
   if (!$retstr) return $str;
   return $retstr;
}
*/
?>