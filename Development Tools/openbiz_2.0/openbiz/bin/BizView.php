<?PHP
/**
 * BizView class - BizView is the class that contains list of forms. View is same as html page.
 * 
 * @package BizView
 * @author rocky swen 
 * @copyright Copyright (c) 2005
 * @access public 
 */
class BizView extends MetaObject implements iSessionObject 
{
   protected $m_Template;
   protected $m_ChildFormList = array();
   protected $m_MetaChildFormList = array();
   protected $m_ViewAccessFlag;
   protected $m_ViewAccessControl;
   protected $m_IsPopup = false;
   protected $m_Height;
   protected $m_Width;
   protected $m_ConsoleOutput = true;

   /**
    * BizView::__construct(). Initialize BizView with xml array
    * 
    * @param array $xmlArr
    * @return void 
    */
   public function __construct(&$xmlArr)
   {
      global $g_BizSystem;

      $this->ReadMetadata($xmlArr);
      
      $this->ValidateAccess($this->m_ViewAccessControl);    // check view access and set ViewAccessFlag
      
      // build forms included in the view
      foreach($this->m_MetaChildFormList as $form) {
         $pkg_form = $this->PrefixPackage($form["FORM"]);
         $formobj = $g_BizSystem->GetObjectFactory()->GetObject($pkg_form);
         $formobj->SetAccessFlag($this->m_ViewAccessFlag);
         $formobj->SetSubForms($form["SUBCTRLS"]);
         //$formobj->SetDependency($form["DEPENDENCY"]);
         $this->AddChildForm($formobj);
      } 
      foreach($this->m_ChildFormList as $formobj)
      {
         $subForms = $formobj->GetSubForms();
         if ($subForms) {
            foreach ($subForms as $subformName) {
               $this->m_ChildFormList[$subformName]->SetParentForm($formobj->m_Name);
            }
         }
      }
   }
   
   protected function ReadMetadata(&$xmlArr)
   {
      $this->m_Name = $xmlArr["BIZVIEW"]["ATTRIBUTES"]["NAME"];
      $this->m_ViewAccessControl = $xmlArr["BIZVIEW"]["ATTRIBUTES"]["ACCESSCONTROL"];
      $this->m_Description = $xmlArr["BIZVIEW"]["ATTRIBUTES"]["DESCRIPTION"];
      $this->m_Package = $xmlArr["BIZVIEW"]["ATTRIBUTES"]["PACKAGE"];
      if ($this->m_Package)
         $this->m_Name = $this->m_Package.".".$this->m_Name;
      $this->m_Class = $xmlArr["BIZVIEW"]["ATTRIBUTES"]["CLASS"];
      $this->m_Template = $xmlArr["BIZVIEW"]["ATTRIBUTES"]["TEMPLATE"];
      
      // build ControlList
      $this->ReadMetaCollection($xmlArr["BIZVIEW"]["CONTROLLIST"]["CONTROL"], $tmpList);
      if (!$tmpList)
         return;
      foreach ($tmpList as $ctrl)
         $this->m_MetaChildFormList[] = $ctrl["ATTRIBUTES"];
   }
   
   /**
    * BizView::GetSessionContext() - Retrieve Session data of this object
    * 
    * @param SessionContext $sessCtxt
    * @return void 
    */
   public function GetSessionVars($sessCtxt)
	{
	}
	/**
    * BizView::SetSessionContext() - Save Session data of this object
    * 
    * @param SessionContext $sessCtxt
    * @return void 
    */
	public function SetSessionVars($sessCtxt)
	{
	}
	
	public function CleanViewHistory()
	{
	   global $g_BizSystem;
	   foreach($this->m_ChildFormList as $ctrl)
	   {
	      $ctrl->CleanHistoryInfo();
	   }
	}
	
	public function SetConsoleOutput($bConsoleOutput)
	{
	   $this->m_ConsoleOutput = $bConsoleOutput;
	} 
	
	protected function AddChildForm($ctrl)
	{
	   $this->m_ChildFormList[$ctrl->m_Name] = $ctrl;
	}
	
	/**
    * BizView::SetPopupSize() - Set the view as a popup window and set its size
    * 
    * @param integer $w, window width
    * @param integer $h, window height
    * @return void 
    */
	public function SetPopupSize($w,$h)
   {
      $this->m_IsPopup = true;
      $this->m_Width = $w;
      $this->m_Height = $h;
   }
   
   public function SetFormMode($form="", $mode="")
   {
      if (!$form || !$mode)
         return;
      $bizForm = $this->m_ChildFormList[$form];
      $bizForm->SetDisplayMode($mode);
   }
   
