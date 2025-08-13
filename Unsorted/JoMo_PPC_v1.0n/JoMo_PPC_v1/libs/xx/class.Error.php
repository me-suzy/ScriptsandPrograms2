<?PHP

	define("__CFG_NO_ERROR", 0);
	define("__CFG_ERROR", 1);
	define("__CFG_WARNING", 2);
	define("__CFG_ALL_ERROR", 3);

	/** Turn on all error messages */
	error_reporting(E_ALL);
	/** Set new error handler */
	set_error_handler("errorHandler");
	/** Create new error handler */

	function errorHandler($errNo, $errMsg, $errFile, $errLine) {
		global $Error;
		if (!isset($Error)) {
			$Error = new Error();
		}
		$Error->errorHandler($errNo, $errMsg, $errFile, $errLine);
	}
		
	class Error {
		
		var $__debug = 0;
		var $__default_MailError = __CFG_ALL_ERROR; 
		var $__default_LogError = __CFG_ALL_ERROR; 
		var $__default_DisplayError = __CFG_NO_ERROR; 
		var $__MailError; 
		var $__LogError; 
		var $__DisplayError; 
		var $__ErrorsEmails = array("you@youdomain.com");
		var $__SupportExpired;
		
		var $__FilterPatterns = array(
				array("Permission denied", "Smarty.class.php"),
				array("problem writing", "Smarty.class.php"),
				array("Undefined offset: 0", "Smarty.class.php")		
		);
		
		function Error($SupportExpired = 0) {
			$this->__SupportExpired = ($SupportExpired) ? $SupportExpired : mktime(0,0,0,12,1,2002);
			if (defined("__CFG_SCRIPT_STATUS")) {
				switch (strtolower(__CFG_SCRIPT_STATUS)) {
					case "debug" :
						$this->__debug = 1;
						$this->__default_MailError = __CFG_NO_ERROR; 
						$this->__default_LogError = __CFG_ALL_ERROR; 
						$this->__default_DisplayError = __CFG_ALL_ERROR; 
						break;
					case "develop" :
						$this->__default_MailError = __CFG_NO_ERROR; 
						$this->__default_LogError = __CFG_ALL_ERROR; 
						$this->__default_DisplayError = __CFG_ALL_ERROR; 
						break;
					case "work" :
						$this->__default_MailError = __CFG_ERROR; 
						$this->__default_LogError = __CFG_NO_ERROR; 
						$this->__default_DisplayError = __CFG_NO_ERROR; 
						break;
					case "forsale" :
						$this->__default_MailError = __CFG_ERROR; 
						$this->__default_LogError = __CFG_NO_ERROR; 
						$this->__default_DisplayError = __CFG_NO_ERROR; 
						break;
					case "install" :
						$this->__default_MailError = __CFG_ALL_ERROR; 
						$this->__default_LogError = __CFG_ALL_ERROR; 
						$this->__default_DisplayError = __CFG_NO_ERROR; 
						break;
					default:
						break;
				}
			}
			$this->restore();
		}
		
		function restore() {
			$this->__MailError =$this->__default_MailError;  
			$this->__LogError = $this->__default_LogError; 
			$this->__DisplayError = $this->__default_DisplayError; 
		}
		
		function silence() {
			$this->__MailError =__CFG_NO_ERROR;  
			$this->__LogError = __CFG_NO_ERROR; 
			$this->__DisplayError = __CFG_NO_ERROR; 
		}

		function DisplayDump() {
			echo "<PRE><FONT color=\"#000099\">" . $this->__GenerateVarsDump() . "</FONT></PRE>";
		}
		
		function MailDump() {
			global $SERVER_NAME, $SCRIPT_NAME;
			$body = "URL: $SERVER_NAME$SCRIPT_NAME\n".
					"Date: " . date("r") . "\n".
					"Dump: \n";
			$body .= $this->__GenerateVarsDump();
			$subject = "Variables Dump";
			foreach ($this->__ErrorsEmails as $email) 
				mail($email, $subject, $body, "From: Error Reporting Sysyem <errors@$SERVER_NAME>");	
		}
		
		function setErrorEmail($emails, $clear = true){
			if ($clear)
				$this->__ErrorsEmails = array();
			if (is_array($emails)){
				foreach($emails as $email)
					$this->__ErrorsEmails[] = $email;
			}
			else{
				$this->__ErrorsEmails[] = $emails;
			}
				
		}
		
		function errorHandler($errNo, $errMsg, $errFile, $errLine) {
			if ($this->__Filter($errNo, $errMsg, $errFile, $errLine)) 
				return;
			
			if ($this->__SupportExpired > time()) {
				$this->__MailError >= __CFG_NO_ERROR;
			}

			$error=array(
				E_ERROR           => "Fatal Error",
				E_WARNING         => "Warning",
				E_NOTICE          => "Notice",
				E_PARSE           => "Parse Error",
				E_CORE_ERROR      => "Core Error",
				E_CORE_WARNING    => "Core Warning",
				E_COMPILE_ERROR   => "Compile Error",
				E_COMPILE_WARNING => "Compile Warning",
				E_USER_ERROR      => "Critical Error",
				E_USER_WARNING    => "Warning",
				E_USER_NOTICE     => "Notice"
			);
			switch($errNo) {
				case E_PARSE:
				case E_ERROR:
				case E_CORE_ERROR:
				case E_COMPILE_ERROR:
				case E_USER_ERROR:
					if ($this->__MailError >= __CFG_ERROR)
						$this->__MailError($error[$errNo], $errMsg, $errFile, $errLine);
					if ($this->__LogError >= __CFG_ERROR)
						$this->__LogError($error[$errNo], $errMsg, $errFile, $errLine);
					if ($this->__DisplayError >= __CFG_ERROR)
						$this->__DisplayError($error[$errNo], $errMsg, $errFile, $errLine);
					else 
						$this->__DisplayExitMessage();
					exit;
					
				case E_WARNING:
				case E_CORE_WARNING:
				case E_COMPILE_WARNING:
				case E_USER_WARNING:
					if ($this->__MailError >= __CFG_WARNING)
						$this->__MailError($error[$errNo], $errMsg, $errFile, $errLine);
					if ($this->__LogError >= __CFG_WARNING)
						$this->__LogError($error[$errNo], $errMsg, $errFile, $errLine);
					if ($this->__DisplayError >= __CFG_WARNING)
						$this->__DisplayError($error[$errNo], $errMsg, $errFile, $errLine);
					break;
					
				case E_NOTICE:
				case E_CORE_NOTICE:
				case E_COMPILE_NOTICE:
				case E_USER_NOTICE:
					if ($this->__MailError >= __CFG_ALL_ERROR)
						$this->__MailError($error[$errNo], $errMsg, $errFile, $errLine);
					if ($this->__LogError >= __CFG_ALL_ERROR)
						$this->__LogError($error[$errNo], $errMsg, $errFile, $errLine);
					if ($this->__DisplayError >= __CFG_ALL_ERROR)
						$this->__DisplayError($error[$errNo], $errMsg, $errFile, $errLine);
					break;
					
			}
		}

		function __GenerateVarsDump() {
			ob_start();
			$arrays2show = array("_SERVER", "_ENV", "_COOKIE", "_REQUEST", "_SESSION");
			foreach($arrays2show as $var) {
				@eval("\$ar = $$var;");
				if (isset($ar)) {
					echo "$var:\n";
					print_r($ar);
				} else {
					echo "$var undefined.\n";
				}
			}
			$dump = ob_get_contents();
			ob_end_clean();
			return $dump;
		}
		
		function __DisplayExitMessage() {
			$message="<HTML><HEAD><TITLE>Fatal Error Page</TITLE></HEAD><BODY>" .
				"<BR><TABLE WIDTH=200 ALIGN=CENTER STYLE=\"font-family: Verdana, Helvetica, Arial, Sans-Serif;font-size:12px;color:#FF0000;\">".
				"<TR><TD ALIGN=CENTER>".
				"Unfortunetly error is occure.<BR>".
				"Please try access later".
				"</TD></TR></TABLE>";
			echo $message;
		}
		
		function __MailError($errorHeader, $errMsg, $errFile, $errLine) {
			global $SERVER_NAME, $SCRIPT_NAME, $ErrorsEmails;
			$body = "URL: $SERVER_NAME$SCRIPT_NAME\n".
					"Date: " . date("r") . "\n".
					"$errorHeader\n".
					"Text: $errMsg\n".
					"File: $errFile\n".
					"Line: $errLine\n";
			if ($this->__debug) {
				$body .= $this->__GenerateVarsDump();
			}
			$subject = "$errorHeader: $errMsg";
			foreach ($this->__ErrorsEmails as $email) 
				mail($email, $subject, $body, "From: Error Reporting Sysyem <errors@$SERVER_NAME>");	
		}
		
		function __DisplayError($errorHeader, $errMsg, $errFile, $errLine) {
			$message="<PRE style=\"font-size:12px;color:#FF0000;\">".
				"<U><STRONG>%s:</STRONG></U><BR>".
				"  Text: <FONT color=\"#009900\">%s</FONT><BR>".
				"  File: <FONT color=\"#009900\">%s</FONT><BR>".
				"  Line: <FONT color=\"#009900\">%s</FONT><BR></PRE>";
			printf($message, $errorHeader, $errMsg, $errFile, $errLine);
			if ($this->__debug) {
				$this->DisplayDump();
			}
		}
		
		function __LogError($errorHeader, $errMsg, $errFile, $errLine) {
			global $SERVER_NAME, $SCRIPT_NAME;
			if (defined("__CFG_LOG_DIR")) {
				$txt =  @fopen(__CFG_LOG_DIR . "log.txt", "a+");
				$html = @fopen(__CFG_LOG_DIR . "log.html", "a+");
			} else {
				$txt =  0;
				$html = 0;
			}
			if ($txt) {
				$message = "$errorHeader:\n".
					"  Url : $SERVER_NAME$SCRIPT_NAME\n".
					"  Date: " .date("r") . "\n".
					"  Text: $errMsg\n".
					"  File: $errFile\n".
					"  Line: $errLine\n\n";
				if ($this->__debug) {
					$message .= $this->__GenerateVarsDump();
				}
				fwrite($txt, $message);
			}
			if ($html) {
				$message = "<PRE style=\"font-size:12px;color:#FF0000;\">".
					"<U><STRONG>$errorHeader:</STRONG></U>\n".
					"  Url : <FONT color=\"#009900\">$SERVER_NAME$SCRIPT_NAME</FONT>\n".
					"  Date: <FONT color=\"#009900\">" .date("r") . "</FONT>\n".
					"  Text: <FONT color=\"#009900\">$errMsg</FONT>\n".
					"  File: <FONT color=\"#009900\">$errFile</FONT>\n".
					"  Line: <FONT color=\"#009900\">$errLine</FONT></PRE>\n";
				if ($this->__debug) {
					$message .= $this->__GenerateVarsDump();
				}
				fwrite($html, $message);
			}		
		}
		function __Filter($errNo, $errMsg, $errFile, $errLine) {
			foreach ($this->__FilterPatterns as $pat) {
				if (preg_match("/" . preg_quote($pat[0]) . "/",$errMsg) && preg_match("/" . preg_quote($pat[1]) . "/",$errFile)) {
					return 1;
				}
			}

			return 0;
		}
	}
?>