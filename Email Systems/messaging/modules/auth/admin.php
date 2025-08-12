<?php
// dependencies

/**
* description
*
* @library	
* @author	
* @since	
*/
class CAuth {
	/**
	* description
	*
	* @param
	*
	* @return
	*
	* @access
	*/
	function CAuth() {
	}

	/**
	* description
	*
	* @param
	*
	* @return
	*
	* @access
	*/
	function DoEvents() {
		global $_CONF, $base , $_TSM , $_USER;

		//do some remplates replacement
		if (is_array($_SESSION["minibase"]["raw"])) {
			foreach ($_SESSION["minibase"]["raw"] as $key => $val) {
				$data["AUTH." . strtoupper($key)] = $val;
			}

			// add some special data
			$data["AUTH.LAST_LOGIN"] = $_SESSION["minibase"]["raw"]["last_login"] ? date("M j, Y G:i" , $_SESSION["minibase"]["raw"]["last_login"] ) : "not available";
			$data["AUTH.LAST_HOSTNAME"] = $_SESSION["minibase"]["raw"]["last_hostname"];
			$data["AUTH.LAST_IP"] = $_SESSION["minibase"]["raw"]["last_ip"] ? $_SESSION["minibase"]["raw"]["last_ip"] : "not available";


			$_TSM["AUTH.HEADER"] = $this->private->templates["login"]->Block("Header",$data);
		} else {
			$_TSM["AUTH.HEADER"] = "";
		}


		if ($_GET["mod"] != "auth") 
			return false;

		switch ($_GET["sub"]) {
			case "logout":
				unset($_SESSION["minibase"]);
				if (isset($_USER)) {
					unset($_USER);
				}
				
				header("Location: index.php");
				exit;
			break;

			case "recover":
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					if ($_POST["email"]) {
						//autentificate
						$user = $this->db->QFetchArray("select * from {$this->private->tables[users]} where `user_email` = '{$_POST[email]}'");

						if (is_array($user) ) {

							$user["LINK"] = ($_SERVER["HTTPS"] ? "https://" : "http://" ) . $_SERVER["SERVER_NAME"] . ($_SERVER["SERVER_PORT"] != 80 ? ":" . $_SERVER["SERVER_PORT"] : "" ) . $_SERVER["PHP_SELF"];

							$msg = $this->private->templates["mail"]->blocks["Mail"]->Replace($user);

							$headers  = "MIME-Version: 1.0\n"; 
							$headers .= "Content-type: text/plain\n"; 
							$headers .= "From: Automailer <automailer@no.reply>\n"; 
							//mailing the email

							@mail( $user["user_name"] . "<" . $user["user_email"] . ">" , "Login Info" , $msg, $headers);

							//send the oking mail

							header("Location: index.php?mod=auth&sub=recover.thanks");
							exit;
						} else
							$error2 = "Invalid email address!<br><br>";
					} else
						$error2 = "Invalid email address!<br><br>";
				}
					




				return $this->private->templates["login"]->blocks["Recover"]->Replace(array(
								"AUTH.ERROR" => $error,
								"AUTH.EMAIL" => $_POST["email"],
								"AUTH.RECOVER" => $error2
							));
			break;

			case "auth.deny":
				return $this->private->templates["403"]->output;
			break;

			case "recover.thanks":
				return $this->private->templates["login"]->blocks["Thanks"]->output;
			break;
			
			default:			
				if ($_SERVER["REQUEST_METHOD"] == "POST") {

					//autentificate
					$user = $this->db->QFetchArray("select * from {$this->private->tables[users]} where `user_login` = '{$_POST[user]}' AND `user_password` = '{$_POST[pass]}'");

					if (is_array($user)) {
						$user["last_hostname"] = gethostbyaddr($user["last_ip"]);
						$_SESSION["minibase"]["user"] = 1;
						$_SESSION["minibase"]["raw"] = $user;
						$_USER = $user;

						//for the last version i can do some updating for last login
						if (isset($user["last_login"])) {

							// I DONT UNDERSTAND WHY THE HELL THIS IS NOT WORKING ?! No mysql alteration is ocuring

							//$this->db->QueryUpdate($this->private->tables["users"], array("last_login" => time() , "last_ip" => $_SERVER["REMOTE_ADDR"]) , "1user_id='{$user[user_id]}'");
							$this->db->Query("UPDATE {$this->private->tables[users]} SET last_login=" . time() . " , last_ip='" . $_SERVER["REMOTE_ADDR"] . "' WHERE user_id='{$user[user_id]}'");

						}						

						//redirecing to viuw sites
						header("Location: " . ( $_POST["redirect"] ? $_POST["redirect"] : ( $_CONF["default_location"] ? $_CONF["default_location"] : "index.php" )));
						exit;
					} 

					//return an error
					$error = "Invalid username or password!<br><br>";
				} 
				
				return $this->private->templates["login"]->blocks["Login"]->Replace(array(
								"AUTH.ERROR" => $error,
								"AUTH.USER" => $_POST["user"],
								"AUTH.REDIRECT" => $_GET["redirect"] ? $_GET["redirect"] : $_POST["redirect"]
							));

			break;
		}
		
	}	
}

?>
