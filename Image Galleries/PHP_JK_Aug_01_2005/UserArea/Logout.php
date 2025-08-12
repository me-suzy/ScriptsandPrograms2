<?php
	require("../Includes/i_Includes.php");
	
	DB_OpenDomains();
	INIT_LoginDetect();
	
	$sReferer = "";
	
	If ( isset($_SERVER['HTTP_REFERER_http']) )
	{
		$sReferer = $_SERVER['HTTP_REFERER_http'];

	}ElseIf ( isset($_SERVER['HTTP_REFERER']) )
	{
		$sReferer = $_SERVER['HTTP_REFERER'];
	}

	If ( ( isset($_COOKIE["GAAuto1"]) ) && ( $_COOKIE["GAAuto1"] == "Y" ) ) {
		$aVariables[0]	= "sReferer";
		$aValues[0]		= rawurlencode($sReferer);
		header( 'location:DisableAutoLogin.php?' . DOMAIN_Link("G") );
	}Else{
		setcookie("GAL1", "", mktime(12,0,0,1, 1, 1990), "/", $_SERVER["SERVER_NAME"], 0);
		setcookie("GAP1", "", mktime(12,0,0,1, 1, 1990), "/", $_SERVER["SERVER_NAME"], 0);
		setcookie("GAA1", "", mktime(12,0,0,1, 1, 1990), "/", $_SERVER["SERVER_NAME"], 0);
		
		setcookie("GAL1", "", -1, "/", $_SERVER["SERVER_NAME"], 0);
		setcookie("GAP1", "", -1, "/", $_SERVER["SERVER_NAME"], 0);
		setcookie("GAA1", "", -1, "/", $_SERVER["SERVER_NAME"], 0);
		
		$bHasAccount = False;
		
		// unfortunately there is a PHP/IIS bug that prevents us from using the "Header" function to send a redirect header
		If ( isset($_SERVER["HTTP_REFERER"]) ) {
			?>
			<script language='JavaScript1.2' type='text/javascript'>
			
				document.location = "<?=$sReferer?>";
			
			</script>
			<?php
			ob_flush();
			exit;
		}Else{
			?>
			<script language='JavaScript1.2' type='text/javascript'>
			
				document.location = "/";
			
			</script>
			<?php
			ob_flush();
			exit;
		}
	}
?>