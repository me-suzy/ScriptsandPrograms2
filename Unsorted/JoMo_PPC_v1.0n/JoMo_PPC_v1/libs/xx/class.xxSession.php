<?PHP
	if(!class_exists("xxObject")) user_error("Class 'xxObject' not found", E_USER_ERROR);
	class xxSession extends xxObject{
		/** Session name */
		var $__name;
		/** Session handle */
		var $__handle="SESSION";
		/** Session variables array */
		var $__variables=array();
		/** object xxSession($name) - constructor */
		function xxSession($name=null){
			session_set_cookie_params(0);
			static $start=false;
			if($start) $this->setError("Session is already");
			if(isset($name) && !empty($name)) session_name($name);
			$this->__name=session_name();
			if(!session_start()) $this->setError("Session is failed");
			session_register($this->__handle);
			$this->__variables=&$GLOBALS[$this->__handle];
			$start=true;
		}
		/** void close() - close session */
		function close(){
			session_destroy();
		}
		/** void assign($variables, $value) - assign variable in session data */
		function assign($variables, $value=null){
			if(is_array($variables)){
				foreach($variables as $key=>$value) $this->__variables[$key]=$value;
			} else $this->__variables[$variables]=$value;
		}
		/** void unassign($variables) - unassign variable from session data */
		function unassign($variables){
			if(is_array($variables)) {
				foreach($variables as $k=>$v) unset($this->__variables[$k]);
			} else unset($this->__variables[$variables]);
		}
		/** bool assigned($variable) - checking variable in session data */
		function assigned($variable){
			return(isset($this->__variables[$variable]) && !empty($this->__variables[$variable]));
		}
		/** void clear() - clear all variables from session data */
		function clear(){
			$this->__variables=array();
		}
		/** variant fetch($variable) - get variable from session data */
		function fetch($variable){
			return $this->__variables[$variable];
		}
		/** string getSessionId() - get session id */
		function getSessionId(){
			return session_id();
		}
		/** string getSessionName() - get session name */
		function getSessionName(){
			return session_name();
		}
	}
?>