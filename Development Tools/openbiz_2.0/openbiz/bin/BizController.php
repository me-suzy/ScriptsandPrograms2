<?PHP

ob_start();
include_once("sysheader.inc");

$bizCtrller = new BizController();
$bizCtrller->DispatchRequest();

/**
 * BizController class - BizController is the class that dispatches client requests to proper objects
 * 
 * @package BizController
 * @author  
 * @copyright Copyright (c) 2005
 * @access public 
 */
class BizController
{
   private $m_UserTimeoutView = "shared.UserTimeoutView";
   private $m_AccessDeniedView = "shared.AccessDeniedView";
   /**
    * BizController::DispatchRequest() - dispatches client requests to proper objects, print the returned html text.
    * 
    * @return void
    */
   public function DispatchRequest()
   {
      $profile = $this->GetUserProfile();
      if ($this->CheckSessionTimeout($profile))  // show timeout view
      {
         global $g_BizSystem;
         $g_BizSystem->GetSessionContext()->Destroy();
         return $this->RenderView($this->m_UserTimeoutView);
      }
      
      // ?view=...&form=...&rule=...&mode=...&...
      // ?vw=...&fm=...&rl=...&md=...&...
      $getKeys = array_keys($_GET);
      if ($getKeys[0] == "view") {
         $form = isset($_GET['form']) ? $_GET['form'] : "";
         $rule = isset($_GET['rule']) ? $_GET['rule'] : "";
         $viewName = $_GET['view'];
         if (!$this->CheckViewAccess($viewName, $profile))  //access denied error
            return $this->RenderView($this->m_AccessDeniedView);
         return $this->RenderView($viewName,$form,$rule);
      }

      $retval = $this->Invoke();

      print($retval);
      exit();
   }
   
   // get user profile array. "USERID", "ROLE" ...
   private function GetUserProfile()
   {
      global $g_BizSystem;
      return $g_BizSystem->GetUserProfile();
   }
   // check if session timed out. true - session timed out, false - session alive
   private function CheckSessionTimeout($profile)
   {
      global $g_BizSystem;
      if (isset($profile["USERID"]) && $profile["USERID"] != "")
         return $g_BizSystem->GetSessionContext()->IsTimeout();
      return false;
   }   
   // role-base view access control
   private function CheckViewAccess($viewName, $profile)
   {
      // load accessService
      global $g_BizSystem;
      $svcobj = $g_BizSystem->GetService("accessService");
      $role = isset($profile["ROLE"]) ? $profile["ROLE"] : null;
      return $svcobj->AllowViewAccess($viewName, $role);
   }
   
   /**
    * BizController::RenderView() - render a bizview
    * 
    * @param string $viewName name of bizview
    * @param string $rule the search rule of a bizform who is not depent on (a subctrl of) another bizform 
    * @return void 
    */
   public function RenderView($viewName, $form="", $rule="", $hist="")
   {
      global $g_BizSystem;
      
      // if previous view is different with the to-be-loaded view, clear the previous session objects
      $prevView = $g_BizSystem->GetCurrentViewName();
      //if ($viewName != $prevView) {
      if ($viewName != "__DynPopup") {  // keep view session when there's popup
         $g_BizSystem->GetSessionContext()->ClearSessionObjects();
         $g_BizSystem->SetCurrentViewName($viewName);
      }
      
      $viewobj = $g_BizSystem->GetObjectFactory()->GetObject($viewName);
      if($viewobj) {
         if ($hist == "N") // clean view history 
            $viewobj->CleanViewHistory();
         $viewobj->ProcessRule($form, $rule);
         if ($_GET['mode'])   // can specify mode of form
            $viewobj->SetFormMode($form, $_GET['mode']);
         $viewobj->Render();
      }
   }
   
   /**
    * BizController::Invoke() - render a bizview
    * 
    * @return HTML text 
    */
   protected function Invoke()
   {
      $func = (isset($_REQUEST['F']) ? $_REQUEST['F'] : "");
      $arg_list = array();
      $i = 0;
      if ($func != "") {
         eval("\$P$i = (isset(\$_REQUEST['P$i']) ? \$_REQUEST['P$i']:'');");
         $Ptmp = "P". $i;

         while ($$Ptmp!="") {
            $parm = $$Ptmp;
            $parm = substr($parm,1,strlen($parm)-2);
            $arg_list[] = $parm;
            $i++;
            eval("\$P$i = (isset(\$_REQUEST['P$i']) ? \$_REQUEST['P$i']:'');");
            $Ptmp = "P". $i;
         }
      }
      else 
         return;
      
      global $g_BizSystem;
      if ($func != "RPCInvoke" && $func != "Invoke") {
         trigger_error("$func is not a valid invocation", E_USER_ERROR);
         return;
      }
      if ($func == "RPCInvoke")
         $g_BizSystem->GetClientProxy()->SetRPCFlag(true);
      
      // save formdata to ClientProxy
      $formdata = (isset($_REQUEST['__FormData']) ? $_REQUEST['__FormData'] : "");
      $formdata = substr($formdata,1,strlen($formdata)-2);
      if ($formdata != "") {
         $g_BizSystem->GetClientProxy()->SetFormInputData($formdata);
      }
      
      // invoke the function
      $num_arg = count($arg_list);
      if ($num_arg<2) {
         $errmsg = BizSystem::GetMessage("ERROR", "SYS_ERROR_RPCARG",array($class));
         trigger_error($errmsg, E_USER_ERROR);
      }
      else
      {
         $objName = $arg_list[0];
         $methodName = $arg_list[1];
         array_shift($arg_list); array_shift($arg_list); // remove the first 2 args
         $obj= $g_BizSystem->GetObjectFactory()->GetObject($objName);

         if ($obj)
         {
            if (method_exists($obj, $methodName)) {
               $rt_val = call_user_func_array(array(&$obj, $methodName),$arg_list);
            }
            else {
               $errmsg = BizSystem::GetMessage("ERROR", "SYS_ERROR_METHODNOTFOUND",array($objName,$methodName));
               trigger_error($errmsg, E_USER_ERROR);
            }
         }
         else {
            $errmsg = BizSystem::GetMessage("ERROR", "SYS_ERROR_CLASSNOTFOUND",array($objName));
            trigger_error($errmsg, E_USER_ERROR);
         }

         return $rt_val;
      }
   }
}
?>