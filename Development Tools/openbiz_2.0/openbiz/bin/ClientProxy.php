<?PHP

/**
 * ClientProxy class - a class that is treated as the bi-direction proxy of client. Through this class, 
 * others can get client form inputs, redraw client form or call client javascript functions.
 * 
 * @package BizSystem
 * @author rocky swen 
 * @copyright Copyright (c) 2005
 * @access public 
 */
class ClientProxy
{
   protected $m_RequestArgs;
   protected $m_FormInputArray;
   protected $m_bRPCFlag = false;
   
   public function SetRPCFlag($flag)
   {
      $this->m_bRPCFlag = $flag;
   }
   
   /**
    * ClientProxy::GetRequestParam() - get the client form data passed by GET or POST
    * 
    * @param string $name
    * @return string
    */
   public function GetRequestParam($name)
   {
      $val = (isset($_REQUEST[$name]) ? $_REQUEST[$name] : "");
      return $val;
   }
   
   /**
    * ClientProxy::SetFormInputData() - called by BizController to parse and save the client form data
    * 
    * @param string $formdata
    * @return void
    */
   public function SetFormInputData($formdata)
   {
      $input_array = explode("^-^-^", $formdata);
      foreach($input_array as $kvpair) {
         $pos = strpos($kvpair, "=");
         $field = substr($kvpair, 0, $pos);
         $value = substr($kvpair, $pos+1, strlen($kvpair)-$pos);
         if ($field) {
            $value = str_replace("%2B","+",$value);
            $value = str_replace("\\'","'",$value);
            $this->m_FormInputArray[$field] = $value;
         }
      }
   }
   
   /**
    * ClientProxy::GetFormInputs() - get form all inputs or one input if ctrlName is given
    * 
    * @param string $ctrlName
    * @return array or string
    */
   public function GetFormInputs($ctrlName=null)
   {
      if ($ctrlName)
         return $this->m_FormInputArray[$ctrlName];
      else 
         return $this->m_FormInputArray;
   }
   
   /**
    * ClientProxy::UpdateFormElements() - update the form controls on the client UI
    * 
    * @param string $formName - name of the html form on client
    * @param array $recArr - name/value pairs
    * @return array or string
    */
   public function UpdateFormElements($formName, &$recArr)
   {
      $rtString = "UPD_FLDS";
      foreach($recArr as $fld=>$val) 
         $rtString .= "[".$fld."]=<".$val.">";
      return $this->_buildTgtCtnt($formName, $rtString); 
   }
   /**
    * ClientProxy::ReDrawForm() - replace the form content with the provided html text
    * 
    * @param string $formName - name of the html form on client
    * @param string $sHTML - html text to redraw the form
    * @return string - encoded html string returns to browser, it'll be processed by client javascript.
    */
   public function ReDrawForm($formName, &$sHTML)
   {
      if ($this->m_bRPCFlag)
         return $this->_buildTgtCtnt($formName, $sHTML); 
   }
   /**
    * ClientProxy::ShowClientAlert() - popup an alert window on the browser
    * 
    * @param string $alertText
    * @return string - encoded html string returns to browser, it'll be processed by client javascript.
    */
   public function ShowClientAlert($alertText)
   {
      $msg = str_replace("'", "\'", $alertText);
      if ($this->m_bRPCFlag)
         return $this->CallClientFunction("alert('$msg')");
   }
   
   public function ShowErrorMessage($errMsg)
   {
      $msg = str_replace("'", "\'", $errMsg);
      if ($this->m_bRPCFlag)
         return $this->CallClientFunction("alert('$msg')");
      else {
         echo "Error message: <font color=maroon>$errMsg</font>";
         //BizSystem::ErrorBacktrace();
      }
   }
   
   public function ShowErrorPopup($errMsg)
   {
      $msg = str_replace("\\", "\\\\", $errMsg);
      $msg = str_replace("'", "\'", $msg);
      
      if ($this->m_bRPCFlag)
         return $this->CallClientFunction("popupErrorText('$msg')");
      else {
         echo $errMsg;
         //BizSystem::ErrorBacktrace();
      }
   }
   
   public function ClosePopup()
   {
      if ($this->m_bRPCFlag)
         return $this->CallClientFunction("close()");
   }
   
   protected function CallClientFunction($funcStr)
   {
      if ($this->m_bRPCFlag)
         return $this->_buildTgtCtnt("FUNCTION", $funcStr); 
   }
   
   /**
    * BuildTgtCtnt()
    * build target-content string with target str and content string as inputs. After RPC call, this function is usually called to
    * set the HTML text to an UI element.
    * 
    * @param string $tgt the HTML element id, i.e. the applet HTML id
    * @param string $ctnt the HTML text to be set as the content of the HTML element referred with the id
    * @return string
    **/
   private function _buildTgtCtnt($tgt, &$ctnt)
   {
      $tmpStr = "### TARGET:".strlen($tgt).":".$tgt.";";
      $tmpStr .= "CONTENT:".strlen($ctnt).":".$ctnt;

      return $tmpStr;
   }
   
   /**
    * ClientProxy::RedirectPage() - redirect page to the given url
    * 
    * @param string $pageURL
    * @return string - encoded html string returns to browser, it'll be processed by client javascript.
    */
   public function RedirectPage($pageURL)
   {
      return $this->CallClientFunction("RedirectPage('$pageURL')"); 
   }
   /**
    * ClientProxy::RedirectView() - redirect page to the given view
    * 
    * @param string $view
    * @return string - encoded html string returns to browser, it'll be processed by client javascript.
    */
   public function RedirectView($view)
   {
      return $this->CallClientFunction("GoToView('$view','')"); 
   }
}

?>