   /**
    * BizView::ProcessRule() - Convert rule to search rule of bizform
    * 
    * @param string $rule. It can be "[field] opr value ..." OR form.ctrl opr value
    *        opr can be =,>,>=,<,<=,!=. "LIKE %" SQL format is also valid rule
    * @return void 
    */
   public function ProcessRule($form="", $rule="")
   { 
      // convert \' to '
      $addSearchRule = str_replace("\'", "'", $rule);
      
      // case 1: form=... $rule=[field] opr value
      if ($form) {
         $bizForm = $this->m_ChildFormList[$form];
         if ($rule) {
            // set dependent search rule which is remembered in the session
            $bizForm->SetFixSearchRule($addSearchRule);
         } 
         return;
      }
      
      // case 2: form.ctrl opr value
      if ($rule) {
         // replace package.form.ctrl with [field]
         // search for all child forms to match form name.
         foreach ($this->m_ChildFormList as $bizForm)
         {
            $bFind = false;
            $formName = $bizForm->m_Name;
            while (preg_match("/$formName\.[a-zA-Z0-9_]+/i",$addSearchRule,$matches))
            {
               $match = $matches[0];
               $ctrlName = substr($match, strlen($formName)+1);
               $ctrlFieldName = $bizForm->GetControl($ctrlName)->m_BizFieldName; // not ctrl_id
               $addSearchRule = str_replace($match,"[".$ctrlFieldName."]",$addSearchRule);
               $bFind = true;
            }
            if ($bFind) {
               //echo "###".$bizForm->m_Name.",".$addSearchRule;
               // set dependent search rule which is remembered in the session
               $bizForm->SetFixSearchRule($addSearchRule);
            }
         }
      }
   } 
   
	/**
    * BizView::Render() - Render this view.
    *
    * @return void
    */
	public function Render()
	{
      $smarty = BizSystem::GetSmartyTemplate();
      
      // todo: shoudl enforce rendering parent form before rendering subforms, 
      // because subform's dataobj is a objreference of the parent dataobj.
      foreach ($this->m_ChildFormList as $form=>$formobj) {
         $sHTML = $formobj->Render();
         $controls[] = "<div id='" . $formobj->m_Name . "_container'>" . $sHTML . "</div>";
         $newClntObjs .= "NewObject('" . $formobj->m_Name . "','" . $formobj->m_jsClass . "'); ";
         // set selectedRow
         $newClntObjs .= "var fobj=GetObject('".$formobj->m_Name."');";
      }
      if ($this->m_IsPopup) {
         $moveToCenter = "moveToCenter(self, ".$this->m_Width.", ".$this->m_Height.");";
         $scripts = "<script>" . $newClntObjs . $moveToCenter . "</script>";
      }
      else 
         $scripts = "<script>" . $newClntObjs . "</script>";
      $smarty->assign("scripts", $scripts);
      $smarty->assign_by_ref("view_description", $this->m_Description); 
      
      $smarty->assign_by_ref("controls", $controls); 
      if ($this->m_ConsoleOutput)
         $smarty->display($this->m_Template);
      else
         return $smarty->fetch($this->m_Template);
	}
	
	/**
    * BizView::ValidateAccess - Validate the view access level 0-deny, 1-read, 2-write
    * 
    * @param string $accessControl with format as Class.Method
    * @return void 
    */
   protected function ValidateAccess($accessControl=null)
   {
      if ($accessControl == null)
         return;

      if (strlen($accessControl)==1) {
         $permission = intval($accessControl);
      }
      else {
         $pos = strpos($accessControl, ".");
         $class = null;
         $method = null;
         if ($pos>0) {
            $class = trim(substr($accessControl, 0, $pos));
            $method = trim(substr($accessControl, $pos+1, strlen($accessControl)));
         }
         $permission = 2;  // default write, full control
         if ($class && $method)
         {
            global $g_BizSystem;
            $classFile= $g_BizSystem->GetLibFileWithPath($class);
            if (!$classFile) {
               $errmsg = BizSystem::GetMessage("ERROR", "SYS_ERROR_CLASSNOTFOUND",array($class));
               trigger_error($errmsg, E_USER_ERROR);
            }
            else {
               include_once($classFile);
               $obj = new $class($this->m_Name);
               if (method_exists($obj, $method))
                  $permission = $obj->$method($this->m_Name);
            }
         }
      }
      if ($permission == 0)
      {
         include_once(SMARTY_DIR."/Smarty.class.php");
         $smarty = new Smarty;
         $smarty->assign("error_title", "Permission Denied");
         $error_msg = "You do not have permission to access ".$this->m_Name;
         $error_msg .= "<FORM><INPUT type=button value=\" Back \" onClick=\"history.back();\"></FORM>";
         $smarty->assign("error_message", $error_msg);
         $smarty->display("viewdenied.tpl");
         exit;
      }
      $this->m_ViewAccessFlag = $permission;
      return;
   }
}

?>