<?PHP
	class xxObject {
		/** void setError($errorMessage, $errorType) - set error messages */
		function setError($errorMessage, $errorType=E_USER_ERROR) {
			user_error($errorMessage, $errorType);
		}
	}
?>