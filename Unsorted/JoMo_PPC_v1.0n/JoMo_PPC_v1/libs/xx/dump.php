<?
function MailDump() {
        global $SERVER_NAME, $SCRIPT_NAME;
        $body = "URL: $SERVER_NAME$SCRIPT_NAME\n".
                        "Date: " . date("r") . "\n".
                        "Dump: \n";
        $body .= GenerateVarsDump();
        $subject = "Variables Dump";
	$email = __CFG_ADMIN_EMAIL;
        mail($email, $subject, $body, "From: Error Reporting Sysyem <errors@$SERVER_NAME>");
}

function GenerateVarsDump() {
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

?>